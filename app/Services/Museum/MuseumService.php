<?php
namespace App\Services\Museum;
use App\Models\Museum;
use App\Models\Region;
use App\Repositories\Museum\MuseumRepository;
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
        $languages = languages();
        $museumTranslations = [];

        $regionId = Region::where('name', $data['region'])->first()->id;
        $museum = [
            'user_id' => auth()->id(),
            'museum_geographical_location_id' => $regionId,
            'email' => $data['email'],
            'account_number' => $data['account_number'],
            'working_hours' => $data['working_hours'],
        ];

        $getCreatedMuseumId = $this->museumRepository->createMuseum($museum);

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

        $images = $data['photos'];
        
        

        dd($data);
        $createMuseum = [
	    	'museum_geographical_location_id' => $regionId,
	    	
        ];

        // DB::transaction(function () use ($data) {
        //     try {
            //     $createProjectData = [
            //         'name'  => $data['name'],
            //         'process_time'  => $data['process_time'], 
            //         'creation_date_at'  => $data['creation_date_at'],
            //         'link_project'  => $data['link_project'],
            //         'link_app_store'    => $data['link_app_store'],
            //         'link_play_market'  => $data['link_play_market'],
            //         'project_language'  => $data['lang'],
            //         'type'  => $data['type'],
            //     ];
        
            //     $createdProj = $this->addProject($createProjectData);
            //     $projectId = $createdProj->id;

            //     $translationData = [
            //         ['description' => $data['proj-am'], 'lang' => 'am', 'project_id' => $projectId],
            //         ['description' => $data['proj-ru'], 'lang' => 'ru', 'project_id' => $projectId],
            //         ['description' => $data['proj-en'], 'lang' => 'en', 'project_id' => $projectId],
            //     ];
                
            //     ProjectTranslation::insert($translationData);

            //     if($data['project_photos']){
            //         $projectPhotoInsert = [];
            //         $photos = $data['project_photos'];
            //         foreach($photos as $photo){
            //             $path = FileUploadService::upload($photo, 'projects/'.$projectId);
            //             $projectPhotoInsert[] = [
            //                 // 'project_id' => $projectId,
            //                 'path' => $path,
            //                 'name' => $photo->getClientOriginalName() 
            //             ];
            //         }

            //         $createdProj->images()->createMany($projectPhotoInsert);

            //         // ProjectPhoto::insert($projectPhotoInsert);
            //     }

            //     session(['success' => 'Операция выполнена успешно']);
            // //     DB::commit();
            // return true;
            // } catch (\Exception $e) {
            //     DB::rollBack();
            // }

        // });

 

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
