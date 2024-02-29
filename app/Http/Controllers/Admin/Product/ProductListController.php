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
    $this->middleware('role:super_admin|museum_admin|content_manager');

		$this->model = $model;
	}

    public function index(Request $request){

      $product_category = ProductCategory::all();

      $data = $this->model
                  ->filter($request->all());

      if(request()->user()->roles[0]->name=="museum_admin" || request()->user()->roles[0]->name=='content_manager'){

        $data=$data->where('museum_id',museumAccessId());

      }
        $data=$data
        ->orderBy('id', 'DESC')->paginate(3)->withQueryString();
        return view('content.product.index', [
            'data' => $data,
            'product_category' => $product_category
        ])
             ->with('i', ($request->input('page', 1) - 1) * 3);
    }
}
