<?php

namespace App\Models;

use App\Traits\Reports\ReportFilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasedItem extends Model
{
    use HasFactory, ReportFilterTrait;
    protected $guarded = [];
    protected $defaultFields = ['museum_id'];

    protected $relationFilter = [
      // 'user' => ['gender', 'country_id'],
      // 'person_purchase' => ['gender', 'country_id'],
      'purchase' => ['age','status','type','gender', 'country_id'],

    ];

    public function museum()
    {
      return $this->belongsTo(Museum::class, "museum_id");
    }

    public function purchase()
    {
      return $this->belongsTo(Purchase::class, "purchase_id");
    }

    public function purchase_united_tickets()
    {
      return $this->hasMany(PurchaseUnitedTickets::class);
    }


}
