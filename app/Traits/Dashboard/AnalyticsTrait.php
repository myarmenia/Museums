<?php
namespace App\Traits\Dashboard;

use App\Models\Museum;
use App\Models\MuseumTranslation;
use App\Models\Purchase;
use App\Models\PurchasedItem;
use App\Models\PurchaseUnitedTickets;
use Illuminate\Support\Facades\DB;

trait AnalyticsTrait
{

    public function allMuseum()
    {
      return Museum::all();
    }
    public function ticketsType($museum_id = null)
    {
      $currentYear = now()->year;
      $purchases_ids = Purchase::where('status', 1)->whereYear('created_at', $currentYear)->pluck('id');
      $analytic = PurchasedItem::whereIn('purchase_id', $purchases_ids);

      if ($museum_id != null) {
        $analytic = $analytic->where('museum_id', $museum_id);
      }

      $analytic = $analytic
        ->groupBy('type')
        ->select(\DB::raw('MAX(type) as type'), \DB::raw('SUM(total_price) as total_price'), \DB::raw('SUM(quantity) as quantity'))
        ->get();

      $analytic = $this->groupTypeKey($analytic);

      return $analytic;

    }

    public function groupTypeKey($data)
    {

      $result = [];
      foreach ($data as $key => $value) {
        $result[$value->type] = $value;
      }

      return $result;

    }


    public function totalRevenue($top_museums = null)
    {
        $currentYear = now()->year;

        $purchases = Purchase::where('status', 1)->whereYear('created_at', $currentYear)->pluck('id');
        $purchased_items = PurchasedItem::whereIn('purchase_id', $purchases);

        $analytic = $purchased_items->where('type', '!=', 'united');

        $purchased_items = PurchasedItem::whereIn('purchase_id', $purchases);
        $purchased_item_id = $purchased_items->where('type', 'united')->pluck('id');


        $united = [];
        $united = PurchaseUnitedTickets::whereIn('purchased_item_id', $purchased_item_id);

        $groupedData = $this->analytic_financial($analytic, $united, $top_museums);
        return $groupedData;

    }

    public function analytic_financial($analytic, $united, $top_museums = null)
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

      if($top_museums){
        return array_merge($analytic, $united);
      }

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
          'item_names' => $museum_names
        ];

    }


}
