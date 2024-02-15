<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Http\Requests\News\CreateNewsRequest;
use App\Http\Resources\Project\ProjectResource;
use App\Models\News;
use App\Models\NewsTranslations;
use App\Services\News\NewsService;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

class NewsController extends Controller
{
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
      // dd($request->all());
        // $data = News::orderBy('id', 'DESC')->with(['translations',  'images'])->paginate(5);
        $paginate = 2;
        $i = $request['page'] ? ($request['page']-1)*$paginate : 0;
        $data = News::latest();

        $this->title = $request->title;
        $this->from_created_at = $request->from_created_at;
        $this->to_created_at = $request->to_created_at;

        // $news_translation = NewsTranslations::latest();
        //                   $news_translation  ->where('title', 'like', '%' . $title . '%')

        //                     ->pluck('news_id')->toArray();

        //                     $data = $data->whereIn('id', $news_translation);

              $news_translation = NewsTranslations::latest();
                          // $news_translation  ->where('title', 'like', '%' . $title . '%')

                          //   ->pluck('news_id')->toArray();
// dd($this->from_created_at);
                            // $data = $data->whereIn('id', $news_translation);
                  $news_translation = $news_translation->where(function($query) {

                              $query

                              ->orWhere('title', 'like', '%' . $this->title . '%')

                                ->orWhere('created_at', '>', "2024-02-15")

                              ->orWhere('created_at', '<', "2024-02-29");

                          })
                          ->pluck('news_id')->toArray();

                         $data = $data->whereIn('id', $news_translation);



        $data = $data->paginate(5)->withQueryString();




        // $data = $this->newsService->customNewsResource($data);

        // return view('content.news.index', compact('data'))
        //        ->with('i', ($request->input('page', 1) - 1) * 5);

        return view('content.news.index',compact('data','i'));
    }

    public function createNewsPage()
    {

        return view('content.news.create');
    }

    public function createNews(Request $request)
    {
// dd($request->all());
        $createNews = $this->newsService->createNews($request->all());

        $data = News::orderBy('id', 'DESC')->with(['translations','images'])->paginate(5);
// dd($data);
        // $data = $this->newsService->customNewsResource($data);

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


      return redirect()->back();

    }


}
