<?php
namespace App\Traits\Museum;

use App\Models\EducationalProgram as ModelEducationalProgram;
use App\Models\EducationalProgramReservation;
use App\Models\OtherService;

trait OtherServices
{
  public function getAllOtherServices()
  {
    return OtherService::where("museum_id", museumAccessId())->latest()->get();
  }

  public function getOtherService($id)
  {
    return OtherService::where(["id" => $id, "museum_id" => museumAccessId()])->first();
  }

}
