<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseUnitedTickets extends Model
{
    use HasFactory;

  protected $guarded = [];

  public function purchased_item()
  {
    return $this->belongsTo(PurchasedItem::class, "purchased_item_id");
  }
  public function museum()
  {
    return $this->belongsTo(Museum::class, "museum_id");
  }
}
