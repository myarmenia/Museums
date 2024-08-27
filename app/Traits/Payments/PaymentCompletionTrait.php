<?php
namespace App\Traits\Payments;

use App\Mail\SendQRTiketsToUsersEmail;
use App\Models\Payment;
use App\Models\Purchase;
use App\Services\Log\LogService;
use Mail;

trait PaymentCompletionTrait
{
  public function paymentCompletion(array $data, $order_id)
  {

    Payment::where('payment_order_id', $order_id)->update($data);
    $payment = $this->getPayment($order_id);

    if ($payment->group_payment_status == 'success' && $payment->status == 'confirmed') {
      $response = 'OK';

        LogService::store($data, 1, 'e-pay', 'store');


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

    } else {
      $response = 'Diny';

    }


    // $redirect_url = $payment->redirect_url . "?result=$response";
    // echo "<script type='text/javascript'>
    //                 window.location = '$redirect_url'
    //           </script>";

    $redirect_url = 'https://museumsarmenia.am/am/'. "?result=$response";
    echo "<script type='text/javascript'>
                    window.location = '$redirect_url'
              </script>";



  }

}

