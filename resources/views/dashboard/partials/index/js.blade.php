<script src="https://cdn.datatables.net/2.3.3/js/dataTables.js"></script>
<script src="http://cdn.datatables.net/2.3.3/js/dataTables.bootstrap5.js"></script>
<script src="{{ asset('dashboard') }}/assets/js/tables-datatables-extensions.js"></script>
<script src="{{ asset('dashboard') }}/assets/vendor/libs/sweetalert2/sweetalert2.js"></script>
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
<script>
    $(function () {
        if (typeof window.LaravelDataTables !== 'undefined') {
            Object.keys(window.LaravelDataTables).forEach(function (tableId) {
                let table = window.LaravelDataTables[tableId];
                $('#export-excel').on('click', function () {
                    table.button('.buttons-excel').trigger();
                });
                $('#export-csv').on('click', function () {
                    table.button('.buttons-csv').trigger();
                });
                $('#export-pdf').on('click', function () {
                    table.button('.buttons-pdf').trigger();
                });
                $('#export-print').on('click', function () {
                    table.button('.buttons-print').trigger();
                });
            });
        }
    });
</script>
<script>
    $(document).on('click', '.delete-btn', function() {
        let tableSelector = $(this).data('table');
        let url = $(this).data('url');

        Swal.fire({
            title: '{{ __("admin.sure") }}',
            text: "{{ __("admin.cant") }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ __("admin.yes_sure") }}',
            cancelButtonText: '{{ __("admin.cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire(
                            '{{ __("admin.delete_success") }}',
                            response.message,
                            'success'
                        );
                        $(tableSelector).DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        let message = '{{ __("admin.delete_error") }}';
                        if(xhr.responseJSON && xhr.responseJSON.message){
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire('{{ __("admin.delete_error") }}', message, 'error');
                    }
                });
            }
        });
    });
</script>

