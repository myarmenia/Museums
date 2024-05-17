<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketPdf extends Model
{
    use HasFactory;

    protected $table = 'ticket_pdf';

    protected $fillable = [
        'museum_id',
        'pdf_path',
    ];
}
