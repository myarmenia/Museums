<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducationalProgramReservation extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function educational_program(): BelongsTo
    {
      return $this->belongsTo(EducationalProgram::class, 'educational_program_id');
    }
}
