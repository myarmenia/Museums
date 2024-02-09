<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Services\Users\OpenNextLessonService;
use App\Traits\Users\GetUserTrait;
use App\Traits\Course\CourseLanguageTrait;
use App\Traits\Users\UserCourseMenegmentTrait;
use Illuminate\Http\Request;
use App\Http\Resources\Student\DashboardResource;

class OpenNextLessonController extends Controller
{
    use GetUserTrait, CourseLanguageTrait, UserCourseMenegmentTrait;

    public function index(Request $request){

        $user = $this->getUser($request->user_id);
        $course = $this->getCourseLanguage($request->course_language_id);
        $course_menegment = $this->getUserCourseMenegment($request->user_id, $request->course_language_id);

        $next_lesson_number = OpenNextLessonService::open_next_lesson($user, $course, $course_menegment);

        return response()->json(['result' => $next_lesson_number]);

    }
}
