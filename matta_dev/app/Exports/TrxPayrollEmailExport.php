<?php

namespace App\Exports;

use App\Models\TrxPayrollEmail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class TrxPayrollEmailExport implements FromCollection, WithHeadings, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Surat Pemberitahuan Penyesuaian Upah
        $where = ['id_addsub'=>0];
        $trx = TrxPayrollEmail::where($where)->orderBy('id', 'asc')->get();
        $arr = [];
        array_push($arr, [
            '#ID',
            'Tahun',
            'Bulan',
            'Nama',
            'NIP',
            'Email',
            'Subyek Email',
            'Redaksi',
        ]);
        foreach ($trx as $data) {
            $nik_property = json_decode($data->nik_property, true);
            $email = json_decode($data->email, true);
            $arr_data = [
                $data->id,
                $data->tahun,
                $data->bulan,
                $nik_property['nama'],
                $data->nik,
                $nik_property['email'],
                $email['subject'],
                $data->param1,

            ];
            array_push($arr, $arr_data);
        }
        return new Collection($arr);
    }

    public function headings(): array
    {
        return ['1','2','3','4','5','6','7','8'];
    }

    public function title(): string
    {
        return 'Penyesuaian Upah';
    }
}
