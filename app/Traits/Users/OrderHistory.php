<?php
namespace App\Traits\Users;

use App\Models\User;

trait OrderHistory
{
  public function getorderHistory()
  {

      $user = auth('api')->user();
      $purchase = $user->purchases;
      $payment = $purchase->pluck('payment');
      // dd($payment);
      // $purchase_items = $payment->pluck('purchase')->pluck('purchased_items')->flatten();
    $purchase_items = $payment->pluck('purchase')->pluck('purchased_items')->flatten();

    dd($purchase_items);

  }
}
