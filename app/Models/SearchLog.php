<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    protected $fillable = [
        'user_id',
        'search_type',
        'search_filters',
        'results_count',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'search_filters' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

