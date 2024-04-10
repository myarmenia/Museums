<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use App\Models\EducationalProgram;
use App\Traits\NodeApi\QrTokenTrait;
use App\Traits\Purchase\PurchaseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EducationalTicket extends Controller
{
    use PurchaseTrait;
    use QrTokenTrait;

    public function __invoke(Request $request)
    {
        try {
            DB::beginTransaction();
        $requestData = $request->input('educational');

        $data['purchase_type'] = 'offline';
        $data['status'] = 1;
        $data['items'] =  [];

        $museumId = getAuthMuseumId();

        $allEducational = EducationalProgram::where(['museum_id' => $museumId, 'status' => 1])->get();

        foreach ($requestData as $key => $item) {
            $educational = $allEducational->find($key);

            if($educational && $item){
                $data['items'][] = [
                    "type"=> 'educational', 
                    "id"=> $educational->id,            
                    "quantity"=> (int) $item
                ];
            }
        }

        $addTicketPurchase =  $this->purchase($data);

        if($addTicketPurchase){
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
    }}