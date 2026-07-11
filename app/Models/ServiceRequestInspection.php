<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
class ServiceRequestInspection extends Model implements Auditable
{
    use HasFactory, AuditableTrait;
    protected $fillable = [
        'service_request_id',
        'response_id',
        'scheduled_at',
        'completed_at',
        'notes',
        'status',
    ];
    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }
    public function response()
    {
        return $this->belongsTo(ServiceRequestResponse::class);
    }
    public function complete($notes = null)
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_at' => now(),
            'notes' => $notes ?? $this->notes,
        ]);
        $this->serviceRequest->update(['status' => ServiceRequest::STATUS_INSPECTION_DONE]);
    }
    public function cancel()
    {
        $this->update(['status' => self::STATUS_CANCELLED]);
    }
    public function scopeScheduled($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED);
    }
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }
}
