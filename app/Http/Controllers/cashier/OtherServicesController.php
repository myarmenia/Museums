<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use App\Models\GuideService;
use App\Models\OtherService;
use App\Models\Ticket;
use App\Traits\NodeApi\QrTokenTrait;
use App\Traits\Purchase\PurchaseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OtherServicesController extends CashierController
{
    //
    use PurchaseTrait;
    use QrTokenTrait;
    public function __invoke(Request $request)
    {
        try {
          // dd($request->all());

            DB::beginTransaction();
            session(['open_tab' =>'navs-top-otherService']);
            $requestDatForValidation = $request->except('other_service');
            $requestData = $request->all();
            $data['purchase_type'] = 'offline';
            $data['status'] = 1;
            $data['items'] = [];

            $museumId = getAuthMuseumId();
            if(is_null($request->other_service)){
              session([
                'errorMessage' => 'Պետք է պարտադիր նշված լինի ծառայության  դաշտը։',

              ]);

              return redirect()->back();


            }


            $data['items'][0]['type'] = "other_service";
                    $data['items'][0]['quantity']=$request->other_service_count;
                    $data['items'][0]['id']=$request->other_service;


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
