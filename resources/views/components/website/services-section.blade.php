<!-- Services Category Start -->
<div class="container-fluid bg-white services-section">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 700px;">
            <span class="section-label">{{ $subtitle }}</span>
            <h1 class="mb-4 mt-3">{{ $title }}</h1>
        </div>

        <div class="services-grid">
            @forelse($categories as $category)
                <a href="{{ route('website.category.show', $category->id) }}" class="service-card-flip wow fadeInUp"
                    data-wow-delay="{{ 0.1 * $loop->iteration }}s">
                    <div class="service-arrow-flip-fixed">
                        <i class="bi bi-arrow-up-left"></i>
                    </div>
                    <div class="flip-card-inner">
                        <!-- Front Face -->
                        <div class="flip-card-front">
                            <div class="service-image-full-front">
                                <img src="{{ $category->getFirstMediaUrl('categories') ?: asset('website/assets/img/logo.png') }}"
                                    alt="{{ $category->name }}" class="service-bg-front">
                                <div class="service-overlay-green"></div>
                            </div>
                            <div class="service-content-front">
                                <h5>{{ $category->name }}</h5>
                                <p>{{ Str::limit($category->description, 60) }}</p>
                            </div>
                        </div>
                        <!-- Back Face -->
                        <div class="flip-card-back">
                            <div class="service-link">
                                <div class="service-content">
                                    <h5>{{ $category->name }}</h5>
                                    <p>{{ Str::limit($category->description, 60) }}</p>
                                </div>
                                <div class="service-image-wrapper">
                                    <img src="{{ $category->getFirstMediaUrl('categories') ?: asset('website/assets/img/logo.png') }}"
                                        alt="{{ $category->name }}" class="service-image">
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-12 text-center">
                    <p>لا توجد خدمات متاحة حالياً.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
<!-- Services Category End -->
