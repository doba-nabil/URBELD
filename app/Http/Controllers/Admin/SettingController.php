<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function changeTheme(Request $request)
    {
        $theme = $request->input('theme');
        if (!in_array($theme, ['light', 'dark'])) {
            return response()->json(['success' => false, 'message' => 'Invalid theme'], 400);
        }
        // Try admin guard first, then web guard
        $user = auth()->guard('admin')->user() ?? auth()->user();
        if ($user) {
            $user->theme_mode = $theme;
            $user->save();
        } else {
            session(['theme' => $theme]);
        }
        return response()->json(['success' => true, 'theme' => $theme]);
    }

    public function get_settings()
    {
        // Socials handled separately as it's a specific JSON blob in 'value'
        $socialsJson = Setting::where('key', 'socials')->value('value') ?? '[]';
        $socials = json_decode($socialsJson, true);

        $settingModel = Setting::where('key', 'media')->first();
        $logoArUrl = $this->getRelativePath($settingModel?->getFirstMediaUrl('logo_ar'));
        $logoEnUrl = $this->getRelativePath($settingModel?->getFirstMediaUrl('logo_en'));
        $faviconUrl = $this->getRelativePath($settingModel?->getFirstMediaUrl('favicon'));
        $homeVideoUrl = $this->getRelativePath($settingModel?->getFirstMediaUrl('home_video')); // This seems to be the video file
        // $homeImageUrl was old, let's keep it or replace with new ones if we migrated fully. 
        // We are using home_hero_image now.
        $homeHeroImageUrl = $this->getRelativePath($settingModel?->getFirstMediaUrl('home_hero_image'));
        $homeVideoCoverUrl = $this->getRelativePath($settingModel?->getFirstMediaUrl('home_video_cover'));
        
        $mainBackgroundUrl = $this->getRelativePath($settingModel?->getFirstMediaUrl('main_background'));
        $footerBackgroundUrl = $this->getRelativePath($settingModel?->getFirstMediaUrl('footer_background'));
        
        $banners = $settingModel?->getMedia('banners') ?? collect();

        return view('dashboard.settings.settings', compact(
            'logoArUrl', 'logoEnUrl', 'faviconUrl', 'socials', 'homeVideoUrl', 'homeHeroImageUrl', 'homeVideoCoverUrl', 'banners', 'mainBackgroundUrl', 'footerBackgroundUrl'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'logo_ar' => 'nullable|image|max:2048',
            'logo_en' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
            'home_video' => 'nullable|mimes:mp4,mov,ogg,qt|max:8192',
            'main_background' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,mp4,mov,ogg,qt,webm|max:8192',
            'footer_background' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,mp4,mov,ogg,qt,webm|max:8192',
            'home_hero_image' => 'nullable|image|max:2048',
            'home_video_cover' => 'nullable|image|max:2048',
            'banners.*' => 'nullable|image|max:2048',
        ]);

        $translatableKeys = [
            'site_name', 'meta_title', 'meta_description', 'home_memmbership', 'footer_text', 'contact_address',
            'home_hero_title', 'home_hero_desc', 'home_hero_btn_text',
            'home_video_label', 'home_video_title', 'home_partners_title',
            'home_commitments_badge', 'home_commitments_title', 'home_commitments_desc',
            'home_contact_badge', 'home_contact_title', 'home_contact_desc', 'home_contact_btn',
            'home_services_title'
        ];

        foreach ($translatableKeys as $key) {
            Setting::setValue($key, $request->input($key), true);
        }
        foreach (['contact_email','contact_phone', 'longitude', 'latitude', 'main_background_type', 'footer_background_type', 'is_subscription_enabled'] as $key) {
            Setting::setValue($key, $request->input($key));
        }

        // Home Settings - Hero
        foreach (['home_hero_title', 'home_hero_desc', 'home_hero_btn_text'] as $key) {
            Setting::setValue($key, $request->input($key), true);
        }
        Setting::setValue('home_hero_btn_link', $request->input('home_hero_btn_link'));

        // Home Settings - Video Showcase
        foreach (['home_video_label', 'home_video_title'] as $key) {
             Setting::setValue($key, $request->input($key), true);
        }
        Setting::setValue('home_video_url', $request->input('home_video_url'));

        // Home Settings - Contact Section
        foreach (['home_contact_badge', 'home_contact_title', 'home_contact_desc', 'home_contact_btn'] as $key) {
            Setting::setValue($key, $request->input($key), true);
        }

        // Home Settings - Commitments Section
        foreach (['home_commitments_badge', 'home_commitments_title', 'home_commitments_desc'] as $key) {
            Setting::setValue($key, $request->input($key), true);
        }

        // Home Settings - Section Titles
        foreach (['home_services_title', 'home_partners_title'] as $key) {
            Setting::setValue($key, $request->input($key), true);
        }
        $settingModel = Setting::firstOrCreate(['key' => 'media']);
        if ($request->hasFile('logo_ar')) {
            $settingModel->clearMediaCollection('logo_ar');
            $settingModel->addMedia($request->file('logo_ar'))->toMediaCollection('logo_ar');
        }
        if ($request->hasFile('logo_en')) {
            $settingModel->clearMediaCollection('logo_en');
            $settingModel->addMedia($request->file('logo_en'))->toMediaCollection('logo_en');
        }
        if ($request->hasFile('favicon')) {
            $settingModel->clearMediaCollection('favicon');
            $settingModel->addMedia($request->file('favicon'))->toMediaCollection('favicon');
        }
        if ($request->hasFile('home_video')) {
            $settingModel->clearMediaCollection('home_video');
            $settingModel->addMedia($request->file('home_video'))->toMediaCollection('home_video');
        }
        if ($request->hasFile('main_background') && $request->file('main_background')->isValid()) {
            $settingModel->clearMediaCollection('main_background');
            $settingModel->addMedia($request->file('main_background'))->toMediaCollection('main_background');
        }
        if ($request->hasFile('footer_background') && $request->file('footer_background')->isValid()) {
            $settingModel->clearMediaCollection('footer_background');
            $settingModel->addMedia($request->file('footer_background'))->toMediaCollection('footer_background');
        }
        if ($request->hasFile('home_hero_image') && $request->file('home_hero_image')->isValid()) {
            $settingModel->clearMediaCollection('home_hero_image');
            $settingModel->addMedia($request->file('home_hero_image'))->toMediaCollection('home_hero_image');
        }
        
        if ($request->hasFile('home_video_cover') && $request->file('home_video_cover')->isValid()) {
            $settingModel->clearMediaCollection('home_video_cover');
            $settingModel->addMedia($request->file('home_video_cover'))->toMediaCollection('home_video_cover');
        }

        // Handle home image deletion

        if ($request->has('delete_home_hero_image')) {
            $settingModel->clearMediaCollection('home_hero_image');
        }
        
        // Handle banner uploads
        if ($request->hasFile('banners')) {
            foreach ($request->file('banners') as $banner) {
                if ($banner->isValid()) {
                    $settingModel->addMedia($banner)->toMediaCollection('banners');
                }
            }
        }
        
        // Handle banner links update
        if ($request->has('banner_links')) {
            foreach ($request->input('banner_links', []) as $bannerId => $link) {
                $media = $settingModel->getMedia('banners')->firstWhere('id', $bannerId);
                if ($media) {
                    $media->setCustomProperty('link', $link);
                    $media->save();
                }
            }
        }
        
        // Handle banner deletion
        if ($request->has('delete_banners')) {
            foreach ($request->input('delete_banners', []) as $bannerId) {
                $settingModel->deleteMedia($bannerId);
            }
        }
        

        

        
        Setting::setValue('socials', json_encode($request->input('socials', [])));

        // Handle commitments list with images
        $commitments = $request->input('home_commitments_list', []);
        
        // When using array inputs for files, Laravel structures it as home_commitments_list => [index => ['image' => UploadedFile]]
        // But only if the index exists in both.
        // Let's iterate over the input array and check for files.
        
        foreach ($commitments as $index => &$commit) {
             // Check if file exists in request for this index and is valid
             if ($request->hasFile("home_commitments_list.$index.image")) {
                 $file = $request->file("home_commitments_list.$index.image");
                 if ($file->isValid()) {
                     $media = $settingModel->addMedia($file)->toMediaCollection('commitments');
                     $commit['image'] = $this->getRelativePath($media->getUrl());
                 } else {
                     $commit['image'] = $commit['old_image'] ?? null;
                 }
             } elseif (isset($commit['old_image'])) {
                $commit['image'] = $commit['old_image'];
            }
             unset($commit['old_image']);
        }
        
        Setting::setValue('home_commitments_list', json_encode(array_values($commitments)), true);

        // Handle home_about_list with images
        $aboutList = $request->input('home_about_list', []);
        
        foreach ($aboutList as $index => &$slide) {
             if ($request->hasFile("home_about_list.$index.image")) {
                 $file = $request->file("home_about_list.$index.image");
                 if ($file->isValid()) {
                     $media = $settingModel->addMedia($file)->toMediaCollection('about_slides');
                     $slide['image'] = $this->getRelativePath($media->getUrl());
                 } else {
                     $slide['image'] = $slide['old_image'] ?? null;
                 }
             } elseif (isset($slide['old_image'])) {
                 $slide['image'] = $slide['old_image'];
             }
             unset($slide['old_image']);
        }
        
        Setting::setValue('home_about_list', json_encode(array_values($aboutList)), true);

        Cache::forget('settings');
        return redirect()->back()->with('success',__('admin.update_success'));
    }
    
    /**
     * Extract relative path from full URL
     * Converts http://localhost:8000/storage/16/logo.png to /storage/16/logo.png
     */
    private function getRelativePath(?string $url): ?string
    {
        if (!$url) {
            return null;
        }
        
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

    public function uploadMedia(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('temp/uploads', $fileName, 'public');
            $fullPath = storage_path('app/public/' . $path);
            \Log::info('[uploadMedia] saved to: ' . $fullPath . ' | exists: ' . (file_exists($fullPath) ? 'YES' : 'NO'));
            return response()->json([
                'success' => true,
                'path' => '/storage/' . $path,
                'name' => $fileName
            ]);
        }
        return response()->json(['success' => false], 400);
    }
}
