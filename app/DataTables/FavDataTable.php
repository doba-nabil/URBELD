<?php

namespace App\DataTables;

use App\Models\Favourite;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FavDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('user_name', function ($q) {
                return $q->user ? e($q->user->name) : __('admin.not_specified');
            })
            ->addColumn('user_gender', function ($q) {
                if (!$q->user || !$q->user->profile) {
                    return __('admin.not_specified');
                }
                return $q->user->profile->gender === 'male' ? __('admin.male') : __('admin.female');
            })
            ->addColumn('favorite_user_name', function ($q) {
                return $q->otherUser ? e($q->otherUser->name) : __('admin.not_specified');
            })
            ->addColumn('favorite_user_gender', function ($q) {
                if (!$q->otherUser || !$q->otherUser->profile) {
                    return __('admin.not_specified');
                }
                return $q->otherUser->profile->gender === 'male' ? __('admin.male') : __('admin.female');
            })
            ->addColumn('created_at', function ($q) {
                return $q->created_at ? $q->created_at->format('Y-m-d H:i') : '-';
            })
            ->addColumn('action', function ($q) {
                return '
        <button class="btn btn-sm btn-danger delete-favorite" 
                data-id="' . $q->id . '"
                data-user="' . e($q->user->name ?? __('admin.not_specified')) . '"
                data-favorite="' . e($q->otherUser->name ?? __('admin.not_specified')) . '">
            <i class="icon-base ti tabler-trash"></i> ' . __('admin.delete') . '
        </button>';
            })
            ->rawColumns(['action'])
            ->setRowId('id')
            ->filterColumn('user_name', function($query, $keyword) {
                $query->whereHas('user', function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('favorite_user_name', function($query, $keyword) {
                $query->whereHas('otherUser', function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            });
    }

    public function query(Favourite $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['user', 'otherUser'])
            ->orderBy('created_at', 'desc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('favorites-table')
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
            Column::make('user_name')->title(__('admin.liker_name'))->addClass('text-start'),
            Column::make('user_gender')->title(__('admin.liker_gender'))->addClass('text-start'),
            Column::make('favorite_user_name')->title(__('admin.liked_user_name'))->addClass('text-start'),
            Column::make('favorite_user_gender')->title(__('admin.liked_user_gender'))->addClass('text-start'),
            Column::make('created_at')->title(__('admin.like_date'))->addClass('text-start'),
            Column::computed('action')->title(__('admin.options'))
                ->exportable(false)
                ->printable(false)
                ->width(80)
                ->addClass('text-start'),
        ];
    }

    protected function filename(): string
    {
        return 'Favourits ' . date('Y-m-d');
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

