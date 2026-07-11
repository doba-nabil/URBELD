<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\ServiceRequestResponseObserver;
#[ObservedBy([ServiceRequestResponseObserver::class])]
class ServiceRequestResponse extends Model implements Auditable
{
    use HasFactory, AuditableTrait;
    protected $fillable = [
        'service_request_id',
        'user_id',
        'status',
        'message',
        'proposed_price',
        'proposed_timeline',
        'responded_at',
    ];
    protected $casts = [
        'proposed_price' => 'decimal:2',
        'responded_at' => 'datetime',
    ];
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_TIMEOUT = 'timeout';
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function inspections()
    {
        return $this->hasMany(ServiceRequestInspection::class , 'response_id');
    }
    public function canBeAccepted(): bool
    {
        return $this->status === self::STATUS_PENDING
            && $this->serviceRequest->status === ServiceRequest::STATUS_PENDING;
    }
    public function accept()
    {
        $this->update(['status' => self::STATUS_ACCEPTED]);
        $this->serviceRequest->update([
            'status' => ServiceRequest::STATUS_PROVIDER_ACCEPTED,
            'accepted_at' => now(),
        ]);
    }
    public function reject()
    {
        $this->update(['status' => self::STATUS_REJECTED]);
    }
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    public function scopeAccepted($query)
    {
        return $query->where('status', self::STATUS_ACCEPTED);
    }
}
