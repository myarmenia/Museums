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
        $total_price = $product->price * $data['quantity'];
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
    return Cart::create($data);
  }


}
