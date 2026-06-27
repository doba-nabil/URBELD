<?php

namespace App\DataTables;

use App\Models\Service;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ServiceDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('provider', function ($q) {
                if ($q->user_id && $q->user) {
                    $route = $q->user->isServiceProvider() ? 'memberships.edit' : 'users.edit';
                    return '<a href="' . route($route, $q->user_id) . '">' . e($q->user->name) . '</a>';
                }
                return '<span class="text-muted">' . __('admin.global') . '</span>';
            })
            ->addColumn('category_info', function ($q) {
                if ($q->category_id && $q->category) {
                    $html = '<span class="badge bg-primary">' . e($q->category->name) . '</span>';
                    if ($q->sub_category_id && $q->subCategory) {
                        $html .= '<br><span class="badge bg-secondary mt-1">' . e($q->subCategory->name) . '</span>';
                    }
                    return $html;
                }
                return '-';
            })
            ->addColumn('title', function ($q) {
                return e($q->title);
            })
            ->addColumn('icon', function ($q) {
                if ($q->icon) {
                    return '<i class="' . e($q->icon) . '"></i> ' . ($q->icon_title ? e($q->icon_title) : '');
                }
                return '-';
            })
            ->addColumn('image', function ($q) {
                $imageUrl = $q->getFirstMediaUrl('services');
                return $imageUrl
                    ? '<img src="' . $imageUrl . '" alt="" width="40" class="rounded-circle" />'
                    : '-';
            })
            ->addColumn('sort_order', function ($q) {
                return $q->sort_order;
            })
            ->addColumn('is_active', function ($q) {
                return $q->is_active
                    ? '<span class="badge bg-label-success">' . __('admin.active') . '</span>'
                    : '<span class="badge bg-label-danger">' . __('admin.inactive') . '</span>';
            })
            ->addColumn('action', function ($q) {
                return '<div class="dropdown">
                    <button class="btn btn-sm btn-default" type="button" data-bs-toggle="dropdown">
                        <i class="icon-base ti tabler-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="' . route('services.edit', $q->id) . '" class="dropdown-item">
                            <i class="icon-base ti tabler-edit"></i> ' . __('admin.edit') . '
                        </a></li>
                        <li><a href="javascript:void(0)" class="dropdown-item delete-btn" 
                            data-id="' . $q->id . '" 
                            data-url="' . route('services.destroy', $q->id) . '" 
                            data-table=".table">
                            <i class="icon-base ti tabler-trash"></i> ' . __('admin.delete') . '
                        </a></li>
                    </ul>
                </div>';
            })
            ->rawColumns(['icon', 'image', 'provider', 'category_info', 'is_active', 'action'])
            ->setRowId('id');
    }

    public function query(Service $model): QueryBuilder
    {
        return $model->newQuery()->with(['user', 'category', 'subCategory']);
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
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('#'),
            Column::make('title')->title(__('admin.title')),
            Column::make('provider')->title(__('admin.provider'))->searchable(false)->orderable(false),
            Column::make('category_info')->title(__('admin.category'))->searchable(false)->orderable(false),
            Column::make('image')->title(__('admin.image'))->searchable(false)->orderable(false),
            Column::make('sort_order')->title(__('admin.sort_order')),
            Column::make('is_active')->title(__('admin.status')),
            Column::computed('action')->title(__('admin.actions'))->exportable(false)->printable(false)->width(60)->addClass('text-center'),
        ];
    }
}
