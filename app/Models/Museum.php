<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Museum extends Model
{
    use HasFactory;

    protected $table = 'museums';

    protected $fillable = [
        'user_id',
        'museum_geographical_location_id',
        'email',
        'account_number',
        // 'working_hours'
    ];

    public function phones(): HasMany
    {
        return $this->hasMany(PhoneNumber::class, 'museum_id', 'id');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function links(): MorphMany
    {
        return $this->morphMany(Link::class, 'linkable');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(MuseumTranslation::class, 'museum_id', 'id');
    }

}
