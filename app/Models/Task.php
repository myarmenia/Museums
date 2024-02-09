<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function lessons(){

      return $this->belongsTo(Lesson::class,'lesson_id');
    }

    public function task_translations(){

      return  $this->hasMany(TaskTranslation::class);

    }
    public function translation($lang){

      return $this->hasOne(TaskTranslation::class)->where('lang', $lang)->first();
   }
}
