<?php

namespace App\Http\Controllers\return_ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReturnTicketRequest;
use App\Models\TicketQr;
use App\Services\Ticket\ReturnTicketService;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

    public function removeTicket($token)
    {
        $token = $this->returnTicketService->removeToken($token);

        return response()->json($token);
    }
}
