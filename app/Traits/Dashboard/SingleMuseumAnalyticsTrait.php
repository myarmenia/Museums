<?php
namespace App\Traits\Dashboard;

use App\Models\Museum;
use App\Models\MuseumTranslation;
use App\Models\Purchase;
use App\Models\PurchasedItem;
use App\Models\PurchaseUnitedTickets;
use Illuminate\Support\Facades\DB;

trait SingleMuseumAnalyticsTrait
{






  public function totalRevenue($museum_id)
  {
    $currentYear = now()->year;

    $purchases = Purchase::where('status', 1)->whereYear('created_at', $currentYear)->pluck('id');
    $purchased_items = PurchasedItem::whereIn('purchase_id', $purchases);

    $analytic = $purchased_items->where('museum_id', $museum_id)->where('type', '!=', 'united');

    $purchased_items = PurchasedItem::whereIn('purchase_id', $purchases);
    $purchased_item_id = $purchased_items->where('museum_id', $museum_id)->where('type', 'united')->pluck('id');


    $united = [];
    $united = PurchaseUnitedTickets::whereIn('purchased_item_id', $purchased_item_id);

    $groupedData = $this->analytic_report_financial($analytic, $united);
    return $groupedData;

  }

  public function analytic_report_financial($analytic, $united)
  {
    $analytic = $analytic
      ->groupBy('museum_id')
      ->select('museum_id', \DB::raw('SUM(total_price) as total_price'))
      ->get();

    $united = $united->groupBy('museum_id')
      ->select('museum_id', \DB::raw('SUM(total_price) as total_price'))
      ->get();



    $analytic = $analytic->toArray();
    $united = $united->toArray();

    // if ($top_museums) {
    //   return array_merge($analytic, $united);
    // }

    return $this->analyticgroupByMuseumId(array_merge($analytic, $united));

  }
  public function analyticgroupByMuseumId($data)
  {

    $groupedData = [];

    // Iterate through each item in the original data
    foreach ($data as $item) {
      $museumId = $item['museum_id'];
      $price = $item['total_price'];

      // Check if museum_id exists in $groupedData array
      if (isset($groupedData[$museumId])) {
        // If museum_id exists, add the price to the existing sum
        $groupedData[$museumId] += $price;
      } else {
        // If museum_id doesn't exist, create a new entry with the price
        $groupedData[$museumId] = $price;
      }
    }

    $museum_ids = array_keys($groupedData);
    $total_prices = array_values($groupedData);

    $museum_names = [];

    foreach ($museum_ids as $key => $value) {
      $museum_name = MuseumTranslation::where(['museum_id' => $value, 'lang' => 'am'])->first()->name;
      array_push($museum_names, $museum_name);
    }

    return [
      'total_prices' => $total_prices,
      'museum_names' => $museum_names
    ];

  }


}
