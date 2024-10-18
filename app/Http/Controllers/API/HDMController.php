<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class HDMController extends Controller
{
  protected $secondKey = null;
  public function encryptData($data, $key) {
      // PKCS7 padding-ի համար կոդավորվող տվյալները բլոկի չափի համաձայն
      // 3DES կոդավորում օգտագործելով ECB ռեժիմ

      $data = $this->pkcs7_pad($data);
      $encrypted = openssl_encrypt($data,'des-ede3-ecb',$key,OPENSSL_RAW_DATA);
      // | OPENSSL_NO_PADDING
      return $encrypted;
  }

  public function decryptData($data, $key) {
      // PKCS7 padding-ի համար կոդավորվող տվյալները բլոկի չափի համաձայն
      // 3DES կոդավորում օգտագործելով ECB ռեժիմ

      // $data = $this->pkcs7_pad($data);
      $decrypted = openssl_decrypt($data,'des-ede3-ecb',$key,OPENSSL_RAW_DATA);

      return $decrypted;
  }
  public function pkcs7_pad($data) {
    $block_size = 8; // Размер блока 3DES в байтах
    $pad = $block_size - (strlen($data) % $block_size);
    return $data . str_repeat(chr($pad), $pad);
  }



  public function createHeader($operationCode, $data_length) {
    // Գլխագիրը պետք է լինի 12 բայթ
    $header = "";

    // Առաջին 6 բայթը կոնստանտ արժեքներ են ըստ փաստաթղթի
    $header .= hex2bin("D580D4B4D5840007");

    // $header .= hex2bin("D580D4B4D584000501000205");


    // 9-րդ բայթ - գործողության կոդը (օր.՝ կտրոնի տպում՝ 4)
    $header .= chr($operationCode);
    $header .= hex2bin("00");

    $header .= chr(intval($data_length >> 8));
    $header .= chr(intval($data_length & 0xFF));
    return $header;
  }

public function generateKey($password) {
  // Գեներացնում ենք 24-բայթանոց բանալի ՀԴՄ գաղտնաբառի հիման վրա
  $hash = hash('sha256', $password, true);

  return substr($hash, 0, 24); // Վերցնում ենք առաջին 24 բայթերը
}


  public function cashiers(){

      $ip = '192.168.10.125'; // ՀԴՄ սարքի IP հասցեն
      $port = 8080; // ՀԴՄ սարքի պորտը
      $hdm_password = "96yQDWay";

      // 1. Նոր սոկետ ստեղծել
      $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
      if ($socket === false) {
          die("Սոկետ ստեղծելու ժամանակ սխալ է տեղի ունեցել: " . socket_strerror(socket_last_error()));
      }

      // 2. TCP կապ հաստատել ՀԴՄ սարքի հետ
      $result = socket_connect($socket, $ip, $port);
      if ($result === false) {
          die("ՀԴՄ սարքի հետ կապ հաստատելու ժամանակ սխալ է տեղի ունեցել: " . socket_strerror(socket_last_error($socket)));
      }

      socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, ["sec"=>30, "usec"=>0]);  // Սպասելու ժամանակ 30 վայրկյան


      $first_key = $this->generateKey($hdm_password);

      // Հարցման մարմինը JSON ձևաչափով
      $jsonBody = json_encode([
        'password' => $hdm_password
        // 'cashier' => 3,
        // 'pin' => 3
      ]);


      $enc_data = $this->encryptData($jsonBody, $first_key);

      $operationCode = '01';  // Օրինակ՝ 2
      $header = $this->createHeader($operationCode, strlen($enc_data));

      // Հարցման ամբողջական մարմին
      $request = $header . $enc_data;

      // 4. Ուղարկել հարցումը
      socket_write($socket, $request, strlen($request));

      // 5. Ստանալ պատասխան
      $response = socket_read($socket, 2048); // 2048 բայթ կարդալ


      // 6. Պատասխանն ապակոդավորել
      $response_data = substr($response, 11); // Հանենք գլխի 12 բայթը
      $resp_json = $this->decryptData($response_data, $first_key);
      // $resp_json = openssl_decrypt($response_data,'des-ede3-ecb',$first_key,OPENSSL_RAW_DATA);
      print_r($resp_json);
      // 7. Արտածել պատասխան
      // echo "Սարքից ստացված պատասխան HEX՝ ".bin2hex($response)."\n";

      // 8. Զանգը փակել
      socket_close($socket);
  }



  public function cashierLogin(){

    $ip = '192.168.10.125'; // ՀԴՄ սարքի IP հասցեն
    $port = 8080; // ՀԴՄ սարքի պորտը
    $hdm_password = "96yQDWay";

    // 1. Նոր սոկետ ստեղծել
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($socket === false) {
        die("Սոկետ ստեղծելու ժամանակ սխալ է տեղի ունեցել: " . socket_strerror(socket_last_error()));
    }

    // 2. TCP կապ հաստատել ՀԴՄ սարքի հետ
    $result = socket_connect($socket, $ip, $port);
    if ($result === false) {
        die("ՀԴՄ սարքի հետ կապ հաստատելու ժամանակ սխալ է տեղի ունեցել: " . socket_strerror(socket_last_error($socket)));
    }

    socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, ["sec"=>30, "usec"=>0]);  // Սպասելու ժամանակ 30 վայրկյան


    $first_key = $this->generateKey($hdm_password);

    // Հարցման մարմինը JSON ձևաչափով
    $jsonBody = json_encode([
      'password' => $hdm_password,
      'cashier' => 3,
      'pin' => 3
    ]);


    $enc_data = $this->encryptData($jsonBody, $first_key);

    $operationCode = '02';  // Օրինակ՝ 2
    $header = $this->createHeader($operationCode, strlen($enc_data));

    // Հարցման ամբողջական մարմին
    $request = $header . $enc_data;

    // 4. Ուղարկել հարցումը
    socket_write($socket, $request, strlen($request));

    // 5. Ստանալ պատասխան
    $response = socket_read($socket, 2048); // 2048 բայթ կարդալ


    // 6. Պատասխանն ապակոդավորել
    $response_data = substr($response, 11); // Հանենք գլխի 12 բայթը
    $resp_json = $this->decryptData($response_data, $first_key);
    // $resp_json = openssl_decrypt($response_data,'des-ede3-ecb',$first_key,OPENSSL_RAW_DATA);
    $resp = json_decode( $resp_json);

    // $this->secondKey = $resp->key;
