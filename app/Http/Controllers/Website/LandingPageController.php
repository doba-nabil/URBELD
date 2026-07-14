<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\LandingPageFeature;
use App\Models\Setting;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale();
        $features = LandingPageFeature::active()->orderBy('order')->get();
        
        $siteName = Setting::getValue('site_name', $locale, 'ERSAA');
        $siteLogo = Setting::getMediaUrl('logo_ar') ?: (Setting::getMediaUrl('favicon') ?: asset('dashboard/assets/img/logo/logo.png'));
        $isRtl = $locale == 'ar';
        $primaryColor = '#014D40'; // Site primary color

        // Admin Settings - Sections
        $aboutTitle = Setting::getValue('landing_about_title', $locale);
        $aboutDesc = Setting::getValue('landing_about_description', $locale);
        $aboutPoint1 = Setting::getValue('landing_about_point_1', $locale);
        $aboutPoint2 = Setting::getValue('landing_about_point_2', $locale);
        
        $featuresTitle = Setting::getValue('landing_features_title', $locale);
        $featuresSubtitle = Setting::getValue('landing_features_subtitle', $locale);

        $heroTitle = Setting::getValue('landing_hero_title', $locale);
        $heroSubtitle = Setting::getValue('landing_hero_subtitle', $locale);
        
        $videoTitle = Setting::getValue('landing_video_title', $locale);
        $videoUrl = Setting::getMediaUrl('landing_video') ?: asset('website/assets/img/video.mp4');
        $videoCover = Setting::getMediaUrl('landing_video_cover') ?: asset('website/assets/img/video-section.png');
        
        $featuresMainImage = Setting::getMediaUrl('landing_features_image') ?: asset('website/assets/img/why-us.png');
        $aboutImage = Setting::getMediaUrl('landing_about_image') ?: asset('website/assets/img/about.jpg');
        $heroBg = Setting::getMediaUrl('landing_hero_bg') ?: asset('website/assets/img/hero-bg.jpg');
        
        // Site core settings for contact
        $sitePhone = Setting::getValue('site_phone');
        $siteEmail = Setting::getValue('site_email');
        
        $socialsJson = Setting::where('key', 'socials')->value('value') ?: '[]';
        $socials = is_string($socialsJson) ? json_decode($socialsJson, true) : (is_array($socialsJson) ? $socialsJson : []);

        return view('website.landing.index', compact(
            'features', 'siteName', 'siteLogo', 'isRtl', 'primaryColor',
            'aboutTitle', 'aboutDesc', 'aboutPoint1', 'aboutPoint2',
            'featuresTitle', 'featuresSubtitle',
            'heroTitle', 'heroSubtitle', 'videoTitle', 'videoUrl', 'videoCover',
            'featuresMainImage', 'aboutImage', 'heroBg', 'sitePhone', 'siteEmail', 'socials'
        ));
    }
}
