<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Chat;
use App\Models\User;

class NewChatMessageNotification extends Notification
{
    use Queueable;

    public $chat;
    public $sender;
    public $messageText;

    public function __construct(Chat $chat, User $sender, $messageText)
    {
        $this->chat = $chat;
        $this->sender = $sender;
        $this->messageText = $messageText;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('admin.email_new_chat_message_title', ['name' => $this->sender->name]) ?? 'رسالة دردشة جديدة من ' . $this->sender->name)
            ->view('emails.new_chat_message', [
                'user' => $notifiable,
                'sender' => $this->sender,
                'chat' => $this->chat,
                'messageText' => $this->messageText,
                'chatUrl' => route('dashboard.chat.show', $this->chat->id),
                'logoUrl' => config('settings.logo') ? asset(config('settings.logo')) : null,
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'new_chat_message',
            'title' => __('admin.new_chat_message_notif_title') ?? 'رسالة جديدة في الدردشة',
            'message' => __('admin.new_chat_message_notif_body', ['name' => $this->sender->name]) ?? "لديك رسالة جديدة من {$this->sender->name}",
            'link' => route('dashboard.chat.show', $this->chat->id),
            'data' => [
                'chat_id' => $this->chat->id,
                'sender_id' => $this->sender->id
            ]
        ];
    }
}
