<?php

namespace App\Http\Controllers\Admin\Events;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use App\Traits\UpdateTrait;
use Illuminate\Http\Request;

class EventUpdateController extends Controller
{
  use UpdateTrait;

  public function model()
  {
    return Event::class;
  }
  public function __invoke(EventRequest $request, string $id)
  {

    $event = $this->itemUpdate($request, $id);

    if ($event) {

      return redirect()->back();
    }

  }
}
