<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketQr;
use Illuminate\Http\Request;
use App\Mail\SendQRTiketsToUsersEmail;
use Mail;
class ChangeStyleController extends Controller
{
    public function change_style(Request $request, $type)
    {
        session()->put('style', $type);

        return back();

    }



    public function test_email(Request $request)
    {
        // $generate_qr = $this->getTokenQr($payment->purchase_id);
        // if ($generate_qr) {

          // $email = $payment->purchase->email;

          // $result = mail::send(new SendQRTiketsToUsersEmail($generate_qr, $email));
        // }
        $generate_qr = TicketQr::where('id',1)->first();
        // dd($generate_qr);
        $email = $request->email;
        $result = mail::send(new SendQRTiketsToUsersEmail($generate_qr, $email));

    }
}
