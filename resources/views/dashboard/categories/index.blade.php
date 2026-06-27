@extends('dashboard.layout.master')
@section('title', __('admin.categories'))
@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card">
            <h5 class="card-header d-flex justify-content-between border-b">
                {{__('admin.categories')}}
                <div class="buttons d-flex justify-content-between">
                    @include('dashboard.partials.index.table_btns')
                    <a class="btn btn-primary" href="{{ route('categories.create') }}"><i
                            class="menu-icon icon-base ti tabler-plus"></i> {{ __('admin.add_new') }}</a>
                </div>
            </h5>
            <div class="table-responsive text-nowrap">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection
@section('dashboard-head')
    @include('dashboard.partials.index.css')
    <style>
        .spinner {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .child-row {
            background-color: #f8f9fa;
        }
        .child-row:hover {
            background-color: #e9ecef;
        }
        .toggle-children {
            transition: all 0.3s ease;
        }
    </style>
@endsection

@section('dashboard-footer')
    {{ $dataTable->scripts() }}
    @include('dashboard.partials.index.js')
    <script>
        $(document).ready(function() {
            let table = $('#table').DataTable();

            // Toggle children rows
            $(document).on('click', '.toggle-children', function() {
                const btn = $(this);
                const categoryId = btn.data('category-id');
                const isExpanded = btn.data('expanded');
                const icon = btn.find('i');

                    if (!isExpanded) {
                    // Show sub-categories
                    btn.prop('disabled', true);
                    const originalIcon = icon.attr('class');
                    icon.removeClass().addClass('ti tabler-loader-2 spinner');
                    $.ajax({
                        url: '{{ url("admin-panel/categories") }}/' + categoryId + '/children',
                        method: 'GET',
                        success: function(response) {
                            if (response.status === 'success' && response.children.length > 0) {
                                const parentRow = btn.closest('tr');
                                let rowsHtml = '';

                                response.children.forEach(function(child) {
                                    const imageHtml = child.image
                                        ? '<img src="' + child.image + '" alt="" width="40" class="rounded-circle" />'
                                        : '-';
                                    const iconHtml = child.icon
                                        ? '<i class="' + child.icon + '"></i>'
                                        : '-';
                                    const childrenCountHtml = child.children_count > 0
                                        ? '<span class="badge bg-label-info">' + child.children_count + ' {{ __("admin.sub_category_badge") }}</span>'
                                        : '<span class="text-muted">-</span>';

                                    // Escape HTML for name
                                    const childName = $('<div>').text(child.name).html();

                                    rowsHtml += '<tr class="child-row" data-parent-id="' + categoryId + '" data-category-id="' + child.id + '">' +
                                        '<td class="text-start">' +
                                            '<div class="d-flex align-items-center">' +
                                                '<span class="ms-3 text-muted">└─</span>' +
                                                '<span>' + childName + '</span>' +
                                                '<span class="badge bg-label-secondary ms-2">{{ __("admin.sub_category_badge") }}</span>' +
                                            '</div>' +
                                        '</td>' +
                                        '<td class="text-start">' +
                                            '<span class="text-primary">' +
                                                '<i class="ti tabler-arrow-left me-1"></i>' +
                                                '{{ __('admin.main_category') }}' +
                                            '</span>' +
                                        '</td>' +
                                        '<td class="text-center">' + childrenCountHtml + '</td>' +
                                        '<td class="text-start">' + iconHtml + '</td>' +
                                        '<td class="text-start">' + imageHtml + '</td>' +
                                        '<td class="text-start">' +
                                            '<div class="dropdown">' +
                                                '<button class="btn btn-sm btn-default" type="button" data-bs-toggle="dropdown">' +
                                                    '<i class="icon-base ti tabler-dots-vertical"></i>' +
                                                '</button>' +
                                                '<ul class="dropdown-menu">' +
                                                    '<li><a href="' + child.edit_url + '" class="dropdown-item">' +
                                                        '<i class="icon-base ti tabler-edit"></i> {{ __("admin.edit") }}' +
                                                    '</a></li>' +
                                                    '<li><a href="javascript:void(0)" class="dropdown-item delete-btn" ' +
                                                        'data-id="' + child.id + '" ' +
                                                        'data-url="' + child.delete_url + '" ' +
                                                        'data-table=".table">' +
                                                        '<i class="icon-base ti tabler-trash"></i> {{ __("admin.delete") }}' +
                                                    '</a></li>' +
                                                '</ul>' +
                                            '</div>' +
                                        '</td>' +
                                    '</tr>';
                                });

                                // Insert rows after parent row
                                parentRow.after(rowsHtml);

                                // Update button state
                                btn.data('expanded', true);
                                icon.removeClass().addClass('ti tabler-chevron-up');
                            } else {
                                Swal.fire({
                                    icon: 'info',
                                    title: '{{ __("admin.no_sub_categories_title") }}',
                                    text: '{{ __("admin.no_sub_categories_text") }}',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                            btn.prop('disabled', false);
                        },
                        error: function() {
                            btn.prop('disabled', false);
                            icon.removeClass().addClass('ti tabler-chevron-down');
                            Swal.fire({
                                icon: 'error',
                                title: '{{ __("admin.error") }}',
                                text: '{{ __("admin.error_fetch_sub_categories") }}'
                            });
                        }
                    });
                } else {
                    // Hide sub-categories
                    $('tr.child-row[data-parent-id="' + categoryId + '"]').remove();
                    btn.data('expanded', false);
                    icon.removeClass('ti tabler-chevron-up').addClass('ti tabler-chevron-down');
                }
            });

            // Update table after deletion
            $(document).on('click', '.delete-btn', function() {
                const deleteUrl = $(this).data('url');
                const categoryId = $(this).data('id');

                Swal.fire({
                    title: '{{ __("admin.sure") }}',
                    text: '{{ __("admin.cant") }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __("admin.yes_sure") }}',
                    cancelButtonText: '{{ __("admin.cancel") }}',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    // Remove row from table
                                    $('tr[data-category-id="' + categoryId + '"]').remove();

                                    Swal.fire({
                                        icon: 'success',
                                        title: '',
                                        text: response.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    });

                                    // Reload table
                                    table.ajax.reload();
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: '{{ __("admin.error") }}',
                                    text: '{{ __("admin.delete_error") }}'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection

