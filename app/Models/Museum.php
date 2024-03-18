<?php

namespace App\Models;

use App\Traits\FilterTrait;
use App\Traits\ModelFilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Museum extends Model
{
    use HasFactory, ModelFilterTrait;

    protected $table = 'museums';

    protected $fillable = [
        'user_id',
        'museum_geographical_location_id',
        'email',
        'account_number',
    ];

    protected $relationFilter = [
        'events' => ['start_date', 'end_date','status','museum_id']

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

    public function getCurrentTranslation(): HasMany
    {
        return $this->hasMany(MuseumTranslation::class, 'museum_id', 'id')->where('lang', session('languages'));
    }

    public function translationsAdmin(): HasMany
    {
        return $this->hasMany(MuseumTranslation::class, 'museum_id', 'id')->where('lang', 'am');;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'museum_geographical_location_id', 'id');
    }

    public function translation($lang)
    {

      return $this->hasOne(MuseumTranslation::class)->where('lang', $lang)->first();
    }
    public function events(){
      return $this->hasMany(Event::class);
    }


    public function standart_tickets(): HasOne
    {
      return $this->hasOne(Ticket::class);

    }

    public function subscription_tickets(): HasOne
    {
      return $this->hasOne(TicketSubscriptionSetting::class);

    }

    public function united_ticket_price(){
      return $this->standart_tickets->price - ($this->standart_tickets->price * ticketType('united')->coefficient);
    }



}
