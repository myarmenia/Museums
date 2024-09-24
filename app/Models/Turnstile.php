<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turnstile extends Model
{
    use HasFactory;
    protected $table = 'turnstiles';

    protected $guarded = [];

    public function scopeMuseum($query, $mac)
    {
      return $query->where('mac', $mac);
    }

    public function get_museum()
    {
      return $this->belongsTo(Museum::class, 'museum_id');
    }
}
