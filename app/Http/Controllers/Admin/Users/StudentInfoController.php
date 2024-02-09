<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Interfaces\Course\LanguageInterface;
use App\Interfaces\User\UserCourseLanguagesInterface;
use App\Interfaces\User\UserInterface;
use App\Interfaces\User\StudentInfoInterface;
use Illuminate\Http\Request;

class StudentInfoController extends Controller
{
  private StudentInfoInterface $studentInmfoRepository; 

  function __construct(StudentInfoInterface $studentInmfoRepository)
  {
    return $this->studentInmfoRepository = $studentInmfoRepository;    
  }


  public function index(int $id)
  {

    $student = $this->studentInmfoRepository->getUser($id);
    $course_languages = $this->studentInmfoRepository ? $this->studentInmfoRepository->getAll() : null;
    $user_course_langages = $this->studentInmfoRepository ? $this->studentInmfoRepository->getUserCourseLanguages($id) : null;

    return view('content.users.lessons', compact('student', 'course_languages', 'user_course_langages'));
  }
}
