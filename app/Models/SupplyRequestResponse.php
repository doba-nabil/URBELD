<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyRequestResponse extends Model
{
    protected $fillable = [
        'supply_request_id',
        'user_id',
        'proposed_price',
        'notes',
        'status',
    ];

    public function supplyRequest()
    {
        return $this->belongsTo(SupplyRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
