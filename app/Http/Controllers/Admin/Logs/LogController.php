<?php

namespace App\Http\Controllers\Admin\Logs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Log;

class LogController extends Controller
{
    protected $model;
    public function __construct(Log $model)
    {
      $this->model = $model;
    }
    public function index(Request $request){
        // $logs = $this->model
        //   ->filter($request->all())
        //   ->get();
        $data = LodSer;

        return view('content.logs.index', [
          'logs' => $logs
        ]);

    }
}
