@extends('layouts.website')

@section('title', $tender->title)

@section('content')
<!-- Header Start -->
<div class="category-header-section text-center services-header-section without-search">
    <div class="container" style="max-width: 1320px;">
        <h1 class="fw-bold mb-3 wow fadeInUp" data-wow-delay="0.1s">{{ $tender->title }}</h1>
        
        <div class="d-flex justify-content-center gap-2 flex-wrap wow fadeInUp" data-wow-delay="0.2s">
            @if($tender->isExpired())
                <span class="badge" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); padding: 8px 15px; font-size: 13px; font-weight: 500; border-radius: 20px;">
                    <i class="bi bi-circle-fill" style="font-size: 10px; color: #ef4444;"></i> {{ __('tenders.status_closed') }}
                </span>
            @else
                <span class="badge" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); padding: 8px 15px; font-size: 13px; font-weight: 500; border-radius: 20px;">
                    <i class="bi bi-circle-fill" style="font-size: 10px; color: #22c55e;"></i> {{ __('tenders.status_open') }}
                </span>
            @endif
            @if($tender->category)
            <span class="badge" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); padding: 8px 15px; font-size: 13px; font-weight: 500; border-radius: 20px;">
                <i class="bi bi-folder"></i> {{ $tender->category->name }}
            </span>
            @endif
            @if($tender->city)
            <span class="badge" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); padding: 8px 15px; font-size: 13px; font-weight: 500; border-radius: 20px;">
                <i class="bi bi-geo-alt-fill text-danger"></i> {{ $tender->city->name }}
            </span>
            @endif
            <span class="badge" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); padding: 8px 15px; font-size: 13px; font-weight: 500; border-radius: 20px;">
                <i class="bi bi-calendar-event"></i> {{ __('tenders.ends_at') }} {{ $tender->ends_at ? $tender->ends_at->format('Y-m-d') : __('tenders.not_specified') }}
            </span>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- PAGE BODY -->
