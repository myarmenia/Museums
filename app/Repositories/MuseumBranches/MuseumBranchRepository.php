<?php
namespace App\Repositories\MuseumBranches;

use App\Interfaces\MuseumBranches\MuseumBranchesRepositoryInterface;
use App\Models\Museum;
use App\Models\MuseumBranche;
use App\Models\MuseumBrancheTranslation;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Auth;

class MuseumBranchRepository implements MuseumBranchesRepositoryInterface
{

  public function all(){

    return MuseumBranche::where('id','>',0)->with(['museum_branche_translations','images'])->orderBy('id','desc')->get();

  }

  public  function creat(){
    return $data = Museum::where('user_id',Auth::id())->first();
  }

  public function store($request){


$museum_branches = MuseumBranche::create([
        'museum_id'=>$request['museum_id'],
        'email'=> $request['email'],
        'phone_number'=>$request['phone_number']]);

        if($museum_branches){

          foreach($request['translate'] as $key => $lang){

            $lang['museum_branche_id'] = $museum_branches->id;
            $lang['lang'] = $key;

            $newstranslate = MuseumBrancheTranslation::create($lang);

          }
        }
        if($photo = $request['photo'] ?? null){
          $path = FileUploadService::upload($request['photo'],'museum_branches/'.$museum_branches->id);
            $photoData = [
              'path' => $path,
              'name' => $photo->getClientOriginalName()
          ];
          $museum_branches->images()->create($photoData);
        }
        if($link = $request['link'] ?? null){
          $link = [
            'path' => $request['link'],
            'name' => 'website'
          ];
        }
          return true;




  }
  public function find(){
    return [];

  }

}

