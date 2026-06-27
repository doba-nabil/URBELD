<?php

namespace App\DataTables;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CategoryDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('name', function ($q) {
                $childrenCount = $q->children()->count();
                $toggleBtn = '';

                if (!$q->parent_id && $childrenCount > 0) {
                    $toggleBtn = '<button type="button" class="btn btn-sm btn-icon btn-outline-secondary toggle-children ms-2"
                        data-category-id="' . $q->id . '"
                        data-expanded="false"
                        title="' . __('admin.show_hide_subcategories') . '">
                        <i class="ti tabler-chevron-down"></i>
                    </button>';
                }

                $indent = $q->parent_id ? '<span class="ms-3 text-muted">└─</span>' : '';
                $badge = $q->parent_id
                    ? '<span class="badge bg-label-secondary ms-2">' . __('admin.sub_badge') . '</span>'
                    : '<span class="badge bg-label-primary ms-2">' . __('admin.main_badge') . '</span>';

                return '<div class="d-flex align-items-center category-row" data-parent-id="' . ($q->parent_id ?? 0) . '" data-category-id="' . $q->id . '">' .
                    $indent . '<span>' . e($q->name) . '</span>' . $badge . $toggleBtn .
                    '</div>';
            })
            ->addColumn('parent', function ($q) {
                if ($q->parent_id) {
                    return '<span class="text-primary"><i class="ti tabler-arrow-left me-1"></i>' . e($q->parent->name) . '</span>';
                }
                return '<span class="text-muted">' . __('admin.main_category') . '</span>';
            })
            ->addColumn('children_count', function ($q) {
                $count = $q->children()->count();
                return $count > 0
                    ? '<span class="badge bg-label-info">' . $count . ' ' . __('admin.sub_badge') . '</span>'
                    : '<span class="text-muted">-</span>';
            })
            ->addColumn('icon', function ($q) {
                return $q->icon
                    ? '<i class="' . e($q->icon) . '"></i>'
                    : '-';
            })
            ->addColumn('image', function ($q) {
                $url = $q->getFirstMediaUrl('categories');
                return $url
                    ? '<img src="' . e($url) . '" alt="" width="40" class="rounded-circle" />'
                    : '-';
            })
            ->addColumn('action', function ($q) {
                $editUrl = url('/admin-panel/categories/' . $q->id . '/edit');
                $deleteUrl = url('/admin-panel/categories/' . $q->id);

                return '
        <div class="dropdown">
            <button class="btn btn-sm btn-default" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="icon-base ti tabler-dots-vertical"></i>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a href="' . $editUrl . '" class="dropdown-item">
                        <i class="icon-base ti tabler-edit"></i> ' . __("admin.edit") . '
                    </a>
                </li>
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
            ->rawColumns(['action', 'name', 'parent', 'icon', 'image', 'children_count'])
            ->setRowClass(function ($q) {
                return $q->parent_id ? 'child-row d-none' : 'parent-row';
            })
            ->setRowId('id')
            ->filterColumn('name', function ($query, $keyword) {
                $query->where('name->ar', 'like', "%{$keyword}%")
                    ->orWhere('name->en', 'like', "%{$keyword}%");
            });
    }

    public function query(Category $model): QueryBuilder
    {
        // Show only main categories initially
        return $model->newQuery()
            ->whereNull('parent_id')
            ->with(['parent', 'children']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
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
            Column::make('name')->title(__('admin.name'))->addClass('text-start'),
            Column::make('parent')->title(__('admin.parent_category'))->addClass('text-start'),
            Column::make('children_count')->title(__('admin.subcategories'))->addClass('text-center'),
            Column::make('icon')->title(__('admin.icon'))->addClass('text-start'),
            Column::make('image')->title(__('admin.image'))->addClass('text-start'),
            Column::computed('action')->title(__('admin.action'))
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-start'),
        ];
    }

    protected function filename(): string
    {
        return 'Categories ' . date('Y-m-d');
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

