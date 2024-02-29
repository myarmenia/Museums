<?php
namespace App\Traits\Museum;

use App\Models\EducationalProgram as ModelEducationalProgram;

trait EducationalProgram
{
    public function getAllEducationalPrograms()
    {
      return ModelEducationalProgram::where("museum_id", museumAccessId())->latest()->get();
    }

    public function getEducationalProgram($id) {
        return ModelEducationalProgram::where(["id" => $id, "museum_id" => museumAccessId()])->first();
    }
}
