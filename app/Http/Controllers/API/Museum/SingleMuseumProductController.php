<?php

namespace App\Http\Controllers\API\Museum;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\SingleMuseumProduct\MuseumBranchResource;
use App\Http\Resources\API\SingleMuseumProduct\ProductsOptionsResource;
use App\Http\Resources\API\SingleMuseumProduct\ProductsResource as SingleMuseumProductProductsResource;
use App\Http\Resources\Shop\ProductCategoryListResource;
use App\Models\Museum;
use App\Models\MuseumBranch;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class SingleMuseumProductController extends BaseController
{
    /**
     * Handle the incoming request.
     */ protected $model;

	public function __construct(Product $model)
	{
		$this->model = $model;
	}
  public function index(Request $request){

    $product_category = ProductCategory::all();

    $data = $this->model
                ->filter($request->all());

      $data=$data->where(['museum_id'=>$request->museum_id, 'status'=>1]);

      $data = $data

      ->orderBy('id', 'DESC')->paginate(12)->withQueryString();
// total count product via filter
      $total = $this->model
      ->filter($request->all());

$total=$total->where('museum_id',$request->museum_id);

$total = $total->get();



      $museum_branches = MuseumBranch::where(['museum_id'=>$request->museum_id,'status'=>1])->get();
      $product_category = ProductCategory::all();

      $product_option['products'] = SingleMuseumProductProductsResource::collection($data);
      $product_option['products_category'] =ProductCategoryListResource::collection($product_category);
      $product_option['museum_branches'] = MuseumBranchResource::collection($museum_branches);

      return  $this->sendResponse(new ProductsOptionsResource($product_option),'success',['page_count' => $data->lastPage(),'total_count'=>count($total)]);

  }
}
