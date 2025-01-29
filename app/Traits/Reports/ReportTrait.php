<?php
namespace App\Traits\Reports;

use App\Models\Purchase;
use App\Models\TicketQr;
use Illuminate\Http\Request;
use App\Models\PurchasedItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseUnitedTickets;


trait ReportTrait
{

  public function generateReport($data, $model)
  {

    $data = array_filter($data, function ($value) {
      return $value !== null && $value !== 'null';
    });


    if (!isset($data['report_type'])) {
      $data['report_type'] = 'fin_quant';
    }

    if (!isset($data['time']) && empty($data['from_created_at']) && empty($data['to_created_at'])) {
      $data['time'][] = 'per_year';
    }


    if (isset($data['time']) && count($data['time']) == 1) {
      $get_report_times = getReportTimes();

      // dd($get_report_times[$data['time'][0]]['start_date']);

      $data['start_date'] = $get_report_times[$data['time'][0]]['start_date'];
      $data['end_date'] = $get_report_times[$data['time'][0]]['end_date'];
    }

    if (isset($data['age'])) {
      $get_age_ranges = getAgeRanges();

      $data['start_age'] = $get_age_ranges[$data['age']]['start_age'];
      $data['end_age'] = $get_age_ranges[$data['age']]['end_age'];

    }

    $purchase = $model->reportFilter($data);
    $purchased_item_id = $purchase->where('type', 'united')->pluck('id');


    $report = $model->reportFilter($data)->where('type', '!=', 'united');


    if (isset($data['museum_id']) && !empty($data['museum_id'])) {
      $report = $report->whereIn('museum_id', $data['museum_id']);
    }


    $united = [];
    $united = PurchaseUnitedTickets::whereIn('purchased_item_id', $purchased_item_id);

    if (isset($data['museum_id']) && !empty($data['museum_id'])) {
      $united = $united->whereIn('museum_id', $data['museum_id']);
    }


    $report_ids = $report->pluck('id');
    // $canceled = TicketQr::where('status', 'returned')->where('type', '!=', 'united')->whereIn('purchased_item_id', $report_ids);


    if ($data['report_type'] == 'fin_quant') {

      $groupedData = $this->report_fin_quant($report, $united);
    }

    if ($data['report_type'] == 'financial') {

      $groupedData = $this->report_financial($report, $united);
    }

    if ($data['report_type'] == 'quantitative') {

      $groupedData = $this->report_quantitative($report, $united);
    }

    // dd($groupedData);
    return $groupedData;

  }


  public function report_financial($report, $united)
  {
    $originalReport = clone $report;

    $all_report = $report
      ->groupBy('museum_id', 'type')
      ->select('museum_id', \DB::raw('MAX(type) as type'), \DB::raw('SUM(total_price - returned_total_price) as total_price'))
      ->get();

    $partner_guide_report = $report->whereIn('sub_type', ['partner_guide_other', 'partner_guide_am'])
      ->groupBy('museum_id')
      ->select('museum_id', \DB::raw('SUM(total_price - returned_total_price) as total_price'))
      ->get()->toArray();    // grvel e nra hamar, vor yndhanur hashvetvutyan eqskursavarneri gumaric hanvi gortsynkerneri eqskuesianeri gumary

    $united = $united->groupBy('museum_id')
      ->select('museum_id', \DB::raw('SUM(total_price) as total_price'))
      ->get();

    // $canceled = $canceled->groupBy('museum_id')
    //   ->select('museum_id', \DB::raw('SUM(price) as total_price'), \DB::raw('COUNT(*) as quantity'))
    //   ->get();

    $canceled = $originalReport->groupBy('museum_id')
      ->select('museum_id', \DB::raw('SUM(returned_total_price) as total_price'))
      ->get();

    $all_report = $all_report->toArray();
    $united = $united->toArray();
    $canceled = $canceled->toArray();

    $canceled = array_map(function ($item) {
      $item['type'] = 'canceled';
      return $item;
    }, $canceled);

    // return $this->groupByMuseumId(array_merge($all_report, $united, $canceled));
    $general_report = $this->groupByMuseumId(array_merge($all_report, $united, $canceled));

    $general_report = $this->generalReport($partner_guide_report, $general_report);

    return $general_report;

  }

  public function report_fin_quant($report, $united)
  {
    $originalReport = clone $report;

    $all_report = $report
      ->groupBy('museum_id', 'type')
      ->select('museum_id', \DB::raw('MAX(type) as type'), \DB::raw('SUM(total_price - returned_total_price) as total_price'), \DB::raw('SUM(quantity - returned_quantity) as quantity'))
      ->get();


    $partner_guide_report = $report->whereIn('sub_type', ['partner_guide_other', 'partner_guide_am'])
      ->groupBy('museum_id')
      ->select('museum_id', \DB::raw('SUM(total_price - returned_total_price) as total_price'), \DB::raw('SUM(quantity - returned_quantity) as quantity'))
      ->get()->toArray();    // grvel e nra hamar, vor yndhanur hashvetvutyan eqskursavarneri gumaric hanvi gortsynkerneri eqskuesianeri gumary


    $united = $united->groupBy('museum_id')
      ->select('museum_id', \DB::raw('SUM(total_price) as total_price'), \DB::raw('SUM(quantity) as quantity'))
      ->get();

    // $canceled = $canceled->groupBy('museum_id')
    //   ->select('museum_id', \DB::raw('SUM(price) as total_price'), \DB::raw('COUNT(*) as quantity'))
    //   ->get();

    $canceled = $originalReport->groupBy('museum_id')
      ->select('museum_id', \DB::raw('SUM(returned_total_price) as total_price'), \DB::raw('SUM(returned_quantity) as quantity'))
      ->get();

    $all_report = $all_report->toArray();
    $united = $united->toArray();
    $canceled = $canceled->toArray();

    $canceled = array_map(function ($item) {
      $item['type'] = 'canceled';
      return $item;
    }, $canceled);


    $general_report = $this->groupByMuseumId(array_merge($all_report, $united, $canceled));

    $general_report = $this->generalReport($partner_guide_report, $general_report);

    // dd($this->groupByMuseumId(array_merge($report, $united, $canceled)));
    // return $this->groupByMuseumId(array_merge($report, $united, $canceled));
    return $general_report;

  }

