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

    // حالات المعاينة
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * الطلب
     */
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    /**
     * الرد المرتبط
     */
    public function response()
    {
        return $this->belongsTo(ServiceRequestResponse::class);
    }

    /**
     * إتمام المعاينة
     */
    public function complete($notes = null)
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_at' => now(),
            'notes' => $notes ?? $this->notes,
        ]);

        // تحديث حالة الطلب إلى "تمت المعاينة"
        $this->serviceRequest->update(['status' => ServiceRequest::STATUS_INSPECTION_DONE]);
    }

    /**
     * إلغاء المعاينة
     */
    public function cancel()
    {
        $this->update(['status' => self::STATUS_CANCELLED]);
    }

    /**
     * Scope للمواعيد المجدولة
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }
}
