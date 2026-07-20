<?php

namespace App\DataTables;

use App\Models\TenderPayPerUse;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TenderPaymentDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('created_at', function ($model) {
                return $model->created_at ? $model->created_at->format('Y-m-d H:i') : '-';
            })
            ->editColumn('user_id', function ($model) {
                if ($model->user) {
                    return $model->user->name;
                }
                return __('admin.unknown');
            })
            ->editColumn('type', function ($model) {
                return $model->type === 'add' 
                    ? '<span class="badge bg-primary">'.__('admin.pay_to_add').'</span>' 
                    : '<span class="badge bg-success">'.__('admin.pay_to_apply').'</span>';
            })
            ->editColumn('status', function ($model) {
                if ($model->status === 'paid') {
                    return '<span class="badge bg-info">'.__('admin.paid').'</span>';
                }
                return '<span class="badge bg-secondary">'.__('admin.used').'</span>';
            })
            ->editColumn('amount_paid', function ($model) {
                return number_format($model->amount_paid, 2) . ' ' . __('admin.sar');
            })
            ->rawColumns(['type', 'status']);
    }

    public function query(TenderPayPerUse $model)
    {
        return $model->newQuery()->with(['user'])->orderBy('id', 'desc');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('tender-payments-table')
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
            ])
            ->parameters([
                'language' => $this->getDataTableLanguage()
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::make('id')->title('#'),
            Column::make('user_id')->title(__('admin.user')),
            Column::make('type')->title(__('admin.payment_type')),
            Column::make('amount_paid')->title(__('admin.paid_amount')),
            Column::make('status')->title(__('admin.payment_status')),
            Column::make('payment_reference')->title(__('admin.payment_reference')),
            Column::make('created_at')->title(__('admin.paid_at')),
        ];
    }

    protected function filename(): string
    {
        return 'TenderPayments_' . date('YmdHis');
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
