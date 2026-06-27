<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SearchDataTable;
use App\Http\Controllers\Controller;
use App\Models\SearchLog;
use Illuminate\Http\Request;

class SearchLogController extends Controller
{
    public function index(SearchDataTable $dataTable)
    {
        return $dataTable->render('dashboard.search_logs.index');
    }

    public function destroy($id)
    {
        try {
            $searchLog = SearchLog::findOrFail($id);
            $searchLog->delete();

            return response()->json([
                'status' => 'success',
                'message' => __('admin.delete_success')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('admin.delete_error')
            ], 500);
        }
    }
}

