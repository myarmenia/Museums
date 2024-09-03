<?php

namespace App\Traits\Cart;

use App\Models\Cart;
use App\Models\CartUnitedTickets;
use App\Models\Event;
use App\Models\EventConfig;
use App\Models\Museum;
use App\Models\PersonPurchase;
use App\Models\Product;
use App\Models\Ticket;
use App\Models\TicketSubscriptionSetting;
use App\Models\TicketUnitedSetting;
use Illuminate\Http\Request;


trait CartStoreTrait
{

  public function getAuthUser(){
    return $user = auth('api')->user();
  }
  public function itemStore(array $data)
  {
    $user = $this->getAuthUser();
    $email = $user->email;
    $row = [];

    foreach ($data['items'] as $key => $value) {

        $value['user_id'] = $user->id;
        $value['email'] = $email;
        if($value['quantity'] < 1 ){
            continue;
        }
        else{

            if ($value['type'] == 'product') {

              $maked_data = $this->makeProductData($value);
              unset($maked_data['product_id']);

              if ($maked_data) {
                  $row = $this->updateOrCreateProduct($maked_data);
              } else {
                  $row = ['error' => 'product_not_available'];
                  break;
              }
            }

            if ($value['type'] == 'event-config') {

                $summedQuantities = collect($data['items'])
                  ->groupBy(function ($q_item) {
                    return $q_item['type'] . '-' . ($q_item['sub_type'] ?? '') . '-' . $q_item['id'];
                  }) // Группировка
                  ->map(function ($group) {
                    return $group->sum('quantity');
                  }) // Суммирование
                    ->sum();



                $maked_data = $this->makeEventConfigData($value, $summedQuantities);
                unset($maked_data['id']);

                if ($maked_data) {
                    $row = $this->updateOrCreateEvent($maked_data);
                } else {
                    $row = ['error' => 'ticket_not_available'];
                    break;
                }
            }

            if ($value['type'] == 'event') {

              $maked_data = $this->makeEventData($value);
              unset($maked_data['id']);

              if ($maked_data) {
                $row = $this->updateOrCreateEvent($maked_data);
              } else {
                $row = ['error' => 'ticket_not_available'];
                break;
              }
            }

            if ($value['type'] == 'standart' || $value['type'] == 'discount' || $value['type'] == 'free' || $value['type'] == 'subscription') {
                $maked_data = $this->makeTicketData($value);
                unset($maked_data['id']);

              if ($maked_data) {
                  $row = $this->updateOrCreateStandart($maked_data);

              } else {
                  $row = ['error' => 'ticket_not_available'];
                  break;
              }
            }

            if ($value['type'] == 'united') {
                $maked_data = $this->makeUnitedTicketData($value);

                unset($maked_data['museum_ids']);
                if ($maked_data) {
                    $row = $this->updateOrCreateUnitedTickets($maked_data);
                } else {
                    $row = ['error' => 'ticket_not_available'];
                    break;
                }
            }
      }
    }

    return $row;
  }

  public function getProduct($id, $quantity)
  {
    return Product::where(['id' => $id, 'status' => 1])->where('quantity', '>=', $quantity)->first();
  }

  public function getEvent($id)
  {
    return Event::where(['id' => $id, 'status' => 1])->first();
  }

  public function getEventConfig($id)
  {
    return EventConfig::where(['id' => $id, 'status' => 1])->whereColumn('visitors_quantity_limitation', '>', 'visitors_quantity')->first();
  }

  public function updateOrCreateProduct($data)
  {
    return Cart::updateOrCreate(['user_id' => $data['user_id'], 'item_relation_id' => $data['item_relation_id'], 'type' => $data['type']], $data);
  }

