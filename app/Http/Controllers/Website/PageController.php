<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Category;

class PageController extends Controller
{
    public function about()
    {
        return view('website.pages.about');
    }

    public function faq()
    {
        $faqs = \App\Models\Faq::where('is_active', true)->orderBy('sort_order')->get();
        return view('website.pages.faq', compact('faqs'));
    }

    public function contact()
    {
        return view('website.pages.contact');
    }

    public function storeContact(Request $request)
    {
        $request->validate([
            'type' => 'required|in:inquiry,help,other',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string'
        ]);

        $contact = Contact::create($request->all());

        // Notify admins
        \App\Services\NotificationService::createAdminNotification(
            'new_contact',
            'رسالة تواصل جديدة',
            "رسالة جديدة من: " . $contact->name,
            route('contacts.show', $contact->id)
        );

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', __('admin.contact_received'));
    }

    public function show($slug)
    {
        $page = \App\Models\Page::where('slug', $slug)->firstOrFail();
        return view('website.pages.show', compact('page'));
    }
}
