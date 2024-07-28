<?php

namespace App\Http\Controllers\Admin\Tickets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tickets\UnitedServiceRequest;
use App\Models\CartUnitedTickets;
use App\Models\TicketUnitedSetting;
use App\Traits\Museum\Tickets\UpdateOrCreateTrait;
use Illuminate\Http\Request;

class UnitedTicketController extends Controller
{
  use UpdateOrCreateTrait;

  public function model()
  {
    return TicketUnitedSetting::class;
  }

  public function __invoke(UnitedServiceRequest $request)
  {

    $ticket = $this->itemUpdateOrCreate($request);

    if ($ticket) {

      return response()->json(['result' => 'success']);

    }
  }
}
