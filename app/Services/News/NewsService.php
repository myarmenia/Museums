<?php
namespace App\Services\News;

use App\Models\Image;
use App\Repositories\News\NewsRepository;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Storage;

class NewsService
{
    protected $newsCategoryService;

    protected $newsRepository;

    public function __construct(NewsCategoryService $newsCategoryService, NewsRepository $newsRepository)
    {
        $this->newsCategoryService = $newsCategoryService;
        $this->newsRepository = $newsRepository;
    }

    public function getCategoryList()
    {
        $categoryList = $this->newsCategoryService->getCategoryListAdmin();
        $readyList = $this->newsCategoryService->customCategoryResAdmin($categoryList);

        return $readyList;
    }

    public function createNews($data)
    {
        $news = $this->newsRepository->createNews($data['category']);

        $title = $data['title'];
        $description = $data['description'];
        $dataNews = [];

        $lang = languages();

        foreach ($lang as $val) {
           $dataNews[] = [
               'news_id' => $news->id,
               'title' => $title[$val],
               'description' => $description[$val],
               'lang' => $val
           ];
        }

        $newsData = $this->newsRepository->createNewsData($dataNews);

        if($photo = $data['photo'] ?? null){
            $path = FileUploadService::upload($photo, 'news/'.$news->id);

            $photoData = [
                'path' => $path,
                'name' => $photo->getClientOriginalName()
            ];

            $news->images()->create($photoData);
        }

        if($newsData){

            session(['success' => 'Операция выполнена успешно']);
            return true;
        }

        session(['errorMessage' => 'Что то пошло не так. Попробуйте еще раз.']);

        return false;
    }

    public function customNewsResource($data)
    {
        $readyResource = [];
        foreach ($data as $key => $val) {

            $readyResource[] = [
                'id' => $val->id,
                'images' => isset($val->images) && count($val->images) > 0? route('get-file', ['path' => $val->images[0]->path]) : null,
                'title' => $val->translations[0]->title,
                'category' => $val->category->type,
                'created_at'=> $val->created_at,
            ];
        }

        return $readyResource;

    }
    public function editNews($id){

      $news = $this->newsRepository->editNews($id);
      return $news;
    }
    public function updateNews($data,$id){

      $news = $this->newsRepository->editNews($id);

      if($news){

        $news->news_category_id = $data['category'];
        $news->save();
        $news->translation;

        $count = 0;
        foreach(languages() as $lang){
          $count++;
          $news_translation= $news->newstranslation($lang);
          $news_translation->title = $data['title'][$lang];
          $news_translation->description = $data['description'][$lang];
          $news_translation->save();

        }
          if(isset($data['photo'])){

            $image = Image::where('imageable_id',$id)->first();

            if(Storage::exists($image->path)){
              // dd($image->path);
              Storage::delete($image->path);
              $image->delete();
            }
            $path = FileUploadService::upload($data['photo'], 'news/'.$news->id);

            $photoData = [
                'path' => $path,
                'name' => $data['photo']->getClientOriginalName()
            ];

            $news->images()->create($photoData);

          }

          return true;


      }

    }

}
