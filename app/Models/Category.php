<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Category extends Model implements HasMedia, Auditable
{
    use HasFactory, HasTranslations, AuditableTrait, InteractsWithMedia;

    protected $fillable = [
        'parent_id',
        'name',
        'description',
        'slug',
        'icon',
        'icon_title',
        'is_active',
        'color',
        'is_system_reserved',
        'show_in_home',
        'supports_tenders',
        'supports_supply_requests',
        'is_full_width',
        'sort_order',
        'bulk_request_title',
        'bulk_request_subtitle',
        'bulk_request_button_text',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_system_reserved' => 'boolean',
        'show_in_home' => 'boolean',
        'supports_tenders' => 'boolean',
        'supports_supply_requests' => 'boolean',
        'is_full_width' => 'boolean',
        'sort_order' => 'integer',
    ];

    public $translatable = ['name', 'description', 'icon_title', 'bulk_request_title', 'bulk_request_subtitle', 'bulk_request_button_text'];

    public function scopeActive($query)
    {
        return $query->where($this->getTable() . '.is_active', true);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_categories', 'category_id', 'user_id');
    }
}