<div class="page-wrap" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

  <!-- MAIN -->
  <div>
    <div class="main-card">

      <!-- INFO GRID -->
      <div class="section">
        <div class="section-title"><i class="bi bi-bar-chart-fill text-primary"></i> {{ __('tenders.details') }}</div>
        <div class="info-grid">
          <div class="info-box highlight">
            <div class="ib-icon"><i class="bi bi-cash-stack text-success"></i></div>
            <div class="ib-label">{{ __('tenders.budget') }}</div>
            <div class="ib-value">{{ $tender->budget ? number_format($tender->budget) . ' ' . __('tenders.sar') : __('tenders.not_specified') }}</div>
          </div>
          <div class="info-box warn">
            <div class="ib-icon"><i class="bi bi-calendar-event text-danger"></i></div>
            <div class="ib-label">{{ __('tenders.end_date') }}</div>
            <div class="ib-value">{{ $tender->ends_at ? $tender->ends_at->format('Y-m-d') : __('tenders.not_specified') }}</div>
          </div>
          <div class="info-box">
            <div class="ib-icon"><i class="bi bi-geo-alt-fill text-danger"></i></div>
            <div class="ib-label">{{ __('tenders.location') }}</div>
            <div class="ib-value">{{ $tender->city ? $tender->city->name : __('tenders.not_specified') }}</div>
          </div>
          <div class="info-box">
            <div class="ib-icon"><i class="bi bi-file-earmark-text text-secondary"></i></div>
            <div class="ib-label">{{ __('tenders.specialty') }}</div>
            <div class="ib-value">{{ $tender->category ? $tender->category->name : __('tenders.not_specified') }}</div>
          </div>
          <div class="info-box">
            <div class="ib-icon"><i class="bi bi-building text-primary"></i></div>
            <div class="ib-label">{{ __('tenders.project_type') }}</div>
            <div class="ib-value">{{ $tender->project_type ?? __('tenders.not_specified') }}</div>
          </div>
          <div class="info-box">
            <div class="ib-icon"><i class="bi bi-calendar-check text-success"></i></div>
            <div class="ib-label">{{ __('tenders.publish_date') }}</div>
            <div class="ib-value">{{ $tender->created_at->format('Y-m-d') }}</div>
          </div>
        </div>
      </div>

      <!-- DESCRIPTION -->
      <div class="section">
        <div class="section-title"><i class="bi bi-journal-text text-warning"></i> {{ __('tenders.description') }}</div>
        <div class="desc-text">
          {!! nl2br(e($tender->description)) !!}
        </div>
      </div>

      <!-- REQUIREMENTS -->
      @if($tender->qualification_requirements && count($tender->qualification_requirements) > 0)
      <div class="section">
        <div class="section-title"><i class="bi bi-check-circle-fill text-success"></i> {{ __('tenders.requirements') }}</div>
        <ul class="req-list">
          @foreach($tender->qualification_requirements as $req)
            <li><i class="bi bi-check-lg text-success" style="margin-top: 3px;"></i> {{ $req }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <!-- FILES DOWNLOAD -->
      @if($tender->hasMedia('tender_files'))
      <div class="section">
        <div class="section-title"><i class="bi bi-paperclip text-secondary"></i> {{ __('tenders.files') }}</div>
        <div class="files-grid">
          @foreach($tender->getMedia('tender_files') as $media)
          <div class="file-item">
            <div class="file-info">
              <div class="file-icon"><i class="bi bi-file-earmark-pdf-fill text-danger"></i></div>
              <div style="overflow: hidden;">
                <div class="file-name text-truncate" title="{{ $media->getCustomProperty('title', $media->file_name) }}">
                    {{ $media->getCustomProperty('title', $media->file_name) }}
                </div>
                <div class="file-size">{{ number_format($media->size / 1024 / 1024, 2) }} MB</div>
              </div>
            </div>
            <a href="{{ $media->getUrl() }}" download class="btn-dl text-decoration-none"><i class="bi bi-download"></i> {{ __('tenders.download') }}</a>
          </div>
          @endforeach
        </div>
      </div>
      @endif

    </div>

      <!-- APPLICATIONS (FOR OWNER ONLY) -->
      @if(auth()->check() && auth()->id() === $tender->user_id)
      <div class="section mt-4">
        <div class="section-title"><i class="bi bi-people-fill text-primary"></i> {{ __('website.applications') ?? 'عروض الموردين' }}</div>
        
        @if($tender->applications && $tender->applications->count() > 0)
            <div class="row">
                @foreach($tender->applications as $app)
                    @php
                        $isAwarded = ($tender->awarded_provider_id === $app->user_id);
                        $isHidden = ($tender->awarded_provider_id && !$isAwarded);
                    @endphp
                    @if(!$isHidden)
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-{{ $isAwarded ? 'success' : 'light' }}">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="me-3">
                                        @if($app->user && $app->user->hasMedia('profile_image'))
                                            <img src="{{ $app->user->getFirstMediaUrl('profile_image') }}" alt="" style="width:50px;height:50px;border-radius:50%;object-fit:cover;">
                                        @else
                                            <div style="width:50px;height:50px;border-radius:50%;background:#e5e7eb;display:flex;align-items:center;justify-content:center;font-weight:bold;color:#6b7280;">
                                                {{ mb_substr($app->user->name ?? 'م', 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold"><a href="{{ route('member.public', $app->user->id) }}" class="text-dark text-decoration-none">{{ $app->user->name }}</a></h6>
                                        <small class="text-muted"><i class="bi bi-geo-alt-fill"></i> {{ $app->user->city ? $app->user->city->name : __('tenders.not_specified') }}</small>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <strong>{{ __('website.proposed_price') ?? 'السعر المقترح:' }}</strong> <span class="text-success fw-bold">{{ number_format($app->proposed_price) }} {{ __('tenders.sar') }}</span><br>
                                    <strong>{{ __('website.execution_time') ?? 'مدة التنفيذ:' }}</strong> {{ $app->execution_time }} {{ __('website.days') ?? 'أيام' }}
                                </div>
                                @if($app->notes)
                                    <p class="text-muted small border p-2 rounded bg-light">{{ $app->notes }}</p>
                                @endif
                                
                                @if($tender->status === \App\Models\Tender::STATUS_PENDING_REVIEW || $tender->status === \App\Models\Tender::STATUS_ACTIVE)
                                    <form action="{{ route('website.tenders.acceptApplication', ['id' => $tender->id, 'applicationId' => $app->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success w-100 mt-2"><i class="bi bi-check-circle"></i> {{ __('website.accept_offer') ?? 'قبول العرض' }}</button>
                                    </form>
                                @elseif($isAwarded)
                                    <div class="alert alert-success p-2 text-center mb-0 mt-2">
                                        <i class="bi bi-star-fill text-warning"></i> {{ __('website.offer_accepted') ?? 'العرض المقبول' }}
                                    </div>
                                    @if($tender->status === \App\Models\Tender::STATUS_IN_PROGRESS)
                                        <form action="{{ route('website.tenders.completeWork', $tender->id) }}" method="POST" class="mt-2">
                                            @csrf
                                            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-flag-fill"></i> {{ __('website.mark_as_completed') ?? 'تأكيد إنتهاء العمل' }}</button>
                                        </form>
                                    @elseif($tender->status === \App\Models\Tender::STATUS_COMPLETED)
                                        <button type="button" class="btn btn-warning w-100 mt-2" data-bs-toggle="modal" data-bs-target="#ratingModal">
                                            <i class="bi bi-star"></i> {{ __('website.rate_provider') ?? 'تقييم المورد' }}
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        @else
            <p class="text-muted">{{ __('website.no_applications_yet') ?? 'لا يوجد عروض حتى الآن.' }}</p>
        @endif
      </div>
      @endif

  </div>

  <!-- SIDEBAR -->
  <div class="sidebar">

    <!-- CTA -->
    <div class="cta-card">
      @if($tender->isExpired())
        <div class="cta-status"><i class="bi bi-circle-fill" style="font-size: 10px; color: #ef4444;"></i> {{ __('tenders.ended_tender') }}</div>
        <button class="btn-main-offer" disabled style="background:#9ca3af;cursor:not-allowed;">
            {{ __('tenders.time_ended') }}
        </button>
      @elseif(auth()->check() && $tender->user_id == auth()->id())
        <div class="cta-status"><i class="bi bi-circle-fill" style="font-size: 10px; color: #22c55e;"></i> {{ __('tenders.own_tender') }}</div>
        <button class="btn-main-offer" disabled style="background:#9ca3af;cursor:not-allowed;">
            {{ __('tenders.cant_apply_own') }}
        </button>
      @elseif($hasApplied)
        <div class="cta-status"><i class="bi bi-check-circle-fill" style="font-size: 14px; color: #10b981;"></i> {{ __('tenders.already_applied') }}</div>
        <button class="btn-main-offer" disabled style="background:#10b981;cursor:not-allowed;">
            <i class="bi bi-check-lg"></i> {{ __('tenders.applied_success_btn') }}
        </button>
      @else
        <div class="cta-status"><i class="bi bi-circle-fill" style="font-size: 10px; color: #22c55e;"></i> {{ __('tenders.status_open') }}</div>
        <a href="{{ route('website.tenders.apply', $tender->id) }}" class="btn-main-offer text-decoration-none">
            <i class="bi bi-rocket-takeoff-fill"></i> {{ __('tenders.apply_now') }}
        </a>
      @endif

      @auth
      <button class="btn-save" id="saveTenderBtn" onclick="toggleSaveTender({{ $tender->id }})">
        @if($isSaved)
          <i class="bi bi-bookmark-fill text-primary" id="saveIcon"></i> <span id="saveText">{{ __('tenders.remove_saved') }}</span>
        @else
          <i class="bi bi-bookmark" id="saveIcon"></i> <span id="saveText">{{ __('tenders.save_tender') }}</span>
        @endif
      </button>
      @else
      <a href="{{ route('login') }}" class="btn-save text-decoration-none text-dark d-block text-center mt-2">
        <i class="bi bi-bookmark"></i> {{ __('tenders.save_tender') }}
      </a>
      @endauth

      <p class="cta-note mt-3">{!! __('tenders.terms_agree') !!}</p>
    </div>

    <!-- DEADLINE -->
    <div class="deadline-card">
      <div class="dl-title"><i class="bi bi-hourglass-split"></i> {{ __('tenders.time_remaining') }}</div>
      <div class="dl-timer" id="countdown-timer">
        @if($tender->isExpired())
            <div style="text-align: center; width: 100%; color: #ef4444; font-weight: bold; font-size: 1.1rem; padding: 10px 0;">
                {{ __('tenders.time_ended') }}
            </div>
        @else
            <div class="dl-unit"><div class="dl-num" id="days">--</div><div class="dl-label">{{ __('tenders.day') }}</div></div>
            <div class="dl-unit"><div class="dl-num" id="hours">--</div><div class="dl-label">{{ __('tenders.hour') }}</div></div>
            <div class="dl-unit"><div class="dl-num" id="mins">--</div><div class="dl-label">{{ __('tenders.minute') }}</div></div>
            <div class="dl-unit"><div class="dl-num" id="secs">--</div><div class="dl-label">{{ __('tenders.second') }}</div></div>
        @endif
      </div>
    </div>

    <!-- POSTER -->
    <div class="poster-card">
      <h4><i class="bi bi-person-circle text-secondary"></i> {{ __('tenders.owner') }}</h4>
      <div class="poster-info">
        <div class="poster-avatar">
            @if($tender->user && $tender->user->hasMedia('profile_image'))
                <img src="{{ $tender->user->getFirstMediaUrl('profile_image') }}" alt="" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
            @else
                {{ mb_substr($tender->user->name ?? 'م', 0, 1) }}
            @endif
        </div>
        <div>
          <div class="poster-name">{{ $tender->user->name ?? __('tenders.unknown_user') }}</div>
          <div class="poster-city"><i class="bi bi-geo-alt-fill text-danger"></i> 
            {{ $tender->user->city ? $tender->user->city->name : __('tenders.not_specified') }}
          </div>
        </div>
      </div>
      @if($tender->user)
      <a href="{{ route('member.public', $tender->user->id) }}" class="btn-contact text-decoration-none d-block text-center mt-3">
        <i class="bi bi-person text-primary"></i> {{ __('tenders.view_profile') }}
      </a>
      @endif
    </div>

    <!-- SHARE -->
    <div style="background:#fff;border-radius:16px;border:1.5px solid #e5e7eb;padding:1.25rem;">
      <div style="font-size:14px;font-weight:700;color:#1a3a2a;margin-bottom:10px;"><i class="bi bi-link-45deg"></i> {{ __('tenders.share') }}</div>
      <div style="display:flex;gap:8px;">
        <button onclick="copyLink(event)" style="flex:1;padding:9px;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:8px;font-size:12px;font-weight:600;font-family:'Tajawal',sans-serif;cursor:pointer;color:#374151;"><i class="bi bi-clipboard"></i> {{ __('tenders.copy_link') }}</button>
        <a href="https://wa.me/?text={{ urlencode(__('tenders.wa_share') . route('website.tenders.show', $tender->id)) }}" target="_blank" style="flex:1;padding:9px;background:#dcfce7;border:1.5px solid #b6ddc8;border-radius:8px;font-size:12px;font-weight:600;font-family:'Tajawal',sans-serif;cursor:pointer;color:#15803d; text-align:center; text-decoration:none;"><i class="bi bi-whatsapp text-success"></i> {{ __('tenders.whatsapp') }}</a>
      </div>
    </div>

  </div>
    </div>
  </div>
</div>

<!-- Rating Modal -->
@if($tender->status === \App\Models\Tender::STATUS_COMPLETED && auth()->check() && auth()->id() === $tender->user_id)
<div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('website.tenders.rate', $tender->id) }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="ratingModalLabel"><i class="bi bi-star-fill text-warning"></i> {{ __('website.rate_provider') ?? 'تقييم المورد' }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3 text-center">
                <label class="form-label d-block fw-bold">{{ __('website.select_rating') ?? 'اختر التقييم (من 1 إلى 5):' }}</label>
                <div class="d-flex justify-content-center gap-3 rating-stars" style="direction:ltr;">
                    <input type="radio" name="score" value="1" id="star1" required> <label for="star1" class="text-warning fs-3"><i class="bi bi-star-fill"></i></label>
                    <input type="radio" name="score" value="2" id="star2"> <label for="star2" class="text-warning fs-3"><i class="bi bi-star-fill"></i></label>
                    <input type="radio" name="score" value="3" id="star3"> <label for="star3" class="text-warning fs-3"><i class="bi bi-star-fill"></i></label>
                    <input type="radio" name="score" value="4" id="star4"> <label for="star4" class="text-warning fs-3"><i class="bi bi-star-fill"></i></label>
                    <input type="radio" name="score" value="5" id="star5"> <label for="star5" class="text-warning fs-3"><i class="bi bi-star-fill"></i></label>
                </div>
            </div>
            <div class="mb-3">
                <label for="comment" class="form-label fw-bold">{{ __('website.rating_comment') ?? 'تعليق (اختياري):' }}</label>
                <textarea class="form-control" name="comment" id="comment" rows="3"></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('website.cancel') ?? 'إلغاء' }}</button>
          <button type="submit" class="btn btn-primary">{{ __('website.submit_rating') ?? 'إرسال التقييم' }}</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif

@endsection

@push('js')
<script>
// CSRF Token for AJAX
const csrfToken = '{{ csrf_token() }}';

// Toggle Save Tender AJAX
window.toggleSaveTender = function(tenderId) {
    const btn = document.getElementById('saveTenderBtn');
    const icon = document.getElementById('saveIcon');
    const text = document.getElementById('saveText');
    
    // Disable temporarily
    btn.style.pointerEvents = 'none';
    btn.style.opacity = '0.6';

    fetch(`/tenders/${tenderId}/save`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'saved') {
            icon.className = 'bi bi-bookmark-fill text-primary';
            text.textContent = '{{ __("tenders.remove_saved") }}';
        } else {
            icon.className = 'bi bi-bookmark';
            text.textContent = '{{ __("tenders.save_tender") }}';
        }
    })
    .catch(error => {
        console.error('Error saving tender:', error);
        alert('{{ __("tenders.error_try_again") }}');
    })
    .finally(() => {
        btn.style.pointerEvents = 'auto';
        btn.style.opacity = '1';
    });
}

