<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyRequest extends Model implements \Spatie\MediaLibrary\HasMedia
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;
    use \Spatie\MediaLibrary\InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'title',
        'quantity',
        'category_id',
        'sub_category_id',
        'provider_id',
        'request_key',
        'description',
        'location',
        'latitude',
        'longitude',
        'voice_record',
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

    public function getRequestKeyAttribute($value)
    {
        if ($value) return $value;
        return 'SUP-' . ($this->created_at ? $this->created_at->format('Ymd') : date('Ymd')) . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
}
