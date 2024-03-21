<?php

namespace App\Http\Controllers\Admin\cashier;

use App\Http\Controllers\Controller;
use App\Services\Cashier\CashierService;
use Illuminate\Http\Request;

class CashierController extends Controller
{
   public $cashierService;

   public function __construct(CashierService $cashierService) 
   {
      $this->cashierService = $cashierService;
   }

   public function index(Request $request)
   {
      $data = $this->cashierService->getAllData();
      return view('content.cashier.create', compact('data'));
   }

   public function createTicket(Request $request)
   {
      dd($request->all());
   }
}
