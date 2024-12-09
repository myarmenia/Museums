<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use App\Models\PurchasedItem;
use App\Traits\Dashboard\AnalyticsAttendanceByAge;
use App\Traits\Dashboard\AnalyticsAttendanceByCountry;
use App\Traits\Dashboard\AnalyticsTopMuseum;
use App\Traits\Dashboard\AnalyticsTrait;
use App\Traits\Reports\CheckReportType;
use App\Traits\Reports\ReportTrait;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
  // use ReportTrait, CheckReportType, AnalyticsTrait;
  use AnalyticsTrait, AnalyticsAttendanceByCountry, AnalyticsAttendanceByAge, AnalyticsTopMuseum;
  protected $user;
  public function __construct(){

    $this->middleware(function ($request, $next) {
      $this->user = Auth::user(); // Set user here

      $authUserRols = Auth::user()->roles->pluck('name')->toArray();
      $roles = ['super_admin','general_manager','chief_accountant'];
      $intersection = array_intersect($authUserRols, $roles);

      if (!count($intersection)) {
        return redirect('/welcome'); // Перенаправляем на страницу /welcome
    }

      return $next($request);
  });
    // session(['last_visited_url' => $lastUrl]);
  }
  public function __invoke(Request $request)
  {

    // $ticket_type = $this->ticketsType();
    // $total_revenue = json_encode($this->totalRevenue());
    // $attendance_by_country = json_encode($this->forAllMuseum()['statistics']);
    // $attendance_by_country_arr = $this->forAllMuseum()['array'];
    // $attendance_by_age_arr = json_encode($this->groupAgeForAllMuseum()['age_analytics']);
    // $age_total_amount = $this->groupAgeForAllMuseum()['age_total_amount'];
    // $age_total_amount_asoc_arr = $this->groupAgeForAllMuseum()['age_analytics_asoc_arr'];
    // $get_top_museum = $this->getTopMuseum();

    $data = $request->all();
    $ticket_statistics = $this->ticketStatistics($data);

    // $ticket_type = $this->ticketsType();  // all tickets type old analitics
    // dd($ticket_statistics);
    $total_revenue = json_encode($this->totalRevenue());
    $attendance_data = $this->forAllMuseum();
    $attendance_by_country = json_encode($attendance_data['statistics']);
    $attendance_by_country_arr = $attendance_data['array'];

    $age_data = $this->groupAgeForAllMuseum();
    $attendance_by_age_arr = json_encode($age_data['age_analytics']);
    $age_total_amount = $age_data['age_total_amount'];
    $age_total_amount_asoc_arr = $age_data['age_analytics_asoc_arr'];
    $get_top_museum = $this->getTopMuseum();

    $museums = Museum::all();

    // $ticket_type = Cache::remember('tickets_type', 60, fn() => $this->ticketsType());
    // $total_revenue = Cache::remember('total_revenue', 60, fn() => json_encode($this->totalRevenue()));
    // $attendance_data = Cache::remember('attendance_data', 60, fn() => $this->forAllMuseum());
    // $attendance_by_country = json_encode($attendance_data['statistics']);
    // $attendance_by_country_arr = $attendance_data['array'];

    // $age_data = Cache::remember('age_data', 60, fn() => $this->groupAgeForAllMuseum());
    // $attendance_by_age_arr = json_encode($age_data['age_analytics']);
    // $age_total_amount = $age_data['age_total_amount'];
    // $age_total_amount_asoc_arr = $age_data['age_analytics_asoc_arr'];

    // $get_top_museum = Cache::remember('get_top_museum', 60, fn() => $this->getTopMuseum());

    return view("content.dashboard.dashboards-analytics", compact(
      'museums',
      'ticket_statistics',
          // 'ticket_type',  // old
          'total_revenue',
          'attendance_by_country',
          'attendance_by_country_arr',
          'attendance_by_age_arr',
          'age_total_amount',
          'age_total_amount_asoc_arr',
          'get_top_museum'
    ));

  }

}
