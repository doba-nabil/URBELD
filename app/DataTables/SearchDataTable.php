<?php

namespace App\DataTables;

use App\Models\SearchLog;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SearchDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('user_name', function ($q) {
                return $q->user ? e($q->user->name) : __('admin.visitor');
            })
            ->addColumn('search_type', function ($q) {
                $type = $q->search_type === 'advanced' ? __('admin.advanced_search') : __('admin.basic_search');
                return '<span class="badge bg-' . ($q->search_type === 'advanced' ? 'primary' : 'info') . '">' . $type . '</span>';
            })
            ->addColumn('search_filters_preview', function ($q) {
                $filters = $q->search_filters ?? [];
                $resolvedFilters = $this->resolveFilterNames($filters);
                $preview = [];
                
                foreach ($resolvedFilters as $key => $value) {
                    if (!empty($value) && $value !== 'all') {
                        $label = ucfirst(str_replace(['_id', '_'], ['', ' '], $key));
                        $preview[] = $label . ': ' . $displayValue = is_array($value) ? implode(', ', $value) : $value;
                    }
                }
                
                $text = !empty($preview) ? implode(', ', array_slice($preview, 0, 3)) : __('admin.no_filter');
                return '<div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;" title="' . e($text) . '">' . e($text) . '</div>';
            })
            ->addColumn('results_count', function ($q) {
                return '<span class="badge bg-success">' . $q->results_count . ' ' . __('admin.result') . '</span>';
            })
            ->addColumn('created_at', function ($q) {
                return $q->created_at ? $q->created_at->format('Y-m-d H:i') : '-';
            })
            ->addColumn('action', function ($q) {
                $resolvedFilters = $this->resolveFilterNames($q->search_filters ?? []);
                return '
        <button class="btn btn-sm btn-info view-search-details" 
                data-id="' . $q->id . '"
                data-filters=\'' . json_encode($resolvedFilters, JSON_UNESCAPED_UNICODE) . '\'>
            <i class="icon-base ti tabler-eye"></i> ' . __('admin.details') . '
        </button>';
            })
            ->rawColumns(['action', 'search_type', 'search_filters_preview', 'results_count'])
            ->setRowId('id')
            ->filterColumn('user_name', function($query, $keyword) {
                $query->whereHas('user', function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                })->orWhereNull('user_id');
            });
    }

    public function query(SearchLog $model): QueryBuilder
    {
        return $model->newQuery()->with('user')->orderBy('created_at', 'desc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('search-logs-table')
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
            Column::make('user_name')->title(__('admin.user'))->addClass('text-start'),
            Column::make('search_type')->title(__('admin.search_type'))->addClass('text-start'),
            Column::make('search_filters_preview')->title(__('admin.search_filters'))->addClass('text-start'),
            Column::make('results_count')->title(__('admin.results_count'))->addClass('text-start'),
            Column::make('created_at')->title(__('admin.date_and_time'))->addClass('text-start'),
            Column::computed('action')->title(__('admin.options'))
                ->exportable(false)
                ->printable(false)
                ->width(80)
                ->addClass('text-start'),
        ];
    }

    protected function filename(): string
    {
        return 'Search List ' . date('Y-m-d');
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

    /**
     * Resolve IDs into readable names for the filters and translate keys.
     */
    protected function resolveFilterNames(array $filters): array
    {
        $resolved = [];
        $keyMapping = [
            'category_id' => 'category',
            'sub_category_id' => 'subcategory',
            'city_id' => 'city',
            'country_id' => 'country',
        ];

        foreach ($filters as $key => $value) {
            if (empty($value) || $value === 'all') continue;

            $newKey = $key;
            $newValue = $value;

            // Determine the display label (translated)
            if (isset($keyMapping[$key])) {
                $newKey = __('admin.' . $keyMapping[$key]);
            } else {
                $cleanKey = str_replace(['_id', '_'], ['', ' '], $key);
                $translated = __('admin.' . str_replace(' ', '_', strtolower($cleanKey)));
                if (str_contains($translated, 'admin.')) {
                    $newKey = ucfirst($cleanKey);
                } else {
                    $newKey = $translated;
                }
            }

            // Resolve ID values to names
            if ($key === 'category_id' || $key === 'sub_category_id') {
                $newValue = \App\Models\Category::find($value)?->name ?? $value;
            } elseif ($key === 'city_id') {
                $newValue = \App\Models\City::find($value)?->name ?? $value;
            } elseif ($key === 'country_id') {
                $newValue = \App\Models\Country::find($value)?->name ?? $value;
            }

            $resolved[$newKey] = $newValue;
        }
        return $resolved;
    }
}

