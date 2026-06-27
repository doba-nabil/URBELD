<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Rating extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'service_request_id',
        'rater_id',
        'rated_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * الطلب
     */
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    /**
     * من قام بالتقييم
     */
    public function rater()
    {
        return $this->belongsTo(User::class, 'rater_id');
    }

    /**
     * من تم تقييمه
     */
    public function rated()
    {
        return $this->belongsTo(User::class, 'rated_id');
    }

    /**
     * التحقق من صحة التقييم (1-5)
     */
    public static function isValidRating($rating): bool
    {
        return is_numeric($rating) && $rating >= 1 && $rating <= 5;
    }

    /**
     * Scope حسب المستخدم الذي تم تقييمه
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('rated_id', $userId);
    }

    /**
     * Scope حسب المستخدم الذي قام بالتقييم
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('rater_id', $userId);
    }

    /**
     * حساب متوسط التقييمات لمستخدم
     */
    public static function averageRatingForUser($userId): float
    {
        return static::forUser($userId)->avg('rating') ?? 0;
    }
}
