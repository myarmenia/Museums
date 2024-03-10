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
  public function __construct()
	{
    $this->middleware('role:museum_admin|content_manager');
    $this->middleware('product_viewer_list');

	}
    public function model()
    {
      return Product::class;
    }
    public function update(ProductRequest $request, string $id){

      $product = $this->itemUpdate($request,$id);

      if($product){

        return redirect()->back();

      }
    }
}
