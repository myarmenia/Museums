<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Museum;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\PurchasedItem;
use App\Traits\Reports\CheckReportType;
use App\Traits\Reports\ReportTrait;
use Illuminate\Http\Request;

class ReportsForSuperAdminController extends Controller
{
  use ReportTrait, CheckReportType;
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

  public function events(Request $request, $request_report_type)
  {

    $request['status'] = 1;
    $data = $this->report($request->all(), $this->model, $request_report_type);

    $events = Event::all();

    return view("content.reports.super-admin", compact('data', 'events'));

  }


}
