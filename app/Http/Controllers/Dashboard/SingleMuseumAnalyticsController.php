<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Traits\Dashboard\AnalyticsTrait;
use Illuminate\Http\Request;

class SingleMuseumAnalyticsController extends Controller
{
  use AnalyticsTrait;
    public function __invoke()
  {
    $museum_id = museumAccessId();
// dd($museum_id);
    if($museum_id){
      $ticket_type = $this->ticketsType($museum_id);

    }
    // $total_revenue = json_encode($this->totalRevenue());
    // $attendance_by_country = json_encode($this->forAllMuseum()['statistics']);
    // $attendance_by_country_arr = $this->forAllMuseum()['array'];
    // $attendance_by_age_arr = json_encode($this->groupAgeForAllMuseum()['age_analytics']);
    // $age_total_amount = $this->groupAgeForAllMuseum()['age_total_amount'];
    // $age_total_amount_asoc_arr = $this->groupAgeForAllMuseum()['age_analytics_asoc_arr'];
    // $get_top_museum = $this->getTopMuseum();

    return view("content.dashboard.museum-dashboards-analytics", compact('ticket_type'));

  }
}
