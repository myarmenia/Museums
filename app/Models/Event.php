<?php

namespace App\Models;

use App\Traits\Museum\Tickets\TicketTypeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Event extends Model
{
    use HasFactory, TicketTypeTrait;
    protected $guarded=[];
    protected $table = 'events';
    public function item_translations():HasMany
  {
    return $this->hasMany(EventTranslation::class);
  }
  public function translation($lang){

    return $this->hasOne(EventTranslation::class)->where('lang', $lang)->first();
  }
  public function images(): MorphMany
  {
    return $this->morphMany(Image::class, 'imageable');
  }
  public function event_configs():HasMany
  {
    return $this->hasMany(EventConfig::class);
  }



}
