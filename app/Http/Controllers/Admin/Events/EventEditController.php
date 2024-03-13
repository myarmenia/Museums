<?php

namespace App\Http\Controllers\Admin\Events;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Traits\Event\EventTrait;
use Illuminate\Http\Request;

class EventEditController extends Controller
{
  use  EventTrait;
  public function __construct(){

    $this->middleware('role:museum_admin');

  }
    public function __invoke($id){

      $data = $this->getEvent($id);

      return view('content.event.edit',compact('data'));

    }
}
