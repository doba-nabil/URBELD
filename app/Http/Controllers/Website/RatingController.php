<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    /**
     * Store a newly created rating in storage.
     */
    public function store(\App\Http\Requests\StoreRatingRequest $request, ServiceRequest $serviceRequest)
    {
        // Must be completed
        if (!in_array($serviceRequest->status, ['work_completed', 'completed'])) {
            abort(403, __('admin.cannot_rate_incomplete_service'));
        }

        // Determine who is rating who
        $raterId = Auth::id();
        $isSeeker = ($serviceRequest->user_id === $raterId);
        $isProvider = ($serviceRequest->awarded_provider_id === $raterId);

        if (!$isSeeker && !$isProvider) {
            abort(403);
        }

        $ratedId = $isSeeker ? $serviceRequest->awarded_provider_id : $serviceRequest->user_id;

        // Prevent duplicate ratings
        if (Rating::where('rater_id', $raterId)->where('service_request_id', $serviceRequest->id)->exists()) {
            return back()->with('error', __('admin.already_rated'));
        }

        $data = $request->validated();

        Rating::create([
            'service_request_id' => $serviceRequest->id,
            'rater_id' => $raterId,
            'rated_id' => $ratedId,
            'rating' => $data['score'],
            'comment' => $data['comment'] ?? null,
        ]);

        return back()->with('success', __('admin.rating_submitted_success'));
    }
}
