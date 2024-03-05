<?php

namespace App\Http\Controllers\API\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductCantroller extends Controller
{
    public function index(){

      $arr=[];
      $product = Product::where('status',1)->orderBy('id','desc')->get()->groupBy('museum_id');

      foreach($product as$key=>$value){
        foreach($value->take(5) as $item){
           array_push($arr, $item);
           }
      }

      shuffle($arr);

      $perPage = 10;
      $currentPage = LengthAwarePaginator::resolveCurrentPage();

      $currentItems = array_slice($arr, ($currentPage - 1) * $perPage, $perPage);
      $paginatedItems = new LengthAwarePaginator($currentItems, count($arr), $perPage);

      return ProductResource::collection($paginatedItems);

    }
}
