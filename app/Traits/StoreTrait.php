<?php

namespace App\Traits;

use App\Models\Product;
use App\Services\FileUploadService;
use App\Services\Log\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait StoreTrait
{
  abstract function model();
  public function itemStore(Request $request)
  {

    $data = $request->except(['translate', 'photo']);

    $className = $this->model();

    if (class_exists($className)) {

      $model = new $className;
      $relation_foreign_key = $model->getForeignKey();
      $table_name = $model->getTable();

      if (in_array('museum_id', Schema::getColumnListing($table_name))) {
        $data['museum_id'] = museumAccessId();
      }

      if ($request['translate'] != null && $className == 'OtherService') {

          $type = $this->makeOtherServiceType($request['translate']['en']);
          $data['type'] = $type;
      }

      $item = $model::create($data);

      if ($item) {
        if ($request['translate'] != null) {
          foreach ($request['translate'] as $key => $lang) {

            $lang[$relation_foreign_key] = $item->id;
            $lang['lang'] = $key;
            $item->item_translations()->create($lang);
          }
        }

        if ($photo = $request['photo'] ?? null) {
          $path = FileUploadService::upload($request['photo'], $table_name . '/' . $item->id);
          $photoData = [
            'path' => $path,
            'name' => $photo->getClientOriginalName()
          ];

          $item->images()->create($photoData);
        }

        LogService::store($request->all(), Auth::id(), $table_name, 'store');


        return true;
      }
    }
  }


  public function makeOtherServiceType($name_en){
      return Str::slug($name_en, '_');
  }

}
