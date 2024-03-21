<?php

namespace App\Http\Controllers\API\Notification;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Notification\NotificationResource;
use Illuminate\Http\Request;

class UnreadNotificationController extends BaseController
{
    public function __invoke(){
      $user = auth('api')->user();
      $notification = $user->unreadNotifications;
      $unread_notification_count = $user->unreadNotifications->count();


      return $this->sendResponse(NotificationResource::collection($notification),'success',['unread_notification_count'=>$unread_notification_count]);
    }
}
