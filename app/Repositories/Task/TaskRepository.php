<?php

namespace App\Repositories\Task;
use App\Interfaces\Task\TaskRepositoryInterface;
use App\Models\Task;
use App\Models\TaskTranslation;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Storage;

class TaskRepository implements TaskRepositoryInterface
{

  public function allTasks($request){

    $tasks = Task::with('task_translations')->orderBy('id', 'DESC')->paginate(10);

    return $tasks;

  }
  public function storeTask($request){

    $task = Task::create($request->only(['lesson_id','duration']));
      if($request->has('video')){

        $path=FileUploadService::upload($request->video,'lessons/'.$request->lesson_id.'/tasks/'.$task->id);
        $task->video=$path;
        $task->save();
      }

      if($task){
        foreach($request->translate as $key => $lang){

          $request['task_id'] = $task->id;

          $request['description'] = $lang['description'];
          $request['lang'] = $key;

          $task_translation = TaskTranslation::create($request->only(['task_id','description','lang']));

        }

        return true;

      }



  }
  public function editTask($id)
  {

    $task = Task::where('id',$id)->with(['lessons','task_translations'])->first();

    return $task;
  }
  public function updateTasks($request, $id)
  {

    $task = Task::where('id',$id)->with('task_translations')->first();
    if($request->has('video')){
      if(Storage::exists($task->video)){
       
        Storage::delete($task->video);

      }
      $path=FileUploadService::upload($request->video,'lessons/'.$request->lesson_id.'/tasks/'.$task->id);
      $task->video=$path;
      $task->save();
    }
    $task->update($request->only(['lesson_id','duration']));



    if($task){
      foreach($request->translate as $key => $lang){
        $request['task_id'] = $task->id;
        $request['description'] = $lang['description'];
        $request['lang'] = $key;
        $task_translation = TaskTranslation::where([
          ['task_id','=', $id],
          ['lang','=',$key]
        ])->first();

        $task_translation->update($lang);

      }
      return true;

    }

  }


}
