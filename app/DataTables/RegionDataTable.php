<?php

namespace App\DataTables;

use App\Models\Region;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RegionDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Region> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($row) {
                return view('dashboard.regions._actions', compact('row'))->render();
            })
            ->editColumn('name', function ($row) {
                return $row->name;
            })
            ->editColumn('country.name', function ($row) {
                return $row->country ? $row->country->name : '-';
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Region>
     */
    public function query(Region $model): QueryBuilder
    {
        return $model->newQuery()->with('country');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('region-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(0)
                    ->parameters([
                        'language' => ['url' => '//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json'],
                        'scrollX' => true,
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('#'),
            Column::make('name')->title(__('admin.name'))->name('name'),
            Column::make('country.name')->title(__('admin.country'))->name('country.name'),
            Column::computed('action')
                  ->title(__('admin.actions'))
                  ->exportable(false)
                  ->printable(false)
                  ->width(100)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Region_' . date('YmdHis');
    }
}
