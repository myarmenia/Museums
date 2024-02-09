<?php
namespace App\Repositories\User;

use App\Interfaces\User\StudentInfoInterface;
use App\Models\User;
use App\Models\UserCourseMenegment;
use App\Models\CourseLanguage;

class StudentInfoRepository implements StudentInfoInterface{

    public function getUser(int $id)
    {
        return User::find($id);
    }

    public function getAll(){
        return CourseLanguage::all();
    }

    public function getUserCourseLanguages(int $id)
    {
        return UserCourseMenegment::where('user_id', $id)->pluck('course_language_id')->toArray();

    }
}