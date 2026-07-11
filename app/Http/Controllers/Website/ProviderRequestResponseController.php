<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderRequestResponseController extends Controller
{
    /**
     * Display a listing of pending service requests for the provider.
     */
    public function index()
    {
        $responses = ServiceRequestResponse::with(['serviceRequest.user', 'serviceRequest.category', 'serviceRequest.media'])
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->get();

        $activeResponses = ServiceRequestResponse::with(['serviceRequest.user', 'serviceRequest.category', 'serviceRequest.awardedProvider', 'serviceRequest.chat'])
            ->where('user_id', Auth::id())
            ->where('status', 'accepted')
            ->whereHas('serviceRequest', function ($query) {
            $query->whereIn('status', ['provider_accepted', 'inspection_scheduled', 'inspection_done', 'work_completed', 'completed']);
        })
            ->get();

        // Supply requests: only for suppliers
        $supplyRequests = collect();
        if (Auth::user()->isSupplier()) {
            $supplyRequests = \App\Models\SupplyRequest::with(['user', 'city', 'responses' => function($q) {
                $q->where('user_id', Auth::id());
            }])
            ->where(function($q) {
                $q->whereDoesntHave('responses', function($sq) {
                    $sq->where('user_id', Auth::id())->where('status', 'accepted');
                })->where('status', 'open');
            })
            ->latest()
            ->get();
        }

        return view('website.provider.requests.index', compact('responses', 'activeResponses', 'supplyRequests'));
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
        ]);

        // Ensure this provider is the awarded provider
        if ($serviceRequest->awarded_provider_id !== Auth::id() || $serviceRequest->status !== 'provider_accepted') {
            abort(403, 'Unauthorized to schedule inspection for this request.');
        }

        $serviceRequest->update([
            'inspection_date' => $request->inspection_date,
            'status' => ServiceRequest::STATUS_INSPECTION_SCHEDULED,
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
    public function ignore(ServiceRequest $serviceRequest)
    {
        ServiceRequestResponse::updateOrCreate(
            [
                'service_request_id' => $serviceRequest->id,
                'user_id' => Auth::id()
            ],
            [
                'status' => 'rejected',
                'responded_at' => now(),
            ]
        );

        return back()->with('success', __('admin.response_rejected_success'));
    }
}
