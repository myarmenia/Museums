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

        $hasHdm = museumHasHdm();

        if (!$hasHdm) {
            return false;
        }

        $incalculableTypes = ['school', 'free']; // no ticket should be printed for these types
        $types_for_names = ['event', 'event-config', 'educational', 'product', 'other_service']; // for productName


        $purchase = Purchase::find($purchase_id);
        $purchase_items = $purchase->purchased_items->whereNotIn('type', $incalculableTypes);
        $items = [];

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

        foreach ($purchase_items as $key => $value) {
          if ($value->total_price > 0) {
            $tp = $value->type;

            $type = getTranslateTicketTitl($value->type);
            $sub_type = in_array($value->type, ['event', 'event-config']) ? ' / ' . getTranslateTicketSubTitle($value->sub_type) : null;

            $name = '';

            if(in_array($tp, $types_for_names)){

              // $relation = $tp == 'event-config' ? 'event' : $tp;
              $relation = $tp;
              $name = $tp == 'event-config' ? $value->event_config->event->translation('am')->name : $value->{$relation}->translation('am')->name;

            }

            $name = $name != '' ? ' / ' . $name: null;

            $item_params['qty'] = $value->quantity;
            $item_params['price'] = $value->total_price / $value->quantity;   // mek apranqi giny
            $item_params['productName'] = $type . $name . $sub_type;

            array_push($items, $item_params);
          }
        }


        $total_price = array_reduce($items, function ($carry, $item) {
          return $carry + ($item['price'] * $item['qty']);
        }, 0);

        if($total_price > 0 ){

            $hdm = new HDM($hasHdm);
            // $hdm = new HDM($ip, $port, $hdmPassword);

            $transaction_type = $purchase->hdm_transaction_type;
            $useExtPOS = $transaction_type == 'cash' ? true : false;
            $paidAmount = $transaction_type == 'cash' ? $total_price : 0;
            $paidAmountCard = $transaction_type == 'cash' ? 0 : $total_price;

            $parrams = [
                'paidAmount' => $paidAmount,
                'paidAmountCard' => $paidAmountCard,
                'partialAmount' => 0,
                'prePaymentAmount' => 0,
                'mode' => 2,
                'items' => $items
            ];

            // $this->hdmFooter('remove');

            if($transaction_type == 'card'){
                $parrams['useExtPOS'] = false;

            }


            if($transaction_type == 'otherPos'){
                $parrams['useExtPOS'] = true;

                // $this->hdmFooter('add');
            }


            $parrams['items'] = $items;


            $jsonBody = json_encode($parrams);


              // dd($footer);
              $print = $hdm->socket($jsonBody, '04');

              if (!$print['success']) {

                  if ((isset($print['result']['operationCode']) && $print['result']['operationCode'] == 102) || $print['result'] == 'logOut') {


                    $cashier_login = $hdm->cashierLogin();

                    return $cashier_login ? $this->PrintHdm($purchase_id) : $cashier_login;
                  }

                  return $print;

              } else {

                $purchase->update([
                  'hdm_crn' => $print['result']['crn'],
                  'hdm_rseq' => $print['result']['rseq']
                ]);
              }

              return $print;
        }

        return [
          'success' => true,
          'result' => true
        ];

  }

  public function hdmFooter($type) // for other transaction set footer
  {

      $hasHdm = museumHasHdm();

      if (!$hasHdm) {
        return false;
      }

      $hdm = new HDM($hasHdm);  // hdm cashier login for hdm

      $text = $type == 'add' ? 'Այլ տերմինալով վճարում ' : '';

      $jsonBody = json_encode([
        'headers'=> [
                        [
                          'text' => ''
                          ]
                        ],

        'footers' => [
                        [
                          'text' => ''
                          ]
                    ]
      ]);

       $return = $hdm->socket($jsonBody, '07');

  }



}
