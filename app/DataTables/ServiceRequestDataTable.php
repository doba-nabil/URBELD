<?php

namespace App\DataTables;

use App\Models\ServiceRequest;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ServiceRequestDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('user', function ($q) {
                return '<span>' . e($q->user->name ?? '-') . '</span>';
            })
            ->addColumn('category', function ($q) {
                return '<span>' . e($q->category->name ?? '-') . '</span>';
            })
            ->addColumn('status', function ($q) {
                $statusLabels = [
                    'under_review' => ['label' => __('admin.under_review'), 'class' => 'bg-label-secondary'],
                    'pending' => ['label' => __('admin.pending'), 'class' => 'bg-label-primary'],
                    'provider_accepted' => ['label' => __('admin.provider_accepted'), 'class' => 'bg-label-warning'],
                    'seeker_confirmed_provider' => ['label' => __('admin.seeker_confirmed_provider'), 'class' => 'bg-label-info'],
                    'inspection_scheduled' => ['label' => __('admin.inspection_scheduled'), 'class' => 'bg-label-info'],
                    'inspection_done' => ['label' => __('admin.inspection_done'), 'class' => 'bg-label-success'],
                    'work_completed' => ['label' => __('admin.work_completed'), 'class' => 'bg-label-success'],
                    'completed' => ['label' => __('admin.completed'), 'class' => 'bg-label-success'],
                    'time_expired' => ['label' => __('admin.time_expired'), 'class' => 'bg-label-danger'],
                    'cancelled' => ['label' => __('admin.cancelled'), 'class' => 'bg-label-secondary'],
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
            ->addColumn('response_deadline', function ($q) {
                if (!$q->response_deadline) {
                    return '-';
                }
                $isExpired = now()->isAfter($q->response_deadline);
                $class = $isExpired ? 'text-danger' : 'text-success';
                return '<span class="' . $class . '">' . $q->response_deadline->format('Y-m-d H:i') . '</span>';
            })
            ->addColumn('action', function ($q) {
                $showUrl = url('/admin-panel/service-requests/' . $q->id);
                $editUrl = url('/admin-panel/service-requests/' . $q->id . '/edit');
                $deleteUrl = url('/admin-panel/service-requests/' . $q->id);

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
            ->rawColumns(['action', 'user', 'category', 'status', 'responses_count', 'response_deadline', 'created_at'])
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

    public function query(ServiceRequest $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->with(['user', 'category'])
            ->withCount('responses');

        if (request()->has('is_consultation')) {
            $query->where('is_consultation', request()->get('is_consultation'));
        }

        return $query;
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
            Column::make('id')->title('ID')->addClass('text-start'),
            Column::make('user')->title(__('admin.service_requester'))->addClass('text-start'),
            Column::make('category')->title(__('admin.category'))->addClass('text-start'),
            Column::make('status')->title(__('admin.status'))->addClass('text-center'),
            Column::make('responses_count')->title(__('admin.responses_count'))->addClass('text-center'),
            Column::make('response_deadline')->title(__('admin.response_deadline'))->addClass('text-start'),
            Column::make('created_at')->title(__('admin.creation_date'))->addClass('text-start'),
            Column::computed('action')->title(__('admin.action'))
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-start'),
        ];
    }

    protected function filename(): string
    {
        return 'ServiceRequests ' . date('Y-m-d');
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
