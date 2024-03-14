<?php

namespace App\Models;

use App\Traits\Museum\Tickets\TicketTypeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventConfig extends Model
{
    use HasFactory, TicketTypeTrait;
    protected $guarded=[];

}
