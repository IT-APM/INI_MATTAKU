<?php

namespace App\Exports;

use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class AttendanceRecapExport implements FromCollection, WithHeadings, WithTitle
{
    private $recap;
    public function __construct($recap) 
    {
        $this->recap = $recap;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $recap = $this->recap;
        $arr = [];
        $no = 1;
        foreach($recap as $data){
            $where = ['nik' => $data['att_nik']];
            $karyawan = Karyawan::where($where)->first();
            array_push($arr, ['No'=>$no, 'Tanggal Awal'=>Carbon::parse($data['tanggal'][0])->format('d-m-Y'), 'Tanggal Akhir'=>Carbon::parse($data['tanggal'][1])->format('d-m-Y'), 'Working Group'=>$karyawan->_wg->wg_name, 'NIP'=>$data['att_nik'], 'Nama Karyawan'=>$karyawan->nama]);
            $no++;
        }
        return new Collection($arr);
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Awal',
            'Tanggal Akhir',
            'Working Group',
            'NIP',
            'Nama Karyawan',
            'Level',
            'Departemen',
            'Email Karyawan',
            'Nama Layer 1',
            'Email Layer 1',
            'Jumlah Hari Kerja',
            'Jumlah Kehadiran',
            'Persentase Kehadiran',
        ];
    }

    public function title(): string
    {
        return 'Recap';
    }
}
