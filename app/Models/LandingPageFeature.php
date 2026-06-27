<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class LandingPageFeature extends Model implements HasMedia
{
    use HasTranslations, InteractsWithMedia;

    protected $fillable = ['title', 'description', 'is_active', 'order'];

    public $translatable = ['title', 'description'];

    /**
     * Scope only active features
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile();
    }
}
