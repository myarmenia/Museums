<?php

namespace App\Http\Controllers\Admin\EducationalPrograms\Reserve;

use App\Http\Controllers\Controller;
use App\Traits\Museum\EducationalProgram;
use Illuminate\Http\Request;

class GetDayReservationsController extends Controller
{
  use EducationalProgram;
  public function __invoke($date)
  {


    $reservetions = $this->getDayReservetions($date);
dd($reservetions);
    if ($reservetions) {

      return redirect()->route('educational_programs_list');
    }
  }
}
