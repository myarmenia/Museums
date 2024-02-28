<?php

namespace App\Models;

use App\Traits\FilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes,FilterTrait;
    protected $table = 'products';
    protected $guarded = [];
// =========================
protected $filterFields = ['product_category_id', 'museum_id'];

protected $filterFieldsInRelation = ['name'];


// fields for model translation
protected $hasRelationTranslation = ['item_translations'];

  public function category(): BelongsTo
  {
    return $this->belongsTo(ProductCategory::class, 'museum_id');
  }
  public function museum(): BelongsTo
  {
    return $this->belongsTo(Museum::class, 'museum_id');
  }

  public function item_translations():HasMany
  {
    return $this->hasMany(ProductTranslation::class);
  }
  public function images(): MorphMany
  {
    return $this->morphMany(Image::class, 'imageable');
  }
  public function translation($lang){

    return $this->hasOne(ProductTranslation::class)->where('lang', $lang)->first();
 }


}
