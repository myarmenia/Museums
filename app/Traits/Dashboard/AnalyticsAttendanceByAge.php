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

    public function groupAgeForAllMuseum()
    {
        $currentYear = now()->year;

        $purchases = Purchase::where('status', 1)->whereYear('created_at', $currentYear)->with('user', 'person_purchase')->get();
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


          if ($ageGroup) {
            $analytics[$ageGroup] += $purchase->amount;
          } else {
            $analytics['unknown'] += $purchase->price;
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
