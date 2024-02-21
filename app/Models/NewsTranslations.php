<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsTranslations extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'news_translations';

    protected $fillable = [
        'news_id',
        'title',
        'description',
        'lang',
        // 'created_at',
        // 'updated_at',
    ];
    public function news(){
      return $this->belongsTo(News::class,'news_id');
    }
}
