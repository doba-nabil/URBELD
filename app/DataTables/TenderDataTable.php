<?php

namespace App\DataTables;

use App\Models\Tender;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TenderDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('title', function ($q) {
                $title = e($q->title);
                if ($q->is_urgent) {
                    $title .= ' <span class="badge bg-warning ms-1">عاجل</span>';
                }
                return '<span>' . $title . '</span>';
            })
            ->addColumn('user', function ($q) {
                return '<span>' . e($q->user->name ?? 'غير معروف') . '</span>';
            })
            ->addColumn('category', function ($q) {
                return '<span>' . e($q->category->name ?? 'غير محدد') . '</span>';
            })
            ->addColumn('status', function ($q) {
                $statusLabels = [
                    Tender::STATUS_PENDING_REVIEW => ['label' => 'بانتظار المراجعة', 'class' => 'bg-label-warning'],
                    Tender::STATUS_ACTIVE => ['label' => 'معتمد ونشط', 'class' => 'bg-label-success'],
                    Tender::STATUS_CLOSED => ['label' => 'مغلق', 'class' => 'bg-label-danger'],
                    Tender::STATUS_REJECTED => ['label' => 'مرفوض', 'class' => 'bg-label-danger'],
                    Tender::STATUS_IN_PROGRESS => ['label' => 'قيد التنفيذ', 'class' => 'bg-label-info'],
                    Tender::STATUS_COMPLETED => ['label' => 'مكتمل', 'class' => 'bg-label-primary'],
                ];
                $status = $statusLabels[$q->status] ?? ['label' => $q->status, 'class' => 'bg-label-secondary'];
                return '<span class="badge ' . $status['class'] . '">' . $status['label'] . '</span>';
            })
            ->addColumn('ends_at', function ($q) {
                return '<span class="text-nowrap">' . ($q->ends_at ? $q->ends_at->format('Y-m-d') : '-') . '</span>';
            })
            ->addColumn('action', function ($q) {
                $showUrl = url('/admin-panel/tenders/' . $q->id);

                return '
        <div class="dropdown">
            <button class="btn btn-sm btn-default" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="icon-base ti tabler-dots-vertical"></i>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a href="' . $showUrl . '" class="dropdown-item">
                        <i class="icon-base ti tabler-eye"></i> ' . __("admin.view") . '
                    </a>
                </li>
            </ul>
        </div>';
            })
            ->rawColumns(['action', 'title', 'user', 'category', 'status', 'ends_at'])
            ->setRowId('id')
            ->filterColumn('user', function ($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('category', function ($query, $keyword) {
                $query->whereHas('category', function ($q) use ($keyword) {
                    $q->where('name->ar', 'like', "%{$keyword}%")
                        ->orWhere('name->en', 'like', "%{$keyword}%");
                });
            });
    }

    public function query(Tender $model): QueryBuilder
    {
        return $model->newQuery()->with(['user', 'category', 'city'])->latest();
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
                'language' => $this->getDataTableLanguage(),
                'scrollX' => true,
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('#'),
            Column::make('title')->title('العنوان'),
            Column::make('user')->title('صاحب الطلب'),
            Column::make('category')->title('التصنيف'),
            Column::make('ends_at')->title('تاريخ الانتهاء'),
            Column::make('status')->title('الحالة')->addClass('text-center'),
            Column::computed('action')->title('إجراءات')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Tenders_' . date('YmdHis');
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
