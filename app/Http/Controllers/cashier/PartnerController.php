<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use App\Models\GuideService;
use App\Traits\NodeApi\QrTokenTrait;
use App\Traits\Purchase\PurchaseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartnerController extends CashierController
{

  use PurchaseTrait;
  use QrTokenTrait;
  public function __invoke(Request $request)
  {

    try {

        DB::beginTransaction();
        $requestDatForValidation = $request->except('partner_id','comment');
        session(['open_tab' =>'navs-top-partners']);
        $data['purchase_type'] = 'offline';
        $data['status'] = 1;
        $data['items'] = [];
        if(!is_null($request->comment)){
            $data['comment']['comment'] = $request->comment;
            $data['comment']['type'] = 'partner';
        }



        $museumId = getAuthMuseumId();

        $filteredData = array_filter($requestDatForValidation, function ($value) {
          return !is_null($value);
        });


        if (empty($filteredData)) {
            session([
              'errorMessage' => 'Պետք է պարտադիր նշված լինի տոմսի քանակ դաշտերը։',

            ]);

            return redirect()->back();


        }

        $guid = GuideService::where('museum_id', $museumId)->first();
        $haveValue = false;
        foreach ($requestDatForValidation as $key => $item) {
            if ($item) {
                $haveValue = true;
                $newItem = [
                    "type" => "partner",
                    "quantity" => (int) $item,
                    "id"=>$request->partner_id,
                    "sub_type"=>$key
                ];

                if (($key === 'guide_other' || $key === 'guide_am') && $guid) {

                    $newItem["id"] = $guid->id;
                    $newItem["partner_id"] = $request->partner_id;
                    if($key === 'guide_other'){
                      $newItem["sub_type"] = "partner_guide_other";
                      $newItem["type"]="guide_other";

                    }else{
                       $newItem["sub_type"]="partner_guide_am";
                       $newItem["type"]="guide_am";
                    }
                }


                $data['items'][] = $newItem;

            }
        }

        if(!$haveValue){
          session(['errorMessage' => 'Լրացրեք քանակ դաշտը']);

          DB::rollBack();
          return redirect()->back();
        }

        $addPurchase = $this->purchase($data);

            if ( $addPurchase) {

                $addQr = $this->getTokenQr( $addPurchase->id);

                if ($addQr) {
                    $pdfPath = $this->showReadyPdf( $addPurchase->id);

                    session(['success' => 'Տոմսերը ավելացված է']);

                    DB::commit();
                    return redirect()->back()->with('pdfFile', $pdfPath);
                }
            }



        DB::rollBack();

        return redirect()->back();


    } catch (\Exception $e) {
        session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
        DB::rollBack();
        dd($e->getMessage());
        return redirect()->back();
    } catch (\Error $e) {
        session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
        DB::rollBack();
        dd($e->getMessage());
        return redirect()->back();
    }
  }
}
