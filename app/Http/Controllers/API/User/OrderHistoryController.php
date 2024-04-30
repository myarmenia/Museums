<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\User\OrderHistoryResource;
use App\Traits\Users\OrderHistory;
use Illuminate\Http\Request;

class OrderHistoryController extends BaseController
{
  use OrderHistory;
    public function __invoke(){
      $order_history = $this->getorderHistory();
      return $this->sendResponse(OrderHistoryResource::collection($order_history), 'success');
    }
}
