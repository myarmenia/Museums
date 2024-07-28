<?php

namespace App\Http\Controllers\Admin\Logs;

use App\Http\Controllers\Controller;
use App\Services\Log\LogService;
use Illuminate\Http\Request;
use App\Models\Log;

class LogController extends Controller
{
    protected $model;
    public function __construct(Log $model)
    {
        $this->middleware('role:super_admin');
        $this->model = $model;
    }

    public function index(Request $request){


    $data = LogService::logFilter($request->all(), $this->model)
            ->orderBy('id', 'DESC')
            ->paginate(10)->withQueryString();
        return view("content.logs.index", compact('data'))
          ->with('i', ($request->input('page', 1) - 1) * 10);

    }
}
