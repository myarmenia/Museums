<?php

namespace App\Services;

use App\Models\User;
use App\Models\Project\Project;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DeleteItemService
{
  public static function delete($tb_name, $id)
  {

      $className = 'App\Models\\' . Str::studly(Str::singular($tb_name));
      $model = '';

      if(class_exists($className)) {
          $model = new $className;

      }


      if(!is_string($model)){

          $item = $model->where('id', $id);

          $file_path = '';
          $item_db = $item->first();

          if(isset($item_db->images)){
            Storage::disk('public')->deleteDirectory("$tb_name/$id");

          }
          else{

                if(isset($item_db->logo)){
                    $file_path = $item_db->logo;
                }
                
                if(isset($item_db->video)){
                  $file_path = $item_db->video;
                }
                if(isset($item_db->path)){
                  $file_path = $item_db->path;
                }

                Storage::delete($file_path);
          }


          $delete = $item ? $item->delete() : false;

          return $delete;
      }

  }
}
