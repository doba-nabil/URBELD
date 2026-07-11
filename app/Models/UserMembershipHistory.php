<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
class UserMembershipHistory extends Model implements Auditable
{
    use HasFactory, AuditableTrait;
    protected $table = 'user_membership_history';
    protected $fillable = [
        'user_id',
        'membership_id',
        'started_at',
        'expires_at',
        'price_paid',
        'status',
        'notes',
    ];
    protected $casts = [
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
        'price_paid' => 'decimal:2',
    ];
    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }
    public function isExpired(): bool
    {
        return now()->isAfter($this->expires_at);
    }
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
            ->where('expires_at', '>', now());
    }
    public function scopeExpired($query)
    {
        return $query->where(function ($q) {
            $q->where('status', self::STATUS_EXPIRED)
                ->orWhere('expires_at', '<=', now());
        });
    }
}
