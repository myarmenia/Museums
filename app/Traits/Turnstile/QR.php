<?php
namespace App\Traits\Turnstile;

use App\Jobs\UpdateQRStatusJob;
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

    $online = true;

    if (!$data['online']) {
      $online = false;

      foreach ($data['qr'] as $value) {
        $data_qr = explode('#', $value);
        $check_qr = $this->checkQR($value, $data['mac'], $online);

        if ($check_qr === 'invalid mac') {
          break;
        }

        if (!$check_qr) {
          $this->addQrBlackList($data_qr[0], $data['mac']);
        }

      }

      return $check_qr === 'invalid mac' ? 'invalid mac' : 'process finished';

    }

    return $this->checkQR($data['qr'][0], $data['mac'], $online);


  }

  public function checkQR($data_qr, $mac, $online)
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


    $turnstile = Turnstile::museum($mac)->first();  // for ticket_redemption_time
    $museum_ids = Turnstile::museum($mac)->pluck('museum_id')->toArray();  // 20.09.24


    if (count($museum_ids) > 0) {
      $museum_id = $turnstile->museum_id;
      $end_date = null;

      $qr = TicketQr::valid($qr_token, $museum_ids)->first();

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
          $check_ticket_accesses = $this->checkTicketAccesses($qr, null, $qr_reade_date, $online, $turnstile->ticket_redemption_time);

          return $check_ticket_accesses ? true : false;
        } else {
          $this->changeTicketStatus($qr, $date);
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

    $checked_date_plus_one_year = $type != 'event' && $type != 'event-config' ? $checked_date->modify('+1 year') : $checked_date;

    return $type != 'event' && $type != 'event-config' ? ($today <= $checked_date_plus_one_year ? true : false) : (($today >= $checked_date && $today <= $checked_end_date) ? true : false);

  }

  public function checkTicketAccesses($qr, $status = null, $date = null, $online, $ticket_redemption_time)
  {

    $new_date = new DateTime();

    $now_date = $date == null ? $new_date->format('Y-m-d H:i:s') : date('Y-m-d H:i:s', $date);

    $status = $qr->type != 'subscription' ? 'used' : 'active';

    if ($online && $ticket_redemption_time != null && $qr->visited_date == null) {
      $update = UpdateQRStatusJob::dispatch($qr->id, $now_date, $status)->delay(now()->addMinutes($ticket_redemption_time));
    } else {
      $update = $qr->update([
        'status' => $status,
        'visited_date' => $now_date,
      ]);
    }

    $update = $qr->update([
      'visited_date' => $now_date,
    ]);

    return $update;
  }

  public function changeTicketStatus($qr, $date)
  {
    $today = new DateTime();
    $today->setTime(0, 0, 0);

    $checked_date = new DateTime($date);
    $checked_date->setTime(0, 0, 0);

    if (($qr->type == 'event' || 'event-config') && $today < $checked_date) {
      $status = 'active';
    } else {
      $status = 'expired';
    }

    $update = $qr->update([
      'status' => $status,
    ]);

    return $update;
  }


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
