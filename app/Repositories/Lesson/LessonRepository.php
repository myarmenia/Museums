<?php
namespace App\Repositories\Lesson;

use App\Interfaces\Lesson\LessonRepositoryInterface;
use App\Models\Lesson;
use App\Models\LessonTranslation;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Storage;

class LessonRepository implements LessonRepositoryInterface
{
  public function allLessons()
  {

      return Lesson::with(['course_languages','lesson_translations'])->orderBy('id', 'DESC')->paginate(10);
  }
  public function storeLesson($request)
  {
     $lesson = Lesson::create($request->only(['course_language_id','duration','number']));

     if($request->has('video')){


        $path = FileUploadService::upload($request->video,'lessons/'.$lesson->id);
        $lesson->video = $path;
        $lesson->save();
        if($lesson){
          foreach($request->translate as $key => $lang){

            $request['lesson_id'] = $lesson->id;
            $request['title'] = $lang['title'];
            $request['description'] = $lang['description'];
            $request['lang'] = $key;

            $lessontranslate = LessonTranslation::create($request->only(['lesson_id','title','description','lang']));

          }

          return true;

        }

      }
  }
  public function editLesson($id){

    $lesson = Lesson::where('id',$id)->with(['lesson_translations','course_languages'])->first();

    return $lesson;
  }

  public function updateLesson($request,$id)
  {
      $lesson = $this->editLesson($id);
      if($request->has('video')){

        if(Storage::exists($lesson->video)){
          // dd($image->path);
          Storage::delete($lesson->video);

        }
        $path = FileUploadService::upload($request->video,'lessons/'.$lesson->id);
        $lesson->video = $path;
        $lesson->save();
      }

    $lesson->update($request->only(['course_language_id','duration','number']));

    if($lesson){

      foreach($request->translate as $key => $lang){

        $lesson_translation = LessonTranslation::where([
          ['lesson_id','=', $id],
          ['lang','=',$key]
        ])->first();

          $lesson_translation->update($lang);
      }
      return true;
    }
  }

}
