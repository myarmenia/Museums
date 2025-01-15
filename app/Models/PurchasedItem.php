<?php

namespace App\Models;

use App\Traits\Reports\ReportFilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchasedItem extends Model
{
    use HasFactory, ReportFilterTrait, SoftDeletes;
    protected $guarded = [];
    // protected $defaultFields = ['museum_id'] commentats e eghel;
    protected $defaultFields = ['item_relation_id', 'museum_id', 'partner_id', 'partner_relation_id'];

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

    public function product()
    {
      return $this->belongsTo(Product::class, "item_relation_id");
    }

    public function event_config()
    {
        return $this->belongsTo(EventConfig::class, 'item_relation_id');
    }

    public function event()
    {
      return $this->belongsTo(Event::class, 'item_relation_id');
    }
    public function standart_ticket()
    {

      return $this->belongsTo(Ticket::class, 'item_relation_id');

    }

    public function united_museums()
    {

      $united_tikets_museums_ids = $this->purchase_united_tickets->pluck('museum_id');
      return Museum::whereIn('id', $united_tikets_museums_ids)->get();

    }

    public function ticketQr(): HasMany
    {
      return $this->hasMany(TicketQr::class);

    }
    public function other_service(): BelongsTo
    {
      return $this->belongsTo(OtherService::class ,'item_relation_id');
    }

}
