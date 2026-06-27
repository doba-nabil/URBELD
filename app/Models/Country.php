<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Country extends Model implements HasMedia, Auditable
{
    use HasFactory, HasTranslations,AuditableTrait, InteractsWithMedia;
    protected $fillable = ['name', 'code'];
    public $translatable = ['name'];

    public function profiles()
    {
        return $this->hasMany(Profile::class, 'country_id');
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, Profile::class, 'country_id', 'id', 'id', 'user_id');
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('countries')
            ->singleFile();
    }

    /**
     * Get country flag URL from uploaded image
     */
    public function getFlagUrl($size = 'w40'): ?string
    {
        // Use local uploaded image
        $localFlag = $this->getFirstMediaUrl('countries');
        if ($localFlag) {
            return $localFlag;
        }

        return null;
    }
}
