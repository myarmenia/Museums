<?php
namespace App\Traits\Museum;
use App\Models\Museum;

trait MuseumTrait
{
  public function getAllMuseums()
  {
    return Museum::all();
  }


}
