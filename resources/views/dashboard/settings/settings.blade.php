@extends('dashboard.layout.master')
@section('title', __('admin.settings'))

@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">{{ __('admin.site_settings') }}</h5>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form id="settingsForm" action="{{ route('settings.post') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Tabs Navigation -->
                            <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="general-tab" data-bs-toggle="tab"
                                        data-bs-target="#general" type="button" role="tab" aria-controls="general"
                                        aria-selected="true">{{ __('admin.general') }}</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="media-tab" data-bs-toggle="tab" data-bs-target="#media"
                                        type="button" role="tab" aria-controls="media"
                                        aria-selected="false">{{ __('admin.media') }}</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                                        type="button" role="tab" aria-controls="home" aria-selected="false">{{ __('admin.home_settings') }}</button>
                                </li>
                            </ul>

                            <div class="tab-content" id="settingsTabsContent">
                                <!-- General Tab -->
                                <div class="tab-pane fade show active" id="general" role="tabpanel"
                                    aria-labelledby="general-tab">
                                    <div class="row g-3">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">{{ __('admin.site_name_ar') }}</label>
                                            <input type="text" name="site_name[ar]" class="form-control"
                                                value="{{ old('site_name.ar', \App\Models\Setting::getValue('site_name', 'ar')) }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">{{ __('admin.site_name_en') }}</label>
                                            <input type="text" name="site_name[en]" class="form-control"
                                                value="{{ old('site_name.en', \App\Models\Setting::getValue('site_name', 'en')) }}">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">{{ __('admin.meta_title_ar') }}</label>
                                            <input type="text" name="meta_title[ar]" class="form-control"
                                                value="{{ old('meta_title.ar', \App\Models\Setting::getValue('meta_title', 'ar')) }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">{{ __('admin.meta_title_en') }}</label>
                                            <input type="text" name="meta_title[en]" class="form-control"
                                                value="{{ old('meta_title.en', \App\Models\Setting::getValue('meta_title', 'en')) }}">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">{{ __('admin.meta_description_ar') }}</label>
                                            <textarea name="meta_description[ar]" class="form-control" rows="3">{{ old('meta_description.ar', \App\Models\Setting::getValue('meta_description', 'ar')) }}</textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">{{ __('admin.meta_description_en') }}</label>
                                            <textarea name="meta_description[en]" class="form-control" rows="3">{{ old('meta_description.en', \App\Models\Setting::getValue('meta_description', 'en')) }}</textarea>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">{{ __('admin.contact_email') }}</label>
                                            <input type="email" name="contact_email" class="form-control"
                                                value="{{ old('contact_email', \App\Models\Setting::getValue('contact_email')) }}">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">{{ __('admin.contact_phone') }}</label>
                                            <input type="text" name="contact_phone" class="form-control"
                                                value="{{ old('contact_phone', \App\Models\Setting::getValue('contact_phone')) }}">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">{{ __('admin.contact_address_ar') }}</label>
                                            <input type="text" name="contact_address[ar]" class="form-control"
                                                value="{{ old('contact_address.ar', \App\Models\Setting::getValue('contact_address', 'ar')) }}">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">{{ __('admin.contact_address_en') }}</label>
                                            <input type="text" name="contact_address[en]" class="form-control"
                                                value="{{ old('contact_address.en', \App\Models\Setting::getValue('contact_address', 'en')) }}">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">{{ __('admin.longitude') }}</label>
                                            <input type="text" name="longitude" class="form-control"
                                                value="{{ old('longitude', \App\Models\Setting::getValue('longitude')) }}">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">{{ __('admin.latitude') }}</label>
                                            <input type="text" name="latitude" class="form-control"
                                                value="{{ old('latitude', \App\Models\Setting::getValue('latitude')) }}">
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <div class="form-check form-switch pt-2">
                                                <input class="form-check-input" type="checkbox" name="is_subscription_enabled" value="1" id="is_subscription_enabled"
                                                    {{ \App\Models\Setting::getValue('is_subscription_enabled') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_subscription_enabled">
                                                    <strong>{{ __('admin.is_subscription_enabled') }}</strong>
                                                </label>
                                            </div>
                                            <small class="text-muted">{{ __('admin.is_subscription_enabled_hint') }}</small>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('admin.footer_text') }}</label>
                                            <textarea name="footer_text" class="form-control" rows="3">{{ old('footer_text', \App\Models\Setting::getValue('footer_text')) }}</textarea>
                                        </div>

                                        <div class="col-12 mt-4">
                                            <h6 class="mb-3">{{ __('admin.social_media') }}</h6>
                                            <div id="socialsWrapper">
                                                @foreach ($socials as $index => $social)
                                                    <div class="row g-3 mb-2 social-row">
                                                        <div class="col-md-4">
                                                            <input type="text"
                                                                name="socials[{{ $index }}][name]"
                                                                class="form-control" placeholder="{{ __('admin.name') }}"
                                                                value="{{ $social['name'] ?? '' }}">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="url"
                                                                name="socials[{{ $index }}][url]"
                                                                class="form-control" placeholder="{{ __('admin.link') }}"
                                                                value="{{ $social['url'] ?? '' }}">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="text"
                                                                name="socials[{{ $index }}][icon]"
                                                                class="form-control"
                                                                placeholder='<i class="fa-brands fa-facebook-f"></i>'
                                                                value="{{ $social['icon'] ?? '' }}">
                                                        </div>
                                                        <div class="col-md-1">
                                                            <button type="button"
                                                                class="btn btn-danger remove-social">X</button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button type="button" class="btn btn-secondary mt-2" id="addSocial">{{ __('admin.add_new') }}</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Media Tab -->
                                <div class="tab-pane fade" id="media" role="tabpanel" aria-labelledby="media-tab">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('admin.logo_ar') }}</label>
                                            <input type="file" name="logo_ar" class="form-control" accept="image/*" onchange="previewBgFile(this, 'logo-ar-preview')">
                                            @if(!empty($logoArUrl))
                                                <div id="logo-ar-preview" class="mt-2">
                                                    <img src="{{ $logoArUrl }}" alt="Logo AR" style="max-height:80px;border-radius:4px;object-fit:contain;">
                                                </div>
                                            @else
                                                <div id="logo-ar-preview" class="mt-2" style="display:none;"></div>
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('admin.logo_en') }}</label>
                                            <input type="file" name="logo_en" class="form-control" accept="image/*" onchange="previewBgFile(this, 'logo-en-preview')">
                                            @if(!empty($logoEnUrl))
                                                <div id="logo-en-preview" class="mt-2">
                                                    <img src="{{ $logoEnUrl }}" alt="Logo EN" style="max-height:80px;border-radius:4px;object-fit:contain;">
                                                </div>
                                            @else
                                                <div id="logo-en-preview" class="mt-2" style="display:none;"></div>
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('admin.favicon') }}</label>
                                            <input type="file" name="favicon" class="form-control" accept="image/*" onchange="previewBgFile(this, 'favicon-preview')">
                                            @if(!empty($faviconUrl))
                                                <div id="favicon-preview" class="mt-2">
                                                    <img src="{{ $faviconUrl }}" alt="Favicon" style="max-height:80px;border-radius:4px;object-fit:contain;">
                                                </div>
                                            @else
                                                <div id="favicon-preview" class="mt-2" style="display:none;"></div>
                                            @endif
                                        </div>

                                        <!-- Main Background -->
                                        <div class="col-12 mt-4">
                                            <h6 class="text-primary border-bottom pb-2">{{ __('admin.main_bg') }}</h6>
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">{{ __('admin.bg_type') }}</label>
                                                    <select name="main_background_type" class="form-select">
                                                        <option value="image" {{ \App\Models\Setting::getValue('main_background_type') == 'image' ? 'selected' : '' }}>{{ __('admin.image') }}</option>
                                                        <option value="video" {{ \App\Models\Setting::getValue('main_background_type') == 'video' ? 'selected' : '' }}>{{ __('admin.video') }}</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="form-label">{{ __('admin.bg_file') }}</label>
                                                    <input type="file" name="main_background" class="form-control" accept="image/*,video/*"
                                                        onchange="previewBgFile(this, 'main-bg-preview')">
                                                    @php
                                                        $mainBgIsVideo = $mainBackgroundUrl && preg_match('/\.(mp4|webm|ogg|mov)$/i', $mainBackgroundUrl);
                                                    @endphp
                                                    @if($mainBgIsVideo)
                                                        <div id="main-bg-preview" class="mt-2">
                                                            <p class="text-muted small mb-1">{{ __('admin.video') }} {{ __('admin.main_bg') }}:</p>
                                                            <video src="{{ $mainBackgroundUrl }}" controls muted loop
                                                                style="width:100%;max-height:200px;border-radius:8px;background:#000;"></video>
                                                        </div>
                                                    @elseif($mainBackgroundUrl)
                                                        <div id="main-bg-preview" class="mt-2">
                                                            <p class="text-muted small mb-1">{{ __('admin.image') }} {{ __('admin.main_bg') }}:</p>
                                                            <img src="{{ $mainBackgroundUrl }}" alt="main bg preview"
                                                                style="width:100%;max-height:150px;object-fit:cover;border-radius:8px;">
                                                        </div>
                                                    @else
                                                        <div id="main-bg-preview" class="mt-2" style="display:none;"></div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Footer Background -->
                                        <div class="col-12 mt-4">
                                            <h6 class="text-primary border-bottom pb-2">{{ __('admin.footer_bg') }}</h6>
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">{{ __('admin.bg_type') }}</label>
                                                    <select name="footer_background_type" class="form-select">
                                                        <option value="image" {{ \App\Models\Setting::getValue('footer_background_type') == 'image' ? 'selected' : '' }}>{{ __('admin.image') }}</option>
                                                        <option value="video" {{ \App\Models\Setting::getValue('footer_background_type') == 'video' ? 'selected' : '' }}>{{ __('admin.video') }}</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="form-label">{{ __('admin.bg_file') }}</label>
                                                    <input type="file" name="footer_background" class="form-control" accept="image/*,video/*"
                                                        onchange="previewBgFile(this, 'footer-bg-preview')">
                                                    @php
                                                        $footerBgIsVideo = $footerBackgroundUrl && preg_match('/\.(mp4|webm|ogg|mov)$/i', $footerBackgroundUrl);
                                                    @endphp
                                                    @if($footerBgIsVideo)
                                                        <div id="footer-bg-preview" class="mt-2">
                                                            <p class="text-muted small mb-1">{{ __('admin.video') }} {{ __('admin.footer_bg') }}:</p>
                                                            <video src="{{ $footerBackgroundUrl }}" controls muted loop
                                                                style="width:100%;max-height:200px;border-radius:8px;background:#000;"></video>
                                                        </div>
                                                    @elseif($footerBackgroundUrl)
                                                        <div id="footer-bg-preview" class="mt-2">
                                                            <p class="text-muted small mb-1">{{ __('admin.image') }} {{ __('admin.footer_bg') }}:</p>
                                                            <img src="{{ $footerBackgroundUrl }}" alt="footer bg preview"
                                                                style="width:100%;max-height:150px;object-fit:cover;border-radius:8px;">
                                                        </div>
                                                    @else
                                                        <div id="footer-bg-preview" class="mt-2" style="display:none;"></div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Home Settings Tab -->
                                <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">

                                    <!-- Hero Section -->
                                    <h6 class="text-primary mt-3 border-bottom pb-2">{{ __('admin.hero_section') }}</h6>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('admin.main_title_ar') }}</label>
                                            <input type="text" name="home_hero_title[ar]" class="form-control"
                                                value="{{ \App\Models\Setting::getValue('home_hero_title', 'ar') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('admin.main_title_en') }}</label>
                                            <input type="text" name="home_hero_title[en]" class="form-control"
                                                value="{{ \App\Models\Setting::getValue('home_hero_title', 'en') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('admin.btn_text_ar') }}</label>
                                            <input type="text" name="home_hero_btn_text[ar]" class="form-control"
                                                value="{{ \App\Models\Setting::getValue('home_hero_btn_text', 'ar') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('admin.btn_text_en') }}</label>
                                            <input type="text" name="home_hero_btn_text[en]" class="form-control"
                                                value="{{ \App\Models\Setting::getValue('home_hero_btn_text', 'en') }}">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">{{ __('admin.btn_link') }}</label>
                                            <input type="text" name="home_hero_btn_link" class="form-control"
                                                value="{{ \App\Models\Setting::getValue('home_hero_btn_link') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('admin.description_ar') }}</label>
                                            <textarea name="home_hero_desc[ar]" class="form-control" rows="2">{{ \App\Models\Setting::getValue('home_hero_desc', 'ar') }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('admin.description_en') }}</label>
                                            <textarea name="home_hero_desc[en]" class="form-control" rows="2">{{ \App\Models\Setting::getValue('home_hero_desc', 'en') }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('admin.hero_image') }}</label>
                                            <input type="file" name="home_hero_image" class="form-control" accept="image/*" onchange="previewBgFile(this, 'home-hero-preview')">
                                            @if(!empty($homeHeroImageUrl))
                                                <div id="home-hero-preview" class="mt-2">
                                                    <img src="{{ $homeHeroImageUrl }}" alt="Hero Image" style="max-height:100px;border-radius:4px;object-fit:contain;">
                                                </div>
                                            @else
                                                <div id="home-hero-preview" class="mt-2" style="display:none;"></div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Slider Section (About) -->
                                    <h5 class="mb-3 text-primary">{{ __('admin.slider_section') }}</h5>
                                    <div class="row">
                                        <div class="col-12 mt-3">
                                            <label class="form-label">{{ __('admin.slides') }}</label>
                                            <div id="aboutSlidesWrapper">
                                                @php
                                                    $aboutList = json_decode(
                                                        \App\Models\Setting::getValue('home_about_list', 'ar', '[]'),
                                                        true,
                                                    );
                                                    if (empty($aboutList)) {
                                                        $aboutList = [
                                                            [
                                                                'title' => 'المكان الأول للعثور على العقار المثالي',
                                                                'description' =>
                                                                    'نحن نساعدك في العثور على منزل أحلامك من خلال خدماتنا المتميزة. نوفر لك مجموعة واسعة من الخيارات العقارية التي تناسب احتياجاتك وميزانيتك. نلتزم بتقديم أفضل تجربة عقارية لعملائنا',
                                                                'points' =>
                                                                    "خدمات عقارية متكاملة\nفريق محترف وخبير\nأفضل الأسعار والعروض",
                                                                'image' => '',
                                                            ],
                                                        ];
                                                    }
                                                @endphp
                                                @foreach ($aboutList as $index => $item)
                                                    <div class="p-3 border rounded mb-3 bg-light position-relative about-slide-row">
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 remove-about-slide"
                                                            style="width: auto;">X</button>
                                                        <h6 class="slide-title-label">{{ __('admin.slide') }}</h6>
                                                        <div class="row g-3">
                                                            <div class="col-md-6 mb-2">
                                                                <label class="form-label">{{ __('admin.slide_title_ar') }}</label>
                                                                <input type="text"
                                                                    name="home_about_list[{{ $index }}][title][ar]"
                                                                    class="form-control" placeholder="{{ __('admin.slide_title_placeholder') }}"
                                                                    value="{{ is_string($item['title']['ar'] ?? null) ? $item['title']['ar'] : (is_string($item['title'] ?? null) ? $item['title'] : '') }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label class="form-label">{{ __('admin.slide_title_en') }}</label>
                                                                <input type="text"
                                                                    name="home_about_list[{{ $index }}][title][en]"
                                                                    class="form-control" placeholder="Slide Title"
                                                                    value="{{ is_string($item['title']['en'] ?? null) ? $item['title']['en'] : '' }}" required>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label class="form-label">{{ __('admin.slide_image') }}</label>
                                                                <input type="file" name="home_about_list[{{ $index }}][image]" class="form-control" accept="image/*" onchange="previewBgFile(this, 'about-preview-{{ $index }}')">
                                                                @php
                                                                    $slideImage = $item['image'] ?? (\App\Models\Setting::getMediaUrl('home_about_image_' . $index) ? \App\Models\Setting::getMediaUrl('home_about_image_' . $index) : '');
                                                                @endphp
                                                                @if(!empty($slideImage))
                                                                    <div id="about-preview-{{ $index }}" class="mt-2">
                                                                        <img src="{{ $slideImage }}" alt="Slide Image" style="max-height:80px;border-radius:4px;object-fit:contain;">
                                                                    </div>
                                                                @else
                                                                    <div id="about-preview-{{ $index }}" class="mt-2" style="display:none;"></div>
                                                                @endif
                                                                <input type="hidden" name="home_about_list[{{ $index }}][old_image]" value="{{ $item['image'] ?? '' }}">
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label class="form-label">{{ __('admin.slide_desc_ar') }}</label>
                                                                <input type="text"
                                                                    name="home_about_list[{{ $index }}][description][ar]"
                                                                    class="form-control" placeholder="{{ __('admin.slide_desc_placeholder') }}"
                                                                    value="{{ is_string($item['description']['ar'] ?? null) ? $item['description']['ar'] : (is_string($item['description'] ?? null) ? $item['description'] : '') }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label class="form-label">{{ __('admin.slide_desc_en') }}</label>
                                                                <input type="text"
                                                                    name="home_about_list[{{ $index }}][description][en]"
                                                                    class="form-control" placeholder="Slide Description"
                                                                    value="{{ is_string($item['description']['en'] ?? null) ? $item['description']['en'] : '' }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label class="form-label">{{ __('admin.btn_text_ar') }}</label>
                                                                <input type="text"
                                                                    name="home_about_list[{{ $index }}][btn_text][ar]"
                                                                    class="form-control" placeholder="اعرض الخدمات"
                                                                    value="{{ is_string($item['btn_text']['ar'] ?? null) ? $item['btn_text']['ar'] : (is_string($item['btn_text'] ?? null) ? $item['btn_text'] : '') }}">
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label class="form-label">{{ __('admin.btn_text_en') }}</label>
                                                                <input type="text"
                                                                    name="home_about_list[{{ $index }}][btn_text][en]"
                                                                    class="form-control" placeholder="View Services"
                                                                    value="{{ is_string($item['btn_text']['en'] ?? null) ? $item['btn_text']['en'] : '' }}">
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label class="form-label">{{ __('admin.btn_link') }}</label>
                                                                <input type="text"
                                                                    name="home_about_list[{{ $index }}][btn_link]"
                                                                    class="form-control" placeholder="رابط الزر"
                                                                    value="{{ $item['btn_link'] ?? '' }}">
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label class="form-label text-muted small">{{ __('admin.points_hint_ar') }}</label>
                                                                <textarea name="home_about_list[{{ $index }}][points][ar]" class="form-control" rows="3"
                                                                    placeholder="{{ __('admin.points_placeholder_ar') }}" required>{{ is_string($item['points']['ar'] ?? null) ? $item['points']['ar'] : (is_string($item['points'] ?? null) ? $item['points'] : '') }}</textarea>
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label class="form-label text-muted small">{{ __('admin.points_hint_en') }}</label>
                                                                <textarea name="home_about_list[{{ $index }}][points][en]" class="form-control" rows="3"
                                                                    placeholder="{{ __('admin.points_placeholder_en') }}" required>{{ is_string($item['points']['en'] ?? null) ? $item['points']['en'] : '' }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>
                                            <button type="button" class="btn btn-secondary mt-2"
                                                id="addAboutSlide">{{ __('admin.add_new_slide') }}</button>
                                        </div>
                                    </div>

                                    <!-- Video Section -->
                                    <h6 class="text-primary mt-3 border-bottom pb-2">{{ __('admin.video_section') }}</h6>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('admin.small_title_ar') }}</label>
                                            <input type="text" name="home_video_label[ar]" class="form-control"
                                                value="{{ \App\Models\Setting::getValue('home_video_label', 'ar') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('admin.small_title_en') }}</label>
                                            <input type="text" name="home_video_label[en]" class="form-control"
                                                value="{{ \App\Models\Setting::getValue('home_video_label', 'en') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('admin.main_title_ar') }}</label>
                                            <input type="text" name="home_video_title[ar]" class="form-control"
                                                value="{{ \App\Models\Setting::getValue('home_video_title', 'ar') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('admin.main_title_en') }}</label>
                                            <input type="text" name="home_video_title[en]" class="form-control"
                                                value="{{ \App\Models\Setting::getValue('home_video_title', 'en') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('admin.video_url') }}</label>
                                            <input type="url" name="home_video_url" class="form-control"
                                                value="{{ \App\Models\Setting::getValue('home_video_url') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('admin.or_upload_video') }}</label>
                                            <input type="file" name="home_video" class="form-control" accept="video/*" onchange="previewBgFile(this, 'home-video-preview')">
                                            @if(!empty($homeVideoUrl))
                                                <div id="home-video-preview" class="mt-2">
                                                    <video src="{{ $homeVideoUrl }}" controls style="max-height:100px;border-radius:4px;"></video>
                                                </div>
                                            @else
                                                <div id="home-video-preview" class="mt-2" style="display:none;"></div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('admin.cover_image') }}</label>
                                            <input type="file" name="home_video_cover" class="form-control" accept="image/*" onchange="previewBgFile(this, 'home-video-cover-preview')">
                                            @if(!empty($homeVideoCoverUrl))
                                                <div id="home-video-cover-preview" class="mt-2">
                                                    <img src="{{ $homeVideoCoverUrl }}" alt="Video Cover" style="max-height:100px;border-radius:4px;object-fit:contain;">
                                                </div>
                                            @else
                                                <div id="home-video-cover-preview" class="mt-2" style="display:none;"></div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Partners Section -->
                                    <h6 class="text-primary mt-3 border-bottom pb-2">{{ __('admin.partners_section') }}</h6>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('admin.section_title_ar') }}</label>
                                            <input type="text" name="home_partners_title[ar]" class="form-control"
                                                value="{{ \App\Models\Setting::getValue('home_partners_title', 'ar') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('admin.section_title_en') }}</label>
                                            <input type="text" name="home_partners_title[en]" class="form-control"
                                                value="{{ \App\Models\Setting::getValue('home_partners_title', 'en') }}">
                                        </div>
                                    </div>

                                    <!-- Commitments Section -->
                                    <h6 class="text-primary mt-3 border-bottom pb-2">{{ __('admin.commitments_section') }}</h6>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('admin.small_title_ar') }}</label>
                                            <input type="text" name="home_commitments_badge[ar]" class="form-control"
                                                value="{{ \App\Models\Setting::getValue('home_commitments_badge', 'ar') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('admin.small_title_en') }}</label>
                                            <input type="text" name="home_commitments_badge[en]" class="form-control"
                                                value="{{ \App\Models\Setting::getValue('home_commitments_badge', 'en') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('admin.main_title_ar') }}</label>
                                            <input type="text" name="home_commitments_title[ar]" class="form-control"
                                                value="{{ \App\Models\Setting::getValue('home_commitments_title', 'ar') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('admin.main_title_en') }}</label>
                                            <input type="text" name="home_commitments_title[en]" class="form-control"
                                                value="{{ \App\Models\Setting::getValue('home_commitments_title', 'en') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('admin.description_ar') }}</label>
                                            <textarea name="home_commitments_desc[ar]" class="form-control" rows="2">{{ \App\Models\Setting::getValue('home_commitments_desc', 'ar') }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('admin.description_en') }}</label>
                                            <textarea name="home_commitments_desc[en]" class="form-control" rows="2">{{ \App\Models\Setting::getValue('home_commitments_desc', 'en') }}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <h6 class="mb-3">{{ __('admin.features_list') }}</h6>
                                            <div id="commitmentsWrapper">
                                                @php
                                                    $commitments = json_decode(
                                                        \App\Models\Setting::getValue(
                                                            'home_commitments_list',
                                                            'ar',
                                                            '[]',
                                                        ),
                                                        true,
                                                    );
                                                @endphp
                                                @foreach ($commitments as $index => $commit)
                                                    <div
                                                        class="row g-3 mb-2 commitment-row border p-3 rounded position-relative">
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 remove-commitment"
                                                            style="width: auto;">X</button>
                                                        <div class="col-md-6">
                                                            <label class="form-label">{{ __('admin.title_ar') }}</label>
                                                            <input type="text"
                                                                name="home_commitments_list[{{ $index }}][title][ar]"
                                                                class="form-control"
                                                                value="{{ is_string($commit['title']['ar'] ?? null) ? $commit['title']['ar'] : (is_string($commit['title'] ?? null) ? $commit['title'] : '') }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">{{ __('admin.title_en') }}</label>
                                                            <input type="text"
                                                                name="home_commitments_list[{{ $index }}][title][en]"
                                                                class="form-control"
                                                                value="{{ is_string($commit['title']['en'] ?? null) ? $commit['title']['en'] : '' }}">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="form-label">{{ __('admin.image_optional') }}</label>
                                                            <input type="file"
                                                                name="home_commitments_list[{{ $index }}][image]"
                                                                class="form-control" accept="image/*">
                                                            <input type="hidden"
                                                                name="home_commitments_list[{{ $index }}][old_image]"
                                                                value="{{ $commit['image'] ?? '' }}">
                                                            @if (!empty($commit['image']))
                                                                <img src="{{ asset($commit['image']) }}" alt="preview"
                                                                    class="mt-2" style="height: 50px; width: auto;">
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">{{ __('admin.description_ar') }}</label>
                                                            <textarea name="home_commitments_list[{{ $index }}][description][ar]" class="form-control" rows="1">{{ is_string($commit['description']['ar'] ?? null) ? $commit['description']['ar'] : (is_string($commit['description'] ?? null) ? $commit['description'] : '') }}</textarea>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">{{ __('admin.description_en') }}</label>
                                                            <textarea name="home_commitments_list[{{ $index }}][description][en]" class="form-control" rows="1">{{ is_string($commit['description']['en'] ?? null) ? $commit['description']['en'] : '' }}</textarea>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button type="button" class="btn btn-secondary mt-2"
                                                id="addCommitment">{{ __('admin.add_new_feature') }}</button>
                                        </div>

                                        <!-- Contact Section -->
                                        <h6 class="text-primary mt-3 border-bottom pb-2">{{ __('admin.contact_section') }}</h6>
                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6">
                                                <label class="form-label">{{ __('admin.small_title_ar') }}</label>
                                                <input type="text" name="home_contact_badge[ar]" class="form-control"
                                                    value="{{ \App\Models\Setting::getValue('home_contact_badge', 'ar') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">{{ __('admin.small_title_en') }}</label>
                                                <input type="text" name="home_contact_badge[en]" class="form-control"
                                                    value="{{ \App\Models\Setting::getValue('home_contact_badge', 'en') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">{{ __('admin.main_title_ar') }}</label>
                                                <input type="text" name="home_contact_title[ar]" class="form-control"
                                                    value="{{ \App\Models\Setting::getValue('home_contact_title', 'ar') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">{{ __('admin.main_title_en') }}</label>
                                                <input type="text" name="home_contact_title[en]" class="form-control"
                                                    value="{{ \App\Models\Setting::getValue('home_contact_title', 'en') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">{{ __('admin.footer_text_contact_ar') }}</label>
                                                <textarea name="home_contact_desc[ar]" class="form-control" rows="2">{{ \App\Models\Setting::getValue('home_contact_desc', 'ar') }}</textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">{{ __('admin.footer_text_contact_en') }}</label>
                                                <textarea name="home_contact_desc[en]" class="form-control" rows="2">{{ \App\Models\Setting::getValue('home_contact_desc', 'en') }}</textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">{{ __('admin.btn_text_ar') }}</label>
                                                <input type="text" name="home_contact_btn[ar]" class="form-control"
                                                    value="{{ \App\Models\Setting::getValue('home_contact_btn', 'ar') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">{{ __('admin.btn_text_en') }}</label>
                                                <input type="text" name="home_contact_btn[en]" class="form-control"
                                                    value="{{ \App\Models\Setting::getValue('home_contact_btn', 'en') }}">
                                            </div>
                                        </div>

                                        <!-- Extras -->
                                        <h6 class="text-primary mt-3 border-bottom pb-2">{{ __('admin.extras') }}</h6>
                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6">
                                                <label class="form-label">{{ __('admin.latest_memberships_text_ar') }}</label>
                                                <textarea name="home_memmbership[ar]" class="form-control" rows="2">{{ \App\Models\Setting::getValue('home_memmbership', 'ar') }}</textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">{{ __('admin.latest_memberships_text_en') }}</label>
                                                <textarea name="home_memmbership[en]" class="form-control" rows="2">{{ \App\Models\Setting::getValue('home_memmbership', 'en') }}</textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">{{ __('admin.services_categories_section_title_ar') }}</label>
                                                <input type="text" name="home_services_title[ar]" class="form-control"
                                                    value="{{ \App\Models\Setting::getValue('home_services_title', 'ar') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">{{ __('admin.services_categories_section_title_en') }}</label>
                                                <input type="text" name="home_services_title[en]" class="form-control"
                                                    value="{{ \App\Models\Setting::getValue('home_services_title', 'en') }}">
                                            </div>
                                        </div>

                                    </div> <!-- End home tab-pane -->
                                </div> <!-- End tab-content -->

                                <div class="col-12 text-end mt-4">
                                    <button type="submit" class="btn btn-primary px-5">{{ __('admin.save_settings') }}</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('dashboard-head')
    @include('dashboard.partials.create.css')
    <style>
        video {
            border-radius: 0 0 10px 10px;
        }

        /* Red dot indicator on tab button when it contains invalid fields */
        .nav-link.tab-has-error {
            position: relative;
            color: #dc3545 !important;
        }
        .nav-link.tab-has-error::after {
            content: '';
            position: absolute;
            top: 4px;
            right: 4px;
            width: 8px;
            height: 8px;
            background: #dc3545;
            border-radius: 50%;
        }
    </style>
