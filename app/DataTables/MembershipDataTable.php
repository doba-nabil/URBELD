<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MembershipDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('name', function ($q) {
                return '<span>' . e($q->name) . '</span>';
            })
            ->addColumn('type', function ($q) {
                // Use provider_type if exists, fallback to membership_type
                $type = $q->provider_type ?: $q->membership_type;
                if ($type === 'individual') {
                    $badgeClass = 'bg-label-primary';
                    $typeText = __('admin.individual');
                } elseif ($type === 'supplier') {
                    $badgeClass = 'bg-label-success';
                    $typeText = __('admin.supplier');
                } else {
                    $badgeClass = 'bg-label-info';
                    $typeText = __('admin.company');
                }
                return '<span class="badge ' . $badgeClass . '">' . $typeText . '</span>';
            })
            ->addColumn('main_category', function ($q) {
                $category = $q->categories->first();
                if ($category) {
                    $categoryName = $category->name;
                    if (is_array($categoryName)) {
                        $locale = app()->getLocale();
                        $categoryName = $categoryName[$locale] ?? $categoryName['ar'] ?? $categoryName['en'] ?? '-';
                    }
                    return '<span>' . e($categoryName) . '</span>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('sub_categories_count', function ($q) {
                $count = $q->categories->count();
                return '<span class="badge bg-label-secondary">' . $count . '</span>';
            })
            ->addColumn('certificates_count', function ($q) {
                $count = $q->getMedia('certificates')->count();
                return '<span class="badge bg-label-warning">' . $count . '</span>';
            })
            ->addColumn('available_requests', function ($q) {
                $categoryIds = $q->categories->pluck('id')->toArray();

                if (empty($categoryIds)) {
                    return '<span class="badge bg-label-secondary">0</span>';
                }

                $count = \App\Models\ServiceRequest::whereIn('category_id', $categoryIds)
                    ->whereIn('status', ['new', 'pending_response'])
                    ->where(function($query) {
                        $query->whereNull('response_deadline')
                            ->orWhere('response_deadline', '>=', now());
                    })
                    ->count();

                if ($count > 0) {
                    return '<span class="badge bg-label-info">' . $count . '</span>';
                }
                return '<span class="badge bg-label-secondary">' . $count . '</span>';
            })
            ->addColumn('country', function ($q) {
                if ($q->city && $q->city->country) {
                    $countryName = $q->city->country->name;
                    if (is_array($countryName)) {
                        $locale = app()->getLocale();
                        $countryName = $countryName[$locale] ?? $countryName['ar'] ?? $countryName['en'] ?? '-';
                    }
                    return '<span>' . e($countryName) . '</span>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('city', function ($q) {
                if ($q->city) {
                    $cityName = $q->city->name;
                    if (is_array($cityName)) {
                        $locale = app()->getLocale();
                        $cityName = $cityName[$locale] ?? $cityName['ar'] ?? $cityName['en'] ?? '-';
                    }
                    return '<span>' . e($cityName) . '</span>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('membership_plan', function ($q) {
                if ($q->membership) {
                    $name = $q->membership->name;
                    if (is_array($name)) {
                        $locale = app()->getLocale();
                        $name = $name[$locale] ?? $name['ar'] ?? $name['en'] ?? '-';
                    }
                    return '<span class="badge bg-label-dark">' . e($name) . '</span>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('is_active', function ($q) {
                if ($q->active === 'active') {
                    return '<span class="badge bg-label-success">' . __('admin.active') . '</span>';
                } elseif ($q->active === 'blocked' || $q->active === '0' || $q->active === 0) {
                    return '<span class="badge bg-label-danger">' . __('admin.blocked') . '</span>';
                } elseif ($q->active === 'pending') {
                    return '<span class="badge bg-label-warning">' . __('admin.pending') . '</span>';
                }
                return '<span class="badge bg-label-secondary">' . __('admin.unactive') . '</span>';
            })
            ->addColumn('is_featured', function ($q) {
                $isFeatured = $q->membership ? $q->membership->is_featured : false;
                $checked = $isFeatured ? 'checked' : '';
                $toggleUrl = route('admin.memberships.toggle-featured', $q->membership_id ?? 0);
                
                // Return a checkbox toggle
                return '<div class="form-check form-switch d-flex justify-content-center">
                            <input class="form-check-input toggle-featured" type="checkbox" data-url="' . $toggleUrl . '" ' . $checked . ' ' . (!$q->membership_id ? 'disabled' : '') . '>
                        </div>';
            })
    ->addColumn('action', function ($q) {
                $showUrl = url('/admin-panel/memberships/' . $q->id);
                $editUrl = url('/admin-panel/memberships/' . $q->id . '/edit');
                $deleteUrl = url('/admin-panel/users/' . $q->id);
                $type = $q->provider_type ?: $q->membership_type;

                $extraLinks = '';
                if ($type === 'supplier') {
                    $productsUrl = route('supplier-products.index', ['supplier_id' => $q->id]);
                    $offersUrl = route('supplier-offers.index', ['supplier_id' => $q->id]);
                    $extraLinks = '
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a href="' . $productsUrl . '" class="dropdown-item text-primary">
                        <i class="icon-base ti tabler-box"></i> المنتجات
                    </a>
                </li>
                <li>
                    <a href="' . $offersUrl . '" class="dropdown-item text-success">
                        <i class="icon-base ti tabler-discount"></i> العروض
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>';
                }

                return '
        <div class="dropdown">
            <button class="btn btn-sm btn-default" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="icon-base ti tabler-dots-vertical"></i>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a href="' . $showUrl . '" class="dropdown-item">
                        <i class="icon-base ti tabler-eye"></i> ' . __('admin.show_review') . '
                    </a>
                </li>
                <li>
                    <a href="' . $editUrl . '" class="dropdown-item">
                        <i class="icon-base ti tabler-edit"></i> ' . __('admin.edit') . '
                    </a>
                </li>' . $extraLinks . '
                <li>
                    <a href="javascript:void(0)" class="dropdown-item delete-btn"
                        data-id="' . $q->id . '"
                        data-url="' . $deleteUrl . '"
                        data-table=".table"
                        title="' . __("admin.delete") . '">
                        <i class="icon-base ti tabler-trash"></i> ' . __("admin.delete") . '
                    </a>
                </li>
            </ul>
        </div>';
            })
            ->rawColumns(['action', 'name', 'type', 'membership_plan', 'main_category', 'sub_categories_count', 'certificates_count', 'available_requests', 'country', 'city', 'is_active', 'is_featured'])
            ->setRowId('id');
    }

    public function query(User $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->where('user_type', 'service_provider')
            ->with(['categories', 'city.country', 'membership']);

        // Filter by provider_type if requested
        if (request()->has('type') && in_array(request()->get('type'), ['individual', 'company', 'supplier'])) {
            $query->where('provider_type', request()->get('type'));
        }

        // Filter by status (pending review)
        if (request()->has('status') && request()->get('status') == 'pending') {
            $query->where('active', 'pending');
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('table')
            ->columns($this->getColumns())
            ->minifiedAjax(url()->current() . '?' . http_build_query(request()->except(['_token', '_method'])))
            ->orderBy(0, 'desc')
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ])->parameters([
                'language' => $this->getDataTableLanguage()
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('#')->addClass('text-center'),
            Column::make('name')->title(__('admin.name'))->addClass('text-start'),
            Column::make('type')->title(__('admin.membership_type'))->addClass('text-center'),
            Column::make('membership_plan')->title(__('admin.membership'))->addClass('text-center'),
            Column::make('main_category')->title(__('admin.main_category'))->addClass('text-start'),
            Column::make('sub_categories_count')->title(__('admin.sub_categories'))->addClass('text-center'),
            Column::make('certificates_count')->title(__('admin.certificates'))->addClass('text-center'),
            Column::make('available_requests')->title(__('admin.available_requests'))->addClass('text-center'),
            Column::make('country')->title(__('admin.country'))->addClass('text-start'),
            Column::make('city')->title(__('admin.city'))->addClass('text-start'),
            Column::make('is_active')->title(__('admin.status'))->addClass('text-center'),
            Column::make('is_featured')->title('مميز')->addClass('text-center')->searchable(false)->orderable(false),
            Column::computed('action')->title(__('admin.actions'))
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Memberships ' . date('Y-m-d');
    }

    protected function getDataTableLanguage(): array
    {
        $locale = app()->getLocale();

        $languages = [
            'en' => [
                'processing' => 'Processing...',
                'search' => 'Search:',
                'lengthMenu' => 'Show _MENU_ entries',
                'info' => 'Showing _START_ to _END_ of _TOTAL_ entries',
                'infoEmpty' => 'Showing 0 to 0 of 0 entries',
                'infoFiltered' => '(filtered from _MAX_ total entries)',
                'loadingRecords' => 'Loading...',
                'zeroRecords' => 'No matching records found',
                'emptyTable' => 'No data available in table',
                'paginate' => [
                    'first' => 'First',
                    'previous' => 'Previous',
                    'next' => 'Next',
                    'last' => 'Last',
                ],
            ],
            'ar' => [
                'processing' => 'جارٍ المعالجة...',
                'search' => 'بحث:',
                'lengthMenu' => 'عرض _MENU_ سجلات',
                'info' => 'عرض _START_ إلى _END_ من أصل _TOTAL_ سجلات',
                'infoEmpty' => 'عرض 0 إلى 0 من أصل 0 سجلات',
                'infoFiltered' => '(تمت التصفية من أصل _MAX_ سجلات)',
                'loadingRecords' => 'جارٍ التحميل...',
                'zeroRecords' => 'لم يتم العثور على سجلات مطابقة',
                'emptyTable' => 'لا توجد بيانات متاحة في الجدول',
                'paginate' => [
                    'first' => 'الأول',
                    'previous' => 'السابق',
                    'next' => 'التالي',
                    'last' => 'الأخير',
                ],
            ],
        ];

        return $languages[$locale] ?? $languages['en'];
    }
}
