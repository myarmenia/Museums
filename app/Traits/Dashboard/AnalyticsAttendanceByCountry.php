<?php
namespace App\Traits\Dashboard;

use App\Models\Country;
use App\Models\Museum;
use App\Models\MuseumTranslation;
use App\Models\Purchase;
use App\Models\PurchasedItem;
use App\Models\PurchaseUnitedTickets;
use Illuminate\Support\Facades\DB;

trait AnalyticsAttendanceByCountry
{

  public function forAllMuseum($museum_id = null)
  {
    $currentYear = now()->year;

    $country_codes = ['am', 'fr', 'ru', 'us'];
    $countryNames = Country::whereIn('key', $country_codes)->pluck('name', 'id')->toArray();

    $purchases = Purchase::where('status', 1)->whereYear('created_at', $currentYear)->with('user', 'person_purchase')->get();


    $aggregatedByCountry = [];
    $countryNames[0] = 'Այլ';


    foreach ($purchases as $purchase) {
      // Determine the country_id based on 'user' or 'person_purchase' relation
      $countryId = $purchase->user->country_id ?? ($purchase->person_purchase->country_id ?? 0);
        if($museum_id != null){
            // ========== for single useum ====================
            $item_ids = PurchasedItem::where(['type' => 'united', 'purchase_id' => $purchase->id])->pluck('id');
            $item = PurchasedItem::where(['museum_id' => $museum_id, 'purchase_id' => $purchase->id])
            ->select(\DB::raw('SUM(total_price - returned_total_price) as total_price'))
            ->pluck('total_price')->toArray();

            $united_item = PurchaseUnitedTickets::whereIn('purchased_item_id', $item_ids)->where('museum_id', $museum_id)
            ->select(\DB::raw('SUM(total_price) as total_price'))
            ->pluck('total_price')->toArray();

            $new_arr = array_sum($item) + array_sum($united_item);

              if (isset($countryNames[$countryId])) {
                if (isset($aggregatedByCountry[$countryId])) {
                  $aggregatedByCountry[$countryId] += $new_arr;
                } else {
                  $aggregatedByCountry[$countryId] = $new_arr;
                }
              } else {
                if (isset($aggregatedByCountry[0])) {
                  $aggregatedByCountry[0] += $new_arr;
                } else {
                  $aggregatedByCountry[0] = $new_arr;
                }

              }
        }
        else{
            if (isset($countryNames[$countryId])) {
              if (isset($aggregatedByCountry[$countryId])) {
                $aggregatedByCountry[$countryId] += $purchase->amount - $purchase->returned_amount;
              } else {
                $aggregatedByCountry[$countryId] = $purchase->amount - $purchase->returned_amount;
              }
            } else {
              if (isset($aggregatedByCountry[0])) {
                $aggregatedByCountry[0] += $purchase->amount - $purchase->returned_amount;
              } else {
                $aggregatedByCountry[0] = $purchase->amount - $purchase->returned_amount;
              }

            }
        }


    }

    $aggregatedByCountryWithName = [];

    $labels = [];
    $series = [];

    foreach ($countryNames as $countryId => $countryName) {

      $totalPrice = $aggregatedByCountry[$countryId] ?? 0;

      array_push($labels, $countryName);
      array_push($series, $totalPrice);

      $aggregatedByCountryWithName['array'][] = [
        'countryId' => $countryId,
        'countryName' => $countryName,
        'totalPrice' => $totalPrice,
      ];
    }

    $aggregatedByCountryWithName['statistics']['labels'] = $labels;
    $aggregatedByCountryWithName['statistics']['series'] = $series;


    return $aggregatedByCountryWithName;

  }


}
