<?php
namespace App\Traits\Reports;

use App\Models\Purchase;
use App\Models\PurchasedItem;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


trait ReportTrait
{

  public function report($data, $model)
  {

      $purchase = $model
          ->reportFilter($data)->pluck('id');
$report = PurchasedItem::whereIn('purchase_id', $purchase)
          ->groupBy('museum_id', 'type')
          ->select('museum_id',  \DB::raw('MAX(type) as type'), \DB::raw('SUM(total_price) as total_price'))
          ->get();

      return $report;

  }


}
