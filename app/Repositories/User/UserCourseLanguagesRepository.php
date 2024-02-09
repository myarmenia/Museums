<?php

namespace App\Repositories\User;

use App\Interfaces\User\UserCourseLanguagesInterface;
use App\Models\User;
use App\Models\UserCourseMenegment;

class UserCourseLanguagesRepository implements UserCourseLanguagesInterface
{

  public function getUserCourseLanguages(int $id)
  {  
    return UserCourseMenegment::where('user_id', $id)->pluck('course_language_id')->toArray();
  }
}
