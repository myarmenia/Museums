<?php

namespace App\Http\Controllers\Admin\cashier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cashier\CashierEventRequest;
use App\Models\Partner;
use App\Models\ProductCategory;
use App\Services\Cashier\CashierService;
use App\Traits\Purchase\PurchaseTrait;
use Illuminate\Http\Request;

class CashierController extends Controller
{
   public $cashierService;

   public function __construct(CashierService $cashierService)
   {
      $this->cashierService = $cashierService;
   }

   public function index(Request $request)
   {

      $allData = $this->cashierService->getAllData();

      if($allData['success']) {
         $data = $allData['data'];
        //  dd($data);
         return view('content.cashier.create', compact('data'));
      }

      return redirect()->route('tickets_show');
   }

   public function checkCoupon(Request $request)
   {
      $checkedData = $this->cashierService->checkCoupon($request->all());

      return response()->json($checkedData);
   }

   public function getEventDetails($id)
   {
      $event = $this->cashierService->getEventDetails($id);

      $event['partners'] = Partner::where(['museum_id'=>$event->museum_id,'status'=>1])->get();

      if ($event)
      {
        session(['eventId' => $id]);

         return response()->json($event);
      }

      return response()->json(['error' => translateMessageApi('something-went-wrong')], 500);
   }

   public function getMuseumProduct(Request $request)
   {
      $product_category = ProductCategory::all();
      $data = $this->cashierService->getMuseumProduct($request->all());

      return view('content.cashier.product', [
         'data' => $data,
         'product_category' => $product_category,
      ])
         ->with('i', ($request->input('page', 1) - 1) * 5);
   }

   public function showLastTicket()
   {
      $data = $this->cashierService->showLastTicket();

      $last_hdm = $this->cashierService->getLastPurchaseHdm();

      return view('content.cashier.show-last-ticket', compact('data', 'last_hdm'));
   }
   public function getOtherServiceDetails($id)
   {

    $otherService = $this->cashierService->getOtherServiceDetails($id);

      if ($otherService) {

         return response()->json($otherService);
      }

      return response()->json(['error' => translateMessageApi('something-went-wrong')], 500);
   }
    public function getPartnerDetails($id)
   {

      $partner = $this->cashierService->getPartnerDetails($id);


        if ($partner) {
          // dd($partner);

          return response()->json($partner);
        }

        return response()->json(['error' => translateMessageApi('something-went-wrong')], 500);
   }

    public function index_with_hdm(){
        $allData = $this->cashierService->getAllData();

        if ($allData['success']) {
          $data = $allData['data'];
          //  dd($data);

          return view('content.cashier.create-with-hdm', compact('data'));
        }

        return redirect()->route('tickets_show');
    }


  public function printLastTeceiptHdm()
  {

    $data = $this->cashierService->lastReceiptHdm();

    return redirect()->back()->with('result_hdm',$data);


  }

}
