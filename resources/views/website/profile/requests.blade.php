@extends('website.layouts.profile')


@section('profile-content')
    <!-- Order Requests Section -->
    <div class="order-requests-section">
        <div class="container">

            <!-- Tabs -->
            <ul class="nav nav-pills mb-4 custom-nav-pills justify-content-center" id="requests-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="active-tab" data-bs-toggle="pill" data-bs-target="#active-requests"
                        type="button" role="tab" aria-controls="active-requests" aria-selected="true">{{ __('website.active_requests') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="completed-tab" data-bs-toggle="pill" data-bs-target="#completed-requests"
                        type="button" role="tab" aria-controls="completed-requests" aria-selected="false">{{ __('website.completed_requests') }}</button>
                </li>
            </ul>

            <div class="tab-content" id="requests-tabContent">

                <!-- Active Requests Tab -->
                <div class="tab-pane fade show active" id="active-requests" role="tabpanel" aria-labelledby="active-tab">
                    <div class="order-requests-list">
                        @php
                            $activeRequests = $requests->whereNotIn('status', [
                                'completed',
                                'work_completed',
                                'cancelled',
                            ]);
                        @endphp
                        @forelse($activeRequests as $request)
                            @php
                                $isProvider = auth()->user()->isServiceProvider();
                                $statusColor = match ($request->status) {
                                    'pending' => 'bg-primary',
                                    'provider_accepted',
                                    'inspection_scheduled',
                                    'inspection_done'
                                        => 'bg-info text-dark',
                                    'work_completed', 'completed' => 'bg-success',
                                    'cancelled', 'rejected', 'timeout' => 'bg-danger',
                                    default => 'bg-secondary',
                                };
                                
                                // Logic for display user
                                if ($isProvider) {
                                    $displayUser = $request->user;
                                    $userLabel = __('website.service_seeker_lbl');
                                } else {
                                    $displayUser = $request->awardedProvider ?? $request->user;
                                    $userLabel = $request->awardedProvider ? __('website.service_provider_lbl') : __('website.service_seeker_lbl');
                                }

                                $displayImage =
                                    $displayUser->getFirstMediaUrl('personal_photo') ?:
                                    $displayUser->getFirstMediaUrl('users') ?:
                                    asset('website/assets/img/logo.png');

                                // Provider-specific response data
                                if ($isProvider) {
                                    $myResponse = $request->responses->first(); // Eager loaded in controller for current user
                                    $hasSubmitted = $myResponse && in_array($myResponse->status, ['pending', 'accepted', 'timeout']) && $myResponse->proposed_price > 0;
                                    $isRejected = $myResponse && $myResponse->status === 'rejected';
                                }
                            @endphp
                            <div class="order-request-item" data-order-id="{{ $request->id }}">
                                <div class="order-request-content">
                                    <div class="order-client-image">
                                        <img src="{{ $displayImage }}" alt="{{ $displayUser->name }}">
                                        <h5 class="order-client-name">
                                            <a href="{{ route('member.public', $displayUser->id) }}" class="text-decoration-none text-dark">
                                                    {{ $userLabel }}: {{ $displayUser->name }}
                                            </a>
                                        </h5>
                                    </div>
                                    <div class="order-request-info">
                                        <h6 class="order-request-title">
                                            {{ $request->category->name }}
                                            @if ($request->subCategory)
                                                - {{ $request->subCategory->name }}
                                            @endif
                                            - {{ __($request->property_type) }}
                                        </h6>
                                        <p class="order-request-text">
                                            {{ Str::limit($request->description, 200) }}
                                        </p>
                                        <div class="mt-2 text-nowrap">
                                            <span
                                                class="badge {{ $statusColor }}">{{ __('admin.' . $request->status) }}</span>

                                            <span class="badge bg-light text-dark border ms-2">
                                                <i class="bi bi-briefcase"></i> {{ __('website.offers') }}:
                                                {{ $request->responses_count ?? 0 }}
                                            </span>

                                            <small class="text-muted ms-2"><i class="bi bi-geo-alt"></i>
                                                {{ $request->location }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="order-request-actions">
                                    <div class="order-actions-buttons d-flex align-items-center gap-2">
                                        {{-- Provider Context Actions --}}
                                        @if($isProvider && $request->status === 'pending')
                                            @if(!$hasSubmitted && !$isRejected)
                                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#offerModal-{{ $request->id }}">
                                                    <i class="bi bi-plus-circle me-1"></i> {{ __('website.submit_offer') }}
                                                </button>
                                                
                                                <form action="{{ route('requests.ignore', $request->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                        <i class="bi bi-x-circle me-1"></i> {{ __('website.ignore_request') }}
                                                    </button>
                                                </form>
                                            @elseif($hasSubmitted)
                                                <span class="badge bg-light text-primary border p-2"><i class="bi bi-check-all me-1"></i> {{ __('website.offer_submitted_status') }}</span>
                                            @elseif($isRejected)
                                                <span class="badge bg-light text-muted border p-2"><i class="bi bi-slash-circle me-1"></i> {{ __('website.ignored') }}</span>
                                            @endif
                                        @endif

                                        {{-- General Actions --}}
                                        <a href="{{ route('requests.show', $request->id) }}"
                                            class="order-action-btn order-action-chat" title="{{ __('website.view_details') }}">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if (!$isProvider && $request->status == 'pending')
                                            <form action="{{ route('requests.destroy', $request->id) }}" method="POST"
                                                class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button class="order-action-btn order-action-delete btn-delete-request"
                                                    type="button" title="{{ __('website.delete_request') }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    <div class="order-time">{{ $request->created_at->diffForHumans() }}</div>
                                </div>
                            </div>

                            {{-- Modal for Offer (for Providers) --}}
                            @if($isProvider && $request->status === 'pending' && !$hasSubmitted && !$isRejected)
                                <div class="modal fade" id="offerModal-{{ $request->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content root-radius shadow-lg">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold">{{ __('website.submit_your_offer') }} #{{ $request->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('requests.respond', $request->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body py-4">
                                                    <div class="mb-4">
                                                        <label class="form-label fw-bold text-dark">{{ __('website.proposed_price_lbl') }} ({{ __('website.rs') }})</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light border-0"><i class="bi bi-cash"></i></span>
                                                            <input type="number" name="proposed_price" class="form-control bg-light border-0 py-2" required placeholder="0.00">
                                                        </div>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="form-label fw-bold text-dark">{{ __('website.proposed_timeline_lbl') }}</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light border-0"><i class="bi bi-clock"></i></span>
                                                            <input type="text" name="proposed_timeline" class="form-control bg-light border-0 py-2" required placeholder="{{ __('website.timeline_placeholder') }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-dark">{{ __('website.message_to_seeker_lbl') }}</label>
                                                        <textarea name="message" class="form-control bg-light border-0 py-2" rows="4" required placeholder="{{ __('website.write_message_here') }}"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0 pt-0 pb-4 justify-content-center">
                                                    <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow">
                                                        <i class="bi bi-send me-1"></i> {{ __('website.send_offer') }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="alert alert-info text-center">{{ __('website.no_active_requests') }}</div>
                        @endforelse
                    </div>
                </div>

                <!-- Completed Requests Tab -->
                <div class="tab-pane fade" id="completed-requests" role="tabpanel" aria-labelledby="completed-tab">
                    <div class="order-requests-list">
                        @php
                            $completedRequests = $requests->whereIn('status', ['completed', 'work_completed']);
                        @endphp
                        @forelse($completedRequests as $request)
                            @php
                                $isProvider = auth()->user()->isServiceProvider();
                                $statusColor = match ($request->status) {
                                    'work_completed', 'completed' => 'bg-success',
                                    default => 'bg-secondary',
                                };
                                
                                if ($isProvider) {
                                    $displayUser = $request->user;
                                    $userLabel = __('website.service_seeker_lbl');
                                } else {
                                    $displayUser = $request->awardedProvider ?? $request->user;
                                    $userLabel = $request->awardedProvider ? __('website.service_provider_lbl') : __('website.service_seeker_lbl');
                                }

                                $displayImage =
                                    $displayUser->getFirstMediaUrl('personal_photo') ?:
                                    $displayUser->getFirstMediaUrl('users') ?:
                                    asset('website/assets/img/logo.png');
                            @endphp
                            <div class="order-request-item" data-order-id="{{ $request->id }}">
                                <div class="order-request-content">
                                    <div class="order-client-image">
                                        <img src="{{ $displayImage }}" alt="{{ $displayUser->name }}">
                                        <h5 class="order-client-name">
                                            <a href="{{ route('member.public', $displayUser->id) }}" class="text-decoration-none text-dark">
                                                {{ $userLabel }}: {{ $displayUser->name }}
                                            </a>
                                        </h5>
                                    </div>
                                    <div class="order-request-info">
                                        <h6 class="order-request-title">
                                            {{ $request->category->name }}
                                            @if ($request->subCategory)
                                                - {{ $request->subCategory->name }}
                                            @endif
                                            - {{ __($request->property_type) }}
                                        </h6>
                                        <p class="order-request-text">
                                            {{ Str::limit($request->description, 200) }}
                                        </p>
                                        <div class="mt-2 text-nowrap">
                                            <span
                                                class="badge {{ $statusColor }}">{{ __('admin.' . $request->status) }}</span>
                                            <small class="text-muted ms-2"><i class="bi bi-geo-alt"></i>
                                                {{ $request->location }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="order-request-actions">
                                    <div class="order-actions-buttons">
                                        @php
                                            $isSeeker = auth()->id() == $request->user_id;
                                            $myRating = $isSeeker ? $request->seekerRating : $request->providerRating;
                                            $otherRating = $isSeeker ? $request->providerRating : $request->seekerRating;
                                        @endphp

                                        <div class="d-flex flex-column align-items-end gap-1 mb-2">
                                            @if($myRating)
                                                <span class="badge bg-light text-success border">
                                                    <i class="bi bi-star-fill text-warning"></i> {{ __('website.my_rating') }}: {{ $myRating->rating }}/5
                                                </span>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-warning"
                                                    data-bs-toggle="modal" data-bs-target="#rateModal-{{ $request->id }}">
                                                    <i class="bi bi-star"></i> {{ __('website.rate_now') }}
                                                </button>
                                            @endif

                                            @if($otherRating)
                                                <span class="badge bg-light text-info border">
                                                    <i class="bi bi-star-fill text-warning"></i> {{ __('website.received_rating') }}: {{ $otherRating->rating }}/5
                                                </span>
                                            @else
                                                <span class="badge bg-light text-muted border">
                                                    <i class="bi bi-hourglass-split"></i> {{ __('website.waiting_other_rating') }}
                                                </span>
                                            @endif
                                        </div>

                                        @if (!$myRating)
                                            <!-- Rating Modal -->
                                            <div class="modal fade" id="rateModal-{{ $request->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content text-wrap">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">{{ __('website.rate_service_experience') }} #{{ $request->id }}
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('requests.rate', $request->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-body text-start">
                                                                <div class="mb-3">
                                                                    <label class="form-label d-block">{{ __('website.rating_out_of_5') }}</label>
                                                                    <div class="star-rating-widget">
                                                                        <input type="radio" id="star5-{{ $request->id }}" name="score" value="5" required />
                                                                        <label for="star5-{{ $request->id }}" title="{{ __('website.excellent') }}"><i class="bi bi-star-fill"></i></label>
                                                                        
                                                                        <input type="radio" id="star4-{{ $request->id }}" name="score" value="4" />
                                                                        <label for="star4-{{ $request->id }}" title="{{ __('website.very_good') }}"><i class="bi bi-star-fill"></i></label>
                                                                        
                                                                        <input type="radio" id="star3-{{ $request->id }}" name="score" value="3" />
                                                                        <label for="star3-{{ $request->id }}" title="{{ __('website.good') }}"><i class="bi bi-star-fill"></i></label>
                                                                        
                                                                        <input type="radio" id="star2-{{ $request->id }}" name="score" value="2" />
                                                                        <label for="star2-{{ $request->id }}" title="{{ __('website.acceptable') }}"><i class="bi bi-star-fill"></i></label>
                                                                        
                                                                        <input type="radio" id="star1-{{ $request->id }}" name="score" value="1" />
                                                                        <label for="star1-{{ $request->id }}" title="{{ __('website.bad') }}"><i class="bi bi-star-fill"></i></label>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">{{ __('website.your_feedback') }}</label>
                                                                    <textarea name="comment" class="form-control" rows="3" placeholder="{{ __('website.write_opinion_here') }}"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">{{ __('website.close') }}</button>
                                                                <button type="submit" class="btn btn-primary">{{ __('website.submit_rating') }}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <a href="{{ route('requests.show', $request->id) }}"
                                            class="order-action-btn order-action-chat" title="{{ __('website.view_details') }}">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                    <div class="order-time">{{ $request->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info text-center">{{ __('website.no_completed_requests') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-delete-request').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    Swal.fire({
                        title: '{{ __('website.are_you_sure') }}',
                        text: '{{ __('website.delete_request_warning') }}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: '{{ __('website.yes_delete_request') }}',
                        cancelButtonText: '{{ __('website.cancel') }}'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
