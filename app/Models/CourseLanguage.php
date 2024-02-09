<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLanguage extends Model
{
    use HasFactory;
    protected $guarded=[];

    // public function course_language_translations(){
    //   return  $this->hasMany(CourseLanguageTranslation::class);
    // }

    public function lessons(){
      return  $this->hasMany(Lesson::class);
    }
    public function coursetranslation($lang){

      return $this->hasOne(CourseLanguageTranslation::class)->where('lang', $lang)->first();
   }
    public function user_course_menegments(){
      return  $this->hasMany(UserCourseMenegment::class);
    }

    public function translation()
    {
        return $this->hasMany(CourseLanguageTranslation::class);
    }

}
