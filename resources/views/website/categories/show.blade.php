@extends('layouts.website')

@section('content')
    <!-- Header Start -->
    <div class="services-header-section">
        <div class="container p-md-5 p-3 mb-md-5">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <h1 class="services-header-title wow fadeInUp" data-wow-delay="0.1s">
                        {{ $category->name }}
                    </h1>
                </div>
                <div class="col-lg-12 mt-md-5">
                    <p class="services-header-description wow fadeInUp" data-wow-delay="0.2s">
                        {{ $category->description ?? __('website.contact_intro') }}
                    </p>
                </div>
            </div>
        </div>
        <!-- Search Start -->
        <div class="search-section">
            <div class="container">
                <div class="search-content wow fadeIn" data-wow-delay="0.1s">
                    <form action="{{ route('providers.search') }}" method="GET">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <select name="category_id" class="form-select select2 border-0 py-3">
                                    <option value="{{ $category->id }}">{{ __('website.service_all') }}</option>
                                    @if (isset($subCategories))
                                        @foreach ($subCategories as $sub)
                                            <option value="{{ $sub->id }}"
                                                {{ request('sub_category_id') == $sub->id ? 'selected' : '' }}>
                                                {{ $sub->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="city_id" class="form-select select2 border-0 py-3">
                                    <option value="">{{ __('website.city_all') }}</option>
                                    @if (isset($cities))
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}"
                                                {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-icon py-3 px-5 me-3 animated fadeIn w-100">
                                    <span>{{ __('website.search') }}</span>
                                    <i class="icon-btn bi bi-arrow-up-left"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        <!-- Search End -->
    </div>
    <!-- Header End -->


    <!-- Services Category Start -->
    <div class="container-fluid bg-white services-section">
        <div class="container">

            <div class="subcategories-grid">
                @php
                    $children = $category->children;
                @endphp

                @forelse($children as $index => $subCategory)
                    @if ($index == 2)
                        <!-- Featured Card (Main Category - Inserted as 3rd card) -->
                        <a href="#" class="subcategory-card subcategory-card-featured wow fadeInUp"
                            data-wow-delay="0.3s" data-card-icon="bi-gear" data-card-title="{{ $category->name }}"
                            data-card-desc="{{ Str::limit($category->description, 100) }}"
                            data-card-image="{{ $category->getFirstMediaUrl('categories') ?: asset('website/assets/img/cat-1.png') }}"
                            data-main-category-id="{{ $category->id }}">
                            <div class="subcategory-featured-bg">
                                <img src="{{ $category->getFirstMediaUrl('categories') ?: asset('website/assets/img/cat-1.png') }}"
                                    alt="{{ $category->name }}">
                                <div class="subcategory-featured-overlay"></div>
                            </div>
                            <div class="subcategory-featured-content">
                                <div class="subcategory-icon">
                                    <i class="bi bi-gear"></i>
                                </div>
                                <h5>{{ __('website.get_various_quotes') }}</h5>
                                <p>{{ Str::limit($category->description, 100) }}</p>
                                <div class="btn-div text-center w-100 mt-5">
                                    <div class="btn-order-now-wrapper">
                                        <button class="btn-order-now" type="button">
                                            <span>{{ __('website.submit_request_now') }}</span>
                                        </button>
                                        <div class="btn-order-now-circle">
                                            <i class="bi bi-arrow-up-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endif

                    <!-- Subcategory Card -->
                    <a href="{{ route('requests.create', ['category' => $category->id, 'subcategory' => $subCategory->id]) }}"
                        class="subcategory-card wow fadeInUp"
                        data-wow-delay="{{ 0.1 * ($index > 1 ? $index + 2 : $index + 1) }}s" data-card-icon="bi-house"
                        data-card-title="{{ $subCategory->name }}"
                        data-card-desc="{{ Str::limit($subCategory->description, 60) }}"
                        data-card-image="{{ $subCategory->getFirstMediaUrl('categories') ?: asset('website/assets/img/cat-1.png') }}"
                        data-subcategory-id="{{ $subCategory->id }}">
                        <div class="subcategory-icon">
                            <i class="bi bi-house"></i>
                        </div>
                        <h5>{{ $subCategory->name }}</h5>
                        <p>{{ Str::limit($subCategory->description, 60) }}</p>
                    </a>

                @empty
                    <!-- Featured Card (Main Category - Shown when no children exist) -->
                    <a href="#" class="subcategory-card subcategory-card-featured wow fadeInUp" data-wow-delay="0.3s"
                        data-card-icon="bi-gear" data-card-title="{{ $category->name }}"
                        data-card-desc="{{ Str::limit($category->description, 100) }}"
                        data-card-image="{{ $category->getFirstMediaUrl('categories') ?: asset('website/assets/img/cat-1.png') }}"
                        data-main-category-id="{{ $category->id }}">
                        <div class="subcategory-featured-bg">
                            <img src="{{ $category->getFirstMediaUrl('categories') ?: asset('website/assets/img/cat-1.png') }}"
                                alt="{{ $category->name }}">
                            <div class="subcategory-featured-overlay"></div>
                        </div>
                        <div class="subcategory-featured-content">
                            <div class="subcategory-icon">
                                <i class="bi bi-gear"></i>
                            </div>
                            <h5>{{ __('website.get_various_quotes') }}</h5>
                            <p>{{ Str::limit($category->description, 100) }}</p>
                            <div class="btn-div text-center w-100 mt-5">
                                <div class="btn-order-now-wrapper">
                                    <button class="btn-order-now" type="button">
                                        <span>{{ __('website.submit_request_now') }}</span>
                                    </button>
                                    <div class="btn-order-now-circle">
                                        <i class="bi bi-arrow-up-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforelse

                @if ($children->count() > 0 && $children->count() <= 2)
                    <!-- Featured Card (Main Category - Shown when 1 or 2 children exist) -->
                    <a href="#" class="subcategory-card subcategory-card-featured wow fadeInUp"
                        data-wow-delay="{{ 0.1 * ($children->count() + 1) }}s" data-card-icon="bi-gear"
                        data-card-title="{{ $category->name }}"
                        data-card-desc="{{ Str::limit($category->description, 100) }}"
                        data-card-image="{{ $category->getFirstMediaUrl('categories') ?: asset('website/assets/img/cat-1.png') }}"
                        data-main-category-id="{{ $category->id }}">
                        <div class="subcategory-featured-bg">
                            <img src="{{ $category->getFirstMediaUrl('categories') ?: asset('website/assets/img/cat-1.png') }}"
                                alt="{{ $category->name }}">
                            <div class="subcategory-featured-overlay"></div>
                        </div>
                        <div class="subcategory-featured-content">
                            <div class="subcategory-icon">
                                <i class="bi bi-gear"></i>
                            </div>
                            <h5>{{ __('website.get_various_quotes') }}</h5>
                            <p>{{ Str::limit($category->description, 100) }}</p>
                            <div class="btn-div text-center w-100 mt-5">
                                <div class="btn-order-now-wrapper">
                                    <button class="btn-order-now" type="button">
                                        <span>{{ __('website.submit_request_now') }}</span>
                                    </button>
                                    <div class="btn-order-now-circle">
                                        <i class="bi bi-arrow-up-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </div>
    <!-- Services Category End -->

    @if ($individualProviders->count() > 0)
        <!-- Request Consultant Section Start -->
        <div class="container-fluid bg-light py-5 consultation-section">
            <div class="container">
                <div class="row align-items-center bg-white rounded shadow-sm p-4 mx-0 border-start border-primary border-5">
                    <div class="col-md-8">
                        <h3 class="fw-bold mb-2">{{ __('website.request_consultant') }}</h3>
                        <p class="text-muted mb-0">
                            {{ __('website.consultation_request_desc') }}
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('requests.create', ['category' => $category->id, 'is_consultation' => 1]) }}" 
                           class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow-sm animated pulse infinite">
                            <i class="bi bi-headset me-2"></i> {{ __('website.order_now') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Request Consultant Section End -->
    @endif

    <!-- Providers Section Start -->
    <div class="container-fluid bg-white individuals-section py-5" id="providers-sidebar-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-12">
                    <h1 class="individuals-section-title wow fadeInUp" data-wow-delay="0.1s" id="providers-title">
                        {{ __('website.companies_and_institutions') }} {{ __('website.for') }} {{ $category->name }}
                    </h1>
                    <p class="individuals-section-description wow fadeInUp" data-wow-delay="0.2s">
                        {{ __('website.providers_desc') }}
                    </p>
                </div>
            </div>

            <div class="wow fadeIn" id="providerTabsContent" data-wow-delay="0.4s">
                @include('website.categories.partials._providers_tabs_content')
            </div>

            <!-- Tabs End -->
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
                        providersTitle.textContent = `{{ __('website.companies_and_institutions') }} {{ __('website.for') }} ${title}`;
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