echo $resp->key;
    // dd($this->secondKey);
    // 7. Արտածել պատասխան
    // echo "Սարքից ստացված պատասխան HEX՝ ".bin2hex($response)."\n";

    // 8. Զանգը փակել
    socket_close($socket);
  }

  public function printReceipt(){
    // dd($this->secondKey);
    $ip = '192.168.10.125'; // ՀԴՄ սարքի IP հասցեն
    $port = 8080; // ՀԴՄ սարքի պորտը
    $hdm_password = "96yQDWay";
    $second_key = '/DjpnvMAcAuEGttBMdxj0R+51EP7ErcA';
    $second_key = base64_decode($second_key);
    dump(bin2hex($second_key), strlen($second_key), $second_key);
    // 1. Նոր սոկետ ստեղծել
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($socket === false) {
        die("Սոկետ ստեղծելու ժամանակ սխալ է տեղի ունեցել: " . socket_strerror(socket_last_error()));
    }

    // 2. TCP կապ հաստատել ՀԴՄ սարքի հետ
    $result = socket_connect($socket, $ip, $port);
    if ($result === false) {
        die("ՀԴՄ սարքի հետ կապ հաստատելու ժամանակ սխալ է տեղի ունեցել: " . socket_strerror(socket_last_error($socket)));
    }

    socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, ["sec"=>30, "usec"=>0]);  // Սպասելու ժամանակ 30 վայրկյան


    // $second_key = $this->generateKey($hdm_password);

    // Հարցման մարմինը JSON ձևաչափով
    $jsonBody = json_encode([
      // 'seq' => 100002,
      'paidAmount' => 0,
      'paidAmountCard' => 10,
      'partialAmount' => 0,
      'prePaymentAmount' => 0,
      'useExtPOS' => true,
      'mode' => 2,
      // 'partnerTin' =>  null,
      'items'=> [
            ['dep' => 1,
            'qty' => 1,
            'price' => 10,
            'productCode' => '0015',
            'productName' => 'ticket',
            'adgCode' => '91.02',
            'unit' => 'hat',
            'additionalDiscount' => 0,
            'additionalDiscountType' => 0,
            'discount' => 0,
            'discountType' => 0
            ]
      ]
    ]);
    // $jsonBody = json_encode([
    //   // 'seq' => 100002,
    //   'crn' => '53028644',
    //   // 'receiptId' => '00000016'
    //   'returnTicketId' =>'00000013',

    //   // 'cashAmountForReturn' => 10,
    //   // 'cardAmountForReturn' => 0.0

    // ]);
    echo($jsonBody);


    $enc_data = $this->encryptData($jsonBody, $second_key);
    dump(bin2hex($enc_data));

    $operationCode = '04';  // Օրինակ՝ 2
    $header = $this->createHeader($operationCode, strlen($enc_data));

    // Հարցման ամբողջական մարմին
    $request = $header . $enc_data;

    // 4. Ուղարկել հարցումը
    socket_write($socket, $request, strlen($request));

    // 5. Ստանալ պատասխան
    $response = socket_read($socket, 2048); // 2048 բայթ կարդալ

    // dd( $response);
    // 6. Պատասխանն ապակոդավորել
    $response_data = substr($response, 11); // Հանենք գլխի 12 բայթը
    dump($response_data);
    dump($this->parseHeader( $response));
    $resp_json = $this->decryptData($response_data, $second_key);
    // $resp_json = openssl_decrypt($response_data,'des-ede3-ecb',$first_key,OPENSSL_RAW_DATA);
    $resp = json_decode( $resp_json);

    dump($resp);


    // 7. Արտածել պատասխան
    echo "Սարքից ստացված պատասխան HEX՝ ".bin2hex($response)."\n";

    // 8. Զանգը փակել
    socket_close($socket);
  }


  public function parseHeader($header) {
    // Убедимся, что длина заголовка соответствует ожидаемым 12 байтам
    // if (strlen($header) !== 11) {
    //     throw new Exception("Неверная длина заголовка");
    // }

    // Первые 6 байт - это константные значения, их просто пропустим
    $constant = substr($header, 0, 6);
  // dd($constant );
    // Проверяем, что они соответствуют ожидаемому значению
    // if (bin2hex($constant) !== "d580d4b4d584") {
    //     throw new Exception("Неверные константные байты");
    // }

    // 7-й байт - это операция (operationCode)
    $operationCode = ord($header[6]);

    // 8-й байт игнорируем (0x00)

    // 9-й и 10-й байты - длина данных (data_length)
    $data_length = (ord($header[8]) << 8) + ord($header[9]);

    return [
        'operationCode' => $operationCode,
        'data_length' => $data_length
    ];
  }

}
