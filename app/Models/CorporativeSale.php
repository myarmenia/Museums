<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CorporativeSale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'tin',
        'file_path',
        'museum_id',
        'email',
        'contract_number',
        'tickets_count',
        'visitors_count',
        'price',
        'coupon',
        'ttl_at',
    ];

    protected $table = 'corporative_sales';

    public function museum(): BelongsTo
    {
      return $this->belongsTo(Museum::class, 'museum_id');
    }
}
