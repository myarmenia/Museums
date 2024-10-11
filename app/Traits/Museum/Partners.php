<?php
namespace App\Traits\Museum;

use App\Models\Partner;

trait Partners
{
  public function getAllPartners()
  {
    return Partner::where("museum_id", museumAccessId())->latest()->get();
  }

  public function getPartner($id)
  {
    return Partner::where(["id" => $id, "museum_id" => museumAccessId()])->first();
  }

}
