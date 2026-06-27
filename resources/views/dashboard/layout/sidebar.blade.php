<aside id="layout-menu" class="layout-menu menu-vertical menu">
    <div class="app-brand demo">
        <a href="{{ url('admin-panel') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <span class="text-primary">
                    <img height="30" src="{{ \App\Models\Setting::getMediaUrl('logo_ar') ?: (\App\Models\Setting::getMediaUrl('favicon') ?: asset('dashboard/assets/img/favicon/fav-icon.png')) }}" alt="logo">
                </span>
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-3">URBELD</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
            <i class="icon-base ti tabler-x d-block d-xl-none"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        @if(auth('admin')->user()->can('home'))
            <li class="menu-item {{ request()->is('*admin-panel') && !request()->is('*reports*') ? 'active' : '' }}">
                <a href="{{ url('admin-panel') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-smart-home"></i>
                    <div>{{ __('admin.admin-panel') }}</div>
                </a>
            </li>
        @endif

        @if(auth('admin')->user()->can('reports'))
            <li class="menu-item {{ request()->is('*reports*') ? 'active' : '' }}">
                <a href="{{ route('admin.reports.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-chart-pie"></i>
                    <div>{{ __('admin.reports') }}</div>
                </a>
            </li>
        @endif

        @if(auth('admin')->user()->can('admins.index') || auth('admin')->user()->can('roles.index') || auth('admin')->user()->can('audits.index'))
            <li class="menu-item {{ request()->is('*admins*') || request()->is('*roles*') ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ti tabler-user-star"></i>
                    <div>{{ __('admin.our_staff') }}</div>
                </a>
                <ul class="menu-sub">
                    @if(auth('admin')->user()->can('admins.index'))
                        <li class="menu-item {{ request()->is('*admins*') ? 'active open' : '' }}">
                            <a href="{{ url('admin-panel/admins') }}" class="menu-link">
                                <div>{{ __('admin.supervisors') }}</div>
                            </a>
                        </li>
                    @endif

                    @if(auth('admin')->user()->can('roles.index'))
                        <li class="menu-item {{ request()->is('*roles*') ? 'active open' : '' }}">
                            <a href="{{ url('admin-panel/roles') }}" class="menu-link">
                                <div>{{ __('admin.Supervisors Privileges') }}</div>
                            </a>
                        </li>
                    @endif

                    @if(auth('admin')->user()->can('audits.index'))
                        <li class="menu-item {{ request()->is('*audits*') ? 'active' : '' }}">
                            <a href="{{ url('admin-panel/audits') }}" class="menu-link">
                                <div>{{ __('admin.audits') }}</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if(auth('admin')->user()->can('users.index'))
            <li class="menu-item {{ request()->is('*users*') ? 'active open' : '' }}">
                <a href="{{ url('admin-panel/users') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-users"></i>
                    <div>{{ __('admin.customers') }}</div>
                </a>
            </li>
        @endif

        @if(auth('admin')->user()->can('contacts.index') || auth('admin')->user()->can('search-logs.index') || auth('admin')->user()->can('notifications.index') || auth('admin')->user()->can('user-membership-history.index') || auth('admin')->user()->can('chats.index'))
            <li
                class="menu-item {{ request()->is('*contact*') || request()->is('*search*') || request()->is('*notifications*') || request()->is('*user-membership-history*') || request()->is('*chats*') ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ti tabler-activity"></i>
                    <div>{{ __('admin.operations_inside_site') }}</div>
                </a>
                <ul class="menu-sub">
                    @if(auth('admin')->user()->can('contacts.index'))
                        <li class="menu-item {{ request()->is('*contacts*') ? 'active open' : '' }}">
                            <a href="{{ url('admin-panel/contacts') }}" class="menu-link">
                                <div>{{ __('admin.complaints_suggestions') }}</div>
                            </a>
                        </li>
                    @endif

                    @if(auth('admin')->user()->can('search-logs.index'))
                        <li class="menu-item {{ request()->is('*search-logs*') ? 'active' : '' }}">
                            <a href="{{ url('admin-panel/search-logs') }}" class="menu-link">
                                <div>{{ __('admin.search_operations') }}</div>
                            </a>
                        </li>
                    @endif

                    @if(auth('admin')->user()->can('notifications.index'))
                        <li class="menu-item {{ request()->is('*notifications*') ? 'active' : '' }}">
                            <a href="{{ url('admin-panel/notifications') }}" class="menu-link">
                                <div>{{ __('admin.notifications') }}</div>
                            </a>
                        </li>
                    @endif

                    @if(auth('admin')->user()->can('user-membership-history.index'))
                        <li class="menu-item {{ request()->is('*user-membership-history*') ? 'active' : '' }}">
                            <a href="{{ route('user-membership-history.index') }}" class="menu-link">
                                <div>{{ __('admin.user_operations') }}</div>
                            </a>
                        </li>
                    @endif

                    @if(auth('admin')->user()->can('chats.index'))
                        <li class="menu-item {{ request()->is('*chats*') ? 'active' : '' }}">
                            <a href="{{ route('chats.index') }}" class="menu-link">
                                <div>{{ __('admin.chats') ?? 'المحادثات' }}</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif


        @if(auth('admin')->user()->can('countries.index') || auth('admin')->user()->can('cities.index'))
            <li class="menu-item {{ request()->is('*countries*') || request()->is('*cities*') ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ti tabler-forms"></i>
                    <div>{{ __('admin.location') }}</div>
                </a>
                <ul class="menu-sub">
                    @if(auth('admin')->user()->can('countries.index'))
                        <li class="menu-item {{ request()->is('*countries*') ? 'active' : '' }}">
                            <a href="{{ url('admin-panel/countries') }}" class="menu-link">
                                <div>{{ __('admin.countries') }}</div>
                            </a>
                        </li>
                    @endif

                    @if(auth('admin')->user()->can('cities.index'))
                        <li class="menu-item {{ request()->is('*cities*') ? 'active' : '' }}">
                            <a href="{{ url('admin-panel/cities') }}" class="menu-link">
                                <div>{{ __('admin.cities') }}</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if(auth('admin')->user()->can('categories.index'))
            <li class="menu-item {{ request()->is('*categories*') ? 'active open' : '' }}">
                <a href="{{ route('categories.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-category-2"></i>
                    <div>{{ __('admin.categories') }}</div>
                </a>
            </li>
        @endif

        @if(auth('admin')->user()->can('memberships.index'))
            <!-- Companies and Institutions -->
            <li class="menu-item {{ request()->is('*memberships*') && request()->get('type') == 'company' ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ti tabler-building"></i>
                    <div>{{ __('admin.companies_and_institutions') }}</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('*memberships*') && request()->get('type') == 'company' && !request()->has('status') ? 'active' : '' }}">
                        <a href="{{ route('memberships.index', ['type' => 'company']) }}" class="menu-link">
                            <div>{{ __('admin.all') }}</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('*memberships*') && request()->get('type') == 'company' && request()->get('status') == 'pending' ? 'active' : '' }}">
                        <a href="{{ route('memberships.index', ['type' => 'company', 'status' => 'pending']) }}" class="menu-link">
                            <div>{{ __('admin.under_review') }}</div>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Individual Providers -->
            <li class="menu-item {{ request()->is('*memberships*') && request()->get('type') == 'individual' ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ti tabler-user"></i>
                    <div>{{ __('admin.individual_providers') }}</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('*memberships*') && request()->get('type') == 'individual' && !request()->has('status') ? 'active' : '' }}">
                        <a href="{{ route('memberships.index', ['type' => 'individual']) }}" class="menu-link">
                            <div>{{ __('admin.all') }}</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('*memberships*') && request()->get('type') == 'individual' && request()->get('status') == 'pending' ? 'active' : '' }}">
                        <a href="{{ route('memberships.index', ['type' => 'individual', 'status' => 'pending']) }}" class="menu-link">
                            <div>{{ __('admin.under_review') }}</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        @if(auth('admin')->user()->can('subscription-packages.index'))
            <li class="menu-item {{ request()->is('*subscription-packages*') ? 'active open' : '' }}">
                <a href="{{ route('subscription-packages.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-package"></i>
                    <div>{{ __('admin.subscription_packages') }}</div>
                </a>
            </li>
        @endif

        @if(auth('admin')->user()->can('services.index'))
            <li class="menu-item {{ request()->is('*services*') ? 'active open' : '' }}">
                <a href="{{ route('services.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-tools"></i>
                    <div>{{ __('admin.services') }}</div>
                </a>
            </li>
        @endif

        @if(auth('admin')->user()->can('success-partners.index'))
            <li class="menu-item {{ request()->is('*success-partners*') ? 'active open' : '' }}">
                <a href="{{ route('success-partners.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-users-group"></i>
                    <div>{{ __('admin.success_partners') }}</div>
                </a>
            </li>
        @endif

        @if(auth('admin')->user()->can('service-requests.index'))
            <li class="menu-item {{ request()->is('*service-requests*') ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ti tabler-file-text"></i>
                    <div>{{ __('admin.service_requests') }}</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('*service-requests*') && request()->get('is_consultation') == '0' ? 'active' : '' }}">
                        <a href="{{ route('service-requests.index', ['is_consultation' => 0]) }}" class="menu-link">
                            <div>{{ __('admin.service_requests_companies') }}</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('*service-requests*') && request()->get('is_consultation') == '1' ? 'active' : '' }}">
                        <a href="{{ route('service-requests.index', ['is_consultation' => 1]) }}" class="menu-link">
                            <div>{{ __('admin.consultation_requests') }}</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif


        @if(auth('admin')->user()->can('pages.index'))
            <li class="menu-item {{ request()->is('*pages*') ? 'active open' : '' }}">
                <a href="{{ url('admin-panel/pages') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-file"></i>
                    <div>{{ __('admin.pages') }}</div>
                </a>
            </li>
        @endif


        @if(auth('admin')->user()->can('landing-page.index'))
            <li class="menu-item {{ request()->is('*landing-page*') ? 'active open' : '' }}">
                <a href="{{ route('admin.landing-page.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-browser"></i>
                    <div>{{ __('admin.landing_page') }}</div>
                </a>
            </li>
        @endif


        @if(auth('admin')->user()->can('faqs.index'))
            <li class="menu-item {{ request()->is('*faqs*') ? 'active open' : '' }}">
                <a href="{{ route('faqs.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-question-mark"></i>
                    <div>{{ __('admin.faqs') }}</div>
                </a>
            </li>
        @endif


    </ul>
</aside>