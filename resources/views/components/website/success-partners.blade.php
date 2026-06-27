<!-- Brands Slider Section Start -->
<div class="brands-slider-section py-5">
    <div class="container">
        <div class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">
            <h3 class="brands-title">{{ $title ?? 'نفخر بالشراكة مع عملاء من الطراز الأول' }}</h3>
        </div>

        <div class="brands-carousel owl-carousel wow fadeInUp" data-wow-delay="0.2s">
            @forelse($partners as $partner)
                <div class="brand-item">
                    <div class="brand-wrapper">
                        <img src="{{ $partner->getFirstMediaUrl('partners') ?: asset('website/assets/img/brand-1.png') }}"
                            alt="{{ $partner->title }}" title="{{ $partner->title }}">
                    </div>
                </div>
            @empty
                <div class="brand-item">
                    <div class="brand-wrapper">
                        <img src="{{ asset('website/assets/img/brand-1.png') }}" alt="Brand 1">
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
<!-- Brands Slider Section End -->
