<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Traits\Dashboard\AnalyticsAttendanceByAge;
use App\Traits\Dashboard\AnalyticsAttendanceByCountry;
use App\Traits\Dashboard\AnalyticsTrait;
use App\Traits\Dashboard\SingleMuseumAnalyticsTrait;
use Illuminate\Http\Request;

class SingleMuseumAnalyticsController extends Controller
{
  use AnalyticsTrait, AnalyticsAttendanceByCountry, SingleMuseumAnalyticsTrait, AnalyticsAttendanceByAge;
  public function __invoke()
  {
    $museum_id = museumAccessId();
    // dd($museum_id);
    if ($museum_id) {
      $ticket_type = $this->ticketsType($museum_id);

      // Оптимизация: вызов groupAgeForAllMuseum один раз
      $group_age_data = $this->groupAgeForAllMuseum($museum_id);
      $attendance_by_age_arr = json_encode($group_age_data['age_analytics']);
      $age_total_amount = $group_age_data['age_total_amount'];
      $age_total_amount_asoc_arr = $group_age_data['age_analytics_asoc_arr'];

      // // Оптимизация: вызов forAllMuseum один раз
      $attendance_by_country_data = $this->forAllMuseum($museum_id);
      $attendance_by_country = json_encode($attendance_by_country_data['statistics']);
      $attendance_by_country_arr = $attendance_by_country_data['array'];

      // // Аналитика по месяцам
      $analitics_by_month = json_encode($this->analyticsByMonth($museum_id));


    }

    // return view("content.dashboard.museum-dashboards-analytics", compact('group_age_data'));


    return view("content.dashboard.museum-dashboards-analytics", compact('ticket_type', 'analitics_by_month', 'attendance_by_age_arr', 'age_total_amount', 'age_total_amount_asoc_arr', 'attendance_by_country', 'attendance_by_country_arr'));

  }
}
