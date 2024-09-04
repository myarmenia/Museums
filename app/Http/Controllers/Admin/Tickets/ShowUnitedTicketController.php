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
      $ticket_school = $this->getSchoolService();

      return view("content.tickets.ticket", compact('ticket_united', 'ticket_school'));


    }
}
