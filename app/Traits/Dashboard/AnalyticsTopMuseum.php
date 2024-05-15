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

      uasort($museums, function ($a, $b) {
        return $b <=> $a;
      });

      $top_museums = [];
      foreach ($museums as $key => $value) {

          $top_museums[$key]['museum_name'] = Museum::find($key)->translationsForAdmin->name;
        $top_museums[$key]['total_price'] = $value;

      }

      $top_museums = array_slice($top_museums, 0, 5);

      return $top_museums;
  }

}
