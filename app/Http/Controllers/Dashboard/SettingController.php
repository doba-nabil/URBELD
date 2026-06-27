<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function editHomepage()
    {
        // Settings are dynamically fetched in the view using Setting::getValue and Setting::getMediaUrl
        return view('dashboard.settings.homepage');
    }

    public function updateHomepage(Request $request)
    {
        $data = $request->except(['_token', '_method', 'media']);

        foreach ($data as $key => $value) {
            if (str_starts_with($key, 'home_about_image_')) {
                continue;
            }

            // Check if it's translatable (array with 'ar' and 'en')
            if (is_array($value) && (isset($value['ar']) || isset($value['en']))) {
                Setting::setValue($key, $value, true);
            } elseif (is_array($value)) {
                // E.g., for commitments list
                Setting::setValue($key, json_encode(array_values($value)));
            } else {
                Setting::setValue($key, $value);
            }
        }

        // Initialize Media Setting
        $mediaSetting = Setting::firstOrCreate(['key' => 'media']);
        
        // Handle old Media Uploads (hero, video)
        $mediaKeys = ['home_hero_image', 'home_video_cover', 'home_video'];
        foreach ($mediaKeys as $key) {
            if ($request->hasFile($key)) {
                $mediaSetting->clearMediaCollection($key);
                $mediaSetting->addMediaFromRequest($key)->toMediaCollection($key);
            }
        }

        // Handle home_about_list with images dynamically
        $aboutList = $request->input('home_about_list', []);
        
        foreach ($aboutList as $index => &$slide) {
             // Check if file exists in request for this index
             if ($request->hasFile("home_about_list.$index.image_file")) {
                $file = $request->file("home_about_list.$index.image_file");
                $media = $mediaSetting->addMedia($file)->toMediaCollection('about_slides');
                $slide['image'] = '/storage/' . $media->id . '/' . $media->file_name;
            } elseif (isset($slide['old_image'])) {
                $slide['image'] = $slide['old_image'];
            }
             unset($slide['old_image']);
             unset($slide['image_file']);
        }
        
        Setting::setValue('home_about_list', json_encode(array_values($aboutList)), true);

        return redirect()->back()->with('success', __('admin.update_homepage_success'));
    }
}
