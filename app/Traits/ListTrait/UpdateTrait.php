<?php
 namespace App\Traits\ListTrait;

use App\Models\Image;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

 trait UpdateTrait{
  // abstract function model();
  // abstract function validationRules($resource_id = 0);
  public function getUpdate(Request $request, $mainTable, $translationTable, $item_id,$id){

    $data = $request->except(['translate','photo','_method']);
// dd($data);
    $item = Product::where('id',$id)->first();

    $item->update($data);
    if($item){

      foreach($request['translate'] as $key => $lang){
// dd($id);
        $product=ProductTranslation::where(['product_id'=>$id,'lang'=>$key])->first();
        $product->update($lang);
      }

      if(isset($request['photo'])){

        $image = Image::where('imageable_id',$id)->first();

        if(Storage::exists($image->path)){
          Storage::delete($image->path);
          $image->delete();
        }
        $path = FileUploadService::upload($request['photo'], $mainTable.'/'.$id);
        $photoData = [
            'path' => $path,
            'name' => $request['photo']->getClientOriginalName()
        ];




        $item->images()->create($photoData);
      }

      return true;
    }

  }

 }
