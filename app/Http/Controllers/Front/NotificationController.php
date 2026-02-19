<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        Auth::user()->notifications()
            ->where('read_at', '<', now()->subHours(24))
            ->delete();
        if ($notification->data['type'] == 'order_limit') {
            return redirect()->route('user.orders.show', $notification->data['order_id']);
        } elseif ($notification->data['type'] == 'support_ticket') {
            return redirect()->route('user.helpline');
        }

        return back();
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }
}
