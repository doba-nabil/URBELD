<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Translatable\HasTranslations;

class SubscriptionPackage extends Model implements Auditable
{
    use HasFactory, HasTranslations, AuditableTrait, SoftDeletes;

    public $translatable = ['name', 'features', 'badge_name'];
    
    protected $fillable = [
        'name',
        'badge_name',
        'description',
        'price',
        'duration_days',
        'features',
        'is_active',
        'sort_order',
        'max_services',
        'max_products',
        'max_offers',
        'works_limit',
        'color',
        'is_recommended',
        'is_featured',
        'receive_tenders_priority',
    ];

    protected $casts = [
        'price'                      => 'decimal:2',
        'duration_days'              => 'integer',
        'is_active'                  => 'boolean',
        'is_recommended'             => 'boolean',
        'is_featured'                => 'boolean',
        'receive_tenders_priority'   => 'boolean',
        'sort_order'                 => 'integer',
        'features'                   => 'array',
        'max_services'               => 'integer',
        'max_products'               => 'integer',
        'max_offers'                 => 'integer',
        'works_limit'                => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            if ($model->is_recommended) {
                static::where('id', '!=', $model->id)->update(['is_recommended' => false]);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where($this->getTable() . '.is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
