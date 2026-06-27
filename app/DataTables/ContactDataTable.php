<?php

namespace App\DataTables;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ContactDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('name', function ($q) {
                return e($q->name);
            })
            ->addColumn('phone', function ($q) {
                return e($q->phone ?? 'N/A');
            })
            ->addColumn('email', function ($q) {
                return e($q->email ?? 'N/A');
            })
            ->addColumn('message_preview', function ($q) {
                return '<div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">' . e(substr($q->message, 0, 100)) . '...</div>';
            })
            ->addColumn('created_at', function ($q) {
                return $q->created_at->format('Y-m-d H:i');
            })
            ->addColumn('action', function ($q) {
                $deleteUrl = url('/admin-panel/contacts/' . $q->id);

                return '
        <div class="dropdown">
            <button class="btn btn-sm btn-default" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="icon-base ti tabler-dots-vertical"></i>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a href="javascript:void(0)" class="dropdown-item view-contact" 
                       data-id="' . $q->id . '"
                       data-name="' . e($q->name) . '"
                       data-phone="' . e($q->phone) . '"
                       data-email="' . e($q->email) . '"
                       data-message="' . e($q->message) . '">
                        <i class="icon-base ti tabler-eye"></i> ' . __('admin.view') . '
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="dropdown-item delete-btn"
                        data-id="' . $q->id . '"
                        data-url="' . $deleteUrl . '"
                        data-table=".table"
                        title="' . __('admin.delete') . '">
                        <i class="icon-base ti tabler-trash"></i> ' . __('admin.delete') . '
                    </a>
                </li>
            </ul>
        </div>';
            })
            ->rawColumns(['action', 'message_preview'])
            ->setRowId('id')
            ->filterColumn('name', function($query, $keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            });
    }

    public function query(Contact $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('created_at', 'desc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
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

    protected function getColumns(): array
    {
        return [
            Column::make('name')->title(__('admin.name'))->addClass('text-start'),
            Column::make('phone')->title(__('admin.phone'))->addClass('text-start'),
            Column::make('email')->title(__('admin.email'))->addClass('text-start'),
            Column::make('message_preview')->title(__('admin.message_text'))->addClass('text-start'),
            Column::make('created_at')->title(__('admin.date'))->addClass('text-start'),
            Column::computed('action')->title(__('admin.options'))
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-start'),
        ];
    }

    protected function filename(): string
    {
        return 'Contact Form ' . date('Y-m-d');
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

