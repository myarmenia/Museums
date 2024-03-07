<?php

namespace App\Http\Controllers\Admin\EducationalPrograms\Reserve;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AcessInItem;
use App\Http\Requests\EducationalPrograms\EducationalProgramReserveRequest;
use App\Models\EducationalProgramReservation;
use App\Traits\UpdateTrait;
use Illuminate\Http\Request;

class ReserveUpdateController extends Controller
{
  use UpdateTrait;
  function __construct()
  {
      $parameter = $this->model();
      $this->middleware(AcessInItem::class . ':' . $parameter);
  }
  public function model()
  {
      return EducationalProgramReservation::class;
  }

  public function __invoke(EducationalProgramReserveRequest $request, string $id){


    if ($request->educational_program_id == "null_id") {
        $request['educational_program_id'] = null;
    }

    $reservetion = $this->itemUpdate($request, $id);

    if($reservetion){

      return redirect()->back();
    }
  }

}
