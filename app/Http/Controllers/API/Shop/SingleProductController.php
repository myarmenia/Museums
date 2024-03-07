<?php

namespace App\Http\Controllers\API\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shop\SingleProductResource as ShopSingleProductResource;
use App\Http\Resources\SingleProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class SingleProductController extends Controller
{
  protected $model;

	public function __construct(Product $model)
	{


		$this->model = $model;
	}
    public function __invoke($id){
      $item=$this->model->find($id);

      return new ShopSingleProductResource($item);


    }
}
