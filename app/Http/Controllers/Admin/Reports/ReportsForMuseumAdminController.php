<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use App\Models\PurchasedItem;
use App\Traits\Paginator;
use App\Traits\Reports\CheckReportType;
use App\Traits\Reports\EventReports;
use App\Traits\Reports\PartnersReports;
use App\Traits\Reports\ReportTrait;
use Illuminate\Http\Request;

class ReportsForMuseumAdminController extends Controller
{
  use ReportTrait, CheckReportType, EventReports, PartnersReports, Paginator;
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

    // $totalQuantity = 0;
    // $totalPrice = 0;

    // if($request->type != 'online'){
    //     $report_with_cashier = $request->all();
    //     $report_with_cashier['type'] = 'offline';

    //     $report_with_cashier = $this->report($report_with_cashier, $this->model, $request_report_type);
    //     // dd($request->all());
    //     if (count($report_with_cashier) > 0) {

    //       $report_with_cashier = array_values($report_with_cashier)[0];
    //       unset($report_with_cashier['school'], $report_with_cashier['partner'], $report_with_cashier['corporative'], $report_with_cashier['canceled']);

    //       $report_with_cashier = array_reduce($report_with_cashier, function ($carry, $item) {
    //         if (isset($item['quantity']) || isset($item['total_price'])) {

    //           $carry['totalQuantity'] += (int) ($item['quantity'] ?? 0);
    //           $carry['totalPrice'] += (int) ($item['total_price'] ?? 0);
    //         }
    //         return $carry;
    //       }, ['totalQuantity' => 0, 'totalPrice' => 0]);
    //     }
    //     else{
    //         $report_with_cashier = ['totalQuantity' => 0, 'totalPrice' => 0];
    //     }
    // }
    // else{
    //     $report_with_cashier = ['totalQuantity' => 0, 'totalPrice' => 0];
    // }

    $report_with_cashier = $this->reportWithCashier($request->all(), $request_report_type);

dd($data);
    return view("content.reports.museum-admin", compact('data', 'report_with_cashier'));

  }

  public function events(Request $request)
  {
    $museum_id = museumAccessId();
    $data = $request->item_relation_id == null ? [] : $this->event_report($request->all(), $this->model);

    return view("content.reports.museum-event", compact('data'));

  }

  public function partners(Request $request)
  {
      $page = request()->page ?? 1;
      $perPage = 10;

      if (isset($request->partner_id) && $request->partner_id == 'all') {
        unset($request['partner_id']);
      }

      $museum_id = museumAccessId();

      $data = $this->partners_report($request->all(), $this->model);

      $totalInfo = $this->totalInfo($data);

      $collection = collect($data);

      $data = $this->arrayPaginator($collection, $request, $perPage);


      return view("content.reports.museum-partners", compact('data', 'totalInfo'));

  }


  public function reportWithCashier($data, $request_report_type){

      $totalQuantity = 0;
      $totalPrice = 0;

      if ($data->type != 'online') {
          $report_with_cashier = $data;
          $report_with_cashier['type'] = 'offline';

          $report_with_cashier = $this->report($report_with_cashier, $this->model, $request_report_type);
          // dd($request->all());
          if (count($report_with_cashier) > 0) {

            $report_with_cashier = array_values($report_with_cashier)[0];
            unset($report_with_cashier['school'], $report_with_cashier['partner'], $report_with_cashier['corporative'], $report_with_cashier['canceled']);

            $report_with_cashier = array_reduce($report_with_cashier, function ($carry, $item) {
              if (isset($item['quantity']) || isset($item['total_price'])) {

                $carry['totalQuantity'] += (int) ($item['quantity'] ?? 0);
                $carry['totalPrice'] += (int) ($item['total_price'] ?? 0);
              }
              return $carry;
            }, ['totalQuantity' => 0, 'totalPrice' => 0]);
          } else {
            $report_with_cashier = ['totalQuantity' => 0, 'totalPrice' => 0];
          }
      } else {
        $report_with_cashier = ['totalQuantity' => 0, 'totalPrice' => 0];
      }
  }
}
