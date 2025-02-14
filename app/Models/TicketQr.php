<?php

namespace App\Models;

use App\Traits\FilterTrait;
use App\Traits\Reports\ReportFilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketQr extends Model
{
    use HasFactory, ReportFilterTrait;

    protected $table = 'ticket_qrs';

    protected $defaultFields = ['museum_id'];
  protected $fillable = [
        'museum_id',
        'purchased_item_id',
        'item_relation_id',
        'token',
        'ticket_token',
        'path',
        'status',
        'type',
        'price',
        'visited_date'
    ];

  public function museum()
  {
    return $this->belongsTo(Museum::class, "museum_id");
  }

  public function purchased_item()
  {
    return $this->belongsTo(PurchasedItem::class, "purchased_item_id");
  }

  public function event_config()
  {
    return $this->belongsTo(EventConfig::class, 'item_relation_id');
  }

  public function event()
  {
    return $this->belongsTo(Event::class, 'item_relation_id')->where('style', 'temporary');
  }

  public function event_with_trashed()
  {
    return $this->belongsTo(Event::class, 'item_relation_id')->where('style', 'temporary')->withTrashed();
  }

  public function corporative()
  {
    return $this->belongsTo(CorporativeSale::class, 'item_relation_id');
  }

  public function accesses(): HasMany
  {
      return $this->hasMany(TicketAccess::class, 'ticket_qr_id');
  }

  public function scopeValid($query, $qr, $museum_ids)
  {
    // return $query->where([
    //         "token" => $qr,
    //         "museum_id" => $museum_id,
    //         'status' => 'active'
    //       ]);

    return $query->where([
        "token" => $qr,
        'status' => 'active'
    ])->whereIn('museum_id', $museum_ids);
  }



  //add status constants
  const STATUS_ACTIVE = 'active';
  const STATUS_EXPIRED = 'expired';
  const STATUS_USED = 'used';
  const STATUS_RETURNED = 'returned';



}
