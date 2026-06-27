<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'notifiable_type',
        'notifiable_id',
        'type',
        'title',
        'message',
        'data',
        'link',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($notification) {
            if (empty($notification->id)) {
                $notification->id = (string) \Illuminate\Support\Str::uuid();
            }

            // Auto-sync polymorphic columns if user_id is provided via custom service
            if ($notification->user_id && empty($notification->notifiable_id)) {
                $notification->notifiable_id = $notification->user_id;
                $notification->notifiable_type = 'App\\Models\\User';
            }
            
            // Auto-sync user_id if polymorphic columns are provided via Laravel Notifiable
            // Modified: only auto-sync if we aren't explicitly trying to keep it null for system-wide dashboard notifications
            if (empty($notification->user_id) && !empty($notification->notifiable_id) && $notification->notifiable_type === 'App\\Models\\User') {
                // If the notifiable is a User, we usually sync it. 
                // However, we'll allow an escape hatch if needed, but for now we'll keep it simple:
                // If it's sent via Laravel notify(), it will have notifiable_id.
                $notification->user_id = $notification->notifiable_id;
            }

            // Sync title, message, and link from data array if empty (important for Laravel Database Channel)
            if (is_array($notification->data)) {
                if (empty($notification->title) && isset($notification->data['title'])) {
                    $notification->title = $notification->data['title'];
                }
                if (empty($notification->message)) {
                    $notification->message = $notification->data['body'] ?? ($notification->data['message'] ?? '');
                }
                if (empty($notification->link)) {
                    $notification->link = $notification->data['url'] ?? ($notification->data['link'] ?? '#');
                }
            }
        });
    }
}

