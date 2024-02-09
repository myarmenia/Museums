<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $guarded=[];
    protected $hidden = ["created_at", "updated_at"];


    public function course_languages(){

      return $this->belongsTo(CourseLanguage::class,'course_language_id');
    }
    public function lesson_translations(){

      return  $this->hasMany(LessonTranslation::class);

    }
    public function tasks(){

      return  $this->hasMany(Task::class);

    }
    public function user_course_menegments(){

      return  $this->hasOne(UserCourseMenegment::class);

    }
    public function translation($lang){
      
      return $this->hasOne(LessonTranslation::class)->where('lang', $lang)->first();
   }


}
