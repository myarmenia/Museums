<?php

namespace App\Http\Controllers\API\Purchase;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\Purchase\StoreRequest;
use App\Traits\Payments\PaymentRegister;
use App\Traits\Payments\PaymentTrait;
use App\Traits\Purchase\PersonTrait;
use App\Traits\Purchase\PurchaseTrait;

class PurchaseStoreController extends BaseController
{
    use PurchaseTrait, PersonTrait, PaymentRegister, PaymentTrait;

    public function __invoke(StoreRequest $request)
    {

      $data = $request->all();
      $data['purchase_type'] = 'online';

      $purchase = $this->purchase($data);

      if (isset($purchase['error'])) {

          return $this->sendError(__('messages.' . $purchase['error']));
      }

      $redirect_url = $this->register($purchase);

      if($redirect_url == 'error_payment'){
          return $this->sendError(__('messages.error_payment'));
      }

      $responce['redirect_url'] = $redirect_url;

      return  $this->sendResponse($responce, 'success');

    }
}
