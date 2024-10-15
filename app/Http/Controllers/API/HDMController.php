<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HDMController extends Controller
{
  public function __invoke()
  {

      $host = '192.168.1.157';
      $port = 8080;
      $password = 'ReVZh4PJ';

      // Создаем TCP-соединение
      $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
      if (!$socket) {
        dd(11);
          die("Couldn't create socket: " . socket_strerror(socket_last_error()));
      }
      if ($socket === false) {
        dd(66);
        echo "Ошибка создания сокета: " . socket_strerror(socket_last_error()) . "\n";
      } else {
        if (socket_connect($socket, $host, $port)) {
        // 12-բայթ գլխագրի կառուցում
          $id = 1;  // ID, օրինակ՝ 1
          $type = 2;  // Տվյալների տեսակ, օրինակ՝ 2
          $dataLength = 100;  // Տվյալների երկարությունը, օրինակ՝ 100 բայթ

          // Գլխագրի փաթեթավորում որպես 12-բայթ բինար տվյալ
          $header = pack('N*', $password);

            // Ուղարկել 12-բայթ գլխագիրը
            // $sent = socket_write($socket, $header, strlen($header));
            // if ($sent === false) {
            //     die("Socket write error: " . socket_strerror(socket_last_error($socket)));
            // }

            // echo "12-բայթ գլխագիրը ուղարկվեց հաջողությամբ";

            $data = json_encode([
              "password" => "ReVZh4PJ",
              "cashier"=>3,
              "pin"=>3
          ]);
          $key = $this->generateKey($password);

          $encryptedData = $this->encryptData($data, $key);
          $p = socket_write($socket, $encryptedData, strlen($encryptedData));
            // Փակել սոկետը
            // socket_close($socket);

            $response = socket_read($socket, 2048);  // ստանում ենք պատասխանը

            if ($response === false) {
              die("Unable to read from socket: " . socket_strerror(socket_last_error($socket)));
          }
            $decryptedResponse = decryptData($response, $key);  // ապակոդավորում ենք պատասխանը
            dd($decryptedResponse);
      }
    }
dd('finish');
  }

  function encryptData($data, $key) {
    // PKCS7 padding-ի համար կոդավորվող տվյալները բլոկի չափի համաձայն
    $blockSize = 8;  // 3DES բլոկի չափը
    $pad = $blockSize - (strlen($data) % $blockSize);
    $data .= str_repeat(chr($pad), $pad);

    // 3DES կոդավորում օգտագործելով ECB ռեժիմ
    $encrypted = openssl_encrypt($data, 'DES-EDE3', $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING);

    return $encrypted;
}

function generateKey($password) {
  // Գեներացնում ենք 24-բայթանոց բանալի ՀԴՄ գաղտնաբառի հիման վրա
  $hash = hash('sha256', $password, true);
  return substr($hash, 0, 24); // Վերցնում ենք առաջին 24 բայթերը
}

}
