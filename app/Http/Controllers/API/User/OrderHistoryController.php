<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Traits\Users\OrderHistory;
use Illuminate\Http\Request;

class OrderHistoryController extends Controller
{
  use OrderHistory;
    public function __invoke(){
      $order_history = $this->getorderHistory();
      return $this->sendResponse($order_history, 'success');
    }
}
