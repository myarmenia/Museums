<?php
namespace App\Traits\Hdm;

use App\HDM\HDM;
use App\Models\HdmConfig;
use App\Models\Purchase;
use App\Models\PurchasedItem;


trait PrintLastReceiptTrait
{
  public function printLastReceiptHdm()
  {

    $hasHdm = museumHasHdm();

    if (!$hasHdm) {
      return false;
    }

    $hdm = new HDM($hasHdm);

    $jsonBody = json_encode([
        'seq'=> ''
      ]);

    return $hdm->socket($jsonBody, '05');


  }



}

