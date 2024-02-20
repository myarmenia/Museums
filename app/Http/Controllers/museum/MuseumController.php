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
        if($this->haveMuseumAdmin() && $data = $this->haveMuseum()) {
            $regions = Region::all();
            return view('content.museum.edit', compact(['data', 'regions']));
        };
        
        $data = Museum::with(['user','translationsAdmin', 'phones', 'images', 'links'])->orderBy('id', 'DESC')->paginate(5);

        return view('content.museum.index', compact('data'))
               ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        if($this->haveMuseumAdmin() && $data = $this->haveMuseum()) {
            $regions = Region::all();
            return view('content.museum.edit', compact(['data', 'regions']));
        };
        $regions = Region::all();

        return view('content.museum.create', compact('regions'));
    }

    public function addMuseum(MuseumRequest $request)
    {

        $this->museumService->createMuseum($request->all());

        return redirect()->route('museum');

    }

    //if have museum get museum or false
    public function haveMuseum()
    {
        if($museum = Museum::where('user_id', auth()->id())->first()) {
            return $museum;
        };

        return false;
    }

    public function haveMuseumAdmin()
    {
        if(auth()->user()->roles()->get()->where('name', 'museum_admin')->count()) {
            return true;
        };

        return false;
    }
    
}
