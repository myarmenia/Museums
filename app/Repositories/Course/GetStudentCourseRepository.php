<?php
namespace App\Repositories\Course;

use App\Interfaces\Course\GetStudentCourseInterface;
use App\Models\UserCourseMenegment;
use App\Traits\Course\CourseLanguageTrait;
use App\Traits\Users\GetUserTrait;

class GetStudentCourseRepository implements GetStudentCourseInterface
{
  use GetUserTrait, CourseLanguageTrait;

  public function GetStudentCourse(int $id, int $course_language_id){

    return UserCourseMenegment::where(['user_id' => $id, 'course_language_id' => $course_language_id])->first();
  }
}
