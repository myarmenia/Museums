<?php

namespace App\Http\Controllers\cashier;

use App\Models\GuideService;
use App\Models\Ticket;
use App\Models\TicketSchoolSetting;
use App\Traits\Hdm\PrintReceiptTrait;
use App\Traits\NodeApi\QrTokenTrait;
use App\Traits\Purchase\PurchaseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuyTicketController extends CashierController
{
    use PurchaseTrait;
    use QrTokenTrait;
    use PrintReceiptTrait;

    public function __invoke(Request $request)
    {
        try {

            DB::beginTransaction();
            session(['open_tab' =>'navs-top-home']);

            $requestData = $request->all();
            unset($requestData['cashe']);
            $data['purchase_type'] = 'offline';
            $data['status'] = 1;
            $data['items'] = [];
            $data['hdm_transaction_type']=$request->cashe;


            $museumId = getAuthMuseumId();

            $ticket = Ticket::where(['museum_id' => $museumId, 'status' => 1])->first();
            // dd($request->all(), $museumId, $ticket);
            $school_ticket = TicketSchoolSetting::first(); //updated from admin and in table only one row  (dont created)

            $filteredData = array_filter($requestData, function ($value) {
              return !is_null($value);
            });



            if (empty($filteredData)) {
                session([
                  'errorMessage' => 'Պետք է պարտադիր նշված լինի տոմսի քանակ դաշտը։'

                ]);

                return redirect()->back();
            }


            if (!$ticket) {
                session(['errorMessage' => 'Դուք չունեք տոմս']);

                return redirect()->route('tickets_show');
            }


            if (!$school_ticket && $request->school != null) {
              session(['errorMessage' => 'Իրավասու մամնի կողմից փոխհատուցման արժեքը դեռևս նշված չէ։']);

              return redirect()->back();
            }

            $ticketId = $ticket->id;


            $guid = GuideService::where('museum_id', $museumId)->first();

            $haveValue = false;
            foreach ($requestData as $key => $item) {

                if ($item) {
                    $haveValue = true;
                    $newItem = [
                        "type" => $key,
                        "quantity" => (int) $item,
                    ];

                    if (($key === 'guide_other' || $key === 'guide_am') && $guid) {

                        $newItem["id"] = $guid->id;
                    }elseif($key === 'school'){

                        $newItem["id"] = $school_ticket->id;
                    }
                     else {
                        $newItem["id"] = $ticketId;
                    }

                    $data['items'][] = $newItem;

                }
            }



            if(!$haveValue){
                session(['errorMessage' => 'Լրացրեք քանակ դաշտը']);

                DB::rollBack();
                return redirect()->back();
            }

            $addTicketPurchase = $this->purchase($data);



                if ($addTicketPurchase) {


                    $addQr = $this->getTokenQr($addTicketPurchase->id);

                    if ($addQr) {
                      if (museumHasHdm()) {

                        $print = $this->PrintHdm($addTicketPurchase->id);

                        if (!$print['success']) {

                            $message = isset($print['result']['message']) ? $print['result']['message'] : 'ՀԴՄ սարքի խնդիր';

                            session(['errorMessage' => $message]);
                            return redirect()->back();
                        }

                      }


                        $pdfPath = $this->showReadyPdf($addTicketPurchase->id);

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
