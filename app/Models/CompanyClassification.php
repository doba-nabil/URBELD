<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Translatable\HasTranslations;

class CompanyClassification extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'name',
        'type', // 'company' or 'supplier'
    ];

    public $translatable = ['name'];
}
