<?php

namespace App\Http\Controllers\Admin\EducationalPrograms;

use App\Http\Controllers\Controller;
use App\Http\Requests\EducationalPrograms\Request as EducationalProgramsRequest;
use App\Models\EducationalProgram;
use App\Traits\UpdateTrait;
use Illuminate\Http\Request;

class EducationalProgramUpdateController extends Controller
{
  use UpdateTrait;

  public function model()
  {
    return EducationalProgram::class;
  }
  public function __invoke(EducationalProgramsRequest $request, string $id)
  {

    $educational_program = $this->itemUpdate($request, $id);

    if ($educational_program) {

      return redirect()->back();
    }

  }
}
