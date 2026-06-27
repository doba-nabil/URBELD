<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DashboardReportExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('dashboard.reports.export', [
            'data' => $this->data
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setRightToLeft(true);
        return [
            1    => ['font' => ['bold' => true, 'size' => 14]],
        ];
    }
}
