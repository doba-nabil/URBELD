<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display the specified chat.
     */
    public function show(Chat $chat)
    {
        // Authorize: Ensure the current user is a participant in this chat
        if (!$chat->participants()->where('users.id', Auth::id())->exists()) {
            abort(403, 'Unauthorized access to this chat.');
        }

        // Mark unread messages as read
        $chat->messages()
            ->where('sender_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Mark related database notifications as read
        Auth::user()->notifications()
            ->where('is_read', false)
            ->where(function($query) use ($chat) {
                $query->where('data->chat_id', $chat->id)
                      ->orWhere('link', 'like', '%' . route('dashboard.chat.show', $chat->id) . '%');
            })
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        // Also update pivot last_read_at
        $chat->participants()->updateExistingPivot(Auth::id(), ['last_read_at' => now()]);

        $chat->load(['messages.user', 'messages.media', 'participants', 'serviceRequest']);
        
        $otherUser = $chat->participants->where('id', '!=', Auth::id())->first();

        return view('website.chat.show', compact('chat', 'otherUser'));
    }

    /**
     * Store a newly created chat message.
     */
    public function sendMessage(Request $request, Chat $chat)
    {
        if (!$chat->participants()->where('users.id', Auth::id())->exists()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required_without:attachment|nullable|string',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        $message = $chat->messages()->create([
            'sender_id' => Auth::id(),
            'message' => $request->message ?? '',
        ]);

        if ($request->hasFile('attachment')) {
            $message->addMediaFromRequest('attachment')->toMediaCollection('chat_attachments');
        }

        // Notify other participants
        $otherParticipants = $chat->participants()->where('users.id', '!=', Auth::id())->get();
        \Illuminate\Support\Facades\Notification::send($otherParticipants, new \App\Notifications\NewChatMessageNotification($chat, Auth::user(), $message->message));

        if ($request->ajax()) {
            $message->load('user');
            return response()->json([
                'success' => true,
                'message' => $message,
                'avatar' => $message->user->getFirstMediaUrl('personal_photo') ?: $message->user->getFirstMediaUrl('users') ?: asset('website/assets/img/logo.png'),
                'time' => $message->created_at->format('h:i A'),
                'attachment_url' => $message->getFirstMediaUrl('chat_attachments')
            ]);
        }

        return back();
    }

    /**
     * Get new messages for the chat.
     */
    public function getMessages(Request $request, Chat $chat)
    {
        if (!$chat->participants()->where('users.id', Auth::id())->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $lastMessageId = $request->query('last_id');

        $messages = $chat->messages()
            ->with(['user', 'media'])
            ->when($lastMessageId, function ($query) use ($lastMessageId) {
                return $query->where('id', '>', $lastMessageId);
            })
            ->where('sender_id', '!=', Auth::id())
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_id' => $message->sender_id,
                    'time' => $message->created_at->format('h:i A'),
                    'avatar' => $message->user->getFirstMediaUrl('personal_photo') ?: $message->user->getFirstMediaUrl('users') ?: asset('website/assets/img/logo.png'),
                    'attachment_url' => $message->getFirstMediaUrl('chat_attachments'),
                ];
            });

        // Mark these messages as read
        if ($messages->count() > 0) {
            $chat->messages()
                ->whereIn('id', $messages->pluck('id'))
                ->update(['read_at' => now()]);
            
            $chat->participants()->updateExistingPivot(Auth::id(), ['last_read_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }
}
