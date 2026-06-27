<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ChatService
{
    /**
     * إنشاء محادثة جديدة أو إرجاع المحادثة الموجودة
     */
    public function getOrCreateChat(User $fromUser, User $toUser, $serviceRequestId = null)
    {
        // البحث عن محادثة موجودة بين المستخدمين
        $chat = Chat::where(function ($query) use ($fromUser, $toUser) {
            $query->where('from_user_id', $fromUser->id)
                ->where('to_user_id', $toUser->id);
        })->orWhere(function ($query) use ($fromUser, $toUser) {
            $query->where('from_user_id', $toUser->id)
                ->where('to_user_id', $fromUser->id);
        })->first();

        // إذا كانت المحادثة موجودة، تفعيلها وإرجاعها
        if ($chat) {
            if (!$chat->active) {
                $chat->update(['active' => true]);
            }
            return $chat;
        }

        // إنشاء محادثة جديدة
        return Chat::create([
            'from_user_id' => $fromUser->id,
            'to_user_id' => $toUser->id,
            'active' => true,
        ]);
    }

    /**
     * تفعيل محادثة موجودة
     */
    public function activateChat(Chat $chat)
    {
        $chat->update(['active' => true]);
        return $chat;
    }

    /**
     * إلغاء تفعيل محادثة
     */
    public function deactivateChat(Chat $chat)
    {
        $chat->update(['active' => false]);
        return $chat;
    }

    /**
     * الحصول على محادثة بالـ UUID
     */
    public function getByUuid($uuid)
    {
        return Chat::where('uuid', $uuid)->firstOrFail();
    }

    /**
     * حذف محادثة
     */
    public function delete($id)
    {
        $chat = Chat::findOrFail($id);
        $chat->messages()->delete();
        return $chat->delete();
    }
}
