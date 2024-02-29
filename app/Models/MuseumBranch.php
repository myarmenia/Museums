<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MuseumBranch extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function images(): MorphMany
    {
      return $this->morphMany(Image::class, 'imageable');
    }

    public function links(): MorphMany
    {
      return $this->morphMany(Link::class, 'linkable');
    }

    public function museum_branche_translations(): HasMany
    {
      return $this->hasMany(MuseumBrancheTranslation::class);
    }

    public function museum(): BelongsTo
    {
      return $this->belongsTo(Museum::class, 'museum_id');
    }
    public function translation($lang){

      return $this->hasOne(MuseumBrancheTranslation::class)->where('lang', $lang)->first();
   }

}
