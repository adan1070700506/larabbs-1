<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\TransFormers\NotificationTransFormer;

class NotificationsController extends Controller
{
    public function index()
    {
        // 获取登录用户的所有通知
        $notifications = $this->user->notifications()->paginate(20);
        // 标记为已读，未读数量清零
        $this->user->markAsRead();
        return $this->response->paginator($notifications, new NotificationTransFormer());
    }

    public function stats()
    {
        return $this->response->array([
            'unread_count' => $this->user()->notification_count,
        ]);
    }
}
