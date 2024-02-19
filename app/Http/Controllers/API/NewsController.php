<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category\NewsResourceByCategoryType;
use App\Http\Resources\News\NewsByCategoryResource;
use App\Http\Resources\News\NewsByIdResource;
use App\Http\Resources\News\NewsResource;
use App\Http\Resources\News\NewsResourceByCategory;
use App\Services\API\News\NewsService;
use App\Traits\News\GetNewsTrait;
use Illuminate\Http\Request;

// use Illuminate\Support\Facades\Request;

class NewsController extends Controller
{
  use GetNewsTrait;
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function getNewslist(Request $request)
        {


        // $news = $this->newsService->getNewsByCategories();
        $news=$this->getNews($request->all());
        // dd($news);
        return NewsResource::collection($news);
        // return NewsResourceByCategory::collection($news);
        // return NewsResourceByCategory::collection($news);
    }

    public function getNewsByCategoryType(int $id)
    {
        $news = $this->newsService->getNewsByCategoryType($id);
        $getCategoryNameById = $this->newsService->getCategoryNameById($id);

        $data = [
            'news' => $news,
            'categoryName' => $getCategoryNameById
        ];

        return new NewsByCategoryResource($data);
    }

    public function getNewss(int $id)
    {
        $news = $this->newsService->getNewsById($id);

        return new NewsByIdResource($news);
    }

}
