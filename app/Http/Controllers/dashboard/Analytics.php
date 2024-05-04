<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\PurchasedItem;
use App\Traits\Dashboard\AnalyticsTrait;
use App\Traits\Reports\CheckReportType;
use App\Traits\Reports\ReportTrait;
use Illuminate\Http\Request;

class Analytics extends Controller
{
    use ReportTrait, CheckReportType, AnalyticsTrait;
    protected $model;
    public function __construct(PurchasedItem $model)
    {
      $this->middleware('role:super_admin|general_manager|chief_accountant');
      $this->model = $model;

    }

    public function index()
    {

      // $data = $this->report([], $this->model, 'report');
      $ticket_type = $this->ticketsType();
      $total_revenue = json_encode($this->totalRevenue());

      // return view("content.dashboard.dashboards-analytics", compact('ticket_type', 'total_revenue'));
    return view("content.dashboard.dashboards-analytics", compact('ticket_type', 'total_revenue'));


  }

}
