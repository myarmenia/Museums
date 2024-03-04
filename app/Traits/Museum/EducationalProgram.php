<?php
namespace App\Traits\Museum;

use App\Models\EducationalProgram as ModelEducationalProgram;
use App\Models\EducationalProgramReservation;

trait EducationalProgram
{
    public function getAllEducationalPrograms()
    {
      return ModelEducationalProgram::where("museum_id", museumAccessId())->latest()->get();
    }

    public function getEducationalProgram($id) {
        return ModelEducationalProgram::where(["id" => $id, "museum_id" => museumAccessId()])->first();
    }

    public function getAllReservetions() {
        return EducationalProgramReservation::where(["museum_id" => museumAccessId()])->first();
    }
}
