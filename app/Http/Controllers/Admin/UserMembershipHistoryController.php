<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserMembershipHistoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\UserMembershipHistory;
use App\Traits\HasActivityFeed;
use Illuminate\Http\Request;

class UserMembershipHistoryController extends Controller
{
    use HasActivityFeed;

    public function index(\App\DataTables\ActivityFeedDataTable $dataTable)
    {
        return $dataTable->render('dashboard.user_membership_history.index');
    }

    public function show($id)
    {
        $history = UserMembershipHistory::with(['user', 'membership'])->findOrFail($id);
        return view('dashboard.user_membership_history.show', compact('history'));
    }
}
