<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Http\Requests\News\CreateNewsRequest;
use App\Http\Resources\Project\ProjectResource;
use App\Models\News;
use App\Models\NewsTranslations;
use App\Services\Log\LogService;
use App\Services\News\NewsService;
use App\Traits\News\GetNewsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Input\Input;

class NewsController extends Controller
{
  use GetNewsTrait;

    protected $newsService;

    public $title;
    public $from_created_at;
    public $to_created_at;
    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function index(Request $request)
    {

      $addressRequest='web';
        $data=$this->getAllNews($request->all(),$addressRequest);
        $data=$data->orderBy('id', 'DESC')->paginate(6)->withQueryString();

            return view("content.news.index", compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 6);


    }

    public function createNewsPage()
    {

        return view('content.news.create');
    }

    public function createNews(CreateNewsRequest $request)
    {
// dd($request->all());
        $createNews = $this->newsService->createNews($request->all());


        $data = News::orderBy('id', 'DESC')->with(['news_translations','images'])->paginate(5);

        LogService::store($request->all(), Auth::id(), 'news', 'store');

        return redirect()->route('news')
            ->with('i', ($request->input('page', 1) - 1) * 5)
            ->with('data', $data);

    }
    public function editNews($id){

      $news = $this->newsService->editNews($id);



      return view('content.news.edit', compact('news'));
    }
    public function updateNews(CreateNewsRequest $request, $id){

      $news = $this->newsService->updateNews($request->all(),$id);

        LogService::store($request->except(["_token"]), Auth::id(), 'news', 'update');

      return redirect()->back();

    }


}
