<?php
namespace App\Traits\Reports;
use App\Models\GuideService;
use App\Models\TicketQr;

trait PartnersReports
{
  protected $itemId;
  public function partners_report($data, $model)
  {

    $currentYear = now()->year;

    $type = ['partner', 'guide'];
    $sub_type = ['partner_guide_am', 'partner_guide_other'];

    $data['status'] = 1;
    $data['museum_id'] = museumAccessId();


    $data = array_filter($data, function ($value) {
      return $value !== null && $value !== 'null';
    });

    $report = $model->where(function ($query) use ($currentYear) {
                          $query->where('type', 'partner')
                            ->whereNotNull('sub_type')
                            ->whereYear('created_at', $currentYear);
                        })
                        ->orWhere(function ($query) use ($sub_type, $currentYear) {
                          $query->where('type', 'guide')
                            ->whereIn('sub_type', $sub_type)
                            ->whereYear('created_at', $currentYear);
                        })
                        ->reportFilter($data); //  purchased_items


    $canceled = $model->where(function ($query) use ($sub_type, $currentYear) {
                  $query->where(function ($q) use ($currentYear) {
                    $q->where('type', 'partner')
                      ->whereNotNull('sub_type')
                      ->whereYear('created_at', $currentYear);
                  })
                    ->orWhere(function ($q) use ($sub_type, $currentYear) {
                      $q->where('type', 'guide')
                        ->whereIn('sub_type', $sub_type)
                        ->whereYear('created_at', $currentYear);
                    });
                })
                ->where('returned_quantity', '>', 0)
                ->reportFilter($data); //  purchased_items

    $groupedData = $this->partners_report_fin_quant($report, $canceled);

    return $groupedData;

  }

  public function partners_report_fin_quant($report, $canceled)
  {

      $this->itemId = 'partner_id';

      if (isset(request()->partner_id)) {
        $this->itemId = 'purchase_id';

      }

      if(isset(request()->partner_id)){
        $report = $report
          ->groupBy('partner_id', 'purchase_id', 'type', 'sub_type')
          ->select('partner_id', \DB::raw('MAX(created_at) as date'), \DB::raw('MAX(purchase_id) as purchase_id'), \DB::raw('MAX(type) as type'), \DB::raw('MAX(sub_type) as sub_type'), \DB::raw('SUM(total_price - returned_total_price) as total_price'), \DB::raw('SUM(quantity - returned_quantity) as quantity'))
          ->get();

        $canceled = $canceled->groupBy('partner_id', 'purchase_id')
          ->select('partner_id', \DB::raw('MAX(created_at) as date'), \DB::raw('MAX(purchase_id) as purchase_id'), \DB::raw('SUM(returned_total_price) as total_price'), \DB::raw('SUM(returned_quantity) as quantity'))
          ->get();

      }
      else{


      $report = $report
          ->groupBy('partner_id', 'type', 'sub_type')
          ->select('partner_id', \DB::raw('MAX(created_at) as date'), \DB::raw('MAX(type) as type'), \DB::raw('MAX(sub_type) as sub_type'), \DB::raw('SUM(total_price - returned_total_price) as total_price'), \DB::raw('SUM(quantity - returned_quantity) as quantity'))
          ->get();

        $canceled = $canceled->groupBy('partner_id')
          ->select('partner_id', \DB::raw('MAX(created_at) as date'), \DB::raw('SUM(returned_total_price) as total_price'), \DB::raw('SUM(returned_quantity) as quantity'))
          ->get();
      }


    $report = $report->toArray();
    $canceled = $canceled->toArray();

    $canceled = array_map(function ($item) {
      $item['type'] = 'canceled';
      return $item;
    }, $canceled);


    // return $this->partnersGroupByPartnerId(array_merge($report));
    return $this->partnersGroupByPartnerId(array_merge($report, $canceled));

  }

  public function partnersGroupByPartnerId($array)
  {

    return array_reduce($array, function ($carry, $item) {
      $item_id = $this->itemId;
      $partnerId = $item[$item_id];


      if (isset($partnerId)) {
        if (!isset($carry[$partnerId])) {
          $carry[$partnerId] = [];

        }

        $type = isset($item['type']) ? $item['type'] : null;
        $subType = isset($item['sub_type']) ? $item['sub_type'] : null;

        $groupKey = isset($type) && $type != 'canceled' ? (isset($subType) ? $subType : $type) : $type;

        if (isset($item['quantity'])) {
          $carry[$partnerId][$groupKey]['quantity'] = $item['quantity'];
        }

        if (isset($item['total_price'])) {
          $carry[$partnerId][$groupKey]['total_price'] = $item['total_price'];
        }

        $carry[$partnerId]['partner_id'] = $item['partner_id'];
        $carry[$partnerId]['date'] = $item['date'] ? date('d-m-Y', strtotime($item['date'])) : null;


      }

      return $carry;
    }, []);

  }

  public function totalInfo($data){
        $sums = reportResult($data);

        $newSums = array_diff_key($sums, ['canceled' => '']);

        $total_sums = array_sum(array_column($newSums,'total_price'));
        $total_quantity = array_sum(array_column($newSums,'quantity'));

        $total_info = $total_sums . ' / ' . $total_quantity;

        return $total_info;
  }


}
