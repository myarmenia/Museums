<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use App\Models\CorporativeSale;
use App\Models\PurchasedItem;
use App\Traits\NodeApi\QrTokenTrait;
use App\Traits\Purchase\PurchaseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CorporativeTicket extends Controller
{
    use PurchaseTrait;
    use QrTokenTrait;

    public function __invoke(Request $request)
    {
        try {
            DB::beginTransaction();

            $requestData = $request->all();

            $museumId = getAuthMuseumId();

            $corporativeSale = CorporativeSale::where(['museum_id' => $museumId, 'coupon' => $requestData['corporative-ticket']])->first();

            $purchaseItem  = PurchasedItem::where(['museum_id' => $museumId, 'item_relation_id' => $corporativeSale->id, 'type' => 'corporative'])->first();

            if($purchaseItem){
                $purchaseId = $purchaseItem->purchase_id;

                if ($purchaseId) {
                    $addQr = $this->getTokenQr($purchaseId);
    
                    if ($addQr) {
    
                        session(['success' => 'Տոմսերը ավելացված է']);
    
                        DB::commit();
                        return redirect()->back();
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
