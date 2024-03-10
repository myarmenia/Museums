<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Museum extends Model
{
    use HasFactory;

    protected $table = 'museums';

    protected $fillable = [
        'user_id',
        'museum_geographical_location_id',
        'email',
        'account_number',
    ];

    public function museum_branches(): HasMany
    {
      return $this->hasMany(MuseumBranch::class, 'museum_id', 'id');
    }
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

    public function translationsAdmin(): HasMany
    {
        return $this->hasMany(MuseumTranslation::class, 'museum_id', 'id')->where('lang', 'am');;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


  public function tickets(): HasMany
  {
      return $this->hasMany(Ticket::class);

  }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'museum_geographical_location_id', 'id');
    }

    public function translation($lang)
    {

      return $this->hasOne(MuseumTranslation::class)->where('lang', $lang)->first();
    }


}
