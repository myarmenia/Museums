<?php

namespace App\Http\Controllers\Admin\Tickets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StandartTicketStoreController extends Controller
{
  public function __invoke(Request $request)
  {
dd($request->id);


  }
}
