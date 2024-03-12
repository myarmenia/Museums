<?php

namespace App\Http\Controllers\Admin\Tickets;

use App\Http\Controllers\Controller;
use App\Traits\Museum\Tickets\TicketsTrait;
use Illuminate\Http\Request;

class ShowTicketsController extends Controller
{
  use TicketsTrait;
  public function __invoke()
  {

    $ticket_standart = $this->getStandart();
    $ticket_subscription = $this->getSubscription();
    // $data = $this->getAllEducationalPrograms();

    // return view("content.educational-programs.index", compact('data'));
    return view("content.tickets.index", compact('ticket_standart','ticket_subscription'));

  }
}
