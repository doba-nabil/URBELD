@php
    auth()->shouldUse('admin');
@endphp
<!doctype html>

<html
    lang="{{app()->getLocale()}}"
    class="layout-navbar-fixed layout-menu-fixed layout-compact"
    dir="{{app()->getLocale() == 'ar' ? 'rtl' : 'ltr'}}"
    data-skin="default"
    data-bs-theme="{{ auth()->guard('admin')->user()->theme_mode }}"
    data-assets-path="{{ asset('dashboard') }}/assets/"
    data-template="vertical-menu-template">
<head>
    @include('dashboard.layout.head')
</head>

<body>
<input type="hidden" value="{{URL::to('/')}}" id="base_url">

<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('dashboard.layout.sidebar')

        <div class="menu-mobile-toggler d-xl-none rounded-1">
            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
                <i class="ti tabler-menu icon-base"></i>
                <i class="ti tabler-chevron-right icon-base"></i>
            </a>
        </div>
        <!-- / Menu -->
        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->
            @include('dashboard.layout.navbar')
            <!-- / Navbar -->

            @if(session('success'))
                <div class="container-xxl mt-3">
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="ti tabler-circle-check me-2 fs-4"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="container-xxl mt-3">
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="ti tabler-circle-x me-2 fs-4"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="container-xxl mt-3">
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->
                @section('dashboard-main')

                @show
                <!-- / Content -->
                <!-- Footer -->
                <footer class="content-footer footer bg-footer-theme">
                    <div class="container-xxl">
                        <div
                            class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                            <div class="text-body">
                                &#169;
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>
                                , {{ __('admin.made_with') }} ❤️
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
</div>
<!-- / Layout wrapper -->

<!-- Core JS -->
<!-- build:js assets/vendor/js/theme.js  -->
@include('dashboard.layout.footer')
</body>
</html>
