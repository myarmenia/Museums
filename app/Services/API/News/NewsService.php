<?php
namespace App\Services\API\News;
use App\Repositories\News\NewsRepository;

class NewsService
{
    protected $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function getNewsById($id)
    {
        return $this->newsRepository->getNewsById($id);
    }

}
