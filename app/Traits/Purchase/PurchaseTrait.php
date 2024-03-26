<?php
namespace App\Traits\Purchase;
use App\Models\Purchase;
use App\Models\PurchasedItem;
use Illuminate\Support\Facades\Auth;

trait PurchaseTrait
{
  public function purchase($data)
  {

      $museum_id = null;
      if(auth('api')->user() != null){
          $user = auth('api')->user();

          $data['user_id'] = $user->id;
          $data['email'] = $user->email;
      }

      if(Auth::user() != null){
          $user = Auth::user();

          $data['user_id'] = $user->id;
          $data['email'] = $user->email;
      }

      if (isset ($data['person']) && count($data['person']) > 0) {
          $person = $this->createPerson($data['person']);
          $data['email'] = $user->email;
          $data['person_id'] = $person->id;
      }

      $purchase = Purchase::create($data);
      $data['purchase_id'] = $purchase->id;

      $item = $this->itemStore($data);
      $museum_id = $item->museum_id ? $item->museum_id : null;

      $amount = array_sum($purchase->purchased_items->pluck('total_price'));
      $purchase->update(['amount' => $amount, 'museum_id' => $museum_id]);
      return Purchase::create($data);
  }

  public function itemStore(array $data)
  {



    if ($data['type'] == 'product') {
        $data = $this->makeProductData($data);
        $row = $this->createProduct($data);

    }

    // if (isset ($data['tickets']) && count($data['tickets']) > 0) {

    //     foreach ($data['tickets'] as $key => $value) {
    //         $value['user_id'] = $user->id;
    //         $value['email'] = $email;

    //         if ($value['type'] == 'event') {

    //             $maked_data = $this->makeEventData($value, $user);
    //             unset($maked_data['id']);
    //             $row = $maked_data ? $this->updateOrCreateEvent($maked_data) : false;
    //         }

    //         if ($value['type'] == 'standart' || $value['type'] == 'discount' || $value['type'] == 'free' || $value['type'] == 'subscription') {
    //             $maked_data = $this->makeTicketData($value, $user);
    //             unset($maked_data['id']);
    //             $row = $maked_data ? $this->updateOrCreateStandart($maked_data) : false;
    //         }

    //         if ($value['type'] == 'united') {
    //             $maked_data = $this->makeUnitedTicketData($value, $user);

    //             unset($maked_data['museum_ids']);
    //             $row = $maked_data ? $this->updateOrCreateUnitedTickets($maked_data) : false;

    //         }


    //     }
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

  public function createProduct($data)
  {
    return PurchasedItem::create($data);
  }

}
