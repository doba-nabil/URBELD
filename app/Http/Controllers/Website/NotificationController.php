<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->where('is_read', false)->paginate(20);
        return view('website.notifications.index', compact('notifications'));
    }

    public function getLatest()
    {
        $notifications = Auth::user()->notifications()
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $this->getNotificationTitle($notification),
                    'body' => $this->getNotificationBody($notification),
                    'link' => $this->getNotificationLink($notification),
                    'is_read' => $notification->is_read,
                    'time' => $notification->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Auth::user()->unreadNotificationsCount(),
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    public function markAllAsRead(Request $request)
    {
        Auth::user()->notifications()->where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    private function getNotificationTitle($notification)
    {
        if ($notification->title) {
            return $notification->title;
        }

        if ($notification->data && isset($notification->data['title'])) {
            return $notification->data['title'];
        }

        return __('admin.new_notification');
    }

    private function getNotificationBody($notification)
    {
        if ($notification->message) {
            return $notification->message;
        }

        if ($notification->data && isset($notification->data['body'])) {
            return $notification->data['body'];
        }

        return '';
    }

    private function getNotificationLink($notification)
    {
        if ($notification->link) {
            return $notification->link;
        }

        if ($notification->data && isset($notification->data['url'])) {
            return $notification->data['url'];
        }

        return '#';
    }
}
