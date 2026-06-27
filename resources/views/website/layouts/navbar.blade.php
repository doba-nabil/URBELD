<div class="navbar-collapse-overlay" id="navbarOverlay"></div>
<div class="container nav-bar bg-transparent">
    <nav class="navbar navbar-expand-lg navbar-light py-0">
        <a href="{{ route('home') }}" class="navbar-brand d-flex align-items-center text-center">
            <div class="logo">
                @php
                    $logoUrl = app()->getLocale() == 'ar' 
                                ? \App\Models\Setting::getMediaUrl('logo_ar') 
                                : \App\Models\Setting::getMediaUrl('logo_en');
                    $logoUrl = $logoUrl ?: asset('website/assets/img/logo.png'); // Fallback
                @endphp
                <img src="{{ $logoUrl }}" height="50" alt="{{ \App\Models\Setting::getValue('site_name', app()->getLocale(), 'Logo') }}">
            </div>
        </a>
        <button type="button" class="navbar-toggler" id="navbarToggler">
            <span class="navbar-toggler-icon">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav">
                <a href="{{ route('home') }}" class="nav-item nav-link {{ request()->routeIs('home') ? 'active' : '' }}">{{ __('website.nav_home') }}</a>
                <a href="{{ route('about') }}" class="nav-item nav-link {{ request()->routeIs('about') ? 'active' : '' }}">{{ __('website.nav_about') }}</a>
                <a href="{{ route('website.categories.index') }}" class="nav-item nav-link {{ request()->routeIs('website.categories.*') || request()->routeIs('website.category.*') ? 'active' : '' }}">{{ __('website.nav_services') }}</a>
                @if(Auth::check() && Auth::user()->isServiceProvider() && Auth::user()->provider_type === 'company')
                    <a href="{{ route('website.subscription-packages.index') }}" class="nav-item nav-link {{ request()->routeIs('website.subscription-packages.*') ? 'active' : '' }}">{{ __('website.nav_subscription_packages') }}</a>
                @endif
                <a href="{{ route('contact') }}" class="nav-item nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">{{ __('website.nav_contact') }}</a>
            </div>
            <div class="auth-buttons d-flex align-items-center">
                <!-- Language Switcher -->
                @php
                    $otherLocale = app()->getLocale() === 'ar' ? 'en' : 'ar';
                    $otherLocaleNative = LaravelLocalization::getSupportedLocales()[$otherLocale]['native'] ?? ($otherLocale === 'ar' ? 'العربية' : 'English');
                    $otherLocaleUrl = route('website.lang', $otherLocale);
                @endphp
                <div class="me-3 dropdown-lang">
                    <a class="nav-link d-flex align-items-center text-white px-3 py-2 rounded-pill shadow-sm" href="{{ $otherLocaleUrl }}" style="background-color: var(--primary, #00B98E); font-weight: bold; border: 1px solid white;">
                        <i class="bi bi-globe me-2"></i>
                        {{ $otherLocaleNative }}
                    </a>
                </div>

                @auth
                    @php
                        $notifications = Auth::user()->notifications()->where('is_read', false)->latest()->limit(5)->get();
                        $unreadCount = Auth::user()->unreadNotificationsCount();
                    @endphp
                    <!-- Notifications Dropdown -->
                    <div class="dropdown me-3" id="notificationsDropdown">
                        <a class="nav-link position-relative px-1" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="outline: none; box-shadow: none;">
                            <i class="bi bi-bell-fill fs-4"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $unreadCount > 0 ? '' : 'd-none' }}" id="notificationBadge">
                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-0 overflow-hidden" style="width: 320px; border-radius: 15px; overflow-x: hidden !important;">
                            <li class="bg-primary text-white p-3 d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold">{{ __('website.nav_notifications') }}</h6>
                                <button class="btn btn-sm btn-light py-0 px-2 fw-bold" id="markAllReadBtn" style="font-size: 0.75rem;">{{ __('website.nav_mark_all_read') }}</button>
                            </li>
                            <div id="notificationList" style="max-height: 400px; overflow-y: auto;">
                                @forelse($notifications as $notification)
                                    @php
                                        $title = $notification->title ?? ($notification->data['title'] ?? __('website.nav_new_notification'));
                                        $body = $notification->message ?? ($notification->data['body'] ?? '');
                                        $url = $notification->link ?? ($notification->data['url'] ?? '#');
                                        $isRead = $notification->is_read || $notification->read_at !== null;
                                    @endphp
                                    <li class="notification-item {{ $isRead ? '' : 'bg-light' }} border-bottom">
                                        <a href="{{ $url }}" class="dropdown-item p-3 mark-as-read" data-id="{{ $notification->id }}" style="white-space: normal;">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 fw-bold" style="font-size: 0.9rem;">{{ $title }}</h6>
                                                    <p class="mb-1 text-muted small">{{ $body }}</p>
                                                    <small class="text-primary">{{ $notification->created_at->diffForHumans() }}</small>
                                                </div>
                                                @if(!$isRead)
                                                    <span class="ms-2 badge bg-primary border-0" style="width: 8px; height: 8px; border-radius: 50%; padding: 0;">&nbsp;</span>
                                                @endif
                                            </div>
                                        </a>
                                    </li>
                                @empty
                                    <div class="text-center p-4 text-muted small">{{ __('website.nav_no_notifications') }}</div>
                                @endforelse
                            </div>
                            <li class="border-top">
                                <a class="dropdown-item text-center py-2 text-primary fw-bold" href="{{ route('notifications.index') }}">{{ __('website.nav_all_notifications') }}</a>
                            </li>
                        </ul>
                    </div>

                    @push('js')
                    <script>
                    $(document).ready(function() {
                        const $badge = $('#notificationBadge');
                        const $list = $('#notificationList');
                        const csrfToken = $('meta[name="csrf-token"]').attr('content');

                        function refreshNotifications() {
                            $.get('/notifications/latest', function(data) {
                                // Update badge
                                if (data.unread_count > 0) {
                                    $badge.text(data.unread_count > 99 ? '99+' : data.unread_count).removeClass('d-none');
                                } else {
                                    $badge.addClass('d-none');
                                }

                                // Update list
                                if (data.notifications.length === 0) {
                                    $list.html('<div class="text-center p-4 text-muted small">{{ __('website.nav_no_notifications') }}</div>');
                                    return;
                                }

                                let html = '';
                                data.notifications.forEach(function(n) {
                                    html += `
                                        <li class="notification-item ${n.is_read ? '' : 'bg-light'} border-bottom">
                                            <a href="${n.link}" class="dropdown-item p-3 mark-as-read" data-id="${n.id}" style="white-space: normal;">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold" style="font-size: 0.9rem;">${n.title}</h6>
                                                        <p class="mb-1 text-muted small">${n.body}</p>
                                                        <small class="text-primary">${n.time}</small>
                                                    </div>
                                                    ${n.is_read ? '' : '<span class="ms-2 badge bg-primary border-0" style="width: 8px; height: 8px; border-radius: 50%; padding: 0;">&nbsp;</span>'}
                                                </div>
                                            </a>
                                        </li>
                                    `;
                                });
                                $list.html(html);
                            });
                        }

                        $(document).on('click', '.mark-as-read', function() {
                            const id = $(this).data('id');
                            const $item = $(this).closest('.notification-item');
                            
                            // Immediately decrement badge count
                            let currentCount = parseInt($badge.text().replace('+', '')) || 0;
                            if (currentCount > 0) {
                                currentCount--;
                                if (currentCount === 0) {
                                    $badge.addClass('d-none');
                                } else {
                                    $badge.text(currentCount > 99 ? '99+' : currentCount);
                                }
                            }

                            $item.fadeOut(300, function() {
                                $(this).remove();
                                if ($('#notificationList').find('.notification-item').length === 0) {
                                    $('#notificationList').html('<div class="text-center p-4 text-muted small">{{ __('website.nav_no_notifications') }}</div>');
                                }
                            });
                            $.post(`/notifications/${id}/mark-as-read`, { _token: csrfToken });
                        });

                        $('#markAllReadBtn').on('click', function(e) {
                            e.stopPropagation();
                            $.post('/notifications/mark-all-as-read', { _token: csrfToken }, function() {
                                refreshNotifications();
                            });
                        });

                        // Poll every 30 seconds
                        setInterval(refreshNotifications, 30000);
                    });
                    </script>
                    @endpush

                    <a href="{{ route('profile.edit') }}" class="btn d-lg-flex text-white">{{ __('website.nav_profile') }}</a>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form" class="d-none">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn d-lg-flex btn-danger ms-2">{{ __('website.nav_logout') }}</a>
                @else
                    <a href="{{ route('login') }}" class="btn d-lg-flex text-white">{{ __('website.nav_login') }}</a>
                    <a href="{{ route('register') }}" class="btn d-lg-flex">{{ __('website.nav_register') }}</a>
                @endauth
            </div>
        </div>
    </nav>
</div>
