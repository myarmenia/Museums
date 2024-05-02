<?php

namespace App\Http\Controllers\cashier;

use App\Models\GuideService;
use App\Models\Ticket;
use App\Traits\NodeApi\QrTokenTrait;
use App\Traits\Purchase\PurchaseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuyTicketController extends CashierController
{
    use PurchaseTrait;
    use QrTokenTrait;

    public function __invoke(Request $request)
    {
        try {
            DB::beginTransaction();
            $requestData = $request->all();
            $data['purchase_type'] = 'offline';
            $data['status'] = 1;
            $data['items'] = [];

            $museumId = getAuthMuseumId();

            $ticket = Ticket::where(['museum_id' => $museumId, 'status' => 1])->first();

            if (!$ticket) {
                session(['errorMessage' => 'Դուք չունեք տոմս']);

                return redirect()->route('tickets_show');
            }

            $ticketId = $ticket->id;

            $guidId = GuideService::where('museum_id', $museumId)->first()->id;

            foreach ($requestData as $key => $item) {
                if ($item) {
                    $newItem = [
                        "type" => $key,
                        "quantity" => (int) $item,
                    ];
                
                    if ($key === 'guide_other' || $key === 'guide_am') {
                        $newItem["id"] = $guidId;
                    } else {
                        $newItem["id"] = $ticketId;
                    }

                    $data['items'][] = $newItem;
                }
            }

            $addTicketPurchase = $this->purchase($data);

            if ($addTicketPurchase) {
                $addQr = $this->getTokenQr($addTicketPurchase->id);

                if ($addQr) {
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