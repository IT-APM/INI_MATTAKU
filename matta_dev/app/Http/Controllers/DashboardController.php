<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Karyawan;
use DB;
use App\Models\Perusahaan;
use App\Models\Divisi;
use App\Models\Departemen;
use App\Models\SubDepartemen;
use App\Models\ToDoList;
use App\Models\TrxPayrollLog;
use App\Models\TrxPayrollBpjstkLog;
use App\Models\TrxPayrollBpjskesLog;
use App\Models\KaryawanStatus;
use App\Models\TrxSurveyPora;

class DashboardController extends Controller
{
    public function survey_pora(Request $request, $id)
    {
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        // dd($request->all(), $id);
        // http://localhost/matta/survey_pora/result/0
        $trx = TrxSurveyPora::all();
        $n_futsal = 0;
        $n_voli = 0;
        $n_basket = 0;
        $n_badminton = 0;
        $n_tenis = 0;
        $n_renang = 0;
        $n_ml = 0;
        $n_fifa = 0;
        foreach($trx as $data){
            $opsi1 = json_decode($data['opsi1'], true) ?? [];
            $opsi2 = json_decode($data['opsi2'], true) ?? [];
            $opsi3 = json_decode($data['opsi3'], true) ?? [];
            $n_futsal += count(array_filter($opsi1, fn($item) => $item === 'futsal'));
            $n_voli += count(array_filter($opsi1, fn($item) => $item === 'voli'));
            $n_basket += count(array_filter($opsi1, fn($item) => $item === 'basket'));
            $n_badminton += count(array_filter($opsi2, fn($item) => $item === 'badminton'));
            $n_tenis += count(array_filter($opsi2, fn($item) => $item === 'tenis'));
            $n_renang += count(array_filter($opsi2, fn($item) => $item === 'renang'));
            $n_ml += count(array_filter($opsi3, fn($item) => $item === 'ml'));
            $n_fifa += count(array_filter($opsi3, fn($item) => $item === 'fifa'));
        }
        $y_max = max([$n_futsal, $n_voli, $n_basket, $n_badminton, $n_tenis, $n_renang, $n_ml, $n_fifa]) + 5;
        $result = [
            'success'=>true,
            ['name'=>'Cabor GROUP', 'opsi'=> [['name'=>'Futsal', 'n'=>$n_futsal], ['name'=>'Voli', 'n'=>$n_voli], ['name'=>'Basket', 'n'=>$n_basket]]],
            ['name'=>'Cabor INDIVIDU / DUO', 'opsi'=> [['name'=>'Badminton', 'n'=>$n_badminton], ['name'=>'Tenis Meja', 'n'=>$n_tenis], ['name'=>'Renang', 'n'=>$n_renang]]],
            ['name'=>'Cabor E-SPORT', 'opsi'=> [['name'=>'Mobile Legend', 'n'=>$n_ml], ['name'=>'FIFA 26', 'n'=>$n_fifa]]],
        ];
        // return response()->json([
        //     'success'=>true,
        //     ['name'=>'Cabor GROUP', 'opsi'=> [['name'=>'Futsal', 'n'=>$n_futsal], ['name'=>'Voli', 'n'=>$n_voli], ['name'=>'Basket', 'n'=>$n_basket]]],
        //     ['name'=>'Cabor INDIVIDU / DUO', 'opsi'=> [['name'=>'Badminton', 'n'=>$n_badminton], ['name'=>'Tenis Meja', 'n'=>$n_tenis], ['name'=>'Renang', 'n'=>$n_renang]]],
        //     ['name'=>'Cabor E-SPORT', 'opsi'=> [['name'=>'Mobile Legend', 'n'=>$n_ml], ['name'=>'FIFA 26', 'n'=>$n_fifa]]],
        //     // 'Cabor GROUP'=>['n_futsal'=>$n_futsal,'n_voli'=>$n_voli,'n_basket'=>$n_basket],
        //     // 'Cabor INDIVIDU / DUO'=>['n_badminton'=>$n_badminton,'n_tenis'=>$n_tenis,'n_renang'=>$n_renang],
        //     // 'Cabor E-SPORT'=>['n_ml'=>$n_ml,'n_fifa'=>$n_fifa],
        // ]);
        return view( 'dashboard.survey_pora', compact('result', 'y_max'));
    }

