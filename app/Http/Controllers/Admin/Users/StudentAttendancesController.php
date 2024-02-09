<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Student\AttendancesResource;
use App\Traits\Users\StudentAttendanceTrait;


class StudentAttendancesController extends Controller
{
    use StudentAttendanceTrait;
    public function index($id){
        $student_attendance = $this->getAll($id);

        // $user = User::find($id);
        // $lesson_quantity = $user->lesson_quantity  + 1;

        // $update = $user->update(['lesson_quantity' => $lesson_quantity]);
        $result = AttendancesResource::collection($student_attendance);
// dd($result);
        if($result){
          return response()->json($result);
        }
    }
}
