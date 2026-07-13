<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SupplierProductDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('image', function ($q) {
                if ($q->hasMedia('product_images')) {
                    return '<img src="' . $q->getFirstMediaUrl('product_images') . '" width="50" height="50" class="rounded">';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('title', function ($q) {
                return '<span>' . e($q->title) . '</span>';
            })
            ->addColumn('supplier', function ($q) {
                return '<span>' . e($q->user->name ?? '-') . '</span>';
            })
            ->addColumn('price', function ($q) {
                return '<span>' . ($q->price ? e($q->price) . ' ' . __('admin.sar') : '-') . '</span>';
            })
            ->addColumn('action', function ($q) {
                $editUrl = route('supplier-products.edit', $q->id);
                $deleteUrl = route('supplier-products.destroy', $q->id);

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
            ->rawColumns(['action', 'image', 'title', 'supplier', 'price'])
            ->setRowId('id');
    }

    public function query(Product $model): QueryBuilder
    {
        $query = $model->newQuery()->with('user')->latest();
        
        if (request()->has('supplier_id')) {
            $query->where('user_id', request('supplier_id'));
        }
        
        return $query;
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
                'language' => $this->getDataTableLanguage()
            ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('id')->title('#'),
            Column::make('image')->title(__('admin.image'))->orderable(false)->searchable(false),
            Column::make('title')->title(__('admin.name')),
            Column::make('supplier')->title('المورد')->name('user.name'),
            Column::make('price')->title(__('admin.price')),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center')
                ->title(__('admin.options')),
        ];
    }

    protected function getDataTableLanguage()
    {
        return [
            'sProcessing' => __('admin.processing'),
            'sLengthMenu' => __('admin.show_entries'),
            'sZeroRecords' => __('admin.no_records_found'),
            'sInfo' => __('admin.showing_entries'),
            'sInfoEmpty' => __('admin.showing_entries_empty'),
            'sInfoFiltered' => __('admin.filtered_from'),
            'sSearch' => __('admin.search'),
            'oPaginate' => [
                'sFirst' => __('admin.first'),
                'sPrevious' => __('admin.previous'),
                'sNext' => __('admin.next'),
                'sLast' => __('admin.last'),
            ]
        ];
    }
}
