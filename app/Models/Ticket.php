<?php

namespace App\Models;

use App\Traits\Museum\Tickets\TicketTypeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes, TicketTypeTrait;

    protected $guarded = [];
    protected $boolFilterFields = ['status'];

  public function museum()
  {
      return $this->belongsTo(Museum::class, 'museum_id');
  }

}
