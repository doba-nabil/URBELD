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

            <a href="{{ route('profile.complete') }}" class="profile-card">
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

            <a href="{{ route('profile.tenders') }}" class="profile-card">
                <span class="profile-card-dot"></span>
                <div class="profile-card-icon icon-orange">
                    <i class="bi bi-balance-scale"></i>
                </div>
                <h3 class="profile-card-title">المناقصات</h3>
                <p class="profile-card-subtitle">طرح المناقصات واستقبال العروض</p>
            </a>

            <a href="#" class="profile-card" data-bs-toggle="modal" data-bs-target="#contactModal">
                <div class="profile-card-icon icon-blue">
                    <i class="bi bi-headset"></i>
                </div>
                <h3 class="profile-card-title">مركز المساعدة</h3>
                <p class="profile-card-subtitle">تجد شروحات متنوعة وفريق خدمة عملاء لتلقي إستفساراتك ومقترحاتك</p>
            </a>

            <a href="{{ route('chat.index') }}" class="profile-card">
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

    <!-- Contact Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="contactModalLabel">{{ __('website.contact_help_center') ?? 'تواصل مع مركز المساعدة' }}</h5>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('website.contact.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('website.name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('website.email') }}</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">{{ __('website.phone') }}</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ auth()->user()->phone }}">
                        </div>
                        <div class="mb-3">
                            <label for="contact_type" class="form-label">{{ __('website.contact_type') ?? 'نوع الرسالة' }}</label>
                            <select class="form-select" id="contact_type" name="type" required>
                                <option value="inquiry">{{ __('website.inquiry') ?? 'استفسار' }}</option>
                                <option value="help">{{ __('website.help') ?? 'مساعدة' }}</option>
                                <option value="other">{{ __('website.other') ?? 'أخرى' }}</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">{{ __('website.message_or_inquiry') ?? 'الرسالة أو الاستفسار' }} <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('website.cancel') ?? 'إلغاء' }}</button>
                            <button type="submit" class="btn btn-primary px-4">{{ __('website.send_message') ?? 'إرسال الرسالة' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
