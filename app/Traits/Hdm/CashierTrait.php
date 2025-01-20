<?php
namespace App\Traits\Hdm;

use App\HDM\HDM;
use App\Models\HdmConfig;


trait CashierTrait
{
  public function cLogin()
  {

      // $ip = '192.168.10.125'; // ՀԴՄ սարքի IP հասցեն
      // $port = 8080; // ՀԴՄ սարքի պորտը
      // $hdmPassword = "96yQDWay";

      // ======== araym petq chi 17.01.25 ======================
    $hasHdm = museumHasHdm();

    if (!$hasHdm) {
      return false;
    }
      $museumAccessId = museumAccessId();

      $hasHdm = HdmConfig::where('museum_id', $museumAccessId)->first();       // when museum work with dhm

      if($hasHdm && $hasHdm->status){

          $hdm = new HDM($hasHdm);  // hdm cashier login for hdm
            // dd($hdm->cashierLogin());
          return $hdm->cashierLogin();

      }
      else{
        return true;
      }

  }

}
