<?php

namespace App\Http\Controllers\museum;

use App\Http\Controllers\Controller;
use App\Http\Requests\MuseumEditRequest;
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
        if(haveMuseumAdmin() && $id = haveMuseum()) {
            return redirect()->route('museum.edit', ['id' => (int) $id]);
        }elseif (haveMuseumAdmin() && !haveMuseum()) {
            return redirect()->route('create-museum');
        };
        
        $data = Museum::with(['user','translationsAdmin', 'phones', 'images', 'links'])->orderBy('id', 'DESC')->paginate(5);

        return view('content.museum.index', compact('data'))
               ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        if( $id = haveMuseum()) {
            return redirect()->route('museum.edit', ['id' => $id]);
        };
        $regions = Region::all();

        return view('content.museum.create', compact('regions'));
    }

    public function addMuseum(MuseumRequest $request)
    {
        $this->museumService->createMuseum($request->all());

        return redirect()->route('museum');

    }

    public function edit($id)
    {
        $data = $this->museumService->getMuseumByUd($id);
        $regions = Region::all();

        return view('content.museum.edit', compact(['data', 'regions']));
    }


    public function update(MuseumEditRequest $request, $id)
    {
        $this->museumService->updateMuseum($request->all(), $id);

        $data = $this->museumService->getMuseumByUd($id);

        return redirect()->route('museum.edit', ['id' => $id]);
    }
    
}
