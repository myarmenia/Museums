<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductListController extends Controller
{

  protected $model;

	public function __construct(Product $model)
	{
    // dd(Auth::user());
    $this->middleware('role:super_admin|museum_admin|content_manager|manager|general_manager');
    // dd($this->middleware('role:super_admin|museum_admin|content_manager|manager|general_manager'));


		$this->model = $model;
	}

    public function index(Request $request){
      $product_category = ProductCategory::all();
      $museums = Museum::all();

      $data = $this->model
                  ->filter($request->all());
      // dd($data->get());

// dd(request()->user()->roles[0]);
      if(!Auth::user()->hasRole('super_admin') && !Auth::user()->hasRole('general_manager')){
        $data=$data->where('museum_id',museumAccessId());

      }
     
        $data=$data
        ->orderBy('id', 'DESC')->paginate(10)->withQueryString();
        return view('content.product.index', [
            'data' => $data,
            'product_category' => $product_category,
            'museums' => $museums
        ])
             ->with('i', ($request->input('page', 1) - 1) * 10);


    }
}
