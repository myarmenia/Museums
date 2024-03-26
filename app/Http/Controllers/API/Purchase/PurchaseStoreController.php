<?php

namespace App\Http\Controllers\API\Purchase;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Purchase\StoreRequest;
use App\Models\Purchase;
use App\Models\PurchasedItem;
use App\Traits\Cart\ItemStoreTrait;
use App\Traits\getPurchaseUniqueTokenTraite;
use App\Traits\Purchase\PersonTrait;
use App\Traits\Purchase\PurchaseTrait;
use Illuminate\Http\Request;

class PurchaseStoreController extends BaseController
{
    use PurchaseTrait, getPurchaseUniqueTokenTraite, PersonTrait;

    public function __invoke(StoreRequest $request)
    {

      $data = $request->all();
      $data['type'] = 'online';

      // $user = auth('api')->user();

      // if (isset ($request['person']) && count($request['person']) > 0) {
      //     $person = $this->createPerson($request['person']);
      //     $request['email'] = $user->email;
      //     $request['type'] = 'online';
      //     $request['person_id'] = $person->id;
      // }

      $item_store = $this->itemStore($data);

      if (!$item_store) {
        return $this->sendError('error');
      }

      // $products = $this->products($user);
      // $tickets = $this->tickets($user);


      // $cart = $this->getCartItems($user);
      // $data = new CartResource(['products' => $products, 'tickets' => $tickets]);

      // $parrams['items_count'] = $cart->count();

      return $this->sendResponse($data, 'success', $parrams);



    }
}
