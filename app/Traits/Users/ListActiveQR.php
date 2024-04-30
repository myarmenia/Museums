<?php
namespace App\Traits\Users;

use App\Models\TicketQr;
use App\Models\User;

trait ListActiveQR
{
  public function getList()
  {

    $user = auth('api')->user();
    $purchased_item_ides = $user->purchases->where('status', 1)->pluck('purchased_items')->flatten()->pluck('id');

    $list_qr = TicketQr::whereIn('purchased_item_id', $purchased_item_ides)->where('status', 'active')->get();

    return $list_qr;

  }
}
