<?php

namespace App\Models;

use App\Traits\Reports\ReportFilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use HasFactory, ReportFilterTrait;
    protected $guarded = [];
    protected $defaultFields = ['type', 'hdm_transaction_type'];   //museum_id  can be null
    protected $boolFilterFields = ['status'];
    protected $filterDateRangeFields = ['start_date', 'end_date'];
    protected $filterAgeRangeFields = ['start_age', 'end_age'];

    protected $relationFilter = [
        'person_purchase' => ['gender', 'country_id'],
        'user' => ['gender', 'country_id'],
        'purchased_items' => ['item_relation_id', 'museum_id', 'partner_id'], //nor e avelacvats
    ];

    public function purchased_items():HasMany
    {
      return $this->hasMany(PurchasedItem::class);
    }

    public function payment()
    {
      return $this->hasOne(Payment::class);
    }

    public function user()
    {
      return $this->belongsTo(User::class, "user_id");
    }

    public function person_purchase()
    {
      return $this->belongsTo(PersonPurchase::class, "person_purchase_id");
    }

    public function cashier_comment()
    {
      return $this->hasOne(CashierComment::class);
    }

}
