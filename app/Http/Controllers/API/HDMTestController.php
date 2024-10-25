<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HDMTestController extends Controller
{


      public function test(){
        return response()->json(['message' => 'ok']);
      }


      // ==========================================
      public function index()
    {

        $host = '192.168.10.125';
        $port = 8080;
        $password = 'ReVZh4PJ';

        // Создаем TCP-соединение
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

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

  // function generateKey($password) {
  //   // Գեներացնում ենք 24-բայթանոց բանալի ՀԴՄ գաղտնաբառի հիման վրա
  //   $hash = hash('sha256', $password, true);
  //   return substr($hash, 0, 24); // Վերցնում ենք առաջին 24 բայթերը
  // }


  public function dll(){
    try {

      $host = '192.168.10.125';
      $port = 8080;
      $password = 'ReVZh4PJ';
      // Создание COM-объекта
      $FR = new \COM("HDMIntegrator.FR");

      // Настройка параметров ККМ
      $FR->IP = "192.168.10.125"; // IP-адрес ККМ
      $FR->Port = 8080; // Порт ККМ
      $FR->FRPassword = "ReVZh4PJ"; // Пароль ККМ
      $FR->OperatorID = 3; // ID оператора
      $FR->OperatorPassword = "3"; // Пароль оператора
      $FR->FRKey = "C1QOLqcqOch87VhsayNv4w=="; // Лицензия

      // Открытие нового чека продажи
      if (!$FR->OpenSaleDocument(2, "")) {
          // Если операция завершилась ошибкой, выводим код и описание ошибки
          throw new Exception($FR->ErrCode . " - " . $FR->ErrDescription);
      }

      // Добавление товара в чек
      if (!$FR->NewItem(1, 1, 10, 0, 0, 0, 0, "ticket", "0000022", "ticket", "91.02")) {
          throw new Exception($FR->ErrCode . " - " . $FR->ErrDescription);
      }

      // Печать чека
      if (!$FR->PrintDocument(10, 0, true, 0, 0)) {
          throw new Exception($FR->ErrCode . " - " . $FR->ErrDescription);
      }

      // Получение номера чека
      $fiscalData = $FR->FiscalData;
      $receiptNumber = $fiscalData->Rseq;

      echo "Чек успешно распечатан. Номер чека: " . $receiptNumber;

  } catch (Exception $e) {
      // Обработка ошибок
      echo "Ошибка: " . $e->getMessage();
  }
  }

  public function getCashiers(){

    try {
      // Создание COM-объекта для взаимодействия с библиотекой HDMIntegrator
      $fr = new \COM("HDMIntegrator.FR");

      // Установка IP-адреса и порта ККМ
      $fr->IP = "192.168.10.125";
      $fr->Port = 8080;

      // Установка пароля ККМ
      $fr->FRPassword = "ReVZh4PJ";  // Введите свой пароль ККМ

      // Вызов метода для получения списка операторов (кассиров)
      if ($fr->GetOperators()) {
          // Если метод вернул True, выведем список операторов
          $operators = $fr->FROperators;
          foreach ($operators as $operator) {
              echo "ID: " . $operator->ID . ", Имя: " . $operator->Name . "\n";
          }
      } else {
          // Если возникла ошибка, выводим код и описание ошибки
          echo "Ошибка: " . $fr->ErrCode . " - " . $fr->ErrDescription;
      }
    } catch (Exception $e) {
        echo "Ошибка создания COM объекта: " . $e->getMessage();
    }
  }


  public function connect(){
    try {
      // 1. Создаем COM-объект
      $fr = new \COM("HDMIntegrator.FR");

      // 2. Устанавливаем параметры подключения
      // Указываем IP-адрес и порт, по которому доступен ККМ
      $fr->IP = "192.168.10.125";  // Замените на ваш IP-адрес ККМ
      $fr->Port = 8080;          // Замените на ваш порт ККМ
      $fr->ConnectionReadTimeout = 90000;
      // Устанавливаем пароль ККМ для доступа
      $fr->FRPassword = "";  // Замените на ваш пароль ККМ

      // 3. Проверяем подключение
      // Используем метод ConnectionCheck() для проверки соединения
      if ($fr->ConnectionCheck()) {
          echo "Подключение к ККМ успешно!";
      } else {
          echo "Ошибка подключениReVZh4PJя: " . $fr->ErrCode . " - " . $fr->ErrDescription;
      }

    } catch (Exception $e) {
        echo "Ошибка создания COM объекта: " . $e->getMessage();
    }

  }


  public function new_index(){
    // dd(66);
    // $host = '192.168.10.125'; // ՀԴՄ IP հասցե
    // $port = 8080;          // ՀԴՄ պորտ
    // $timeout = 30;         // Սպասման ժամանակ

    // $socket = stream_socket_client("tcp://$host:$port", $errno, $errstr, $timeout);

    // if (!$socket) {
    //     die("Cannot connect to HDCM: $errstr ($errno)");
    // }

    // // Գլխագիր ու տվյալների հարցման բայթերը
    // $message = $this->createHeader('01', 2);

    // // Ուղարկում ենք բայթերի տեսքով
    // fwrite($socket, $message);

    // // Պատասխանի ստացում
    // stream_set_timeout($socket, 120);
    // $response = fread($socket, 1024);
    // echo bin2hex($response); // Հնարավոր է կոդավորված պատասխանի դեպում պետք լինի декодировать

    // // Կապի փակումը
    // fclose($socket);
    $host = '192.168.10.125';  // ՀԴՄ-ի IP հասցեն
    $port = 8080;  // ՀԴՄ սարքի համար նախատեսված պորտը

    $password = "ReVZh4PJ";  // ՀԴՄ-ի գաղտնաբառ
  $operationCode = 4;  // Կտրոնի տպում
  $sequenceNumber = 1;  // Հարցման հերթական համարը

  // Ապրանքների ցուցակ
  $items = [
      [
          'dep' => 1,
          'qty' => 2,
          'price' => 20,
          'productCode' => '001',
          'productName' => 'Coca Cola',
          'adgCode' => '0104',
          'unit' => 'litr'
      ]
  ];

  $this->sendFiscalRequest($host, $port, $password, $operationCode, $sequenceNumber, $items);
  }



  public function createHeader($operationCode, $sequenceNumber) {
    // Գլխագիրը պետք է լինի 12 բայթ
    $header = "";

    // Առաջին 6 բայթը կոնստանտ արժեքներ են ըստ փաստաթղթի
    $header .= hex2bin("D580D4B4D584");

    // 7-րդ և 8-րդ բայթեր - հարցման հերթական համարը (BigEndian)
    $header .= pack('n', $sequenceNumber);

    // 9-րդ բայթ - գործողության կոդը (օր.՝ կտրոնի տպում՝ 4)
    $header .= chr($operationCode);

    // Վերջին 3 բայթերը 0-ներով լցված են (պահուստային)
    $header .= hex2bin("000000");

    return $header;
  }
  function createHeader1($operationCode, $data_length) {
    // Գլխագիրը պետք է լինի 12 բայթ
    $header = "";

    // Առաջին 6 բայթը կոնստանտ արժեքներ են ըստ փաստաթղթի
    $header .= hex2bin("D580D4B4D5840007");

    // 9-րդ բայթ - գործողության կոդը (օր.՝ կտրոնի տպում՝ 4)
    $header .= chr($operationCode);
    $header .= hex2bin("00");

    $header .= chr(intval($data_length>>8));
    $header .= chr(intval($data_length & 0xFF));
    return $header;
  }

  public function generateKey($password) {
    // Գեներացնում ենք 24-բայթանոց բանալի ՀԴՄ գաղտնաբառի հիման վրա
    $hash = hash('sha256', $password, true);
    return substr($hash, 0, 24); // Վերցնում ենք առաջին 24 բայթերը
  }

  function sendFiscalRequest($host, $port, $password, $operationCode, $sequenceNumber, $items) {
    // 1. Ստեղծել կոդավորված գաղտնաբառը
    $passwordKey = $this->generatePasswordHash($password);

    // 2. Ստեղծել հարցման գլխագիրը
    $header = $this->createHeader($operationCode, $sequenceNumber);

    // 3. Ստեղծել հարցման JSON մարմինը
    $body = [
        'seq' => $sequenceNumber,
        'items' => $items,
        'paidAmount' => 1000,
        'mode' => 2
    ];
    $jsonBody = json_encode($body);

    // 4. Հարցումը կառուցել (գլխագիր + JSON մարմին)
    $fullQuery = $header . $jsonBody;

    // 5. Ուղարկել հարցումը TCP կապի միջոցով
    $socket = fsockopen($host, $port, $errno, $errstr, 30);
    if (!$socket) {
        die("Չի հաջողվել կապ հաստատել: $errstr ($errno)\n");
    }

    fwrite($socket, $fullQuery);

    // Ստանալ պատասխան
    $response = fgets($socket, 1024);
    fclose($socket);

    // Տպել պատասխանը
    echo "Սարքից ստացված պատասխան՝ $response\n";
  }



  public function generatePasswordHash($password) {
    // Գեներացնում ենք գաղտնաբառի SHA-256 հեշը
    $hash = hash('sha256', $password, true);  // true-ը ապահովում է raw binary արդյունք
    // Վերցնում ենք առաջին 24 բայթը
    $key = substr($hash, 0, 24);
    return $key;
  }



  public function printCopy(){
    $host = '192.168.10.125';  // ՀԴՄ-ի IP հասցեն
    $port = 8080;  // ՀԴՄ սարքի պորտը
    $password = "ReVZh4PJ";  // ՀԴՄ-ի գաղտնաբառ
    $sequenceNumber = 1133;  // Հարցման հերթական համարը

    // Կանչում ենք ֆունկցիան վերջին կտրոնի կրկնօրինակի տպման համար
    $response = $this->printLastReceiptCopy($host, $port, $password, $sequenceNumber);

    // Տպում ենք ֆունկցիայի վերադարձած արդյունքը
    dd($response);
  //   $host = '192.168.10.125';  // ՀԴՄ սարքի IP հասցեն
  // $port = 8080;  // ՀԴՄ սարքի պորտը
  // $operationCode = 5;  // Վերջին կտրոնի կրկնօրինակի տպում
  // $sequenceNumber = 1;  // Հարցման հերթական համարը

  // $body = [
  //     'seq' => $sequenceNumber,
  // ];
  // $jsonBody = json_encode($body);

  // // Ստեղծում ենք գլխագիր և ամբողջ հարցումը
  // $header = $this->createHeader($operationCode, $sequenceNumber);
  // $fullQuery = $header . $jsonBody;

  // echo "Հարցումը՝ " . bin2hex($fullQuery) . "\n";
  // // Սկսել TCP կապը
  // $socket = fsockopen($host, $port, $errno, $errstr, 30);
  // fwrite($socket, $fullQuery);
  // set_time_limit(300);
  // $response = fgets($socket, 1024);

  // // Տպել բինար պատասխանը
  // if ($response) {
  //     echo "Սարքից ստացված բինար պատասխան՝ " . bin2hex($response) . "\n";
  // } else {
  //     echo "Սարքից պատասխան չի ստացվել կամ կապը խզվել է\n";
  // }

  // fclose($socket);
  }
  public function loginCashier(){
    $host = '192.168.10.125';  // ՀԴՄ սարքի IP հասցեն
    $port = 8080;  // ՀԴՄ սարքի պորտը
    $password = "ReVZh4PJ";  // ՀԴՄ գաղտնաբառը
    $sequenceNumber = 3333;  // Հարցման հերթական համարը
    $operatorId = 3;  // Օպերատորի ID
    $operatorPassword = 3;  // Օպերատորի գաղտնաբառը

    // Կանչել օպերատորի մուտքի ֆունկցիան
    $response = $this->operatorLogin($host, $port, $password, $sequenceNumber, $operatorId, $operatorPassword);

    // Տպել ստացված պատասխանը
    print_r($response);

  }
  public function operatorLogin($host, $port, $password, $sequenceNumber, $operatorId, $operatorPassword) {
    // 1. Ստեղծել կոդավորված գաղտնաբառը
    $passwordKey = $this->generatePasswordHash($password);

    // 2. Գործողության կոդը օպերատորի մուտքի համար (ըստ ՀԴՄ փաստաթղթի)
    $operationCode = 1;  // Օրինակ՝ գործողության կոդը 2 է օպերատորի մուտքի համար
    $sequenceNumber = 1000;
    // 3. Ստեղծել հարցման 12-բայթ գլխագիրը
    $header = $this->createHeader($operationCode, $sequenceNumber);

    // 4. Ստեղծել հարցման JSON մարմինը
    $body = [
        'password' => 'ReVZh4PJ',
        // 'cashier' => $operatorId,  // Օպերատորի ID
        // 'pin' => $operatorPassword  // Օպերատորի գաղտնաբառը
    ];
    $jsonBody = json_encode($body);

    // 5. Հարցումը կառուցել
    $fullQuery = $header . $jsonBody;

    // 6. Ուղարկել հարցումը TCP կապի միջոցով
    $socket = fsockopen($host, $port, $errno, $errstr, 30);
    if (!$socket) {
        die("Չի հաջողվել կապ հաստատել: $errstr ($errno)\n");
    }

    stream_set_timeout($socket, 30);  // 30 վայրկյան

    // Ուղարկել հարցումը
    fwrite($socket, $fullQuery);
    echo "Հարցումը՝ " . bin2hex($fullQuery) . "\n";  // Տպել հարցումը

    // Ստանալ ՀԴՄ-ի պատասխանը
    $response = fgets($socket, 1024);
    fclose($socket);

    if ($response === false) {
        echo "Պատասխան չի ստացվել կամ կապը խզվել է\n";
        return null;  // Վերադարձնել null, եթե պատասխանը չկա
    }

    // Տպել ստացված պատասխանն
    echo "Սարքից ստացված պատասխան՝ '$response'\n";
  dd(substr($response,11));
    // Վերծանել JSON պատասխանը
    $decodedResponse = json_decode($response, true);
    if ($decodedResponse === null) {
        echo "JSON վերծանման սխալ՝ " . json_last_error_msg() . "\n";  // Տպել JSON սխալի հաղորդագրությունը
        return null;
    }

    // Տպել հաջողված պատասխան
    return $decodedResponse;
  }
  // ===============================
  // public function operatorLogin1($host, $port, $password, $sequenceNumber, $operatorId, $operatorPassword) {
  //     $passwordKey = $this->generatePasswordHash($password);

  //      // 1. Ստեղծել սոքեթ (TCP պրոտոկոլով)
  //      $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
  //      if ($socket === false) {
  //          die("Չհաջողվեց սոքեթ ստեղծել: " . socket_strerror(socket_last_error()) . "\n");
  //      }

  //      // 2. Սարքի հետ կապ հաստատել
  //      $result = socket_connect($socket, $host, $port);
  //      if ($result === false) {
  //          die("Չհաջողվեց կապ հաստատել ՀԴՄ-ի հետ: " . socket_strerror(socket_last_error($socket)) . "\n");
  //      }

  //      socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, ["sec"=>60, "usec"=>0]);  // Սպասելու ժամանակ 30 վայրկյան

  //      // 3. Գործողության կոդը օպերատորի մուտքի համար
  //      $operationCode = 2;  // Օրինակ՝ 2

  //      // 4. Ստեղծել հարցման 12-բայթ գլխագիրը
  //      $header = $this->createHeader($operationCode, $sequenceNumber);

  //      // 5. Ստեղծել հարցման JSON մարմինը
  //      $body = [
  //          'password' => $passwordKey,
  //          'cashier' => $operatorId,
  //          'pin' => $operatorPassword
  //      ];
  //      $jsonBody = json_encode($body);

  //      // 6. Հարցումը կառուցել
  //      $fullQuery = $header . $jsonBody;

  //      // 7. Ուղարկել հարցումը ՀԴՄ սարքին
  //      $send = socket_write($socket, $fullQuery, strlen($fullQuery));
  //      if ($send === false) {
  //          die("Չհաջողվեց հարցումը ուղարկել: " . socket_strerror(socket_last_error($socket)) . "\n");
  //      }

  //      echo "Հարցումը՝ " . bin2hex($fullQuery) . "\n";

  //      // 8. Ստանալ ՀԴՄ-ի պատասխանը
  //      $response = socket_read($socket, 1024);
  //      if ($response === false) {
  //          die("Չհաջողվեց պատասխան ստանալ: " . socket_strerror(socket_last_error($socket)) . "\n");
  //      }

  //      // 9. Տպել ստացված պատասխանն
  //      echo "Սարքից ստացված պատասխան՝ '$response'\n";

  //      // 10. Վերծանել JSON պատասխանը
  //      $decodedResponse = json_decode($response, true);
  //      if ($decodedResponse === null) {
  //          echo "JSON վերծանման սխալ՝ " . json_last_error_msg() . "\n";
  //          socket_close($socket);
  //          return null;
  //      }

  //      // 11. Սոքեթի փակումը
  //      socket_close($socket);

  //      // Վերադարձնել ստացված պատասխանը
  //      return $decodedResponse;
  // }
  function operatorLogin1($host, $port, $password, $operatorId, $operatorPassword)
  {
      $passwordKey = $this->generatePasswordHash($password);

      // 5. Ստեղծել հարցման JSON մարմինը
      $body = [
           'password' => 'ReVZh4PJ'
          //  'cashier' => $operatorId,
          //  'pin' => $operatorPassword
      ];
      $jsonBody = json_encode($body);

      $enc_data = openssl_encrypt($jsonBody,'DES-ECB',$this->generatePasswordHash($password),OPENSSL_RAW_DATA  | OPENSSL_NO_PADDING);

      // PKCS7 padding
    //   $l = strlen($jsonBody);
    //   $pad = 8-$l+8*intval($l/8);
    // for ($i = 0; $i<$pad ; $i++)
    //     $enc_data .= chr($pad);

  //       dd($enc_data);
  // "
  // \x08\x08\x08\x08\x08\x08\x08\x08
  // "
  // "
  // \x08\x08\x08\x08\x08\x08\x08\x08
  // "
    $blockSize = 8;
    $padLength = $blockSize - (strlen($jsonBody) % $blockSize);
    $enc_data .= str_repeat(chr($padLength), $padLength);
        // dd($enc_data);
      // 4. Ստեղծել հարցման 12-բայթ գլխագիրը
          // 3. Գործողության կոդը օպերատորի մուտքի համար
       $operationCode = 01;  // Օրինակ՝ 2
      $header = $this->createHeader1($operationCode, strlen($enc_data));
      // 6. Հարցումը կառուցել
      $fullQuery = $header . $enc_data;


      // 1. Ստեղծել սոքեթ (TCP պրոտոկոլով)
       $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
       if ($socket === false) {
           die("Չհաջողվեց սոքեթ ստեղծել: " . socket_strerror(socket_last_error()) . "\n");
       }

       // 2. Սարքի հետ կապ հաստատել
       $result = socket_connect($socket, $host, $port);
       if ($result === false) {
           die("Չհաջողվեց կապ հաստատել ՀԴՄ-ի հետ: " . socket_strerror(socket_last_error($socket)) . "\n");
       }

       socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, ["sec"=>30, "usec"=>0]);  // Սպասելու ժամանակ 30 վայրկյան


       // 7. Ուղարկել հարցումը ՀԴՄ սարքին
       $send = socket_write($socket, $fullQuery, strlen($fullQuery));
       if ($send === false) {
           die("Չհաջողվեց հարցումը ուղարկել: " . socket_strerror(socket_last_error($socket)) . "\n");
       }

       echo "Հարցումը՝ " . bin2hex($fullQuery) . "\n";

       // 8.response Ստանալ ՀԴՄ-ի պատասխանը
       $response = socket_read($socket, 1024);
       if ($response === false) {
           die("Չհաջողվեց պատասխան ստանալ: " . socket_strerror(socket_last_error($socket)) . "\n");
       }

       // 9. Տպել ստացված պատասխանն
       echo "Սարքից ստացված պատասխան HEX՝ ".bin2hex($response)."\n";

      $enc_responce  = substr($response,10);
      dd($enc_responce);
      $resp_json = openssl_decrypt($response,'DES-ECB',$this->generatePasswordHash($password),OPENSSL_RAW_DATA);
  dd($resp_json);
       // 10. Վերծանել JSON պատասխանը
       $decodedResponse = json_decode($resp_json, true);
       if ($decodedResponse === null) {
           echo "JSON վերծանման սխալ՝ " . json_last_error_msg() . "\n";
           socket_close($socket);
           return null;
       }

       // 11. Սոքեթի փակումը
       socket_close($socket);

       // Վերադարձնել ստացված պատասխանը
       return $decodedResponse;
  }
  public function cashiers(){
    $host = '192.168.10.125';  // ՀԴՄ սարքի IP հասցեն
    $port = 8080;  // ՀԴՄ սարքի պորտը
    $password = "ReVZh4PJ";  // ՀԴՄ գաղտնաբառը
    $sequenceNumber = 3333;  // Հարցման հերթական համարը
    $operatorId = 3;  // Օպերատորի ID
    $operatorPassword = 3;  // Օպերատորի գաղտնաբառը

    // Կանչել օպերատորի մուտքի ֆունկցիան
    $response = $this->operatorLogin1($host, $port, $password, $operatorId, $operatorPassword);

    // Տպել ստացված պատասխանը
    print_r($response);

  }


}
