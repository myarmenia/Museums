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

    if ($reservetions) {

      // return response()->json($reservetions);
      return view('components.offcanvas', ['reservetions' => $reservetions]);
    }
  }
}
