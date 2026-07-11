@extends('website.layouts.profile')

@section('profile-content')
@php
    $newRequests = $requests->whereNotIn('status', ['completed', 'work_completed', 'cancelled']);
    $processingRequests = $requests->whereIn('status', ['completed', 'work_completed']);
    
    // Group by category for the filter chips
    $categoriesCount = $requests->groupBy('category_id')->map(function ($group) {
        return [
            'count' => $group->count(), 
            'category' => $group->first()->category
        ];
    });
@endphp

<div class="incoming-requests-wrapper mt-4 mb-5" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="container bg-light rounded-4 p-4 shadow-sm" style="background-color: #f8f9fa !important;">
        
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0 text-dark">الطلبات الواردة الجديدة</h3>
            <a href="{{ route('profile.edit') }}" class="text-decoration-none text-muted small fw-bold">
                العودة للوحة التحكم <i class="bi bi-arrow-left ms-1"></i>
            </a>
        </div>

        <!-- Filter Chips Section -->
        <div class="d-flex justify-content-start mb-4">
            <div class="d-flex gap-2 flex-wrap">
                <button class="ir-filter-chip active" data-filter="all" style="background-color: #143526; color: white; border-color: #143526;">
                    الكل <span class="ir-chip-count badge rounded-pill ms-1" style="background-color: #ffffff30; color: white;">{{ $requests->count() }}</span>
                </button>
                @foreach($categoriesCount as $item)
                    @php 
                        $cat = $item['category']; 
                        $color = $cat->color ?? '#6c757d';
                        if($cat->name == 'استشارة قانونية عقارية' || str_contains($cat->name, 'قانوني')) $color = '#a855f7';
                        elseif($cat->name == 'استخراج الرخص والموافقات' || str_contains($cat->name, 'هندسية')) $color = '#3b82f6';
                        elseif($cat->name == 'توريد مواد البناء' || str_contains($cat->name, 'توريد')) $color = '#10b981';
                        elseif(str_contains($cat->name, 'مقاولات')) $color = '#f59e0b';
                    @endphp
                    <button class="ir-filter-chip" data-filter="cat-{{ $cat->id }}" style="--chip-color: {{ $color }}; color: {{ $color }}; border-color: {{ $color }}; background-color: white;">
                        <i class="{{ $cat->icon ?? 'bi bi-tag' }}"></i>
                        {{ $cat->name }} 
                        <span class="ir-chip-count badge rounded-pill ms-1" style="background-color: #f3f4f6; color: #6b7280;">{{ $item['count'] }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="d-flex justify-content-start mb-4 border-bottom">
            <button class="btn border fw-bold px-5 py-2 ir-tab-active bg-white" id="new-tab" data-bs-toggle="pill" data-bs-target="#new-requests" type="button" role="tab" aria-selected="true" style="border-bottom: none !important; border-bottom-left-radius: 0; border-bottom-right-radius: 0; color: #1f2937;">
                الجديدة ({{ $newRequests->count() }})
            </button>
            <button class="btn border-0 fw-bold px-5 py-2 text-muted" id="processing-tab" data-bs-toggle="pill" data-bs-target="#processing-requests" type="button" role="tab" aria-selected="false">
                المعالجة ({{ $processingRequests->count() }})
            </button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- New Requests Tab -->
            <div class="tab-pane fade show active" id="new-requests" role="tabpanel">
                <div class="ir-orders-list">
                    @forelse($newRequests as $request)
                        @include('website.profile.partials.order-card', ['request' => $request])
                    @empty
                        <div class="alert alert-info text-center rounded-3 border-0">لا توجد طلبات جديدة حالياً</div>
                    @endforelse
                </div>
            </div>

            <!-- Processing Requests Tab -->
            <div class="tab-pane fade" id="processing-requests" role="tabpanel">
                <div class="ir-orders-list">
                    @forelse($processingRequests as $request)
                        @include('website.profile.partials.order-card', ['request' => $request])
                    @empty
                        <div class="alert alert-info text-center rounded-3 border-0">لا توجد طلبات قيد المعالجة حالياً</div>
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
    // Tabs logic
    const tabs = document.querySelectorAll('.incoming-requests-wrapper [data-bs-toggle="pill"]');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => {
                t.classList.remove('ir-tab-active', 'bg-white', 'border');
                t.classList.add('border-0', 'text-muted');
                t.style.color = '#6c757d';
                t.style.borderBottom = 'none';
            });
            this.classList.remove('border-0', 'text-muted');
            this.classList.add('ir-tab-active', 'bg-white', 'border');
            this.style.color = '#1f2937';
            this.style.borderBottomColor = 'white';
        });
    });

    // Category filter logic
    const filterChips = document.querySelectorAll('.ir-filter-chip');
    const orderCards = document.querySelectorAll('.ir-order-card-wrapper');

    filterChips.forEach(chip => {
        chip.addEventListener('click', function() {
            filterChips.forEach(c => {
                c.classList.remove('active');
                if(c.dataset.filter === 'all') {
                    c.style.backgroundColor = 'white';
                    c.style.color = '#143526';
                    c.querySelector('.ir-chip-count').style.backgroundColor = '#f3f4f6';
                    c.querySelector('.ir-chip-count').style.color = '#6b7280';
                } else {
                    c.style.backgroundColor = 'white';
                    c.style.color = c.style.getPropertyValue('--chip-color');
                    c.querySelector('.ir-chip-count').style.backgroundColor = '#f3f4f6';
                    c.querySelector('.ir-chip-count').style.color = '#6b7280';
                }
            });
            
            this.classList.add('active');
            if(this.dataset.filter === 'all') {
                this.style.backgroundColor = '#143526';
                this.style.color = 'white';
                this.querySelector('.ir-chip-count').style.backgroundColor = '#ffffff30';
                this.querySelector('.ir-chip-count').style.color = 'white';
            } else {
                this.style.backgroundColor = this.style.getPropertyValue('--chip-color');
                this.style.color = 'white';
                this.querySelector('.ir-chip-count').style.backgroundColor = '#ffffff30';
                this.querySelector('.ir-chip-count').style.color = 'white';
            }

            const filter = this.getAttribute('data-filter');

            orderCards.forEach(card => {
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
