<?php

namespace App\DataTables;

use App\Models\SupplyRequest;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SupplyRequestDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('user', function ($q) {
                return '<span>' . e($q->user->name ?? '-') . '</span>';
            })
            ->addColumn('city', function ($q) {
                return '<span>' . e($q->city->name ?? '-') . '</span>';
            })
            ->addColumn('status', function ($q) {
                $statusLabels = [
                    'pending' => ['label' => 'قيد المراجعة', 'class' => 'bg-label-secondary'],
                    'open' => ['label' => 'طلب جديد', 'class' => 'bg-label-primary'],
                    'in_progress' => ['label' => 'قيد التنفيذ', 'class' => 'bg-label-warning'],
                    'completed' => ['label' => 'مكتمل', 'class' => 'bg-label-success'],
                    'closed' => ['label' => 'مغلق', 'class' => 'bg-label-danger'],
                ];
                $status = $statusLabels[$q->status] ?? ['label' => $q->status, 'class' => 'bg-label-secondary'];
                return '<span class="badge ' . $status['class'] . '">' . $status['label'] . '</span>';
            })
            ->addColumn('created_at', function ($q) {
                return '<span class="text-nowrap">' . $q->created_at->format('Y-m-d H:i') . '</span>';
            })
            ->addColumn('responses_count', function ($q) {
                $count = $q->responses()->count();
                return '<span class="badge bg-label-info">' . $count . '</span>';
            })
            ->addColumn('action', function ($q) {
                $showUrl = url('/admin-panel/supply-requests/' . $q->id);
                $deleteUrl = url('/admin-panel/supply-requests/' . $q->id);

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
            ->rawColumns(['action', 'user', 'city', 'status', 'responses_count', 'created_at'])
            ->setRowId('id')
            ->filterColumn('user', function ($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('city', function ($query, $keyword) {
                $query->whereHas('city', function ($q) use ($keyword) {
                    $q->where('name->ar', 'like', "%{$keyword}%")
                        ->orWhere('name->en', 'like', "%{$keyword}%");
                });
            });
    }

    public function query(SupplyRequest $model): QueryBuilder
    {
        return $model->newQuery()->with(['user', 'city', 'responses'])->latest();
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
                'language' => $this->getDataTableLanguage(),
                'scrollX' => true,
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('title')->title('عنوان الطلب'),
            Column::make('user')->title('صاحب الطلب'),
            Column::make('city')->title('المدينة'),
            Column::make('status')->title('الحالة')->addClass('text-center'),
            Column::make('responses_count')->title('العروض')->addClass('text-center')->searchable(false)->orderable(false),
            Column::make('created_at')->title('تاريخ الطلب'),
            Column::computed('action')->title('إجراءات')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'SupplyRequests_' . date('YmdHis');
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
