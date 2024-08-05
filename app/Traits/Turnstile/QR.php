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
      // dd(count($data['qr']));


      if(!$data['online'] ){

        foreach ($data['qr'] as $value) {
            $data_qr = explode('#', $value);
            $check_qr = $this->checkQR($value, $data['mac']);

            // $qr_token = $data_qr[0];
            // $qr_hash = count($data_qr) > 1 ? $data_qr[1] : null;
            // $qr_reade_date = count($data_qr) > 2 ? $data_qr[2] : null;
        }
        return 'process finished';
      }

      return $this->checkQR($data['qr'][0], $data['mac']);



    // if($qr_hash != null && hash('sha256', $qr_token) !== $qr_hash){
      //   return 'invalid scan';
      // }

      // $turnstile = Turnstile::museum($data['mac'])->first();

      // if($turnstile){
      //     $museum_id = $turnstile->museum_id;

      //     $qr = TicketQr::valid($qr_token, $museum_id)->first();


      //     if($qr){
      //         if($qr->type == 'event'){
      //           $date = $qr->event_config->day;
      //         }
      //         elseif($qr->type == 'corporative'){
      //           $date = $qr->corporative->created_at;

      //         }
      //         else{
      //           $date = $qr->created_at;
      //         }

      //         $check_date = $this->checkDate($date, $qr->type);


      //         if($check_date){
      //           $check_ticket_accesses = $this->checkTicketAccesses( $qr, null);

      //           return $check_ticket_accesses ? true : false;
      //         }
      //     }

      //     return false;


      // }

    // return 'invalid mac';
  }

  public function checkQR($data_qr, $mac){

      $data_qr = explode('#', $data_qr);

      $qr_token = $data_qr[0];
      $qr_hash = count($data_qr) > 1 ? $data_qr[1] : null;
      $qr_reade_date = count($data_qr) > 2 ? $data_qr[2] : null;

      if($qr_hash != null && hash('sha256', $qr_token) !== $qr_hash){
        return 'invalid scan';
      }

      $turnstile = Turnstile::museum($mac)->first();

      if($turnstile){
          $museum_id = $turnstile->museum_id;

          $qr = TicketQr::valid($qr_token, $museum_id)->first();


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
                $check_ticket_accesses = $this->checkTicketAccesses( $qr, null, $qr_reade_date);

                return $check_ticket_accesses ? true : false;
              }
          }

          return false;
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


  public function checkTicketAccesses($qr, $status = null, $date = null){

    $now = new DateTime(); // Получаем текущее время
    $now_date = $date ? $date : $now->format('Y-m-d H:i:s');



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
