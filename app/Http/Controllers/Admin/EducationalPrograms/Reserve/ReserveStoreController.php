<?php

namespace App\Http\Controllers\Admin\EducationalPrograms\Reserve;

use App\Http\Controllers\Controller;
use App\Http\Requests\EducationalPrograms\EducationalProgramReserveRequest;
use App\Models\EducationalProgramReservation;
use App\Traits\StoreTrait;
use Illuminate\Http\Request;

class ReserveStoreController extends Controller
{
  use StoreTrait;
  public function model()
  {
      return EducationalProgramReservation::class;
  }

  public function __invoke(EducationalProgramReserveRequest $request){

   
    if ($request->educational_program_id == "null_id") {
        $request['educational_program_id'] = null;
    }

    $reservetion = $this->itemStore($request);

    if($reservetion){

      return redirect()->route('educational_programs_list');
    }
  }
}
