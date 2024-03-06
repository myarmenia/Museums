<?php

namespace App\Http\Controllers\Admin\EducationalPrograms;

use App\Http\Controllers\Controller;
use App\Http\Resources\EducationalPrograms\GetCalendarResource;
use App\Traits\Museum\GetCalendarData;
use Illuminate\Http\Request;

class GetCalendarDataController extends Controller
{
    use GetCalendarData;
    public function __invoke()
    {
      // $student_attendance = $this->getAll($id);
        $data = $this->getData();


        $result = GetCalendarResource::collection($data);

        if ($result) {
          return response()->json($result);
        }
    }
}
