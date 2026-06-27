<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\FavDataTable;
use App\Http\Controllers\Controller;
use App\Models\Favourite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(FavDataTable $dataTable)
    {
        return $dataTable->render('dashboard.favorites.index');
    }

    public function destroy($id)
    {
        try {
            $favorite = Favourite::findOrFail($id);
            $favorite->delete();

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

