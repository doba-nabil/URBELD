<?php
namespace App\Services;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class ChatService
{
    public function getOrCreateChat(User $fromUser, User $toUser, $serviceRequestId = null)
    {
        $chat = Chat::where(function ($query) use ($fromUser, $toUser) {
            $query->where('from_user_id', $fromUser->id)
                ->where('to_user_id', $toUser->id);
        })->orWhere(function ($query) use ($fromUser, $toUser) {
            $query->where('from_user_id', $toUser->id)
                ->where('to_user_id', $fromUser->id);
        })->first();
        if ($chat) {
            if (!$chat->active) {
                $chat->update(['active' => true]);
            }
            return $chat;
        }
        return Chat::create([
            'from_user_id' => $fromUser->id,
            'to_user_id' => $toUser->id,
            'active' => true,
        ]);
    }
    public function activateChat(Chat $chat)
    {
        $chat->update(['active' => true]);
        return $chat;
    }
    public function deactivateChat(Chat $chat)
    {
        $chat->update(['active' => false]);
        return $chat;
    }
    public function getByUuid($uuid)
    {
        return Chat::where('uuid', $uuid)->firstOrFail();
    }
    public function delete($id)
    {
        $chat = Chat::findOrFail($id);
        $chat->messages()->delete();
        return $chat->delete();
    }
}
