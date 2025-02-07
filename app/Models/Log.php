<?php

namespace App\Models;

use App\Traits\FilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    use HasFactory, FilterTrait;
    protected $guarded = [];
    protected $table = 'logs';

    protected $filterFields = ['type'];

    public function user(): BelongsTo
    {
      return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }



}
