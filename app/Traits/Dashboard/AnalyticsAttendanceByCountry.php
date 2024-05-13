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

    // ========== for single useum ====================
    if($museum_id != null){
        $purchased_items = $purchases->pluck('purchased_items')->flatten()->where('museum_id', $museum_id)->pluck('purchase_id')->toArray();
        $purchase_united_tickets = $purchases->pluck('purchased_items')->flatten()
          ->pluck('purchase_united_tickets')->flatten()->where('museum_id', $museum_id)
          ->pluck('purchased_item')->pluck('purchase_id')->toArray();

        $purchases_ids = array_merge($purchased_items, $purchase_united_tickets);
        $purchases = Purchase::whereIn('id', $purchases_ids)->get();
    }
    // ========== end for single useum ====================

    $aggregatedByCountry = [];
    $countryNames[0] = 'Այլ';



    foreach ($purchases as $purchase) {
      // Determine the country_id based on 'user' or 'person_purchase' relation
      $countryId = $purchase->user->country_id ?? ($purchase->person_purchase->country_id ?? 0);


      if (isset($countryNames[$countryId])) {
        if (isset($aggregatedByCountry[$countryId])) {
          $aggregatedByCountry[$countryId] += $purchase->amount;
        } else {
          $aggregatedByCountry[$countryId] = $purchase->amount;
        }
      } else {
        if (isset($aggregatedByCountry[0])) {
          $aggregatedByCountry[0] += $purchase->amount;
        } else {
          $aggregatedByCountry[0] = $purchase->amount;
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
