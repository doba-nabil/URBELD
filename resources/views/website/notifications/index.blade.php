@extends('website.layouts.master')

@section('content')
 <!-- Header Start -->
    <div class="services-header-section without-search">
        <div class="container p-md-5 p-4 mb-md-5">
            <div class="row align-items-center">
                <div class="col-lg-12">

                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header bg-primary text-white p-4 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold">كافة الإشعارات</h4>
                    <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-light btn-sm fw-bold">تحديد الكل كمقروء</button>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($notifications as $notification)
                            @php
                                $title = $notification->title ?: ($notification->data['title'] ?? 'إشعار جديد');
                                $body = $notification->message ?: ($notification->data['body'] ?? '');
                                $link = $notification->link ?: ($notification->data['url'] ?? '#');
                            @endphp
                            <a href="{{ $link }}" class="list-group-item list-group-item-action p-4 border-0 border-bottom notification-item {{ $notification->is_read ? '' : 'bg-light' }} mark-as-read" data-id="{{ $notification->id }}">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-circle">
                                            <i class="bi bi-bell-fill"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0 fw-bold">{{ $title }}</h6>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-0 text-secondary">{{ $body }}</p>
                                    </div>
                                    @if(!$notification->is_read)
                                        <div class="ms-3">
                                            <span class="badge bg-primary rounded-pill" style="padding: 5px;">&nbsp;</span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-bell-slash fs-1 text-muted d-block mb-3"></i>
                                <h5 class="text-muted">لا يوجد إشعارات حالياً</h5>
                            </div>
                        @endforelse
                    </div>
                </div>
                @if($notifications->hasPages())
                    <div class="card-footer bg-white p-4">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .list-group-item-action:hover {
        background-color: #f8f9fa !important;
    }
    .bg-light {
        background-color: #f0f7ff !important;
    }
    .container-fluid.p-0{
        background-color: #fff;
    }
</style>

@push('js')
<script>
$(document).ready(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    $('.mark-as-read').on('click', function(e) {
        const id = $(this).data('id');
        const $item = $(this).closest('.notification-item');
        
        // We don't preventDefault here because we want the link to work
        // but we send the request in the background
        $.post(`/notifications/${id}/mark-as-read`, { _token: csrfToken });
    });
});
</script>
@endpush
@endsection
