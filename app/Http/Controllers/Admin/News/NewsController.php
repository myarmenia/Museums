<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Http\Requests\News\CreateNewsRequest;
use App\Http\Resources\Project\ProjectResource;
use App\Models\News\News;
use App\Models\News\NewsCategory;
use App\Services\News\NewsService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function index(Request $request)
    {
        $data = News::orderBy('id', 'DESC')->with(['translations', 'category', 'images'])->paginate(5);
        // $data = News::orderBy('id', 'DESC')->with(['translations'])->paginate(5);

        $data = $this->newsService->customNewsResource($data);

        return view('content.news.index', compact('data'))
               ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function createNewsPage()
    {
        $categoryList = $this->newsService->getCategoryList();

        return view('content.news.create', compact('categoryList'));
    }

    public function createNews(CreateNewsRequest $request)
    {
        $createNews = $this->newsService->createNews($request->all());

        $data = News::orderBy('id', 'DESC')->with(['translations', 'category', 'images'])->paginate(5);

        $data = $this->newsService->customNewsResource($data);

        return redirect()->route('news')
            ->with('i', ($request->input('page', 1) - 1) * 5)
            ->with('data', $data);

    }
    public function editNews($id){

      $news = $this->newsService->editNews($id);
      $categoryList = $this->newsService->getCategoryList();


      return view('content.news.edit', compact('news','categoryList'));
    }
    public function updateNews(CreateNewsRequest $request, $id){

      $news = $this->newsService->updateNews($request->all(),$id);


      return redirect()->back();
     
    }


}
