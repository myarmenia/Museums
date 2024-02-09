<?php

namespace App\Http\Resources\News;

use App\Models\News\NewsCategoryTranslations;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResourceByCategory extends JsonResource
{

    private $newsCategory;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if(!session()->has('newsCategoryById')){
            $categoryName = NewsCategoryTranslations::where('lang', session('languages'))
                ->select('id','name', 'news_category_id')
                ->get()
                ->keyBy('news_category_id')
                ->toArray();

            $this->newsCategory = $categoryName;
        }else{
            $this->newsCategory = session('newsCategoryById');
        }
       
      $categoryName = 'Untitled';
      $categoryId = $this->resource->first()->news_category_id;
      $categoryArr = $this->newsCategory;

      if(array_key_exists($categoryId, $categoryArr)){
        $categoryName = $categoryArr[$categoryId]['name'];
      }

        return [
            'category' => ['categoryId' => $categoryId, 'categoryName' => $categoryName],
            'items' => NewsResource::collection($this->resource->reverse()->slice(0, 3)),
        ];
    }
}
