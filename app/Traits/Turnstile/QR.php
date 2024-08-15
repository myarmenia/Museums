<?php
namespace App\Traits\Turnstile;

use App\Models\QrBlackList;
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


    if (!$data['online']) {

      foreach ($data['qr'] as $value) {
        $data_qr = explode('#', $value);
        $check_qr = $this->checkQR($value, $data['mac']);

        if ($check_qr === 'invalid mac') {
          break;
        }

        if (!$check_qr) {
          $this->addQrBlackList($data_qr[0], $data['mac']);
        }

      }

      return $check_qr === 'invalid mac' ? 'invalid mac' : 'process finished';

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

  public function checkQR($data_qr, $mac)
  {
    // example
    // "893AD83C829E71#6e2dd53cc2adeaa52123d424da1451d9e23d3b1340d8cf7f747e71af2b5f274f#1723445813#2024-08-12 09:20:36"
    // "qr-token#qr-hash(sha256)#timestampe-qr-created_at#qr-reader-date"

    $data_qr = explode('#', $data_qr);

    $qr_token = $data_qr[0];
    $qr_hash = count($data_qr) > 1 ? $data_qr[1] : null;
    $qr_reade_date = count($data_qr) > 3 && count($data_qr) != '_' ? $data_qr[3] : null;

    if ($qr_hash != null && $qr_hash !== '_' && hash('sha256', $qr_token) !== $qr_hash) {

      return 'invalid scan';
    }

    $turnstile = Turnstile::museum($mac)->first();

    if ($turnstile) {
      $museum_id = $turnstile->museum_id;
      $end_date = null;

      $qr = TicketQr::valid($qr_token, $museum_id)->first();

      if ($qr) {
        if ($qr->type == 'event-config') {

            $date = $qr->event_config->day;
            $end_date = $qr->event_config->day;

        } elseif ($qr->type == 'event') {
            $date = $qr->event->start_date;
            $end_date = $qr->event->end_date;

        } elseif ($qr->type == 'corporative') {
          $date = $qr->corporative->created_at;

        } else {
          $date = $qr->created_at;
        }

        $check_date = $this->checkDate($date, $end_date, $qr->type);


        if ($check_date) {
          $check_ticket_accesses = $this->checkTicketAccesses($qr, null, $qr_reade_date);

          return $check_ticket_accesses ? true : false;
        }
      }

      return false;
    }

    return 'invalid mac';
  }

  public function checkDate($date, $end_date = null, $type)
  {
    $checked_date = new DateTime($date);
    $checked_date->setTime(0, 0, 0);

    $checked_end_date = new DateTime($end_date);
    $checked_end_date->setTime(0, 0, 0);

    $today = new DateTime();
    $today->setTime(0, 0, 0);

    $checked_date_plus_one_year = $type != 'event' ? $checked_date->modify('+1 year') : $checked_date;
    // $checked_date_plus_one_year = $type != 'event' ? $checked_date->modify('+1 year') : $checked_date;


    return $type != 'event' ? ($today <= $checked_date_plus_one_year ? true : false) : (($today >= $checked_date && $today <= $checked_end_date) ? true : false);

  }

  public function checkTicketAccesses($qr, $status = null, $date = null)
  {

      $new_date = new DateTime();
      $date = $date == null ? $new_date : $new_date->setTimestamp($date);

      $date = $date->modify('+4 hours');  // +4 hour to UTC
      $now_date = $date->format('Y-m-d H:i:s');

      $update = $qr->update([
        'status' => 'used',
        'visited_date' => $now_date,
      ]);

      return $update;
  }


  // public function checkTicketAccesses($qr, $status = null, $date = null)
  // {

  //   $new_date = new DateTime();
  //   $date = $date == null ? $new_date : $new_date->setTimestamp($date);

  //   $date = $date->modify('+4 hours');  // +4 hour to UTC
  //   $now_date = $date->format('Y-m-d H:i:s');

  //   if ($qr->type == 'subscription') {
  //     $created_at = $qr->created_at;

  //     $access_period = Carbon::parse($created_at)->addDays(365); // Добавляем 365 days
  //     // $access_period = $now->modify('+365 days');

  //   } else {

  //     $access_period = $date->modify('+8 hours'); // Добавляем 8 часов
  //   }

  //   $qr_access = TicketAccess::where('ticket_qr_id', $qr->id)->first();

  //   if (!$qr_access) {
  //     $data = [
  //       'ticket_qr_id' => $qr->id,
  //       'museum_id' => $qr->museum_id,
  //       'visited_date' => $now_date,
  //       'access_period' => $access_period

  //     ];

  //     if ($status) {
  //       $data['status'] = $status;
  //     }

  //     TicketAccess::create($data);
  //     return true;

  //   } else {
  //     return $now_date <= $qr_access->access_period ? true : false;
  //   }


  // }

  public function addQrBlackList($qr, $mac)
  {

    $qr_from_black_list = QrBlackList::where('qr', $qr)->first();

    if ($qr_from_black_list == null) {
      QrBlackList::create(['qr' => $qr, 'mac' => $mac]);
    }

  }

  public function getSingleMuseumQrBlackList($mac)
  {

      $list = QrBlackList::where('mac', $mac)->orderByDesc('id')->take(50)->pluck('qr')->toArray();
      $latestIds = QrBlackList::where('mac', $mac)->orderByDesc('id')->take(50)->pluck('id');

      // Удаляем все записи, кроме тех, у которых ID в списке последних 50
      QrBlackList::where('mac', $mac)
            ->whereNotIn('id', $latestIds)
            ->delete();

      $data['black_list'] = $list;

      return $data;

  }


}
