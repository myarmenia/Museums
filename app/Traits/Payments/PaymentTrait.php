<?php
namespace App\Traits\Payments;
use App\Models\Payment;


trait PaymentTrait
{
  public function addPayment(array $data)
  {

      Payment::create($data);

  }


}
