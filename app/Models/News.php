<?php

namespace App\Models;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;


class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = [
        'user_id',
    ];

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function news_translations()
    {
        return $this->hasMany(NewsTranslations::class);
    }

    public function translation($lang){

      return $this->hasOne(NewsTranslations::class)->where('lang', $lang)->first();
   }


}
