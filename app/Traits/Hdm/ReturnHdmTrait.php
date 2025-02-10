<?php
namespace App\Traits\Hdm;

use App\HDM\HDM;
use App\Models\HdmConfig;
use App\Models\Purchase;
use App\Models\PurchasedItem;


trait ReturnHdmTrait
{
  public function returnHdm($purchased_item_id)
  {

        $hasHdm = museumHasHdm();

        if (!$hasHdm) {
          return false;
        }


        $purchase_item = PurchasedItem::find($purchased_item_id);

        if($purchase_item){
            $type = $purchase_item->type;
            $sub_type = $purchase_item->sub_type;


            $purchase = $purchase_item->purchase;
            $transaction_type = $purchase->hdm_transaction_type;

            $crn = $purchase->hdm_crn;
            $rseq = $purchase->hdm_rseq;


            $type_name = getTranslateTicketTitl($type);
            $sub_type_name = in_array($type, ['event', 'event-config']) ? ' / ' . getTranslateTicketSubTitle($sub_type) : null;

            $types_for_names = ['event', 'event-config', 'educational', 'product', 'other_service']; // for productName
            $types_for_packet = ['educational', 'product', 'other_service']; // for packet return

            $name = '';

            if (in_array($type, $types_for_names)) {

              // $relation = $type == 'event-config' ? 'event' : $type;
              // $name = $purchase_item->{$relation}->translation('am')->name;
              $relation = $type;
              $name = $type == 'event-config' ? $purchase_item->event_config->event->translation('am')->name : $purchase_item->{$relation}->translation('am')->name;

            }

            $name = $name != '' ? ' / ' . $name : null;

            $search_name = $type_name . $name . $sub_type_name;
            $price = $purchase_item->total_price / $purchase_item->quantity;

            $hdm = new HDM($hasHdm);  // hdm cashier login for hdm
            $hdm_coupon = $this->getReturnHdm($crn, $rseq);


            if(!$hdm_coupon['success']){
                return $hdm_coupon;
            }


            $hdm_coupon_item = array_filter($hdm_coupon['result']['totals'], function ($item) use ($search_name, $price) {
                  return $item['gn'] === $search_name && $item['p'] == $price;
            });

            $hdm_coupon_item = reset($hdm_coupon_item);

            $quantity = in_array($type, $types_for_packet) ? $hdm_coupon_item['qty'] : 1;
            $rpid = $hdm_coupon_item['rpid'];
            $total_price = in_array($type, $types_for_packet) ?  $hdm_coupon_item['tt'] :  $hdm_coupon_item['p'];

            $cashAmountForReturn = $transaction_type == 'cash' ? $total_price : 0;
            $cardAmountForReturn = $transaction_type == 'cash' ? 0 : $total_price;


            $return_item = [
                              [
                                'rpid' => $rpid,
                                'quantity' => $quantity
                              ]
                          ];


            $parrams = [
                        'crn' => $crn,
                        'returnTicketId' => $rseq,
                        'returnItemList' => $return_item,
                        'cashAmountForReturn' => $cashAmountForReturn,
                        'cardAmountForReturn' => $cardAmountForReturn
                      ];

                      // dd($parrams);

            $jsonBody = json_encode($parrams);
            $return = $hdm->socket($jsonBody, '06');

            return $return;
        }

        return [
              'success' => false,
              'result' => [
                'message' => "Տոմսը չի գտնվել։",
                'error' => 1
              ]
            ];

  }



  public function getReturnHdm($crn, $rseq)
  {

        $hasHdm = museumHasHdm();

        if (!$hasHdm) {
          return false;
        }

        $hdm = new HDM($hasHdm);  // hdm cashier login for hdm

        $jsonBody = json_encode([

          'crn' => $crn,
          'receiptId' => $rseq   /// veradarghvogh ktroni stacum

        ]);

        return $hdm->socket($jsonBody, '10');

  }

}

