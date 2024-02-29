<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Traits\UpdateTrait;
use Illuminate\Http\Request;

class ProductUpdateController extends Controller
{
  use UpdateTrait;
    public function model()
    {
      return Product::class;
    }
    public function update(ProductRequest $request, string $id){

      $product = $this->itemUpdate($request,'products','product_id',$id);

      if($product){

        return redirect()->back();
        
      }
    }
}
