<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends BaseController
{
    
    public function index()
    {
        $notifications = $notification = Notification::with('notifiable')->
        whereNotifiableId(auth()->user()->id)->get();

        return $this->sendResponse($notifications, __('Notifications fetched successfully.'));
    }
}
