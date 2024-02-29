<?php

namespace App\Http\Controllers\Admin\EducationalPrograms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EducationalProgramEditController extends Controller
{
  public function __invoke(){

    return view("content.educational-programs.create");
}
}
