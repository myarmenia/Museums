<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporativeVisitorCount extends Model
{
    use HasFactory;

    protected $fillable = [
        'corporative_id',
        'count',
    ];

    protected $table = 'corporative_visitor_counts';

    
}
