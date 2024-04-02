<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use Illuminate\Http\Request;

class ReportsForSuperAdminController extends Controller
{
  public function __construct()
  {
      $this->middleware('role:super_admin');
  }

  public function index(Request $request)
  {

    // $data = LogService::logFilter($request->all(), $this->model)
    //   ->orderBy('id', 'DESC')
    //   ->paginate(10)->withQueryString();

    $museums = Museum::all();
    return view("content.reports.super-admin", compact('museums'));

    // return view("content.logs.index", compact('data'))
    //   ->with('i', ($request->input('page', 1) - 1) * 10);

  }
}
