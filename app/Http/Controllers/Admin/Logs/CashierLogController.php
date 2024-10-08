<?php

namespace App\Http\Controllers\Admin\Logs;

use App\Http\Controllers\Controller;
use App\Models\CashierLog;
use App\Services\Log\CashierService;
use App\Services\Log\LogService;
use Illuminate\Http\Request;

class CashierLogController extends Controller
{
    protected $model;
    public function __construct(CashierLog $model)
    {
      $this->model = $model;
    }
    public  function index(Request $request){

      if (isset($request->action)) {

        $newRelationFilter = $request->action == 'store'
          ? ['purchases' => ['museum_id']]
          : ['ticket_qrs' => ['museum_id']];

        $this->model->setRelationFilter($newRelationFilter);
      }


      $data = CashierService::logFilter($request->all(), $this->model)
        ->orderBy('id', 'DESC')
        ->paginate(30)->withQueryString();

      return view("content.logs.cashier-logs", compact('data'))
        ->with('i', ($request->input('page', 1) - 1) * 30);

    }


  public function cashier_logs_show_more(Request $request){

      $details = CashierService::showDetails($request->id);
      $details = $details ? $details : null;

      return view('components.show-details', ['details' => $details]);

  }
}
