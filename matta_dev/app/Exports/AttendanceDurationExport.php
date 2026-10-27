<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class AttendanceDurationExport implements FromCollection, WithHeadings, WithTitle
{
    private $duration;
    public function __construct($duration) 
    {
        $this->duration = $duration;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $duration = $this->duration;
        $arr = [];
        $no = 1;
        foreach($duration as $data){
            $att_property = json_decode($data['att_property'], true);
            array_push($arr, ['No'=>$no, 'Tanggal'=>Carbon::parse($data['att_date'])->format('d-m-Y'), 'Clock-in'=>Carbon::parse($data['Clock-in'])->format('d-m-Y H:i:s'), 'Clock-out'=>Carbon::parse($data['Clock-out'])->format('d-m-Y H:i:s'), 'Durasi'=>Carbon::parse(strtotime($data['Clock-out'])-strtotime($data['Clock-in']))->format('H:i:s'), 'Nama'=>$att_property['nama'], 'NIP'=>$data['att_nik'], 'Perusahaan'=>$att_property['perusahaan'], 'Direktorat'=>$att_property['divisi'], 'Divisi'=>$att_property['dep'], 'Departemen'=>$att_property['sub_dep'], 'Working Group'=>$att_property['wg_name'], 'Type'=>$data['att_data_src']]);
            $no++;
        }
        return new Collection($arr);
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Clock-in',
            'Clock-out',
            'Durasi',
            'Nama',
            'NIP',
            'Perusahaan',
            'Direktorat',
            'Divisi',
            'Departemen',
            'Working Group',
            'Type',
        ];
    }

    public function title(): string
    {
        return 'Duration';
    }
}
