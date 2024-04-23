<?php

namespace App\Models;

use App\Traits\Museum\Tickets\TicketTypeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketSubscriptionSetting extends Model
{
    use HasFactory, TicketTypeTrait;
  protected $guarded = [];

  protected $boolFilterFields = ['status'];


  public function museum()
  {
    return $this->belongsTo(Museum::class, 'museum_id');
  }
}
