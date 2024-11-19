<?php

namespace App\Http\Controllers\Admin\EducationalPrograms;

use App\Http\Controllers\Controller;
use App\Traits\Museum\EducationalProgram;
use Illuminate\Http\Request;

class EducationalProgramCalendarController extends Controller
{
  use EducationalProgram;
  public function __invoke()
  {
    $data = $this->getAllReservetions();
    // dd($data);
    return view("content.educational-programs.calendar",  compact('data'));
  }
}
