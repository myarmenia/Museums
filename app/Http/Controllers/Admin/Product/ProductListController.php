<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use app\Traits\ListTrait\GetListTrait as ListTraitGetListTrait;
use App\Traits\ListTrait\GetListTrait;
use Illuminate\Http\Request;

class ProductListController extends Controller
{
  use GetListTrait;
  // protected $listService;

    public function index(Request $request){

      $model="Product";
      $addressRequest="web";
      $data = $this->getList($request->all(), $model, $addressRequest);
      $data=$data->orderBy('id', 'DESC')->paginate(3)->withQueryString();
        return view("content.product.index", compact('data'))
        ->with('i', ($request->input('page', 1) - 1) * 3);
    }
}
