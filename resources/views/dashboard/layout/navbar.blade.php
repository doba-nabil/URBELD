<nav
    class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="icon-base ti tabler-menu-2 icon-md"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
        {{--        <!-- Search -->--}}
        {{--        <div class="navbar-nav align-items-center">--}}
        {{--            <div class="nav-item navbar-search-wrapper px-md-0 px-2 mb-0">--}}
        {{--                <a class="nav-item nav-link search-toggler d-flex align-items-center px-0" href="javascript:void(0);">--}}
        {{--                    <span class="d-inline-block text-body-secondary fw-normal" id="autocomplete"></span>--}}
        {{--                </a>--}}
        {{--            </div>--}}
        {{--        </div>--}}

        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-md-auto">
            @php
                $otherLocale = app()->getLocale() === 'ar' ? 'en' : 'ar';
                $otherLocaleNative = LaravelLocalization::getSupportedLocales()[$otherLocale]['native'] ?? ($otherLocale === 'ar' ? 'العربية' : 'English');
                $otherLocaleUrl = route('admin.lang', $otherLocale);
            @endphp
            <li class="nav-item me-2 me-xl-0">
                <a class="nav-link text-white px-3 py-2 rounded shadow-sm d-flex align-items-center" href="{{ $otherLocaleUrl }}" style="background-color: var(--bs-primary, #7367f0); font-weight: bold;">
                    <i class="icon-base ti tabler-language icon-22px me-2 text-white"></i>
                    {{ $otherLocaleNative }}
                </a>
            </li>
            <!--/ Language -->

            <!-- Style Switcher -->
            <li class="nav-item dropdown">
                <a
                    class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill"
                    id="nav-theme"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown">
                    <i class="icon-base ti tabler-sun icon-22px theme-icon-active text-heading"></i>
                    <span class="d-none ms-2" id="nav-theme-text">Toggle theme</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-theme-text">
                    <li>
                        <button
                            type="button"
                            class="dropdown-item align-items-center active"
                            data-bs-theme-value="light"
                            aria-pressed="false">
                            <span><i class="icon-base ti tabler-sun icon-22px me-3" data-icon="sun"></i>{{ __('admin.light') }}</span>
                        </button>
                    </li>
                    <li>
                        <button
                            type="button"
                            class="dropdown-item align-items-center"
                            data-bs-theme-value="dark"
                            aria-pressed="true">
                        <span
                        ><i class="icon-base ti tabler-moon-stars icon-22px me-3" data-icon="moon-stars"></i
                            >{{ __('admin.dark') }}</span
                        >
                        </button>
                    </li>
                </ul>
            </li>
            <!-- / Style Switcher-->
            <!-- Quick links  -->
            <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown">
                <a
                    class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown"
                    data-bs-auto-close="outside"
                    aria-expanded="false">
                    <i class="icon-base ti tabler-layout-grid-add icon-22px text-heading"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end p-0">
                    <div class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h6 class="mb-0 me-auto">{{__('admin.shortcuts')}}</h6>
                        </div>
                    </div>
                    <div class="dropdown-shortcuts-list scrollable-container">

                        <div class="row row-bordered overflow-visible g-0">
                            <div class="dropdown-shortcuts-item col">
                          <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                            <i class="icon-base ti tabler-user icon-26px text-heading"></i>
                          </span>
                                <a href="{{ url('admin-panel/users') }}"
                                   class="stretched-link">{{__('admin.users')}}</a>
                                <small>{{__('admin.manage_users')}}</small>
                            </div>
                            <div class="dropdown-shortcuts-item col">
                          <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                            <i class="icon-base ti tabler-users icon-26px text-heading"></i>
                          </span>
                                <a href="#" class="stretched-link">{{__('admin.manage_role')}}</a>
                                <small>{{__('admin.permissions')}}</small>
                            </div>
                        </div>
                        <div class="row row-bordered overflow-visible g-0">
                            <div class="dropdown-shortcuts-item col">
                          <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                            <i class="icon-base ti tabler-device-desktop-analytics icon-26px text-heading"></i>
                          </span>
                                <a href="{{ url('admin-panel') }}" class="stretched-link">{{__('admin.admin-panel')}}</a>
                                <small> {{__('admin.admin-panel')}}</small>
                            </div>
                            <div class="dropdown-shortcuts-item col">
                          <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                            <i class="icon-base ti tabler-settings icon-26px text-heading"></i>
                          </span>
                                <a href="{{ url('admin-panel/settings') }}"
                                   class="stretched-link">{{__('admin.settings')}}</a>
                                <small>{{__('admin.setting')}}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <!-- Quick links -->

            <!-- Notification -->
            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
                <a
                    class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill"
                    href="{{ route('admin.notifications.index') }}"
                    aria-expanded="false">
                    <span class="position-relative">
                      <i class="icon-base ti tabler-bell icon-22px text-heading"></i>
                      @php
                          $unreadCount = \App\Models\Notification::whereNull('user_id')->where('is_read', false)->count();
                      @endphp
                      @if($unreadCount > 0)
                          <span class="badge rounded-pill bg-danger badge-dot badge-notifications border">{{ $unreadCount }}</span>
                      @endif
                    </span>
                </a>
            </li>
            <!--/ Notification -->

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a
                    class="nav-link dropdown-toggle hide-arrow p-0"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        @if(auth()->guard('admin')->user() && auth()->guard('admin')->user()->getFirstMediaUrl('users'))
                            <img src="{{ auth()->guard('admin')->user()->getFirstMediaUrl('users') }}" alt class="rounded-circle"/>
                        @else
                            <img src="{{ asset('dashboard') }}/assets/img/avatars/avatar.png" alt class="rounded-circle"/>
                        @endif
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item mt-0" href="#">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-2">
                                    <div class="avatar avatar-online">
                                        @if(auth()->guard('admin')->user() && auth()->guard('admin')->user()->getFirstMediaUrl('users'))
                                            <img src="{{ auth()->guard('admin')->user()->getFirstMediaUrl('users') }}" alt class="rounded-circle"/>
                                        @else
                                            <img src="{{ asset('dashboard') }}/assets/img/avatars/avatar.png" alt class="rounded-circle"/>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ auth()->guard('admin')->user()->name }}</h6>
                                    <small class="text-body-secondary">{{ auth()->guard('admin')->user()->email }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1 mx-n2"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ url('admin-panel/profile') }}">
                            <i class="icon-base ti tabler-user me-3 icon-md"></i
                            ><span class="align-middle">{{ __('admin.my_profile') }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ url('admin-panel/settings') }}">
                            <i class="icon-base ti tabler-settings me-3 icon-md"></i
                            ><span class="align-middle">{{ __('admin.settings') }}</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1 mx-n2"></div>
                    </li>
                    <li>
                        <div class="d-grid px-2 pt-2 pb-1">
                            <a class="btn btn-sm btn-danger d-flex" href="{{ route('admin.logout') }}">
                                <small class="align-middle">{{ __('admin.logout') }}</small>
                                <i class="icon-base ti tabler-logout ms-2 icon-14px"></i>
                            </a>
                        </div>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
