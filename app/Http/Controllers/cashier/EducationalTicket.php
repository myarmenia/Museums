<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use App\Models\EducationalProgram;
use App\Traits\Hdm\PrintReceiptTrait;
use App\Traits\NodeApi\QrTokenTrait;
use App\Traits\Purchase\PurchaseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EducationalTicket extends CashierController
{
    use PurchaseTrait;
    use QrTokenTrait;
    use PrintReceiptTrait;

    public function __invoke(Request $request)
    {
        try {

            DB::beginTransaction();
            session(['open_tab' =>'navs-top-educational']);

            $requestData = $request->input('educational');

            $data['purchase_type'] = 'offline';
            $data['status'] = 1;
            $data['items'] = [];
            $data['hdm_transaction_type']=$request->cashe;

            $museumId = getAuthMuseumId();

            $allEducational = EducationalProgram::where(['museum_id' => $museumId, 'status' => 1])->get();

            $haveValue = false;
            foreach ($requestData as $key => $item) {
                $educational = $allEducational->find($key);

                if ($educational && $item) {
                    $haveValue = true;
                    $data['items'][] = [
                        "type" => 'educational',
                        "id" => $educational->id,
                        "quantity" => (int) $item
                    ];
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
