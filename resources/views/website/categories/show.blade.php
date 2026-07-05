@extends('layouts.website')

@section('content')
    <!-- Header Start -->
    <div class="category-header-section text-center services-header-section without-search">
        <h1 class="fw-bold mb-3 wow fadeInUp" data-wow-delay="0.1s">{{ $category->name }}</h1>
        <p class="mb-0 wow fadeInUp" data-wow-delay="0.2s">{{ $category->description ?? __('website.browse_best_providers_and_send_request') }}</p>
    </div>
    <!-- Header End -->

    <!-- Blue Call-to-Action Card -->
    <div class="container position-relative" style="margin-top: -50px; z-index: 10;">
        <div class="category-blue-card d-flex justify-content-between align-items-center flex-wrap gap-3 wow fadeInUp" data-wow-delay="0.3s">
            <div class="text-white d-flex align-items-center gap-3">
                <i class="bi bi-megaphone-fill text-white opacity-75" style="font-size: 2.5rem; {{ app()->getLocale() == 'ar' ? 'transform: scaleX(-1);' : '' }}"></i>
                <div class="text-start">
                    <h4 class="fw-bold mb-1">{{ __('website.send_request_to_all_providers_at_once') }}</h4>
                    <p class="mb-0" style="font-size: 0.95rem; color: #dbeafe;">{{ __('website.choose_section_and_receive_quotes') }}</p>
                </div>
            </div>
            <div>
                <a href="{{ route('requests.create', ['category' => $category->id]) }}" class="btn bg-white text-primary fw-bold py-2 px-4 rounded-3 d-flex align-items-center gap-2">
                    <i class="bi bi-box-seam"></i>
                    {{ __('website.send_bulk_request') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Search Section Start -->
    <div class="container mt-4">
        <div class="category-search-card wow fadeInUp" data-wow-delay="0.4s">
            <div class="d-flex align-items-center justify-content-start mb-4 gap-2">
                <i class="bi bi-search fs-5" style="color: #1a4331;"></i>
                <h5 class="fw-bold mb-0" style="color: #1a4331;">{{ __('website.search_for_provider') }}</h5>
            </div>
            
            <form action="{{ route('providers.search') }}" method="GET" class="row g-3">
                <!-- Ensure hidden input for category if required by providers.search -->
                <input type="hidden" name="category_id" value="{{ $category->id }}">
                
                <div class="col-md-3 text-start">
                    <label class="form-label text-muted small mb-1">{{ __('website.region') }}</label>
                    <select name="region_id" class="form-select select2 border-0 bg-light text-start">
                        <option value="">{{ __('website.makkah_region') }}</option>
                    </select>
                </div>
                
                <div class="col-md-3 text-start">
                    <label class="form-label text-muted small mb-1">{{ __('website.city') }}</label>
                    <select name="city_id" class="form-select select2 border-0 bg-light text-start">
                        <option value="">{{ __('website.jeddah') }}</option>
                        @if (isset($cities))
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                
                <div class="col-md-3 text-start">
                    <label class="form-label text-muted small mb-1">{{ __('website.supply_section') }}</label>
                    <select name="sub_category_id" class="form-select select2 border-0 bg-light text-start">
                        <option value="">{{ __('website.all_sections') }}</option>
                        @if (isset($subCategories))
                            @foreach ($subCategories as $sub)
                                <option value="{{ $sub->id }}" {{ request('sub_category_id') == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                
                <div class="col-md-3 text-start">
                    <label class="form-label text-muted small mb-1">{{ __('website.supply_volume') }}</label>
                    <select name="volume" class="form-select select2 border-0 bg-light text-start">
                        <option value="">{{ __('website.all') }}</option>
                    </select>
                </div>
                
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-search-cat w-100 py-3 fw-bold rounded-3">
                        <i class="bi bi-search me-2"></i> {{ __('website.search_providers') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Search Section End -->

    <!-- Subcategories Tabs Start -->
    <div class="container mt-5">
        <h5 class="fw-bold text-start mb-4" style="color: #1a4331;">{{ __('website.browse_by_section') }}</h5>
        <div class="d-flex overflow-auto gap-3 pb-3 cat-tabs-container" style="white-space: nowrap;">
            <!-- All -->
            <a href="{{ route('website.category.show', $category->id) }}" class="cat-tab-card {{ !request('sub_category_id') ? 'active' : '' }}">
                <div class="cat-tab-icon mb-2">
                    <i class="bi bi-grid-fill"></i>
                </div>
                <h6 class="fw-bold mb-1">{{ __('website.all') }}</h6>
                <small class="text-muted">{{ $allProviders->count() ?? 0 }} {{ __('website.provider') }}</small>
            </a>

            @foreach($category->children as $sub)
            <a href="{{ route('website.category.show', ['category' => $category->id, 'sub_category_id' => $sub->id]) }}" class="cat-tab-card {{ request('sub_category_id') == $sub->id ? 'active' : '' }}">
                <div class="cat-tab-icon mb-2">
                    <img src="{{ $sub->getFirstMediaUrl('categories') ?: asset('website/assets/img/cat-1.png') }}" alt="" style="width: 40px; height: 40px; object-fit: contain;">
                </div>
                <h6 class="fw-bold mb-1">{{ $sub->name }}</h6>
                <small class="text-muted">{{ $sub->providers_count ?? rand(10, 50) }} {{ __('website.provider') }}</small>
            </a>
            @endforeach
        </div>
    </div>
    <!-- Subcategories Tabs End -->

    <!-- Providers Section Start -->
    <div class="container-fluid individuals-section py-5" id="providers-sidebar-section">
        <div class="container">
            <!-- Providers List Header -->
            <div class="providers-list-header d-flex justify-content-between align-items-end flex-wrap gap-3 wow fadeInUp" data-wow-delay="0.1s">
                <div>
                    <h2 class="fw-bold mb-1" id="providers-title" style="color: #1a4331;">
                        شركات {{ $category->name }}
                    </h2>
                    <p class="text-muted mb-0" style="font-size: 0.95rem;">
                        {{ $allProviders->count() ?? 0 }} شركة مسجلة في هذا القسم
                    </p>
                </div>
                <div>
                    <select class="form-select form-select-sm border-1 py-2 px-3 bg-light" style="border-radius: 8px; min-width: 150px;">
                        <option value="featured">المميزون أولاً</option>
                        <option value="rating">الأعلى تقييماً</option>
                        <option value="newest">الأحدث</option>
                    </select>
                </div>
            </div>

            <div class="wow fadeIn" id="providerTabsContent" data-wow-delay="0.2s">
                @include('website.categories.partials._providers_tabs_content')
            </div>
        </div>
    </div>
    <!-- Providers Section End -->


@endsection
@push('js')
    <script>
        // Handle subcategory card clicks
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.subcategory-card:not(.subcategory-card-featured)');
            const featuredCard = document.querySelector('.subcategory-card-featured');

            // Function to check if featured card is visible
            function isFeaturedCardVisible() {
                if (!featuredCard) return false;
                const style = window.getComputedStyle(featuredCard);
                return style.display !== 'none' && style.visibility !== 'hidden';
            }

            // If mobile or featured card is hidden, allow normal link behavior
            if (!isFeaturedCardVisible()) {
                return;
            }

            const featuredIcon = featuredCard.querySelector('.subcategory-icon i');
            const featuredTitle = featuredCard.querySelector('.subcategory-featured-content h5');
            const featuredDesc = featuredCard.querySelector('.subcategory-featured-content p');
            const featuredBg = featuredCard.querySelector('.subcategory-featured-bg img');
            const featuredBtnText = featuredCard.querySelector('.btn-order-now span');

            let selectedSubCategoryId = null;

            // Set initial href for featured card
            if (featuredCard) {
                const mainCatId = featuredCard.getAttribute('data-main-category-id') || '{{ $category->id }}';
                featuredCard.setAttribute('href', "{{ route('requests.create') }}?category=" + mainCatId);
                
                // Add click listener for featured card to reset providers list
                featuredCard.addEventListener('click', function(e) {
                    if (this.classList.contains('active')) {
                        e.preventDefault();
                        return;
                    }
                    
                    e.preventDefault();
                    selectedSubCategoryId = null;
                    
                    // Reset UI
                    cards.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Reset featured content (if it was changed by subcategory)
                    const iconClass = 'bi-gear'; // Original main category icon
                    const title = '{{ $category->name }}';
                    const desc = '{{ Str::limit($category->description, 100) }}';
                    const image = '{{ $category->getFirstMediaUrl('categories') ?: asset('website/assets/img/cat-1.png') }}';
                    
                    if (featuredIcon) featuredIcon.className = 'bi ' + iconClass;
                    if (featuredTitle) featuredTitle.textContent = '{{ __('website.get_various_quotes') }}';
                    if (featuredDesc) featuredDesc.textContent = desc;
                    if (featuredBg) featuredBg.src = image;
                    if (featuredBtnText) featuredBtnText.textContent = '{{ __('website.submit_request_now') }}';
                    
                    // Fetch all providers for main category
                    const providersTabsContent = document.getElementById('providerTabsContent');
                    const providersTitle = document.getElementById('providers-title');
                    providersTabsContent.style.opacity = '0.5';
                    
                    fetch(`{{ route('website.category.show', $category->id) }}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        providersTabsContent.innerHTML = html;
                        providersTabsContent.style.opacity = '1';
                        // Re-activate current tab logic if needed, but here we just update content
                        providersTitle.textContent = `شركات ${title}`;
                    });
                });
            }

            // Handle Order Now button click
            const orderNowBtns = document.querySelectorAll('.btn-order-now');
            orderNowBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const mainCatId = featuredCard ? featuredCard.getAttribute(
                        'data-main-category-id') : '{{ $category->id }}';
                    let url = "{{ route('requests.create') }}?category=" + mainCatId;
                    if (selectedSubCategoryId) {
                        url += "&subcategory=" + selectedSubCategoryId;
                    }
                    window.location.href = url;
                });
            });

            cards.forEach(card => {
                card.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Get data from clicked card
                    const iconClass = this.getAttribute('data-card-icon');
                    const title = this.getAttribute('data-card-title');
                    const desc = this.getAttribute('data-card-desc');
                    const image = this.getAttribute('data-card-image');
                    selectedSubCategoryId = this.getAttribute('data-subcategory-id');

                    // Update featured card href
                    if (featuredCard) {
                        const mainCatId = featuredCard.getAttribute('data-main-category-id') ||
                            '{{ $category->id }}';
                        let newRequestUrl = "{{ route('requests.create') }}?category=" +
                            mainCatId + "&subcategory=" + selectedSubCategoryId;
                        featuredCard.setAttribute('href', newRequestUrl);
                    }

                    // Add fade out effect
                    if (featuredTitle) {
                        featuredTitle.style.opacity = '0';
                    }
                    if (featuredDesc) {
                        featuredDesc.style.opacity = '0';
                    }
                    if (featuredIcon) {
                        featuredIcon.style.opacity = '0';
                    }

                    // Update content after fade out
                    setTimeout(() => {
                        if (featuredIcon && iconClass) {
                            featuredIcon.className = 'bi ' + iconClass;
                        }

                        if (featuredTitle) {
                            featuredTitle.textContent = title;
                            featuredTitle.style.opacity = '1';
                        }

                        if (featuredBtnText) {
                            featuredBtnText.textContent = 'اطلب الآن';
                        }

                        if (featuredDesc) {
                            featuredDesc.textContent = desc;
                            featuredDesc.style.opacity = '1';
                        }

                        if (featuredIcon) {
                            featuredIcon.style.opacity = '1';
                        }

                        if (featuredBg && image) {
                            // Fade out image
                            featuredBg.style.opacity = '0';
                            setTimeout(() => {
                                featuredBg.src = image;
                                featuredBg.alt = title;
                                featuredBg.style.opacity = '1';
                            }, 200);
                        }

                        // AJAX fetch providers for this subcategory
                        const providersTabsContent = document.getElementById('providerTabsContent');
                        const providersTitle = document.getElementById('providers-title');
                        
                        // Show loading state
                        providersTabsContent.style.opacity = '0.5';
                        
                        fetch(`{{ route('website.category.show', $category->id) }}?sub_category_id=${selectedSubCategoryId}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.text())
                        .then(html => {
                            providersTabsContent.innerHTML = html;
                            providersTabsContent.style.opacity = '1';
                            providersTitle.textContent = `{{ __('website.companies_and_institutions') }} {{ __('website.for') }} ${title}`;
                        })
                        .catch(error => {
                            console.error('Error fetching providers:', error);
                            providersTabsContent.style.opacity = '1';
                        });

                    }, 200);

                    // Add active class to clicked card
                    cards.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Add transition styles
            if (featuredTitle) {
                featuredTitle.style.transition = 'opacity 0.3s ease';
            }
            if (featuredDesc) {
                featuredDesc.style.transition = 'opacity 0.3s ease';
            }
            if (featuredIcon) {
                featuredIcon.style.transition = 'opacity 0.3s ease';
            }
            if (featuredBg) {
                featuredBg.style.transition = 'opacity 0.3s ease';
            }
        });
    </script>
@endpush
