<?php

namespace App\Models;

use App\Traits\StoreTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use HasFactory , SoftDeletes;
    protected $guardet=[];

    protected $table = 'banners';

    public function item_translations():HasMany
    {
      return $this->hasMany(BannerTranslation::class);
    }
    public function images(): MorphMany
    {
      return $this->morphMany(Image::class, 'imageable');
    }
    public function translation($lang){

      return $this->hasOne(BannerTranslation::class)->where('lang', $lang)->first();
   }


}
