<?php
 namespace App\Traits;

use App\Models\Image;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait UpdateTrait{
  abstract function model();

  public function itemUpdate(Request $request, $id){

    $data = $request->except(['translate','photo','_method']);

    $className = $this->model();

    if(class_exists($className)) {

      $model = new $className;
      $relation_foreign_key = $model->getForeignKey();
      $table_name = $model->getTable();

      $item = $model::where('id', $id)->first();
      $item->update($data);
      if($item){

        foreach($request['translate'] as $key => $lang){

          $item->item_translations()->where([$relation_foreign_key => $id,'lang' => $key])->update($lang);
        }

        if(isset($request['photo'])){

          $image = Image::where('imageable_id',$id)->first();

          if(Storage::exists($image->path)){
            Storage::delete($image->path);
            $image->delete();
          }
          $path = FileUploadService::upload($request['photo'], $table_name.'/'.$id);
          $photoData = [
              'path' => $path,
              'name' => $request['photo']->getClientOriginalName()
          ];

          $item->images()->create($photoData);
        }

        return true;
      }
    }
    else{
      return false;

    }

  }

}
