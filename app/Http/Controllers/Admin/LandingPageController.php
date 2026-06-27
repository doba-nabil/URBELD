<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\LandingPageFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LandingPageController extends Controller
{
    public function index()
    {
        $features = LandingPageFeature::orderBy('order')->get();
        
        $settingModel = Setting::where('key', 'media')->first();
        $aboutImageUrl = $settingModel?->getFirstMediaUrl('landing_about_image');
        $videoUrl = $settingModel?->getFirstMediaUrl('landing_video');
        $videoCoverUrl = $settingModel?->getFirstMediaUrl('landing_video_cover');
        $featuresImageUrl = $settingModel?->getFirstMediaUrl('landing_features_image');
        $heroBgUrl = $settingModel?->getFirstMediaUrl('landing_hero_bg');

        return view('dashboard.landing_page.index', compact('features', 'aboutImageUrl', 'videoUrl', 'videoCoverUrl', 'featuresImageUrl', 'heroBgUrl'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'landing_about_image' => 'nullable|image|max:2048',
            'landing_video' => 'nullable|mimes:mp4,mov,ogg,qt|max:30480', // Support up to 30MB
            'landing_video_cover' => 'nullable|image|max:2048',
            'landing_features_image' => 'nullable|image|max:2048',
            'landing_hero_bg' => 'nullable|image|max:3072',
        ]);

        $translatableKeys = [
            'landing_hero_title',
            'landing_hero_subtitle',
            'landing_about_title',
            'landing_about_description',
            'landing_about_point_1',
            'landing_about_point_2',
            'landing_video_title',
            'landing_features_title',
            'landing_features_subtitle',
        ];

        foreach ($translatableKeys as $key) {
            Setting::setValue($key, $request->input($key), true);
        }

        $settingMedia = Setting::firstOrCreate(['key' => 'media']);

        if ($request->hasFile('landing_about_image')) {
            $settingMedia->clearMediaCollection('landing_about_image');
            $settingMedia->addMedia($request->file('landing_about_image'))->toMediaCollection('landing_about_image');
        }

        if ($request->hasFile('landing_video')) {
            $settingMedia->clearMediaCollection('landing_video');
            $settingMedia->addMedia($request->file('landing_video'))->toMediaCollection('landing_video');
        }

        if ($request->hasFile('landing_video_cover')) {
            $settingMedia->clearMediaCollection('landing_video_cover');
            $settingMedia->addMedia($request->file('landing_video_cover'))->toMediaCollection('landing_video_cover');
        }

        if ($request->hasFile('landing_features_image')) {
            $settingMedia->clearMediaCollection('landing_features_image');
            $settingMedia->addMedia($request->file('landing_features_image'))->toMediaCollection('landing_features_image');
        }

        if ($request->hasFile('landing_hero_bg')) {
            $settingMedia->clearMediaCollection('landing_hero_bg');
            $settingMedia->addMedia($request->file('landing_hero_bg'))->toMediaCollection('landing_hero_bg');
        }

        Cache::forget('settings');

        return redirect()->back()->with('success', __('admin.update_success'));
    }

    public function createFeature()
    {
        return view('dashboard.landing_page.features.create');
    }

    public function storeFeature(Request $request)
    {
        $request->validate([
            'title.ar' => 'required|string|max:255',
            'title.en' => 'required|string|max:255',
            'description.ar' => 'required|string',
            'description.en' => 'required|string',
            'image' => 'required|image|max:2048',
        ]);

        $feature = LandingPageFeature::create([
            'title' => $request->title,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
            'order' => $request->order ?? 0,
        ]);

        if ($request->hasFile('image')) {
            $feature->addMedia($request->file('image'))->toMediaCollection('image');
        }

        return redirect()->route('admin.landing-page.index')->with('success', __('admin.create_success'));
    }

    public function editFeature(LandingPageFeature $feature)
    {
        return view('dashboard.landing_page.features.edit', compact('feature'));
    }

    public function updateFeature(Request $request, LandingPageFeature $feature)
    {
        $request->validate([
            'title.ar' => 'required|string|max:255',
            'title.en' => 'required|string|max:255',
            'description.ar' => 'required|string',
            'description.en' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $feature->update([
            'title' => $request->title,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
            'order' => $request->order ?? 0,
        ]);

        if ($request->hasFile('image')) {
            $feature->clearMediaCollection('image');
            $feature->addMedia($request->file('image'))->toMediaCollection('image');
        }

        return redirect()->route('admin.landing-page.index')->with('success', __('admin.update_success'));
    }

    public function destroyFeature(LandingPageFeature $feature)
    {
        $feature->delete();
        return redirect()->back()->with('success', __('admin.delete_success'));
    }
}
