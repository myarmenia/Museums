<?php

namespace App\Http\Controllers\museum;

use App\Http\Controllers\Controller;
use App\Http\Requests\MuseumRequest;
use App\Models\Museum;
use Illuminate\Http\Request;

class MuseumController extends Controller
{
    public function index(Request $request)
    {
        $data = Museum::orderBy('id', 'DESC')->paginate(5);

        return view('content.museum.index', compact('data'))
               ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        // $region = Region::all();
        return view('content.museum.create');
    }

    public function addMuseum(MuseumRequest $request)
    {

        dd($request->all());
        // $createProj = $this->projectService->createProject($request->all());
        // if($createProj){
        //     $data = Project::orderBy('id', 'DESC')->paginate(5);

        //     return redirect()->route('project')
        //           ->with('i', ($request->input('page', 1) - 1) * 5);
        // }
    }

    
}
