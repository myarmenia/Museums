<?php

namespace App\Http\Controllers\return_ticket;

use App\Http\Controllers\Controller;
use App\Services\Ticket\ReturnTicketService;
use Illuminate\Http\Request;

class ReturnTicketController extends Controller
{
    private $returnTicketService;

    public function __construct(ReturnTicketService $returnTicketService)
    {
        $this->returnTicketService = $returnTicketService;
    }
    public function index()
    {
        return view('content.return-ticket.index');
    }

    public function checkTicket($token)
    {
        $token = $this->returnTicketService->checkToken($token);

        return response()->json($token);
    }

    public function removeTicket(Request $request)
    {

        $token = $this->returnTicketService->removeToken($request->all());

        return response()->json($token);
    }
}
