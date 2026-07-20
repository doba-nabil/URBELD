<?php

namespace App\DataTables;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class NotificationDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('type_icon', function ($q) {
                $icons = [
                    'message' => '<i class="ti tabler-mail text-primary"></i>',
                    'visit' => '<i class="ti tabler-eye text-info"></i>',
                    'payment' => '<i class="ti tabler-cash text-success"></i>',
                    'subscribe' => '<i class="ti tabler-user-plus text-info"></i>',
                    'contact' => '<i class="ti tabler-phone text-warning"></i>',
                    'request_moderation' => '<i class="ti tabler-shield text-warning"></i>',
                    'response_moderation' => '<i class="ti tabler-clipboard-check text-success"></i>',
                    'new_request' => '<i class="ti tabler-briefcase text-primary"></i>',
                    'supply_request' => '<i class="ti tabler-truck text-info"></i>',
                    'new_tender_admin' => '<i class="ti tabler-gavel text-warning"></i>',
                    'new_member' => '<i class="ti tabler-user-plus text-primary"></i>',
                ];
                return $icons[$q->type] ?? '<i class="ti tabler-bell text-secondary"></i>';
            })
            ->addColumn('title_display', function ($q) {
                $icons = [
                    'message' => '<i class="ti tabler-mail text-primary"></i>',
                    'visit' => '<i class="ti tabler-eye text-info"></i>',
                    'payment' => '<i class="ti tabler-cash text-success"></i>',
                    'subscribe' => '<i class="ti tabler-user-plus text-info"></i>',
                    'contact' => '<i class="ti tabler-phone text-warning"></i>',
                    'request_moderation' => '<i class="ti tabler-shield text-warning"></i>',
                    'response_moderation' => '<i class="ti tabler-clipboard-check text-success"></i>',
                    'new_request' => '<i class="ti tabler-briefcase text-primary"></i>',
                    'supply_request' => '<i class="ti tabler-truck text-info"></i>',
                    'new_tender_admin' => '<i class="ti tabler-gavel text-warning"></i>',
                    'new_member' => '<i class="ti tabler-user-plus text-primary"></i>',
                ];
                $icon = $icons[$q->type] ?? '<i class="ti tabler-bell text-secondary"></i>';
                $badge = $q->is_read ? '' : '<span class="badge bg-warning ms-2">' . __('admin.new') . '</span>';
                $link = $q->link ? $q->link : 'javascript:void(0)';
                return '<a href="'.$link.'" class="text-body fw-bold text-decoration-none">' . $icon . ' ' . e($q->title) . '</a> ' . $badge;
            })
            ->addColumn('status', function ($q) {
                return $q->is_read
                    ? '<span class="badge bg-label-success"><i class="ti tabler-check"></i> ' . __('admin.read') . '</span>'
                    : '<span class="badge bg-label-warning"><i class="ti tabler-alert-circle"></i> ' . __('admin.unread') . '</span>';
            })
            ->addColumn('created_at_formatted', function ($q) {
                return $q->created_at->format('Y-m-d H:i');
            })
            ->addColumn('action', function ($q) {
                $html = '';
                if ($q->link) {
                    $html .= '<a href="'.$q->link.'" class="btn btn-sm btn-outline-primary me-2"><i class="ti tabler-eye"></i></a>';
                }
                $html .= '<button class="btn btn-sm btn-outline-danger delete-notification" data-id="' . $q->id . '">';
                $html .= '<i class="ti tabler-trash"></i>';
                $html .= '</button>';
                return $html;
            })
            ->rawColumns(['type_icon', 'title_display', 'status', 'action'])
            ->setRowId('id');
    }

    public function query(Notification $model): QueryBuilder
    {
        return $model->newQuery()->where('user_id', auth()->guard('admin')->id())->orderBy('created_at', 'desc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('notifications-table')
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
            Column::make('title_display')->title(__('admin.notification'))->addClass('text-start'),
            Column::make('message')->title(__('admin.message_text'))->addClass('text-start'),
            Column::make('status')->title(__('admin.status'))->addClass('text-start'),
            Column::make('created_at_formatted')->title(__('admin.date_and_time'))->addClass('text-start'),
            Column::computed('action')->title(__('admin.options'))
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->addClass('text-start'),
        ];
    }

    protected function filename(): string
    {
        return 'Notifications_' . date('Y-m-d');
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
        ][$locale] ?? [
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
        ];
    }
}

