<?php
namespace App\Traits\Payments;

use App\Models\Payment;
use GuzzleHttp\Client;

trait CheckPaymentStatusTrait
{
  public function checkStatus(string $order_id)
  {

      $client = new Client(['verify' => false]);
      $result_data = false;

      $response = $client->post('https://api.e-payments.am/group-payments/check-status', [
        'headers' => [
          'Content-Type' => 'application/json',
          'token' => env('PAYMENT_TOKEN')
        ],
        'body' => json_encode([
          "payment" => [
              "order_number" => $order_id
          ]
        ])
      ]);

      if ($response->getStatusCode() == 200) { // 200 OK

            $response_d = $response->getBody()->getContents();
            $response_data = json_decode($response_d);
            $payment_result = $response_data->status;
            $response = '';
            if ($response_data->status == 'OK') {


                $group_payment_status = $response_data->data->group_payment_status;
                $status = $response_data->data->payment != null ? $response_data->data->payment->status : null;
                $result_data = ['status' => $status, 'group_payment_status' => $group_payment_status];

                // if($response_data->data->group_payment_status == 'success' && $response_data->data->payment != null && $response_data->data->payment->status == 'confirmed'){
                //     $response = 'OK';
                // }

            } else {

                $message = isset($response_data->message) ? $response_data->message : (isset($response_data->errors) ? $response_data->errors->payment : null);
                // $response = 'error_payment';
                $result_data = ['payment_message' => $message];
            }

            //  $payment = $this->getPayment($order_id);
             $result_data['payment_result'] = $payment_result;
            //  $payment->update($result_data);

            //  $this->paymentCompletion($result_data, $order_id);

      }

      return $result_data;
  }



}
