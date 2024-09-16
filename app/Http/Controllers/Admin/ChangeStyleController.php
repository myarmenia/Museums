<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\cashier\CashierController;
use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\TicketQr;
use App\Models\User;
use App\Traits\NodeApi\QrTokenTrait;
use Illuminate\Http\Request;
use App\Mail\SendQRTiketsToUsersEmail;
use Mail;
class ChangeStyleController extends CashierController
{
    use QrTokenTrait;
    public function change_style(Request $request, $type)
    {
        session()->put('style', $type);

        return back();

    }



    public function test_email(Request $request, $purchaseId, $email)
    {

        // $generate_qr = $this->getTokenQr($purchaseId);


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



}
