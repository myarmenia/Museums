<?php

namespace App\Http\Controllers\Admin\Tickets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShowTicketsController extends Controller
{
  public function __invoke()
  {

    // $data = $this->getAllEducationalPrograms();

    // return view("content.educational-programs.index", compact('data'));
    return view("content.tickets.index");

  }
}
