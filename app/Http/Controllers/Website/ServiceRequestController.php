<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceRequestController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $categories = Category::with('children')->whereNull('parent_id')->where('is_active', true)->get();
        $activityTypes = \App\Models\ActivityType::where('is_active', true)->ordered()->get();

        $provider = null;
        $service = null;

        if ($request->has('service_id')) {
            $service = \App\Models\Service::with(['user', 'category', 'subCategory'])->findOrFail($request->service_id);
            $provider = $service->user;
        } elseif ($request->has('provider_id')) {
            $provider = \App\Models\User::where('user_type', 'service_provider')->findOrFail($request->provider_id);
        }

        return view('website.requests.create', compact('categories', 'activityTypes', 'provider', 'service'));
    }

    public function getProviderCategories(\App\Models\User $user)
    {
        // Get main categories (parents) that the provider belongs to
        $mainCategories = $user->categories()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->get(['categories.id', 'categories.name'])
            ->map(function($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name // Evaluated string
                ];
            });

        // Get sub categories (children) that the provider belongs to
        $subCategories = $user->categories()
            ->whereNotNull('parent_id')
            ->where('is_active', true)
            ->get(['categories.id', 'categories.name', 'categories.parent_id'])
            ->map(function($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'parent_id' => $cat->parent_id
                ];
            });

        return response()->json([
            'status' => 'success',
            'main_categories' => $mainCategories,
            'sub_categories' => $subCategories,
        ]);
    }

    public function store(\App\Http\Requests\StoreServiceRequest $request, \App\Actions\ServiceRequests\CreateWebsiteServiceRequestAction $action)
    {
        try {
            $data = $request->validated();
            
            $serviceRequest = $action->execute($data, $request, Auth::id());

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('admin.request_submitted_success'),
                    'redirect' => route('requests.show', $serviceRequest->id)
                ]);
            }

            return redirect()->route('requests.show', $serviceRequest->id)
                ->with('success', __('admin.request_submitted_success'));

        }
        catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', __('admin.request_submit_error') . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     * Note: Route parameter is {request} from resource route, so we use $request name
     * but type-hint ServiceRequest for implicit model binding.
     */
    public function show(Request $httpRequest, $id)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);
        $user = Auth::user();

        // Authorization: Check if the user can view this request
        $canView = false;

        // 1. The request owner (seeker) can ALWAYS view their own request
        if ($serviceRequest->user_id == $user->id) {
            $canView = true;
        }

        // 2. The awarded provider can ALWAYS view the request
        if ($serviceRequest->awarded_provider_id == $user->id) {
            $canView = true;
        }

        // 3. Admin can view anything
        if ($user->is_admin) {
            $canView = true;
        }

        // 4. A service provider who has a response (pending, accepted, or rejected) can view
        if ($user->isServiceProvider()) {
            $hasResponse = $serviceRequest->responses()->where('user_id', $user->id)->exists();
            if ($hasResponse) {
                $canView = true;
            }
            // Also allow viewing if the request is still pending and they share the category or sub-category
            if ($serviceRequest->status === ServiceRequest::STATUS_PENDING) {
                $categoryIds = array_filter([$serviceRequest->category_id, $serviceRequest->sub_category_id]);
                $sharesCategory = $user->categories()->whereIn('categories.id', $categoryIds)->exists();
                if ($sharesCategory) {
                    $canView = true;
                }
            }
            
            // Note: Service Providers CANNOT see under_review requests unless they are the owner (which shouldn't happen)
        }

        if (!$canView) {
            abort(403, __('admin.no_permission_view_request'));
        }

        $serviceRequest->load(['category', 'user', 'responses.user', 'inspections', 'awardedProvider', 'media']);

        // Load the chat for this service request (if exists)
        $chat = \App\Models\Chat::where('service_request_id', $serviceRequest->id)
            ->whereHas('participants', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        })->first();

        return view('website.seeker.requests.show', compact('serviceRequest', 'chat'));
    }

    public function respond(Request $request, $id)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);

        $request->validate([
            'proposed_price' => 'required|numeric',
            'proposed_timeline' => 'required|string',
            'message' => 'required|string',
        ]);

        if ($serviceRequest->user_id == Auth::id()) {
            return back()->with('error', __('admin.cannot_respond_own_request'));
        }

        // Prevent duplicate responses
        if ($serviceRequest->responses()->where('user_id', Auth::id())->exists()) {
            return back()->with('error', __('admin.already_responded'));
        }

        $response = $serviceRequest->responses()->create([
            'user_id' => Auth::id(),
            'proposed_price' => $request->proposed_price,
            'proposed_timeline' => $request->proposed_timeline,
            'message' => $request->message,
            'status' => ServiceRequestResponse::STATUS_UNDER_REVIEW,
        ]);

        // Seeker notification moved to ServiceRequestResponseObserver::updated

        return back()->with('success', __('admin.offer_submitted_success'));
    }

    /**
     * User accepts a specific provider for their service request.
     */
    public function acceptProvider($serviceRequestId, \App\Models\User $provider)
    {
        $serviceRequest = ServiceRequest::findOrFail($serviceRequestId);

        if ($serviceRequest->user_id != Auth::id()) {
            abort(403);
        }

        // Validate that this provider actually accepted the request
        $acceptedResponse = $serviceRequest->responses()
            ->where('user_id', $provider->id)
            ->where('status', 'accepted')
            ->firstOrFail();

        $chat = null;

        DB::transaction(function () use ($serviceRequest, $provider, $acceptedResponse, &$chat) {
            // 1. Update service_request
            $serviceRequest->update([
                'status' => ServiceRequest::STATUS_PROVIDER_ACCEPTED,
                'awarded_provider_id' => $provider->id,
            ]);

            // 2. Reject other responses
            $serviceRequest->responses()
                ->where('id', '!=', $acceptedResponse->id)
                ->update(['status' => 'rejected']);

            // 3. Automatically create a Chat
            $chat = \App\Models\Chat::create([
                'from_user_id' => Auth::id(),
                'to_user_id' => $provider->id,
                'service_request_id' => $serviceRequest->id,
                'active' => true,
            ]);

            $chat->participants()->attach([
                Auth::id(), // Seeker
                $provider->id // Provider
            ]);

            // 4. Notify Provider that they were selected
            $provider->notify(new \App\Notifications\ProviderAcceptedNotification($acceptedResponse));
        });

        // Redirect to the actual chat (FIX: was redirecting to dashboard.chat.index without ID)
        if ($chat) {
            return redirect()->route('dashboard.chat.show', ['chat' => $chat->id])
                ->with('success', __('admin.provider_accepted_chat_opened'));
        }

        return redirect()->route('requests.show', $serviceRequest->id)
            ->with('success', __('admin.provider_accepted_success'));
    }

    public function confirmSeeker($id)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);

        if ($serviceRequest->user_id != Auth::id()) {
            abort(403);
        }

        $serviceRequest->update(['status' => ServiceRequest::STATUS_SEEKER_CONFIRMED]);

        return back()->with('success', __('admin.work_agreement_confirmed'));
    }

    public function confirmAgreement($id)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);

        if ($serviceRequest->user_id != Auth::id()) {
            abort(403);
        }

        if ($serviceRequest->status !== ServiceRequest::STATUS_INSPECTION_DONE) {
            return back()->with('error', __('admin.request_must_be_in_inspection_status'));
        }

        $serviceRequest->update(['status' => ServiceRequest::STATUS_WORK_COMPLETED]);

        return back()->with('success', __('admin.agreement_made_work_started'));
    }

    public function completeInspection(\App\Models\ServiceRequestInspection $inspection)
    {
        // Authorization: Only the provider who scheduled it
        if ($inspection->user_id != Auth::id()) {
            abort(403);
        }

        $inspection->update(['status' => 'completed', 'completed_at' => now()]);
        
        $serviceRequest = $inspection->serviceRequest;
        $serviceRequest->update(['status' => ServiceRequest::STATUS_INSPECTION_DONE]);

        return back()->with('success', __('admin.inspection_completed_success'));
    }

    public function scheduleInspection(Request $request, $id)
    {
        // Moved to ProviderRequestResponseController
        abort(404);
    }

    public function completeWork($id)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);

        if ($serviceRequest->user_id != Auth::id() && $serviceRequest->awarded_provider_id != Auth::id()) {
            abort(403, __('admin.no_permission_confirm_request'));
        }

        $serviceRequest->update(['status' => 'work_completed']);

        return back()->with('success', __('admin.work_completed_success'));
    }

    public function startChat($id, \App\Services\ChatService $chatService)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);
        $acceptedResponse = $serviceRequest->responses()->where('status', 'accepted')->first();

        if (!$acceptedResponse) {
            return back()->with('error', __('admin.no_accepted_provider'));
        }

        $otherUserId = (Auth::id() == $serviceRequest->user_id) ? $acceptedResponse->user_id : $serviceRequest->user_id;
        $otherUser = \App\Models\User::findOrFail($otherUserId);

        $chat = $chatService->getOrCreateChat(Auth::user(), $otherUser);

        // FIX: was using ['id' => $chat->id], route expects {chat}
        return redirect()->route('dashboard.chat.show', ['chat' => $chat->id]);
    }

    /**
     * Soft-delete a service request (only by the owner)
     */
    public function destroy($id)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);

        if ($serviceRequest->user_id != Auth::id()) {
            abort(403);
        }

        $serviceRequest->delete();

        return redirect()->route('profile.requests')->with('success', __('admin.request_deleted_success'));
    }
}
