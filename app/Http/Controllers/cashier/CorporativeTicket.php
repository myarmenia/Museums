<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use App\Mail\SendCorporativeEmail;
use App\Models\CorporativeSale;
use App\Models\CorporativeVisitorCount;
use App\Models\PurchasedItem;
use App\Traits\NodeApi\QrTokenCorporativeTrait;
use App\Traits\Purchase\PurchaseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mail;
use Carbon\Carbon;

class CorporativeTicket extends CashierController
{
    use PurchaseTrait;
    use QrTokenCorporativeTrait;

    public function __invoke(Request $request)
    {
        try {
            DB::beginTransaction();

            $requestData = $request->all();

            $museumId = getAuthMuseumId();

            $corporativeSale = CorporativeSale::where(['museum_id' => $museumId, 'coupon' => $requestData['corporative-ticket']])->first();

            if ($corporativeSale->tickets_count < ($corporativeSale->visitors_count + (int) $requestData['buy-ticket'])) {
                session(['errorMessage' => 'Տոմսերի քանակը չպետք է գերազանցի կորպորատիվի տոմսի քանակից']);
                DB::rollBack();
                return redirect()->back();
            }

            $purchaseItem = PurchasedItem::where(['museum_id' => $museumId, 'item_relation_id' => $corporativeSale->id, 'type' => 'corporative'])->first();

            if ($purchaseItem) {
                $purchaseId = $purchaseItem->purchase_id;
                
                $ticketCount = (int) $requestData['buy-ticket'];
                
                if(!$ticketCount){
                    session(['errorMessage' => 'Լրացրեք քանակ դաշտը']);
                        
                    DB::rollBack();
                    return redirect()->back();
                }

                if ($purchaseId) {

                    $addQr = $this->getTokenQr($purchaseId, $corporativeSale, $ticketCount);
                    if ($addQr) {
                        $pdfPath = $this->showReadyPdf($purchaseId, $addQr);

                        $corporativeSale->update(['visitors_count' => $corporativeSale->visitors_count + $ticketCount]);
                        CorporativeVisitorCount::create(['corporative_id' => $corporativeSale->id, 'count' => $ticketCount]);

                        $ownerEmail = $corporativeSale->email;
                        $museumName = $corporativeSale->museum->translationsAdmin->first()->name;

                        $mailData = [
                            'museum_name' =>$museumName,
                            'date' => Carbon::now()->format('Y-m-d H:i:s'),
                            'ticketCount' => $ticketCount,
                            'remainder' => $corporativeSale->tickets_count - $corporativeSale->visitors_count
                        ];

                        Mail::send(new SendCorporativeEmail($mailData, $ownerEmail));
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
