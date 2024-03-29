<?php
namespace App\Traits\Purchase;
use App\Models\PersonPurchase;

trait PersonTrait
{
  public function createPerson($person)
  {
      return PersonPurchase::create($person);
  }



}
