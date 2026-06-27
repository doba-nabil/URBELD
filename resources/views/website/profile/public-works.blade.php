@extends('website.layouts.profile')

@section('title', __('admin.works_portfolio') ?? 'الأعمال السابقة')

@section('profile-content')
    <div class="wyn-services-container py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0 text-dark position-relative pb-2"
                    style="border-bottom: 3px solid #0056b3; display: inline-block;">
                    {{ __('admin.works_portfolio') ?? 'الأعمال السابقة' }}
                </h3>
            </div>

            <div class="row g-4">
                @forelse($works as $work)
                    <div class="col-12 mb-4">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                            <div class="card-body p-4">
                                <h4 class="fw-bold text-dark mb-3">{{ $work->title }}</h4>
                                <p class="text-muted mb-4" style="line-height: 1.8;">{!! nl2br(e($work->description)) !!}</p>

                                @if($work->hasMedia('work_images'))
                                    <div class="row g-3">
                                        @foreach($work->getMedia('work_images') as $media)
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <a href="{{ $media->getUrl() }}" target="_blank"
                                                    class="d-block position-relative rounded overflow-hidden shadow-sm"
                                                    style="padding-top: 100%;">
                                                    <img src="{{ $media->getUrl() }}" alt="{{ $work->title }}"
                                                        class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover transition-all"
                                                        style="transition: transform 0.3s;"
                                                        onmouseover="this.style.transform='scale(1.05)'"
                                                        onmouseout="this.style.transform='scale(1)'">
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm">
                        <div class="empty-state-icon mb-3">
                            <i class="bi bi-images text-muted" style="font-size: 4rem; opacity: 0.5;"></i>
                        </div>
                        <h4 class="text-muted mb-2">{{ __('admin.no_works_available') ?? 'لا توجد أعمال سابقة' }}</h4>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection