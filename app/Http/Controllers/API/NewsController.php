<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category\NewsResourceByCategoryType;
use App\Http\Resources\News\NewsByCategoryResource;
use App\Http\Resources\News\NewsByIdResource;
use App\Http\Resources\News\NewsResource;
use App\Http\Resources\News\NewsResourceByCategory;
use App\Services\API\News\NewsService;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function getNewsByCategories()
    {
        $news = $this->newsService->getNewsByCategories();

        return NewsResourceByCategory::collection($news);
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

    public function getNews(int $id)
    {
        $news = $this->newsService->getNewsById($id);

        return new NewsByIdResource($news);
    }
   
}
