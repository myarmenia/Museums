<?php
namespace App\Traits\Payments;

use App\Models\Payment;


trait PaymentCompletionTrait
{
    public function paymentCompletion(array $data, $order_id)
    {

        Payment::where('payment_order_id', $order_id)->update($data);
        $payment = $this->getPayment($order_id);

        if ($payment->group_payment_status == 'success' && $payment->status == 'confirmed') {
            $response = 'OK';

            $this->updateItemQuantity($payment->purchase_id);
            // code get QR
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

