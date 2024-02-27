<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Traits\ListTrait\StoreTrait;
use Illuminate\Http\Request;

class ProductStoreController extends Controller
{
  use StoreTrait;

    public function store(ProductRequest $request){

      $product = $this->getStore($request,'products','product_translations','product_id');
      if($product){
        return redirect()->route('product-list');
      }
    }
}
