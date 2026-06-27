<?php

namespace App\DataTables;

use App\Models\UserMembershipHistory;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserMembershipHistoryDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('user', function ($q) {
                return '<span>' . e($q->user->name ?? '-') . '</span>';
            })
            ->addColumn('membership', function ($q) {
                $membershipName = $q->membership->name ?? '-';
                if (is_array($membershipName)) {
                    $locale = app()->getLocale();
                    $membershipName = $membershipName[$locale] ?? $membershipName['ar'] ?? $membershipName['en'] ?? '-';
                }
                return '<span>' . e($membershipName) . '</span>';
            })
            ->addColumn('status', function ($q) {
                $statuses = [
                    'active' => ['label' => __('admin.active'), 'class' => 'badge bg-label-success'],
                    'expired' => ['label' => __('admin.expired'), 'class' => 'badge bg-label-danger'],
                    'cancelled' => ['label' => __('admin.cancelled'), 'class' => 'badge bg-label-secondary'],
                ];
                $status = $statuses[$q->status] ?? ['label' => $q->status, 'class' => 'badge bg-label-secondary'];
                return '<span class="' . $status['class'] . '">' . $status['label'] . '</span>';
            })
            ->addColumn('account_status', function ($q) {
                if (!$q->user) return '-';
                $statuses = [
                    'active' => ['label' => __('admin.active'), 'class' => 'badge bg-label-success'],
                    'pending' => ['label' => __('admin.under_review'), 'class' => 'badge bg-label-warning'],
                    'blocked' => ['label' => __('admin.blocked'), 'class' => 'badge bg-label-danger'],
                ];
                $status = $statuses[$q->user->active] ?? ['label' => $q->user->active, 'class' => 'badge bg-label-secondary'];
                return '<span class="' . $status['class'] . '">' . $status['label'] . '</span>';
            })
            ->addColumn('price_paid', function ($q) {
                return '<span>' . number_format($q->price_paid, 2) . ' ' . __('admin.currency') . '</span>';
            })
            ->addColumn('started_at', function ($q) {
                return $q->started_at ? $q->started_at->format('Y-m-d H:i') : '-';
            })
            ->addColumn('expires_at', function ($q) {
                if (!$q->expires_at) {
                    return '-';
                }
                $isExpired = now()->isAfter($q->expires_at);
                $class = $isExpired ? 'text-danger' : 'text-success';
                return '<span class="' . $class . '">' . $q->expires_at->format('Y-m-d H:i') . '</span>';
            })
            ->addColumn('action', function ($q) {
                $viewUrl = route('user-membership-history.show', $q->id);
                return '<a href="' . $viewUrl . '" class="btn btn-sm btn-info">
                    <i class="icon-base ti tabler-eye"></i> ' . __('admin.view') . '
                </a>';
            })
            ->rawColumns(['action', 'user', 'membership', 'status', 'account_status', 'price_paid', 'expires_at'])
            ->setRowId('id');
    }

    public function query(UserMembershipHistory $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['user', 'membership'])
            ->latest();
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
            Column::make('user')->title(__('admin.user'))->addClass('text-start'),
            Column::make('membership')->title(__('admin.membership'))->addClass('text-start'),
            Column::make('price_paid')->title(__('admin.price_paid'))->addClass('text-center'),
            Column::make('started_at')->title(__('admin.started_at'))->addClass('text-center'),
            Column::make('expires_at')->title(__('admin.expires_at'))->addClass('text-center'),
            Column::make('status')->title(__('admin.membership_status'))->addClass('text-center'),
            Column::make('account_status')->title(__('admin.account_status'))->addClass('text-center'),
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
