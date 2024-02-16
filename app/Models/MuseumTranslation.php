<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MuseumTranslation extends Model
{
    use HasFactory;

    protected $table = 'museum_translations';

    protected $fillable = [
        'museum_id',
        'name',
        'description',
        'working_days',
        'director_name',
        'addres'
    ];
}
