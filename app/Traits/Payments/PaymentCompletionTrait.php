<?php
namespace App\Traits\Payments;

use App\Mail\SendQRTiketsToUsersEmail;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\TicketPdf;
use App\Models\TicketQr;
use Barryvdh\DomPDF\Facade\Pdf;
use Mail;
use Storage;

trait PaymentCompletionTrait
{
  public function paymentCompletion(array $data, $order_id)
  {

    Payment::where('payment_order_id', $order_id)->update($data);
    $payment = $this->getPayment($order_id);
    $pdfPath = null;

    if ($payment->group_payment_status == 'success' && $payment->status == 'confirmed') {
      $response = 'OK';

      // =============== get QR via paymant purchase_id ======================
      if($payment->purchase->status == 0){

        $generate_qr = $this->getTokenQr($payment->purchase_id);
        if (count($generate_qr) > 0) {

          $email = $payment->purchase->email;


          $result = mail::send(new SendQRTiketsToUsersEmail($generate_qr, $email));
        }

      }


      // =============== update purchase status to 1 ======================
      $payment->purchase->update(['status' => 1]);
      $this->updateItemQuantity($payment->purchase_id);

      // =============== if transaction from cart, delete cart items ======================
      if ($payment->guard_type == 'cart') {
        $user = $payment->purchase->user;
        if ($user) {

          $user->carts->each(function ($cart) {
              $cart->delete();
          });
        }
      }

      // =============== 18.09.24 ===============================
      if ($payment->purchase->type == 'online' && $payment->guard_type != 'cart') {
          $purchasedId = $payment->purchase->id;
          $museumId = $payment->purchase->museum_id;

          $purchase = Purchase::find($purchasedId);
          $purchaseItemsIds = $purchase->purchased_items->pluck('id')->toArray();
          $generate_qr = TicketQr::whereIn('purchased_item_id', $purchaseItemsIds)->get();

        $pdfPath = $this->pdfTickets($generate_qr, $museumId, $purchasedId);
      }

      // =============== 18.09.24 ===============================

    } else {
      $response = 'Diny';

    }


    // $redirect_url = $payment->redirect_url . "?result=$response";
    // echo "<script type='text/javascript'>
    //                 window.location = '$redirect_url'
    //           </script>";

    // museumsarmenia.am

    if($payment->guard_name == 'mobile'){
        $redirect_url = $payment->redirect_url . "?result=$response&pdf=$pdfPath";
    }
    else{
        $redirect_url = 'https://museumsarmenia.am/am/' . "?result=$response&pdf=$pdfPath";
    }

    echo "<script type='text/javascript'>
                    window.location = '$redirect_url'
              </script>";



  }

  public function pdfTickets($data, $museumId, $purchasedId = null){

      $pdf = TicketPdf::where('purchased_id', $purchasedId)->first();

      if($pdf == null){
        $pdf = Pdf::loadView('components.qr-tickets', ['result' => $data])->setPaper('a4', 'portrait');

        $fileName = 'ticket-' . time() . $purchasedId . '.pdf';
        $path = 'public/pdf-file/' . $fileName;

        Storage::put($path, $pdf->output());

        TicketPdf::create([
          'museum_id' => $museumId,
          'purchased_id' =>$purchasedId,
          'pdf_path' => $path
        ]);
      }
      else{
        $path =  $pdf->pdf_path;
        $fileName = explode('public/pdf-file/', $path)[1];
      }


      return asset('storage/' . 'pdf-file/' . $fileName);

  }

}

