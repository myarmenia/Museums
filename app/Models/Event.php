<?php

namespace App\Models;


use App\Traits\FilterTrait;
use App\Traits\ModelFilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Event extends Model
{


    use HasFactory, SoftDeletes, Notifiable, FilterTrait;


    protected $guarded=[];
    protected $table = 'events';

    protected $defaultFillableFields = ['museum_id'];
    protected $boolFilterFields = ['status'];
    protected $filterDateRangeFields = ['start_date', 'end_date'];

    protected $filterFields =['museum_id'];
    protected $filterFieldsInRelation = ['museum_geographical_location_id','museum_id'];

    protected $hasRelation = ['museum'];



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
  public function museum():BelongsTo
  {
    return $this->belongsTo(Museum::class,'museum_id');
  }
  public function image(): MorphOne
  {
    return $this->morphOne(Image::class, 'imageable');
  }


  public function similar_event($museum_id,$event_id){

    return $this->where([
      ['museum_id','=',$museum_id],
      ['id','!=',$event_id]
    ] )->get();


  }



}
