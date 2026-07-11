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
        'tender_id',
        'supply_request_id',
        'rater_id',
        'rated_id',
        'rating',
        'comment',
    ];
    protected $casts = [
        'rating' => 'integer',
    ];
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }
    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }
    public function supplyRequest()
    {
        return $this->belongsTo(SupplyRequest::class);
    }
    public function rater()
    {
        return $this->belongsTo(User::class, 'rater_id');
    }
    public function rated()
    {
        return $this->belongsTo(User::class, 'rated_id');
    }
    public static function isValidRating($rating): bool
    {
        return is_numeric($rating) && $rating >= 1 && $rating <= 5;
    }
    public function scopeForUser($query, $userId)
    {
        return $query->where('rated_id', $userId);
    }
    public function scopeByUser($query, $userId)
    {
        return $query->where('rater_id', $userId);
    }
    public static function averageRatingForUser($userId): float
    {
        return static::forUser($userId)->avg('rating') ?? 0;
    }
}
