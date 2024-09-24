<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventReportRequest;
use App\Models\Event;
use App\Models\Museum;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\PurchasedItem;
use App\Traits\Reports\CheckReportType;
use App\Traits\Reports\EventReports;
use App\Traits\Reports\ReportTrait;
use Illuminate\Http\Request;

class ReportsForSuperAdminController extends Controller
{
  use ReportTrait, CheckReportType, EventReports;
  protected $model;
  public function __construct(PurchasedItem $model)
  {
      $this->middleware('role:super_admin|general_manager|chief_accountant');
      $this->model = $model;

  }

  public function index(Request $request, $request_report_type)
  {

    $request['status'] = 1;
    $data = $this->report($request->all(), $this->model, $request_report_type);

    $museums = Museum::all();

    return view("content.reports.super-admin", compact('data', 'museums'));

  }

  public function events(Request $request)
  {
    
    $data = $request->item_relation_id == null ? [] : $this->event_report($request->all(), $this->model);

    return view("content.reports.super-admin-event", compact('data'));

  }


}
