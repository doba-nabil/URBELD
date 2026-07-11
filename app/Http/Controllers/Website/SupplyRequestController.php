<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupplyRequestController extends Controller
{
    public function index(Request $request)
    {
        $requests = \App\Models\SupplyRequest::with(['user', 'city'])
            ->latest()
            ->paginate(12);

        return view('website.supply_requests.index', compact('requests'));
    }

    public function show($id)
    {
        $supplyRequest = \App\Models\SupplyRequest::with(['user', 'city', 'responses.user'])->findOrFail($id);
        return view('website.supply_requests.show', compact('supplyRequest'));
    }

    public function create()
    {
        $cities = \App\Models\City::orderBy('name')->get();
        return view('website.supply_requests.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'delivery_date' => 'nullable|date',
        ]);

        $supplyRequest = new \App\Models\SupplyRequest($validated);
        $supplyRequest->user_id = auth()->id();
        $supplyRequest->status = 'open';
        $supplyRequest->save();

        return redirect()->route('website.supply-requests.index')->with('success', 'تم إضافة طلب التوريد بنجاح');
    }

    public function storeApplication(Request $request, $id)
    {
        $supplyRequest = \App\Models\SupplyRequest::findOrFail($id);
        
        $validated = $request->validate([
            'proposed_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $response = new \App\Models\SupplyRequestResponse($validated);
        $response->user_id = auth()->id();
        $response->supply_request_id = $supplyRequest->id;
        $response->status = 'pending';
        $response->save();

        return redirect()->route('website.supply-requests.show', $supplyRequest->id)->with('success', 'تم تقديم العرض بنجاح');
    }

    public function acceptApplication(Request $request, $id, $applicationId)
    {
        $supplyRequest = \App\Models\SupplyRequest::findOrFail($id);
        
        if ($supplyRequest->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $application = $supplyRequest->responses()->findOrFail($applicationId);

        $supplyRequest->update([
            'awarded_provider_id' => $application->user_id,
            'status' => \App\Models\SupplyRequest::STATUS_IN_PROGRESS,
            'accepted_at' => now(),
        ]);

        return back()->with('success', __('website.offer_accepted_successfully') ?? 'تم قبول العرض بنجاح وتحويل الطلب إلى قيد التنفيذ');
    }

    public function completeWork(Request $request, $id)
    {
        $supplyRequest = \App\Models\SupplyRequest::findOrFail($id);
        
        if ($supplyRequest->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if ($supplyRequest->status !== \App\Models\SupplyRequest::STATUS_IN_PROGRESS) {
            return back()->with('error', 'الطلب ليس قيد التنفيذ');
        }

        $supplyRequest->update([
            'status' => \App\Models\SupplyRequest::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        return back()->with('success', __('website.work_completed_successfully') ?? 'تم تأكيد الانتهاء بنجاح. يرجى تقييم المورد.');
    }
}
