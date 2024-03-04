<?php
namespace App\Traits\Museum;

use App\Models\EducationalProgram as ModelEducationalProgram;
use App\Models\EducationalProgramReservation;

trait GetCalendarData
{
  public function getData()
  {
    return EducationalProgramReservation::where("museum_id", museumAccessId())->get();
  }

}
