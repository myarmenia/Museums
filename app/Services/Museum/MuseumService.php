<?php
namespace App\Services\Museum;

use App\Models\Museum;
use App\Models\Region;
use App\Repositories\Museum\MuseumRepository;
use App\Services\Image\ImageService;
use App\Services\Link\LinkService;
use Illuminate\Support\Facades\DB;

class MuseumService
{

    protected $museumRepository;

    public function __construct(MuseumRepository $museumRepository)
    {
        $this->museumRepository = $museumRepository;
    }

    public function addMuseum($data)
    {
        $museum = Museum::create($data);

        return $museum;
    }

    public function createMuseum($data)
    {
        try {
            DB::beginTransaction();
            $languages = languages();
            $museumTranslations = [];

            $regionId = Region::where('name', $data['region'])->first()->id;
            $museum = [
                'user_id' => auth()->id(),
                'museum_geographical_location_id' => $regionId,
                'email' => $data['email'],
                'account_number' => $data['account_number'],
            ];

            $getCreatedMuseum = $this->museumRepository->createMuseum($museum);
            $getCreatedMuseumId = $getCreatedMuseum->id;

            foreach ($languages as $key => $lang) {
                $museumTranslations[] = [
                    'museum_id' => $getCreatedMuseumId,
                    'lang' => $lang,
                    'name' => $data['name'][$lang],
                    'description' => $data['description'][$lang],
                    'working_days' => $data['work_days'][$lang],
                    'director_name' => $data['owner'][$lang],
                    'address' => $data['address'][$lang],
                ];
            }

            $museumTranslations = $this->museumRepository->createMuseumTranslations($museumTranslations);

            $imagesData = [
                'photos' => [
                    'image' => $data['photos'],
                    'mainPhoto' => $data['mainPhoto'],
                ],
                'museum' => $getCreatedMuseum
            ];

            ImageService::createImageble($imagesData);

            $createLinkData = [
                'link' => $data['link'],
                'museum' => $getCreatedMuseum
            ];

            LinkService::createLink($createLinkData);
            DB::commit();

            return true;
        } catch (\Exception $e) {
            session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
            DB::rollBack();
            return false;
        } catch (\Error $e) {
            session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
            DB::rollBack();
            return false;
        }

    }

    // public function getProject()
    // {
    //    return $this->projectRepository->getProject();  

    // }

    // public function updateProject($data, $id){

    //     $project = Project::find($id);

    //     $project->update([
    //         "name" => $data['name'],        
    //         "project_language" => $data['lang'],
    //         "process_time" => $data['process_time'],
    //         "creation_date_at" => $data['creation_date_at'],            
    //         "type" => $data['type']]);


    //     foreach ( $project->translation as $key => $value) {
    //        $value->where('lang', $value->lang)->update(['description' => $data["proj-$value->lang"]]);
    //     }

    //     if($data['project_photos']){
    //         $projectPhotoInsert = [];
    //         $photos = $data['project_photos'];
    //         foreach($photos as $photo){
    //             $path = FileUploadService::upload($photo, 'projects/'.$project->id);
    //             $projectPhotoInsert[] = [
    //                 'path' => $path,
    //                 'name' => $photo->getClientOriginalName() 
    //             ];
    //         }

    //         $project->images()->createMany($projectPhotoInsert);

    //     }

    //     return $project;


    // }


}
