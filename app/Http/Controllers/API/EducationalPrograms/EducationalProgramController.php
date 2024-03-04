<?php

namespace App\Http\Controllers\API\EducationalPrograms;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\EducationalPrograms\EducationalProgramsResource;
use App\Traits\Museum\EducationalProgram;
use Illuminate\Http\Request;

class EducationalProgramController extends BaseController
{
    use EducationalProgram;
    public function __invoke($id)
    {

      try {

          $educational_program = $this->getSingleMuseumEducationalProgramsForAPI($id);

          return $this->sendResponse(EducationalProgramsResource::collection($educational_program), 'success');

      } catch (\Throwable $th) {

          return $this->sendError($th->errorInfo, 'error');
      }


    }
}
