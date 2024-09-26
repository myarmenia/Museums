<?php
namespace App\Traits\Reports;
use App\Models\GuideService;
use App\Models\TicketQr;

trait PartnersReports
{

  public function partners_report($data, $model)
  {
      // if (!isset($data['item_relation_id']) || $data['item_relation_id'] == null) {
      //   // return false;
      // }
      // else{

      // }


    $type = ['partner', 'guide'];
    $sub_type = ['partner_guide_am', 'partner_guide_other'];

    $data['status'] = 1;
    $data['museum_id'] = museumAccessId();



    $data = array_filter($data, function ($value) {
      return $value !== null && $value !== 'null';
    });


    if(isset($data['item_relation_id']) && $data['item_relation_id'] != null){

        $guide_ids = GuideService::where('museum_id',  museumAccessId())->pluck('id')->toArray();
        array_push($guide_ids, $data['item_relation_id']);

        $data['item_relation_id'] = $guide_ids;
      
    }

    $report = $model->where(function ($query) {
                          $query->where('type', 'partner')
                            ->whereNotNull('sub_type');
                        })
                        ->orWhere(function ($query) use ($sub_type) {
                          $query->where('type', 'guide')
                            ->whereIn('sub_type', $sub_type);
                        })
                        ->where('returned_quantity', 0)
                        ->reportFilter($data); //  purchased_items


    $report_ids = $report->pluck('id');
    $canceled = TicketQr::where('status', 'returned')->where('type', 'partner')->whereIn('purchased_item_id', $report_ids);

    $groupedData = $this->partners_report_fin_quant($report, $canceled);

    // dd($groupedData);
    return $groupedData;
    // return [
    //   'data' => reset($groupedData),
    //   'item' => $this->getEvant($event_id)
    // ];


  }

  public function partners_report_fin_quant($report, $canceled)
  {

    $report = $report
      ->groupBy('museum_id', 'type', 'sub_type')
      ->select('museum_id', \DB::raw('MAX(type) as type'), \DB::raw('MAX(sub_type) as sub_type'), \DB::raw('SUM(total_price - returned_total_price) as total_price'), \DB::raw('SUM(quantity - returned_quantity) as quantity'))
      ->get();

    $canceled = $canceled->groupBy('museum_id')
      ->select('museum_id', \DB::raw('SUM(price) as total_price'), \DB::raw('COUNT(*) as quantity'))
      ->get();

    $report = $report->toArray();
    $canceled = $canceled->toArray();

    $canceled = array_map(function ($item) {
      $item['type'] = 'canceled';
      return $item;
    }, $canceled);

    return $this->partnersGroupByMuseumId(array_merge($report));
    // return $this->groupByMuseumId(array_merge($report, $canceled));

  }

  public function partnersGroupByMuseumId($array)
  {

    return array_reduce($array, function ($carry, $item) {
      $museumId = $item['museum_id'];

      if (isset($museumId)) {
        if (!isset($carry[$museumId])) {
          $carry[$museumId] = [];
        }

        $type = isset($item['type']) ? $item['type'] : null;
        $subType = isset($item['sub_type']) ? $item['sub_type'] : null;

        // Определяем, какой ключ использовать для группировки
        $groupKey = isset($subType) ? $subType : $type;

        if (isset($item['quantity'])) {
          $carry[$museumId][$groupKey]['quantity'] = $item['quantity'];
        }

        if (isset($item['total_price'])) {
          $carry[$museumId][$groupKey]['total_price'] = $item['total_price'];
        }

        $carry[$museumId]['museum_id'] = $item['museum_id'];
      }

      return $carry;
    }, []);

  }

}
