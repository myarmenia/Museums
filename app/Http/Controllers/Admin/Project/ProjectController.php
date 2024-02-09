<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Http\Resources\Project\ProjectResource;
use App\Models\Project\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectAddOrCreateRequest;


class ProjectController extends Controller
{

    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index(Request $request)
    {
        $data = Project::orderBy('id', 'DESC')->paginate(5);

        return view('content.project.index', compact('data'))
               ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        return view('content.project.create');
    }

    public function addProject(ProjectAddOrCreateRequest $request)

    {

        $createProj = $this->projectService->createProject($request->all());
        if($createProj){
            $data = Project::orderBy('id', 'DESC')->paginate(5);

            return redirect()->route('project')
                  ->with('i', ($request->input('page', 1) - 1) * 5);
        }

      
    }

    public function getProject()
    {
        $project = $this->projectService->getProject();

        return ProjectResource::collection($project);
    }



    // public function show($id)
    // {
    //     $user = User::find($id);
    //     // return view('users.show', compact('user'));
    // }

    public function edit($id)
    {
        $project = Project::find($id);

        return view('content.project.edit', compact('project'));
    }

    public function update( ProjectAddOrCreateRequest $request, $id)
    {

        $createProj = $this->projectService->updateProject($request->all(), $id);

        return redirect()->back();

    }

    // public function destroy($id)
    // {
    //     User::find($id)->delete();
    //     return redirect()->route('users.index')
    //     ->with('success', 'User deleted successfully');
    // }
}
