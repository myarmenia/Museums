<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductListController extends Controller
{

  protected $model;

	public function __construct(Product $model)
	{
		$this->model = $model;
	}

    public function index(Request $request){
      // dd($request->route()->middleware());
      $addressRequest = "web";
      $product_category = ProductCategory::all();
      // $filterArray = ['product_category_id'=>$request['product_category_id'] ?? null,'name'=>$request['name'] ?? null ];
      $data = $this->model
                  ->filter($request->all());
                  // ->get();
        // dd($data);
        $data=$data->orderBy('id', 'DESC')->paginate(3)->withQueryString();
        return view('content.product.index', [
            'data' => $data,
            'product_category'=>$product_category
        ])
             ->with('i', ($request->input('page', 1) - 1) * 3);
    }
}
