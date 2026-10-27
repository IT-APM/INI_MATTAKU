<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\TrxBpdMaster;
use App\Models\TrxBpdDetailBiaya;
use App\Models\TrxBpdDetailInfo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Models\Karyawan;

class TrxBpdMasterExportInfo implements FromCollection, WithHeadings, WithTitle
{
    private $pengaju;
    private $_perusahaan;
    private $_luar_dalam;
    private $_status_id;
    public function __construct(Request $request) 
    {
        $this->pengaju = $request->pengaju;
        $this->_perusahaan = $request->input('perusahaan', []);
        $this->_luar_dalam = $request->input('luar_dalam', []);
        $this->_status_id = $request->input('status_id', []);
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // $trx = TrxBpdMaster::orderBy('id', 'desc')->get();
        $pengaju = $this->pengaju;
        $_perusahaan = $this->_perusahaan;
        $_luar_dalam = $this->_luar_dalam;
        $_status_id = $this->_status_id;
        if(count($_perusahaan) > 0){
            $arr_perusahaan = $_perusahaan;
        }
        else{
            $arr_perusahaan = array();
        }
        if(count($_luar_dalam) > 0){
            $arr_luar_dalam = $_luar_dalam;
        }
        else{
            $arr_luar_dalam = array();
        }
        if(count($_status_id) > 0){
            $arr_status_id = $_status_id;
        }
        else{
            $arr_status_id = array();
        }
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $_pengaju = Karyawan::select('nik')->where('nama','LIKE','%'.$pengaju.'%')->get();
        $nik_karyawan = Karyawan::select('nik')->whereIn('kode_perusahaan', $arr_perusahaan)->get()->toArray();
        $trx = TrxBpdMaster::whereIn('pengaju', $nik_karyawan)->whereIn('pengaju', $_pengaju)->whereIn('luar_dalam', $arr_luar_dalam)->whereIn('status_id', $arr_status_id)->orderBy('id', 'desc')->get();
        if(Gate::allows('hc_nkp')){
            $trx = TrxBpdMaster::whereIn('pengaju',$arr_nik_nkp)->whereIn('pengaju', $nik_karyawan)->whereIn('pengaju', $_pengaju)->whereIn('luar_dalam', $arr_luar_dalam)->whereIn('status_id', $arr_status_id)->orderBy('id', 'desc')->get();
        }
        // 
        $arr = array();
        foreach ($trx as $data) {
            $pengaju_property = json_decode($data->pengaju_property, true);
            $admin_property = json_decode($data->admin_property, true);
            $atasan_property = json_decode($data->atasan_property, true);
            $koordinator_property = json_decode($data->koordinator_property, true);
            $layer1_property = json_decode($data->layer1_property, true);
            $layer2_property = json_decode($data->layer2_property, true);
            $hco_property = json_decode($data->hco_property, true);
            $admin = '';
            if(!is_null($admin_property)){
                $admin = $admin_property['nama'].' / '.$admin_property['nik'];
            }
            $koordinator = '';
            if(!is_null($koordinator_property)){
                $koordinator = $koordinator_property['nama'].' / '.$koordinator_property['nik'];
            }
            $layer1 = '';
            if(!is_null($layer1_property)){
                $layer1 = $layer1_property['nama'].' / '.$layer1_property['nik'];
            }
            $layer2 = '';
            if(!is_null($layer2_property)){
                $layer2 = $layer2_property['nama'].' / '.$layer2_property['nik'];
            }
            $hco = '';
            if(!is_null($hco_property)){
                $hco = $hco_property['nama'].' / '.$hco_property['nik'];
            }
            $luar_dalam = '';
            if ($data->is_ca == 1 && $data->luar_dalam == 'ca_dalam'){
                $luar_dalam = 'CA Dalam Negeri';
            }elseif ($data->is_ca == 1 && $data->luar_dalam == 'ca_luar'){
                $luar_dalam = 'CA Luar Negeri';
            }elseif ($data->is_ca == 0 && $data->luar_dalam == 'reimburse'){
                $luar_dalam = 'Reimbursement';
            }
            $is_leader = '';
            if ($data->is_leader == 1){
                $is_leader = 'Leader';
            }elseif($data->is_leader == 2){
                $is_leader = 'Member';
            }elseif($data->is_leader == 3){
                $is_leader = 'Management';
            }
            $kelompok = '';
            if(!is_null($data->kelompok_id)){
                $kelompok = $data->_kelompok->kelompok;
            }
            $is_project = '';
            if(!is_null($data->is_project)){
                if ($data->is_project){
                    $is_project = 'Project';
                }else{
                    $is_project = 'Corporate';
                }
            }
            $project_id = '';
            if(!is_null($data->project_id)){
                $project_id = $data->_project->project_id.' / '.$data->_project->name.' / '.$data->_project->group;
            }
            $arr_data = [
                $data->id,
                $pengaju_property['nama'].' / '.$pengaju_property['nik'],
                $atasan_property['nama'].' / '.$atasan_property['nik'],
                $admin,
                $koordinator,
                $layer1,
                $layer2,
                $hco,
                $pengaju_property['perusahaan'],
                $pengaju_property['divisi'],
                $pengaju_property['dep'],
                $pengaju_property['sub_dep'],
                $pengaju_property['level'],
                $pengaju_property['jabatan'],
                $luar_dalam,
                $data->periode,
                $is_leader,
                $kelompok,
                $is_project,
                $project_id,
                $data->uraian,
                $data->_status->status,
                $data->created_at,
            ];
            $where = ['id_trx' => $data->id];
            $trxInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
            if(empty($trxInfo)){
                array_push($arr, $arr_data);
            }else{
                foreach ($trxInfo as $data_info) {
                    $arrInfo = $arr_data;
                    $tujuan = '';
                    if($data_info->luar_dalam=='ca_dalam' || $data_info->luar_dalam=='reimburse'){
                        $tujuan = $data_info->_tujuan1_dalam->propinsi.' - '.$data_info->_tujuan2_dalam->kota;
                    }else{
                        $tujuan = $data_info->_tujuan1_luar->negara;
                    }
                    $uang_saku = '';
                    if(!is_null($data_info->is_approve)){
                        if ($data_info->is_approve){
                            $uang_saku = 'Ada';
                        }else{
                            $uang_saku = 'Tidak Ada';
                        }
                    }
                    array_push($arrInfo, $data_info->jenis, $tujuan, \Carbon\Carbon::parse($data_info->tanggal_mulai)->format('d-m-Y'), \Carbon\Carbon::parse($data_info->tanggal_akhir)->format('d-m-Y'), \Carbon\Carbon::parse($data_info->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($data_info->tanggal_akhir))+1, $uang_saku);
                    array_push($arr, $arrInfo);
                }
            }
        }
        // return $trx;
        return new Collection($arr);
    }

    public function headings(): array
    {
        return [
            '#ID',
            'Nama / NIP',
            'Atasan / NIP',
            'Admin / NIP',
            'Project Coordinator / NIP',
            'Layer 1 / NIP',
            'layer 2 / NIP',
            'HCO / NIP',
            'Perusahaan',
            'Direktorat',
            'Divisi',
            'Departemen',
            'Level',
            'Jabatan',
            'Pengajuan',
            'Tahun',
            'Golongan',
            'Kelompok Perjalanan Dinas',
            'Jenis BPD',
            'Project ID',
            'Uraian Tugas Perjalanan Dinas',
            'Status Pengajuan',
            'Created at',
            'Tipe',
            'Tujuan',
            'Tanggal Dari',
            'Tanggal Sampai',
            'Jumlah Hari',
            'Uang Saku',
        ];
    }

    public function title(): string
    {
        return 'BPD Info';
    }
}
