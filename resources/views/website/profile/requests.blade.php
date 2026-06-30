@extends('website.layouts.profile')

@section('profile-content')
@php
    $activeRequests = $requests->whereNotIn('status', ['completed', 'work_completed', 'cancelled']);
    $completedRequests = $requests->whereIn('status', ['completed', 'work_completed']);
    
    // Group by category for the filter chips
    $categoriesCount = $requests->groupBy('category_id')->map(function ($group) {
        return [
            'count' => $group->count(), 
            'category' => $group->first()->category
        ];
    });
@endphp

<div class="orders-page-wrapper mt-4 mb-5">
    <div class="container">
        
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div class="d-flex flex-column align-items-start">
                <a href="{{ route('profile.edit') }}" class="back-link mb-2 text-decoration-none text-muted">
                    <i class="bi bi-arrow-right"></i> العودة للوحة الرئيسية
                </a>
                <h2 class="orders-page-title fw-bold">طلباتي</h2>
            </div>
            
            <div class="orders-tabs-toggle d-flex align-items-center bg-white rounded-pill p-1 shadow-sm border">
                <button class="btn rounded-pill px-4 active" id="active-tab" data-bs-toggle="pill" data-bs-target="#active-requests" type="button" role="tab" aria-selected="true">
                    النشطة ({{ $activeRequests->count() }})
                </button>
                <button class="btn rounded-pill px-4" id="completed-tab" data-bs-toggle="pill" data-bs-target="#completed-requests" type="button" role="tab" aria-selected="false">
                    المكتملة والملغاة ({{ $completedRequests->count() }})
                </button>
            </div>
        </div>

        <!-- Filter Chips Section -->
        <div class="orders-filter-chips mb-4 d-flex gap-2 flex-wrap">
            <button class="filter-chip active-chip" data-filter="all">
                الكل
            </button>
            @foreach($categoriesCount as $item)
                @php 
                    $cat = $item['category']; 
                    $color = $cat->color ?? '#064B3B'; 
                @endphp
                <button class="filter-chip" data-filter="cat-{{ $cat->id }}" style="--chip-color: {{ $color }}">
                    <i class="{{ $cat->icon ?? 'bi bi-tag' }}"></i>
                    {{ $cat->name }}
                    <span class="chip-count">{{ $item['count'] }}</span>
                </button>
            @endforeach
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Active Requests Tab -->
            <div class="tab-pane fade show active" id="active-requests" role="tabpanel">
                <div class="orders-list">
                    @forelse($activeRequests as $request)
                        @include('website.profile.partials.order-card', ['request' => $request])
                    @empty
                        <div class="alert alert-info text-center">لا توجد طلبات نشطة حالياً</div>
                    @endforelse
                </div>
            </div>

            <!-- Completed Requests Tab -->
            <div class="tab-pane fade" id="completed-requests" role="tabpanel">
                <div class="orders-list">
                    @forelse($completedRequests as $request)
                        @include('website.profile.partials.order-card', ['request' => $request])
                    @empty
                        <div class="alert alert-info text-center">لا توجد طلبات مكتملة حالياً</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab toggling logic for the custom pills
    const tabs = document.querySelectorAll('.orders-tabs-toggle button');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('active', 'btn-primary'));
            this.classList.add('active');
        });
    });

    // Category filter logic
    const filterChips = document.querySelectorAll('.filter-chip');
    filterChips.forEach(chip => {
        chip.addEventListener('click', function() {
            filterChips.forEach(c => c.classList.remove('active-chip'));
            this.classList.add('active-chip');
            
            const filter = this.getAttribute('data-filter');
            const allCards = document.querySelectorAll('.order-card-wrapper');
            
            allCards.forEach(card => {
                if (filter === 'all' || card.getAttribute('data-category-id') === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endpush
