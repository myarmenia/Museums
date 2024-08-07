<?php
namespace App\Traits\Turnstile;

use App\Models\Museum;
use App\Models\Turnstile;
use GuzzleHttp\Client;

trait Managment
{
  public function turnstile($data)
  {
      $action = $data['action'];
      $museum_id = getAuthMuseumId();
      $museum = Museum::find($museum_id);

      $local_ip = $museum->turnstile->local_ip;


      $client = new Client(['verify' => false]);

      try {
          $response = $client->post("http://$local_ip/post", [
            'headers' => [
              'Content-Type' => 'application/json',
            ],
            'body' => json_encode($data)
          ]);

          if ($response->getStatusCode() == 200) { // 200 OK

            $response_d = $response->getBody()->getContents();
            $response_data = json_decode($response_d);
            // dd($response_data);
            return $response_data->status;

          } else {
            // Handle non-200 status code
            return false;
          }
      } catch (\Throwable $th) {
        // throw $th;
        return false;
      }

  }


}
