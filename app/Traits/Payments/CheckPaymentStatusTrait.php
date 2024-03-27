<?php
namespace App\Traits\Payments;

use App\Models\Payment;
use GuzzleHttp\Client;

trait CheckPaymentStatusTrait
{
  public function checkStatus($order_id)
  {

      $client = new Client(['verify' => false]);


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

      // if ($response->getStatusCode() == 200) { // 200 OK

      //       $response_d = $response->getBody()->getContents();
      //       $response_data = json_decode($response_d);
      //       $status = $response_data->status;
      //       if ($response_data->status == 'OK') {
      //         dd($response_data->data);
      //         $payment = $this->getPayment($order_id);
      //         group_payment_status
      //         $payment_result = $response_data->data->payment->status;

      //         $payment->update(['status' => $response_data->status]);
      //         if($response_data->data->group_payment_status == 'success' && $response_data->data->payment != null && $response_data->data->payment->status == 'confirmed')
      //         $order_id = $response_data->data->order_number;

      //         return $response_data->data->redirect_url;
      //       } else {
      //         $message = isset($response_data->message) ? $response_data->message : (isset($response_data->errors) ? $response_data->errors->payment : null);
      //         return 'error_payment';
      //       }

      // }
  }



}
