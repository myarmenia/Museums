<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Traits\ListTrait\UpdateTrait;
use Illuminate\Http\Request;

class ProductUpdateController extends Controller
{
  use UpdateTrait;
    public function update(ProductRequest $request, string $id){

      $product = $this->getUpdate($request,'products','product_translations','product_id',$id);
      if($product){
        return redirect()->back();
      }
    }
}
