<?php
namespace App\Traits\Dashboard;

use App\Models\Country;
use App\Models\Museum;
use App\Models\MuseumTranslation;
use App\Models\Purchase;
use App\Models\PurchasedItem;
use App\Models\PurchaseUnitedTickets;
use Illuminate\Support\Facades\DB;

trait AnalyticsTopMuseum
{

  public function getTopMuseum()
  {
      $museums = $this->totalRevenue('top');

      usort($museums, function ($a, $b) {

          $priceA = (int) $a['total_price'];
          $priceB = (int) $b['total_price'];


          if ($priceA == $priceB) {
            return 0;
          }
          return ($priceA < $priceB) ? 1 : -1;
      });

      $top_museums = array_slice($museums, 0, 5);

      foreach ($top_museums as $key => $value) {

        $top_museums[$key]['museum_name'] = Museum::find($value['museum_id'])->translationsForAdmin->name;
      }

      
      return $top_museums;
  }



}
