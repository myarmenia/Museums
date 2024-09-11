<?php

namespace App\Traits;

use App\Models\EventConfig;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Services\FileUploadService;
use App\Services\Log\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait UpdateTrait
{
  abstract function model();

  public function itemUpdate(Request $request, $id)
  {

    $data = $request->except(['translate', 'photo', '_method']);

    $className = $this->model();

    if (class_exists($className)) {

      $model = new $className;
      $relation_foreign_key = $model->getForeignKey();

      $table_name = $model->getTable();

      $item = $model::where('id', $id)->first();

      if ($request['translate'] != null && $table_name == 'other_services') {

          $type = $this->makeOtherServiceType($request['translate']['en']['name']);
          $data['type'] = $type;
      }

      $item->update($data);

      if ($item) {
        if ($request['translate'] != null) {
          foreach ($request['translate'] as $key => $lang) {

            $item->item_translations()->where([$relation_foreign_key => $id, 'lang' => $key])->update($lang);
          }

        }

        if (isset($request['photo'])) {


          $image = Image::where(['imageable_id' => $id, 'imageable_type' => $className])->first();


          if (Storage::exists($image->path)) {

            Storage::delete($image->path);

            $image->delete();
          }
          $path = FileUploadService::upload($request['photo'], $table_name . '/' . $id);
          $photoData = [
            'path' => $path,
            'name' => $request['photo']->getClientOriginalName()
          ];

          $item->images()->create($photoData);

        }

        if($className=="App\Models\Event"){
          $item_config=self::update_config($item);
        }

        LogService::store($request->all(), Auth::id(), $table_name, 'update');

        return true;
      }
    } else {

      return false;
    }
  }
  public function update_config($item){
    // dd($item);
    $event_config=EventConfig::where('event_id',$item->id)->get();
    foreach($event_config as $conf){
      $conf->visitors_quantity_limitation=$item->visitors_quantity_limitation;
      $conf->price=$item->price;
      $conf->save();

    }
  }

  public function makeOtherServiceType($name_en)
  {
    $type = Str::slug($name_en, '_');
    return strtolower($type);
  }
}
