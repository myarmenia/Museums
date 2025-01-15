<?php
namespace App\Traits\Hdm;

use App\HDM\HDM;


trait CashierTrait
{
  public function login()
  {
      $ip = '192.168.1.22'; // ՀԴՄ սարքի IP հասցեն
      $port = 1025; // ՀԴՄ սարքի պորտը
      $hdmPassword = "96yQDWay";

      $hdm = new HDM($ip, $port, $hdmPassword);


      return $hdm->cashierLogin();
  }



}
