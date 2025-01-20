<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use App\Models\TicketSubscriptionSetting;
use App\Traits\Hdm\PrintReceiptTrait;
use App\Traits\NodeApi\QrTokenTrait;
use App\Traits\Purchase\PurchaseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionTicket extends CashierController
{
    use PurchaseTrait;
    use QrTokenTrait;
    use PrintReceiptTrait;

    public function __invoke(Request $request)
    {
        try {
            DB::beginTransaction();
            session(['open_tab' =>'navs-top-aboniment']);
            $requestData = (int) $request->input('aboniment-ticket');

            if(!$requestData){
                session([
                  'errorMessage' => 'Լրացրեք քանակ դաշտը'
                ]);

                DB::rollBack();
                return redirect()->back();
            }

            $museumId = getAuthMuseumId();

            $subscription = TicketSubscriptionSetting::where(['museum_id' => $museumId, 'status' => 1])->first();

            if ($subscription) {
                $data['purchase_type'] = 'offline';
                $data['status'] = 1;
                $data['items'] = [];
                $data['hdm_transaction_type']=$request->cashe;
                if ($requestData > 10) {
                    session(['errorMessage' => 'Ամենաշատը կարող եք գնել 10 տոմս']);
                    DB::rollBack();
                    return redirect()->back();
                }

                $data['items'][] = [
                    "type" => 'subscription',
                    "id" => $subscription->id,
                    "quantity" => (int) $requestData
                ];

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
            }

            session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
            DB::rollBack();
            return redirect()->back();

        } catch (\Exception $e) {
            session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
            DB::rollBack();
            dd($e->getMessage());
            return false;
        } catch (\Error $e) {
            session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }
}
