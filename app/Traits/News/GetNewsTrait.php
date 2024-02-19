<?php

namespace App\Traits\News;
use App\Models\News;
use App\Models\NewsTranslations;

trait GetNewsTrait
{
    public function getNews($request)
    {

      $data = News::where('id','>',0);
      // $lang = session('languages') ? session('languages'): "am";
      $lang = "am";

      $news_translation = NewsTranslations::where('lang',$lang);
     
            if(isset($request['title'])){
              $news_translation = $news_translation->where('title', 'like', '%' . $request['title'] . '%');
            }
            if(isset($request['from_created_at'])){
              $news_translation = $news_translation->where('created_at', '>=', $request['from_created_at']);
            }
            if(isset($request['to_created_at'])){
              $news_translation = $news_translation->where('created_at', '<=', $request['to_created_at']);
            }
            $news_translation=$news_translation->pluck('news_id')->toArray();

            $data = $data->whereIn('id', $news_translation);

      $data=$data->orderBy('id', 'DESC')->paginate(3)->withQueryString();

      return $data;

    }
}
