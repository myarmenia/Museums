<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\News\NewsByIdResource;
use App\Http\Resources\News\NewsResource;
use App\Services\API\News\NewsService;
use App\Traits\FilterTrait;
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

        $addressRequest='api';
        $data=$this->getAllNews($request->all(), $addressRequest);
        $data=$data->orderBy('id', 'DESC')->paginate(6)->withQueryString();
        return NewsResource::collection($data);

    }


    public function getNews(int $id)
    {
        $news = $this->newsService->getNewsById($id);

        return new NewsByIdResource($news);
    }

}
