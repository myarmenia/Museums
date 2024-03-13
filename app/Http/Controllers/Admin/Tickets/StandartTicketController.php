<?php

namespace App\Http\Controllers\Admin\Tickets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tickets\TicketRequest;
use App\Models\Ticket;
use App\Traits\Museum\Tickets\UpdateOrCreateTrait;
use Illuminate\Http\Request;

class StandartTicketController extends Controller
{
  use UpdateOrCreateTrait;

  public function model()
  {
    return Ticket::class;
  }

  public function __invoke(TicketRequest $request)
  {

    $ticket = $this->itemUpdateOrCreate($request);

    if ($ticket) {

      return response()->json(['result' => 'success']);
    }
  }
}
