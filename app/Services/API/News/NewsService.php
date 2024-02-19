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
    public function getNewsByCategories()
    {
      
        return $this->newsRepository->getNewsByCategories();

    }

    public function getNewsByCategoryType($id)
    {
        return $this->newsRepository->getNewsByCategoryType($id);
    }

    public function getCategoryNameById($id)
    {
        return $this->newsRepository->getCategoryNameById($id);
    }

    public function getNewsById($id)
    {
        return $this->newsRepository->getNewsById($id);
    }

}
