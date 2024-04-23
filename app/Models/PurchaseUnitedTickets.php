<?php

namespace App\Models;

use App\Traits\Reports\ReportFilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseUnitedTickets extends Model
{
    use HasFactory, ReportFilterTrait;

  protected $guarded = [];
  protected $table = 'purchase_united_tickets';

  // protected $defaultFields = ['museum_id'];
  protected $relationFilter = [
    'purchased_item' => ['age', 'gender', 'country_id'],
  ];


  public function purchased_item()
  {
    return $this->belongsTo(PurchasedItem::class, "purchased_item_id");
  }
  public function museum()
  {
    return $this->belongsTo(Museum::class, "museum_id");
  }
}