// COPY LINK
window.copyLink = function(event) {
  navigator.clipboard.writeText(window.location.href).catch(()=>{});
  const btn = event.target;
  const origText = btn.innerHTML;
  btn.innerHTML = '<i class="bi bi-check-lg text-success"></i> {{ __("tenders.copied") }}';
  setTimeout(()=>{ btn.innerHTML = origText; }, 2000);
}

// POPUP ALERTS FROM SESSION
@if(session('error_popup') == 'payment_or_subscription_required')
    document.addEventListener("DOMContentLoaded", function() {
        if(typeof showSubscriptionPopup === 'function') {
            showSubscriptionPopup();
        } else {
            alert('{{ __("tenders.apply_sub_required") }}');
        }
    });
@endif

// COUNTDOWN TIMER
@if(!$tender->isExpired() && $tender->ends_at)
    // Pass timestamp in milliseconds from backend to JS
    const deadlineTimestamp = {{ $tender->ends_at->timestamp * 1000 }};
    
    function updateTimer() {
        const now = new Date().getTime();
        const diff = deadlineTimestamp - now;
        
        if(diff <= 0) {
            document.getElementById('countdown-timer').innerHTML = '<div style="text-align: center; width: 100%; color: #ef4444; font-weight: bold; font-size: 1.1rem; padding: 10px 0;">{{ __("tenders.time_ended") }}</div>';
            return;
        }
        
        const d = Math.floor(diff / 86400000);
        const h = Math.floor((diff % 86400000) / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);
        
        const elDays = document.getElementById('days');
        const elHours = document.getElementById('hours');
        const elMins = document.getElementById('mins');
        const elSecs = document.getElementById('secs');
        
        if(elDays) elDays.textContent = String(d).padStart(2,'0');
        if(elHours) elHours.textContent = String(h).padStart(2,'0');
        if(elMins) elMins.textContent = String(m).padStart(2,'0');
        if(elSecs) elSecs.textContent = String(s).padStart(2,'0');
    }
    
    setInterval(updateTimer, 1000);
    updateTimer(); // Initial call
@endif
</script>
@endpush
