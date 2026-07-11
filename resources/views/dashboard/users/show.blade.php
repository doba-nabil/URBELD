@extends('dashboard.layout.master')

@section('title', 'Membership Profile')

@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">Membership Profile: {{ $user->name }}</h4>
            <div>
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">
                    <i class="icon-base ti tabler-edit"></i> Edit
                </a>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="icon-base ti tabler-arrow-right"></i> Back
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Profile Info -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Profile Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            @if($user->isIndividual() && $user->getFirstMediaUrl('personal_photo'))
                                <img src="{{ $user->getFirstMediaUrl('personal_photo') }}" 
                                     alt="Personal Photo" 
                                     class="rounded-circle" 
                                     width="150" 
                                     height="150"
                                     style="object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center" 
                                     style="width: 150px; height: 150px;">
                                    <i class="ti tabler-user fs-1 text-white"></i>
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <strong>Name:</strong>
                            <p>{{ $user->name }}</p>
                        </div>

                        <div class="mb-3">
                            <strong>Email:</strong>
                            <p>{{ $user->email }}</p>
                        </div>

                        <div class="mb-3">
                            <strong>Phone:</strong>
                            <p>{{ $user->phone ?? '-' }}</p>
                        </div>

                        <div class="mb-3">
                            <strong>Membership Type:</strong>
                            <p>
                                <span class="badge {{ $user->isCompany() ? 'bg-label-primary' : 'bg-label-info' }}">
                                    {{ $user->isCompany() ? 'Company' : 'Individual' }}
                                </span>
                            </p>
                        </div>

                        @if($user->isCompany() || $user->provider_type == 'company')
                        <div class="mb-3">
                            <strong>Company Registration Number:</strong>
                            <p>{{ $user->company_registration_number ?? '-' }}</p>
                        </div>

                        <div class="mb-3">
                            <strong>Representative Name:</strong>
                            <p>{{ $user->representative_name ?? '-' }}</p>
                        </div>
                        @endif

                        @if($user->membership)
                        <div class="mb-3">
                            <strong>Membership:</strong>
                            <p>{{ $user->membership->name }}</p>
                        </div>
                        @endif

                        @if($user->city)
                        <div class="mb-3">
                            <strong>City:</strong>
                            <p>{{ $user->city->name }}</p>
                        </div>
                        @endif

                        @if($user->bio)
                        <div class="mb-3">
                            <strong>Bio:</strong>
                            <p>{{ $user->bio }}</p>
                        </div>
                        @endif

                        <div class="mb-3">
                            <strong>Status:</strong>
                            <p>
                                <span class="badge {{ $user->active ? 'bg-label-success' : 'bg-label-danger' }}">
                                    {{ $user->active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>

                        @if($user->hasActiveMembership())
                        <div class="mb-3">
                            <strong>Membership Expires:</strong>
                            <p class="{{ $user->membership_expires_at && $user->membership_expires_at->isPast() ? 'text-danger' : 'text-success' }}">
                                {{ $user->membership_expires_at ? $user->membership_expires_at->format('Y-m-d') : '-' }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Categories -->
                @if($user->categories->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Categories</h5>
                    </div>
                    <div class="card-body">
                        @foreach($user->categories as $category)
                            <span class="badge bg-label-primary me-1 mb-1">{{ $category->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Files -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Files & Documents</h5>
                    </div>
                    <div class="card-body">
                        @if($user->isIndividual())
                            @if($user->getMedia('certificates')->count() > 0)
                                <div class="mb-3">
                                    <strong>Certificates:</strong>
                                    <ul class="list-unstyled">
                                        @foreach($user->getMedia('certificates') as $media)
                                            <li>
                                                <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="ti tabler-file"></i> {{ $media->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @else
                            @if($user->getMedia('commercial_registration')->count() > 0)
                                <div class="mb-3">
                                    <strong>Commercial Registration:</strong>
                                    <ul class="list-unstyled">
                                        @foreach($user->getMedia('commercial_registration') as $media)
                                            <li>
                                                <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="ti tabler-file"></i> {{ $media->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if($user->getMedia('company_files')->count() > 0)
                                <div class="mb-3">
                                    <strong>Company Files:</strong>
                                    <ul class="list-unstyled">
                                        @foreach($user->getMedia('company_files') as $media)
                                            <li>
                                                <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="ti tabler-file"></i> {{ $media->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if($user->getMedia('certificates')->count() > 0)
                                <div class="mb-3">
                                    <strong>Certificates:</strong>
                                    <ul class="list-unstyled">
                                        @foreach($user->getMedia('certificates') as $media)
                                            <li>
                                                <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="ti tabler-file"></i> {{ $media->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Statistics & Activities -->
            <div class="col-md-8">
                <!-- Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="avatar mx-auto mb-2">
                                    <span class="avatar-initial rounded bg-label-primary">
                                        <i class="ti tabler-file-text fs-4"></i>
                                    </span>
                                </div>
                                <h4 class="mb-0">{{ $stats['total_requests'] }}</h4>
                                <small class="text-muted">Total Requests</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="avatar mx-auto mb-2">
                                    <span class="avatar-initial rounded bg-label-info">
                                        <i class="ti tabler-message fs-4"></i>
                                    </span>
                                </div>
                                <h4 class="mb-0">{{ $stats['total_responses'] }}</h4>
                                <small class="text-muted">Total Responses</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="avatar mx-auto mb-2">
                                    <span class="avatar-initial rounded bg-label-success">
                                        <i class="ti tabler-check fs-4"></i>
                                    </span>
                                </div>
                                <h4 class="mb-0">{{ $stats['accepted_responses'] }}</h4>
                                <small class="text-muted">Accepted</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="avatar mx-auto mb-2">
                                    <span class="avatar-initial rounded bg-label-warning">
                                        <i class="ti tabler-star fs-4"></i>
                                    </span>
                                </div>
                                <h4 class="mb-0">{{ number_format($stats['average_rating'], 1) }}</h4>
                                <small class="text-muted">Avg Rating</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Requests -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Service Requests</h5>
                        <a href="{{ route('service-requests.index', ['user_id' => $user->id]) }}" class="btn btn-sm btn-primary">
                            View All
                        </a>
                    </div>
                    <div class="card-body">
                        @if($user->serviceRequests->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->serviceRequests->take(5) as $request)
                                            <tr>
                                                <td>#{{ $request->id }}</td>
                                                <td>{{ $request->category->name ?? '-' }}</td>
                                                <td>
                                                    @php
                                                        $statuses = [
                                                            'new' => ['label' => 'New', 'class' => 'badge bg-label-primary'],
                                                            'pending_response' => ['label' => 'Pending', 'class' => 'badge bg-label-warning'],
                                                            'accepted' => ['label' => 'Accepted', 'class' => 'badge bg-label-success'],
                                                            'rejected' => ['label' => 'Rejected', 'class' => 'badge bg-label-danger'],
                                                            'time_expired' => ['label' => 'Expired', 'class' => 'badge bg-label-secondary'],
                                                            'under_inspection' => ['label' => 'Inspection', 'class' => 'badge bg-label-info'],
                                                            'agreed' => ['label' => 'Agreed', 'class' => 'badge bg-label-success'],
                                                            'completed' => ['label' => 'Completed', 'class' => 'badge bg-label-success'],
                                                        ];
                                                        $status = $statuses[$request->status] ?? ['label' => $request->status, 'class' => 'badge bg-label-secondary'];
                                                    @endphp
                                                    <span class="{{ $status['class'] }}">{{ $status['label'] }}</span>
                                                </td>
                                                <td>{{ $request->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    <a href="{{ route('service-requests.show', $request->id) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="ti tabler-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted text-center">No service requests yet</p>
                        @endif
                    </div>
                </div>

                <!-- Service Request Responses -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Service Request Responses</h5>
                    </div>
                    <div class="card-body">
                        @if($user->serviceRequestResponses->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Request ID</th>
                                            <th>Status</th>
                                            <th>Proposed Price</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->serviceRequestResponses->take(5) as $response)
                                            <tr>
                                                <td>#{{ $response->service_request_id }}</td>
                                                <td>
                                                    @php
                                                        $responseStatuses = [
                                                            'pending' => ['label' => 'Pending', 'class' => 'badge bg-label-warning'],
                                                            'accepted' => ['label' => 'Accepted', 'class' => 'badge bg-label-success'],
                                                            'rejected' => ['label' => 'Rejected', 'class' => 'badge bg-label-danger'],
                                                        ];
                                                        $responseStatus = $responseStatuses[$response->status] ?? ['label' => $response->status, 'class' => 'badge bg-label-secondary'];
                                                    @endphp
                                                    <span class="{{ $responseStatus['class'] }}">{{ $responseStatus['label'] }}</span>
                                                </td>
                                                <td>{{ $response->proposed_price ? number_format($response->proposed_price, 2) . ' ' . __('admin.currency') : '-' }}</td>
                                                <td>{{ $response->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    <a href="{{ route('service-requests.show', $response->service_request_id) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="ti tabler-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted text-center">No responses yet</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
