<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Exports\ReportExport;
use App\Http\Controllers\Controller;
use App\Traits\Reports\CheckReportType;
use App\Traits\Reports\ReportTrait;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportExcelController extends Controller
{


  public function export(Request $request)
    {

      return Excel::download(new ReportExport($request), 'products7.xlsx');

    }
}
