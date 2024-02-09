<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Interfaces\Course\GetStudentCourseInterface;
use App\Interfaces\User\UserInterface;
use App\Services\Users\AddCourseForStudentService;
use Illuminate\Http\Request;


class OpenCourseLanguageForStudentController extends Controller
{
  private GetStudentCourseInterface $getStudentCourseRepository;

  function __construct(GetStudentCourseInterface $getStudentCourseRepository)
  {
    return $this->getStudentCourseRepository = $getStudentCourseRepository;

  }

  public function index(Request $request, $user_id)
  {

      $student = $this->getStudentCourseRepository->getUser($user_id);
      $hasCourse = $this->getStudentCourseRepository->getStudentCourse($user_id, $request->course_language_id);
      $course = $this->getStudentCourseRepository->getCourseLanguage($request->course_language_id);

      $add_course_for_student = AddCourseForStudentService::add_course($student, $hasCourse, $course);
      
      return redirect()->back()->with('message', $add_course_for_student);
  }
}
