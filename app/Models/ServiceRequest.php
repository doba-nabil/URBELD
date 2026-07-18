<?php
namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\ServiceRequestObserver;
#[ObservedBy([ServiceRequestObserver::class])]
class ServiceRequest extends Model implements HasMedia, Auditable
{
    use HasFactory, AuditableTrait, InteractsWithMedia, SoftDeletes;
    protected $fillable = [
        'user_id',
        'category_id',
        'service_type',
        'status',
        'location',
        'latitude',
        'longitude',
        'description',
        'provider_id',
        'sub_category_id',
        'dynamic_data',
        'awarded_provider_id',
        'inspection_date',
        'response_deadline',
        'accepted_at',
        'completed_at',
        'city_id',
        'neighborhood',
        'request_key',
        'voice_record',
        'service_id',
        'is_consultation',
        'attachment_link',
    ];
    protected $casts = [
        'dynamic_data' => 'array',
        'inspection_date' => 'datetime',
        'response_deadline' => 'datetime',
        'accepted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_PENDING = 'pending';
    const STATUS_PROVIDER_ACCEPTED = 'provider_accepted';
    const STATUS_SEEKER_CONFIRMED = 'seeker_confirmed_provider';
    const STATUS_INSPECTION_SCHEDULED = 'inspection_scheduled';
    const STATUS_INSPECTION_DONE = 'inspection_done';
    const STATUS_WORK_COMPLETED = 'work_completed';
    const STATUS_REJECTED_BY_USER = 'rejected_by_user';
    const STATUS_COMPLETED = 'completed';
    const STATUS_TIME_EXPIRED = 'time_expired';
    const STATUS_CANCELLED = 'cancelled';
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
    public function awardedProvider()
    {
        return $this->belongsTo(User::class, 'awarded_provider_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }
    public function activityType()
    {
        return $this->belongsTo(ActivityType::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function responses()
    {
        return $this->hasMany(ServiceRequestResponse::class);
    }
    public function acceptedResponse()
    {
        return $this->hasOne(ServiceRequestResponse::class)
            ->where('status', ServiceRequestResponse::STATUS_ACCEPTED);
    }
    public function inspections()
    {
        return $this->hasMany(ServiceRequestInspection::class);
    }
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
    public function seekerRating()
    {
        return $this->hasOne(Rating::class)->where('rater_id', $this->user_id);
    }
    public function providerRating()
    {
        return $this->hasOne(Rating::class)->where('rater_id', $this->awarded_provider_id);
    }
    public function chat()
    {
        return $this->hasOne(Chat::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    /**
     * Media Collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('blueprints')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf']);
        $this->addMediaCollection('site_photos')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }
    public function isTimeExpired(): bool
    {
        if (!$this->response_deadline) {
            return false;
        }
        return now()->isAfter($this->response_deadline);
    }
    public function canRespond(): bool
    {
        return $this->status === self::STATUS_PENDING
            && !$this->isTimeExpired();
    }
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [self::STATUS_COMPLETED, self::STATUS_CANCELLED]);
    }
    public function scopeExpired($query)
    {
        return $query->where('response_deadline', '<', now())
            ->where('status', self::STATUS_PENDING);
    }
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function getRequestKeyAttribute($value)
    {
        if ($value) return $value;
        return 'REQ-' . ($this->created_at ? $this->created_at->format('Ymd') : date('Ymd')) . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
}
