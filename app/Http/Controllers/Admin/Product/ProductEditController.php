<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductEditController extends Controller
{
    public function edit($id){

      $data = Product::find($id);
      $category=ProductCategory::all();
        return view("content.product.edit", compact("data","category"));
    }
}
