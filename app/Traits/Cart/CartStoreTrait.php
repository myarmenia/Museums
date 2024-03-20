<?php
namespace App\Traits\Cart;

use App\Models\Cart;
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



      if($data['type'] == 'product'){
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

      }

      $row = $this->store($data);
      return $row;
// dd($row);
      // return Ticket::where("museum_id", museumAccessId())->first();
  }

  public function getProduct($id){
      return Product::find($id);
  }

  public function store($data)
  {
    return Cart::updateOrCreate(['user_id' => $data['user_id'], 'product_id' => $data['product_id']], $data);
  }

  public function getCartItems()
  {
   
    $user = auth('api')->user();
    return $user->carts;
  }


}
