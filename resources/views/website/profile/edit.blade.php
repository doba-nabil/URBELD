@extends('website.layouts.profile')

@section('profile-content')
    <div class="container mt-4 mb-5">
        <div class="profile-dashboard-grid">
            <a href="{{ route('provider.requests.index') }}" class="profile-card">
                <span class="profile-card-badge">جديد</span>
                <div class="profile-card-icon icon-teal">
                    <i class="bi bi-inbox"></i>
                </div>
                <h3 class="profile-card-title">الطلبات الواردة</h3>
                <p class="profile-card-subtitle">استقبال وإدارة طلبات العملاء الجديدة</p>
            </a>

            <a href="{{ route('profile.requests') }}" class="profile-card">
                <div class="profile-card-icon icon-blue">
                    <i class="bi bi-clipboard-check"></i>
                </div>
                <h3 class="profile-card-title">طلباتي</h3>
                <p class="profile-card-subtitle">إدارة طلباتك النشطة والمكتملة</p>
            </a>

            <a href="#about-me-section" class="profile-card">
                <div class="profile-card-icon icon-green">
                    <i class="bi bi-person"></i>
                </div>
                <h3 class="profile-card-title">بياناتي</h3>
                <p class="profile-card-subtitle">تعديل وعرض معلوماتك المهنية والتصنيفات</p>
            </a>

            <a href="{{ route('profile.reports') }}" class="profile-card">
                <div class="profile-card-icon icon-red">
                    <i class="bi bi-bar-chart"></i>
                </div>
                <h3 class="profile-card-title">التقارير</h3>
                <p class="profile-card-subtitle">تقارير وإحصاءات العمليات والمشاريع</p>
            </a>

            <a href="{{ route('profile.subscription') }}" class="profile-card">
                <div class="profile-card-icon icon-purple">
                    <i class="bi bi-credit-card"></i>
                </div>
                <h3 class="profile-card-title">اشتراكاتي</h3>
                <p class="profile-card-subtitle">تفاصيل الباقة والاستهلاك والترقية</p>
            </a>

            <a href="#" class="profile-card">
                <span class="profile-card-dot"></span>
                <div class="profile-card-icon icon-orange">
                    <i class="bi bi-balance-scale"></i>
                </div>
                <h3 class="profile-card-title">المناقصات</h3>
                <p class="profile-card-subtitle">طرح المناقصات واستقبال العروض</p>
            </a>

            <a href="#" class="profile-card">
                <div class="profile-card-icon icon-blue">
                    <i class="bi bi-headset"></i>
                </div>
                <h3 class="profile-card-title">مركز المساعدة</h3>
                <p class="profile-card-subtitle">تجد شروحات متنوعة وفريق خدمة عملاء لتلقي إستفساراتك ومقترحاتك</p>
            </a>

            <a href="#" class="profile-card">
                <div class="profile-card-icon icon-blue">
                    <i class="bi bi-envelope"></i>
                </div>
                <h3 class="profile-card-title">الرسائل</h3>
                <p class="profile-card-subtitle">تابع الرسائل الواردة والصادرة مع الأعضاء</p>
            </a>

            @if(auth()->user()->isSupplier())
                <a href="{{ route('supplier.products.index') }}" class="profile-card">
                    <div class="profile-card-icon icon-indigo">
                        <i class="bi bi-box"></i>
                    </div>
                    <h3 class="profile-card-title">المنتجات</h3>
                    <p class="profile-card-subtitle">إدارة منتجاتك وأسعارها</p>
                </a>
                <a href="{{ route('supplier.offers.index') }}" class="profile-card">
                    <div class="profile-card-icon icon-indigo">
                        <i class="bi bi-tags"></i>
                    </div>
                    <h3 class="profile-card-title">العروض والخصومات</h3>
                    <p class="profile-card-subtitle">إدارة عروضك وخصوماتك للعملاء</p>
                </a>
                <a href="{{ route('supplier.cities.index') }}" class="profile-card">
                    <div class="profile-card-icon icon-indigo">
                        <i class="bi bi-map"></i>
                    </div>
                    <h3 class="profile-card-title">مدن التوصيل</h3>
                    <p class="profile-card-subtitle">تحديد المدن التي توفر التوصيل إليها</p>
                </a>
            @else
                <a href="{{ route('provider.works.index') }}" class="profile-card">
                    <div class="profile-card-icon icon-indigo">
                        <i class="bi bi-images"></i>
                    </div>
                    <h3 class="profile-card-title">{{ auth()->user()->isCompanyProvider() ? 'المشاريع' : 'معرض الأعمال' }}</h3>
                    <p class="profile-card-subtitle">إضافة وإدارة أعمالك السابقة (البورتفوليو)</p>
                </a>
            @endif
        </div>
    </div>

    <!-- About Me Section -->
    <div class="about-me-section">
        <div class="container">
            <h2 class="about-me-title">{{ __('website.about_me') }}</h2>
            <div class="about-me-content">
                <p class="about-me-text">
                    {{ auth()->user()->bio ?? __('website.no_bio') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Update Forms Section -->
    <div class="leave-reply-section">
        <div class="container">
            <div class="row justify-content-center">
                    <!-- Read-Only View -->
                    <div class="profile-readonly-container">
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-0">
                                <h5 class="mb-0 fw-bold text-primary">{{ __('website.personal_info') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <strong class="text-muted d-block mb-1">{{ __('website.name') }}:</strong>
                                        <div class="p-2 bg-light rounded">{{ auth()->user()->name }}</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong class="text-muted d-block mb-1">{{ __('website.email') }}:</strong>
                                        <div class="p-2 bg-light rounded">{{ auth()->user()->email }}</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong class="text-muted d-block mb-1">{{ __('website.phone') }}:</strong>
                                        <div class="p-2 bg-light rounded">{{ auth()->user()->phone ?? __('website.none') }}</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong class="text-muted d-block mb-1">{{ __('website.city') }}:</strong>
                                        <div class="p-2 bg-light rounded">{{ auth()->user()->city->name ?? __('website.none') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Forms View -->
                    <div class="profile-edit-form-container" style="display: none;">
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-0">
                                <h5 class="mb-0 fw-bold text-primary">{{ __('website.update_personal_info') }}</h5>
                            </div>
                        <div class="card-body">
                            @include('website.profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="profile-edit-form-container" style="display: none;">
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-0">
                                <h5 class="mb-0 fw-bold text-primary">{{ __('website.update_password') }}</h5>
                            </div>
                        <div class="card-body">
                            @include('website.profile.partials.update-password-form')
                        </div>
                    </div>

                    <div class="profile-edit-form-container" style="display: none;">
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-0">
                                <h5 class="mb-0 fw-bold text-danger">{{ __('website.delete_account') }}</h5>
                            </div>
                        <div class="card-body">
                            @include('website.profile.partials.delete-user-form')
                        </div>
                    </div>

                    <div class="card mb-4 shadow-sm border-0 mt-5">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0 fw-bold text-primary">{{ __('website.notification_settings') }}</h5>
                        </div>
                        <div class="card-body">
                            @include('website.profile.partials.notification-settings-form')
                        </div>
                    </div>
                    </div> <!-- End Edit Forms View container -->
                </div>
            </div>
        </div>
    </div>
@endsection

