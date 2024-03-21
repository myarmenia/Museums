<?php
namespace App\Traits\Cart;

use App\Models\Cart;
use App\Models\Event;
use App\Models\EventConfig;
use App\Models\Product;
use App\Models\Ticket;
use App\Models\TicketSubscriptionSetting;
use Illuminate\Http\Request;


trait CartStoreTrait
{
  public function cartStore(array $data)
  {
      $user = auth('api')->user();
      $email = $user->email;
      $data['user_id'] = $user->id;
      $data['email'] = $email;

      if($data['type'] == 'product'){
        $this->makeProductData($data, $user);
        $row = $this->updateOrCreateProduct($data);

      }

      // if($data['type'] == 'event'){
          if(isset($data['tickets']) && count($data['tickets']) > 0){
              foreach ($data['tickets'] as $key => $value) {
                  $value['user_id'] = $user->id;
                  $value['email'] = $email;

                  if($value['type'] == 'event'){
                      $maked_data = $this->makeEventData($value, $user);
                      $row = $maked_data ? $this->updateOrCreateEvent($maked_data) : false;
                  }

                  if ($value['type'] == 'standart' || $value['type'] == 'discount' || $value['type'] == 'free' || $value['type'] == 'subscription') {
                      $maked_data = $this->makeTicketData($value, $user);
                      unset($maked_data['id']);
                      $row = $maked_data ? $this->updateOrCreateStandart($maked_data) : false;
                  }

                  // $data = $maked_data;
                  // $data['type'] = $value['type'];
                  // $data['user_id'] = $user->id;
                  // $data['email'] = $email;


                  // $row = $maked_data ? $this->updateOrCreateEvent($data) : false;

              }
          }
      // }

      return $row;

  }

  public function getProduct($id){
      return Product::where(['id' => $id, 'status' => 1])->first();
  }

  public function getEvent($id){
    return Event::where(['id' => $id, 'status' => 1])->first();
  }

  public function getEventConfig($id)
  {
    return EventConfig::where(['id' => $id, 'status' => 1])->first();
  }

  public function updateOrCreateProduct($data)
  {
    return Cart::updateOrCreate(['user_id' => $data['user_id'], 'product_id' => $data['product_id']], $data);
  }

  public function updateOrCreateEvent($data)
  {
    return Cart::updateOrCreate(['user_id' => $data['user_id'], 'event_config_id' => $data['id']], $data);
  }

  public function updateOrCreateStandart($data)
  {
    return Cart::updateOrCreate(['user_id' => $data['user_id'], 'museum_id' => $data['museum_id'], 'type' => $data['type']], $data);
  }

  public function getStandartTicket($id){
    return Ticket::where(['id' => $id, 'status' => 1])->first();

  }

  public function getSubscriptionTicket($id)
  {
    return TicketSubscriptionSetting::where(['id' => $id, 'status' => 1])->first();

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
    $event_config = $this->getEventConfig($data['id']);

    if(!$event_config){
      return false;
    }

    if ($event_config->event) {
      return false;
    }

    $data['museum_id'] = $event_config ? $event_config->event->museum->id : false;
    $hasEvent = $user->carts->where('event_config_id', $data['id'])->first();

    if($hasEvent){
      $quantity = $data['quantity'] + $hasEvent->quantity;
    }
    else{
      $quantity = $data['quantity'];
    }

    $total_price = $event_config->price * $quantity;

    $data['quantity'] = $quantity;
    $data['total_price'] = $total_price;

    return $data;
  }


  public function makeTicketData($data, $user)
  {
    $ticket = $data['type'] == 'subscription' ? $this->getSubscriptionTicket($data['id']) : $this->getStandartTicket($data['id']);

    if (!$ticket) {
      return false;
    }

    $data['museum_id'] = $ticket ? $ticket->museum->id : false;
    $hasRow = $user->carts->where(['museum_id' => $ticket->museum->id, 'type' => $data['type']])->first();

    if ($hasRow) {
      $quantity = $data['quantity'] + $hasRow->quantity;
    } else {
      $quantity = $data['quantity'];
    }

    $coefficient = ticketType($data['type'])->coefficient;
    $total_price = $ticket->price * $coefficient * $quantity;

    $data['quantity'] = $quantity;
    $data['total_price'] = $total_price;

    return $data;
  }


}
