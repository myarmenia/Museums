<?php
namespace App\Traits\Purchase;

use App\Models\PersonPurchase;

trait WithoutPayment
{
  public function withoutPayment($purchase)
  {
    return true;
    // return PersonPurchase::create($person);
  }



}
