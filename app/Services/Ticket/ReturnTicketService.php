<?php

namespace App\Services\Ticket;

use App\Models\TicketQr;
use Carbon\Carbon;

class ReturnTicketService
{
  public function checkToken($token)
  {
    $id = substr($token, 0, -5);
    $id = (int) $id;
    $ticket = null;

    $museumId = getAuthMuseumId();

    if($museumId && $id) {
        $dateLimit = Carbon::now()->subDays(15);

        $ticket = TicketQr::where(['id' => $id, 'museum_id' => $museumId ,'status' => TicketQr::STATUS_ACTIVE])->where('created_at', '>=', $dateLimit)->first();
        if($ticket) {
            return ['success' => true, 'data' => $ticket];
        }
    }

    return ['success' => false, 'data' => null];

  }
}
