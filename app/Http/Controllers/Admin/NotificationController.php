<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\NotificationDataTable;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(NotificationDataTable $dataTable)
    {
        return $dataTable->render('dashboard.notifications.index');
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->guard('admin')->id())
            ->findOrFail($id);
        
        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
        
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->guard('admin')->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        
        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        $count = Notification::where('user_id', auth()->guard('admin')->id())
            ->where('is_read', false)
            ->count();
        
        return response()->json(['count' => $count]);
    }

    public function destroy($id)
    {
        $notification = Notification::where('user_id', auth()->guard('admin')->id())
            ->findOrFail($id);
        
        $notification->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => __('admin.deleted_successfully')
        ]);
    }
}

