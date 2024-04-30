<?php
namespace App\Traits\Turnstile;
use App\Models\TicketAccess;
use App\Models\TicketQr;
use DateTime;

trait QR
{
  public function check($data)
  {


      // $turnstile_user = auth('turnstile')->user();
      $qr = TicketQr::where([
        "token" => $data['token'],
        "museum_id" => $data['museum_id'],
        'status' => 'active'
      ])->first();

      if($qr){
        if($qr->type == 'event'){
          $date = $qr->event_config->day;
        }
        elseif($qr->type == 'corporative'){
          $date = $qr->corporative->created_at;

        }
        else{
          $date = $qr->created_at;
        }

        $check_date = $this->checkDate($date, $qr->type);

        if($check_date){
          $check_ticket_accesses = $this->checkTicketAccesses( $qr, null);

          return $check_ticket_accesses ? true : false;
        }


    }

    return false;
  }

  public function checkDate($date, $type){
      $checked_date = new DateTime($date);
      $checked_date->setTime(0, 0, 0);

      $today = new DateTime();
      $today->setTime(0, 0, 0);

      $checked_date_plus_one_year = $type != 'event' ? $checked_date->modify('+1 year') : $checked_date;

      return $type != 'event' ? ($today <= $checked_date_plus_one_year ? true : false) : ($today == $checked_date_plus_one_year ? true : false);

  }


  public function checkTicketAccesses($qr, $status = null){

    $now = new DateTime(); // Получаем текущее время
    $now_date = $now->format('Y-m-d H:i:s');
    $now_plus_8_hour = $now->modify('+8 hours'); // Добавляем 8 часов
    $qr_access = TicketAccess::where('ticket_qr_id', $qr->id)->first();


      if(!$qr_access){
        $data = [
          'ticket_qr_id' => $qr->id,
          'museum_id' => $qr->museum_id,
          'visited_date' => $now_date,
          'access_period' => $now_plus_8_hour

        ];

        if($status){
            $data['status'] = $status;
        }

        TicketAccess::create($data);
        return true;

      }
      else{
        return $now_date <= $qr_access->access_period ? true : false;
      }


  }




}
