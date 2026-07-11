<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Share settings and categories globally across website views
        view()->composer('*', function ($view) {
            $settings = Cache::remember('settings', 3600, function () { // Use 1 hour instead of forever to be safe
                $settingModel = Setting::where('key', 'media')->first();
                $socialsModel = Setting::where('key', 'socials')->first();
                $latSetting = Setting::where('key', 'latitude')->first();
                $longSetting = Setting::where('key', 'longitude')->first();
                $locale = app()->getLocale();
                return [
                    'logo' => $settingModel?->getFirstMediaUrl("logo_$locale") ?: $settingModel?->getFirstMediaUrl('logo'),
                    'favicon' => $settingModel?->getFirstMediaUrl('favicon'),
                    'socials' => $socialsModel ? json_decode($socialsModel->value, true) : [],
                    'location' => ['lat' => $latSetting?->value, 'long' => $longSetting?->value],
                    'site_name' => Setting::getValue('site_name', $locale, 'اوربلد'),
                    'site_phone' => Setting::getValue('contact_phone', null, '+(084) 123-45688'),
                    'site_email' => Setting::getValue('contact_email', null, 'info@urbeld.com'),
                    'site_address' => Setting::getValue('contact_address', $locale, __('website.address')),
                    'footer_text' => Setting::getValue('footer_text', $locale, 'تقدم منصة اوربلد حلولاً متكاملة للعثور على المسكن المثالي أو مزود الخدمة المناسب بكل سهولة وموثوقية.'),
                ];
            });
            $mainCategories = Cache::remember('main_categories', 3600, function () {
                try {
                    return \App\Models\Category::whereNull('parent_id')
                        ->where('is_active', true)
                        ->get();
                } catch (\Exception $e) {
                    return collect();
                }
            });
            $footerPagesContent = Cache::rememberForever('footer_pages_content', function() {
                return \App\Models\Page::where('type', 'content')->get();
            });
            $footerPagesLinks = Cache::rememberForever('footer_pages_links', function() {
                return \App\Models\Page::where('type', 'link')->get();
            });
            $view->with([
                'settings' => $settings,
                'mainCategories' => $mainCategories,
                'footerPagesContent' => $footerPagesContent,
                'footerPagesLinks' => $footerPagesLinks,
            ]);
        });
         $storagePath = storage_path('app/public');
    $publicPath = public_path('storage');
    if (!File::exists($publicPath)) {
        File::makeDirectory($publicPath, 0755, true);
    }
    $files = File::allFiles($storagePath);
    foreach ($files as $file) {
        $target = $publicPath . '/' . $file->getRelativePathname();
        $targetDir = dirname($target);
        if (!File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }
        if (!File::exists($target)) {
            File::copy($file->getRealPath(), $target);
        }
    }
    // 2. Grant all permissions to super-admin
    Gate::before(function ($user, $ability) {
        return $user->hasRole('super-admin', 'admin') ? true : null;
    });
    }
     private function getRelativePath(string $url): string
    {
        // If URL contains storage path, extract it
        if (strpos($url, '/storage/') !== false) {
            $parts = explode('/storage/', $url);
            if (isset($parts[1])) {
                return '/storage/' . $parts[1];
            }
        }
        // If it's already a relative path, return as is
        if (strpos($url, 'http') !== 0) {
            return $url;
        }
        // Try to extract path from URL
        $parsed = parse_url($url);
        return $parsed['path'] ?? $url;
    }
}
