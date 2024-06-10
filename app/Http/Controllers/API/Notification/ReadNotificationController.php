<?php

namespace App\Http\Controllers\API\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;

class ReadNotificationController extends Controller
{
    public function __invoke($notificationId){

      $user = auth('api')->user();
      // dd($user->notifications());

      $user->notifications()->where('id', $notificationId)->update(['read_at' => now()]);

      return redirect()->route('unreadNotification');
    }
}
