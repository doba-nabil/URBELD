<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ChatDataTable;
use App\DataTables\ContactDataTable;
use App\DataTables\FavDataTable;
use App\DataTables\SearchDataTable;
use App\DataTables\SubscribersDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function search(SearchDataTable $dataTable)
    {
        $title = __('admin.search_logs');
        return $dataTable->render('dashboard.test_tables.index', compact('title'));
    }

    public function chat(ChatDataTable $dataTable)
    {
        $title = __('admin.chats');
        return $dataTable->render('dashboard.test_tables.index', compact('title'));
    }

    public function fav(FavDataTable $dataTable)
    {
        $title = __('admin.favorites');
        return $dataTable->render('dashboard.test_tables.index', compact('title'));
    }

    public function contact(ContactDataTable $dataTable)
    {
        $title = __('admin.complaints_and_suggestions');
        return $dataTable->render('dashboard.test_tables.index', compact('title'));
    }

    public function subscribers(SubscribersDataTable $dataTable)
    {
        $title = 'القائمة البريدية ';
        return $dataTable->render('dashboard.test_tables.index', compact('title'));
    }
}
