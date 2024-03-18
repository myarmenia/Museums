<?php

namespace App\Http\Controllers\Corporative;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\Corporative\CorporativeRequest;
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
        $museumId = getAuthMuseumId();
        if(!$museumId) {
            session(['errorMessage' => 'Նախ ստեղծեք թանգարան']);
            return redirect()->route('create-museum');
        }

        $query = CorporativeSale::query();
        
        if (request()->filled('tin')) {
            $tin = request()->tin;
            $query->where('tin', $tin);
        }
    
        $data = $query->where('museum_id', $museumId)->orderBy('id', 'DESC')->paginate(5);

        return view('content.corporative.index', compact('data'))
               ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        return view('content.corporative.create');
    }

    public function addCorporative(CorporativeRequest $request)
    {
        $this->corporativeSaleService->createCorporative($request->all());

        return redirect()->route('corporative');
    }
}
