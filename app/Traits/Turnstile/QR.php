<?php
namespace App\Traits\Turnstile;
use App\Models\TicketAccess;
use App\Models\TicketQr;
use App\Models\Turnstile;
use DateTime;
use Illuminate\Support\Carbon;

trait QR
{
  public function check($data)
  {


      $data_qr = explode('#', $data['qr']);
      $qr_token =  $data_qr[0];
      $qr_hash =  $data_qr[1];

      // dd(hash('sha256', '75902282C81B39'));

      if( hash('sha256', $qr_token) !== $qr_hash){
        return 'invalid scan';
      }

      $turnstile = Turnstile::museum($data['mac'])->first();

      if($turnstile){
          $museum_id = $turnstile->museum_id;

          $qr = TicketQr::valid($qr_token, $museum_id)->first();
          // $qr = TicketQr::where([
          //   "token" => $data['qr'],
          //   "museum_id" => $data['museum_id'],
          //   'status' => 'active'
          // ])->first();

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


      }

    return 'invalid mac';
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



    if ($qr->type == 'subscription') {
        $created_at = $qr->created_at;

        $access_period = Carbon::parse($created_at)->addDays(365); // Добавляем 365 days
        // $access_period = $now->modify('+365 days');

    }
    else{

        $access_period = $now->modify('+8 hours'); // Добавляем 8 часов
    }

    $qr_access = TicketAccess::where('ticket_qr_id', $qr->id)->first();


      if(!$qr_access){
        $data = [
          'ticket_qr_id' => $qr->id,
          'museum_id' => $qr->museum_id,
          'visited_date' => $now_date,
          'access_period' => $access_period

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
