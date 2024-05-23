<?php

namespace App\Http\Controllers\API\Notification;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Notification\NotificationResource;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnreadNotificationController extends BaseController
{
    public function __invoke(){

        $notifications = DB::table('notifications')->orderBy('created_at', 'desc')->get();

        return $this->sendResponse(NotificationResource::collection($notifications), 'success', ['unread_notification_count' => $notifications->count()]);
    }
}
