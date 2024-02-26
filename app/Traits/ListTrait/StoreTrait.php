<?php
 namespace App\Traits\ListTrait;

use App\Models\Product;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

 trait StoreTrait{
  // abstract function model();
  // abstract function validationRules($resource_id = 0);
  public function getStore(Request $request,$mainTable,$translationTable,$item_id){
// dd($mainTable);
    $data = $request->except(['translate','photo']);
// dd($data);
    // $item=Product::create($data);
    $item=DB::table($mainTable)->insertGetId($data);

    if($item){
      foreach($request['translate'] as $key => $lang){

        $lang[$item_id] =  $item;
        $lang['lang'] = $key;

        $itemTranslate =DB::table($translationTable)->insertGetId($lang);

      }

    if($photo = $request['photo'] ?? null){
      $path = FileUploadService::upload($request['photo'],$mainTable.'/'.$item);
        $photoData = [
          'path' => $path,
          'name' => $photo->getClientOriginalName()
      ];

      // DB::table($translationTable)->find($item)->images()->create($photoData);
      $table=Product::find($item)->images()->create($photoData);
    }

      return true;
    }

  }

 }
