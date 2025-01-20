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

      // $ip = '192.168.10.125'; // ՀԴՄ սարքի IP հասցեն
      // $port = 8080; // ՀԴՄ սարքի պորտը
      // $hdmPassword = "96yQDWay";



        $hasHdm = museumHasHdm();

        if (!$hasHdm) {
          return false;
        }

        $arr = ['school', 'educational'];

        // $incalculableTypes = ['school', 'free']; // no ticket should be printed for these types
        $purchase_item = PurchasedItem::find($purchased_item_id);

        // dd($purchase_item->type);
        $quantity = in_array($purchase_item->type, $arr ) ? $purchase_item->quantity : 1;
        $purchase = $purchase_item ? $purchase_item->purchase : null;

        $return_item = [
          'rpid' => 0,
          // 'quantity' => 1
        ];

        // dd($purchase->hdm_rseq);
        $rseq = $purchase->hdm_rseq;

        $hdm = new HDM($hasHdm);  // hdm cashier login for hdm
        $purchase = Purchase::find(17088);
        $jsonBody = json_encode([
          // 'seq' => 100002,
          'crn' => $purchase->hdm_crn,
          'returnTicketId' => $rseq,
          // 'receiptId' => $rseq,   /// veradarghvogh ktroni stacum

          // 'returnItemList' => $return_item,
          // 'cashAmountForReturn' =>5,
          // 'cardAmountForReturn' => 0


        ]);


    $return = $hdm->socket($jsonBody, '06');
   return $return;
          // return $return = $hdm->socket($jsonBody, '12');



  }

}
