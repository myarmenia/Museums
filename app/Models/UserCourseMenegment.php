<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCourseMenegment extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function course_languages(){

      return  $this->belongsTo(CourseLanguage::class,'course_language_id');
    }

    public function lessons(){

      return  $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function users(){

      return  $this->belongsTo(User::class, 'user_id');
    }
}
