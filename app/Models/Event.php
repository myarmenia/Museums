<?php

namespace App\Models;


use App\Traits\FilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{


    use HasFactory, SoftDeletes, FilterTrait;


    protected $guarded=[];
    protected $table = 'events';

    protected $filterFields =['museum_id'];
    protected $filterDateFields = ['start_date', 'end_date'];

    protected $filterFieldsInRelation = ['museum_geographical_location_id'];
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


  public function similar_event($museum_id,$event_id){

    return $this->where([
      ['museum_id','=',$museum_id],
      ['id','!=',$event_id]
    ] )->get();


  }




}
