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
    ];

    public $translatable = ['name', 'description', 'icon_title'];

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
}

