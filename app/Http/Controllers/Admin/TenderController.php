<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tender;
use App\Jobs\SendTenderNotificationsJob;
use Illuminate\Support\Facades\DB;

class TenderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenders = Tender::with(['user', 'category', 'city'])->latest()->paginate(15);
        return view('dashboard.tenders.index', compact('tenders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Tender $tender)
    {
        $tender->load(['user', 'category', 'city', 'applications.user']);
        return view('dashboard.tenders.show', compact('tender'));
    }

    /**
     * Approve the tender and notify users.
     */
    public function approve(Request $request, Tender $tender)
    {
        if ($tender->status === Tender::STATUS_ACTIVE) {
            return back()->with('error', 'هذه المناقصة معتمدة مسبقاً.');
        }

        DB::beginTransaction();
        try {
            $tender->update(['status' => Tender::STATUS_ACTIVE]);

            // Dispatch notification job
            SendTenderNotificationsJob::dispatch($tender);

            DB::commit();
            return back()->with('success', 'تم اعتماد المناقصة بنجاح، وجاري إرسال الإشعارات للمستفيدين.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء الاعتماد: ' . $e->getMessage());
        }
    }

    /**
     * Reject the tender.
     */
    public function reject(Request $request, Tender $tender)
    {
        $tender->update([
            'status' => Tender::STATUS_CLOSED // or a specific REJECTED status if you have one
        ]);

        return back()->with('success', 'تم رفض المناقصة وإغلاقها.');
    }
}