  public function updateOrCreateEvent($data)
  {

    return Cart::updateOrCreate(['user_id' => $data['user_id'], 'item_relation_id' => $data['item_relation_id'], 'type' => $data['type'], 'sub_type' => $data['sub_type']], $data);
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

  public function updateOrCreateStandart($data)
  {
    return Cart::updateOrCreate(['user_id' => $data['user_id'], 'item_relation_id' => $data['item_relation_id'], 'type' => $data['type']], $data);
  }

  public function getStandartTicket($id)
  {
    return Ticket::where(['id' => $id, 'status' => 1])->first();
  }

  public function getSubscriptionTicket($id)
  {
    return TicketSubscriptionSetting::where(['id' => $id, 'status' => 1])->first();
  }


  public function makeProductData($data)
  {
    $product = $this->getProduct($data['product_id'], $data['quantity']);

    if (!$product) {
      return false;
    }

    $data['museum_id'] = $product->museum->id;

    $hasProduct = $this->getAuthUser()->carts()->where(['item_relation_id' =>  $data['product_id'], 'type' => $data['type']])->first();

    if ($hasProduct) {
      $quantity = $data['quantity'] + $hasProduct->quantity;
    } else {
      $quantity = $data['quantity'];
    }

    $total_price = $product->price * $quantity;

    $data['quantity'] = $quantity;
    $data['total_price'] = $total_price;
    $data['item_relation_id'] = $data['product_id'];

    return $data;
  }

  public function makeEventConfigData($data, $summedQuantities)
  {

    $event_config = $this->getEventConfig($data['id']);
    $event = $event_config->event;

    if (!$event_config) {
      return false;
    }

    if (!$event_config->event) {
      return false;
    }

    $remainder = $event_config->visitors_quantity_limitation - $event_config->visitors_quantity;

    if ($summedQuantities > $remainder) {
      return false;
    }

    $data['museum_id'] = $event_config ? $event_config->event->museum->id : false;
    $hasEvent = $this->getAuthUser()->carts()->where(['item_relation_id' =>  $data['id'], 'type' => $data['type'], 'sub_type' => $data['sub_type']])->first();

    if ($hasEvent) {
      $quantity = $data['quantity'] + $hasEvent->quantity;
    } else {
      $quantity = $data['quantity'];
    }

    // $total_price = $event_config->price * $quantity;
      $total_price = isset($data['sub_type']) ?
                        ($data['sub_type'] == 'discount' ? $event->discount_price * $quantity :
                          $event->price * $quantity) :
                            $event->price * $quantity;

    $data['quantity'] = $quantity;
    $data['total_price'] = $total_price;
    $data['item_relation_id'] = $data['id'];


    return $data;
  }

  public function makeEventData($data)
  {

    $event = $this->getEvent($data['id']);

    if (!$event) {
      return false;
    }

    $data['museum_id'] = $event->museum->id;
    $hasEvent = $this->getAuthUser()->carts()->where(['item_relation_id' => $data['id'], 'type' => $data['type'], 'sub_type' => $data['sub_type']])->first();

    if ($hasEvent) {
      $quantity = $data['quantity'] + $hasEvent->quantity;
    } else {
      $quantity = $data['quantity'];
    }

    // $total_price = $event->price * $quantity;
    $total_price = isset($data['sub_type']) ?
                      ($data['sub_type'] == 'discount' ? $event->discount_price * $quantity :
                        $event->price * $quantity) :
                          $event->price * $quantity;

    $data['quantity'] = $quantity;
    $data['total_price'] = $total_price;
    $data['item_relation_id'] = $data['id'];


    return $data;
  }


  public function makeTicketData($data)
  {
    $ticket = $data['type'] == 'subscription' ? $this->getSubscriptionTicket($data['id']) : $this->getStandartTicket($data['id']);

    if (!$ticket) {
      return false;
    }

    $data['museum_id'] = $ticket ? $ticket->museum->id : false;

    $hasRow = $this->getAuthUser()->carts()->where(['item_relation_id' => $data['id'], 'type' => $data['type']])->first();

    if ($hasRow) {

      $quantity = $data['quantity'] + $hasRow->quantity;
    } else {

      $quantity = $data['quantity'];
    }

    $coefficient = ticketType($data['type'])->coefficient;
    $total_price = $ticket->price * $coefficient * $quantity;

    $data['quantity'] = $quantity;
    $data['total_price'] = $total_price;
    $data['item_relation_id'] = $data['id'];

    return $data;
  }

  public function makeUnitedTicketData($data)
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
      $discont_price = round($price - ($price * $coefficient));
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
