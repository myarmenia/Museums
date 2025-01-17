<?php
namespace App\Traits\Hdm;

use App\HDM\HDM;
use App\Models\HdmConfig;
use App\Models\Purchase;
use Auth;


trait PrintReceiptTrait
{

  use CashierTrait;
  public function PrintHdm($purchase_id)
  {

        // $ip = '192.168.10.125'; // ՀԴՄ սարքի IP հասցեն
        // $port = 8080; // ՀԴՄ սարքի պորտը
        // $hdmPassword = "96yQDWay";

        $museumAccessId = museumAccessId();

        $hasHdm = HdmConfig::where('museum_id', $museumAccessId)->first();       // when museum work with dhm

        if (!$hasHdm || ($hasHdm && !$hasHdm->status)) {

            return false;
        }


        $hdm = new HDM($hasHdm);  // hdm cashier login for hdm
        // $hdm = new HDM($ip, $port, $hdmPassword);

        $purchase = Purchase::find($purchase_id);
        $amount = $purchase->amount;
        $transaction_type = $purchase->hdm_transaction_type;
        $useExtPOS = $transaction_type == 'cashe' ? true : false;
        $paidAmount = $transaction_type == 'cashe' ? $purchase->amount : 0;
        $paidAmountCard = $transaction_type == 'cashe' ? 0 : $purchase->amount;
        $purchase_items = $purchase->purchased_items;

        $item_params = [
                          'dep' => 1,
                          'productCode' => '0015',
                          'adgCode' => '91.02',
                          'unit' => 'հատ',
                          'additionalDiscount' => 0,
                          'additionalDiscountType' => 0,
                          'discount' => 0,
                          'discountType' => 0
                        ];

        $items = [];


        foreach ($purchase_items as $key => $value) {
            if($value->total_price > 0){
                  $item_params['qty'] = $value->quantity;
                  $item_params['price'] = $value->total_price / $value->quantity;   // mek apranqi giny
                  $item_params['productName'] = getTranslateTicketTitl($value->type);

                  array_push($items, $item_params);
            }
        }


        $jsonBody = json_encode([
              // 'seq' => 100002,
              'paidAmount' => $paidAmount,
              'paidAmountCard' => $paidAmountCard,
              'partialAmount' => 0,
              'prePaymentAmount' => 0,
              'useExtPOS' => $useExtPOS,
              'mode' => 2,
              'items' => $items

            ]);


        $print = $hdm->socket($jsonBody, '04');

        if(!$print['success']){

          if( ( isset($print['result']['operationCode']) && $print['result']['operationCode'] == 102) || $print['result'] == 'logOut'){
           
              $this->cLogin();
              return $this->PrintHdm($purchase_id);
          }
        }
        else{

          $purchase->update([
            'hdm_crn' => $print['result']['crn'],
            'hdm_rseq' => $print['result']['rseq']
          ]);
        }

        return $print['success'];

  }

  public function returnHdm()
  {

    // $ip = '192.168.10.125'; // ՀԴՄ սարքի IP հասցեն
    // $port = 8080; // ՀԴՄ սարքի պորտը
    // $hdmPassword = "96yQDWay";

    $museumAccessId = museumAccessId();

    $hasHdm = HdmConfig::where('museum_id', $museumAccessId)->first();       // when museum work with dhm

    if ($hasHdm && $hasHdm->status) {

      $hdm = new HDM($hasHdm);  // hdm cashier login for hdm
      $purchase = Purchase::find(17088);

      $jsonBody = json_encode([
        // 'seq' => 100002,
        'crn' => $purchase->hdm_crn,
        'returnTicketId' => $purchase->hdm_rseq

      ]);

      return $return = $hdm->socket($jsonBody, '06');

    }

  }



}
