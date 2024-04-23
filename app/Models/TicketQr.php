<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketQr extends Model
{
    use HasFactory;

    protected $table = 'ticket_qrs';

    protected $fillable = [
        'museum_id',
        'purchased_item_id',
        'item_relation_id',
        'token',
        'path',
        'status',
        'type',
        'price',
    ];

  public function museum()
  {
    return $this->belongsTo(Museum::class, "museum_id");
  }

  public function purchased_item()
  {
    return $this->belongsTo(PurchasedItem::class, "purchased_item_id");
  }

  //add status constants
  const STATUS_ACTIVE = 'active';
  const STATUS_EXPIRED = 'expired';
  const STATUS_USED = 'used';
  const STATUS_RETURNED = 'returned';

}
