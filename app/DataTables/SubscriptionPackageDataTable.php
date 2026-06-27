<?php

namespace App\DataTables;

use App\Models\SubscriptionPackage;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SubscriptionPackageDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('name', function ($q) {
                $name = $q->name;
                if (is_array($name)) {
                    $locale = app()->getLocale();
                    $name = $name[$locale] ?? $name['ar'] ?? $name['en'] ?? '-';
                }
                return '<span>' . e($name) . '</span>';
            })
            ->addColumn('badge_name', function ($q) {
                return '<span>' . e($q->getTranslation('badge_name', 'ar')) . ' / ' . e($q->getTranslation('badge_name', 'en')) . '</span>';
            })
            ->addColumn('price', function ($q) {
                return '<span>' . number_format($q->price, 2) . ' ' . __('admin.currency') . '</span>';
            })
            ->addColumn('duration_days', function ($q) {
                return '<span>' . $q->duration_days . ' ' . __('admin.days') . '</span>';
            })
            ->addColumn('sort_order', function ($q) {
                return '<span>' . $q->sort_order . '</span>';
            })
            ->addColumn('is_active', function ($q) {
                $badgeClass = $q->is_active ? 'bg-label-success' : 'bg-label-danger';
                $statusText = $q->is_active ? __('admin.active') : __('admin.inactive');
                return '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>';
            })
            ->addColumn('action', function ($q) {
                $editUrl = route('subscription-packages.edit', $q->id);
                $deleteUrl = route('subscription-packages.destroy', $q->id);
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
            ->rawColumns(['action', 'name', 'price', 'duration_days', 'is_active', 'badge_name', 'sort_order'])
            ->setRowId('id');
    }

    public function query(SubscriptionPackage $model): QueryBuilder
    {
        return $model->newQuery()->latest();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('table')
            ->columns($this->getColumns())
            ->minifiedAjax()
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
            Column::make('badge_name')->title(__('admin.badge_name'))->addClass('text-center'),
            Column::make('price')->title(__('admin.price'))->addClass('text-center'),
            Column::make('duration_days')->title(__('admin.duration'))->addClass('text-center'),
            Column::make('sort_order')->title(__('admin.sort_order_package'))->addClass('text-center'),
            Column::make('works_limit')->title(__('admin.works_limit'))->addClass('text-center'),
            Column::make('max_services')->title(__('admin.max_services'))->addClass('text-center'),
            Column::make('is_active')->title(__('admin.status'))->addClass('text-center'),
            Column::computed('action')->title(__('admin.actions'))
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function getDataTableLanguage(): array
    {
        $locale = app()->getLocale();
        return [
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
        ][$locale] ?? [];
    }
}
