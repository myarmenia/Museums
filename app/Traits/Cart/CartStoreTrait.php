<?php
namespace App\Traits\Cart;

use App\Models\Cart;
use App\Models\CartUnitedTickets;
use App\Models\Event;
use App\Models\EventConfig;
use App\Models\Museum;
use App\Models\Product;
use App\Models\Ticket;
use App\Models\TicketSubscriptionSetting;
use App\Models\TicketUnitedSetting;
use Illuminate\Http\Request;


trait CartStoreTrait
{
  public function cartStore(array $data)
  {
    $user = auth('api')->user();
    $email = $user->email;
    $data['user_id'] = $user->id;
    $data['email'] = $email;

    if ($data['type'] == 'product') {
        $data = $this->makeProductData($data, $user);
        $row = $this->updateOrCreateProduct($data);

    }

    if (isset ($data['tickets']) && count($data['tickets']) > 0) {

        foreach ($data['tickets'] as $key => $value) {
            $value['user_id'] = $user->id;
            $value['email'] = $email;

            if ($value['type'] == 'event') {
            
                $maked_data = $this->makeEventData($value, $user);
                unset($maked_data['id']);
                $row = $maked_data ? $this->updateOrCreateEvent($maked_data) : false;
            }

            if ($value['type'] == 'standart' || $value['type'] == 'discount' || $value['type'] == 'free' || $value['type'] == 'subscription') {
                $maked_data = $this->makeTicketData($value, $user);
                unset($maked_data['id']);
                $row = $maked_data ? $this->updateOrCreateStandart($maked_data) : false;
            }

            if ($value['type'] == 'united') {
                $maked_data = $this->makeUnitedTicketData($value, $user);

                unset($maked_data['museum_ids']);
                $row = $maked_data ? $this->updateOrCreateUnitedTickets($maked_data) : false;

            }


        }
    }

    return $row;

  }

  public function getProduct($id)
  {
    return Product::where(['id' => $id, 'status' => 1])->first();
  }

  public function getEvent($id)
  {
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

    return Cart::updateOrCreate(['user_id' => $data['user_id'], 'event_config_id' => $data['event_config_id']], $data);
  }

  public function updateOrCreateStandart($data)
  {
    return Cart::updateOrCreate(['user_id' => $data['user_id'], 'museum_id' => $data['museum_id'], 'type' => $data['type']], $data);
  }

  public function updateOrCreateUnitedTickets($data)
  {

    $united = $data['united'];
    unset($data['museum_ids']);
    unset($data['united']);

    $cart = Cart::create($data);

    foreach ($united as $key => $value) {
      $value['cart_id'] = $cart->id;
      CartUnitedTickets::create($value);
    }

    return $cart;
  }

  public function getStandartTicket($id)
  {
    return Ticket::where(['id' => $id, 'status' => 1])->first();

  }

  public function getSubscriptionTicket($id)
  {
    return TicketSubscriptionSetting::where(['id' => $id, 'status' => 1])->first();

  }


  public function makeProductData($data, $user)
  {
      $product = $this->getProduct($data['product_id']);
      $data['museum_id'] = $product->museum->id;

      $hasProduct = $user->carts()->where('product_id', $data['product_id'])->first();

      if ($hasProduct) {
        $quantity = $data['quantity'] + $hasProduct->quantity;
      } else {
        $quantity = $data['quantity'];
      }

      $total_price = $product->price * $quantity;

      $data['quantity'] = $quantity;
      $data['total_price'] = $total_price;

      return $data;
  }

  public function makeEventData($data, $user)
  {

      $event_config = $this->getEventConfig($data['id']);

      if (!$event_config) {
        return false;
      }

      if (!$event_config->event) {
        return false;
      }

      $data['museum_id'] = $event_config ? $event_config->event->museum->id : false;
      $hasEvent = $user->carts()->where('event_config_id', $data['id'])->first();

      if ($hasEvent) {
        $quantity = $data['quantity'] + $hasEvent->quantity;
      } else {
        $quantity = $data['quantity'];
      }

      $total_price = $event_config->price * $quantity;

      $data['quantity'] = $quantity;
      $data['total_price'] = $total_price;
      $data['event_config_id'] = $data['id'];


      return $data;
  }


  public function makeTicketData($data, $user)
  {
      $ticket = $data['type'] == 'subscription' ? $this->getSubscriptionTicket($data['id']) : $this->getStandartTicket($data['id']);

      if (!$ticket) {
        return false;
      }

      $data['museum_id'] = $ticket ? $ticket->museum->id : false;

      $hasRow = $user->carts()->where(['museum_id' => $ticket->museum->id, 'type' => $data['type']])->first();

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

  public function makeUnitedTicketData($data, $user)
  {

      $min_museum_quantity = TicketUnitedSetting::first()->min_museum_quantity;
      if ($min_museum_quantity > count($data['museum_ids'])) {
        return false;
      }
      $museums = Museum::whereIn('id', $data['museum_ids'])->get();
      $total_price = 0;
      $coefficient = ticketType($data['type'])->coefficient;

      foreach ($museums as $key => $museum) {
        $price = $museum->standart_tickets ? $museum->standart_tickets->price : 0;
        $discont_price = $price - ($price * $coefficient);
        $total_price += $discont_price;
        $data['united'][$key] = [
          'museum_id' => $museum->id,
          'price' => $discont_price
        ];
      }


      $data['total_price'] = $total_price * $data['quantity'];

      return $data;
  }


}
