<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EducationalProgram extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'educational_programs';
    protected $guarded = [];

    public function museum(): BelongsTo
    {
      return $this->belongsTo(Museum::class, 'museum_id');
    }

    public function item_translations():HasMany
    {
      return $this->hasMany(EducationalProgramTranslation::class);
    }

    public function translation($lang){

      return $this->hasOne(EducationalProgramTranslation::class)->where('lang', $lang)->first();
   }
   public function translationAm()
   {
    
       return $this->hasOne(EducationalProgramTranslation::class, 'educational_program_id')
                   ->where('lang', 'am');
   }


}
