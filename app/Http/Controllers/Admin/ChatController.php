<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ChatDataTable;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(ChatDataTable $dataTable)
    {
        return $dataTable->render('dashboard.chats.index');
    }

    public function getMessages($uuid)
    {
        try {
            $chat = Chat::where('uuid', $uuid)
                ->with(['messages.sender', 'fromUser', 'toUser', 'firstMessage'])
                ->firstOrFail();

            // Set a transient from_user_id based on the first message sender for the JS logic
            if ($chat->firstMessage) {
                $chat->from_user_id = $chat->firstMessage->sender_id;
            }

            return response()->json([
                'status' => 'success',
                'chat' => $chat,
                'messages' => $chat->messages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('admin.error_fetching_messages')
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $chat = Chat::findOrFail($id);
            // Delete all messages first
            $chat->messages()->delete();
            // Delete the chat
            $chat->delete();

            return response()->json([
                'status' => 'success',
                'message' => __('admin.delete_success')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('admin.delete_error')
            ], 500);
        }
    }
}

