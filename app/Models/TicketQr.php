<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketQr extends Model
{
    use HasFactory;

    protected $table = 'ticket_qrs';

    protected $fillable = [
        'museum_id',
        'purchased_item_id',
        'item_relation_id',
        'token',
        'path',
        'type',
        'price',
    ];
}
