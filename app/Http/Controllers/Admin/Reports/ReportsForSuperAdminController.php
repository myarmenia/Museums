<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\PurchasedItem;
use App\Traits\Reports\ReportTrait;
use Illuminate\Http\Request;

class ReportsForSuperAdminController extends Controller
{
  use ReportTrait;
  protected $model;
  public function __construct(PurchasedItem $model)
  {
      $this->middleware('role:super_admin');
      $this->model = $model;

  }

  public function index(Request $request)
  {

// dd($request->all());
    $data = $this->report($request->all(), $this->model);
    // dd($data);
    // $data = Purchase::report($request->all())
    //   ->purchased_items()->groupBy('museum_id', 'type')
    //       ->select('museum_id',  \DB::raw('MAX(type) as type'), \DB::raw('SUM(total_price) as total_price'))
    //       // ->get();
    //   ->withQueryString();
    // dd($request->all());
    $museums = Museum::all();
    // return view("content.reports.super-admin", compact('museums'));

    return view("content.reports.super-admin", compact('data', 'museums'));

  }
}