    public function get_divisi(Request $request, $id){
        $perusahaan = explode(',', $request->perusahaan);
        $arr_perusahaan = [];
        foreach($perusahaan as $_perusahaan){
            array_push($arr_perusahaan, base64_decode($_perusahaan));
        }
        $_data = TrxPayrollLog::select('divisi')->whereIn('perusahaan', $arr_perusahaan)->orderBy('divisi', 'asc')->groupBy('divisi')->get();
        $data = [];
        foreach($_data as $__data){
            array_push($data, ['id'=>base64_encode($__data->divisi), 'nama'=>$__data->divisi]);
        }
        return json_encode($data, true);
    }

    public function get_departemen(Request $request, $id){
        $perusahaan = explode(',', $request->perusahaan);
        $arr_perusahaan = [];
        foreach($perusahaan as $_perusahaan){
            array_push($arr_perusahaan, base64_decode($_perusahaan));
        }
        $divisi = explode(',', $request->divisi);
        $arr_divisi = [];
        foreach($divisi as $_divisi){
            array_push($arr_divisi, base64_decode($_divisi));
        }
        $_data = TrxPayrollLog::select('departemen')->whereIn('perusahaan', $arr_perusahaan)->whereIn('divisi', $arr_divisi)->orderBy('departemen', 'asc')->groupBy('departemen')->get();
        $data = [];
        foreach($_data as $__data){
            array_push($data, ['id'=>base64_encode($__data->departemen), 'nama'=>$__data->departemen]);
        }
        return json_encode($data, true);
    }

    public function get_subdepartemen(Request $request, $id){
        $perusahaan = explode(',', $request->perusahaan);
        $arr_perusahaan = [];
        foreach($perusahaan as $_perusahaan){
            array_push($arr_perusahaan, base64_decode($_perusahaan));
        }
        $divisi = explode(',', $request->divisi);
        $arr_divisi = [];
        foreach($divisi as $_divisi){
            array_push($arr_divisi, base64_decode($_divisi));
        }
        $departemen = explode(',', $request->departemen);
        $arr_departemen = [];
        foreach($departemen as $_departemen){
            array_push($arr_departemen, base64_decode($_departemen));
        }
        $_data = TrxPayrollLog::select('subdepartemen')->whereIn('perusahaan', $arr_perusahaan)->whereIn('divisi', $arr_divisi)->whereIn('departemen', $arr_departemen)->orderBy('subdepartemen', 'asc')->groupBy('subdepartemen')->get();
        $data = [];
        foreach($_data as $__data){
            array_push($data, ['id'=>base64_encode($__data->subdepartemen), 'nama'=>$__data->subdepartemen]);
        }
        return json_encode($data, true);
    }

