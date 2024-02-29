<?php

namespace App\Http\Controllers\Admin\EducationalPrograms;

use App\Http\Controllers\Controller;
use App\Traits\Museum\EducationalProgram;
use Illuminate\Http\Request;

class EducationalProgramEditController extends Controller
{
  use EducationalProgram;
  public function __invoke($id){

    $program = $this->getEducationalProgram($id);

    if(!$program) {
        return redirect()->back();
    }
    return view("content.educational-programs.edit", compact('program'));
  }
}
