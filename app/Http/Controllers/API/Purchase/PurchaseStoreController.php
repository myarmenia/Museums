<?php

namespace App\Http\Controllers\API\Purchase;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\Purchase\StoreRequest;
use App\Traits\Payments\PaymentRegister;
use App\Traits\Payments\PaymentTrait;
use App\Traits\Purchase\PersonTrait;
use App\Traits\Purchase\PurchaseTrait;
use App\Traits\Purchase\WithoutPayment;

class PurchaseStoreController extends BaseController
{
    use PurchaseTrait, PersonTrait, PaymentRegister, PaymentTrait, WithoutPayment;

    public function __invoke(StoreRequest $request)
    {

      $data = $request->all();
      $data['purchase_type'] = 'online';


      // ============ empty items ======================
      if(!isset($data['items']) || (isset($data['items']) && count($data['items']) == 0 )){
          return $this->sendError(__('messages.ticket_not_selected'));
      }
      // ======================================================


      $purchase = $this->purchase($data);

      if (isset($purchase['error'])) {

          $name = isset($purchase['name']) ? $purchase['name'] : '';
          return $this->sendError($name . ' ' . __('messages.' .  $purchase['error']));
      }


      // ============ maount = 0 =========================
      if($purchase->amount == 0){
          $proces = $this->withoutPayment($purchase);
          return $proces ? $this->sendResponse([], 'success') : $this->sendError(__('messages.error_server'));
      }
      // ======================================================

      

      $redirect_url = $this->register($purchase);

      if($redirect_url == 'error_payment'){
          return $this->sendError(__('messages.error_payment'));
      }

      $responce['redirect_url'] = $redirect_url;

      return  $this->sendResponse($responce, 'success');

    }
}