    public function biaya_lain(Request $request)
    {
        // dd($request->all());
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $tahun = TrxPayrollLog::distinct()->orderBy('tahun', 'asc')->get(['tahun'])->toArray();
        $bulan = [['bulan'=>'Januari','angka'=>1],['bulan'=>'Februari','angka'=>2],['bulan'=>'Maret','angka'=>3],['bulan'=>'April','angka'=>4],['bulan'=>'Mei','angka'=>5],['bulan'=>'Juni','angka'=>6],['bulan'=>'Juli','angka'=>7],['bulan'=>'Agustus','angka'=>8],['bulan'=>'September','angka'=>9],['bulan'=>'Oktober','angka'=>10],['bulan'=>'November','angka'=>11],['bulan'=>'Desember','angka'=>12]];
        // 
        $status_karyawan = TrxPayrollLog::select('status_karyawan')->orderBy('status_karyawan', 'asc')->groupBy('status_karyawan')->get();
        $perusahaan = [];
        $data = TrxPayrollLog::select('perusahaan')->orderBy('perusahaan', 'asc')->groupBy('perusahaan')->get();
        foreach($data as $_data){
            array_push($perusahaan, ['id'=>base64_encode($_data->perusahaan), 'nama'=>$_data->perusahaan]);
        }
        $divisi = [];
        $departemen = [];
        $subdepartemen = [];
        //
        $bulan_tahun_dari = '';
        $bulan_tahun_sampai = '';
        // 
        $arr_perusahaan = [];
        $arr_divisi = [];
        $arr_departemen = [];
        $arr_subdepartemen = [];
        $arr_tahun = [];
        $arr_bulan = [];
        $arr_status_karyawan = [];
        // 
        $payroll = [];
        // start filter
        if ($request->filter == true) {
            $bulan_tahun_dari = $request->bulan_tahun_dari;
            $bulan_tahun_sampai = $request->bulan_tahun_sampai;
            $_tahun = $request->input('tahun', []);
            $_bulan = $request->input('bulan', []);
            $_status_karyawan = $request->input('status_karyawan', []);
            $_perusahaan = [];
            foreach($request->input('perusahaan', []) as $_data){
                array_push($_perusahaan, base64_decode($_data));
            }
            $_divisi = [];
            foreach($request->input('divisi', []) as $_data){
                array_push($_divisi, base64_decode($_data));
            }
            $_departemen = [];
            foreach($request->input('departemen', []) as $_data){
                array_push($_departemen, base64_decode($_data));
            }
            $_subdepartemen = [];
            foreach($request->input('subdepartemen', []) as $_data){
                array_push($_subdepartemen, base64_decode($_data));
            }
            if (count($_perusahaan) > 0) {
                $arr_perusahaan = $_perusahaan;
            }
            if (count($_divisi) > 0) {
                $arr_divisi = $_divisi;
            }
            if (count($_departemen) > 0) {
                $arr_departemen = $_departemen;
            }
            if (count($_subdepartemen) > 0) {
                $arr_subdepartemen = $_subdepartemen;
            }
            if (count($_tahun) > 0) {
                $arr_tahun = $_tahun;
            }
            if (count($_bulan) > 0) {
                $arr_bulan = $_bulan;
            }
            if (count($_status_karyawan) > 0) {
                $arr_status_karyawan = $_status_karyawan;
            }
            // 
            $data = TrxPayrollLog::select('divisi')->whereIn('perusahaan', $arr_perusahaan)->orderBy('divisi', 'asc')->groupBy('divisi')->get();
            foreach($data as $_data){
                array_push($divisi, ['id'=>base64_encode($_data->divisi), 'nama'=>$_data->divisi]);
            }
            $data = TrxPayrollLog::select('departemen')->whereIn('perusahaan', $arr_perusahaan)->whereIn('divisi', $arr_divisi)->orderBy('departemen', 'asc')->groupBy('departemen')->get();
            foreach($data as $_data){
                array_push($departemen, ['id'=>base64_encode($_data->departemen), 'nama'=>$_data->departemen]);
            }
            $data = TrxPayrollLog::select('subdepartemen')->whereIn('perusahaan', $arr_perusahaan)->whereIn('divisi', $arr_divisi)->whereIn('departemen', $arr_departemen)->orderBy('subdepartemen', 'asc')->groupBy('subdepartemen')->get();
            foreach($data as $_data){
                array_push($subdepartemen, ['id'=>base64_encode($_data->subdepartemen), 'nama'=>$_data->subdepartemen]);
            }
            // 
            $payroll = TrxPayrollLog::whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
            // whereIn('tahun', $arr_tahun)
            // ->whereIn('bulan', $arr_bulan)
            ->whereIn('status_karyawan', $arr_status_karyawan)
            ->whereIn('perusahaan', $arr_perusahaan)
            ->whereIn('divisi', $arr_divisi)
            ->whereIn('departemen', $arr_departemen)
            ->whereIn('subdepartemen', $arr_subdepartemen)
            ->orderBy('tahun', 'asc')->orderBy('bulan', 'asc')->get();
            if(Gate::allows('hc_nkp')){
                $payroll = TrxPayrollLog::whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
                // whereIn('tahun', $arr_tahun)
                // ->whereIn('bulan', $arr_bulan)
                ->whereIn('nik',$arr_nik_nkp)
                ->whereIn('status_karyawan', $arr_status_karyawan)
                ->whereIn('perusahaan', $arr_perusahaan)
                ->whereIn('divisi', $arr_divisi)
                ->whereIn('departemen', $arr_departemen)
                ->whereIn('subdepartemen', $arr_subdepartemen)
                ->orderBy('tahun', 'asc')->orderBy('bulan', 'asc')->get();
            }
        }
        // end filter
        return view( 'dashboard.biaya_lain', compact('payroll', 'perusahaan', 'divisi', 'departemen', 'subdepartemen', 'tahun', 'bulan', 'status_karyawan', 'arr_perusahaan', 'arr_divisi', 'arr_departemen', 'arr_subdepartemen', 'arr_tahun', 'arr_bulan', 'arr_status_karyawan', 'bulan_tahun_dari', 'bulan_tahun_sampai'));
    }