  public function report_quantitative($report, $united)
  {
    $originalReport = clone $report;

    $all_report = $report
      ->groupBy('museum_id', 'type')
      ->select('museum_id', \DB::raw('MAX(type) as type'), \DB::raw('SUM(quantity - returned_quantity) as quantity'))
      ->get();

    $partner_guide_report = $report->whereIn('sub_type', ['partner_guide_other', 'partner_guide_am'])
      ->groupBy('museum_id')
      ->select('museum_id',  \DB::raw('SUM(quantity - returned_quantity) as quantity'))
      ->get()->toArray();    // grvel e nra hamar, vor yndhanur hashvetvutyan eqskursavarneri gumaric hanvi gortsynkerneri eqskuesianeri gumary

    $united = $united->groupBy('museum_id')
      ->select('museum_id', \DB::raw('SUM(quantity) as quantity'))
      ->get();

    // $canceled = $canceled->groupBy('museum_id')
    //   ->select('museum_id', \DB::raw('SUM(price) as total_price'), \DB::raw('COUNT(*) as quantity'))
    //   ->get();

    $canceled = $originalReport->groupBy('museum_id')
      ->select('museum_id',  \DB::raw('SUM(returned_quantity) as quantity'))
      ->get();

    $all_report = $all_report->toArray();
    $united = $united->toArray();
    $canceled = $canceled->toArray();

    $canceled = array_map(function ($item) {
      $item['type'] = 'canceled';
      return $item;
    }, $canceled);

    // return $this->groupByMuseumId(array_merge($all_report, $united, $canceled));
    $general_report = $this->groupByMuseumId(array_merge($all_report, $united, $canceled));

    $general_report = $this->generalReport($partner_guide_report, $general_report);

    return $general_report;

  }

  public function groupByMuseumId($array)
  {

    return array_reduce($array, function ($carry, $item) {
      $museumId = $item['museum_id'];

      if (isset ($museumId)) {
        if (!isset ($carry[$museumId])) {
          $carry[$museumId] = [];

        }

        $type = isset ($item['type']) ? $item['type'] : 'united';

        if (isset ($item['quantity'])) {
          $carry[$museumId][$type]['quantity'] = $item['quantity'];
        }

        if (isset ($item['total_price'])) {
          $carry[$museumId][$type]['total_price'] = $item['total_price'];
        }

        $carry[$museumId]['museum_id'] = $item['museum_id'];

      }

      return $carry;
    }, []);
  }


  // ========= grvel e nra hamar, vor yndhanur hashvetvutyan eqskursavarneri gumaric hanvi gortsynkerneri eqskuesianeri gumary 03.12.2024 =======================================

  public function generalReport($partner_guide_report, $general_report)
  {

      foreach ($partner_guide_report as $key => $value) {

          $museumId = $value['museum_id'];
          $quantity = $value['quantity'] ?? 0;
          $totalPrice = $value['total_price'] ?? 0;


          if (isset($general_report[$museumId]['guide']['quantity'])) {
              $general_report[$museumId]['guide']['quantity'] -= $quantity;
          }

          if (isset($general_report[$museumId]['guide']['total_price'])) {
              $general_report[$museumId]['guide']['total_price'] -= $totalPrice;
          }

          if (isset($general_report[$museumId]['partner']['quantity'])) {
              $general_report[$museumId]['partner']['quantity'] += $quantity;
          }

          if (isset($general_report[$museumId]['partner']['total_price'])) {
              $general_report[$museumId]['partner']['total_price'] += $totalPrice;
          }

      }

      return $general_report;
  }

  // ====================== educational row total count in PurchasedItem ==============================

  public function totalEducational($data, $model){
    $data = array_filter($data, function ($value) {
      return $value !== null && $value !== 'null';
    });


    if (!isset($data['report_type'])) {
      $data['report_type'] = 'fin_quant';
    }

    if (!isset($data['time']) && empty($data['from_created_at']) && empty($data['to_created_at'])) {
      $data['time'][] = 'per_year';
    }


    if (isset($data['time']) && count($data['time']) == 1) {
      $get_report_times = getReportTimes();


      $data['start_date'] = $get_report_times[$data['time'][0]]['start_date'];
      $data['end_date'] = $get_report_times[$data['time'][0]]['end_date'];
    }

    if (isset($data['age'])) {
      $get_age_ranges = getAgeRanges();

      $data['start_age'] = $get_age_ranges[$data['age']]['start_age'];
      $data['end_age'] = $get_age_ranges[$data['age']]['end_age'];

    }

    $report = $model->reportFilter($data)->where('type', 'educational')->whereColumn('returned_quantity', '!=', 'quantity');


    if (isset($data['museum_id']) && !empty($data['museum_id'])) {
      $report = $report->whereIn('museum_id', $data['museum_id']);
    }

    return count($report->get());


  }

}
