<?php

namespace App\Http\Controllers\Admin\EducationalPrograms;

use App\Http\Controllers\Controller;
use App\Http\Requests\EducationalPrograms\Request as EducationalProgramsRequest;
use App\Models\EducationalProgram;
use App\Traits\StoreTrait;
use Illuminate\Http\Request;

class EducationalProgramStoreController extends Controller
{
    use StoreTrait;

    public function model()
    {
      return EducationalProgram::class;
    }

    public function __invoke(EducationalProgramsRequest $request){

      $educational_program = $this->itemStore($request);

      if($educational_program){

        return redirect()->route('educational_programs_list');
      }
    }

}
