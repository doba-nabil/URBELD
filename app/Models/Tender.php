<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class Tender extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;
    protected $fillable = [
        'user_id',
        'request_key',
        'title',
        'description',
        'project_type',
        'category_id',
        'sub_category_id',
        'city_id',
        'budget',
        'qualification_requirements',
        'status',
        'ends_at',
        'is_urgent',
        'admin_notes',
        'awarded_provider_id',
        'accepted_at',
        'completed_at',
    ];
    protected $casts = [
        'qualification_requirements' => 'array',
        'ends_at'                    => 'datetime',
        'is_urgent'                  => 'boolean',
        'budget'                     => 'decimal:2',
        'accepted_at'                => 'datetime',
        'completed_at'               => 'datetime',
    ];
    const STATUS_PENDING_REVIEW = 'pending_review';
    const STATUS_ACTIVE         = 'active';
    const STATUS_CLOSED         = 'closed';
    const STATUS_REJECTED       = 'rejected';
    const STATUS_IN_PROGRESS    = 'in_progress';
    const STATUS_COMPLETED      = 'completed';
    // ========================
    // Relationships
    // ========================
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function applications()
    {
        return $this->hasMany(TenderApplication::class);
    }
    public function payPerUse()
    {
        return $this->hasMany(TenderPayPerUse::class);
    }
    public function savedByUsers()
    {
        return $this->hasMany(SavedTender::class);
    }
    // ========================
    // Media Collections
    // ========================
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('tender_files')
             ->acceptsMimeTypes([
                 'application/pdf',
                 'application/zip',
                 'image/jpeg',
                 'image/png',
                 'image/webp',
                 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
             ]);
    }
    // ========================
    // Scopes
    // ========================
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                     ->where(function ($q) {
                         $q->whereNull('ends_at')
                           ->orWhere('ends_at', '>', now());
                     });
    }
    public function scopePendingReview($query)
    {
        return $query->where('status', self::STATUS_PENDING_REVIEW);
    }
    // ========================
    // Helpers
    // ========================
    public function isExpired(): bool
    {
        return $this->ends_at && $this->ends_at->isPast();
    }
    public function canApply(): bool
    {
        return $this->status === self::STATUS_ACTIVE && !$this->isExpired();
    }
    public function getRemainingSecondsAttribute(): int
    {
        if (!$this->ends_at || $this->isExpired()) {
            return 0;
        }
        return (int) now()->diffInSeconds($this->ends_at, false);
    }
    public function getApplicationsCountAttribute(): int
    {
        return $this->applications()->count();
    }

    public function getRequestKeyAttribute($value)
    {
        if ($value) return $value;
        return 'TEN-' . ($this->created_at ? $this->created_at->format('Ymd') : date('Ymd')) . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
}
