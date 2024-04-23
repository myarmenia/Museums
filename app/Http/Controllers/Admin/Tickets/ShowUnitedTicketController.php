<?php

namespace App\Http\Controllers\Admin\Tickets;

use App\Http\Controllers\Controller;
use App\Traits\Museum\Tickets\TicketsTrait;
use Illuminate\Http\Request;

class ShowUnitedTicketController extends Controller
{
  use TicketsTrait;
    public function __invoke()
    {

      $ticket_united = $this->getUniteddeService();

      return view("content.tickets.united", compact('ticket_united'));


    }
}
