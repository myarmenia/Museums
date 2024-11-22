<?php

namespace App\Http\Controllers\API\HDM;

use App\HDM\HDM;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class CashierLoginController extends Controller
{
  public function __invoke()
  {

    $ip = '192.168.10.125'; // ՀԴՄ սարքի IP հասցեն
    $port = 8080; // ՀԴՄ սարքի պորտը
    $hdmPassword = "96yQDWay";

    $hdm = new HDM($ip, $port, $hdmPassword);



    $login = $hdm->cashierLogin();

// dd($login);
  }
}
