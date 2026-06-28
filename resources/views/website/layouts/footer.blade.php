@php
    $footerBgType = \App\Models\Setting::getValue('footer_background_type', null, 'image');
    $footerBgMedia = \App\Models\Setting::getMediaUrl('footer_background');
@endphp
<footer class="new-footer position-relative overflow-hidden" @if($footerBgType == 'image' && $footerBgMedia) style="background-image: url('{{ $footerBgMedia }}'); background-size: cover; background-position: center;" @endif>
    @if($footerBgType == 'video' && $footerBgMedia)
        <video autoplay loop muted playsinline class="position-absolute w-100 h-100" style="top: 0; left: 0; z-index: 0; object-fit: cover;">
            <source src="{{ $footerBgMedia }}" type="video/mp4">
        </video>
        <div class="position-absolute w-100 h-100" style="top: 0; left: 0; z-index: 0; background: rgba(0,0,0,0.4);"></div>
    @endif
    <div class="position-relative" style="z-index: 1;">

    <!-- Footer Content -->
    <div class="container">
        <div class="footer-content">
            <div class="container">
                <div class="row g-5">
                    <!-- Logo & Description -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-logo-section">
                            <a href="{{ route('home') }}">
                                @if (isset($settings['logo']) && $settings['logo'])
                                    <img src="{{ $settings['logo'] }}" alt="{{ $settings['site_name'] ?? 'URBELD' }}"
                                        class="footer-logo">
                                @else
                                    <img src="{{ asset('website/assets/img/footer-logo.png') }}" alt="URBELD Logo"
                                        class="footer-logo">
                                @endif
                            </a>
                            <p class="footer-description">
                                {{ \App\Models\Setting::getValue('footer_text', app()->getLocale(), __('website.footer_fallback_text')) }}
                            </p>
                        </div>
                    </div>

                    @php
                        $allPages = $footerPagesContent->merge($footerPagesLinks);
                        $half = ceil($allPages->count() / 2);
                        $col1 = $allPages->slice(0, $half);
                        $col2 = $allPages->slice($half);
                    @endphp

                    <!-- Links Column 1 -->
                    <div class="col-lg-2 col-md-3 col-6">
                        <h5 class="footer-title">{{ __('website.footer_quick_links') }}</h5>
                        <ul class="footer-links">
                            @foreach ($col1 as $page)
                                <li>
                                    <a
                                        href="{{ $page->type === 'link' ? $page->target_url : route('website.page.show', $page->slug) }}">
                                        {{ $page->title }}
                                    </a>
                                </li>
                            @endforeach
                            @if ($col1->isEmpty())
                                <li><a href="{{ route('about') }}">{{ __('website.footer_about') }}</a></li>
                                <li><a href="{{ route('website.categories.index') }}">{{ __('website.footer_categories') }}</a></li>
                                @if(Auth::check() && Auth::user()->isServiceProvider())
                                    <li><a href="{{ route('website.subscription-packages.index') }}">{{ __('website.nav_subscription_packages') }}</a></li>
                                @endif
                                <li><a href="{{ route('contact') }}">{{ __('website.footer_contact') }}</a></li>
                            @endif
                        </ul>
                    </div>

                    <!-- Links Column 2 -->
                    <div class="col-lg-2 col-md-3 col-6">
                        <h5 class="footer-title">{{ __('website.footer_our_services') }}</h5>
                        <ul class="footer-links">
                            @foreach ($col2 as $page)
                                <li>
                                    <a
                                        href="{{ $page->type === 'link' ? $page->target_url : route('website.page.show', $page->slug) }}">
                                        {{ $page->title }}
                                    </a>
                                </li>
                            @endforeach
                            @if ($col2->isEmpty())
                                <li><a href="{{ route('website.faq') }}">{{ __('website.footer_faq') }}</a></li>
                                <li><a href="{{ route('website.categories.index') }}">{{ __('website.footer_solutions') }}</a></li>
                            @endif
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div class="col-lg-4 col-md-6">
                        <h5 class="footer-title">{{ __('website.footer_contact_info') }}</h5>
                        <div class="footer-contact">
                            <p class="footer-phone">
                                <i class="bi bi-telephone-fill me-2 text-primary"></i>
                                <a href="tel:{{ $settings['site_phone'] ?? '' }}"
                                    style="color: inherit; text-decoration: none;">
                                    {{ $settings['site_phone'] ?? '+(084) 123-45688' }}
                                </a>
                            </p>
                            <p class="footer-email">
                                <i class="bi bi-envelope-fill me-2 text-primary"></i>
                                <a href="mailto:{{ $settings['site_email'] ?? '' }}"
                                    style="color: inherit; text-decoration: none;">
                                    {{ $settings['site_email'] ?? 'info@urbeld.sa' }}
                                </a>
                            </p>
                        </div>

                        <div class="footer-social">
                            <div class="footer-social-icons">
                                @foreach ($settings['socials'] as $social)
                                    <a href="{{ $social['url'] }}" target="_blank" title="{{ $social['name'] }}">
                                        {!! $social['icon'] !!}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Copyright -->
                <div class="footer-bottom">
<p>{!! __('website.footer_copyright', ['site_name' => $settings['site_name'] ?? __('website.site_name_fallback')]) !!}</p>
</div>
            </div>
        </div>
    </div>
    </div>
</footer>
<!-- Back to Top -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
