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
        'works_limit',
        'color',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_days' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'features' => 'array',
        'max_services' => 'integer',
        'works_limit' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where($this->getTable() . '.is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
