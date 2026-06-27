<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\ProviderWork;
use Illuminate\Http\Request;

class ProviderWorkController extends Controller
{
    public function index()
    {
        $works = auth()->user()->works()->orderBy('sort_order')->get();
        return view('website.profile.works.index', compact('works'));
    }

    public function create()
    {
        return view('website.profile.works.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Check for works limit
        if ($user->subscriptionPackage && $user->subscriptionPackage->works_limit > 0) {
            $currentWorksCount = $user->works()->count();
            if ($currentWorksCount >= $user->subscriptionPackage->works_limit) {
                return back()->with('error', __('admin.works_limit_reached') ?? 'لقد وصلت للحد الأقصى للأعمال المسموح بها في باقتك.');
            }
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $work = auth()->user()->works()->create([
            'title' => $request->title,
            'description' => $request->description,
            'sort_order' => auth()->user()->works()->count(),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $work->addMedia($file)
                    ->usingName($file->getClientOriginalName())
                    ->usingFileName($file->getClientOriginalName())
                    ->preservingOriginal()
                    ->toMediaCollection('work_images');
            }
        }

        return redirect()->route('provider.works.index')->with('success', __('admin.Success') ?? 'تمت الإضافة بنجاح');
    }

    public function edit(ProviderWork $work)
    {
        if ($work->user_id !== auth()->id()) {
            abort(403);
        }
        return view('website.profile.works.edit', compact('work'));
    }

    public function update(Request $request, ProviderWork $work)
    {
        if ($work->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $work->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $work->addMedia($file)
                    ->usingName($file->getClientOriginalName())
                    ->usingFileName($file->getClientOriginalName())
                    ->preservingOriginal()
                    ->toMediaCollection('work_images');
            }
        }

        return redirect()->route('provider.works.index')->with('success', __('admin.Success') ?? 'تم التعديل بنجاح');
    }

    public function destroy(ProviderWork $work)
    {
        if ($work->user_id !== auth()->id()) {
            abort(403);
        }

        $work->delete();
        return back()->with('success', __('admin.Success') ?? 'تم الحذف بنجاح');
    }

    public function destroyImage(ProviderWork $work, $mediaId)
    {
        if ($work->user_id !== auth()->id()) {
            abort(403);
        }

        $media = $work->media()->findOrFail($mediaId);
        $media->delete();

        return response()->json(['success' => true]);
    }
}
