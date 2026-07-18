<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderRequestResponseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSupplier = $user->isSupplier();
        $isProvider = $user->isServiceProvider();
        
        $requests = collect();
        $supplyRequests = collect();

        if ($isProvider && !$isSupplier) {
            $requests = ServiceRequest::where('user_id', '!=', $user->id)
                ->whereHas('responses', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->with(['category', 'subCategory', 'awardedProvider', 'user'])
                ->with(['responses' => function($q) use ($user) {
                    $q->where('user_id', $user->id);
                }])
                ->latest()
                ->get();
        }

        if ($isSupplier) {
            $supplyRequests = \App\Models\SupplyRequest::where('user_id', '!=', $user->id)
                ->where(function($q) use ($user) {
                    $q->whereHas('responses', function($sq) use ($user) {
                        $sq->where('user_id', $user->id);
                    })->orWhere('status', 'open');
                })
                ->with(['city', 'user', 'responses' => function($q) use ($user) {
                    $q->where('user_id', $user->id);
                }])
                ->latest()
                ->get();
        }

        $pageTitle = 'الطلبات الواردة';
        $isIncomingPage = true;
        return view('website.profile.requests', compact('requests', 'supplyRequests', 'pageTitle', 'isIncomingPage'));
    }

    /**
     * Accept a pending service request within 48 hours.
     */
    public function accept(Request $request, ServiceRequestResponse $response)
    {
        if ($response->user_id !== Auth::id() || $response->status !== 'pending') {
            abort(403, 'Unauthorized or request no longer pending.');
        }

        // Check if 48 hours have passed
        if (now()->diffInHours($response->created_at) >= 48) {
            $response->update(['status' => ServiceRequestResponse::STATUS_TIMEOUT]);
            return back()->with('error', __('admin.deadline_48h_passed'));
        }

        $request->validate([
            'proposed_price' => 'nullable|numeric',
            'proposed_timeline' => 'nullable|string',
            'message' => 'nullable|string',
        ]);

        $response->update([
            'status' => 'accepted',
            'proposed_price' => $request->proposed_price,
            'proposed_timeline' => $request->proposed_timeline,
            'message' => $request->message ?? '',
            'responded_at' => now(),
        ]);

        // Stage 4: Notify the Seeker that a provider has expressed interest
        $seeker = $response->serviceRequest->user;
        if ($seeker) {
            $seeker->notify(new \App\Notifications\ServiceRequestResponseNotification(
                $response->serviceRequest,
                $response,
                'new_response'
                ));
        }

        return back()->with('success', __('admin.response_accepted_seeker_notified'));
    }

    /**
     * Reject a pending service request.
     */
    public function reject(ServiceRequestResponse $response)
    {
        if ($response->user_id !== Auth::id() || $response->status !== 'pending') {
            abort(403, 'Unauthorized or request no longer pending.');
        }

        // Check if 48 hours have passed
        if (now()->diffInHours($response->created_at) >= 48) {
            $response->update(['status' => ServiceRequestResponse::STATUS_TIMEOUT]);
            return back()->with('error', __('admin.deadline_48h_passed'));
        }

        $response->update([
            'status' => 'rejected',
            'responded_at' => now(),
        ]);

        return back()->with('success', __('admin.response_rejected_success'));
    }

    /**
     * Provider schedules the inspection after being accepted.
     */
    public function scheduleInspection(Request $request, \App\Models\ServiceRequest $serviceRequest)
    {
        $request->validate([
            'inspection_date' => 'required|date|after:now',
            'notes' => 'nullable|string',
        ]);

        // Ensure this provider is the awarded provider
        if ($serviceRequest->awarded_provider_id !== Auth::id() || !in_array($serviceRequest->status, ['provider_accepted', 'seeker_confirmed_provider'])) {
            abort(403, 'Unauthorized to schedule inspection for this request.');
        }

        $serviceRequest->inspections()->create([
            'user_id' => Auth::id(),
            'scheduled_at' => $request->inspection_date,
            'notes' => $request->notes,
            'status' => 'scheduled',
        ]);

        $serviceRequest->update([
            'inspection_date' => $request->inspection_date,
            'status' => \App\Models\ServiceRequest::STATUS_INSPECTION_SCHEDULED,
        ]);

        // Notify Seeker
        if ($serviceRequest->user) {
            $serviceRequest->user->notify(new \App\Notifications\InspectionScheduledNotification($serviceRequest));
        }

        return back()->with('success', __('admin.inspection_scheduled_success'));
    }

    /**
     * Provider ignores/rejects a request without submitting an offer.
     */
    public function ignore(Request $request, ServiceRequest $serviceRequest)
    {
        ServiceRequestResponse::updateOrCreate(
            [
                'service_request_id' => $serviceRequest->id,
                'user_id' => Auth::id()
            ],
            [
                'status' => 'rejected',
                'message' => $request->input('message', ''),
                'responded_at' => now(),
            ]
        );

        return back()->with('success', __('admin.response_rejected_success'));
    }
}
