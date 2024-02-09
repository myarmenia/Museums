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
        'news_category_id',
    ];

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function translations()
    {
        return $this->hasMany(NewsTranslations::class, 'news_id', 'id');
    }

    public function category()
    {
        return $this->hasOne(NewsCategory::class, 'id', 'news_category_id');
    }
    public function newstranslation($lang){

      return $this->hasOne(NewsTranslations::class)->where('lang', $lang)->first();
   }


}
