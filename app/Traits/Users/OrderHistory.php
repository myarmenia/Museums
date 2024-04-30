<?php
namespace App\Traits\Users;

use App\Models\User;

trait OrderHistory
{
  public function getorderHistory()
  {

      $user = auth('api')->user();
      $purchase = $user->purchases;
      $payment = $purchase->pluck('payment')->where('status', '!=', null);

      $purchase_items = $payment->pluck('purchase')->pluck('purchased_items')->flatten();

      return $purchase_items;

  }
}
