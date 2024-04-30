<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Mail\SendQRTiketsToUsersEmail;
use App\Models\TicketQr;
use App\Traits\NodeApi\QrTokenTrait;
use App\Traits\Users\ListActiveQR;
use App\Traits\Users\SendQRToMail;
use Illuminate\Http\Request;
use Mail;

class SendQRToMailController extends Controller
{
    use SendQRToMail, ListActiveQR, QrTokenTrait;
    public function __invoke(Request $request)
    {
      // $list = $this->sendQR($request->id);
      // return true;
    // $generate_qr = $this->getTokenQr(1);
    $generate_qr = TicketQr::whereIn('id', [1, 2])->get();
    // dd($generate_qr);
    $email = 'naromisho87@gmail.com';
    $result = mail::send(new SendQRTiketsToUsersEmail($generate_qr, $email));
    }
}
