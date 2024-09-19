<?php

namespace App\Http\Controllers\API\Purchase;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Services\Log\LogService;
use App\Traits\NodeApi\QrTokenTrait;
use App\Traits\Payments\CheckPaymentStatusTrait;
use App\Traits\Payments\PaymentCompletionTrait;
use App\Traits\Payments\PaymentTrait;
use App\Traits\Purchase\UpdateItemQuantityTrait;
use Illuminate\Http\Request;

class PaymentResultController extends BaseController
{
  use CheckPaymentStatusTrait, PaymentCompletionTrait, PaymentTrait, UpdateItemQuantityTrait, QrTokenTrait;
    public function __invoke(Request $request)
    {

          $order_number = $request->order_number;
          $payment_result = $this->checkStatus($order_number);
          
          // ============= test ================
          LogService::store(null, 1, 'payment', 'store');

          if($payment_result){
                    $payment_completion = $this->paymentCompletion($payment_result, $order_number);
                    return $payment_completion;
                }

          }
}
