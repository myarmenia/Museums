<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use App\Models\GuideService;
use App\Traits\NodeApi\QrTokenTrait;
use App\Traits\Purchase\PurchaseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartnerController extends Controller
{

  use PurchaseTrait;
  use QrTokenTrait;
  public function __invoke(Request $request)
  {

    try {


        DB::beginTransaction();
        $requestDatForValidation = $request->except('partner_id','comment');
        $requestData = $request->all();
        $data['purchase_type'] = 'offline';
        $data['status'] = 1;
        $data['items'] = [];
        $data['comment'] = (object) [
          'comment' => $request->comment,
          'type' => 'partner'
      ];

        $museumId = getAuthMuseumId();

        $filteredData = array_filter($requestDatForValidation, function ($value) {
          return !is_null($value);
        });

// dd($filteredData);
        if (empty($filteredData)) {
            session(['errorMessage' => 'Պետք է պարտադիր նշված լինի տոմսի քանակ դաշտը։']);

            return redirect()->back()->session('navs-top-partners');

        }

        $guid = GuideService::where('museum_id', $museumId)->first();
        $haveValue = false;
        foreach ($requestDatForValidation as $key => $item) {
            if ($item) {
                $haveValue = true;
                $newItem = [
                    "type" => $key,
                    "quantity" => (int) $item,
                    "id"=>$request->partner_id,
                ];

                if (($key === 'guide_other' || $key === 'guide_am') && $guid) {

                    $newItem["id"] = $guid->id;
                    if($key === 'guide_other'){
                      $newItem["sub_type"] = "partner_guide_other";

                    }else{
                       $newItem["sub_type"]="partner_guide_am";
                    }
                }


                $data['items'][] = $newItem;

            }
        }

dd($data);
        if(!$haveValue){
          session(['errorMessage' => 'Լրացրեք քանակ դաշտը']);

          DB::rollBack();
          return redirect()->back();
        }







// dd($data);
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
