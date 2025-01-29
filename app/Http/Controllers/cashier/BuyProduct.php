<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\TicketSubscriptionSetting;
use App\Traits\Hdm\PrintReceiptTrait;
use App\Traits\NodeApi\QrTokenTrait;
use App\Traits\Purchase\PurchaseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuyProduct extends CashierController
{
    use PurchaseTrait;
    use PrintReceiptTrait;
    use QrTokenTrait;

    public function __invoke(Request $request)
    {
        try {
            DB::beginTransaction();

            $requestData = $request->input('product');
            // dd($request->all());

            $allMuseumProduct = Product::where(['museum_id' => getAuthMuseumId(), 'status' => 1])->get();

            if ($allMuseumProduct->count()) {
                $data['purchase_type'] = 'offline';
                $data['status'] = 1;
                $data['items'] = [];
                $data['hdm_transaction_type']=$request->cashe;

                $haveValue = false;

                foreach ($requestData as $key => $countProduct) {
                    if ($countProduct = (int) $countProduct) {
                        $haveValue = true;
                        if($p = $allMuseumProduct->find((int) $key)){
                            if ($p->quantity  < $countProduct) {
                                session(['errorMessage' => 'Պետք է համապատասխանեն ապրանքի քանակ և մուտքագրված թիվ դաշտերը']);
                                DB::rollBack();
                                return redirect()->back();
                            }

                            $p->update(['quantity' => $p->quantity - $countProduct]);

                            $data['items'][] = [
                                "type" => 'product',
                                "product_id" => (int) $key,
                                "quantity" => (int) $countProduct
                            ];
                        }
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

                    // if($addQr){
                    //   if (museumHasHdm()) {

                    //     $print = $this->PrintHdm($addTicketPurchase->id);

                    //     if (!$print['success']) {

                    //         $message = isset($print['result']['message']) ? $print['result']['message'] : 'ՀԴՄ սարքի խնդիր';

                    //         session(['errorMessage' => $message]);
                    //         return redirect()->back();
                    //     }

                    //   }

                    // }

                  $pdfPath = $this->showReadyPdf($addTicketPurchase->id);
                  // dd(  $pdfPath);

                    session(['success' => 'Ապրանքը վաճառված է']);

                    DB::commit();
                    // dd($pdfPath);
                    // return redirect()->back();
                    return redirect()->back()->with('pdfFile', $pdfPath);

                    // return redirect()->route('cashier.product')->with('pdfFile', $pdfPath);
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
