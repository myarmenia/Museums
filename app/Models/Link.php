<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Link extends Model
{
    use HasFactory;

    protected $table = 'links';

    protected $fillable = [
        'linkable_id',
        'linkable_type',
        'path',
        'name',
    ];

    public function linkable(): MorphTo
    {
        return $this->morphTo();
    }
}
