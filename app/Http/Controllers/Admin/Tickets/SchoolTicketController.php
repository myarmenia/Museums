<?php

namespace App\Http\Controllers\Admin\Tickets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tickets\SchoolServiceRequest;
use App\Models\TicketSchoolSetting;
use App\Traits\Museum\Tickets\UpdateOrCreateTrait;
use Illuminate\Http\Request;

class SchoolTicketController extends Controller
{
  use UpdateOrCreateTrait;

  public function model()
  {
    return TicketSchoolSetting::class;
  }

  public function __invoke(SchoolServiceRequest $request)
  {

    $ticket = $this->itemUpdateOrCreate($request);

    if ($ticket) {

      return response()->json(['result' => 'success']);

    }
  }
}
