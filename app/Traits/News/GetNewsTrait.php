<?php

namespace App\Traits\News;
use App\Models\News;
use App\Models\NewsTranslations;
use Illuminate\Http\Request;

trait GetNewsTrait
{
    public function getAllNews($request, $addressRequest)
    {
 
      $data = News::where('id','>',0);

      if($addressRequest=='web'){
        $lang = "am";

      }else{

        $lang = session('languages');
      }

      $news_translation = NewsTranslations::where('lang',$lang);

            if(isset($request['title'])){

              $news_translation = $news_translation->where('title', 'like', '%' . $request['title'] . '%');

            }

            if(isset($request['from_created_at'])){
              $news_translation = $news_translation->whereDate('created_at', '>=', $request['from_created_at']);


            }
            if(isset($request['to_created_at'])){

              $news_translation = $news_translation->whereDate('created_at', '<=', $request['to_created_at']);

            }

            $news_translation=$news_translation->pluck('news_id')->toArray();


            $data = $data->whereIn('id', $news_translation);



      return $data;

    }

}
