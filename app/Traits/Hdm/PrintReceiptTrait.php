<?php
namespace App\Traits\Hdm;

use App\HDM\HDM;
use App\Models\Purchase;


trait PrintReceiptTrait
{
  public function PrintHdm($purchase_id, $purchase_type)
  {
        $ip = '192.168.10.125'; // ՀԴՄ սարքի IP հասցեն
        $port = 8080; // ՀԴՄ սարքի պորտը
        $hdmPassword = "96yQDWay";

        $hdm = new HDM($ip, $port, $hdmPassword);

        $purchase = Purchase::find($purchase_id);
        $amount = $purchase->amount;

        $jsonBody = json_encode([
          // 'seq' => 100002,
          'paidAmount' => 10,
          'paidAmountCard' => 0,
          'partialAmount' => 0,
          'prePaymentAmount' => 0,
          'useExtPOS' => true,
          'mode' => 2,
          // 'partnerTin' =>  null,
          'items' => [
            [
              'dep' => 1,
              'qty' => 1,
              'price' => $amount,
              'productCode' => '0015',
              'productName' => 'ticket',
              'adgCode' => '91.02',
              'unit' => 'hat',
              'additionalDiscount' => 0,
              'additionalDiscountType' => 0,
              'discount' => 0,
              'discountType' => 0
            ]
          ]
        ]);


        $print = $hdm->socket($jsonBody, '04');

        dd($print);
  }



}
