<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use App\Models\PurchasedItem;
use App\Traits\Reports\CheckReportType;
use App\Traits\Reports\EventReports;
use App\Traits\Reports\PartnersReports;
use App\Traits\Reports\ReportTrait;
use Illuminate\Http\Request;

class ReportsForMuseumAdminController extends Controller
{
  use ReportTrait, CheckReportType, EventReports, PartnersReports;
  protected $model;
  public function __construct(PurchasedItem $model)
  {
    $this->middleware('role:museum_admin|manager|accountant');
    $this->model = $model;

  }

  public function index(Request $request, $request_report_type)
  {

    $museum_id = museumAccessId();
    $request['status'] = 1;
    $request['museum_id'] = [$museum_id];
    $data = $this->report($request->all(), $this->model, $request_report_type);

    return view("content.reports.museum-admin", compact('data'));

  }

  public function events(Request $request)
  {
    $museum_id = museumAccessId();
    $data = $request->item_relation_id == null ? [] : $this->event_report($request->all(), $this->model);

    return view("content.reports.museum-event", compact('data'));

  }

  public function partners(Request $request)
  {
    $museum_id = museumAccessId();
    // $data = $request->item_relation_id == null ? [] : $this->event_report($request->all(), $this->model);
    $data = $this->partners_report($request->all(), $this->model);

    return view("content.reports.museum-partners", compact('data'));

  }
}
