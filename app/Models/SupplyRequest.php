<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyRequest extends Model implements \Spatie\MediaLibrary\HasMedia
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;
    use \Spatie\MediaLibrary\HasMedia, \Spatie\MediaLibrary\InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'city_id',
        'status',
        'delivery_date',
        'awarded_provider_id',
        'accepted_at',
        'completed_at',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'accepted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_OPEN = 'open';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CLOSED = 'closed';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function responses()
    {
        return $this->hasMany(SupplyRequestResponse::class);
    }

    public function awardedProvider()
    {
        return $this->belongsTo(User::class, 'awarded_provider_id');
    }
}
