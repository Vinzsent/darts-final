<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orWhereNull('user_id')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true]);
        return back();
    }

    public function markAllRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
        return back();
    }

    public function unreadCount()
    {
        $count = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();
        return response()->json(['count' => $count]);
    }
}
