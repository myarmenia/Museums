<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Traits\NodeApi\QrTokenTrait;
use App\Traits\Purchase\PurchaseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuyTicketController extends Controller
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

            foreach ($requestData as $key => $item) {
                if ($item) {
                    $data['items'][] = [
                        "type" => $key,
                        "id" => $ticketId,
                        "quantity" => (int) $item
                    ];
                }
            }

            $addTicketPurchase = $this->purchase($data);

            if ($addTicketPurchase) {
                $addQr = $this->getTokenQr($addTicketPurchase->id);

                if ($addQr) {
                    session(['success' => 'Տոմսերը ավելացված է']);

                    DB::commit();
                    return redirect()->back();
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