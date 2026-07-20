<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TenderPayPerUse extends Model
{
    protected $table = 'tender_pay_per_use';
    protected $fillable = [
        'user_id',
        'tender_id',
        'type',
        'amount_paid',
        'status',
        'payment_reference',
        'paid_at',
    ];
    protected $casts = [
        'amount_paid' => 'decimal:2',
        'paid_at'     => 'datetime',
    ];
    const STATUS_PENDING = 'pending';
    const STATUS_PAID    = 'paid';
    const STATUS_FAILED  = 'failed';
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }
}
