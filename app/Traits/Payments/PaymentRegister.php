<?php
namespace App\Traits\Payments;
use App\Models\Payment;
use App\Models\PurchasedItem;
use App\Models\PurchaseUnitedTickets;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use stdClass;


trait PaymentRegister
{
  public function register($data)
  {

      $payments = [];

      $data_items = $data->purchased_items()->where('type', '!=', 'united')->groupBy('museum_id','type')
          ->select('museum_id',  \DB::raw('MAX(type) as type'), \DB::raw('SUM(total_price) as total_price'))
          ->get();

      $data_united_items_ids = $data->purchased_items->where('type', 'united')->pluck('id');
      $data_united_items = PurchaseUnitedTickets::whereIn('purchased_item_id', $data_united_items_ids)->groupBy('museum_id')
        ->select('museum_id', \DB::raw('SUM(price) as total_price'))
        ->get();

      foreach ($data_items as $key => $item) {

          $item_params = new stdClass();
          $item_params->amount = $item->total_price;
          // $item_params->account = $item->museum->account_number;
          $item_params->account = "900018001322";
          $item_params->beneficiary_name = $item->museum->translationsForAdmin->name;
          $item_params->notice = $this->getNotice($item->type);
          $item_params->description = "Վաճառք";

          array_push($payments, $item_params);
      }

      if(count( $data_united_items) > 0){
            foreach ($data_united_items as $k => $united_item) {

                $item_params = new stdClass();
                $item_params->amount = $united_item->total_price;
                // $item_params->account = $united_item->museum->account_number;
                $item_params->account = "900018001322";
                $item_params->beneficiary_name = $united_item->museum->translationsForAdmin->name;
                $item_params->notice = $this->getNotice('united');
                $item_params->description = "Վաճառք";

                array_push($payments, $item_params);

            }
      }


      $client = new Client(['verify' => false]);


      $response = $client->post('https://api.e-payments.am/group-payments/register', [
          'headers' => [
            'Content-Type' => 'application/json',
            'token' => env('PAYMENT_TOKEN')
          ],
          'body' => json_encode([
            "callback_url" => url(''). '/purchase/payment-result',
            "contact_email" => $data['email'],
            'payments' => $payments
            ])
      ]);



        if ($response->getStatusCode() == 200) { // 200 OK

              $response_d = $response->getBody()->getContents();
              $response_data = json_decode($response_d);

              if($response_data->status == 'OK'){

                  $order_id = $response_data->data->order_number;
                  $order = [
                    'purchase_id' => $data->id,
                    'payment_order_id' => $order_id,
                    'payment_result' => 'new',
                    'guard_name' => request()->request_name,
                    'guard_type' => request()->request_type

                  ];

                  $this->addPayment($order);

                  return $response_data->data->redirect_url;
              }
              else{
                  return 'error_payment';
              }

          }

  }


    public function getNotice($type)
    {
        return $type == 'subscription' ? 'Աբոնեմենտ տոմս' :
              ($type == 'united' ? 'Միասնական տոմս' :
              ($type == 'event' ? 'Միջոցառման տոմս' :
              ($type == 'product' ? 'Թանգարանի ապրանք' : 'Թանգարանի տոմս')));

    }

}
