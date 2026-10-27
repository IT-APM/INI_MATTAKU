<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AttendanceMultiSheetExport implements WithMultipleSheets
{
    private $trx;
    private $duration;
    private $recap;
    public function __construct($trx, $duration, $recap) 
    {
        $this->trx = $trx;
        $this->duration = $duration;
        $this->recap = $recap;
    }

    public function sheets(): array
    {
        $trx = $this->trx;
        $duration = $this->duration;
        $recap = $this->recap;
        return [
            new AttendanceRawDataExport($trx),
            new AttendanceDurationExport($duration),
            new AttendanceRecapExport($recap),
        ];
    }
}
