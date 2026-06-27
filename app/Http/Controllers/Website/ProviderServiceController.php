<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProviderServiceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (Auth::user()->provider_type === 'individual') {
                    return redirect()->route('profile.edit')->with('error', __('website.only_companies_can_add_services') ?? 'هذه الخاصية متاحة للشركات والمؤسسات فقط.');
                }
                return $next($request);
            }),
        ];
    }

    public function index()

    {
        $services = Service::where('user_id', Auth::id())->with(['category', 'subCategory'])->latest()->get();
        return view('website.provider.services.index', compact('services'));
    }

    public function create()
    {
        $user = Auth::user();

        // Get main categories assigned to user
        $mainCategories = $user->categories()->whereNull('parent_id')->get();

        // Get sub categories assigned to user
        $subCategories = $user->categories()->whereNotNull('parent_id')->get();

        // If no main categories are directly assigned, get the parents of the assigned subcategories
        if ($mainCategories->isEmpty() && $subCategories->isNotEmpty()) {
            $parentIds = $subCategories->pluck('parent_id')->unique();
            $mainCategories = \App\Models\Category::whereIn('id', $parentIds)->get();
        }

        return view('website.provider.services.create', compact('mainCategories', 'subCategories'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Check for max services limit
        if ($user->subscriptionPackage && $user->subscriptionPackage->max_services > 0) {
            $currentCount = Service::where('user_id', $user->id)->count();
            if ($currentCount >= $user->subscriptionPackage->max_services) {
                return back()->with('error', __('admin.services_limit_reached') ?? 'لقد وصلت للحد الأقصى للخدمات المسموح بها في باقتك.');
            }
        }

        $request->validate([
            'title.ar' => 'required|string|max:255',
            'title.en' => 'nullable|string|max:255',
            'content.ar' => 'required|string',
            'content.en' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $service = new Service();
        $service->user_id = $user->id;
        $service->category_id = $request->category_id;
        $service->sub_category_id = $request->sub_category_id;
        $service->setTranslations('title', (array)$request->input('title'));
        $service->setTranslations('content', (array)$request->input('content'));
        $service->is_active = $request->has('is_active') ? true : false;
        $service->sort_order = 0;
        $service->save();

        if ($request->hasFile('image')) {
            $service->addMedia($request->file('image'))->toMediaCollection('services');
        }

        return redirect()->route('provider.services.index')->with('success', __('admin.save_success'));
    }

    public function edit(Service $service)
    {
        if ($service->user_id !== Auth::id()) {
            abort(403);
        }

        $user = Auth::user();

        // Get main categories assigned to user
        $mainCategories = $user->categories()->whereNull('parent_id')->get();

        // Get sub categories assigned to user
        $subCategories = $user->categories()->whereNotNull('parent_id')->get();

        // If no main categories are directly assigned, get the parents of the assigned subcategories
        if ($mainCategories->isEmpty() && $subCategories->isNotEmpty()) {
            $parentIds = $subCategories->pluck('parent_id')->unique();
            $mainCategories = \App\Models\Category::whereIn('id', $parentIds)->get();
        }

        return view('website.provider.services.edit', compact('service', 'mainCategories', 'subCategories'));
    }

    public function update(Request $request, Service $service)
    {
        if ($service->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title.ar' => 'required|string|max:255',
            'title.en' => 'nullable|string|max:255',
            'content.ar' => 'required|string',
            'content.en' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $service->category_id = $request->category_id;
        $service->sub_category_id = $request->sub_category_id;
        $service->setTranslations('title', (array)$request->input('title'));
        $service->setTranslations('content', (array)$request->input('content'));
        $service->is_active = $request->has('is_active') ? true : false;
        $service->save();

        if ($request->hasFile('image')) {
            $service->addMedia($request->file('image'))->toMediaCollection('services');
        }

        return redirect()->route('provider.services.index')->with('success', __('admin.update_success'));
    }

    public function destroy(Service $service)
    {
        if ($service->user_id !== Auth::id()) {
            abort(403);
        }

        $service->delete();
        return back()->with('success', __('admin.delete_success'));
    }
}
