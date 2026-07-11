<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class Membership extends Model implements HasMedia, Auditable
{
    use HasFactory, HasTranslations, AuditableTrait, SoftDeletes, InteractsWithMedia;
    public $translatable = ['name'];
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'sort_order',
        'type', // company or individual
        'id_front_image', // For individual
        'id_back_image', // For individual
        'commercial_registration', // For company
        'employees_count', // For company
        'main_category_id', // For both individual and company
        'country_id', // For both individual and company
        'city_id', // For both individual and company
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'employees_count' => 'integer',
    ];
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function membershipHistory()
    {
        return $this->hasMany(UserMembershipHistory::class);
    }
    public function certificates()
    {
        return $this->hasMany(MembershipCertificate::class)->orderBy('sort_order');
    }
    public function mainCategory()
    {
        return $this->belongsTo(Category::class, 'main_category_id');
    }
    public function subCategories()
    {
        return $this->belongsToMany(Category::class, 'membership_sub_categories', 'membership_id', 'category_id')
            ->withTimestamps();
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('id_front')->singleFile();
        $this->addMediaCollection('id_back')->singleFile();
        $this->addMediaCollection('commercial_registration')->singleFile();
    }
    /**
     * Check if membership is company type
     */
    public function isCompany(): bool
    {
        return $this->type === 'company';
    }
    /**
     * Check if membership is individual type
     */
    public function isIndividual(): bool
    {
        return $this->type === 'individual';
    }
}
