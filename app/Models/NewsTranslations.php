<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsTranslations extends Model
{
    use HasFactory;

    protected $table = 'news_translations';

    protected $fillable = [
        'news_id',
        'title',
        'description',
        'lang',
    ];
}
