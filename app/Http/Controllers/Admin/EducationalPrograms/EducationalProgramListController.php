<?php

namespace App\Http\Controllers\Admin\EducationalPrograms;

use App\Http\Controllers\Controller;
use App\Traits\Museum\EducationalProgram;
use Illuminate\Http\Request;

class EducationalProgramListController extends Controller
{
  use EducationalProgram;
  public function __invoke(){

    $data = $this->getAllEducationalPrograms();

    return view("content.educational-programs.index", compact('data'));
  }
}
