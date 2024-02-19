<?php
namespace App\Repositories\News;

use App\Interfaces\News\NewsInterface;
use App\Models\News;
use App\Models\News\NewsCategoryTranslations;
use App\Models\NewsTranslations;

class NewsRepository implements NewsInterface
{

    public function createNews($userId)
    {
;
        $news = News::create([
            "user_id" => $userId
        ]);

        return $news;
    }

    public function createNewsData($data)
    {

        return NewsTranslations::insert($data);
    }


    public function getNews()
    {
        return News::with(['images',  'translations' ])
            ->paginate(10);

    }





    public function getNewsById($id)
    {

          $news= News::where('id', $id)->with([
            'images',
            'news_translations'=>function ($q) {
              $q->where('lang', session('languages'));
            }
            ])->first();

            return $news;
    }


    public function editNews($id){
      $news = News::where('id',$id)->with('news_translations')->first();


      return $news;
    }




}
