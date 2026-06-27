<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationSettingsController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        $user->update([
            'receive_email_notifications' => $request->has('receive_email_notifications'),
            'receive_push_notifications' => $request->has('receive_push_notifications'),
        ]);

        return back()->with('success', __('admin.notification_settings_updated'));
    }
}
