<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketQr;
use App\Traits\NodeApi\QrTokenTrait;
use Illuminate\Http\Request;
use App\Mail\SendQRTiketsToUsersEmail;
use Mail;
class ChangeStyleController extends Controller
{
    use QrTokenTrait;
    public function change_style(Request $request, $type)
    {
        session()->put('style', $type);

        return back();

    }



    public function test_email(Request $request)
    {

        $generate_qr = $this->getTokenQr(1);
        $generate_qr = TicketQr::whereIn('id',[1,2])->get();
        // dd($generate_qr);
        $email = $request->email;
        $result = mail::send(new SendQRTiketsToUsersEmail($generate_qr, $email));

    }
}
