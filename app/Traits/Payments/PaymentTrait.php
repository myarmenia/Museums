<?php
namespace App\Traits\Payments;
use App\Models\Payment;


trait PaymentTrait
{
  public function addPayment(array $data)
  {
      Payment::create($data);
  }

  public function getPayment($order_id){

      return Payment::where('payment_order_id', $order_id)->first();
  }

}
