<?php
namespace App\Traits\Reports;
use App\Models\Event;
use App\Models\EventConfig;
use App\Models\TicketQr;
use DB;

trait EventReports
{

  public function event_report($data, $model)
  {

    if (!isset($data['item_relation_id']) || $data['item_relation_id'] == null) {
      return false;
    }


    $interface = getAuthUserRoleInterfaceName();
    $museum_id = $interface == 'museum' ? getAuthMuseumId() : (isset($data['museum_id']) ? $data['museum_id'] : null);
    $event_id = $data['item_relation_id'];

    $type = $this->getEvantStyle($data['item_relation_id']) == 'basic' ? 'event-config' : 'event';

    $data['status'] = 1;
    $data['museum_id'] = $museum_id;

    if ($type == 'event-config') {
      $confid_ids = EventConfig::where('event_id', $data['item_relation_id'])->pluck('id')->toArray();
      $data['item_relation_id'] = $confid_ids;

    }


    $data = array_filter($data, function ($value) {
      return $value !== null && $value !== 'null';
    });

    $report = $model->where('type', $type)->reportFilter($data); //  purchased_items

    $event_report_partner = $this->event_report_partner($model, $data, $type);


    $groupedData = $this->event_report_fin_quant($report);
    $groupedData = reset($groupedData);


    if ($event_report_partner) {
      $groupedData['partner']['total_price'] = $event_report_partner->total_price;
      $groupedData['partner']['quantity'] = $event_report_partner->quantity;

    }


    return [
      'data' => $groupedData,
      'item' => $this->getEvant($event_id)
    ];

  }

  public function event_report_fin_quant($report)
  {

    $report = $report
      ->groupBy('museum_id', 'type', 'sub_type')
      ->select('museum_id', DB::raw('MAX(type) as type'), DB::raw('MAX(sub_type) as sub_type'), DB::raw('SUM(total_price - returned_total_price) as total_price'), DB::raw('SUM(quantity - returned_quantity) as quantity'))
      ->get();


    $report = $report->toArray();

    return $this->eventGroupByMuseumId(array_merge($report));

  }

  public function event_report_partner($model, $data, $type)
  {
    $data['partner_relation_id'] = $data['item_relation_id'];
    unset($data['item_relation_id']);

    $event_report_partner = $model
      ->where('type', 'partner')->where('sub_type', $type)->reportFilter($data); //  purchased_items

    $totals = $event_report_partner
      ->select(
        DB::raw('SUM(total_price - returned_total_price) as total_price'),
        DB::raw('SUM(quantity - returned_quantity) as quantity')
      )
      ->first();

    return $totals;
  }

  public function getEvantStyle($id)
  {

    return Event::withTrashed()->find($id)->style;
  }

  public function getEvant($id)
  {

    return Event::withTrashed()->find($id);
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
