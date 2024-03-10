<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Traits\StoreTrait;
use Illuminate\Http\Request;

class ProductStoreController extends Controller
{
  use StoreTrait;

  public function __construct()
	{
    $this->middleware('role:museum_admin|content_manager');
    $this->middleware('product_viewer_list');

	}
    public function model()
    {
      return Product::class;
    }
    public function store(ProductRequest $request){

      $product = $this->itemStore($request);

      if($product){

        return redirect()->route('product_list');
      }
    }
}
