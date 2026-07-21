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
        $stats = $this->tenderService->getStats($request);
        
        $categories = Category::active()->whereNull('parent_id')->where('supports_tenders', true)->get();
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
        $tender = Tender::with(['user', 'category', 'city', 'media'])
            ->where('request_key', $id)->orWhere('id', $id)->firstOrFail();
        
        $user = auth()->user();
        $hasApplied = $user ? $tender->applications()->where('user_id', $user->id)->exists() : false;
        $isSaved = $user ? $user->hasSavedTender($tender->id) : false;

        if ($user && $user->id === $tender->user_id) {
            $tender->load(['applications.user.city', 'applications.user.media']);
        }

        $hasRated = false;
        if ($user) {
            $hasRated = \App\Models\Rating::where('rater_id', $user->id)
                                          ->where('tender_id', $tender->id)
                                          ->exists();
        }

        return view('website.tenders.show', compact('tender', 'hasApplied', 'isSaved', 'hasRated'));
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

        $categories = Category::active()->whereNull('parent_id')->where('supports_tenders', true)->get();
        $cities = City::orderBy('name')->get();

        return view('website.tenders.create', compact('categories', 'cities'));
    }

    /**
     * Store a newly created tender.
     */
    public function store(StoreTenderRequest $request)
    {
        try {
            $tender = $this->tenderService->createTender(
                auth()->user(), 
                $request->validated(), 
                $request->file('files'), 
                $request->input('file_titles')
            );

            // Mark the 'add' payment as used if it exists
            $payment = \App\Models\TenderPayPerUse::where('user_id', auth()->id())
                ->where('type', 'add')
                ->where('status', 'paid')
                ->first();
            if ($payment) {
                $payment->update(['status' => 'used', 'tender_id' => $tender->id]);
            }

            return redirect()->route('website.tenders.index')->with('success', __('tenders.created_success'));

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('tenders.create_error') . $e->getMessage());
        }
    }

    /**
     * Show dedicated payment page.
     */
    public function paymentPage($type, $id = null)
    {
        $user = auth()->user();
        if ($type === 'apply') {
            $tender = Tender::findOrFail($id);
            $fee = \App\Models\Setting::getValue('tender_apply_fee', null, 0);
            return view('website.tenders.payment', compact('type', 'tender', 'fee'));
        } elseif ($type === 'add') {
            $fee = \App\Models\Setting::getValue('tender_add_fee', null, 0);
            return view('website.tenders.payment', compact('type', 'fee'));
        }
        
        abort(404);
    }

    /**
     * Handle payment to add a tender.
     */
    public function payToAdd(Request $request)
    {
        $request->validate([
            'transfer_name' => 'required|string|max:255',
            'transfer_number' => 'required|string|max:255',
        ]);

        \App\Models\TenderPayPerUse::create([
            'user_id' => auth()->id(),
            'tender_id' => null,
            'type' => 'add',
            'amount_paid' => \App\Models\Setting::getValue('tender_add_fee', null, 0),
            'status' => 'paid',
            'payment_reference' => $request->transfer_name . ' - ' . $request->transfer_number,
            'paid_at' => now(),
        ]);

        return redirect()->route('website.tenders.create')->with('success', 'تم تسجيل الدفع بنجاح، يمكنك الآن إضافة المناقصة.');
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

        if ($user->user_type === 'service_provider' && $user->active !== 'active' && $user->active !== '1' && $user->active !== 1) {
            return redirect()->route('profile.complete')->with('error', __('website.please_activate_membership_to_apply_error'));
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
        $user = auth()->user();

        if ($user->user_type === 'service_provider' && $user->active !== 'active' && $user->active !== '1' && $user->active !== 1) {
            return redirect()->route('profile.complete')->with('error', __('website.please_activate_membership_to_apply_error'));
        }
        
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
     * Process mock payment for a tender.
     */
    public function pay(Request $request, $id)
    {
        $tender = Tender::active()->findOrFail($id);
        $user = auth()->user();

        $request->validate([
            'transfer_name' => 'required|string|max:255',
            'receipt_number' => 'required|string|max:255',
        ]);

        \App\Models\TenderPayPerUse::updateOrCreate(
            ['user_id' => $user->id, 'tender_id' => $tender->id, 'type' => 'apply'],
            [
                'status' => 'paid',
                'amount_paid' => \App\Models\Setting::getValue('tender_pay_per_use_price', null, 0) ?? 0,
                'payment_reference' => 'Transfer: ' . $request->transfer_name . ' | Receipt: ' . $request->receipt_number,
                'paid_at' => now(),
            ]
        );

        return redirect()->route('website.tenders.apply', $tender->id)
            ->with('success', __('tenders.payment_successful') ?? 'تم تسجيل عملية الدفع بنجاح، يمكنك الآن تقديم عرضك.');
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

    /**
     * Accept a specific application/offer for the tender.
     */
    public function acceptApplication(Request $request, $id, $applicationId)
    {
        $tender = Tender::findOrFail($id);
        
        if ($tender->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $application = $tender->applications()->findOrFail($applicationId);

        $tender->update([
            'awarded_provider_id' => $application->user_id,
            'status' => Tender::STATUS_IN_PROGRESS,
            'accepted_at' => now(),
        ]);

        \App\Services\NotificationService::createNotification(
            $application->user_id,
            'tender_awarded',
            'تم قبول عرضك!',
            "لقد تم قبول عرضك للمناقصة: {$tender->title}، يرجى البدء في التنفيذ.",
            route('website.tenders.show', $tender->id),
            true
        );

        return back()->with('success', __('website.offer_accepted_successfully') ?? 'تم قبول العرض بنجاح وتحويل المناقصة إلى قيد التنفيذ');
    }

    /**
     * Mark the tender as completed.
     */
    public function completeWork(Request $request, $id)
    {
        $tender = Tender::findOrFail($id);
        
        // Owner marks as complete
        if ($tender->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if ($tender->status !== Tender::STATUS_IN_PROGRESS) {
            return back()->with('error', 'المناقصة ليست قيد التنفيذ');
        }

        $tender->update([
            'status' => Tender::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        if ($tender->awarded_provider_id) {
            \App\Services\NotificationService::createNotification(
                $tender->awarded_provider_id,
                'tender_completed',
                'اكتملت المناقصة',
                "قام صاحب المناقصة: {$tender->title} بإنهاء العمل وتأكيد الإستلام.",
                route('website.tenders.show', $tender->id),
                true
            );
        }

        return back()->with('success', __('website.work_completed_successfully') ?? 'تم تأكيد الانتهاء بنجاح. يرجى تقييم المورد.');
    }
}
