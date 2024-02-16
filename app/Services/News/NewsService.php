<?php
namespace App\Services\News;

use App\Models\Image;
use App\Models\NewsTranslations;
use App\Repositories\News\NewsRepository;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsService
{

    protected $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {

        $this->newsRepository = $newsRepository;
    }



    public function createNews($request)
    {


      $request['user_id']=Auth::id();

        $news = $this->newsRepository->createNews($request['user_id']);

        foreach($request['translate'] as $key => $lang){

          $lang['news_id'] = $news->id;
          $lang['lang'] = $key;
         
          $newstranslate = NewsTranslations::create($lang);

        }

        if($photo = $request['photo'] ?? null){
            $path = FileUploadService::upload($photo, 'news/'.$news->id);

            $photoData = [
                'path' => $path,
                'name' => $photo->getClientOriginalName()
            ];

            $news->images()->create($photoData);
        }

        return true;
    }

    public function customNewsResource($data)
    {

        $readyResource = [];
        // foreach ($data as $key => $val) {
        //   dd($val);
        //     $readyResource[] = [
        //         'id' => $val->id,
        //         'images' => isset($val->images) && count($val->images) > 0? route('get-file', ['path' => $val->images[0]->path]) : null,
        //         'title' => $val->translations[0]->title,
        //         'created_at'=> $val->created_at,
        //     ];
        // }
        return $readyResource;
    }
    public function editNews($id){

      $news = $this->newsRepository->editNews($id);
      return $news;
    }
    public function updateNews($data,$id){

      $news = $this->newsRepository->editNews($id);

      if($news){

        $news->translation;

        foreach(languages() as $lang){

          $news_translation= $news->newstranslation($lang);
          $news_translation->title = $data['title'][$lang];
          $news_translation->description = $data['description'][$lang];
          $news_translation->save();

        }
          if(isset($data['photo'])){
            $image = Image::where('imageable_id',$id)->first();
            if(Storage::exists($image->path)){
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
          session(['success' => 'Գործողությունը հաջողությամբ իրականացվեց']);
          return true;


      }

    }

}
