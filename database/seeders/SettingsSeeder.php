<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::setValue('site_name', 'اسم الموقع');
        Setting::setValue('meta_title', 'Meta title');
        Setting::setValue('meta_description', 'Meta Description');
        Setting::setValue('home_memmbership', '');
        Setting::setValue('footer_text', 'نص الفوتر');
        Setting::setValue('contact_email', 'info@example.com');
        Setting::setValue('contact_phone', '+20123456789');
        Setting::setValue('media', null);
    }
}
