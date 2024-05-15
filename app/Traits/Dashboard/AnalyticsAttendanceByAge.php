<?php
namespace App\Traits\Dashboard;

use App\Models\Country;
use App\Models\Museum;
use App\Models\MuseumTranslation;
use App\Models\Purchase;
use App\Models\PurchasedItem;
use App\Models\PurchaseUnitedTickets;
use Illuminate\Support\Facades\DB;

trait AnalyticsAttendanceByAge
{

    public function groupAgeForAllMuseum($museum_id = null)
    {
        $currentYear = now()->year;

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


        $analytics = [
          'junior' => 0,
          'young' => 0,
          'old' => 0,
          'unknown' => 0,
        ];

        foreach ($purchases as $purchase) {
          $user = $purchase->user;
          $person = $purchase->person_purchase;
          if($user != null){
              $user_age = $user->birth_date != null ? getAge($user->birth_date) : null;
              $ageGroup = $this->getAgeGroup($user_age);
          }
          else if( $person != null){
              $person_age = $person->age != null ? $person->age : null;
              $ageGroup = $this->getAgeGroup($person_age);
          }
          else{
            $ageGroup = $this->getAgeGroup(null);
          }


          if($museum_id != null){
                // ========== for single museum ====================
                $item_ids = PurchasedItem::where(['museum_id' => $museum_id, 'purchase_id' => $purchase->id])->pluck('id');
                $item = PurchasedItem::where(['museum_id' => $museum_id, 'purchase_id' => $purchase->id])
                ->select(\DB::raw('SUM(total_price - returned_total_price) as total_price'))
                ->pluck('total_price')->toArray();

                $united_item = PurchaseUnitedTickets::whereIn('purchased_item_id', $item_ids)->where('museum_id', $museum_id)
                ->select(\DB::raw('SUM(total_price) as total_price'))
                ->pluck('total_price')->toArray();

                $new_arr = array_sum($item) + array_sum($united_item);

                if ($ageGroup) {
                  $analytics[$ageGroup] += $new_arr;
                } else {
                  $analytics['unknown'] += $new_arr;
                }
          }
          else{
              if ($ageGroup) {
                $analytics[$ageGroup] += $purchase->amount - $purchase->returned_amount;
              } else {
                $analytics['unknown'] += $purchase->amount - $purchase->returned_amount;
              }

          }


        }

        return [
          'age_analytics' => array_values($analytics),
          'age_analytics_asoc_arr' => $analytics,
          'age_total_amount' => array_sum(array_values($analytics))
        ];

    }


    public static function getAgeGroup($age)
    {
        $groups = getAgeRanges();
        $groups['unknown'] = ['start_age' => null, 'end_age' => null];

        if ($age === null) {
          return 'unknown'; // Map NULL ages to "unknown" category
        }

        foreach ($groups as $groupName => $range) {
          if ($age >= $range['start_age'] && $age <= $range['end_age']) {
            return $groupName;
          }
        }

        return null;
    }


}
