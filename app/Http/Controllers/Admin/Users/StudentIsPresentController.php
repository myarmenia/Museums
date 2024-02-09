<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\Users\StudentIsPresentService;
use App\Traits\Users\GetUserTrait;
use App\Traits\Users\StudentAttendanceTrait;

class StudentIsPresentController extends Controller
{
    use GetUserTrait, StudentAttendanceTrait;
    public function index($id){

        $user = $this->getUser($id);
        $student_attendance = $this->getLastRow($id);

        // $user = User::find($id);
        // $lesson_quantity = $user->lesson_quantity  + 1;

        // $update = $user->update(['lesson_quantity' => $lesson_quantity]);
        $lesson_quantity = StudentIsPresentService::update($user, $student_attendance);

        if($lesson_quantity){
          return response()->json(['result' => $lesson_quantity]);
        }


    }
}
