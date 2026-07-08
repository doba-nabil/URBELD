<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedTender extends Model
{
    protected $fillable = ['user_id', 'tender_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }
}
