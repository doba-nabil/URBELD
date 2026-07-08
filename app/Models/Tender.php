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
    ];

    protected $casts = [
        'qualification_requirements' => 'array',
        'ends_at'                    => 'datetime',
        'is_urgent'                  => 'boolean',
        'budget'                     => 'decimal:2',
    ];

    // حالات المناقصة
    const STATUS_PENDING_REVIEW = 'pending_review';
    const STATUS_ACTIVE         = 'active';
    const STATUS_CLOSED         = 'closed';
    const STATUS_REJECTED       = 'rejected';

    // ========================
    // Relationships
    // ========================

    /** صاحب المناقصة */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** التصنيف الرئيسي */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /** التخصص (التصنيف الفرعي) */
    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }

    /** المدينة */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /** التقديمات */
    public function applications()
    {
        return $this->hasMany(TenderApplication::class);
    }

    /** المدفوعات الفردية */
    public function payPerUse()
    {
        return $this->hasMany(TenderPayPerUse::class);
    }

    /** المناقصات المحفوظة */
    public function savedByUsers()
    {
        return $this->hasMany(SavedTender::class);
    }

    // ========================
    // Media Collections
    // ========================

    public function registerMediaCollections(): void
    {
        // ملفات المناقصة - كل ملف له title في custom_properties
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

    /** هل المناقصة منتهية الوقت؟ */
    public function isExpired(): bool
    {
        return $this->ends_at && $this->ends_at->isPast();
    }

    /** هل يمكن التقديم عليها؟ */
    public function canApply(): bool
    {
        return $this->status === self::STATUS_ACTIVE && !$this->isExpired();
    }

    /** الوقت المتبقي (ثوانٍ) */
    public function getRemainingSecondsAttribute(): int
    {
        if (!$this->ends_at || $this->isExpired()) {
            return 0;
        }
        return (int) now()->diffInSeconds($this->ends_at, false);
    }

    /** عدد التقديمات */
    public function getApplicationsCountAttribute(): int
    {
        return $this->applications()->count();
    }
}
