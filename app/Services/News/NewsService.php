<?php
namespace App\Services\News;

use App\Models\Image;
use App\Models\NewsTranslations;
use App\Repositories\News\NewsRepository;
use App\Services\FileUploadService;
use App\Services\Log\LogService;
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
    public function updateNews($request,$id){

      $news = $this->newsRepository->editNews($id);

      if($news){
          if(isset($request['photo'])){
            $image = Image::where('imageable_id',$id)->get();

              foreach( $image as $item){
                   if(Storage::exists($item->path)){
                     Storage::delete($item->path);
                   }

                $item->delete();
              }
            
            $path = FileUploadService::upload($request['photo'], 'news/'.$news->id);
            $photoData = [
                'path' => $path,
                'name' => $request['photo']->getClientOriginalName()
            ];

            $news->images()->create($photoData);
          }

        foreach($request['translate'] as $key=>$lang){
          $lang['news_id'] = $news->id;
          $lang['lang'] = $key;

          $newstranslate = NewsTranslations::where(['news_id'=>$news->id,'lang'=>$key])->first();
          $newstranslate->update($lang);

        }

          session(['success' => 'Գործողությունը հաջողությամբ իրականացվեց']);
          return true;
      }

    }

}
