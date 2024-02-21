<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneNumber extends Model
{
    use HasFactory;

    protected $table = 'phone_numbers';

    protected $fillable = [
        'museum_id',
        'number',
        'phone_name'
    ];

    public $timestamps = false;
}
