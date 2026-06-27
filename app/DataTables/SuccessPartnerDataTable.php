<?php

namespace App\DataTables;

use App\Models\SuccessPartner;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SuccessPartnerDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('title', function ($q) {
                return e($q->title);
            })
            ->addColumn('image', function ($q) {
                $imageUrl = $q->getFirstMediaUrl('partners');
                return $imageUrl
                    ? '<img src="' . $imageUrl . '" alt="" width="60" class="rounded" />'
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
                        <li><a href="' . route('success-partners.edit', $q->id) . '" class="dropdown-item">
                            <i class="icon-base ti tabler-edit"></i> ' . __('admin.edit') . '
                        </a></li>
                        <li><a href="javascript:void(0)" class="dropdown-item delete-btn" 
                            data-id="' . $q->id . '" 
                            data-url="' . route('success-partners.destroy', $q->id) . '" 
                            data-table=".table">
                            <i class="icon-base ti tabler-trash"></i> ' . __('admin.delete') . '
                        </a></li>
                    </ul>
                </div>';
            })
            ->rawColumns(['image', 'is_active', 'action'])
            ->setRowId('id');
    }

    public function query(SuccessPartner $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(3, 'asc')
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
            Column::make('image')->title(__('admin.image')),
            Column::make('sort_order')->title(__('admin.sort_order')),
            Column::make('is_active')->title(__('admin.status')),
            Column::computed('action')->title(__('admin.actions'))->exportable(false)->printable(false)->width(60)->addClass('text-center'),
        ];
    }
}
