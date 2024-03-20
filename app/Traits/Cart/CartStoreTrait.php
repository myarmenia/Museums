<?php
namespace App\Traits\Cart;

use App\Models\Cart;
use App\Models\Event;
use App\Models\Product;
use Illuminate\Http\Request;


trait CartStoreTrait
{
  public function cartStore(array $data)
  {
      $user = auth('api')->user();
      $email = $user->email;
      $data['user_id'] = $user->id;
      $data['email'] = $email;

dd($data);

      if($data['type'] == 'product'){
        $this->makeProductData($data, $user);
        $row = $this->updateOrCreateProduct($data);

      }
      if($data['type'] == 'event'){
        $this->makeProductData($data, $user);
        $user->carts->sync($data);
        // $row = $this->updateOrCreateEvent($data);

      }

      return $row;
// dd($row);
      // return Ticket::where("museum_id", museumAccessId())->first();
  }

  public function getProduct($id){
      return Product::find($id);
  }

  public function getEvent($id){
    return Event::find($id);
  }

  public function updateOrCreateProduct($data)
  {
    return Cart::updateOrCreate(['user_id' => $data['user_id'], 'product_id' => $data['product_id']], $data);
  }

  public function getCartItems()
  {

    $user = auth('api')->user();
    $cart_items = Cart::where('user_id', $user->id)->get();
    return $cart_items;
  }

  public function makeProductData($data, $user){
      $product = $this->getProduct($data['product_id']);
      $data['museum_id'] = $product->museum->id;

      $hasProduct = $user->carts->where('product_id', $data['product_id'])->first();

      if($hasProduct){
        $quantity = $data['quantity'] + $hasProduct->quantity;
      }
      else{
        $quantity = $data['quantity'];
      }

      $total_price = $product->price * $quantity;

      $data['quantity'] = $quantity;
      $data['total_price'] = $total_price;

      return $data;
  }

  public function makeEventData($data, $user){
    $event = $this->getEvent($data['event_id']);
    $data['museum_id'] = $event->museum->id;

    $hasEvent = $user->carts->where('event_id', $data['event_id'])->first();

    if($hasEvent){
      $quantity = $data['quantity'] + $hasEvent->quantity;
    }
    else{
      $quantity = $data['quantity'];
    }

    $total_price = $event->price * $quantity;

    $data['quantity'] = $quantity;
    $data['total_price'] = $total_price;

    return $data;
}


}
