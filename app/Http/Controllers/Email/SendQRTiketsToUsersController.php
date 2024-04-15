<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Mail\SendQRTiketsToUsersEmail;
use App\Models\TicketQr;
use Illuminate\Http\Request;
use Mail;

class SendQRTiketsToUsersController extends Controller
{
  public function __invoke()
  {
      $data = TicketQr::find(1);
      $email = 'naromisho87@gmail.com';

      $result = mail::send(new SendQRTiketsToUsersEmail($data, $email));

      return $result;

  }
}
