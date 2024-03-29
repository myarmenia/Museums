<?php
namespace App\Traits\Payments;

use App\Models\Payment;
use App\Models\Purchase;


trait PaymentCompletionTrait
{
    public function paymentCompletion(array $data, $order_id)
    {

        Payment::where('payment_order_id', $order_id)->update($data);
        $payment = $this->getPayment($order_id);

        if ($payment->group_payment_status == 'success' && $payment->status == 'confirmed') {
            $response = 'OK';

            $this->updateItemQuantity($payment->purchase_id);
            if($payment->guard_type == 'cart'){
                $user = $payment->purchase->user;
                if($user){
                    $user->cart->delete();
                }
            }
            // code get QR via $paymant->purchase_id
            // code send email
        }
        else{
            $response = 'Diny';

        }


        return $payment->guard_name == 'mobile' ?
                  "<script type='text/javascript'>
                      window.location = 'mobile://museum/". $response ."'
                  </script>" :
                  "<script type='text/javascript'>
                      window.location = 'web://museum/". $response ."'
                  </script>";


    }

}

