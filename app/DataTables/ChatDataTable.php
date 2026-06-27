<?php

namespace App\DataTables;

use App\Models\Chat;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ChatDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('from_user', function ($q) {
                // Determine sender: prioritize first message sender, then from_user_id, fallback to first participant
                $senderId = $q->firstMessage ? $q->firstMessage->sender_id : ($q->from_user_id ?: ($q->participants->first() ? $q->participants->first()->id : null));
                
                $sender = $q->participants->where('id', $senderId)->first();
                if (!$sender && $q->fromUser) $sender = $q->fromUser;
                if (!$sender && $q->toUser) $sender = $q->toUser;

                if (!$sender) return __('admin.not_specified');
                $roleHtml = $sender->user_type == 'provider' ? ' <br><small class="text-muted">' . __('website.service_provider') . '</small>' : '';
                return e($sender->name) . $roleHtml;
            })
            ->addColumn('to_user', function ($q) {
                // Determine sender id first to find the receiver
                $senderId = $q->firstMessage ? $q->firstMessage->sender_id : ($q->from_user_id ?: ($q->participants->first() ? $q->participants->first()->id : null));
                
                // Receiver is the participant who is NOT the sender
                $receiver = $q->participants->where('id', '!=', $senderId)->first();
                
                // Fallbacks
                if (!$receiver) {
                    if ($q->to_user_id && $q->to_user_id != $senderId) $receiver = $q->toUser;
                    else if ($q->from_user_id && $q->from_user_id != $senderId) $receiver = $q->fromUser;
                }

                if (!$receiver) return __('admin.not_specified');
                $roleHtml = $receiver->user_type == 'provider' ? ' <br><small class="text-muted">' . __('website.service_provider') . '</small>' : '';
                return e($receiver->name) . $roleHtml;
            })
            ->addColumn('messages_count', function ($q) {
                return '<span class="badge bg-primary">' . $q->messages()->count() . ' ' . __('admin.message') . '</span>';
            })
            ->addColumn('service_request', function ($q) {
                return $q->serviceRequest ? '<a href="'.route('service-requests.show', $q->serviceRequest->id).'">#' . $q->serviceRequest->id . '</a>' : '-';
            })
            ->addColumn('last_message_date', function ($q) {
                $lastMessage = $q->lastMessage;
                return $lastMessage ? $lastMessage->created_at->format('Y-m-d H:i') : '-';
            })
            ->addColumn('created_at', function ($q) {
                return $q->created_at ? $q->created_at->format('Y-m-d H:i') : '-';
            })
            ->addColumn('action', function ($q) {
                $senderId = $q->firstMessage ? $q->firstMessage->sender_id : ($q->from_user_id ?: ($q->participants->first() ? $q->participants->first()->id : null));
                $sender = $q->participants->where('id', $senderId)->first();
                $receiver = $q->participants->where('id', '!=', $senderId)->first();

                $fromName = $sender ? $sender->name : __('admin.not_specified');
                $toName = $receiver ? $receiver->name : __('admin.not_specified');

                return '
        <button class="btn btn-sm btn-info view-chat-messages" 
                data-uuid="' . $q->uuid . '"
                data-from="' . e($fromName) . '"
                data-to="' . e($toName) . '">
            <i class="icon-base ti tabler-eye"></i> ' . __('admin.view_messages') . '
        </button>
        <button class="btn btn-sm btn-danger delete-chat" 
                data-id="' . $q->id . '"
                data-from="' . e($fromName) . '"
                data-to="' . e($toName) . '">
            <i class="icon-base ti tabler-trash"></i> ' . __('admin.delete') . '
        </button>';
            })
            ->rawColumns(['action', 'messages_count', 'from_user', 'to_user', 'service_request'])
            ->setRowId('id');
    }

    public function query(Chat $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['fromUser', 'toUser', 'lastMessage', 'firstMessage', 'participants'])
            ->orderBy('updated_at', 'desc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('chats-table')
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
            Column::make('id')->title('#')->width(50),
            Column::make('service_request')->title(__('website.service_request'))->addClass('text-start'),
            Column::make('from_user')->title(__('admin.sender'))->addClass('text-start'),
            Column::make('to_user')->title(__('admin.receiver'))->addClass('text-start'),
            Column::make('messages_count')->title(__('admin.messages_count'))->addClass('text-start'),
            Column::make('created_at')->title(__('admin.chat_start_date'))->addClass('text-start'),
            Column::make('last_message_date')->title(__('admin.last_message'))->addClass('text-start'),
            Column::computed('action')->title(__('admin.options'))
                ->exportable(false)
                ->printable(false)
                ->width(160)
                ->addClass('text-start'),
        ];
    }

    protected function filename(): string
    {
        return 'Chats ' . date('Y-m-d');
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

