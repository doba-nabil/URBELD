<?php

namespace App\DataTables;

use App\Traits\HasActivityFeed;
use Yajra\DataTables\CollectionDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ActivityFeedDataTable extends DataTable
{
    use HasActivityFeed;

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return (new CollectionDataTable($query))
            ->addColumn('type', function ($row) {
                $class = match ($row['type']) {
                    'user' => 'success',
                    'request' => 'primary',
                    'rating' => 'warning',
                    default => 'secondary'
                };
                return '<span class="badge bg-label-' . $class . '">' . e($row['type_label']) . '</span>';
            })
            ->addColumn('details', function ($row) {
                return match ($row['type']) {
                    'user' => __('admin.new_user_joined_desc'),
                    'request' => __('admin.new_request_published_desc'),
                    'rating' => __('admin.user_rated_experience_desc'),
                    default => '-'
                };
            })
            ->addColumn('time', function ($row) {
                return $row['date']->diffForHumans() . ' (' . $row['date']->format('Y-m-d H:i') . ')';
            })
            ->addColumn('action', function ($row) {
                return '<a href="' . $row['url'] . '" class="btn btn-sm btn-icon btn-label-primary">
                            <i class="ti ti-eye"></i>
                        </a>';
            })
            ->rawColumns(['type', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        return $this->getActivityFeed(100); // Increased limit for DataTable
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('activity-feed-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(3) // Order by Date column
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ])
                    ->parameters([
                        'language' => $this->getDataTableLanguage()
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::computed('type')->title(__('admin.type'))->addClass('text-center'),
            Column::make('user_name')->title(__('admin.user'))->addClass('text-start'),
            Column::computed('details')->title(__('admin.details'))->addClass('text-start'),
            Column::computed('time')->title(__('admin.date'))->addClass('text-center'),
            Column::computed('action')->title(__('admin.actions'))
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get DataTable language settings.
     *
     * @return array
     */
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
