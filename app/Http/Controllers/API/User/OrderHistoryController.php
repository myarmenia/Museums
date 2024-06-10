<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\User\OrderHistoryResource;
use App\Traits\Paginate;
use App\Traits\Paginator;
use App\Traits\Users\OrderHistory;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderHistoryController extends BaseController
{
  use OrderHistory, Paginator;
    public function __invoke(Request $request){

      $page = request()->page ?? 1;
      $perPage = 2;

      $order_history = $this->getorderHistory();

      $orderHistoryResourceCollection = OrderHistoryResource::collection($order_history);

      $order_history = $this->arrayPaginator($orderHistoryResourceCollection, $request, $perPage);
      $params = [
        'page_count' => $order_history->lastPage(),
        'total_count' => $order_history->total(),
        'unread_notification_count' => $order_history->count()
      ];

      $order_history = array_values($order_history->items());
      return $this->sendResponse($order_history, 'success', $params);
    }


}
