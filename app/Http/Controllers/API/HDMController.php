<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class HDMController extends Controller
{
  public function index()
  {

      $host = '192.168.10.125';
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
    $fr->FRPassword = "ReVZh4PJ";  // Замените на ваш пароль ККМ

    // 3. Проверяем подключение
    // Используем метод ConnectionCheck() для проверки соединения
    if ($fr->ConnectionCheck()) {
        echo "Подключение к ККМ успешно!";
    } else {
        echo "Ошибка подключения: " . $fr->ErrCode . " - " . $fr->ErrDescription;
    }

  } catch (Exception $e) {
      echo "Ошибка создания COM объекта: " . $e->getMessage();
  }

}

}
