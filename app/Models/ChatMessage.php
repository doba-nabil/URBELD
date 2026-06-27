<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ChatMessage extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['chat_id', 'sender_id', 'message', 'is_read', 'read_at'];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
