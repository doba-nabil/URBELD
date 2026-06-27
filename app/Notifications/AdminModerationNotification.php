<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AdminModerationNotification extends Notification
{
    use Queueable;

    public $model;
    public $type; // 'request' or 'response'

    public function __construct($model, $type = 'request')
    {
        $this->model = $model;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        if ($this->type === 'request') {
            return [
                'type' => 'request_moderation',
                'title' => __('admin.request_moderation_title'),
                'message' => __('admin.request_moderation_body', [
                    'name' => $this->model->user->name ?? ($notifiable->getLocale() == 'ar' ? 'مستخدم' : 'User'),
                    'id' => $this->model->id
                ]),
                'link' => url('/admin-panel/service-requests/' . $this->model->id),
                'id' => $this->model->id
            ];
        } else {
            return [
                'type' => 'response_moderation',
                'title' => __('admin.response_moderation_title'),
                'message' => __('admin.response_moderation_body', [
                    'name' => $this->model->user->name ?? ($notifiable->getLocale() == 'ar' ? 'مقدم خدمة' : 'Provider'),
                    'id' => $this->model->service_request_id
                ]),
                'link' => url('/admin-panel/service-requests/' . $this->model->service_request_id),
                'id' => $this->model->id
            ];
        }
    }
}
