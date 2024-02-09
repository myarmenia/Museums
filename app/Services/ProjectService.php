<?php
namespace App\Services;
use App\Models\Project\Project;
use App\Models\Project\ProjectPhoto;
use App\Models\Project\ProjectTranslation;
use App\Repositories\Project\ProjectRepository;
use Illuminate\Support\Facades\DB;

class ProjectService
{

    protected $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function addProject($data)
    {
        $project = Project::create($data);

        return $project;
    }

    public function createProject($data)
    {

        // DB::transaction(function () use ($data) {
        //     try {
                $createProjectData = [
                    'name'  => $data['name'],
                    'process_time'  => $data['process_time'], 
                    'creation_date_at'  => $data['creation_date_at'],
                    'link_project'  => $data['link_project'],
                    'link_app_store'    => $data['link_app_store'],
                    'link_play_market'  => $data['link_play_market'],
                    'project_language'  => $data['lang'],
                    'type'  => $data['type'],
                ];
        
                $createdProj = $this->addProject($createProjectData);
                $projectId = $createdProj->id;

                $translationData = [
                    ['description' => $data['proj-am'], 'lang' => 'am', 'project_id' => $projectId],
                    ['description' => $data['proj-ru'], 'lang' => 'ru', 'project_id' => $projectId],
                    ['description' => $data['proj-en'], 'lang' => 'en', 'project_id' => $projectId],
                ];
                
                ProjectTranslation::insert($translationData);

                if($data['project_photos']){
                    $projectPhotoInsert = [];
                    $photos = $data['project_photos'];
                    foreach($photos as $photo){
                        $path = FileUploadService::upload($photo, 'projects/'.$projectId);
                        $projectPhotoInsert[] = [
                            // 'project_id' => $projectId,
                            'path' => $path,
                            'name' => $photo->getClientOriginalName() 
                        ];
                    }

                    $createdProj->images()->createMany($projectPhotoInsert);

                    // ProjectPhoto::insert($projectPhotoInsert);
                }

                session(['success' => 'Операция выполнена успешно']);
            //     DB::commit();
            return true;
            // } catch (\Exception $e) {
            //     DB::rollBack();
            // }

        // });

 

    }

    public function getProject()
    {
       return $this->projectRepository->getProject();  

    }

    public function updateProject($data, $id){

        $project = Project::find($id);

        $project->update([
            "name" => $data['name'],        
            "project_language" => $data['lang'],
            "process_time" => $data['process_time'],
            "creation_date_at" => $data['creation_date_at'],            
            "type" => $data['type']]);
        

        foreach ( $project->translation as $key => $value) {
           $value->where('lang', $value->lang)->update(['description' => $data["proj-$value->lang"]]);
        }

        if($data['project_photos']){
            $projectPhotoInsert = [];
            $photos = $data['project_photos'];
            foreach($photos as $photo){
                $path = FileUploadService::upload($photo, 'projects/'.$project->id);
                $projectPhotoInsert[] = [
                    'path' => $path,
                    'name' => $photo->getClientOriginalName() 
                ];
            }

            $project->images()->createMany($projectPhotoInsert);

        }

        return $project;
        

    }

  
}
