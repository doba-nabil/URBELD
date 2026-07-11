<?php
namespace App\Http\Controllers\Admin;
use App\DataTables\ServiceRequestDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequestRequest;
use App\Http\Requests\Admin\ServiceRequestInspectionRequest;
use App\Http\Requests\Admin\ServiceRequestResponseRequest;
use App\Http\Requests\Admin\RatingRequest;
use App\Models\Category;
use App\Services\ServiceRequestService;
use App\Services\ServiceRequestResponseService;
use App\Services\ServiceRequestInspectionService;
use App\Services\RatingService;
use Illuminate\Http\Request;
class ServiceRequestController extends Controller
{
    public function __construct(
        private ServiceRequestService $serviceRequestService,
        private ServiceRequestResponseService $responseService,
        private ServiceRequestInspectionService $inspectionService,
        private RatingService $ratingService
    ) {}
    public function index(ServiceRequestDataTable $dataTable)
    {
        $categories = Category::whereNull('parent_id')->get();
        $statuses = [
            'under_review' => 'قيد المراجعة المبدئية',
            'pending' => 'طلب جديد / بانتظار العروض',
            'provider_accepted' => 'تم قبول العرض من مقدم الخدمة',
            'seeker_confirmed_provider' => 'تم تأكيد مقدم الخدمة من العميل',
            'inspection_scheduled' => 'موعد المعاينة مجدول',
            'inspection_done' => 'تمت المعاينة',
            'work_completed' => 'اكتمل العمل / التقييم',
            'completed' => 'مكتمل (مؤرشف)',
            'time_expired' => 'منتهي الوقت',
            'cancelled' => 'ملغى',
        ];
        return $dataTable->render('dashboard.service_requests.index', compact('categories', 'statuses'));
    }
    public function create()
    {
        $categories = Category::whereNull('parent_id')->get();
        $activityTypes = \App\Models\ActivityType::active()->get();
        $users = \App\Models\User::serviceSeekers()->get();
        $providers = \App\Models\User::serviceProviders()->get();
        return view('dashboard.service_requests.create', compact('categories', 'activityTypes', 'users', 'providers'));
    }
    public function store(ServiceRequestRequest $request)
    {
        try {
            $data = $request->validated();
            $serviceRequest = $this->serviceRequestService->create(
                $data,
                $request->file('blueprints'),
                $request->file('site_photos')
            );
            return redirect()->route('service-requests.index')->with('success', __('admin.save_success'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
    public function show($id)
    {
        $serviceRequest = $this->serviceRequestService->getById($id);
        return view('dashboard.service_requests.show', compact('serviceRequest'));
    }
    public function edit($id)
    {
        $serviceRequest = $this->serviceRequestService->getById($id);
        $categories = Category::whereNull('parent_id')->get();
        $activityTypes = \App\Models\ActivityType::active()->get();
        $users = \App\Models\User::serviceSeekers()->get();
        $providers = \App\Models\User::serviceProviders()->get();
        return view('dashboard.service_requests.edit', compact('serviceRequest', 'categories', 'activityTypes', 'users', 'providers'));
    }
    public function update(ServiceRequestRequest $request, $id)
    {
        try {
            $serviceRequest = $this->serviceRequestService->getById($id);
            $data = $request->validated();
            $this->serviceRequestService->update(
                $serviceRequest,
                $data,
                $request->file('blueprints'),
                $request->file('site_photos')
            );
            return redirect()->route('service-requests.index')->with('success', __('admin.update_success'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            $this->serviceRequestService->delete($id);
            return response()->json([
                'status' => 'success',
                'message' => __('admin.delete_success')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('admin.delete_error')
            ], 500);
        }
    }
    public function getProviderCategories($id)
    {
        $provider = \App\Models\User::serviceProviders()->with('categories')->findOrFail($id);
        $mainCategories = $provider->categories()->whereNull('parent_id')->get()->map(function($cat) {
            return [
                'id' => $cat->id,
                'name' => $cat->name
            ];
        });
        $subCategories = $provider->categories()->whereNotNull('parent_id')->get()->map(function($cat) {
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
    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:under_review,pending,provider_accepted,seeker_confirmed_provider,inspection_scheduled,inspection_done,completed,time_expired,cancelled'
        ]);
        try {
            $serviceRequest = $this->serviceRequestService->getById($id);
            $this->serviceRequestService->changeStatus($serviceRequest, $request->status);
            return response()->json([
                'status' => 'success',
                'message' => __('admin.update_success')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function approveResponse(Request $request, $id, $responseId)
    {
        try {
            $response = $this->responseService->getById($responseId);
            if ($response->status !== \App\Models\ServiceRequestResponse::STATUS_UNDER_REVIEW) {
                throw new \Exception(__('admin.offer_not_under_review'));
            }
            $response->update(['status' => \App\Models\ServiceRequestResponse::STATUS_PENDING]);
            return response()->json([
                'status' => 'success',
                'message' => __('admin.offer_approved_success')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function updateExpired()
    {
        try {
            $count = $this->serviceRequestService->updateExpiredRequests();
            return response()->json([
                'status' => 'success',
                'message' => __('admin.requests_updated', ['count' => $count]),
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function acceptResponse($id, $responseId)
    {
        try {
            $serviceRequest = $this->serviceRequestService->getById($id);
            $response = $this->responseService->getById($responseId);
            if ($response->service_request_id != $serviceRequest->id) {
                throw new \Exception(__('admin.response_not_belong_to_request'));
            }
            $this->responseService->accept($response);
            return response()->json([
                'status' => 'success',
                'message' => __('admin.response_accepted_chat_activated')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function scheduleInspection(ServiceRequestInspectionRequest $request, $id)
    {
        try {
            $serviceRequest = $this->serviceRequestService->getById($id);
            $response = $this->responseService->getById($request->response_id);
            $inspection = $this->inspectionService->schedule(
                $serviceRequest,
                $response,
                $request->validated()
            );
            return response()->json([
                'status' => 'success',
                'message' => __('admin.inspection_scheduled_successfully'),
                'inspection' => $inspection
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function completeInspection(Request $request, $id, $inspectionId)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000'
        ]);
        try {
            $inspection = $this->inspectionService->getById($inspectionId);
            if ($inspection->service_request_id != $id) {
                throw new \Exception(__('admin.inspection_not_belong_to_request'));
            }
            $this->inspectionService->complete($inspection, $request->notes);
            return response()->json([
                'status' => 'success',
                'message' => __('admin.inspection_completed_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function agree($id)
    {
        try {
            $serviceRequest = $this->serviceRequestService->getById($id);
            if ($serviceRequest->status !== ServiceRequest::STATUS_UNDER_INSPECTION) {
                throw new \Exception(__('admin.request_must_be_in_inspection_status'));
            }
            $this->serviceRequestService->changeStatus($serviceRequest, ServiceRequest::STATUS_AGREED);
            return response()->json([
                'status' => 'success',
                'message' => __('admin.agreement_made_work_started')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function complete(Request $request, $id)
    {
        $request->validate([
            'seeker_rating' => 'required|integer|min:1|max:5',
            'seeker_comment' => 'nullable|string|max:1000',
            'provider_rating' => 'required|integer|min:1|max:5',
            'provider_comment' => 'nullable|string|max:1000',
        ]);
        try {
            $serviceRequest = $this->serviceRequestService->getById($id);
            if ($serviceRequest->status !== ServiceRequest::STATUS_AGREED) {
                throw new \Exception(__('admin.request_must_be_agreed_status'));
            }
            $this->serviceRequestService->changeStatus($serviceRequest, ServiceRequest::STATUS_COMPLETED);
            $acceptedResponse = $serviceRequest->acceptedResponse;
            if (!$acceptedResponse) {
                throw new \Exception(__('admin.no_accepted_response_for_request'));
            }
            $this->ratingService->createMutualRating(
                $serviceRequest,
                $serviceRequest->user,
                $acceptedResponse->user,
                [
                    'rating' => $request->seeker_rating,
                    'comment' => $request->seeker_comment,
                ],
                [
                    'rating' => $request->provider_rating,
                    'comment' => $request->provider_comment,
                ]
            );
            return response()->json([
                'status' => 'success',
                'message' => __('admin.request_completed_ratings_added')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function updateResponse(ServiceRequestResponseRequest $request, $id, $responseId)
    {
        try {
            $response = $this->responseService->getById($responseId);
            if ($response->service_request_id != $id) {
                throw new \Exception(__('admin.response_not_belong_to_request'));
            }
            $this->responseService->update($response, $request->validated());
            return response()->json([
                'status' => 'success',
                'message' => __('admin.update_success')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
