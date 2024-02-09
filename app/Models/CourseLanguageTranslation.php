<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLanguageTranslation extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function course_language(){

      return $this->belongsTo(CourseLanguage::class,'course_language_id');
    }
}
