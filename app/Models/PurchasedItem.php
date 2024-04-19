<?php

namespace App\Models;

use App\Traits\Reports\ReportFilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasedItem extends Model
{
    use HasFactory, ReportFilterTrait;
    protected $guarded = [];
    // protected $defaultFields = ['museum_id'];

    protected $relationFilter = [
      'purchase' => ['status','type', 'gender', 'country_id', 'start_date', 'end_date', 'start_age', 'end_age'],
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

  public function event_config()
  {

      return $this->belongsTo(EventConfig::class, 'item_relation_id');

  }
  public function standart_ticket()
  {
    // return EventConfig::where('id', $this->item_relation_id)->first();
    return $this->belongsTo(Ticket::class, 'item_relation_id');

  }

  public function united_museums()
  {

    $united_tikets_museums_ids = $this->purchase_united_tickets->pluck('museum_id');
    return Museum::whereIn('id', $united_tikets_museums_ids)->get();

  }


}
