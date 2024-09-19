<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\cashier\CashierController;
use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\TicketQr;
use App\Models\User;
use App\Traits\NodeApi\QrTokenTrait;
use App\Traits\Payments\PaymentCompletionTrait;
use Illuminate\Http\Request;
use App\Mail\SendQRTiketsToUsersEmail;
use Mail;
class ChangeStyleController extends CashierController
{
    use QrTokenTrait, PaymentCompletionTrait;
    public function change_style(Request $request, $type)
    {
        session()->put('style', $type);

        return back();

    }



    public function test_email(Request $request, $purchaseId, $email)
    {

        // for sernd online ticket to email via purchased_id and email (important)


        try {
          $purchase = Purchase::find($purchaseId);
          $purchaseItemsIds = $purchase->purchased_items->pluck('id')->toArray();
          $generate_qr = TicketQr::whereIn('purchased_item_id', $purchaseItemsIds)->get();

          $result = mail::send(new SendQRTiketsToUsersEmail($generate_qr, $email));

          echo $result ?  "email sent successfully" : 'error';

        } catch (\Throwable $th) {
          dd($th);
          throw $th;
        }



    }

    public function testPdfTickets(Request $request, $purchaseId){
        $purchase = Purchase::find($purchaseId);
        $purchaseItemsIds = $purchase->purchased_items->pluck('id')->toArray();
        $generate_qr = TicketQr::whereIn('purchased_item_id', $purchaseItemsIds)->get();
        $pdfPath = $this->pdfTickets($generate_qr, $purchase->museum_id, $purchase->id);

        $redirect_url = 'https://museumsarmenia.am/am/' . "?result=OK&pdf=$pdfPath";
        echo "<script type='text/javascript'>
                        window.location = '$redirect_url'
                  </script>";

    }



}
