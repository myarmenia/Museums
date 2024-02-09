<?php
namespace App\Repositories\User;

use App\Http\Resources\UserCurrentLessonResource;
use App\Interfaces\User\UserCurrentLessonInterface;
use App\Models\UserCourseMenegment;

// use App\Models\User;


class UserCurrentLessonRepository implements UserCurrentLessonInterface
{

  public function getcurrentlesson()
  {

    $user_id = auth('api')->user()->id;
    // dd($user_id);

    $user_current_lesson = UserCourseMenegment::where('user_id',$user_id)->with('course_languages','lessons','lessons.lesson_translations','lessons.tasks')->get()->last();


    $current_lesson = new UserCurrentLessonResource($user_current_lesson);
    return $current_lesson;

  }
}
