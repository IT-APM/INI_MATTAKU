<?php

namespace App\Exports;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class AttendanceExport implements FromCollection, WithHeadings, WithTitle
{
    private $trx;
    public function __construct($trx) 
    {
        $this->trx = $trx;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $trx = $this->trx;
        // dd($trx);
        // return Attendance::all();
        $arr = [];
        $no = 1;
        foreach($trx as $data){
            $map = '';
            if(!empty($data->att_long) && !empty($data->att_lat)){
                $map = 'https://www.google.com/maps/place/'.$data->att_lat.','.$data->att_long;
            }
            array_push($arr, ['No'=>$no, 'Tanggal'=>Carbon::parse($data->att_date)->format('d-m-Y'), 'Timestamp'=>Carbon::parse($data->att_timestamp)->format('d-m-Y H:i:s'), 'Nama'=>$data->_att_nik->nama, 'NIP'=>$data->att_nik, 'Perusahaan'=>$data->_att_nik->_perusahaan->nama, 'Direktorat'=>$data->_att_nik->_divisi->nama, 'Divisi'=>$data->_att_nik->_departemen->nama, 'Departemen'=>$data->_att_nik->_subdepartemen->nama, 'Working Group'=>$data->_att_nik->_wg->wg_name, 'Action'=>$data->att_type, 'Longitude'=>$data->att_long, 'Latitude'=>$data->att_lat, 'Address'=>$data->att_address, 'Map'=>$map, 'Status'=>$data->att_status, 'Type'=>$data->att_data_src]);
            $no++;
        }
        return new Collection($arr);
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Timestamp',
            'Nama',
            'NIP',
            'Perusahaan',
            'Direktorat',
            'Divisi',
            'Departemen',
            'Working Group',
            'Action',
            'Longitude',
            'Latitude',
            'Address',
            'Map',
            'Status',
            'Type',
        ];
    }

    public function title(): string
    {
        return 'Raw Data dan Duration';
    }
}
