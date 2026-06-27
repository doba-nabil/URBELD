<?php

namespace App\DataTables;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<User> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('user', function ($user) {
                $url = $user->getFirstMediaUrl('users');
                $img = $url
                    ? '<img height="30" width="30" style="object-fit:cover; border-radius:50%; margin-right:8px;margin-left:8px;" src="' . $url . '" alt="user image">'
                    : '';

                $name = e($user->getAttributes()['name'] ?? $user->name);

                $nameHtml = '<div><strong>' . $name . '</strong></div>';


                return '<div class="d-flex align-items-center">'
                    . $img .
                    '<div>' . $nameHtml . '</div>
        </div>';
            })
            ->addColumn('action', function ($user) {
                $editUrl = url('/admin-panel/users/' . $user->id . '/edit');
                $deleteUrl = url('/admin-panel/users/' . $user->id);

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
                        data-id="' . $user->id . '"
                        data-url="' . $deleteUrl . '"
                        data-table=".table"
                        title="' . __("admin.delete") . '">
                       <i class="icon-base ti tabler-trash"></i> ' . __("admin.delete") . '
                    </a>
                </li>
            </ul>
        </div>';
            })
            ->addColumn('active', function ($user) {
                if ($user->active === 'active') {
                    return '<span class="badge bg-label-success">' . __('admin.active') . '</span>';
                } elseif ($user->active === 'blocked' || $user->active === '0' || $user->active === 0) {
                    return '<span class="badge bg-label-danger">' . __('admin.blocked') . '</span>';
                } elseif ($user->active === 'pending') {
                    return '<span class="badge bg-label-warning">' . __('admin.pending') . '</span>';
                }
                return '<span class="badge bg-label-secondary">' . __('admin.unactive') . '</span>';
            })
            ->rawColumns(['action', 'user', 'active'])
            ->setRowId('id')
            ->filterColumn('user', function ($query, $keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            });

    }


    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<User>
     */
    public function query(User $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->where('is_admin', 0)
            // Show all users except service_providers (companies and engineers)
            // This includes: service_seekers, users with null user_type, or any other type except service_provider
            ->where(function ($q) {
                $q->where('user_type', '!=', 'service_provider')
                    ->orWhereNull('user_type');
            })
            ->with(['city', 'categories', 'membership']);

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('category-table')
            ->columns($this->getColumns())
            ->minifiedAjax(url()->current() . '?' . http_build_query(request()->all()))
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

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('#')->addClass('text-start'),
            Column::make('user')->title(__('admin.name'))->addClass('text-start'),
            Column::make('active')->title(__('admin.user_status'))->addClass('text-start'),
            Column::make('phone')->title(__('admin.phone'))->addClass('text-start'),
            Column::make('email')->title(__('admin.email'))->addClass('text-start'),
            Column::computed('action')->title(__('admin.action'))
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-start'),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Users ' . date('Y-m-d');
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
