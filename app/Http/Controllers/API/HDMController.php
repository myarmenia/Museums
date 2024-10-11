<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HDMController extends Controller
{
  public function __invoke()
  {

      $host = '192.168.10.126';
      $port = 8080;
      $password = 'ReVZh4PJ';

      // Создаем TCP-соединение
      $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

      if ($socket === false) {
        dd(66);
        echo "Ошибка создания сокета: " . socket_strerror(socket_last_error()) . "\n";
      } else {

        $result = socket_connect($socket, $host, $port);

        if ($result === false) {
          dd(22);
          echo "Ошибка подключения: " . socket_strerror(socket_last_error($socket)) . "\n";

        } else {
          // $command = "COMMAND_HDM";
        // $command = json_encode([
        //   'password' => $password,
        //   // 'deviceId' => '123456'
        // ]);
        $command = "GET_STATUS";
          socket_write($socket, $command, strlen($command));

          // Чтение ответа
          $response = socket_read($socket, 1024);
          echo "Ответ от устройства: $response\n";
        }
        dd(44);
        socket_close($socket);
      }
dd('finish');
  }
}
