<?php
namespace App\Traits\Hdm;

use App\HDM\HDM;
use App\Models\HdmConfig;
use App\Models\Purchase;


trait ReturnHdm
{
  public function returnHdm()
  {

      // $ip = '192.168.10.125'; // ՀԴՄ սարքի IP հասցեն
      // $port = 8080; // ՀԴՄ սարքի պորտը
      // $hdmPassword = "96yQDWay";

      $museumAccessId = museumAccessId();

      $hasHdm = HdmConfig::where('museum_id', $museumAccessId)->first();       // when museum work with dhm

      if($hasHdm && $hasHdm->status){

          $hdm = new HDM($hasHdm);  // hdm cashier login for hdm
          $purchase = Purchase::find(17088);
          $jsonBody = json_encode([
            // 'seq' => 100002,
            'crn' => $purchase->hdm_crn,
            'returnTicketId' => $purchase->hdm_rseq

          ]);

          return $return = $hdm->socket($jsonBody, '12');

      }

  }

}
