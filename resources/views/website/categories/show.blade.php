@extends('layouts.website')

@section('content')
    <div class="category-header-section services-header-section without-search">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-md-10">
                    <h1 class="fw-bold mb-3 text-white wow fadeInUp" data-wow-delay="0.1s">{{ $category->name }}</h1>
                    <p class="mb-0 text-white-50 wow fadeInUp" data-wow-delay="0.2s">{{ $category->description ?? __('website.browse_best_providers_and_send_request') }}</p>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="category-stats-bar wow fadeInUp" data-wow-delay="0.3s">
                <div class="d-flex justify-content-between text-center" style="max-width: 800px;">
                    <div class="stat-item">
                        <div class="text-white fw-bold fs-2 mb-1">{{ $stats['companies'] ?? 0 }}</div>
                        <div class="text-white-50 small">{{ __('website.registered_company') ?? 'شركة مسجلة' }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="text-white fw-bold fs-2 mb-1">{{ $stats['premium'] ?? 0 }}</div>
                        <div class="text-white-50 small">{{ __('website.premium_company') ?? 'شركة مميزة' }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="text-white fw-bold fs-2 mb-1">{{ $category->children->count() ?? 0 }}</div>
                        <div class="text-white-50 small">{{ __('website.subcategories') ?? 'أقسام فرعية' }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="text-white fw-bold fs-2 mb-1">94%</div>
                        <div class="text-white-50 small">{{ __('website.customer_satisfaction') ?? 'نسبة رضا العملاء' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- BROADCAST BANNER -->
    <div class="bc-wrap">
      <div class="bc-banner">
        <div class="bc-left">
          <div class="bc-icon-wrap"><i class="bi bi-megaphone-fill text-white"></i></div>
          <div>
            <div class="bc-title">{{ $category->bulk_request_title ?: __('website.send_request_to_all_providers_at_once') }}</div>
            <div class="bc-sub">{{ $category->bulk_request_subtitle ?: __('website.choose_section_and_receive_quotes') }}</div>
          </div>
        </div>
        <a href="{{ route('requests.create', ['category' => $category->id]) }}" class="btn-bc" style="text-decoration: none;"><i class="bi bi-send-fill me-1"></i> {{ $category->bulk_request_button_text ?: __('website.send_bulk_request') }}</a>
      </div>
    </div>

    <!-- FILTER -->
    <div class="filter-wrap">
      <div class="filter-card">
        <div class="filter-top">
          <div class="filter-title"><i class="bi bi-search me-1"></i> {{ __('website.search_for_provider') }}</div>
        </div>
        <form action="{{ route('providers.search') }}" method="GET">
            <input type="hidden" name="category_id" value="{{ $category->id }}">
            <div class="filter-grid" style="grid-template-columns: repeat(3, 1fr);">
              <div class="fg">
                <label>{{ __('website.region') }}</label>
                <select name="region_id" class="select2">
                  <option value="">{{ __('website.all') }}</option>
                  @if (isset($regions))
                      @foreach ($regions as $region)
                          <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                      @endforeach
                  @endif
                </select>
              </div>
              <div class="fg">
                <label>{{ __('website.city') }}</label>
                <select name="city_id" class="select2">
                  <option value="">{{ __('website.jeddah') }}</option>
                  @if (isset($cities))
                      @foreach ($cities as $city)
                          <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                      @endforeach
                  @endif
                </select>
              </div>
              <div class="fg">
                <label>{{ __('website.sub_category') ?? 'القسم الفرعي' }}</label>
                <select name="sub_category_id" id="subFilter" class="select2">
                  <option value="">{{ __('website.all_sections') }}</option>
                  @if (isset($subCategories))
                      @foreach ($subCategories as $sub)
                          <option value="{{ $sub->id }}" {{ request('sub_category_id') == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                      @endforeach
                  @endif
                </select>
              </div>
            </div>
            <div class="filter-actions">
              <button type="submit" class="btn-search"><i class="bi bi-search me-1"></i> {{ __('website.search_providers') }}</button>
            </div>
        </form>
      </div>
    </div>

    <!-- SUB-CATEGORIES -->
    <div class="sub-wrap">
      <div class="sub-title">{{ __('website.browse_by_section') }}</div>
      <div class="sub-grid">
        <a href="{{ route('website.category.show', $category->id) }}" class="sub-box {{ !request('sub_category_id') ? 'active' : '' }}">
          <div class="sub-icon"><i class="bi bi-grid-fill"></i></div>
          <div class="sub-label">{{ __('website.all') }}</div>
          <div class="sub-count">{{ $allProviders->count() ?? 0 }} شركة</div>
        </a>

        @foreach($category->children as $sub)
        <a href="{{ route('website.category.show', ['category' => $category->id, 'sub_category_id' => $sub->id]) }}" class="sub-box {{ request('sub_category_id') == $sub->id ? 'active' : '' }}">
          <div class="sub-icon">
             @if($sub->getFirstMediaUrl('categories'))
                 <img src="{{ $sub->getFirstMediaUrl('categories') }}" alt="" style="width: 28px; height: 28px; object-fit: contain;">
             @else
                 <i class="bi bi-folder-fill"></i>
             @endif
          </div>
          <div class="sub-label">{{ $sub->name }}</div>
          <div class="sub-count">{{ $sub->providers_count ?? 0 }} {{ __('website.company') ?? 'شركة' }}</div>
        </a>
        @endforeach
      </div>
    </div>

    <!-- COMPANIES -->
    <div class="companies-wrap">
      <div class="sec-header">
        <div class="sec-header-left">
          <h2 id="providers-title">شركات <span id="activeSubLabel">{{ $category->name }}</span></h2>
          <p id="countLabel">{{ $allProviders->count() ?? 0 }} شركة مسجّلة في هذا القسم</p>
        </div>
        <select class="sort-sel" id="sort-select">
          <option value="premium">{{ __('website.sort_premium') ?? 'المميّزون أولاً' }}</option>
          <option value="rating">{{ __('website.sort_rating') ?? 'الأعلى تقييماً' }}</option>
          <option value="newest">{{ __('website.sort_newest') ?? 'الأحدث تسجيلاً' }}</option>
        </select>
      </div>

      <div class="wow fadeIn" id="providerTabsContent" data-wow-delay="0.2s">
          @include('website.categories.partials._providers_tabs_content')
      </div>
    </div>


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
                    
                    const sortSelect = document.getElementById('sort-select');
                    const sortValue = sortSelect ? sortSelect.value : '';
                    let fetchUrl = `{{ route('website.category.show', $category->id) }}`;
                    if (sortValue) fetchUrl += `?sort=${sortValue}`;
                    
                    fetch(fetchUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        providersTabsContent.innerHTML = html;
                        providersTabsContent.style.opacity = '1';
                        providersTitle.innerHTML = `شركات <span id="activeSubLabel">${title}</span>`;
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
                        
                        const sortSelect = document.getElementById('sort-select');
                        const sortValue = sortSelect ? sortSelect.value : '';
                        let fetchUrl = `{{ route('website.category.show', $category->id) }}?sub_category_id=${selectedSubCategoryId}`;
                        if (sortValue) fetchUrl += `&sort=${sortValue}`;
                        
                        fetch(fetchUrl, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.text())
                        .then(html => {
                            providersTabsContent.innerHTML = html;
                            providersTabsContent.style.opacity = '1';
                            providersTitle.innerHTML = `شركات <span id="activeSubLabel">${title}</span>`;
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

            // Handle Sorting Dropdown Change (Using jQuery to support select2/nice-select if used)
            $('#sort-select').on('change', function() {
                const sortValue = $(this).val();
                let fetchUrl = `{{ route('website.category.show', $category->id) }}?sort=${sortValue}`;
                if(selectedSubCategoryId) {
                    fetchUrl += `&sub_category_id=${selectedSubCategoryId}`;
                }

                const providersTabsContent = document.getElementById('providerTabsContent');
                providersTabsContent.style.opacity = '0.5';
                    
                    fetch(fetchUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        providersTabsContent.innerHTML = html;
                        providersTabsContent.style.opacity = '1';
                    })
                    .catch(error => {
                        console.error('Error fetching providers:', error);
                        providersTabsContent.style.opacity = '1';
                    });
                });
            });

        });
    </script>
@endpush
