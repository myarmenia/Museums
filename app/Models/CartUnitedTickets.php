<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartUnitedTickets extends Model
{
    use HasFactory;
    protected $guarded = [];

  public function carts()
  {
    return $this->belongsToMany(Cart::class, 'cart_id');
  }
}
