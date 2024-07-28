<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\MuseumStaff;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductCreateController extends Controller
{
  public function __construct()
	{
    $this->middleware('role:museum_admin|content_manager|manager');
    $this->middleware('product_viewer_list');

	}
  public function create($museumId){

    $data = ProductCategory::all();
    $museum_staff = MuseumStaff::where(['user_id'=>Auth::id(),'museum_id'=>$museumId])->first();

    return view('content.product.create',compact('data','museum_staff'));
  }
}
