<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\TenderPaymentDataTable;
use Illuminate\Http\Request;

class TenderPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TenderPaymentDataTable $dataTable)
    {
        return $dataTable->render('dashboard.tender_payments.index');
    }
}
