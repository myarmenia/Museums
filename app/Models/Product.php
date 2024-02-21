<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];


  public function category(): BelongsTo
  {
    return $this->belongsTo(ProductCategory::class, 'museum_id');
  }
  public function museum(): BelongsTo
  {
    return $this->belongsTo(Museum::class, 'museum_id');
  }

  public function translations()
  {
    return $this->hasMany(ProductTranslation::class);
  }

}
