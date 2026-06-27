<!-- jQuery -->
<script src="{{ asset('dashboard') }}/assets/vendor/libs/jquery/jquery.js"></script>

<script src="{{ asset('dashboard') }}/assets/vendor/libs/popper/popper.js"></script>
<script src="{{ asset('dashboard') }}/assets/vendor/js/bootstrap.js"></script>
<script src="{{ asset('dashboard') }}/assets/vendor/libs/node-waves/node-waves.js"></script>

<script src="{{ asset('dashboard') }}/assets/vendor/libs/@algolia/autocomplete-js.js"></script>

<script src="{{ asset('dashboard') }}/assets/vendor/libs/pickr/pickr.js"></script>

<script src="{{ asset('dashboard') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

<script src="{{ asset('dashboard') }}/assets/vendor/libs/hammer/hammer.js"></script>

<script src="{{ asset('dashboard') }}/assets/vendor/libs/i18n/i18n.js"></script>

<script src="{{ asset('dashboard') }}/assets/vendor/js/menu.js"></script>

<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{ asset('dashboard') }}/assets/vendor/libs/apex-charts/apexcharts.js"></script>
<script src="{{ asset('dashboard') }}/assets/vendor/libs/chartjs/chartjs.js"></script>
<script src="{{ asset('dashboard') }}/assets/vendor/libs/swiper/swiper.js"></script>

<!-- Main JS -->

<script src="{{ asset('dashboard') }}/assets/js/main.js"></script>

<!-- Page JS -->
<script src="{{ asset('dashboard') }}/assets/js/dashboards-analytics.js"></script>
<script>
    document.querySelectorAll('[data-bs-theme-value]').forEach(button => {
        button.addEventListener('click', function () {
            let newTheme = this.getAttribute('data-bs-theme-value');
            fetch("{{ route('theme.change') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ theme: newTheme })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.documentElement.setAttribute('data-bs-theme', data.theme);
                        document.querySelectorAll('[data-bs-theme-value]').forEach(btn => {
                            btn.classList.remove('active');
                        });
                        this.classList.add('active');
                        let icon = document.querySelector('#nav-theme .theme-icon-active');
                        if (data.theme === 'dark') {
                            icon.classList.remove('tabler-sun');
                            icon.classList.add('tabler-moon-stars');
                        } else {
                            icon.classList.remove('tabler-moon-stars');
                            icon.classList.add('tabler-sun');
                        }
                    }
                });
        });
    });
</script>
@section('dashboard-footer')

@show
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '',
            text: "{{ session('success') }}",
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: '{{ __("admin.Error") }}',
            text: "{{ session('error') }}",
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false
        });
        @endif

        @if ($errors->any())
        @php
            $allErrors = implode("\\n", $errors->all());
        @endphp
        Swal.fire({
            icon: 'error',
            title: '',
            html: "{!! $allErrors !!}",
            timer: 4000,
            timerProgressBar: true,
            showConfirmButton: true
        });
        @endif
    });
</script>
