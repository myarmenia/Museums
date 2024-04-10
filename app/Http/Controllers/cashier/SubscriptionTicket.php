<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use App\Models\TicketSubscriptionSetting;
use App\Traits\NodeApi\QrTokenTrait;
use App\Traits\Purchase\PurchaseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionTicket extends Controller
{
    use PurchaseTrait;
    use QrTokenTrait;

    public function __invoke(Request $request)
    {
        try {
            DB::beginTransaction();
            $requestData = (int) $request->input('aboniment-ticket');

            $museumId = getAuthMuseumId();

            $subscription = TicketSubscriptionSetting::where(['museum_id' => $museumId, 'status' => 1])->first();

            if ($subscription) {
                $data['purchase_type'] = 'offline';
                $data['status'] = 1;
                $data['items'] = [];

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
