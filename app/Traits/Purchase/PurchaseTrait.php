<?php
namespace App\Traits\Purchase;
use App\Models\Purchase;


trait PurchaseTrait
{
  public function createPerson($data)
  {
    return Purchase::create($data);
  }



}
