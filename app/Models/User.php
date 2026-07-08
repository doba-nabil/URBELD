<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class User extends Authenticatable implements HasMedia, Auditable
{

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, InteractsWithMedia,AuditableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'name',
        'representative_name',
        'email',
        'password',
        'phone',
        'id_number',
        'otp_code',
        'otp_expires_at',
        'points',
        'last_login_at',
        'last_seen_at',
        'active',
        'is_admin',
        'is_trusted',
        'classification_id',
        'google_id',
        'facebook_id',
        'membership_type', // company or individual
        'user_type',      // service_seeker or service_provider
        'provider_type',  // individual or company
        'city_id',
        'bio',
        'years_of_experience',
        'receive_email_notifications',
        'membership_id',
        'subscription_package_id',
        'subscription_start_at',
        'subscription_end_at',
        'theme_mode',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->user_number = self::generateUniqueUserNumber();
            if (empty($user->uuid)) {
                $user->uuid = (string) Str::uuid();
            }
        });

        static::updating(function ($user) {
            if (empty($user->uuid)) {
                $user->uuid = (string) Str::uuid();
            }
        });
    }

    protected static function generateUniqueUserNumber()
    {
        do {
            $number = rand(100000, 999999);
        } while (self::where('user_number', $number)->exists());
        return $number;
    }

    public function getUuidAttribute($value)
    {
        return $value ?: $this->id;
    }

    // display_name removed - not used in estate project
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'subscription_start_at' => 'datetime',
            'subscription_end_at' => 'datetime',
            'password' => 'hashed',
            'years_of_experience' => 'integer',
            'receive_email_notifications' => 'boolean',
        ];
    }
    
    
    public function favourites()
    {
        return $this->belongsToMany(User::class, 'favourites', 'user_id', 'favourite_user_id')
            ->withTimestamps();
    }

    public function favoriteCount()
    {
        return $this->favorites()->count();
    }


    public function lovedBy()
    {
        return $this->belongsToMany(User::class, 'favourites', 'favourite_user_id', 'user_id')
            ->withTimestamps();
    }

    // Profile relationship removed - not used in estate project
    // Users are linked to memberships directly, not profiles

    public function compatibility_tests()
    {
        return $this->hasMany(UserAnswer::class, 'user_id')->where('exam_type', 'test');
    }

    public function profile_tests()
    {
        return $this->hasMany(UserAnswer::class, 'user_id')->where('exam_type', 'profile');
    }


    public function hasInterestWith($userId)
    {
        // Check if there's mutual interest (either user added the other to favorites)
        return $this->favorites()->where('favourite_user_id', $userId)->exists()
            || \App\Models\Favourite::where('user_id', $userId)
                ->where('favourite_user_id', $this->id)
                ->exists();
    }

    /**
     * Users that this user has blocked
     */
    public function blockedUsers()
    {
        return $this->belongsToMany(User::class, 'blocked_users', 'user_id', 'blocked_user_id')
            ->withTimestamps();
    }

    /**
     * Users who have blocked this user
     */
    public function blockedBy()
    {
        return $this->belongsToMany(User::class, 'blocked_users', 'blocked_user_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Check if this user has blocked another user
     */
    public function hasBlocked($userId)
    {
        return $this->blockedUsers()->where('blocked_user_id', $userId)->exists();
    }

    /**
     * Check if this user is blocked by another user
     */
    public function isBlockedBy($userId)
    {
        return $this->blockedBy()->where('user_id', $userId)->exists();
    }

    /**
     * Block a user
     */
    public function block($userId)
    {
        if (!$this->hasBlocked($userId) && $this->id != $userId) {
            $this->blockedUsers()->attach($userId);
            return true;
        }
        return false;
    }

    /**
     * Unblock a user
     */
    public function unblock($userId)
    {
        if ($this->hasBlocked($userId)) {
            $this->blockedUsers()->detach($userId);
            return true;
        }
        return false;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }

    /**
     * Check if user is currently online (active within 5 minutes)
     */
    public function isOnline(): bool
    {
        if (!$this->last_seen_at) {
            return false;
        }
        return $this->last_seen_at->diffInMinutes(now()) < 5;
    }

    /**
     * العضوية الحالية
     */
    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    /**
     * تاريخ العضويات
     */
    public function membershipHistory()
    {
        return $this->hasMany(UserMembershipHistory::class);
    }

    /**
     * الطلبات التي أنشأها (كطالب خدمة)
     */
    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }

    /**
     * الردود التي قدمها (كمقدم خدمة)
     */
    public function serviceRequestResponses()
    {
        return $this->hasMany(ServiceRequestResponse::class);
    }

    /**
     * خدمات مزود الخدمة
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * الاعمال السابقة لمزود الخدمة
     */
    public function works()
    {
        return $this->hasMany(ProviderWork::class);
    }

    /**
     * التقييمات التي قام بها
     */
    public function ratingsGiven()
    {
        return $this->hasMany(Rating::class, 'rater_id');
    }

    /**
     * التقييمات التي تلقاها
     */
    public function ratingsReceived()
    {
        return $this->hasMany(Rating::class, 'rated_id');
    }

    /**
     * City
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Categories that user works in (multi-select)
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'user_categories', 'user_id', 'category_id')
            ->withTimestamps();
    }

    /**
     * Check if user is company type
     */
    public function isCompany(): bool
    {
        return $this->membership_type === 'company';
    }

    /**
     * Check if user is individual type
     */
    public function isIndividual(): bool
    {
        return $this->membership_type === 'individual';
    }

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        // User profile image
        $this->addMediaCollection('users')
            ->singleFile();

        // Album photos
        $this->addMediaCollection('album_photos');

        // Personal photo (for individual)
        $this->addMediaCollection('personal_photo')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->singleFile();

        // Identity documents (for individual)
        $this->addMediaCollection('id_front')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->singleFile();
            
        $this->addMediaCollection('id_back')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->singleFile();

        // Certificates (for both types)
        $this->addMediaCollection('certificates')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf']);

        // Commercial registration (for company)
        $this->addMediaCollection('commercial_registration')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf'])
            ->singleFile();

        // Company files (for company)
        $this->addMediaCollection('company_files')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf']);
    }

    /**
     * التحقق من نوع المستخدم
     */
    public function isServiceSeeker(): bool
    {
        return $this->user_type === 'service_seeker';
    }

    public function isServiceProvider(): bool
    {
        return $this->user_type === 'service_provider';
    }

    public function isIndividualProvider(): bool
    {
        return $this->isServiceProvider() && $this->provider_type === 'individual';
    }

    public function isCompanyProvider(): bool
    {
        return $this->isServiceProvider() && $this->provider_type === 'company';
    }

    public function isSupplier(): bool
    {
        return $this->isServiceProvider() && $this->provider_type === 'supplier';
    }

    public function classification()
    {
        return $this->belongsTo(CompanyClassification::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function supplierOffers()
    {
        return $this->hasMany(SupplierOffer::class);
    }

    public function deliveryCities()
    {
        return $this->belongsToMany(City::class, 'supplier_delivery_cities', 'user_id', 'city_id')->withTimestamps();
    }

    /**
     * التحقق من صلاحية العضوية
     */
    public function hasActiveMembership(): bool
    {
        if (!$this->membership_id || !$this->membership_expires_at) {
            return false;
        }
        return now()->isBefore($this->membership_expires_at);
    }

    /**
     * الحصول على متوسط التقييمات
     */
    public function getAverageRatingAttribute(): float
    {
        return Rating::where('rated_id', $this->id)->avg('rating') ?? 0.0;
    }

    public function getTotalCompletedRequestsAttribute(): int
    {
        if ($this->isServiceProvider()) {
            return ServiceRequest::where('awarded_provider_id', $this->id)
                ->whereIn('status', ['work_completed', 'completed'])
                ->count();
        }

        return $this->serviceRequests()->whereIn('status', ['work_completed', 'completed'])->count();
    }

    public function getActiveRequestsCountAttribute(): int
    {
        if ($this->isServiceProvider()) {
            return ServiceRequest::where('awarded_provider_id', $this->id)
                ->whereIn('status', ['provider_accepted', 'inspection_scheduled'])
                ->count();
        }

        return $this->serviceRequests()->whereIn('status', ['pending', 'provider_accepted', 'inspection_scheduled'])->count();
    }

    /**
     * Scope لمقدمي الخدمات
     */
    public function scopeServiceProviders($query)
    {
        return $query->where('user_type', 'service_provider');
    }

    /**
     * Scope لطالبي الخدمات
     */
    public function scopeServiceSeekers($query)
    {
        return $query->where('user_type', 'service_seeker');
    }

    /**
     * Scope للمستخدمين مع عضوية نشطة
     */
    public function scopeWithActiveMembership($query)
    {
        return $query->whereNotNull('membership_id')
            ->where('membership_expires_at', '>', now());
    }


    /**
     * Get user's subscription package
     */
    public function subscriptionPackage()
    {
        return $this->belongsTo(SubscriptionPackage::class, 'subscription_package_id');
    }

    /**
     * Check if user has an active subscription
     */
    public function hasActiveSubscription()
    {
        return $this->subscription_package_id && 
               $this->subscription_start_at && 
               $this->subscription_end_at && 
               now()->between($this->subscription_start_at, $this->subscription_end_at);
    }

    /**
     * Check if subscription expires within 7 days
     */
    public function isSubscriptionExpiringSoon(): bool
    {
        if (!$this->subscription_end_at || !$this->hasActiveSubscription()) {
            return false;
        }

        return now()->diffInDays($this->subscription_end_at, false) <= 7 && 
               now()->diffInDays($this->subscription_end_at, false) >= 0;
    }

    /**
     * Scope للمستخدمين مع اشتراك نشط
     */
    public function scopeWithActiveSubscription($query)
    {
        return $query->whereNotNull('subscription_package_id')
            ->where('subscription_start_at', '<=', now())
            ->where('subscription_end_at', '>=', now());
    }

    /**
     * Get user's notifications
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->latest();
    }

    /**
     * Get user's unread notifications count
     */
    public function unreadNotificationsCount()
    {
        return $this->notifications()->where('is_read', false)->count();
    }
}

