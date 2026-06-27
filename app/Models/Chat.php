<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Chat extends Model
{
    protected $fillable = ['uuid', 'from_user_id', 'to_user_id', 'service_request_id', 'active'];

    protected static function booted()
    {
        static::creating(function ($chat) {
            $chat->uuid = (string) Str::uuid();
        });
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at', 'asc');
    }

    public function lastMessage()
    {
        return $this->hasOne(ChatMessage::class)->latestOfMany();
    }

    public function firstMessage()
    {
        return $this->hasOne(ChatMessage::class)->oldestOfMany();
    }

    public function unreadMessages()
    {
        return $this->hasMany(ChatMessage::class)
            ->where('is_read', false)
            ->where('sender_id', '!=', auth()->id());
    }
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function ToUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'chat_participants')
                    ->withPivot('last_read_at')
                    ->withTimestamps();
    }
}
