<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TenderApplication extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'tender_id',
        'user_id',
        'price',
        'delivery_days',
        'notes',
        'status',
    ];

    protected $casts = [
        'price'         => 'decimal:2',
        'delivery_days' => 'integer',
    ];

    const STATUS_PENDING  = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';

    // ========================
    // Relationships
    // ========================

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ========================
    // Media Collections
    // ========================

    public function registerMediaCollections(): void
    {
        /**
         * ملفات العرض المقدَّم على المناقصة
         * custom_properties:
         *   - title: عنوان الملف
         *   - file_type: 'file' | 'link'
         *   - link_url: الرابط إذا كان file_type = 'link'
         */
        $this->addMediaCollection('application_files')
             ->acceptsMimeTypes([
                 'application/pdf',
                 'image/jpeg',
                 'image/png',
                 'image/webp',
                 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                 'application/zip',
             ]);
    }
}
