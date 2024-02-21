<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory, SoftDeletes;
  protected $guarded = [];


  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function ticket_types()
  {
    return $this->belongsTo(TicketType::class, 'ticket_type_id');
  }

  public function museums()
  {
    return $this->belongsTo(Museum::class, 'museum_id');
  }


}
