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

    public function forAllMuseum()
    {
        $country_codes = ['am', 'fr', 'ru', 'us'];
        $countryNames = Country::whereIn('key', $country_codes)->pluck('name', 'id');

        $purchases = Purchase::where('status', 1)->with('user', 'person_purchase')->get();
    $aggregatedByCountry = [];
// dd($purchases);
    // Iterate through each purchase
    foreach ($purchases as $purchase) {
      // Determine the country_id based on 'user' or 'person_purchase' relation
      $countryId = $purchase->user->country_id ?? ($purchase->person_purchase->country_id ?? 0);
dump($countryId);
      if ($countryId) {
        // Sum the prices and group by country_id
        $aggregatedByCountry[$countryId] = ($aggregatedByCountry[$countryId] ?? 0) + $purchase->price;
      }
    }

    // Fetch country names for each country_id
    // $countryNames = DB::table('countries')
    //   ->whereIn('id', array_keys($aggregatedByCountry))
    //   ->pluck('name', 'id');

    // Combine country names with aggregated data
    $aggregatedByCountryWithName = [];
    foreach ($aggregatedByCountry as $countryId => $totalPrice) {
      $countryName = $countryNames[$countryId] ?? 'Unknown';
      $aggregatedByCountryWithName[] = compact('countryId', 'countryName', 'totalPrice');
    }
        // $k = $purchase->pluck('user');
        dd($aggregatedByCountryWithName);
    }



}
