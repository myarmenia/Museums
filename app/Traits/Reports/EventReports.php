<?php
namespace App\Traits\Reports;
use App\Models\Event;
use App\Models\TicketQr;

trait EventReports
{

  public function event_report($data, $model)
  {

    $interface = getAuthUserRoleInterfaceName();
    $museum_id = $interface == 'museum' ? getAuthMuseumId() : null;

    $type = $this->getEvantStyle($data['item_relation_id']) == 'basic' ? 'event-config' : 'event';
    // $data['type'] = 'online';


    $data['status'] = 1;
    $data['museum_id'] = $museum_id;


    $data = array_filter($data, function ($value) {
      return $value !== null && $value !== 'null';
    });

    $report = $model->reportFilter($data)->where('type',  $type); //  purchased_items
    // dd($purchase->get());
    // if ($museum_id != null) {
    //   $purchase = $purchase->where('museum_id', $museum_id);
    // }


    $report_ids = $report->pluck('id');
    $canceled = TicketQr::where('status', 'returned')->where('type', $type)->whereIn('purchased_item_id', $report_ids);

    $groupedData = $this->event_report_fin_quant($report, $canceled);


    // dd($groupedData);

    return [
      'data' => reset($groupedData),
      'item' => $this->getEvant($data['item_relation_id'])
    ];

  }

  public function event_report_fin_quant($report, $canceled)
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

    // dd($this->groupByMuseumId(array_merge($report, $canceled)));

    return $this->eventGroupByMuseumId(array_merge($report, $canceled));

  }

  public function getEvantStyle($id){

    return Event::find($id)->style;
  }

  public function getEvant($id){

    return Event::find($id);
  }


  public function eventGroupByMuseumId($array)
  {

    return array_reduce($array, function ($carry, $item) {
        $museumId = $item['museum_id'];

        if (isset($museumId)) {
            if (!isset($carry[$museumId])) {
                $carry[$museumId] = [];
            }

            $type = isset($item['type']) ? $item['type'] : 'united';
            $subType = isset($item['sub_type']) ? $item['sub_type'] : null;

            // Определяем, какой ключ использовать для группировки
            $groupKey = isset($subType) ?  $subType : $type;

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
