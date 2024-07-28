<?php
namespace App\Traits\Payments;

use App\Mail\SendQRTiketsToUsersEmail;
use App\Models\Payment;
use App\Models\Purchase;
use Mail;

trait PaymentCompletionTrait
{
  public function paymentCompletion(array $data, $order_id)
  {

    Payment::where('payment_order_id', $order_id)->update($data);
    $payment = $this->getPayment($order_id);

    if ($payment->group_payment_status == 'success' && $payment->status == 'confirmed') {
      $response = 'OK';

      // =============== update purchase status to 1 ======================
      $payment->purchase->update(['status' => 1]);
      $this->updateItemQuantity($payment->purchase_id);

      // =============== get QR via paymant purchase_id ======================
      $generate_qr = $this->getTokenQr($payment->purchase_id);
      if (count($generate_qr) > 0) {

        $email = $payment->purchase->email;

        $result = mail::send(new SendQRTiketsToUsersEmail($generate_qr, $email));
      }

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

    // window.location = 'museums://TicketCongrats/". $response ."'

    // echo $payment->guard_name == 'mobile' ?
    //           "<script type='text/javascript'>
    //               window.location = 'museums://TicketCongrats'
    //           </script>" :
    //           "<script type='text/javascript'>
    //               window.location = 'http://museumfront.gorc-ka.am/am/'
    //           </script>";
    $redirect_url = $payment->redirect_url . "?result=$response";
    echo "<script type='text/javascript'>
                    window.location = '$redirect_url'
              </script>";



  }

}

