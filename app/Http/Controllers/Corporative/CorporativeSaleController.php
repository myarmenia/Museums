<?php

namespace App\Http\Controllers\Corporative;

use App\Http\Controllers\API\BaseController;
use App\Models\CorporativeSale;
use App\Models\CorporativeVisitorCount;
use App\Services\Corporative\CorporativeSaleService;
use Illuminate\Http\Request;

class CorporativeSaleController extends BaseController
{
    protected $corporativeSaleService;

    public function __construct(CorporativeSaleService $corporativeSaleService)
    {
        $this->corporativeSaleService = $corporativeSaleService;
    }

    public function index(Request $request)
    {
        // if(haveMuseumAdmin() && $id = haveMuseum()) {
        //     return redirect()->route('museum.edit', ['id' => (int) $id]);
        // }elseif (haveMuseumAdmin() && !haveMuseum()) {
        //     return redirect()->route('create-museum');
        // };
        
        $data = CorporativeSale::orderBy('id', 'DESC')->paginate(5);

        return view('content.corporative.index', compact('data'))
               ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        return view('content.corporative.create');
    }

    public function addCorporative(Request $request)
    {
        dd($request->all());
        $id = $this->museumService->createMuseum($request->all());

        return redirect()->route('museum.edit', ['id' => $id]);

    }
}
