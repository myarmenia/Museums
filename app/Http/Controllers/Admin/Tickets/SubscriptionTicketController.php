<?php

namespace App\Http\Controllers\Admin\Tickets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tickets\TicketRequest;
use App\Models\TicketSubscriptionSetting;
use App\Traits\Museum\Tickets\UpdateOrCreateTrait;
use Illuminate\Http\Request;

class SubscriptionTicketController extends Controller
{
  use UpdateOrCreateTrait;

  public function model()
  {
    return TicketSubscriptionSetting::class;
  }

  public function __invoke(TicketRequest $request)
  {

    $ticket = $this->itemUpdateOrCreate($request);

    if ($ticket) {

      return response()->json(['result' => 'success']);

    }
  }
}
