<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OtherService extends Model
{
  use HasFactory;
  protected $guarded = [];

  public function museum(): BelongsTo
  {
    return $this->belongsTo(Museum::class, 'museum_id');
  }

  public function item_translations(): HasMany
  {
    return $this->hasMany(OtherServiceTranslation::class);
  }

  public function translation($lang)
  {

    return $this->hasOne(OtherServiceTranslation::class)->where('lang', $lang)->first();
  }

}
