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
  // public function model(){
  //   return Product::class;
  // }

    public function store(ProductRequest $request){
// dd($request->all());
      $product = $this->getStore($request,'products','product_translations','product_id');
      if($product){
        $data=Product::where('id','>',0);

        $data=$data->orderBy('id','desc')->paginate(6)->withQueryString();

        return redirect()->route('product-list')->with('data')
        ->with('i', ($request->input('page', 1) - 1) * 6);

      }
    }
}
