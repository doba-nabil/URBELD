<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyClassification extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type', // 'company' or 'supplier'
    ];
}