@endsection

@section('dashboard-footer')
    @include('dashboard.partials.edit.js')
    <script>
        'use strict';

        document.addEventListener('DOMContentLoaded', function() {
            function initDropzone(selector, inputName, currentUrl = null, acceptedFiles = "image/*") {
                Dropzone.autoDiscover = false;

                const dropzoneEl = document.querySelector(selector);
                if (!dropzoneEl) return;

                const myDropzone = new Dropzone(dropzoneEl, {
                    url: "{{ route('settings.upload_media') }}",
                    paramName: "file",
                    autoProcessQueue: true,
                    uploadMultiple: false,
                    maxFiles: 1,
                    acceptedFiles: acceptedFiles,
                    addRemoveLinks: true,
                    dictDefaultMessage: "{{ __('admin.Drop files here or click to upload') }}",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                });

                // Load existing media
                if (currentUrl) {
                    let mockFile = {
                        name: "Current Media",
                        size: 100
                    };
                    myDropzone.emit("addedfile", mockFile);
                    if (currentUrl.match(/\.(jpg|jpeg|png|gif|svg|webp|avif)$/i)) {
                        myDropzone.emit("thumbnail", mockFile, currentUrl);
                    } else if (currentUrl.match(/\.(mp4|webm|ogg|mov)$/i)) {
                        const thumbEl = dropzoneEl.querySelector('.dz-preview .dz-image img');
                        if (thumbEl) {
                            thumbEl.src = 'https://cdn-icons-png.flaticon.com/512/1179/1179069.png';
                        }
                    }
                    myDropzone.emit("complete", mockFile);
                    myDropzone.files.push(mockFile);
                    const previewImg = dropzoneEl.querySelector('.dz-preview img');
                    if (previewImg) {
                        previewImg.style.width = '100%';
                        previewImg.style.height = 'auto';
                        previewImg.style.maxHeight = '120px';
                        previewImg.style.objectFit = 'contain';
                    }
                }

                myDropzone.on("success", function(file, response) {
                    if (response.success) {
                        const pathName = inputName.includes('[') ? inputName.replace(/\]$/, '_path]') : `${inputName}_path`;
                        let inputPath = document.querySelector(`input[name='${pathName}']`);
                        if (!inputPath) {
                            inputPath = document.createElement("input");
                            inputPath.type = "hidden";
                            inputPath.name = pathName;
                            dropzoneEl.closest("form").appendChild(inputPath);
                        }
                        inputPath.value = response.path;
                    }
                });

                myDropzone.on("removedfile", function(file) {
                    const pathName = inputName.includes('[') ? inputName.replace(/\]$/, '_path]') : `${inputName}_path`;
                    const inputPath = document.querySelector(`input[name='${pathName}']`);
                    if (inputPath) inputPath.value = "";
                    
                    const inputFile = document.querySelector(`input[name='${inputName}']`);
                    if (inputFile) inputFile.value = "";
                });
            }

            // Removed old Dropzone intialization logic since we use native inputs now

            // New Dropzones for Background Settings
            initDropzone('#dropzone-main-bg', 'main_background', '{{ $mainBackgroundUrl ?? '' }}', 'image/*,video/*');
            initDropzone('#dropzone-footer-bg', 'footer_background', '{{ $footerBackgroundUrl ?? '' }}', 'image/*,video/*');


            // Social Media dynamic rows
            let socialIndex = {{ count($socials) }};
            document.getElementById('addSocial').addEventListener('click', function() {
                const wrapper = document.getElementById('socialsWrapper');
                const div = document.createElement('div');
                div.classList.add('row', 'g-3', 'mb-2', 'social-row');
                div.innerHTML = `
            <div class="col-md-4">
                <input type="text" name="socials[${socialIndex}][name]" class="form-control" placeholder="{{ __('admin.name') }}">
            </div>
            <div class="col-md-4">
                <input type="url" name="socials[${socialIndex}][url]" class="form-control" placeholder="{{ __('admin.link') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="socials[${socialIndex}][icon]" class="form-control" placeholder=' <i class="fa-brands fa-facebook-f"></i>'>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger remove-social">X</button>
            </div>
        `;
                wrapper.appendChild(div);
                socialIndex++;
            });

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-social')) {
                    e.target.closest('.social-row').remove();
                }
            });

            // Commitments dynamic list
            let commitmentIndex = {{ isset($commitments) ? count($commitments) : 0 }};
            document.getElementById('addCommitment').addEventListener('click', function() {
                const wrapper = document.getElementById('commitmentsWrapper');
                const div = document.createElement('div');
                div.classList.add('row', 'g-3', 'mb-2', 'commitment-row', 'border', 'p-3', 'rounded',
                    'position-relative');
                div.innerHTML = `
                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 remove-commitment" style="width: auto;">X</button>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('admin.title_ar') }}</label>
                        <input type="text" name="home_commitments_list[${commitmentIndex}][title][ar]" class="form-control" placeholder="{{ __('admin.title_ar') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('admin.title_en') }}</label>
                        <input type="text" name="home_commitments_list[${commitmentIndex}][title][en]" class="form-control" placeholder="{{ __('admin.title_en') }}">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">{{ __('admin.image') }} ({{ __('admin.optional') }})</label>
                        <input type="file" name="home_commitments_list[${commitmentIndex}][image]" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('admin.description_ar') }}</label>
                        <textarea name="home_commitments_list[${commitmentIndex}][description][ar]" class="form-control" rows="1" placeholder="{{ __('admin.description_ar') }}"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('admin.description_en') }}</label>
                        <textarea name="home_commitments_list[${commitmentIndex}][description][en]" class="form-control" rows="1" placeholder="{{ __('admin.description_en') }}"></textarea>
                    </div>
                `;
                wrapper.appendChild(div);
                commitmentIndex++;
            });

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-commitment')) {
                    e.target.closest('.commitment-row').remove();
                }
            });

            // About Slides (Slider under search) dynamic list
            let aboutSlideIndex = {{ isset($aboutList) ? count($aboutList) : 1 }};

            document.getElementById('addAboutSlide').addEventListener('click', function() {
                const wrapper = document.getElementById('aboutSlidesWrapper');
                const div = document.createElement('div');
                div.classList.add('p-3', 'border', 'rounded', 'mb-3', 'bg-light', 'position-relative',
                    'about-slide-row');

                const dropzoneId = `dropzone-about-${aboutSlideIndex}`;

                div.innerHTML = `
                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 remove-about-slide" style="width: auto;">X</button>
                    <h6 class="slide-title-label">{{ __('admin.slide') }}</h6>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">{{ __('admin.slide_title_ar') }}</label>
                            <input type="text" name="home_about_list[${aboutSlideIndex}][title][ar]" class="form-control" placeholder="{{ __('admin.slide_title_placeholder') }}" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">{{ __('admin.slide_title_en') }}</label>
                            <input type="text" name="home_about_list[${aboutSlideIndex}][title][en]" class="form-control" placeholder="{{ __('admin.slide_title_en') }}" required>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label">{{ __('admin.slide_image') }}</label>
                            <input type="file" name="home_about_list[${aboutSlideIndex}][image]" class="form-control" accept="image/*" onchange="previewBgFile(this, 'about-preview-${aboutSlideIndex}')">
                            <div id="about-preview-${aboutSlideIndex}" class="mt-2" style="display:none;"></div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">{{ __('admin.slide_desc_ar') }}</label>
                            <input type="text" name="home_about_list[${aboutSlideIndex}][description][ar]" class="form-control" placeholder="{{ __('admin.slide_desc_placeholder') }}" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">{{ __('admin.slide_desc_en') }}</label>
                            <input type="text" name="home_about_list[${aboutSlideIndex}][description][en]" class="form-control" placeholder="{{ __('admin.slide_desc_en') }}" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">{{ __('admin.btn_text_ar') }}</label>
                            <input type="text" name="home_about_list[${aboutSlideIndex}][btn_text][ar]" class="form-control" placeholder="اعرض الخدمات">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">{{ __('admin.btn_text_en') }}</label>
                            <input type="text" name="home_about_list[${aboutSlideIndex}][btn_text][en]" class="form-control" placeholder="View Services">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label">{{ __('admin.btn_link') }}</label>
                            <input type="text" name="home_about_list[${aboutSlideIndex}][btn_link]" class="form-control" placeholder="رابط الزر">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label text-muted small">{{ __('admin.points_hint_ar') }}</label>
                            <textarea name="home_about_list[${aboutSlideIndex}][points][ar]" class="form-control" rows="3" placeholder="نقطة... نقطة..." required></textarea>
                        </div>
                         <div class="col-md-6 mb-2">
                            <label class="form-label text-muted small">{{ __('admin.points_hint_en') }}</label>
                            <textarea name="home_about_list[${aboutSlideIndex}][points][en]" class="form-control" rows="3" placeholder="Point... Point..." required></textarea>
                        </div>
                    </div>
                `;
                wrapper.appendChild(div);

                // Removed dropzone init for new slide

                aboutSlideIndex++;
            });

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-about-slide')) {
                    e.target.closest('.about-slide-row').remove();
                }
            });


            // ─── Auto-open tab that contains an invalid required field ─────────────
            const settingsForm = document.getElementById('settingsForm');

            settingsForm.addEventListener('submit', function(e) {
                // Collect all invalid required fields
                const invalidFields = [];
                settingsForm.querySelectorAll('[required]').forEach(function(field) {
                    if (!field.checkValidity()) {
                        invalidFields.push(field);
                    }
                });

                if (invalidFields.length === 0) return; // all valid → let form submit normally

                e.preventDefault();

                // Remove previous error styling
                document.querySelectorAll('#settingsTabs .nav-link').forEach(function(btn) {
                    btn.classList.remove('tab-has-error');
                });

                // Mark ALL tabs that contain at least one error
                invalidFields.forEach(function(field) {
                    var pane = field.closest('.tab-pane');
                    if (pane) {
                        var id  = pane.getAttribute('id');
                        var btn = document.querySelector('#settingsTabs [data-bs-target="#' + id + '"]');
                        if (btn) btn.classList.add('tab-has-error');
                    }
                });

                // Find the tab that contains the FIRST invalid field
                var firstField   = invalidFields[0];
                var targetPane   = firstField.closest('.tab-pane');
                var targetTabBtn = targetPane
                    ? document.querySelector('#settingsTabs [data-bs-target="#' + targetPane.getAttribute('id') + '"]')
                    : null;

                if (targetTabBtn) {
                    var alreadyActive = targetTabBtn.classList.contains('active');

                    if (alreadyActive) {
                        // Tab is already visible → just report validity
                        firstField.reportValidity();
                    } else {
                        // Wait for Bootstrap to finish switching tabs, THEN report validity
                        targetPane.addEventListener('shown.bs.tab', function handler() {
                            targetPane.removeEventListener('shown.bs.tab', handler);
                            requestAnimationFrame(function() {
                                firstField.reportValidity();
                            });
                        });
                        new bootstrap.Tab(targetTabBtn).show();
                    }
                } else {
                    // Field is not inside a tab-pane, just report validity directly
                    firstField.reportValidity();
                }
            });

        });

        // ── Live preview for native background file inputs ────────────────────
        function previewBgFile(input, previewId) {
            var preview = document.getElementById(previewId);
            if (!preview) return;
            var file = input.files[0];
            if (!file) { preview.style.display = 'none'; return; }

            var url = URL.createObjectURL(file);
            var isVideo = file.type.startsWith('video/');

            if (isVideo) {
                preview.innerHTML = '<p class="text-muted small mb-1">{{ __("admin.video_preview") }}</p>' +
                    '<video src="' + url + '" controls muted loop ' +
                    'style="width:100%;max-height:200px;border-radius:8px;background:#000;"></video>';
            } else {
                preview.innerHTML = '<p class="text-muted small mb-1">{{ __("admin.image_preview") }}</p>' +
                    '<img src="' + url + '" style="width:100%;max-height:150px;object-fit:cover;border-radius:8px;">';
            }
            preview.style.display = 'block';
        }
    </script>
@endsection
