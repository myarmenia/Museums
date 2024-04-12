<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use App\Models\PurchasedItem;
use App\Traits\Reports\CheckReportType;
use App\Traits\Reports\ReportTrait;
use Illuminate\Http\Request;

class ReportsForMuseumAdminController extends Controller
{
  use ReportTrait, CheckReportType;
  protected $model;
  public function __construct(PurchasedItem $model)
  {
    $this->middleware('role:museum_admin|manager|accountant');
    $this->model = $model;

  }

  public function index(Request $request, $request_report_type)
  {

    $museum_id = museumAccessId();
    $request['museum_id'] = [$museum_id];
    $data = $this->report($request->all(), $this->model, $request_report_type);
    // dd($data);


    return view("content.reports.museum-admin", compact('data'));

  }
}