    public function biaya(Request $request)
    {
        // dd($request->all());
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $tahun = TrxPayrollLog::distinct()->orderBy('tahun', 'asc')->get(['tahun'])->toArray();
        $bulan = [['bulan'=>'Januari','angka'=>1],['bulan'=>'Februari','angka'=>2],['bulan'=>'Maret','angka'=>3],['bulan'=>'April','angka'=>4],['bulan'=>'Mei','angka'=>5],['bulan'=>'Juni','angka'=>6],['bulan'=>'Juli','angka'=>7],['bulan'=>'Agustus','angka'=>8],['bulan'=>'September','angka'=>9],['bulan'=>'Oktober','angka'=>10],['bulan'=>'November','angka'=>11],['bulan'=>'Desember','angka'=>12]];
        // 
        $status_karyawan = TrxPayrollLog::select('status_karyawan')->orderBy('status_karyawan', 'asc')->groupBy('status_karyawan')->get();
        $perusahaan = [];
        $data = TrxPayrollLog::select('perusahaan')->orderBy('perusahaan', 'asc')->groupBy('perusahaan')->get();
        foreach($data as $_data){
            array_push($perusahaan, ['id'=>base64_encode($_data->perusahaan), 'nama'=>$_data->perusahaan]);
        }
        $divisi = [];
        $departemen = [];
        $subdepartemen = [];
        //
        $bulan_tahun_dari = '';
        $bulan_tahun_sampai = '';
        // 
        $arr_perusahaan = [];
        $arr_divisi = [];
        $arr_departemen = [];
        $arr_subdepartemen = [];
        $arr_tahun = [];
        $arr_bulan = [];
        $arr_status_karyawan = [];
        // 
        $payroll = [];
        // start filter
        if ($request->filter == true) {
            $bulan_tahun_dari = $request->bulan_tahun_dari;
            $bulan_tahun_sampai = $request->bulan_tahun_sampai;
            $_tahun = $request->input('tahun', []);
            $_bulan = $request->input('bulan', []);
            $_status_karyawan = $request->input('status_karyawan', []);
            $_perusahaan = [];
            foreach($request->input('perusahaan', []) as $_data){
                array_push($_perusahaan, base64_decode($_data));
            }
            $_divisi = [];
            foreach($request->input('divisi', []) as $_data){
                array_push($_divisi, base64_decode($_data));
            }
            $_departemen = [];
            foreach($request->input('departemen', []) as $_data){
                array_push($_departemen, base64_decode($_data));
            }
            $_subdepartemen = [];
            foreach($request->input('subdepartemen', []) as $_data){
                array_push($_subdepartemen, base64_decode($_data));
            }
            if (count($_perusahaan) > 0) {
                $arr_perusahaan = $_perusahaan;
            }
            if (count($_divisi) > 0) {
                $arr_divisi = $_divisi;
            }
            if (count($_departemen) > 0) {
                $arr_departemen = $_departemen;
            }
            if (count($_subdepartemen) > 0) {
                $arr_subdepartemen = $_subdepartemen;
            }
            if (count($_tahun) > 0) {
                $arr_tahun = $_tahun;
            }
            if (count($_bulan) > 0) {
                $arr_bulan = $_bulan;
            }
            if (count($_status_karyawan) > 0) {
                $arr_status_karyawan = $_status_karyawan;
            }
            // 
            $data = TrxPayrollLog::select('divisi')->whereIn('perusahaan', $arr_perusahaan)->orderBy('divisi', 'asc')->groupBy('divisi')->get();
            foreach($data as $_data){
                array_push($divisi, ['id'=>base64_encode($_data->divisi), 'nama'=>$_data->divisi]);
            }
            $data = TrxPayrollLog::select('departemen')->whereIn('perusahaan', $arr_perusahaan)->whereIn('divisi', $arr_divisi)->orderBy('departemen', 'asc')->groupBy('departemen')->get();
            foreach($data as $_data){
                array_push($departemen, ['id'=>base64_encode($_data->departemen), 'nama'=>$_data->departemen]);
            }
            $data = TrxPayrollLog::select('subdepartemen')->whereIn('perusahaan', $arr_perusahaan)->whereIn('divisi', $arr_divisi)->whereIn('departemen', $arr_departemen)->orderBy('subdepartemen', 'asc')->groupBy('subdepartemen')->get();
            foreach($data as $_data){
                array_push($subdepartemen, ['id'=>base64_encode($_data->subdepartemen), 'nama'=>$_data->subdepartemen]);
            }
            // 
            $payroll = TrxPayrollLog::whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
            // whereIn('tahun', $arr_tahun)
            // ->whereIn('bulan', $arr_bulan)
            ->whereIn('status_karyawan', $arr_status_karyawan)
            ->whereIn('perusahaan', $arr_perusahaan)
            ->whereIn('divisi', $arr_divisi)
            ->whereIn('departemen', $arr_departemen)
            ->whereIn('subdepartemen', $arr_subdepartemen)
            ->orderBy('tahun', 'asc')->orderBy('bulan', 'asc')->get();
            if(Gate::allows('hc_nkp')){
                $payroll = TrxPayrollLog::whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
                // whereIn('tahun', $arr_tahun)
                // ->whereIn('bulan', $arr_bulan)
                ->whereIn('nik',$arr_nik_nkp)
                ->whereIn('status_karyawan', $arr_status_karyawan)
                ->whereIn('perusahaan', $arr_perusahaan)
                ->whereIn('divisi', $arr_divisi)
                ->whereIn('departemen', $arr_departemen)
                ->whereIn('subdepartemen', $arr_subdepartemen)
                ->orderBy('tahun', 'asc')->orderBy('bulan', 'asc')->get();
            }
        }
        // end filter
        return view( 'dashboard.biaya', compact('payroll', 'perusahaan', 'divisi', 'departemen', 'subdepartemen', 'tahun', 'bulan', 'status_karyawan', 'arr_perusahaan', 'arr_divisi', 'arr_departemen', 'arr_subdepartemen', 'arr_tahun', 'arr_bulan', 'arr_status_karyawan', 'bulan_tahun_dari', 'bulan_tahun_sampai'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perusahaan = DB::select("SELECT b.id AS id,b.nama AS nama FROM mtr_tbl_karyawan AS a LEFT JOIN mtr_tbl_perusahaan AS b ON b.id=a.kode_perusahaan GROUP BY b.id ORDER BY b.nama");;
        //
        // $data_status = DB::select("SELECT b.status_karyawan AS _status,COUNT(a.kode_status) AS jumlah,SUM(COUNT(a.kode_status)) OVER() AS total FROM mtr_tbl_karyawan AS a LEFT JOIN mtr_tbl_status_karyawan AS b ON b.id=a.kode_status WHERE a.is_aktif=1 AND a.nik NOT IN ('IT.BM','Hco.all') GROUP BY a.kode_status");
        // $status = array();
        // $jumlah_status = array();
        // foreach($data_status as $_data){
        //     array_push($status, $_data->_status);
        //     array_push($jumlah_status, round($_data->jumlah / $_data->total * 100, 2));
        // }
        // //
        // $data_perusahaan = DB::select("SELECT b.nama AS perusahaan,COUNT(a.kode_perusahaan) AS jumlah,SUM(COUNT(a.kode_perusahaan)) OVER() AS total FROM mtr_tbl_karyawan AS a LEFT JOIN mtr_tbl_perusahaan AS b ON b.id=a.kode_perusahaan WHERE a.is_aktif=1 AND a.nik NOT IN ('IT.BM','Hco.all') GROUP BY a.kode_perusahaan");
        // $perusahaan = array();
        // $jumlah_perusahaan = array();
        // foreach($data_perusahaan as $_data){
        //     array_push($perusahaan, $_data->perusahaan);
        //     array_push($jumlah_perusahaan, round($_data->jumlah / $_data->total * 100, 2));
        // }
        //
        $data_kelamin = DB::select("SELECT b.kelamin AS kelamin,COUNT(a.kode_kelamin) AS jumlah,SUM(COUNT(a.kode_kelamin)) OVER() AS total FROM mtr_tbl_karyawan AS a LEFT JOIN mtr_tbl_kelamin AS b ON b.id=a.kode_kelamin WHERE a.is_aktif=1 AND a.nik NOT IN ('IT.BM','Hco.all') GROUP BY a.kode_kelamin");
        // dd($data_kelamin);
        $kelamin = array();
        $jumlah_kelamin = array();
        $total_kelamin = 0;
        foreach($data_kelamin as $_data){
            if(!empty($_data->kelamin)){
                array_push($kelamin, $_data->kelamin);
                array_push($jumlah_kelamin, $_data->jumlah);
                $total_kelamin += $_data->jumlah;
            }
        }
        // //
        // $data_level = DB::select("SELECT b.level AS level,COUNT(a.kode_level) AS jumlah,SUM(COUNT(a.kode_level)) OVER() AS total FROM mtr_tbl_karyawan AS a LEFT JOIN mtr_tbl_level AS b ON b.id=a.kode_level WHERE a.is_aktif=1 AND a.nik NOT IN ('IT.BM','Hco.all')GROUP BY a.kode_level");
        // $level = array();
        // $jumlah_level = array();
        // foreach($data_level as $_data){
        //     array_push($level, $_data->level);
        //     array_push($jumlah_level, round($_data->jumlah / $_data->total * 100, 2));
        // }
        // return view( 'dashboard.index', compact( 'status', 'jumlah_status','perusahaan', 'jumlah_perusahaan','kelamin', 'jumlah_kelamin','level', 'jumlah_level' ) );
        $data = DB::select("SELECT COUNT(a.nik) AS jumlah, b.nama_singkat AS nama FROM mtr_tbl_karyawan AS a LEFT JOIN mtr_tbl_perusahaan AS b ON a.kode_perusahaan=b.id WHERE a.is_aktif=1 AND a.nik NOT IN ('IT.BM','Hco.all') GROUP BY a.kode_perusahaan");
        $perusahaan_aktif = array();
        $karyawan_aktif = array();
        $total_aktif = 0;
        foreach($data as $_data){
            array_push($perusahaan_aktif, $_data->nama);
            array_push($karyawan_aktif, $_data->jumlah);
            $total_aktif += $_data->jumlah;
        }
        // 
        $data = DB::select("SELECT COUNT(a.nik) AS jumlah, b.nama_singkat AS nama FROM mtr_tbl_karyawan AS a LEFT JOIN mtr_tbl_perusahaan AS b ON a.kode_perusahaan=b.id WHERE a.is_aktif=0 AND a.nik NOT IN ('IT.BM','Hco.all') GROUP BY a.kode_perusahaan");
        $perusahaan_nonaktif = array();
        $karyawan_nonaktif = array();
        $total_nonaktif = 0;
        foreach($data as $_data){
            array_push($perusahaan_nonaktif, $_data->nama);
            array_push($karyawan_nonaktif, $_data->jumlah);
            $total_nonaktif += $_data->jumlah;
        }
        // 
        $data = DB::select("SELECT COUNT(a.jenis) AS jumlah, a.tahun AS tahun FROM trx_tbl_pelatihan AS a GROUP BY a.tahun");
        $tahun_pelatihan = array();
        $jumlah_pelatihan = array();
        $total_pelatihan = 0;
        foreach($data as $_data){
            array_push($tahun_pelatihan, $_data->tahun);
            array_push($jumlah_pelatihan, $_data->jumlah);
            $total_pelatihan += $_data->jumlah;
        }
        // 
        $data = DB::select("SELECT COUNT(tanggal) AS jumlah, MONTHNAME(tanggal) AS bulan FROM trx_tbl_nonaktif_master WHERE YEAR(tanggal)=YEAR(NOW()) GROUP BY MONTH(tanggal)");
        $bulan_karyawannonaktif = array();
        $jumlah_karyawannonaktif = array();
        $total_karyawannonaktif = 0;
        foreach($data as $_data){
            array_push($bulan_karyawannonaktif, $_data->bulan);
            array_push($jumlah_karyawannonaktif, $_data->jumlah);
            $total_karyawannonaktif += $_data->jumlah;
        }
        // 
        $data = DB::select("SELECT COUNT(tanggal) AS jumlah, MONTHNAME(tanggal) AS bulan FROM trx_tbl_nonaktif_master WHERE YEAR(tanggal)=YEAR(NOW())-1 GROUP BY MONTH(tanggal)");
        $bulan_karyawannonaktif_1 = array();
        $jumlah_karyawannonaktif_1 = array();
        $total_karyawannonaktif_1 = 0;
        foreach($data as $_data){
            array_push($bulan_karyawannonaktif_1, $_data->bulan);
            array_push($jumlah_karyawannonaktif_1, $_data->jumlah);
            $total_karyawannonaktif_1 += $_data->jumlah;
        }
        return view( 'dashboard.index', compact( 'perusahaan', 'perusahaan_aktif', 'karyawan_aktif', 'total_aktif', 'perusahaan_nonaktif', 'karyawan_nonaktif', 'total_nonaktif', 'tahun_pelatihan', 'jumlah_pelatihan', 'total_pelatihan', 'kelamin', 'jumlah_kelamin', 'total_kelamin', 'bulan_karyawannonaktif', 'jumlah_karyawannonaktif','total_karyawannonaktif', 'bulan_karyawannonaktif_1', 'jumlah_karyawannonaktif_1','total_karyawannonaktif_1' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
