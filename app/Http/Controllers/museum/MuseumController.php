<?php

namespace App\Http\Controllers\museum;

use App\Http\Controllers\Controller;
use App\Http\Requests\MuseumRequest;
use App\Models\Museum;
use App\Models\Region;
use App\Services\Museum\MuseumService;
use Illuminate\Http\Request;

class MuseumController extends Controller
{

    protected $museumService;

    public function __construct(MuseumService $museumService)
    {
        $this->museumService = $museumService;
    }
    public function index(Request $request)
    {
        $data = Museum::orderBy('id', 'DESC')->paginate(5);

        return view('content.museum.index', compact('data'))
               ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        $regions = Region::all();

        return view('content.museum.create', compact('regions'));
    }

    public function addMuseum(MuseumRequest $request)
    {

        $createMuseum = $this->museumService->createMuseum($request->all());
        dd($request->all());
        // if($createProj){
        //     $data = Project::orderBy('id', 'DESC')->paginate(5);

        //     return redirect()->route('project')
        //           ->with('i', ($request->input('page', 1) - 1) * 5);
        // }
    }

    
}
