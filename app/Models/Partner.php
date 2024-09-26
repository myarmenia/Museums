<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function museum()
    {
      return $this->belongsTo(Museum::class,'museum_id');
    }

}
