<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Tender;
use App\Services\TenderService;
use App\Http\Requests\Website\Tender\StoreTenderRequest;
use App\Http\Requests\Website\Tender\StoreTenderApplicationRequest;
use Illuminate\Http\Request;

class TenderController extends Controller
{
    protected TenderService $tenderService;

    public function __construct(TenderService $tenderService)
    {
        $this->tenderService = $tenderService;
    }

    /**
     * Display a listing of tenders with filters.
     */
    public function index(Request $request)
    {
        $tenders = $this->tenderService->getFilteredTenders($request);
        $stats = $this->tenderService->getStats();
        
        $categories = Category::active()->whereNull('parent_id')->get();
        $cities = City::orderBy('name')->get();
        $tab = $request->get('tab', 'all');
        $sort = $request->get('sort', 'latest');

        return view('website.tenders.index', compact('tenders', 'categories', 'cities', 'stats', 'tab', 'sort'));
    }

    /**
     * Show a single tender details.
     */
    public function show($id)
    {
        $tender = Tender::with(['user', 'category', 'city', 'media'])->findOrFail($id);
        
        $user = auth()->user();
        $hasApplied = $user ? $tender->applications()->where('user_id', $user->id)->exists() : false;
        $isSaved = $user ? $user->hasSavedTender($tender->id) : false;

        return view('website.tenders.show', compact('tender', 'hasApplied', 'isSaved'));
    }

    /**
     * Show form to create a new tender.
     */
    public function create()
    {
        $user = auth()->user();
        
        if (!$user->canPostTender()) {
            return redirect()->route('website.tenders.index')->with('error_popup', 'subscription_required');
        }

        $categories = Category::active()->whereNull('parent_id')->get();
        $cities = City::orderBy('name')->get();

        return view('website.tenders.create', compact('categories', 'cities'));
    }

    /**
     * Store a newly created tender.
     */
    public function store(StoreTenderRequest $request)
    {
        try {
            $this->tenderService->createTender(
                auth()->user(), 
                $request->validated(), 
                $request->file('files'), 
                $request->input('file_titles')
            );

            return redirect()->route('website.tenders.index')->with('success', __('tenders.created_success'));

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('tenders.create_error') . $e->getMessage());
        }
    }

    /**
     * Show form to apply for a tender.
     */
    public function apply($id)
    {
        $tender = Tender::active()->findOrFail($id);
        $user = auth()->user();

        if ($tender->isExpired()) {
            return redirect()->route('website.tenders.show', $tender->id)->with('error', __('tenders.expired_error'));
        }

        if ($tender->user_id == $user->id) {
            return redirect()->route('website.tenders.show', $tender->id)->with('error', __('tenders.own_tender_error'));
        }

        if (!$user->canApplyToTender($tender->id)) {
            return redirect()->route('website.tenders.show', $tender->id)->with('error_popup', 'payment_or_subscription_required');
        }

        return view('website.tenders.apply', compact('tender'));
    }

    /**
     * Store application for a tender.
     */
    public function storeApplication(StoreTenderApplicationRequest $request, $id)
    {
        $tender = Tender::active()->findOrFail($id);
        
        try {
            $this->tenderService->applyToTender(
                $tender, 
                auth()->user(), 
                $request->validated(), 
                $request->file('files'), 
                $request->input('file_titles')
            );

            return redirect()->route('website.tenders.show', $tender->id)->with('success', __('tenders.applied_success'));

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('tenders.apply_error') . $e->getMessage());
        }
    }

    /**
     * Toggle save/bookmark for a tender.
     */
    public function toggleSave($id)
    {
        $tender = Tender::findOrFail($id);
        $result = $this->tenderService->toggleSaveTender($tender, auth()->user());
        
        // Translating the messages from the service
        $result['message'] = $result['status'] === 'saved' ? __('tenders.saved_success') : __('tenders.removed_success');
        
        return response()->json($result);
    }
}
