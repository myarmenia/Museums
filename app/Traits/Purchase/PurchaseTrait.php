<?php
namespace App\Traits\Purchase;

use App\Models\Event;
use App\Models\EventConfig;
use App\Models\Museum;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchasedItem;
use App\Models\PurchaseUnitedTickets;
use App\Models\Ticket;
use App\Models\TicketSubscriptionSetting;
use App\Models\TicketUnitedSetting;
use Illuminate\Support\Facades\Auth;

trait PurchaseTrait
{
  public function purchase($data)
  {

    $museum_id = null;
    $purchase_data = [];

    if (auth('api')->user() != null) {
      $user = auth('api')->user();

      $purchase_data['user_id'] = $user->id;
      $purchase_data['email'] = $user->email;
    }

    if (Auth::user() != null) {
      $user = Auth::user();

      $purchase_data['user_id'] = $user->id;
      $purchase_data['email'] = $user->email;
    }

    if (isset ($data['person']) && count($data['person']) > 0) {

      $country_id = getCountry($data['person']['country_id'])->id;
      $data['person']['country_id'] = $country_id;
      $person = $this->createPerson($data['person']);
      $purchase_data['email'] = $person->email;
      $purchase_data['person_purchase_id'] = $person->id;
    }

    $purchase_data['type'] = $data['purchase_type'];
    $purchase_data['amount'] = 0;

    $purchase = Purchase::create($purchase_data);
    $data['purchase_id'] = $purchase->id;

    $item = $this->itemStore($data);


    if(!$item){
        return false;
    }

    $museum_id = $item && $item->museum_id != null ? $item->museum_id : null;    // museum_id ete cartic lini gnum@ petq e toghne null
    $prcase_items_count = $purchase->purchased_items->count();

    if ($prcase_items_count == 0) {
        return false;
    }

    $amount = array_sum($purchase->purchased_items->pluck('total_price')->toArray());
    $update = $purchase->update(['amount' => $amount, 'museum_id' => $museum_id]);

    return $update ? $purchase : false;
  }

  public function itemStore(array $data)
  {
// dd($data);
    // if ($data['type'] == 'product') {
    //   $data = $this->makeProductData($data);
    //   dd($data);
    //   $row = $this->addItemInPurchasedItem($data);

    // }

    // if (isset ($data['products']) && count($data['products']) > 0) {

    //     foreach ($data['products'] as $p => $product) {
    //       $product['purchase_id'] = $data['purchase_id'];
    //       $data = $this->makeProductData($product);

    //       $row = $this->addItemInPurchasedItem($data);
    //     }
    // }

    // if (isset ($data['tickets']) && count($data['tickets']) > 0) {

      foreach ($data['items'] as $key => $value) {
        // $value['user_id'] = $user->id;
        // $value['email'] = $email;
        $value['purchase_id'] = $data['purchase_id'];
        if ($value['type'] == 'product') {

          $maked_data = $this->makeProductData($value);
          // unset($maked_data['id']);
          $row = $maked_data ? $this->addItemInPurchasedItem($maked_data) : false;


        }


        if ($value['type'] == 'event') {

          $maked_data = $this->makeEventData($value);
          unset($maked_data['id']);
          $row = $maked_data ? $this->addItemInPurchasedItem($maked_data) : false;

        }

        if ($value['type'] == 'standart' || $value['type'] == 'discount' || $value['type'] == 'free' || $value['type'] == 'subscription') {

          $maked_data = $this->makeTicketData($value);
          unset($maked_data['id']);

          $row = $maked_data ? $this->addItemInPurchasedItem($maked_data) : false;

        }

        if ($value['type'] == 'united') {

          $maked_data = $this->makeUnitedTicketData($value);

          $row = $maked_data ? $this->createUnitedTickets($maked_data) : false;

        }

      }
    // }



    return $row;

  }

  public function makeProductData($data)
  {
    $product = $this->getProduct($data['product_id']);
    $data['museum_id'] = $product->museum->id;

    $total_price = $product->price * $data['quantity'];

    $data['total_price'] = $total_price;

    return $data;
  }
  public function makeTicketData($data)
  {
    $ticket = $data['type'] == 'subscription' ? $this->getSubscriptionTicket($data['id']) : $this->getStandartTicket($data['id']);

    if (!$ticket) {
      return false;
    }

    $data['museum_id'] = $ticket ? $ticket->museum->id : false;


    $coefficient = ticketType($data['type'])->coefficient;
    $total_price = $ticket->price * $coefficient * $data['quantity'];

    $data['total_price'] = $total_price;

    return $data;
  }
  public function makeEventData($data)
  {

    $event_config = $this->getEventConfig($data['id']);

    if (!$event_config) {
      return false;
    }

    if (!$event_config->event) {
      return false;
    }

    $data['museum_id'] = $event_config ? $event_config->event->museum->id : false;

    $total_price = $event_config->price * $data['quantity'];

    $data['total_price'] = $total_price;
    $data['event_config_id'] = $data['id'];


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


  public function getProduct($id)
  {
    return Product::where(['id' => $id, 'status' => 1])->where('quantity', '>', 0)->first();
  }

  public function getEvent($id)
  {
    return Event::where(['id' => $id, 'status' => 1])->first();
  }

  public function getEventConfig($id)
  {
   
    return EventConfig::where(['id' => $id, 'status' => 1])->whereColumn('visitors_quantity_limitation', '>', 'visitors_quantity')->first();

  }

  public function getStandartTicket($id)
  {
    return Ticket::where(['id' => $id, 'status' => 1])->first();
  }

  public function getSubscriptionTicket($id)
  {
    return TicketSubscriptionSetting::where(['id' => $id, 'status' => 1])->first();
  }
  public function addItemInPurchasedItem($data)
  {
    return PurchasedItem::create($data);
  }

  public function createUnitedTickets($data)
  {

    $united = $data['united'];
    unset($data['museum_ids']);
    unset($data['united']);

    $purchased_item = PurchasedItem::create($data);

    foreach ($united as $key => $value) {
      $value['purchased_item_id'] = $purchased_item->id;
      PurchaseUnitedTickets::create($value);
    }

    return $purchased_item;
  }
}
