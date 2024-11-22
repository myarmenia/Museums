<?php

namespace App\Http\Controllers\API\HDM;

use App\Http\Controllers\Controller;
use App\HDM\HDM;
use Illuminate\Http\Request;
use Session;

class GetCashiersController extends Controller
{
    public function __invoke()
    {

      // $ip = '192.168.10.125'; // ՀԴՄ սարքի IP հասցեն
      $ip = '46.130.63.249'; // ՀԴՄ սարքի IP հասցեն

      $port = 8080; // ՀԴՄ սարքի պորտը
      $hdmPassword = "96yQDWay";

      $hdm = new HDM($ip, $port, $hdmPassword);

      $jsonBody = json_encode([
        'password' => $hdmPassword
      ]);

      $cashiers = $hdm->socket($jsonBody, '01');

      dd($cashiers);
    }
}
