<?php

namespace App\HDM;
use App\Models\HdmConfig;
use Auth;
use Exception;
use Session;

class HDM
{
  protected $ip;
  protected $port;
  protected $hdmPassword;
  protected $loginKey;
  protected $firstEncryptionKeys = ['01', '02'];   // 03 - 13 second encription keys


  public function __construct($hasHdm)
  {

    $this->ip = $hasHdm->ip;
    $this->port = $hasHdm->port;
    $this->hdmPassword = $hasHdm->password;

  }


  public function TCPClient($loginKey)
  {
    $this->loginKey = $loginKey;

  }



  public function cashierLogin()
  {

    $jsonBody = json_encode([
      'password' => $this->hdmPassword,
      'cashier' => 3,
      'pin' => 3
    ]);

    
    $res = $this->socket($jsonBody, '02');

    if ($res['success']) {

      Session::put('cashierLoginKey', $res['result']['key']);

      return true;

    }


    return [
            'success' => false,
            'result' => [
              'message' => "ՀԴՄ սարքի հետ կապ հաստատելու ժամանակ սխալ է տեղի ունեցել",
              'error' => 1
        ]
    ];


  }

  /**
   * 3DES կոդավորում օգտագործելով ECB ռեժիմ
   */
  public function pkcs7Pad($data)
  {
    $blockSize = 8; // Размер блока 3DES в байтах
    $pad = $blockSize - (strlen($data) % $blockSize);
    return $data . str_repeat(chr($pad), $pad);
  }




  /**
   * PKCS7 padding-ի համար կոդավորվող տվյալները բլոկի չափի համաձայն
   * 3DES կոդավորում օգտագործելով ECB ռեժիմ
   */
  public function encryptData($data, $key)
  {

    $data = $this->pkcs7Pad($data);
    $encrypted = openssl_encrypt($data, 'des-ede3-ecb', $key, OPENSSL_RAW_DATA);

    return $encrypted;
  }



  /**
   * PKCS7 padding-ի համար կոդավորվող տվյալները բլոկի չափի համաձայն
   * 3DES կոդավորում օգտագործելով ECB ռեժիմ
   */
  public function decryptData($data, $key)
  {

    $decrypted = openssl_decrypt($data, 'des-ede3-ecb', $key, OPENSSL_RAW_DATA);

    return $decrypted;
  }



  /**
   * Գեներացնում ենք 24-բայթանոց բանալի ՀԴՄ գաղտնաբառի հիման վրա
   * Վերցնում ենք առաջին 24 բայթերը
   */
  public function generateKey($password)
  {

    $hash = hash('sha256', $password, true);

    return substr($hash, 0, 24); // Վերցնում ենք առաջին 24 բայթերը
  }



  /**
   * first key or login key (second key)
   */
  public function getKey($operationCode){

    $loginKey = Session::get('cashierLoginKey');

    $key = in_array($operationCode, $this->firstEncryptionKeys) ? $this->generateKey($this->hdmPassword) :
    ($loginKey != null ? base64_decode($loginKey) : false);

    return $key;

  }




  /**
   * Գլխագիրը պետք է լինի 12 բայթ
   * Առաջին 6 բայթը կոնստանտ արժեքներ են ըստ փաստաթղթի
   * 9-րդ բայթ - գործողության կոդը (օր.՝ կտրոնի տպում՝ 4)
   */
  public function createHeader($operationCode, $dataLength)
  {
    $header = "";

    $header .= hex2bin("D580D4B4D5840007");

    $header .= chr($operationCode);
    $header .= hex2bin("00");

    $header .= chr(intval($dataLength >> 8));
    $header .= chr(intval($dataLength & 0xFF));

    return $header;
  }



  /**
   * socket connect
   * socket write
   * socket read
   */
  public function socket(string $jsonBody = null, string $operationCode)
  {

    try {

      // 1. Նոր սոկետ ստեղծել
      $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
      if ($socket === false) {
        die("Սոկետ ստեղծելու ժամանակ սխալ է տեղի ունեցել: " . socket_strerror(socket_last_error()));
      }

      // 2. TCP կապ հաստատել ՀԴՄ սարքի հետ
      // $result = socket_connect($socket, $this->ip, $this->port);
      // if ($result === false) {

      //   die("ՀԴՄ սարքի հետ կապ հաստատելու ժամանակ սխալ է տեղի ունեցել: " . socket_strerror(socket_last_error($socket)));
      // }

      if (!@socket_connect($socket, $this->ip, $this->port)) {
          $errorCode = socket_last_error($socket);
          $errorMessage = socket_strerror($errorCode);
          socket_close($socket); // Закрыть сокет перед завершением
          // die("ՀԴՄ սարքի հետ կապ հաստատելու ժամանակ սխալ է տեղի ունեցել: [{$errorCode}] {$errorMessage}");

          return [
            'success' => false,
            'result' => [
                          'message' => "ՀԴՄ սարքի հետ կապ հաստատելու ժամանակ սխալ է տեղի ունեցել",
                          'error' => 1
                    ]
          ];
      }


      socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, ["sec" => 30, "usec" => 0]);  // Սպասելու ժամանակ 30 վայրկյան

      // $key = in_array($operationCode, $this->firstEncryptionKeys) ? $this->generateKey($this->hdmPassword) : base64_decode($this->loginKey);
      $key = $this->getKey($operationCode);

      if(!$key){
        return [
          'success' => false,
          'result' => 'logOut'
        ];
      }

      $enc_data = $this->encryptData($jsonBody, $key);

      $header = $this->createHeader($operationCode, strlen($enc_data));

      // Հարցման ամբողջական մարմին
      $request = $header . $enc_data;

      // 4. Ուղարկել հարցումը
      socket_write($socket, $request, strlen($request));


      // 5. Ստանալ պատասխան
      $res = socket_read($socket, 2048); // 2048 բայթ կարդալ


      // 6. Պատասխանն ապակոդավորել
      $responseJsone = substr($res, 11); // Հանենք գլխի 12 բայթը

      $responseDecrypt = $this->decryptData($responseJsone, $key);

      $response = json_decode($responseDecrypt, true); //

      $parseHeader = $this->parseHeader($res);

      if($parseHeader['data_length'] == 0){
        //  return $parseHeader['operationCode'];
        return [
          'success' => false,
          'result' => $parseHeader
        ];
      }

      // if (!in_array($operationCode, $this->firstEncryptionKeys)) {
      //   $this->loginKey = $response->key;
      // }


      // 7. Արտածել պատասխան
      // echo "Սարքից ստացված պատասխան HEX՝ ".bin2hex($response)."\n";

      // 8. Զանգը փակել
      socket_close($socket);

      return [
        'success' => true,
        'result' => $response
      ];

    } catch (\Throwable $th) {
      // throw $th;
      return [
        'success' => false,
        'result' => $th
      ];
    }

  }


  public function returnHdm(){

  }


  public function parseHeader($header)
  {
    // Убедимся, что длина заголовка соответствует ожидаемым 12 байтам
    // if (strlen($header) !== 11) {
    //     throw new Exception("Неверная длина заголовка");
    // }

    // Первые 6 байт - это константные значения, их просто пропустим
    $constant = substr($header, 0, 6);
    // dd($constant );
    // Проверяем, что они соответствуют ожидаемому значению
    // if (bin2hex($constant) !== "D580D4B4D5840007") {
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
