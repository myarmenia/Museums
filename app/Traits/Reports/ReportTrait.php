<?php
namespace App\Traits\Reports;

use App\Models\Purchase;
use App\Models\PurchasedItem;
use App\Models\PurchaseUnitedTickets;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


trait ReportTrait
{

  public function report($data, $model)
  {
      if(isset($data['time'])){
        $get_report_times = getReportTimes();

        $data['start_date'] = $get_report_times[$data['time']]['start_date'];
        $data['end_date'] = $get_report_times[$data['time']]['end_date'];

      }

    $purchase = $model
      ->reportFilter($data);
// dd($purchase->pluck('id'));

    $purchased_item_id = $purchase->where('type', 'united')->pluck('id');
      // dd($purchased_item_id);
// dd($purchase->get());

    // $purchased_item_id = $purchase->where('type', 'united')->pluck('id');
// dd( $purchased_item_id);
    // $report = $model
    //   ->reportFilter($data)->where('type', '!=', 'united')
    $report =  $model
      ->reportFilter($data)->where('type', '!=', 'united');

      if(isset($data['museum_id']) && !empty($data['museum_id'])){
        $report =  $report->whereIn('museum_id', $data['museum_id']);
      }

      $report =  $report
      ->groupBy('museum_id', 'type')
      ->select('museum_id', \DB::raw('MAX(type) as type'), \DB::raw('SUM(total_price) as total_price'))
      ->get();
    // dd($purchase->pluck('id'));
// dd(PurchaseUnitedTickets::whereIn('purchased_item_id', $purchased_item_id)->whereIn('museum_id',[2])->get());

$united =[];

    // dd( $purchased_item_id);

// if(count($purchased_item_id) > 0){
// dd(PurchaseUnitedTickets::reportFilter($data)->get());
      // $united = PurchaseUnitedTickets::reportFilter($data)->groupBy('museum_id')
      //   ->select('museum_id', \DB::raw('SUM(price) as total_price'))
      //   ->get();



      $united = PurchaseUnitedTickets::whereIn('purchased_item_id', $purchased_item_id);
        if(isset($data['museum_id']) && !empty($data['museum_id'])){
          $united =  $united->whereIn('museum_id', $data['museum_id']);
        }

        $united = $united->groupBy('museum_id')
        ->select('museum_id', \DB::raw('SUM(total_price) as total_price'))
        ->get();
        $united = $united->toArray();

// }
    // dd($united);
    // $united = PurchaseUnitedTickets::reportFilter($data)->groupBy('museum_id')
    //   ->select('museum_id', \DB::raw('SUM(price) as total_price'))
    //   ->get();
    // $united = PurchaseUnitedTickets::groupBy('museum_id')
    //   ->select('museum_id', \DB::raw('SUM(price) as total_price'))
    //   ->get();
    // dd($united);
    // dd(array_merge($report->toArray(), $united->toArray()));
    // $groupedData = $this->groupByMuseumId($report->toArray());

    $groupedData = $this->groupByMuseumId(array_merge($report->toArray(), $united));
    // dd($groupedData);
    return $groupedData;
    // return array_merge($report->toArray(), $united->toArray());

  }

  public function groupByMuseumId($array)
  {
    return array_reduce($array, function ($carry, $item) {
      $museumId = $item['museum_id'];

      if(isset($museumId)){
          if (!isset ($carry[$museumId])) {
            $carry[$museumId] = [];

          }
          $type = isset ($item['type']) ? $item['type'] : 'united';
          $carry[$museumId][$type] = $item['total_price'];
      }

      return $carry;
    }, []);
  }


}
