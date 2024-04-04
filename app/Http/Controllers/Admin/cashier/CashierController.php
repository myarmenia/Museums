<?php

namespace App\Http\Controllers\Admin\cashier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cashier\CashierEventRequest;
use App\Models\ProductCategory;
use App\Services\Cashier\CashierService;
use App\Traits\Purchase\PurchaseTrait;
use Illuminate\Http\Request;

class CashierController extends Controller
{
   use PurchaseTrait;
   public $cashierService;

   public function __construct(CashierService $cashierService)
   {
      $this->cashierService = $cashierService;
   }

   public function index(Request $request)
   {
      $data = $this->cashierService->getAllData();
      return view('content.cashier.create', compact('data'));
   }

   public function createTicket(Request $request)
   {
      $data['purchase_type'] = 'offline';
      $data['status'] = 1;
      $data['items'] =  [
         [
             "type"=>"standart", 
             "id"=> 1,            
             "quantity"=> 4
         ],
         [
             "type"=>"discount", 
             "id"=> 1,            
             "quantity"=> 3
         ],
         [
            "type"=>"free", 
            "id"=> 1,            
            "quantity"=> 3
         ],

      ];

      $k =  $this->purchase($data);

      dd($k);
   }

   public function checkCoupon(Request $request)
   {
      $checkedData = $this->cashierService->checkCoupon($request->all());

      return response()->json($checkedData);
   }

   public function corporativeTicket(Request $request)
   {
      $buyTicket = $this->cashierService->corporativeTicket($request->all());

      dd($request->all());
   }

   public function getEventDetails($id)
   {
      $event = $this->cashierService->getEventDetails($id);

      if ($event) {
         return response()->json($event);
      }

      return response()->json(['error' => translateMessageApi('something-went-wrong')], 500);
   }

   public function createEducational(CashierEventRequest $request)
   {
      dd($request->all());
   }

   public function saleProduct(Request $request)
   {
      dd($request->all());
   }

   public function getProduct(Request $request)
   {
      $product_category = ProductCategory::all();
      $data = $this->cashierService->getProduct($request->all());

      return view('content.cashier.product', [
         'data' => $data,
         'product_category' => $product_category,
      ])
         ->with('i', ($request->input('page', 1) - 1) * 5);
   }
}
