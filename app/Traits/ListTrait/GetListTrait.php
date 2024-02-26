<?php
namespace app\Traits\ListTrait;

use App\Models\Product;
use App\Models\ProductTranslation;

trait GetListTrait{

  public function getList($request,$model, $addressRequest){

    $data = Product::where('id','>',0);
// dd($data);
      if($addressRequest=='web'){
        $lang = "am";

      }else{

        $lang = session('languages');
      }

      // $news_translation = ProductTranslation::where('lang',$lang);

      //       if(isset($request['title'])){

      //         $news_translation = $news_translation->where('title', 'like', '%' . $request['title'] . '%');

      //       }

      //       if(isset($request['from_created_at'])){
      //         $news_translation = $news_translation->whereDate('created_at', '>=', $request['from_created_at']);


      //       }
      //       if(isset($request['to_created_at'])){

      //         $news_translation = $news_translation->whereDate('created_at', '<=', $request['to_created_at']);

      //       }

      //       $news_translation=$news_translation->pluck('news_id')->toArray();


      //       $data = $data->whereIn('id', $news_translation);



      return $data;

  }

}
