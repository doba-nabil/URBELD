<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Translatable\HasTranslations;
class SubscriptionType extends Model implements Auditable
{
    use HasFactory, HasTranslations, AuditableTrait, SoftDeletes;
    public $translatable = ['name'];
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'sort_order',
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
