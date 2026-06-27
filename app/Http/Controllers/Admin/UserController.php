<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CategoryDataTable;
use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Http\Requests\Admin\UserRequest;
use App\Models\City;
use App\Models\Membership;
use App\Services\CategoryService;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}


    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('dashboard.users.index');
    }

    public function create()
    {
        return view('dashboard.users.create');
    }

    public function store(UserRequest $request)
    {
        $this->userService->create(
            $request->validated(),
            $request->file('image')
        );
        return redirect()->route('users.index')->with('success', __('admin.save_success'));
    }

    public function show($id)
    {
        $user = $this->userService->getById($id);
        $user->load(['membership', 'city', 'categories', 'serviceRequests', 'serviceRequestResponses']);
        
        // Statistics
        $stats = [
            'total_requests' => $user->serviceRequests()->count(),
            'total_responses' => $user->serviceRequestResponses()->count(),
            'accepted_responses' => $user->serviceRequestResponses()->where('status', 'accepted')->count(),
            'average_rating' => $user->ratingsReceived()->avg('rating') ?? 0,
        ];
        
        return view('dashboard.users.show', compact('user', 'stats'));
    }

    public function edit($id)
    {
        $user = $this->userService->getById($id);

        if ($user->isServiceProvider()) {
            return redirect()->route('memberships.edit', $id);
        }

        return view('dashboard.users.edit', compact('user'));
    }

    public function update(UserRequest $request, $id)
    {
        $user = $this->userService->getById($id);
        $this->userService->update($user, $request->validated(), $request->file('image'));
        return redirect()->route('users.index')->with('success', __('admin.update_success'));
    }

    public function destroy($id)
    {
        try {
            $this->userService->delete($id);

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

}
