@extends('website.layouts.profile')

@section('title', __('website.request_details_id') . ($serviceRequest->request_key ?? $serviceRequest->id))

@section('profile-content')
    <div class="order-requests-section">
        <div class="container">
            
            {{-- Workflow Actions Container --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm root-radius">
                        <div class="card-body p-4">
                            {{-- Status Tracker --}}
                            <div class="status-tracker mb-4">
                                <ul class="list-unstyled d-flex justify-content-between align-items-center mb-0 text-center overflow-auto pb-2">
                                    @php
                                        $statuses = [
                                            'under_review' => __('website.under_review'),
                                            'pending' => __('website.status_new'),
                                            'provider_accepted' => __('website.status_offer_accepted'),
                                            'seeker_confirmed_provider' => __('website.status_seeker_confirmed'),
                                            'inspection_scheduled' => __('website.status_inspection_scheduled'),
                                            'inspection_done' => __('website.status_inspection_done'),
                                            'work_completed' => __('website.status_work_completed')
                                        ];
                                        $currentIndex = array_search($serviceRequest->status, array_keys($statuses));
                                        if ($currentIndex === false) $currentIndex = -1;
                                    @endphp
                                    @foreach($statuses as $key => $label)
                                        <li class="flex-fill px-2">
                                            <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center {{ array_search($key, array_keys($statuses)) <= $currentIndex ? 'bg-primary text-white' : 'bg-light text-muted border' }}" style="width: 35px; height: 35px;">
                                                @if(array_search($key, array_keys($statuses)) < $currentIndex)
                                                    <i class="fa fa-check"></i>
                                                @else
                                                    {{ $loop->iteration }}
                                                @endif
                                            </div>
                                            <small class="d-block {{ array_search($key, array_keys($statuses)) <= $currentIndex ? 'fw-bold text-primary' : 'text-muted' }}" style="white-space: nowrap;">{{ $label }}</small>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="actions-content text-center py-3 bg-light rounded-4">
                                {{-- SEEKER ACTIONS --}}
                                @if (auth()->id() == $serviceRequest->user_id)
                                    @if ($serviceRequest->status === 'pending')
                                        <h5 class="fw-bold mb-0">{{ __('website.review_offers_below') }}</h5>
                                    @elseif ($serviceRequest->status === 'provider_accepted')
                                        <h5 class="fw-bold mb-3">{{ __('website.provider_accepted_msg') }}</h5>
                                        <form action="{{ route('requests.confirm-seeker', $serviceRequest->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary px-5 py-2">
                                                <i class="bi bi-person-check me-1"></i> {{ __('website.confirm_provider_btn') }}
                                            </button>
                                        </form>
                                    @elseif ($serviceRequest->status === 'seeker_confirmed_provider')
                                        <div class="text-primary">
                                            <i class="bi bi-calendar2-range display-4 d-block mb-2"></i>
                                            <h5>{{ __('website.waiting_provider_schedule') }}</h5>
                                        </div>
                                    @elseif ($serviceRequest->status === 'inspection_scheduled')
                                        <h5 class="fw-bold mb-3">المعاينة المجدولة: {{ \Carbon\Carbon::parse($serviceRequest->inspection_date)->format('Y-m-d h:i A') }}</h5>
                                        <form action="{{ route('requests.inspections.complete', $serviceRequest->inspections->last()->id ?? 0) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success px-5 py-2 rounded-pill">
                                                <i class="fa fa-check-circle me-1"></i> تأكيد إتمام المعاينة
                                            </button>
                                        </form>
                                    @elseif ($serviceRequest->status === 'inspection_done')
                                        <h5 class="fw-bold mb-3">{{ __('website.inspection_complete_question') }}</h5>
                                        <form action="{{ route('requests.agree', $serviceRequest->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success px-5 py-2">
                                                <i class="bi bi-play-circle me-1"></i> {{ __('website.confirm_start_work_btn') }}
                                            </button>
                                        </form>
                                    @elseif ($serviceRequest->status === 'work_completed')
                                        <div class="text-success">
                                            <i class="fa fa-check display-4 d-block mb-2"></i>
                                            <h5>{{ __('website.work_completed_success') }}</h5>
                                        </div>
                                    @endif
                                @else
                                    {{-- PROVIDER ACTIONS --}}
                                    @php
                                        $myResponseTracker = null;
                                        if (auth()->check()) {
                                            $myResponseTracker = $serviceRequest->responses->where('user_id', auth()->id())->first();
                                        }
                                        $isProvider = $serviceRequest->awarded_provider_id == auth()->id();
                                    @endphp

                                    @if($myResponseTracker && $myResponseTracker->status === 'rejected')
                                        <div class="text-danger text-center">
                                            <i class="bi bi-x-circle display-4 d-block mb-2"></i>
                                            <h5 class="fw-bold mb-1">لقد قمت بالاعتذار عن هذا الطلب</h5>
                                            @if($myResponseTracker->message)
                                                <p class="mb-0 mt-2">السبب: {{ $myResponseTracker->message }}</p>
                                            @endif
                                        </div>
                                    @elseif((!$myResponseTracker || $myResponseTracker->status === 'pending') && in_array($serviceRequest->status, ['pending', 'open']))
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button type="button" class="btn btn-success fw-bold py-2 px-4 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#offerModal">
                                                <i class="bi bi-currency-dollar me-1"></i> {{ __('website.submit_offer') }}
                                            </button>
                                            

                                        </div>
                                    @elseif($myResponseTracker && in_array($myResponseTracker->status, ['under_review', 'accepted', 'provider_accepted']))
                                        <div class="text-center p-3 rounded" style="background-color: #f8f9fa; border: 1px dashed #ccc;">
                                            <h6 class="fw-bold text-success mb-2"><i class="bi bi-check-circle-fill me-1"></i> لقد قمت بتقديم عرض</h6>
                                            <div class="text-muted small mb-2">السعر: <strong class="text-dark">{{ number_format($myResponseTracker->proposed_price, 2) }} ر.س</strong></div>
                                            <div class="text-muted small mb-2">المدة: <strong class="text-dark">{{ $myResponseTracker->proposed_timeline }}</strong></div>
                                            <div class="text-muted small">الرسالة: {{ Str::limit($myResponseTracker->message, 50) }}</div>
                                        </div>
                                    @endif
                                    
                                    @if($isProvider || ($myResponseTracker && in_array($myResponseTracker->status, ['accepted', 'provider_accepted'])))
                                        <a href="{{ route('chat.start', $serviceRequest->user_id) }}" class="btn btn-outline-secondary fw-bold py-2 px-5 mt-2 rounded-pill shadow-sm border-1">
                                            <i class="bi bi-chat-dots me-1"></i> {{ (str_contains($serviceRequest->category->name ?? '', 'قانوني') || ($serviceRequest->category->name ?? '') == 'استشارة قانونية عقارية') ? 'مراسلة العميل' : 'التواصل مع العميل' }}
                                        </a>
                                    @endif

                                    @if($isProvider && in_array($serviceRequest->status, ['provider_accepted', 'seeker_confirmed_provider']))
                                        <button type="button" class="btn btn-primary fw-bold py-2 px-5 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#scheduleModal">
                                            <i class="bi bi-calendar-plus me-1"></i> {{ __('website.schedule_inspection') }}
                                        </button>
                                    @endif

                                    @if($isProvider && $serviceRequest->status === 'inspection_scheduled')
                                        <form action="{{ route('requests.inspections.complete', $serviceRequest->inspections->last()->id ?? 0) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success px-5 fw-bold py-2 rounded-pill shadow-sm">
                                                <i class="fa fa-check-circle me-1"></i> تأكيد إتمام المعاينة
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    {{-- Offers Section (Only for seeker and only in pending) --}}
                    @if (auth()->id() == $serviceRequest->user_id && $serviceRequest->status === 'pending')
                        <h4 class="fw-bold mb-4 mt-2 px-2"><i class="bi bi-briefcase text-primary me-2"></i>{{ __('website.submitted_offers_count', ['count' => $serviceRequest->responses->where('status', 'accepted')->count()]) }}</h4>
                        @forelse($serviceRequest->responses->where('status', 'accepted') as $offer)
                            <div class="card shadow-sm border-0 root-radius mb-4 hover-shadow transition">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $offer->user->getFirstMediaUrl('avatar') ?: asset('website/assets/img/logo.png') }}" class="rounded-circle border" style="width: 50px; height: 50px; object-fit: cover;">
                                            <div>
                                                <h6 class="fw-bold mb-0">{{ $offer->user->name }}</h6>
                                                <small class="text-muted">{{ $offer->user->isCompany() ? __('website.company_office') : __('website.freelance_engineer') }}</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="fw-bold text-primary fs-5">{{ $offer->proposed_price }} {{ __('website.rs') }}</span>
                                            <small class="text-muted d-block small">{{ __('website.expected_timeline') }}: {{ $offer->proposed_timeline }}</small>
                                        </div>
                                    </div>
                                    <p class="text-muted small mb-3">{{ $offer->message }}</p>
                                    <div class="d-flex justify-content-end gap-2">
                                        <form action="{{ route('requests.reject-provider', [$serviceRequest->id, $offer->user_id]) }}" method="POST" class="reject-offer-form">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-4 btn-reject" 
                                                data-title="{{ __('website.confirm_reject_offer_title') }}"
                                                data-text="{{ __('website.confirm_reject_offer_text') }}"
                                                data-btn="{{ __('website.yes_reject') }}">
                                                <i class="bi bi-x-circle me-1"></i> {{ __('website.reject_offer') }}
                                            </button>
                                        </form>
                                        <form action="{{ route('requests.accept-provider', [$serviceRequest->id, $offer->user_id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill px-4"><i class="bi bi-check-circle me-1"></i> {{ __('website.accepted_offer_btn') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-light border text-center py-4 rounded-4 mb-4">
                                <p class="text-muted mb-0">{{ __('website.no_offers_yet') }}</p>
                            </div>
                        @endforelse
                    @endif
                </div>

                <div class="col-12 mt-4">
                    {{-- Sidebar Info --}}
                    @if ($serviceRequest->awardedProvider)
                        @if (auth()->id() == $serviceRequest->user_id)
                            {{-- Show Provider Info to Seeker --}}
                            <div class="card shadow-sm border-0 root-radius mb-4">
                                <div class="card-body p-4 text-center">
                                    <h6 class="fw-bold border-bottom pb-2 mb-3">{{ __('website.selected_provider') }}</h6>
                                    <img src="{{ $serviceRequest->awardedProvider->getFirstMediaUrl('avatar') ?: asset('website/assets/img/logo.png') }}" 
                                         class="rounded-circle border mb-2" style="width: 80px; height: 80px; object-fit: cover;">
                                    <h5 class="fw-bold mb-1">{{ $serviceRequest->awardedProvider->name }}</h5>
                                    <div class="text-warning small mb-3">
                                        @php $avg = $serviceRequest->awardedProvider->average_rating; @endphp
                                        @for($i=1; $i<=5; $i++)
                                            <i class="bi bi-star{{ $i <= $avg ? '-fill' : '' }}"></i>
                                        @endfor
                                        ({{ number_format($avg, 1) }})
                                    </div>
                                    @if ($chat)
                                        <div class="d-grid">
                                            <a href="{{ route('dashboard.chat.show', ['chat' => $chat->id]) }}" class="btn btn-primary rounded-pill">
                                                <i class="bi bi-chat-dots me-1"></i> {{ __('website.instant_chat') }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @elseif (auth()->id() == $serviceRequest->awarded_provider_id)
                            {{-- Show Seeker Info to Awarded Provider --}}
                            <div class="card shadow-sm border-0 root-radius mb-4">
                                <div class="card-body p-4 text-center">
                                    <h6 class="fw-bold border-bottom pb-2 mb-3">معلومات العميل</h6>
                                    <img src="{{ $serviceRequest->user->getFirstMediaUrl('avatar') ?: asset('website/assets/img/logo.png') }}" 
                                         class="rounded-circle border mb-2" style="width: 80px; height: 80px; object-fit: cover;">
                                    <h5 class="fw-bold mb-1">{{ $serviceRequest->user->name }}</h5>
                                    <div class="text-muted small mb-3">
                                        طالب الخدمة
                                    </div>
                                    @if ($chat)
                                        <div class="d-grid">
                                            <a href="{{ route('dashboard.chat.show', ['chat' => $chat->id]) }}" class="btn btn-primary rounded-pill">
                                                <i class="bi bi-chat-dots me-1"></i> {{ __('website.instant_chat') }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            {{-- Show Awarded Provider Info to Other Providers --}}
                            <div class="card shadow-sm border-0 root-radius mb-4">
                                <div class="card-body p-4 text-center">
                                    <h6 class="fw-bold border-bottom pb-2 mb-3">{{ __('website.selected_provider') }}</h6>
                                    <img src="{{ $serviceRequest->awardedProvider->getFirstMediaUrl('avatar') ?: asset('website/assets/img/logo.png') }}" 
                                         class="rounded-circle border mb-2" style="width: 80px; height: 80px; object-fit: cover;">
                                    <h5 class="fw-bold mb-1">{{ $serviceRequest->awardedProvider->name }}</h5>
                                </div>
                            </div>
                        @endif
                    @else
                        @if (auth()->id() == $serviceRequest->user_id && $serviceRequest->responses->where('status', 'accepted')->count() == 0)
                            <div class="card shadow-sm border-0 root-radius bg-primary text-white mb-4">
                                <div class="card-body p-4 text-center">
                                    <i class="bi bi-hourglass-top display-4 d-block mb-3"></i>
                                    <h5 class="fw-bold">{{ __('website.waiting_offers') }}</h5>
                                    <p class="small mb-0">{{ __('website.waiting_offers_desc') }}</p>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            @if ($serviceRequest->inspection_date)
                <div class="alert alert-warning border-0 shadow-sm mb-4 d-flex align-items-center">
                    <div class="fs-1 me-3 text-warning"><i class="bi bi-calendar-check"></i></div>
                    <div>
                        <h6 class="fw-bold mb-1">{{ __('website.scheduled_inspection_lbl') }}</h6>
                        <span class="fs-5">{{ \Carbon\Carbon::parse($serviceRequest->inspection_date)->format('Y-m-d h:i A') }}</span>
                    </div>
                </div>
            @endif

            <div class="card shadow-sm border-0 root-radius mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0">
                            <a href="{{ route('profile.requests') }}" class="btn btn-sm btn-outline-secondary me-3 rounded-pill">
                                <i class="bi bi-arrow-right"></i> {{ __('website.back') }}
                            </a>
                            <i class="bi bi-file-earmark-text text-primary me-2"></i>{{ __('website.request_details_id') }}{{ $serviceRequest->request_key ?? $serviceRequest->id }}
                        </h4>
                        <div>
                            <span class="badge bg-primary px-3 py-2 fs-6">{{ __('website.'.$serviceRequest->status) }}</span>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6 col-lg-3">
                            <div class="p-3 border rounded bg-light h-100">
                                <small class="text-muted d-block mb-1">{{ __('website.category_lbl') }}</small>
                                <span class="fw-bold">{{ $serviceRequest->category->name ?? __('website.none') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="p-3 border rounded bg-light h-100">
                                <small class="text-muted d-block mb-1">{{ __('website.service_type_lbl') }}</small>
                                <span class="fw-bold">
                
                                    {{ $serviceRequest->subCategory->name ?? '' }}
                                    
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="p-3 border rounded bg-light h-100">
                                <small class="text-muted d-block mb-1">{{ __('website.detailed_address_lbl') }}</small>
                                <span class="fw-bold"><i
                                        class="bi bi-geo-alt me-1"></i>{{ $serviceRequest->location }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="p-3 border rounded bg-light h-100">
                                <small class="text-muted d-block mb-1">{{ __('website.added_date_lbl') }}</small>
                                <span class="fw-bold"><i
                                        class="bi bi-calendar3 me-1"></i>{{ $serviceRequest->created_at->format('Y-m-d') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h5 class="fw-bold border-bottom pb-2 mb-3">{{ __('website.request_description') }}</h5>
                        <p class="text-muted lead" style="white-space: pre-line;">{{ $serviceRequest->description }}</p>
                    </div>

                    @if ($serviceRequest->voice_record)
                        <div class="mt-4 p-3 bg-light rounded-4">
                            <h5 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-mic text-primary me-2"></i>{{ __('website.voice_record_lbl') }}</h5>
                            <audio controls class="w-100 shadow-sm rounded-pill mb-2">
                                <source src="{{ Storage::url($serviceRequest->voice_record) }}" type="audio/mpeg">
                                <source src="{{ Storage::url($serviceRequest->voice_record) }}" type="audio/webm">
                                <source src="{{ Storage::url($serviceRequest->voice_record) }}" type="audio/ogg">
                                <source src="{{ Storage::url($serviceRequest->voice_record) }}" type="audio/wav">
                                {{ __('website.browser_not_support_audio') }}
                            </audio>
                            <div class="text-center">
                                <a href="{{ Storage::url($serviceRequest->voice_record) }}" target="_blank" class="btn btn-sm btn-link">
                                    <i class="bi bi-download me-1"></i> {{ __('website.download_voice_record') }}
                                </a>
                            </div>
                        </div>
                    @endif

                    @if ($serviceRequest->latitude && $serviceRequest->longitude)
                        <div class="mt-4">
                            <h5 class="fw-bold border-bottom pb-2 mb-3">{{ __('website.location_on_map') }}</h5>
                            <iframe
                                width="100%"
                                height="350"
                                style="border:0; border-radius: 12px;"
                                loading="lazy"
                                allowfullscreen
                                src="https://www.google.com/maps/embed/v1/place?q={{ $serviceRequest->latitude }},{{ $serviceRequest->longitude }}&key={{ config('services.google_maps.key') }}">
                            </iframe>
                            <div class="mt-2 text-end">
                                <a href="https://www.google.com/maps?q={{ $serviceRequest->latitude }},{{ $serviceRequest->longitude }}"
                                    target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="bi bi-map me-1"></i> {{ __('website.open_in_google_maps') }}
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Attachments Section --}}
                    @if ($serviceRequest->hasMedia('blueprints') || $serviceRequest->hasMedia('site_photos'))
                        <div class="mt-4">
                            <h5 class="fw-bold border-bottom pb-2 mb-3">{{ __('website.attached_files_lbl') }}</h5>
                            
                            @if ($serviceRequest->hasMedia('blueprints'))
                                <div class="mb-4">
                                    <h6 class="fw-bold"><i class="bi bi-file-earmark-pdf text-primary me-2"></i>{{ __('website.blueprints_lbl') }}</h6>
                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                        @foreach ($serviceRequest->getMedia('blueprints') as $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill">
                                                <i class="bi bi-download me-1"></i> {{ $media->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if ($serviceRequest->hasMedia('site_photos'))
                                <div class="mb-4">
                                    <h6 class="fw-bold"><i class="bi bi-images text-primary me-2"></i>{{ __('website.site_photos_lbl') }}</h6>
                                    <div class="row g-2 mt-2">
                                        @foreach ($serviceRequest->getMedia('site_photos') as $media)
                                            <div class="col-6 col-md-3 col-lg-2">
                                                <a href="{{ $media->getUrl() }}" target="_blank" class="d-block border rounded overflow-hidden shadow-sm hover-shadow transition">
                                                    <img src="{{ $media->getUrl() }}" class="img-fluid" style="height: 120px; width: 100%; object-fit: cover;">
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif



                    @if ($serviceRequest->status === 'work_completed')
                        @php
                            $hasRated = \App\Models\Rating::where('rater_id', auth()->id())
                                ->where('service_request_id', $serviceRequest->id)
                                ->exists();
                        @endphp
                        @if (!$hasRated)
                            <div class="mt-5 p-4 rounded-4 border-2"
                                style="border: 2px dashed #ffc107; background: #fffcf4;">
                                <div class="row align-items-center">
                                    <div class="col-lg-4 text-center mb-3 mb-lg-0">
                                        <div class="display-4 text-warning mb-2"><i class="bi bi-star-fill"></i></div>
                                        <h4 class="fw-bold">{{ __('website.rate_experience') }}</h4>
                                        <p class="text-muted">{{ __('website.rating_help_msg') }}</p>
                                    </div>
                                    <div class="col-lg-8 border-start-lg ps-lg-4">
                                        <form action="{{ route('requests.rate', $serviceRequest->id) }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label fw-bold">{{ __('website.rating_out_of_5') }}</label>
                                                    <div class="star-rating-widget fs-2">
                                                        <input type="radio" id="star5" name="score" value="5" required />
                                                        <label for="star5" title="{{ __('website.excellent') }}"><i class="bi bi-star-fill"></i></label>
                                                        
                                                        <input type="radio" id="star4" name="score" value="4" />
                                                        <label for="star4" title="{{ __('website.very_good') }}"><i class="bi bi-star-fill"></i></label>
                                                        
                                                        <input type="radio" id="star3" name="score" value="3" />
                                                        <label for="star3" title="{{ __('website.good') }}"><i class="bi bi-star-fill"></i></label>
                                                        
                                                        <input type="radio" id="star2" name="score" value="2" />
                                                        <label for="star2" title="{{ __('website.acceptable') }}"><i class="bi bi-star-fill"></i></label>
                                                        
                                                        <input type="radio" id="star1" name="score" value="1" />
                                                        <label for="star1" title="{{ __('website.bad') }}"><i class="bi bi-star-fill"></i></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label fw-bold">{{ __('website.your_feedback') }}</label>
                                                    <textarea name="comment" class="form-control" rows="3"
                                                        placeholder="{{ __('website.feedback_quality_msg') }}"></textarea>
                                                </div>
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-warning w-100 py-2"><i
                                                            class="bi bi-send me-1"></i>
                                                        {{ __('website.send_final_rating') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-success border-0 shadow-sm mt-4 text-center">
                                <i class="fa fa-check-circle-fill me-2"></i> {{ __('website.rating_success_msg') }}
                            </div>
                        @endif
                    @endif
                    @if ($serviceRequest->status === 'work_completed' || $serviceRequest->status === 'completed')
                        <div class="mt-5 border-top pt-4">
                            <h5 class="fw-bold mb-4"><i class="bi bi-star-half text-warning me-2"></i>{{ __('website.ratings_and_feedback') }}</h5>
                            <div class="row g-4">
                                {{-- Seeker's Rating of Provider --}}
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 bg-light rounded-4">
                                        <div class="card-body p-4">
                                            <h6 class="fw-bold mb-3">{{ __('website.seeker_rating_to_provider') }}</h6>
                                            @if($serviceRequest->seekerRating)
                                                <div class="text-warning mb-2 h4">
                                                    @for($i=1; $i<=5; $i++)
                                                        <i class="bi bi-star{{ $i <= $serviceRequest->seekerRating->rating ? '-fill' : '' }}"></i>
                                                    @endfor
                                                </div>
                                                <p class="text-muted mb-0 italic">"{{ $serviceRequest->seekerRating->comment ?: __('website.no_comment') }}"</p>
                                            @else
                                                <div class="alert alert-info py-2 mb-0 small">
                                                    <i class="bi bi-info-circle me-1"></i> {{ __('website.waiting_seeker_rating') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Provider's Rating of Seeker --}}
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 bg-light rounded-4">
                                        <div class="card-body p-4">
                                            <h6 class="fw-bold mb-3">{{ __('website.provider_rating_to_seeker') }}</h6>
                                            @if($serviceRequest->providerRating)
                                                <div class="text-warning mb-2 h4">
                                                    @for($i=1; $i<=5; $i++)
                                                        <i class="bi bi-star{{ $i <= $serviceRequest->providerRating->rating ? '-fill' : '' }}"></i>
                                                    @endfor
                                                </div>
                                                <p class="text-muted mb-0 italic">"{{ $serviceRequest->providerRating->comment ?: __('website.no_comment') }}"</p>
                                            @else
                                                <div class="alert alert-info py-2 mb-0 small">
                                                    <i class="bi bi-info-circle me-1"></i> {{ __('website.waiting_provider_rating') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>


        </div>
    </div>

    {{-- MODALS --}}
    
    {{-- Offer Modal (For Provider) --}}
    <div class="modal fade" id="offerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content root-radius shadow border-0">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold">{{ __('website.submit_offer') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('requests.respond', $serviceRequest->request_key ?? $serviceRequest->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">{{ __('website.proposed_price_lbl') }} ({{ __('website.rs') }})</label>
                            <input type="number" name="proposed_price" class="form-control" required placeholder="00.00">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">{{ __('website.expected_timeline') }}</label>
                            <input type="text" name="proposed_timeline" class="form-control" required placeholder="{{ __('website.timeline_example') }}">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold small">{{ __('website.message_to_seeker') }}</label>
                            <textarea name="message" class="form-control" rows="4" required placeholder="{{ __('website.message_to_seeker_placeholder') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('website.cancel') }}</button>
                        <button type="submit" class="btn btn-primary px-4">{{ __('website.submit_offer_now') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Schedule Modal (For Provider) --}}
    <div class="modal fade" id="scheduleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content root-radius shadow border-0">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold">{{ __('website.schedule_inspection') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('provider.requests.schedule', $serviceRequest->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">{{ __('website.inspection_date_time') }}</label>
                            <input type="datetime-local" name="inspection_date" class="form-control" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold small">{{ __('website.additional_notes_optional') }}</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="{{ __('website.notes_placeholder') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('website.cancel') }}</button>
                        <button type="submit" class="btn btn-success px-4">{{ __('website.confirm_schedule_btn') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Simple confirmation for forms
        document.querySelectorAll('form').forEach(form => {
            if (form.querySelector('button[type="submit"]') && !form.closest('.modal') && !form.classList.contains('reject-offer-form')) {
                form.addEventListener('submit', function(e) {
                    const btn = e.submitter || form.querySelector('button[type="submit"]');
                    if (!btn) return;
                    if (btn.classList.contains('btn-confirm')) return; // skip if already confirmed
                    
                    e.preventDefault();
                    const title = btn.getAttribute('data-title') || @json(__('website.are_you_sure'));
                    const text = btn.getAttribute('data-text') || @json(__('website.confirm_action_proceed_msg'));
                    const color = btn.getAttribute('data-color') || '#014D40';
                    const icon = btn.getAttribute('data-icon') || 'question';
                    const confirmBtnText = btn.getAttribute('data-confirm-btn') || @json(__('website.yes_proceed'));

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonColor: color,
                        cancelButtonColor: '#d33',
                        confirmButtonText: confirmBtnText,
                        cancelButtonText: @json(__('website.cancel'))
                    }).then((result) => {
                        if (result.isConfirmed) {
                            btn.classList.add('btn-confirm');
                            form.submit();
                        }
                    });
                });
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.reject-offer-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = form.querySelector('.btn-reject');
                
                Swal.fire({
                    title: btn.getAttribute('data-title'),
                    text: btn.getAttribute('data-text'),
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#9ca3af',
                    confirmButtonText: btn.getAttribute('data-btn'),
                    cancelButtonText: @json(__('website.cancel'))
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


