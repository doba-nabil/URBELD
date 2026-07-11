<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class Banner extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = [
        'title',
        'supplier_offer_id',
        'page_scope',
        'category_id',
        'custom_page',
        'is_active',
        'sort_order',
    ];
    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];
    const SCOPE_HOME              = 'home';
    const SCOPE_ALL_CATEGORIES    = 'all_categories';
    const SCOPE_SPECIFIC_CATEGORY = 'specific_category';
    const SCOPE_TENDERS           = 'tenders';
    const SCOPE_CUSTOM            = 'custom';
    public static function scopeLabels(): array
    {
        return [
            self::SCOPE_HOME              => 'الصفحة الرئيسية',
            self::SCOPE_ALL_CATEGORIES    => 'كل صفحات التصنيفات',
            self::SCOPE_SPECIFIC_CATEGORY => 'تصنيف محدد',
            self::SCOPE_TENDERS           => 'صفحة المناقصات',
            self::SCOPE_CUSTOM            => 'صفحة مخصصة',
        ];
    }
    // ========================
    // Relationships
    // ========================
    public function supplierOffer()
    {
        return $this->belongsTo(SupplierOffer::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    // ========================
    // Media Collections
    // ========================
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('banner_image')
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
             ->singleFile();
    }
    // ========================
    // Scopes
    // ========================
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
    public static function getForPage(string $scope, ?int $categoryId = null)
    {
        $cacheKey = "banners_{$scope}_{$categoryId}";
        // Cache for 6 hours (21600 seconds), using tags for easy invalidation
        $closure = function () use ($scope, $categoryId) {
            return static::active()
                ->where(function ($q) use ($scope, $categoryId) {
                    $q->where('page_scope', $scope);
                    if ($scope === self::SCOPE_SPECIFIC_CATEGORY && $categoryId) {
                        $q->orWhere(function ($inner) use ($categoryId) {
                            $inner->where('page_scope', self::SCOPE_SPECIFIC_CATEGORY)
                                  ->where('category_id', $categoryId);
                        });
                    }
                })
                ->with(['supplierOffer', 'supplierOffer.user'])
                ->get();
        };
        if (\Illuminate\Support\Facades\Cache::supportsTags()) {
            return \Illuminate\Support\Facades\Cache::tags(['banners'])->remember($cacheKey, 21600, $closure);
        }
        return \Illuminate\Support\Facades\Cache::remember($cacheKey, 21600, $closure);
    }
    public function getImageUrlAttribute(): string
    {
        if ($this->getFirstMediaUrl('banner_image')) {
            return $this->getFirstMediaUrl('banner_image');
        }
        if ($this->supplierOffer) {
            return $this->supplierOffer->getFirstMediaUrl('offer_images') ?: '';
        }
        return '';
    }
}
