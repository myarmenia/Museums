<?php
namespace App\Traits\Purchase;

use App\Models\EventConfig;
use App\Models\PersonPurchase;
use App\Models\Product;
use App\Models\Purchase;

trait UpdateItemQuantityTrait
{
  public function updateItemQuantity($purchase_id)
  {

    $array_types = ['product', 'event-config'];

    $purchase = Purchase::find($purchase_id);
    $items = $purchase->purchased_items->whereIn('type', $array_types)->toArray();

    if (count($items) > 0) {
      foreach ($items as $i => $item) {
        switch ($item['type']) {
          case 'product':
            $this->updateProductQuantity($item);
            break;
          case 'event-config':
            $this->updateEventConfigeQuantity($item);
            break;
        }
      }
    }

  }

  public function updateProductQuantity($item)
  {

    $product = Product::find($item['item_relation_id']);

    $quantity = $product->quantity - $item['quantity'];
    $product->update(['quantity' => $quantity]);
  }


  public function updateEventConfigeQuantity($item)
  {

    $event_config = EventConfig::find($item['item_relation_id']);
    $quantity = $event_config->visitors_quantity + $item['quantity'];
    $event_config->update(['visitors_quantity' => $quantity]);
  }



}
