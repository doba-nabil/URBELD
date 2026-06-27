<?php

namespace App\DataTables;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FaqDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('question', function ($faq) {
                return $faq->question;
            })
            ->editColumn('is_active', function ($faq) {
                return $faq->is_active ? '<span class="badge bg-success">' . __('admin.active') . '</span>' : '<span class="badge bg-danger">' . __('admin.inactive') . '</span>';
            })
            ->addColumn('action', function ($faq) {
                $editUrl = route('faqs.edit', $faq->id);
                $deleteUrl = route('faqs.destroy', $faq->id);
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
                            data-id="' . $faq->id . '"
                            data-url="' . $deleteUrl . '"
                            data-table=".table"
                            title="' . __("admin.delete") . '">
                            <i class="icon-base ti tabler-trash"></i> ' . __("admin.delete") . '
                        </a>
                    </li>
                </ul>
            </div>';
            })
            ->rawColumns(['action', 'is_active'])
            ->setRowId('id');
    }

    public function query(Faq $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('faq-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
            ])->parameters([
                'language' => $this->getDataTableLanguage()
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('#')->addClass('text-start'),
            Column::make('question')->title(__('admin.question'))->addClass('text-start'),
            Column::make('is_active')->title(__('admin.status'))->addClass('text-start'),
            Column::computed('action')->title(__('admin.action'))
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-start'),
        ];
    }

    protected function filename(): string
    {
        return 'Faqs ' . date('Y-m-d');
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
