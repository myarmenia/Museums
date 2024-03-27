<?php

namespace App\Http\Controllers\API\Purchase;

use App\Http\Controllers\Controller;
use App\Traits\Payments\CheckPaymentStatusTrait;
use Illuminate\Http\Request;

class PaymentResultController extends Controller
{
  use CheckPaymentStatusTrait;
    public function __invoke(Request $request)
    {
   
          $order_number = $request->order_number;
          $payment_status = $this->checkStatus($order_number);
    }
}
