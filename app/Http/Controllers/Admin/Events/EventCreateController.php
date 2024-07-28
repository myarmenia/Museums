<?php

namespace App\Http\Controllers\Admin\Events;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventCreateController extends Controller
{
  public function __construct(){

    $this->middleware('role:museum_admin|manager|content_manager');

  }
    public function __invoke(){
      return view("content.event.create");
    }
}
