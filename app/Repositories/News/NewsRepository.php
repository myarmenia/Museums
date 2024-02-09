<?php
namespace App\Repositories\News;

use App\Interfaces\News\NewsInterface;
use App\Models\News\News;
use App\Models\News\NewsCategory;
use App\Models\News\NewsCategoryTranslations;
use App\Models\News\NewsTranslations;

class NewsRepository implements NewsInterface
{

    public function createNews($categoryId)
    {
        $news = News::create([
            "news_category_id" => $categoryId
        ]);

        return $news;
    }

    public function createNewsData($data)
    {
        return NewsTranslations::insert($data);
    }


    public function getNewsByCategories()
    {
        return News::with(['images', 'category', 'translations' ])
            ->paginate(10)
            ->groupBy('news_category_id');
    }

    public function getNewsByCategoryType($id)
    {
        return News::where('news_category_id', $id)->with([
            'images',
            'category',
            'translations' => function ($q) {
                $q->where('lang', session('languages'));
            }
        ])
            ->paginate(12);
    }

    public function getCategoryNameById($id)
    {
        return  NewsCategoryTranslations::where('lang', session('languages'))
            ->where('news_category_id', $id)->first()->name;
    }

    public function getNewsById($id)
    {
        return News::where('id', $id)
            ->with([
                'images',
                'category.translations' => function ($q) {
                    $q->where('lang', session('languages'));
                },
                'translations' => function ($q) {
                    $q->where('lang', session('languages'));
                }
            ])->first();
    }


    public function editNews($id){
      $news = News::where('id',$id)->with('translations')->first();


      return $news;
    }

  


}
