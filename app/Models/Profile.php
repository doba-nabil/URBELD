<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Profile extends Model
{
    use HasTranslations;

    public $translatable = ['country', 'nationality', 'educational_level', 'marriage_type'];

    protected $fillable = [
        'first_name',
        'last_name',
        'user_id',
        'gender',
        'birth_date',
        'height',
        'weight',
        'hijab',
        'religion',
        'job',
        'nationality',
        'nationality_id',
        'country',
        'country_id',
        'city_id',
        'marital_status',
        'marriage_type',
        'marriage_type_id',
        'polygamy',
        'about_me',
        'my_desires',
        'linkedin_url',
    ];

    protected $casts = [
        'country' => 'array',
        'nationality' => 'array',
        'educational_level' => 'array',
        'marriage_type' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function nationality()
    {
        return $this->belongsTo(Country::class , 'nationality_id');
    }

    public function marriageType()
    {
        return $this->belongsTo(MarriageType::class);
    }

    public function educationalLevel()
    {
        return $this->belongsTo(EducationalLevel::class);
    }

    /**
     * Get country name - from relationship if available, otherwise from JSON field
     */
    public function getCountryNameAttribute()
    {
        // Try to get from relationship first if country_id exists
        if ($this->country_id) {
            // Check if relationship is already loaded
            if ($this->relationLoaded('country')) {
                $country = $this->getRelation('country');
                if ($country && $country instanceof Country) {
                    return $country->name;
                }
            }
            
            // If not loaded, try to load it
            try {
                $country = $this->country()->first();
                if ($country) {
                    return $country->name;
                }
            } catch (\Exception $e) {
                // If relationship fails, fall through to JSON field
            }
        }
        
        // Fallback to JSON field if it exists
        $countryField = $this->attributes['country'] ?? null;
        if ($countryField) {
            // Try to decode it (it's stored as JSON in database)
            $decoded = json_decode($countryField, true);
            if (is_array($decoded)) {
                return $decoded['ar'] ?? $decoded['en'] ?? (is_string($decoded) ? $decoded : null);
            }
            // If it's already a string, return it
            if (is_string($countryField)) {
                return $countryField;
            }
        }
        
        return null;
    }

    /**
     * Get nationality name - from relationship if available, otherwise from JSON field
     */
    public function getNationalityNameAttribute()
    {
        // Try to get from relationship first if nationality_id exists
        if ($this->nationality_id) {
            // Check if relationship is already loaded
            if ($this->relationLoaded('nationality')) {
                $nationality = $this->getRelation('nationality');
                if ($nationality && $nationality instanceof Country) {
                    return $nationality->name;
                }
            }
            
            // If not loaded, try to load it
            try {
                $nationality = $this->nationality()->first();
                if ($nationality) {
                    return $nationality->name;
                }
            } catch (\Exception $e) {
                // If relationship fails, fall through to JSON field
            }
        }
        
        // Fallback to JSON field if it exists
        $nationalityField = $this->attributes['nationality'] ?? null;
        if ($nationalityField) {
            // Try to decode it (it's stored as JSON in database)
            $decoded = json_decode($nationalityField, true);
            if (is_array($decoded)) {
                return $decoded['ar'] ?? $decoded['en'] ?? (is_string($decoded) ? $decoded : null);
            }
            // If it's already a string, return it
            if (is_string($nationalityField)) {
                return $nationalityField;
            }
        }
        
        return null;
    }
}
