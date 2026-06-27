<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Service extends Model implements HasMedia, Auditable
{
    use HasFactory, HasTranslations, AuditableTrait, InteractsWithMedia, SoftDeletes;

    public $translatable = ['title', 'content'];

    protected $fillable = [
        'title',
        'icon',
        'content',
        'sort_order',
        'is_active',
        'user_id',
        'category_id',
        'sub_category_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('services')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->singleFile();
    }

    /**
     * Scope for active services
     */
    public function scopeActive($query)
    {
        return $query->where($this->getTable() . '.is_active', true);
    }

    /**
     * Scope for ordered services
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    /**
     * Get the provider that owns the service.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the main category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the sub category.
     */
    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }
}
