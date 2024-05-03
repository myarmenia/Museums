<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductEditController extends Controller
{
  public function __construct(Product $model)
	{

    $this->middleware('role:museum_admin|content_manager|manager');


	}

    public function edit($id){


      $data = Product::find($id);

      if( $data!=null && $data->museum_id==museumAccessId()){

        $category=ProductCategory::all();
        return view("content.product.edit", compact("data","category"));

      }else{
        return redirect()->route('product_list');
      }



    }
}
