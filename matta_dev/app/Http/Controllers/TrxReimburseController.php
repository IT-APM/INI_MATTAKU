<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
// 
use App\Models\Karyawan;
use App\Models\Perusahaan;
use App\Models\Divisi;
// 
use App\Models\ReimburseHubungan;
use App\Models\ReimburseLampiran;
use App\Models\ReimburseLayanan;
use App\Models\ReimbursePlafon;
use App\Models\ReimburseStatus;
use App\Models\TrxReimburseMaster;
use App\Models\TrxReimburseDetail;
use App\Models\TrxReimburseLampiran;
use App\Models\TrxReimburseLog;
// 
use App\Models\ToDoList;
use App\Models\ToDoListLog;
use App\Models\MtrUtility;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;
// 
use Carbon\Carbon;
use DB;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Support\Facades\Crypt;
// 

class TrxReimburseController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show','update']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // http://localhost/matta/reimburse?q=back
        // if($request->q == 'back'){
        //     // dd($request->all());
        //     set_time_limit(0);
        //     ini_set('max_execution_time', 0);
        //     // $trx = TrxReimburseMaster::all();
        //     $trx = TrxReimburseMaster::whereDate('created_at', '>=', '2026-09-01')->get();
        //     foreach($trx as $_trx){
        //         $trxM = TrxReimburseMaster::find($_trx->id);
        //         $karyawan_property = json_decode($_trx->karyawan_property, true);
        //         $arr = ['nik_karyawan'=>$_trx->nik_karyawan, 'created_at'=>Carbon::parse($_trx->created_at)->format('Y-m-d')];
        //         $ret = $this->getNikPropertyBackdate($arr);
        //         if($ret == 'null'){
        //             // dd('NOK', $ret, $trxM);
        //             $karyawan_property['status_karyawan'] = 0;
        //             $karyawan_property['findim_subdepartemen'] = 0;
        //             $karyawan_property['findim_departemen'] = 0;
        //             $karyawan_property['findim_divisi'] = 0;
        //             $karyawan_property['findim_project'] = 0;
        //             $trxM->karyawan_property = json_encode($karyawan_property);
        //             $trxM->update();
        //         }else{
        //             $ret = json_decode($ret, true);
        //             // dd('OK', $ret, $trxM, $ret['status_karyawan']);
        //             $karyawan_property['status_karyawan'] = $ret['status_karyawan'];
        //             $karyawan_property['findim_subdepartemen'] = $ret['findim_subdepartemen'];
        //             $karyawan_property['findim_departemen'] = $ret['findim_departemen'];
        //             $karyawan_property['findim_divisi'] = $ret['findim_divisi'];
        //             $karyawan_property['findim_project'] = $ret['findim_project'];
        //             $trxM->karyawan_property = json_encode($karyawan_property);
        //             $trxM->update();
        //         }
        //     }
        //     dd('done');
        // }
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        // 0=NA
        // 3=SMS Project
        $perusahaan = Perusahaan::whereNotIn('id', [0,3])->orderBy( 'nama', 'asc' )->get();
        $divisi = array();
        $nik_karyawan = ReimbursePlafon::pluck('nik_karyawan')->toArray();
        $no = 0;
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->whereNotIn('nik', ['Hco.all','IT.BM','IT Infras'])->orderBy('nama', 'asc')->get();
        // ambil semua data reimburse
        $trx = TrxReimburseMaster::orderBy('id', 'desc')->get();
        if(Gate::allows('hc_nkp')){
            $karyawan = Karyawan::select('nik', 'nama')->whereIn('nik',$arr_nik_nkp)->where($where)->whereNotIn('nik', ['Hco.all','IT.BM','IT Infras'])->orderBy('nama', 'asc')->get();
            $trx = TrxReimburseMaster::whereIn('nik_karyawan',$arr_nik_nkp)->orderBy('id', 'desc')->get();
        }
        // 
        $trxD = TrxReimburseDetail::whereIn('id_reimburse', $trx->pluck('id')->toArray())->get();
        $layanan = ReimburseLayanan::whereIn('id', $trxD->pluck('id_layanan')->toArray())->orderBy('layanan', 'asc')->get();
        // 
        $isPlafon = true;
        if(auth()->user()->userrole == 'nhc'){
            $where = ['nik_karyawan' => auth()->user()->nik];
            // $trx = TrxReimburseMaster::where($where)->orderBy('id', 'desc')->limit(100)->get();
            $trx = TrxReimburseMaster::where($where)->whereIn('nik_karyawan', $nik_karyawan)->orderBy('id', 'desc')->get();
            // 
            // ambil plafon karyawan tersebut
            $periode = Carbon::now()->format('Y');
            $where = ['periode' => $periode, 'nik_karyawan' => auth()->user()->nik];
            if(ReimbursePlafon::where($where)->orderBy('id', 'desc')->count() == 0){
                $isPlafon = false;
            }
            // 
            $trxD = TrxReimburseDetail::whereIn('id_reimburse', $trx->pluck('id')->toArray())->get();
            $layanan = ReimburseLayanan::whereIn('id', $trxD->pluck('id_layanan')->toArray())->orderBy('layanan', 'asc')->get();
            // 
        }
        // filter parameter
        $arr_status_pengajuan = array();
        $arr_tahun = array();
        $arr_layanan = array();
        $arr_nik_karyawan = array();
        $arr_perusahaan = array();
        $arr_divisi = array();
        foreach ($trx as $data){
            array_push($arr_status_pengajuan, $data->status_pengajuan);
            if(!in_array($data->periode, $arr_tahun)){
                array_push($arr_tahun, $data->periode);
            }
            array_push($arr_nik_karyawan, $data->nik_karyawan);
        }
        sort($arr_tahun);
        $status_pengajuan = ReimburseStatus::whereIn('id', $arr_status_pengajuan)->orderBy('id', 'asc')->get();
        $tahun = $arr_tahun;
        $nik_karyawan = Karyawan::whereIn('nik', $arr_nik_karyawan)->orderBy('nama', 'asc')->get();
        // start dibatasi 30 hari terakhir dari created_at
        $trx = $trx->filter(function ($item) {
            return Carbon::parse($item->created_at)->gte(Carbon::now()->subDays(30));
        });
        // end
        $bulan_tahun_dari = '';
        $bulan_tahun_sampai = '';
        // start filter
        if($request->filter == true){
            // dd($request->all());
            $_status_pengajuan = $request->input('status_pengajuan', []);
            $_tahun = $request->input('tahun', []);
            $_layanan = $request->input('layanan', []);
            $_nik_karyawan = $request->input('nik_karyawan', []);
            $_perusahaan = $request->input('kode_perusahaan', []);
            $_divisi = $request->input('kode_divisi', []);
            $bulan_tahun_dari = $request->bulan_tahun_dari;
            $bulan_tahun_sampai = $request->bulan_tahun_sampai;
            $tanggal_dari = Carbon::createFromFormat('Y-m', $bulan_tahun_dari)->startOfMonth()->startOfDay();
            $tanggal_sampai = Carbon::createFromFormat('Y-m', $bulan_tahun_sampai)->endOfMonth()->endOfDay();
            if(count($_status_pengajuan) > 0){
                $arr_status_pengajuan = $_status_pengajuan;
            }
            else{
                $arr_status_pengajuan = array();
            }
            if(count($_tahun) > 0){
                $arr_tahun = $_tahun;
            }
            else{
                $arr_tahun = array();
            }
            if(count($_layanan) > 0){
                $arr_layanan = $_layanan;
            }
            else{
                $arr_layanan = array();
            }
            if(count($_nik_karyawan) > 0){
                $arr_nik_karyawan = $_nik_karyawan;
            }
            // else{
            //     $arr_nik_karyawan = array();
            // }
            if (count($_perusahaan) > 0) {
                $arr_perusahaan = $_perusahaan;
            }
            else{
                $arr_perusahaan = array();
            }
            if (count($_divisi) > 0) {
                $arr_divisi = $_divisi;
            }
            else{
                $arr_divisi = array();
            }
            $divisi = Divisi::whereIn('kode_perusahaan', $arr_perusahaan)->get();
            $div_karyawan = Karyawan::whereIn('kode_divisi', $arr_divisi)->whereNotIn('nik', ['Hco.all','IT.BM','IT Infras'])->pluck('nik')->toArray();
            if(Gate::allows('hc_nkp')){
                $div_karyawan = Karyawan::whereIn('nik',$arr_nik_nkp)->whereIn('kode_divisi', $arr_divisi)->whereNotIn('nik', ['Hco.all','IT.BM','IT Infras'])->pluck('nik')->toArray();
            }
            // $trx = TrxReimburseMaster::whereIn('status_pengajuan', $arr_status_pengajuan)->whereIn('periode', $arr_tahun)->whereIn('nik_karyawan', $arr_nik_karyawan)->orderBy('id', 'desc')->get();
            $trx = TrxReimburseMaster::whereIn('nik_karyawan', $div_karyawan)->whereIn('status_pengajuan', $arr_status_pengajuan)->whereBetween('created_at', [$tanggal_dari, $tanggal_sampai])->whereIn('nik_karyawan', $arr_nik_karyawan)->orderBy('id', 'desc')->get();
            if(Gate::allows('hc_nkp')){
                // $trx = TrxReimburseMaster::whereIn('nik_karyawan',$arr_nik_nkp)->whereIn('status_pengajuan', $arr_status_pengajuan)->whereIn('periode', $arr_tahun)->whereIn('nik_karyawan', $arr_nik_karyawan)->orderBy('id', 'desc')->get();
                $trx = TrxReimburseMaster::whereIn('nik_karyawan', $div_karyawan)->whereIn('nik_karyawan',$arr_nik_nkp)->whereIn('status_pengajuan', $arr_status_pengajuan)->whereBetween('created_at', [$tanggal_dari, $tanggal_sampai])->whereIn('nik_karyawan', $arr_nik_karyawan)->orderBy('id', 'desc')->get();
            }
            $trxD = TrxReimburseDetail::whereIn('id_layanan', $arr_layanan)->whereIn('id_reimburse', $trx->pluck('id')->toArray())->get();
            $trx = TrxReimburseMaster::whereIn('id', $trxD->pluck('id_reimburse')->toArray())->orderBy('id', 'desc')->get();
            if(auth()->user()->userrole == 'nhc'){
                $where = ['nik_karyawan' => auth()->user()->nik];
                // $trx = TrxReimburseMaster::whereIn('status_pengajuan', $arr_status_pengajuan)->whereIn('periode', $arr_tahun)->whereIn('nik_karyawan', $arr_nik_karyawan)->where($where)->orderBy('id', 'desc')->get();
                $trx = TrxReimburseMaster::whereIn('status_pengajuan', $arr_status_pengajuan)->whereBetween('created_at', [$tanggal_dari, $tanggal_sampai])->whereIn('nik_karyawan', $arr_nik_karyawan)->where($where)->orderBy('id', 'desc')->get();
                // ambil plafon karyawan tersebut
                $periode = Carbon::now()->format('Y');
                $where = ['periode' => $periode, 'nik_karyawan' => auth()->user()->nik];
                if(ReimbursePlafon::where($where)->orderBy('id', 'desc')->count() == 0){
                    $isPlafon = false;
                }
                $trxD = TrxReimburseDetail::whereIn('id_layanan', $arr_layanan)->whereIn('id_reimburse', $trx->pluck('id')->toArray())->get();
                $trx = TrxReimburseMaster::whereIn('id', $trxD->pluck('id_reimburse')->toArray())->orderBy('id', 'desc')->get();
            }
        }else{
            $arr_status_pengajuan = array();
            $arr_tahun = array();
            $arr_layanan = array();
            $arr_nik_karyawan = array();
        }
        // end filter
        return view( 'formulir.reimburse.index', compact( 'trx', 'no', 'isPlafon', 'status_pengajuan', 'tahun', 'nik_karyawan', 'arr_status_pengajuan', 'arr_tahun', 'arr_nik_karyawan', 'karyawan', 'layanan', 'arr_layanan', 'bulan_tahun_dari', 'bulan_tahun_sampai', 'perusahaan', 'divisi', 'arr_perusahaan', 'arr_divisi'));
        // return view('formulir.reimburse.index', compact('trx', 'no', 'isPlafon'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // if(Carbon::now()->toDateString() >= '2024-12-17' && \Carbon\Carbon::now()->toDateString() <= '2025-01-02'){
        if(Carbon::now()->toDateString() >= '2025-03-17' && \Carbon\Carbon::now()->toDateString() <= '2025-04-08'){
            return redirect('reimburse')->with('msg', 'Tidak Tersedia');
        }
        // karyawan masih di hardcode
        // Abi Suhendra Adi Guna
        // $nik_karyawan = '01031801682';
        // $nik_karyawan = auth()->user()->nik;
        $nik_karyawan = Crypt::decrypt($request->nik_karyawan);
        $where = ['nik' => $nik_karyawan];
        $pengaju = Karyawan::select('nik', 'nama')->where($where)->first();
        $pengaju = $pengaju->nama.' / '.$pengaju->nik;
        $periode = Carbon::now()->format('Y');
        // 
        $where = ['nik' => $nik_karyawan];
        $karyawan = Karyawan::where($where)->first();
        // ambil plafon karyawan tersebut
        $where = ['periode' => $periode, 'nik_karyawan' => $nik_karyawan];
        $plafon = ReimbursePlafon::where($where)->orderBy('id', 'desc')->first();
        // jika tidak ada maka ambil default 0
        if(empty($plafon)){
            $plafon = ReimbursePlafon::find(1000);
        }
        // jumlahkan semua plafon kesehatan yang status tidak Ditolak di periode dan nik tersebut
        $where = ['periode' => $periode, 'nik_karyawan' => $nik_karyawan];
        $plafon_diambil = TrxReimburseMaster::where($where)->whereNotIn('status_pengajuan', [3])->sum('klaim_kesehatan');
        // 
        $plafon->kesehatan -= $plafon_diambil;
        // cek semua klaim non kesehatan
        // 2= Kacamata-Frame
        $cekData = $this->isKlaimNonKesehatan(0, $nik_karyawan, 2);
        if($cekData['cek']){
            $plafon->frame = 0;
        }
        // 3= Kacamata-Lensa
        $cekData = $this->isKlaimNonKesehatan(0, $nik_karyawan, 3);
        if($cekData['cek']){
            $plafon->lensa = 0;
        }
        // 4= Kehamilan
        $cekData = $this->isKlaimNonKesehatan($periode, $nik_karyawan, 4);
        if($cekData['cek']){
            $plafon->kehamilan = 0;
        }
        // 5= Persalinan-Normal
        $cekData = $this->isKlaimNonKesehatan($periode, $nik_karyawan, 5);
        if($cekData['cek']){
            $plafon->persalinan_normal = 0;
        }
        // 6= Persalinan-Caesar
        $cekData = $this->isKlaimNonKesehatan($periode, $nik_karyawan, 6);
        if($cekData['cek']){
            $plafon->persalinan_caesar = 0;
        }
        // 
        $hubungan = ReimburseHubungan::orderBy('id', 'asc')->get();
        $layanan = ReimburseLayanan::orderBy('layanan', 'asc')->get();
        $lampiran = ReimburseLampiran::orderBy('lampiran', 'asc')->get();
        if(auth()->user()->userrole == 'nhc'){
            $layanan = ReimburseLayanan::whereIn('id', [1,2,3,4,5,6])->orderBy('layanan', 'asc')->get();
            $lampiran = ReimburseLampiran::whereIn('id', [1,2,3,4,5,6,7,8,9])->orderBy('lampiran', 'asc')->get();
        }
        $layanan_istri = ReimburseLayanan::whereIn('id', [1,4,5,6])->orderBy('layanan', 'asc')->get();
        $layanan_anak = ReimburseLayanan::whereIn('id', [1])->orderBy('layanan', 'asc')->get();
        $max_date = Carbon::now()->toDateString();
        // jika tidak ada maka ambil default 0
        if($plafon->id == 1000){
            $layanan = ReimburseLayanan::whereIn('id', [1])->orderBy('layanan', 'asc')->get();
            $layanan_istri = $layanan;
            $layanan_anak = $layanan;
        }
        return view('formulir.reimburse.insert', compact('karyawan', 'plafon', 'hubungan', 'layanan', 'lampiran', 'layanan_istri', 'layanan_anak', 'max_date', 'pengaju', 'nik_karyawan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // return redirect()->back()->with('error','hello');
        // karyawan masih di hardcode
        // $nik_karyawan = '01031801682';
        // $nik_karyawan = auth()->user()->nik;
        $nik_karyawan = $request->nik_karyawan;
        $periode = Carbon::now()->format('Y');
        // agregate semua input array
        $hubungan = $request->input('hubungan', []);
        $nama_pasien = $request->input('nama_pasien', []);
        $layanan = $request->input('layanan', []);
        $tanggal = $request->input('tanggal', []);
        $jumlah_klaim = $request->input('jumlah_klaim', []);
        // 
        // $hubungan = array_values(array_filter([$request->_hubungan1, $request->_hubungan3, $request->_hubungan4, $request->_hubungan5, $request->_hubungan6]));
        // $nama_pasien = array_values(array_filter([$request->nama_pasien1, $request->nama_pasien3, $request->nama_pasien4, $request->nama_pasien5, $request->nama_pasien6]));
        // $layanan = array_values(array_filter([$request->layanan1, $request->layanan3, $request->layanan4, $request->layanan5, $request->layanan6]));
        // $tanggal = array_values(array_filter([$request->tanggal1, $request->tanggal3, $request->tanggal4, $request->tanggal5, $request->tanggal6]));
        // $jumlah_klaim = array_values(array_filter([$request->jumlah_klaim1, $request->jumlah_klaim3, $request->jumlah_klaim4, $request->jumlah_klaim5, $request->jumlah_klaim6]));
        // 
        $jumlah_klaim = str_replace( ',', '', $jumlah_klaim );
        // 
        $lampiran = $request->input('lampiran', []);
        $file_lampiran = $request->file('file_lampiran', []);
        // 
        $klaim_kesehatan = 0;
        $klaim_non_kesehatan = 0;
        $jumlah_klaim_frame = 0;
        $jumlah_klaim_lensa = 0;
        $jumlah_klaim_hamil = 0;
        $jumlah_klaim_salin_normal = 0;
        $jumlah_klaim_salin_caesar = 0;
        // jumlahkan semua layanan kesehatan ke klaim_kesehatan dan non kesehatan ke klaim_non_kesehatan
        for ($x = 0; $x < count($layanan); $x++) {
            // klaim_kesehatan
            if ($layanan[$x] == 1) {
                $klaim_kesehatan += $jumlah_klaim[$x];
            } 
            // klaim_non_kesehatan
            else {
                // jumlah_klaim_frame
                if($layanan[$x] == 2){
                    $jumlah_klaim_frame += $jumlah_klaim[$x];
                }
                // jumlah_klaim_lensa
                else if($layanan[$x] == 3){
                    $jumlah_klaim_lensa += $jumlah_klaim[$x];
                }
                // jumlah_klaim_hamil
                else if($layanan[$x] == 4){
                    $jumlah_klaim_hamil += $jumlah_klaim[$x];
                }
                // jumlah_klaim_salin_normal
                else if($layanan[$x] == 5){
                    $jumlah_klaim_salin_normal += $jumlah_klaim[$x];
                }
                // jumlah_klaim_salin_caesar
                else if($layanan[$x] == 6){
                    $jumlah_klaim_salin_caesar += $jumlah_klaim[$x];
                }
                // klaim_non_kesehatan
                $klaim_non_kesehatan += $jumlah_klaim[$x];
            }
        }
        // ambil plafon
        $where = ['periode' => $periode, 'nik_karyawan' => $nik_karyawan];
        $plafon = ReimbursePlafon::where($where)->orderBy('id', 'desc')->first();
        // jika tidak ada maka ambil default 0
        if(empty($plafon)){
            $plafon = ReimbursePlafon::find(1000);
        }
        // ambil semua klaim_kesehatan yang status tidak Ditolak di periode dan nik tersebut
        $where = ['periode' => $periode, 'nik_karyawan' => $nik_karyawan];
        $plafon_diambil = TrxReimburseMaster::where($where)->whereNotIn('status_pengajuan', [3])->sum('klaim_kesehatan');
        // trx_tbl_reimburse_master
        $trxM = new TrxReimburseMaster;
        $trxM->nik_karyawan = $nik_karyawan;
        $trxM->karyawan_property = $this->getNikProperty($nik_karyawan);
        $trxM->periode = $periode;
        $trxM->plafon = $plafon->kesehatan;
        $trxM->plafon_diambil = $plafon_diambil;
        $trxM->sisa_plafon_1 = $trxM->plafon - $trxM->plafon_diambil;
        $trxM->klaim_kesehatan = $klaim_kesehatan;
        $trxM->klaim_non_kesehatan = $klaim_non_kesehatan;
        $trxM->sisa_plafon_2 = $trxM->sisa_plafon_1 - $trxM->klaim_kesehatan;
        $trxM->status_pengajuan = 1;
        $trxM->catatan = $request->catatan;
        // cek apakah klaim_kesehatan lebih dari plafon kesehatan
        // hanya yang ada plafonnya
        $error = '<h4>ERROR:</h4>';
        if($plafon->id > 1000){
            if($klaim_kesehatan > $trxM->sisa_plafon_1){
                $error .= '<h5>Nominal klaim kesehatan melebihi plafon.
                <br/>Untuk informasi lebih lanjut dapat menghubungi '.MtrUtility::find(51)->informasi.'.
                <br/>Terima kasih.</h5>';
                return redirect()->back()->with('error', $error);
            }
        }
        // cek apakah klaim_non_kesehatan lebih dari plafonnya
        for ($x = 0; $x < count($layanan); $x++) {
            // 2 = Kacamata-Frame
            if($layanan[$x] == 2){
                $cekData = $this->isKlaimNonKesehatan(0, $nik_karyawan, $layanan[$x]);
                // bisa mengajukan kembali setelah 2 tahun dari tanggal pengajuan terakhir
                if($cekData['cek']){
                    $error .= '<h5>Klaim Kacamata-Frame sudah pernah diajukan di tanggal '.Carbon::parse($cekData['tanggal'])->format('d-m-Y').'.
                    <br/>Anda dapat mengajukan kembali dari tanggal '.Carbon::parse(strtotime("+2 years", strtotime($cekData['tanggal'])))->format('d-m-Y').'.
                    <br/>Untuk informasi lebih lanjut dapat menghubungi '.MtrUtility::find(51)->informasi.'.
                    <br/>Terima kasih.</h5>';
                    return redirect()->back()->with('error', $error);
                }else{
                    if($jumlah_klaim_frame > $plafon->frame){
                        $error .= '<h5>Nominal klaim Kacamata-Frame melebihi plafon.</h5>';
                        return redirect()->back()->with('error', $error);
                    }
                }
            }
            // 3 = Kacamata-Lensa
            else if($layanan[$x] == 3){
                $cekData = $this->isKlaimNonKesehatan(0, $nik_karyawan, $layanan[$x]);
                // bisa mengajukan kembali setelah 1 tahun dari tanggal pengajuan terakhir
                if($cekData['cek']){
                    $error .= '<h5>Klaim Kacamata-Lensa sudah pernah diajukan di tanggal '.Carbon::parse($cekData['tanggal'])->format('d-m-Y').'.
                    <br/>Anda dapat mengajukan kembali dari tanggal '.Carbon::parse(strtotime("+1 years", strtotime($cekData['tanggal'])))->format('d-m-Y').'.
                    <br/>Untuk informasi lebih lanjut dapat menghubungi '.MtrUtility::find(51)->informasi.'.
                    <br/>Terima kasih.</h5>';
                    return redirect()->back()->with('error', $error);
                }else{
                    if($jumlah_klaim_lensa > $plafon->lensa){
                        $error .= '<h5>Nominal klaim Kacamata-Lensa melebihi plafon.</h5>';
                        return redirect()->back()->with('error', $error);
                    }
                }
            }
            // 4 = Kehamilan
            else if($layanan[$x] == 4){
                $cekData = $this->isKlaimNonKesehatan($periode, $nik_karyawan, $layanan[$x]);
                if($cekData['cek']){
                    $error .= '<h5>Klaim Kehamilan sudah pernah diajukan.
                    <br/>Anda dapat mengajukan kembali tahun berikutnya.
                    <br/>Untuk informasi lebih lanjut dapat menghubungi '.MtrUtility::find(51)->informasi.'.
                    <br/>Terima kasih.</h5>';
                    return redirect()->back()->with('error', $error);
                }else{
                    if($jumlah_klaim_hamil > $plafon->kehamilan){
                        $error .= '<h5>Nominal klaim Kehamilan melebihi plafon.</h5>';
                        return redirect()->back()->with('error', $error);
                    }
                }
            }
            // 5 = Persalinan-Normal
            else if($layanan[$x] == 5){
                $cekData = $this->isKlaimNonKesehatan($periode, $nik_karyawan, $layanan[$x]);
                if($cekData['cek']){
                    $error .= '<h5>Klaim Persalinan-Normal sudah pernah diajukan.
                    <br/>Anda dapat mengajukan kembali tahun berikutnya.
                    <br/>Untuk informasi lebih lanjut dapat menghubungi '.MtrUtility::find(51)->informasi.'.
                    <br/>Terima kasih.</h5>';
                    return redirect()->back()->with('error', $error);
                }else{
                    if($jumlah_klaim_salin_normal > $plafon->persalinan_normal){
                        $error .= '<h5>Nominal klaim Persalinan-Normal melebihi plafon.</h5>';
                        return redirect()->back()->with('error', $error);
                    }
                }
            }
            // 6 = Persalinan-Caesar
            else if($layanan[$x] == 6){
                $cekData = $this->isKlaimNonKesehatan($periode, $nik_karyawan, $layanan[$x]);
                if($cekData['cek']){
                    $error .= '<h5>Klaim Persalinan-Caesar sudah pernah diajukan.
                    <br/>Anda dapat mengajukan kembali tahun berikutnya.
                    <br/>Untuk informasi lebih lanjut dapat menghubungi '.MtrUtility::find(51)->informasi.'.
                    <br/>Terima kasih.</h5>';
                    return redirect()->back()->with('error', $error);
                }else{
                    if($jumlah_klaim_salin_caesar > $plafon->persalinan_caesar){
                        $error .= '<h5>Nominal klaim Persalinan-Caesar melebihi plafon.</h5>';
                        return redirect()->back()->with('error', $error);
                    }
                }
            }
        }
        // trx_tbl_reimburse_master
        $trxM->save();
        // trx_tbl_reimburse_log
        $this->insertLog($trxM->id, 1, $nik_karyawan, NULL, NULL);
        // trx_tbl_reimburse_detail
        for ($x = 0; $x < count($hubungan); $x++) {
            $trxD = new TrxReimburseDetail;
            $trxD->id_reimburse = $trxM->id;
            $trxD->nama_pasien = $nama_pasien[$x];
            $trxD->id_hubungan = $hubungan[$x];
            $trxD->id_layanan = $layanan[$x];
            $trxD->tanggal = $tanggal[$x];
            $trxD->jumlah_klaim = $jumlah_klaim[$x];
            $trxD->save();
        }
        // trx_tbl_reimburse_lampiran
        for ($x = 0; $x < count($lampiran); $x++) {
            $trxL = new TrxReimburseLampiran;
            $trxL->id_reimburse = $trxM->id;
            $trxL->id_lampiran = $lampiran[$x];
            // 
            $_filename = $file_lampiran[$x];
            $namafile = $nik_karyawan . '_' . $x . '_' . time() . '.' . $_filename->getClientOriginalExtension();
            $_filename->move('public/assets/reimburse/', $namafile);
            $trxL->filename = $namafile;
            // 
            $trxL->save();
        }
        // start
        // parameter yang dipassing ke sendemail
        $karyawan = json_decode($trxM->karyawan_property, true);
        $mailData['title'] = 'Formulir Reimbursement';
        $mailData['filename'] = 'emails.reimburse0';
        // ambil email pic hco untuk reimburse
        $nik_hco = MtrUtility::find(12)->informasi;
        $email_hco = Karyawan::where('nik', $nik_hco)->value('email');
        $mailData['to'] = $email_hco;
        $mailData['data'] = ['email_to' => $mailData['to'], 'id_status' => 1, 'nama' => $karyawan['nama'].'/'.$karyawan['nik'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'], 'link' => route('reimburse.show', [(new OpenDataController)->encryptWithKey($trxM->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'id_status' => 1]), 'id_trx' => $trxM->id];
        $todolist = ['id_trx' => $trxM->id, 'nik' => $nik_hco, 'id_status' => $trxM->status_pengajuan, 'pic' => 'pic'];
        $this->insertToDoList($todolist, $mailData);
        $this->sendEmail($mailData);
        // finish
        return redirect('reimburse')->with('msg', 'Data is saved successfully.');
    }

    public function isKlaimNonKesehatan($periode, $nik_karyawan, $id_layanan){
        // ambil semua id yang status tidak Ditolak di periode dan nik tersebut
        $where = ['nik_karyawan' => $nik_karyawan];
        if($periode > 0){
            $where = ['periode' => $periode, 'nik_karyawan' => $nik_karyawan];
        }
        $plafonId = TrxReimburseMaster::select('id')->where($where)->whereNotIn('status_pengajuan', [3])->get();
        // ambil semua klaim non kesehatan
        $data = TrxReimburseDetail::whereIn('id_reimburse', $plafonId)->whereNotIn('id_layanan', [1])->orderBy('id', 'desc')->get();
        // 
        $cek = false;
        $tanggal = '';
        foreach ($data as $value) {
            if($value->id_layanan == $id_layanan){
                $tanggal = $value->tanggal;
                // 2 = Kacamata-Frame bisa mengajukan kembali setelah 2 tahun dari tanggal pengajuan terakhir
                if($id_layanan == 2){
                    if(date("Y-m-d") < date("Y-m-d", strtotime("+2 years", strtotime($tanggal)))){
                        $cek = true;
                        break;
                    }
                }
                // 3 = Kacamata-Lensa bisa mengajukan kembali setelah 1 tahun dari tanggal pengajuan terakhir
                else if($id_layanan == 3){
                    if(date("Y-m-d") < date("Y-m-d", strtotime("+1 years", strtotime($tanggal)))){
                        $cek = true;
                        break;
                    }
                }
                // selain Kacamata
                else{
                    $cek = true;
                    break;
                }
            }
        }
        return ['cek'=> $cek, 'tanggal'=>$tanggal];
    }

    public static function getIsKlaimNonKesehatan($periode, $nik_karyawan, $id_layanan){
        // ambil semua id yang status tidak Ditolak di periode dan nik tersebut
        $where = ['nik_karyawan' => $nik_karyawan];
        if($periode > 0){
            $where = ['periode' => $periode, 'nik_karyawan' => $nik_karyawan];
        }
        $plafonId = TrxReimburseMaster::select('id')->where($where)->whereNotIn('status_pengajuan', [3])->get();
        // ambil semua klaim non kesehatan
        $data = TrxReimburseDetail::whereIn('id_reimburse', $plafonId)->whereNotIn('id_layanan', [1])->orderBy('id', 'desc')->get();
        // 
        $cek = false;
        $tanggal = '';
        foreach ($data as $value) {
            if($value->id_layanan == $id_layanan){
                $tanggal = $value->tanggal;
                // 2 = Kacamata-Frame bisa mengajukan kembali setelah 2 tahun dari tanggal pengajuan terakhir
                if($id_layanan == 2){
                    if(date("Y-m-d") < date("Y-m-d", strtotime("+2 years", strtotime($tanggal)))){
                        $cek = true;
                        break;
                    }
                }
                // 3 = Kacamata-Lensa bisa mengajukan kembali setelah 1 tahun dari tanggal pengajuan terakhir
                else if($id_layanan == 3){
                    if(date("Y-m-d") < date("Y-m-d", strtotime("+1 years", strtotime($tanggal)))){
                        $cek = true;
                        break;
                    }
                }
                // selain Kacamata
                else{
                    $cek = true;
                    break;
                }
            }
        }
        return ['cek'=> $cek, 'tanggal'=>$tanggal];
    }

    public function getNikProperty($nik)
    {
        $where = ['nik' => $nik, 'is_aktif' => 1];
        // $data = Karyawan::select('mtr_tbl_karyawan.nik', 'mtr_tbl_karyawan.nama', 'mtr_tbl_karyawan.email', 'mtr_tbl_karyawan.telepon1', 'mtr_tbl_perusahaan.nama as perusahaan', 'mtr_tbl_divisi.nama as divisi', 'mtr_tbl_departemen.nama as dep', 'mtr_tbl_sub_departemen.nama as sub_dep', 'mtr_tbl_level.level as level', 'mtr_tbl_jabatan.jabatan as jabatan', 'mtr_tbl_level.id as id_level')
        //     ->leftJoin('mtr_tbl_perusahaan', 'mtr_tbl_perusahaan.id', '=', 'mtr_tbl_karyawan.kode_perusahaan')
        //     ->leftJoin('mtr_tbl_divisi', 'mtr_tbl_divisi.id', '=', 'mtr_tbl_karyawan.kode_divisi')
        //     ->leftJoin('mtr_tbl_departemen', 'mtr_tbl_departemen.id', '=', 'mtr_tbl_karyawan.kode_departemen')
        //     ->leftJoin('mtr_tbl_sub_departemen', 'mtr_tbl_sub_departemen.id', '=', 'mtr_tbl_karyawan.kode_sub_departemen')
        //     ->leftJoin('mtr_tbl_level', 'mtr_tbl_level.id', '=', 'mtr_tbl_karyawan.kode_level')
        //     ->leftJoin('mtr_tbl_jabatan', 'mtr_tbl_jabatan.id', '=', 'mtr_tbl_karyawan.kode_jabatan')
        //     ->where($where)->first();
        $data = DB::table('mtr_tbl_karyawan as a')->select('a.nik', 'a.nama', 'a.email', 'a.telepon1', 'b.nama as perusahaan', 'c.nama as divisi', 'd.nama as dep', 'e.nama as sub_dep', 'f.level as level', 'g.jabatan as jabatan', 'f.id as id_level', 'h.status_karyawan as status_karyawan', DB::Raw("CONCAT(i.subdepartemen_id, '/', i.subdepartemen_desc) as findim_subdepartemen"), DB::Raw("CONCAT(j.departemen_id, '/', j.departemen_desc) as findim_departemen"), DB::Raw("CONCAT(k.divisi_id, '/', k.divisi_desc) as findim_divisi"), DB::Raw("CONCAT(l.project_id, '/', l.project_desc) as findim_project"))
            ->leftJoin('mtr_tbl_perusahaan as b', 'b.id', '=', 'a.kode_perusahaan')
            ->leftJoin('mtr_tbl_divisi as c', 'c.id', '=', 'a.kode_divisi')
            ->leftJoin('mtr_tbl_departemen as d', 'd.id', '=', 'a.kode_departemen')
            ->leftJoin('mtr_tbl_sub_departemen as e', 'e.id', '=', 'a.kode_sub_departemen')
            ->leftJoin('mtr_tbl_level as f', 'f.id', '=', 'a.kode_level')
            ->leftJoin('mtr_tbl_jabatan as g', 'g.id', '=', 'a.kode_jabatan')
            ->leftJoin('mtr_tbl_status_karyawan as h', 'h.id', '=', 'a.kode_status')
            ->leftJoin('mtr_tbl_findim_subdepartemen as i', 'i.id', '=', 'a.findim_subdepartemen')
            ->leftJoin('mtr_tbl_findim_departemen as j', 'j.id', '=', 'a.findim_departemen')
            ->leftJoin('mtr_tbl_findim_divisi as k', 'k.id', '=', 'a.findim_divisi')
            ->leftJoin('mtr_tbl_findim_project as l', 'l.id', '=', 'a.findim_project')
            ->where($where)->first();
        return json_encode($data);
    }

    public function getNikPropertyBackdate($arr)
    {
        $where = ['nik' => $arr['nik_karyawan'], 'is_aktif' => 1];
        $data = DB::table('mtr_tbl_karyawan_history_archive as a')->select('a.nik', 'a.nama', 'a.email', 'a.telepon1', 'b.nama as perusahaan', 'c.nama as divisi', 'd.nama as dep', 'e.nama as sub_dep', 'f.level as level', 'g.jabatan as jabatan', 'f.id as id_level', 'h.status_karyawan as status_karyawan', DB::Raw("CONCAT(i.subdepartemen_id, '/', i.subdepartemen_desc) as findim_subdepartemen"), DB::Raw("CONCAT(j.departemen_id, '/', j.departemen_desc) as findim_departemen"), DB::Raw("CONCAT(k.divisi_id, '/', k.divisi_desc) as findim_divisi"), DB::Raw("CONCAT(l.project_id, '/', l.project_desc) as findim_project"))
            ->leftJoin('mtr_tbl_perusahaan as b', 'b.id', '=', 'a.kode_perusahaan')
            ->leftJoin('mtr_tbl_divisi as c', 'c.id', '=', 'a.kode_divisi')
            ->leftJoin('mtr_tbl_departemen as d', 'd.id', '=', 'a.kode_departemen')
            ->leftJoin('mtr_tbl_sub_departemen as e', 'e.id', '=', 'a.kode_sub_departemen')
            ->leftJoin('mtr_tbl_level as f', 'f.id', '=', 'a.kode_level')
            ->leftJoin('mtr_tbl_jabatan as g', 'g.id', '=', 'a.kode_jabatan')
            ->leftJoin('mtr_tbl_status_karyawan as h', 'h.id', '=', 'a.kode_status')
            ->leftJoin('mtr_tbl_findim_subdepartemen as i', 'i.id', '=', 'a.findim_subdepartemen')
            ->leftJoin('mtr_tbl_findim_departemen as j', 'j.id', '=', 'a.findim_departemen')
            ->leftJoin('mtr_tbl_findim_divisi as k', 'k.id', '=', 'a.findim_divisi')
            ->leftJoin('mtr_tbl_findim_project as l', 'l.id', '=', 'a.findim_project')
            ->where($where)->whereDate('a.created_at', Carbon::parse($arr['created_at'])->format('Y-m-d'))->first();
        return json_encode($data);
    }

    public function insertToDoList($todolist, $mailData)
    {
        $data = New ToDoList;
        $data->id_trx = $todolist['id_trx'];
        $data->id_status = $todolist['id_status'];
        $data->nik = $todolist['nik'];
        $data->pic = $todolist['pic'];
        $data->title = $mailData['title'];
        $data->nama = $mailData['data']['nama'];
        $data->jabatan = $mailData['data']['jabatan'];
        $data->unit_kerja = $mailData['data']['unit_kerja'];
        $data->link = $mailData['data']['link'];
        $data->filename = $mailData['filename'];
        $data->email = $mailData['to'];
        $data->save();
        $this->insertToDoListLog($todolist, $mailData);
    }

    public function insertToDoListLog($todolist, $mailData)
    {
        $data = New ToDoListLog;
        $data->id_trx = $todolist['id_trx'];
        $data->id_status = $todolist['id_status'];
        $data->nik = $todolist['nik'];
        $data->pic = $todolist['pic'];
        $data->title = $mailData['title'];
        $data->nama = $mailData['data']['nama'];
        $data->jabatan = $mailData['data']['jabatan'];
        $data->unit_kerja = $mailData['data']['unit_kerja'];
        $data->link = $mailData['data']['link'];
        $data->filename = $mailData['filename'];
        $data->email = $mailData['to'];
        $data->save();
    }

    public function deleteToDoList($where)
    {
        // $trx = ToDoList::where($where)->first();
        // if (!empty($trx)) {
        //     $trx->delete();
        // }
        $trx = ToDoList::where($where)->delete();
    }

    public function insertLog($id_trx, $id_status, $nik_pic, $keterangan, $filename)
    {
        $data = new TrxReimburseLog;
        $data->id_trx = $id_trx;
        $data->id_status = $id_status;
        $data->nik_pic = $nik_pic;
        $data->keterangan = $keterangan;
        $data->filename = $filename;
        $data->save();
    }

    public function sendEmail($mailData)
    {
        // hco.all@alita.id
        // $ccEmails = explode(',', MtrUtility::find(11)->informasi);
        // Email cc reimburse only
        $ccEmails = explode(',', MtrUtility::find(18)->informasi);
        Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //
        // $periode = Carbon::now()->format('Y');
        $linkAction = route('reimburse.update', $id);
        $id = (new OpenDataController)->decryptWithKey($id);
        $where = ['id_trx' => $id];
        $history = TrxReimburseLog::where($where)->orderBy('created_at', 'desc')->get();
        // 
        // $where = ['id_trx' => $id];
        // $nReview = TrxReimburseLog::where($where)->whereIn('id_status', [2, 3])->count();
        $nReview = ToDoList::where(['id_trx'=>$id, 'id_status'=>$request->id_status, 'nik'=>MtrUtility::find(12)->informasi])->whereIn('filename', ['emails.reimburse0','emails.reimburse_done'])->exists();
        // 
        $trxM = TrxReimburseMaster::find($id);
        $karyawan = json_decode($trxM->karyawan_property, true);
        // 
        // $where = ['nik_karyawan' => $trxM->nik_karyawan, 'periode' => $periode];
        $where = ['nik_karyawan' => $trxM->nik_karyawan];
        $data = TrxReimburseMaster::select('id')->where($where)->orderBy('id', 'asc')->get();
        $array = array();
        foreach ($data as $value) {
            array_push($array, $value->id);
        }
        $ke = array_search($id, $array) + 1;
        // 
        $where = ['id_reimburse' => $id];
        $lampiran = TrxReimburseLampiran::where($where)->get();
        // 
        $where = ['id_reimburse' => $id];
        $trxD = TrxReimburseDetail::where($where)->get();
        // 
        $data = ['ke' => $ke, 'nik' => $karyawan['nik'], 'nama' => $karyawan['nama'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep']];
        // 
        // $view = $request->view;
        $isHC = $request->isHC;
        $view = (new OpenDataController)->decryptWithKey($request->view);
        switch ($view) {
            case 'approval':
                // if ($nReview == 0) {
                if ($nReview) {
                    return view('emails.reimburse', compact('trxM', 'trxD', 'data', 'lampiran', 'linkAction', 'history'));
                } else {
                    return view('emails.reimburse_ok');
                }
                break;
            case 'detail':
                $this->authorize('auth');
                // 
                $where = ['id_trx' => $id];
                $history = TrxReimburseLog::where($where)->orderBy('created_at', 'desc')->get();
                // 
                return view('formulir.reimburse.view', compact('history', 'trxM', 'trxD', 'data', 'lampiran'));
                break;
            case 'pdf':
                // $this->authorize('auth');
                // ambil plafon
                $where = ['periode' => $trxM->periode, 'nik_karyawan' => $trxM->nik_karyawan];
                $plafon = ReimbursePlafon::where($where)->orderBy('id', 'desc')->first();
                // return response()->json( [ 'success' => true, 'message' => $plafon ], 200 );
                // print to pdf
                $waktu = date('YmdHis');
                // start
                $htmlReimburseLog = '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0"><tr>';
                // karyawan
                $where = ['id_trx' => $id, 'nik_pic' => $trxM->nik_karyawan, 'id_status' => 1];
                $reimburseLog = TrxReimburseLog::where($where)->orderBy('id', 'desc')->first();
                if (!empty($reimburseLog)) {
                    $htmlReimburseLog .= '<td>Mengajukan<br/><img src="' . public_path('assets/images/matta/requested_new.png') . '" height="30" /><br/>' . $karyawan['nama'] . '<br/>' . $karyawan['jabatan'] . '<br/>' . $reimburseLog->created_at . '</td>';
                }
                // hco
                // 6=Penangguhan Pengajuan
                $where = ['id_trx' => $id, 'id_status' => 6];
                $reimburseLog = TrxReimburseLog::where($where)->orderBy('id', 'desc')->first();
                if (!empty($reimburseLog)) {
                    $htmlReimburseLog .= '<td>Menangguhkan<br/><img src="' . public_path('assets/images/matta/hold.png') . '" height="30" /><br/>' . $reimburseLog->_nik->nama . '<br/>' . $reimburseLog->_nik->_jabatan->jabatan . '<br/>' . $reimburseLog->created_at . '</td>';
                }
                // 2=Disetujui dan Proses Pembayaran
                $where = ['id_trx' => $id, 'id_status' => 2];
                $reimburseLog = TrxReimburseLog::where($where)->orderBy('id', 'desc')->first();
                if (!empty($reimburseLog)) {
                    $htmlReimburseLog .= '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $reimburseLog->_nik->nama . '<br/>' . $reimburseLog->_nik->_jabatan->jabatan . '<br/>' . $reimburseLog->created_at . '</td>';
                }
                // 7=Pembayaran Berhasil
                $where = ['id_trx' => $id, 'id_status' => 7];
                $reimburseLog = TrxReimburseLog::where($where)->orderBy('id', 'desc')->first();
                if (!empty($reimburseLog)) {
                    $htmlReimburseLog .= '<td>Pembayaran<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $reimburseLog->_nik->nama . '<br/>' . $reimburseLog->_nik->_jabatan->jabatan . '<br/>' . $reimburseLog->created_at . '</td>';
                }
                // 3=Ditolak
                $where = ['id_trx' => $id, 'id_status' => 3];
                $reimburseLog = TrxReimburseLog::where($where)->orderBy('id', 'desc')->first();
                if (!empty($reimburseLog)) {
                    $htmlReimburseLog .= '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $reimburseLog->_nik->nama . '<br/>' . $reimburseLog->_nik->_jabatan->jabatan . '<br/>' . $reimburseLog->created_at . '</td>';
                }
                // finish
                $htmlReimburseLog .= '</tr></table>';
                // die;
                $where = ['id_reimburse' => $id];
                $check_kes = TrxReimburseDetail::where($where)->whereIn('id_layanan', [1])->exists();
                $where = ['id_reimburse' => $id];
                $check_non_kes = TrxReimburseDetail::where($where)->whereNotIn('id_layanan', [1])->exists();
                // 
                $filename = $waktu . '_formulir_reimburse.pdf';
                $view_kes = \View::make('formulir.reimburse.pdf_kes', compact('trxM', 'trxD', 'plafon', 'karyawan', 'lampiran', 'htmlReimburseLog', 'ke', 'isHC'));
                $html_kes = $view_kes->render();
                $view_non_kes = \View::make('formulir.reimburse.pdf_non_kes', compact('trxM', 'trxD', 'plafon', 'karyawan', 'lampiran', 'htmlReimburseLog', 'ke', 'isHC'));
                $html_non_kes = $view_non_kes->render();
                $pdf = new TCPDF;
                $pdf::SetTitle('Formulir Reimbursement');
                $pdf::SetFont('times', '', 10, '', true);
                if($check_kes){
                    $pdf::AddPage();
                    $pdf::writeHTML($html_kes, true, false, true, false, '');
                }
                if($check_non_kes){
                    $pdf::AddPage();
                    $pdf::writeHTML($html_non_kes, true, false, true, false, '');
                }
                $pdf::Output(public_path($filename), 'I');
                return response()->download(public_path($filename));
                break;
            default:
                return view('emails.reimburse_ok');
        }
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
        $id = (new OpenDataController)->decryptWithKey($id);
        $no = 0;
        $trxM = TrxReimburseMaster::find($id);
        $karyawan = json_decode($trxM->karyawan_property, true);
        // 
        // $where = ['nik_karyawan' => $trxM->nik_karyawan, 'periode' => $periode];
        $where = ['nik_karyawan' => $trxM->nik_karyawan];
        $data = TrxReimburseMaster::select('id')->where($where)->orderBy('id', 'asc')->get();
        $array = array();
        foreach ($data as $value) {
            array_push($array, $value->id);
        }
        $ke = array_search($id, $array) + 1;
        // 
        $where = ['id_reimburse' => $id];
        $lampiran = TrxReimburseLampiran::where($where)->get();
        // 
        $where = ['id_reimburse' => $id];
        $trxD = TrxReimburseDetail::where($where)->get();
        // 
        $data = ['ke' => $ke, 'nik' => $karyawan['nik'], 'nama' => $karyawan['nama'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep']];
        // 
        $where = ['id_trx' => $id];
        $history = TrxReimburseLog::where($where)->orderBy('created_at', 'desc')->get();
        return view('formulir.reimburse.detail.edit', compact('no', 'history', 'trxM', 'trxD', 'data', 'lampiran'));
    }

    public function editDetail($id)
    {
        //
        $id = (new OpenDataController)->decryptWithKey($id);
        $status = 'edit';
        $trxD = TrxReimburseDetail::find( $id );
        $hubungan = ReimburseHubungan::orderBy('id', 'asc')->get();
        $layanan = ReimburseLayanan::orderBy('id', 'asc')->get();
        return view('formulir.reimburse.detail.upsert', compact('trxD', 'status', 'hubungan', 'layanan'));
    }
    
    public function updateDetail(Request $request, $id)
    {
        // dd($request->all(), $id);
        $id = (new OpenDataController)->decryptWithKey($id);
        $trxMId = (new OpenDataController)->decryptWithKey($request->trxMId);
        $trxM = TrxReimburseMaster::find($trxMId);
        $trxD = TrxReimburseDetail::find($id);
        // 
        $nik_karyawan = $trxM->nik_karyawan;
        $periode = $trxM->periode;
        $id_hubungan = $request->id_hubungan;
        $nama_pasien = $request->nama_pasien;
        $tanggal = $request->tanggal;
        $jumlah_klaim_akhir = str_replace( ',', '', $request->jumlah_klaim );
        $jumlah_klaim_awal = $trxD->jumlah_klaim;
        // 
        $where = ['periode' => $periode, 'nik_karyawan' => $nik_karyawan];
        $plafon = ReimbursePlafon::where($where)->orderBy('id', 'desc')->first();
        // jika tidak ada maka ambil default 0
        if(empty($plafon)){
            $plafon = ReimbursePlafon::find(1000);
        }
        // 1 = Kesehatan
        if($request->_id_layanan == 1){
            $klaim_kesehatan_awal = $trxM->klaim_kesehatan;
            $klaim_kesehatan_akhir = $klaim_kesehatan_awal - $jumlah_klaim_awal + $jumlah_klaim_akhir;
            // cek apakah klaim_kesehatan lebih dari plafon kesehatan
            $error = '<h4>ERROR:</h4>';
            // hanya yang ada plafonnya
            if($plafon->id > 1000){
                if($klaim_kesehatan_akhir > $trxM->sisa_plafon_1){
                    $error .= '<h5>Nominal klaim kesehatan melebihi plafon.
                    <br/>Untuk informasi lebih lanjut dapat menghubungi '.MtrUtility::find(51)->informasi.'.
                    <br/>Terima kasih.</h5>';
                    return redirect()->back()->with('error', $error);
                }
            }
            // 
            $trxM->klaim_kesehatan = $klaim_kesehatan_akhir;
            $trxM->sisa_plafon_2 = $trxM->sisa_plafon_1 - $trxM->klaim_kesehatan;
            $trxM->update();
        }
        // 2 = Kacamata-Frame
        else if($request->_id_layanan == 2){
            $klaim_kesehatan_awal = $trxM->klaim_non_kesehatan;
            $klaim_kesehatan_akhir = $klaim_kesehatan_awal - $jumlah_klaim_awal + $jumlah_klaim_akhir;
            // cek apakah klaim_kesehatan lebih dari plafon klaim_non_kesehatan
            $error = '<h4>ERROR:</h4>';
            if($jumlah_klaim_akhir > $plafon->frame){
                $error .= '<h5>Nominal klaim Kacamata-Frame melebihi plafon.</h5>';
                return redirect()->back()->with('error', $error);
            }
            // 
            $trxM->klaim_non_kesehatan = $klaim_kesehatan_akhir;
            $trxM->update();
        }
        // 3 = Kacamata-Lensa
        else if($request->_id_layanan == 3){
            $klaim_kesehatan_awal = $trxM->klaim_non_kesehatan;
            $klaim_kesehatan_akhir = $klaim_kesehatan_awal - $jumlah_klaim_awal + $jumlah_klaim_akhir;
            // cek apakah klaim_kesehatan lebih dari plafon klaim_non_kesehatan
            $error = '<h4>ERROR:</h4>';
            if($jumlah_klaim_akhir > $plafon->lensa){
                $error .= '<h5>Nominal klaim Kacamata-Lensa melebihi plafon.</h5>';
                return redirect()->back()->with('error', $error);
            }
            // 
            $trxM->klaim_non_kesehatan = $klaim_kesehatan_akhir;
            $trxM->update();
        }
        // 4 = Kehamilan
        else if($request->_id_layanan == 4){
            $klaim_kesehatan_awal = $trxM->klaim_non_kesehatan;
            $klaim_kesehatan_akhir = $klaim_kesehatan_awal - $jumlah_klaim_awal + $jumlah_klaim_akhir;
            // cek apakah klaim_kesehatan lebih dari plafon klaim_non_kesehatan
            $error = '<h4>ERROR:</h4>';
            if($jumlah_klaim_akhir > $plafon->kehamilan){
                $error .= '<h5>Nominal klaim Kehamilan melebihi plafon.</h5>';
                return redirect()->back()->with('error', $error);
            }
            // 
            $trxM->klaim_non_kesehatan = $klaim_kesehatan_akhir;
            $trxM->update();
        }
        // 5 = Persalinan-Normal
        else if($request->_id_layanan == 5){
            $klaim_kesehatan_awal = $trxM->klaim_non_kesehatan;
            $klaim_kesehatan_akhir = $klaim_kesehatan_awal - $jumlah_klaim_awal + $jumlah_klaim_akhir;
            // cek apakah klaim_kesehatan lebih dari plafon klaim_non_kesehatan
            $error = '<h4>ERROR:</h4>';
            if($jumlah_klaim_akhir > $plafon->persalinan_normal){
                $error .= '<h5>Nominal klaim Persalinan-Normal melebihi plafon.</h5>';
                return redirect()->back()->with('error', $error);
            }
            // 
            $trxM->klaim_non_kesehatan = $klaim_kesehatan_akhir;
            $trxM->update();
        }
        // 6 = Persalinan-Caesar
        else if($request->_id_layanan == 6){
            $klaim_kesehatan_awal = $trxM->klaim_non_kesehatan;
            $klaim_kesehatan_akhir = $klaim_kesehatan_awal - $jumlah_klaim_awal + $jumlah_klaim_akhir;
            // cek apakah klaim_kesehatan lebih dari plafon klaim_non_kesehatan
            $error = '<h4>ERROR:</h4>';
            if($jumlah_klaim_akhir > $plafon->persalinan_caesar){
                $error .= '<h5>Nominal klaim Persalinan-Caesar melebihi plafon.</h5>';
                return redirect()->back()->with('error', $error);
            }
            // 
            $trxM->klaim_non_kesehatan = $klaim_kesehatan_akhir;
            $trxM->update();
        }
        // 7 = Ekses
        else if($request->_id_layanan == 7){
            $klaim_kesehatan_awal = $trxM->klaim_non_kesehatan;
            $klaim_kesehatan_akhir = $klaim_kesehatan_awal - $jumlah_klaim_awal + $jumlah_klaim_akhir;
            // 
            $trxM->klaim_non_kesehatan = $klaim_kesehatan_akhir;
            $trxM->update();
        }
        // 
        $trxD->id_hubungan = $id_hubungan;
        $trxD->nama_pasien = $nama_pasien;
        $trxD->tanggal = $tanggal;
        $trxD->jumlah_klaim = $jumlah_klaim_akhir;
        $trxD->update();
        if(!empty($request->delete)){
            $trxD->delete();
        }
        // 
        $this->insertLog($trxMId, 5, auth()->user()->nik, NULL, NULL);
        // return response()->json( [ 'request' => $request->all(), 'id' => $id ], 200 );
        return redirect()->route('reimburse.edit', (new OpenDataController)->encryptWithKey($trxMId))->with( 'msg', 'Data is saved successfully.' );
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
        // dd($request->all(), $id);
        $id = (new OpenDataController)->decryptWithKey($id);
        $trx = TrxReimburseMaster::find($id);
        $status_pengajuan = $request->status_pengajuan;
        $catatan = $request->catatan;
        $trx->status_pengajuan = $status_pengajuan;
        $trx->update();
        // upload file jika ada
        $namafile = NULL;
        $filename = $request->file('filename');
        if ($filename){
            $request->validate([
                'filename' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);
            $extension = $filename->getClientOriginalExtension();
            $namafile = $trx->nik_karyawan.'_'.time().'.'.$extension;
            $filename->move('public/assets/reimburse/', $namafile);
        }
        // check 7=Ekses
        $where = ['id_reimburse' => $id];
        $check_ekses = TrxReimburseDetail::where($where)->whereIn('id_layanan', [7])->exists();
        $this->deleteToDoList(['id_trx'=>$id, 'pic'=>'pic', 'filename'=>'emails.reimburse0']);
        $this->deleteToDoList(['id_trx'=>$id, 'pic'=>'pic', 'filename'=>'emails.reimburse_done']);
        $this->insertLog($id, $status_pengajuan, MtrUtility::find(12)->informasi, $catatan, $namafile);
        // 2=Disetujui dan Proses Pembayaran
        // 3=Ditolak
        // 6=Penangguhan Pengajuan
        // 7=Pembayaran Berhasil
        if(in_array($request->status_pengajuan, [2,3,6,7])){
            // start
            // parameter yang dipassing ke sendemail
            // karyawan
            $karyawan = json_decode($trx->karyawan_property, true);
            $mailData['title'] = 'Formulir Reimbursement';
            $mailData['filename'] = 'emails.reimburse_done';
            $mailData['to'] = $karyawan['email'];
            $mailData['data'] = ['email_to' => $mailData['to'], 'id_status' => $request->status_pengajuan, 'status' => ReimburseStatus::find($request->status_pengajuan)->status, 'nama' => $karyawan['nama'].'/'.$karyawan['nik'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'], 'link' => route('reimburse.show', [(new OpenDataController)->encryptWithKey($trx->id), 'view' => (new OpenDataController)->encryptWithKey('pdf'), 'id_status' => $request->status_pengajuan]), 'id_trx' => $trx->id];
            if($trx->plafon > 0){
                // jika bukan Ekses
                if(!$check_ekses){
                    $this->sendEmail($mailData);
                }
            }
            // hco
            // 6=Penangguhan Pengajuan
            if(in_array($request->status_pengajuan, [6])){
                $nik_hco = MtrUtility::find(12)->informasi;
                $email_hco = Karyawan::where('nik', $nik_hco)->value('email');
                $mailData['to'] = $email_hco;
                $mailData['data'] = ['email_to' => $mailData['to'], 'id_status' => $request->status_pengajuan, 'status' => ReimburseStatus::find($request->status_pengajuan)->status, 'nama' => $karyawan['nama'].'/'.$karyawan['nik'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'], 'link' => route('reimburse.show', [(new OpenDataController)->encryptWithKey($trx->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'id_status' => $request->status_pengajuan]), 'id_trx' => $trx->id];
                if($trx->plafon > 0){
                    // jika bukan Ekses
                    if(!$check_ekses){
                        $todolist = ['id_trx' => $trx->id, 'nik' => $nik_hco, 'id_status' => $trx->status_pengajuan, 'pic' => 'pic'];
                        $this->insertToDoList($todolist, $mailData);
                        $this->sendEmail($mailData);
                    }
                }
            }
            // finish
        }
        // 3=Ditolak
        // else if($request->status_pengajuan == 3){
        //     // start
        //     // parameter yang dipassing ke sendemail
        //     $karyawan = json_decode($trx->karyawan_property, true);
        //     $mailData['title'] = 'Formulir Reimbursement';
        //     $mailData['filename'] = 'emails.reimburse_done';
        //     $mailData['to'] = $karyawan['email'];
        //     $mailData['data'] = ['email_to' => $mailData['to'], 'id_status' => $request->status_pengajuan, 'status' => ReimburseStatus::find($request->status_pengajuan)->status, 'nama' => $karyawan['nama'].'/'.$karyawan['nik'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'], 'link' => route('reimburse.show', [(new OpenDataController)->encryptWithKey($trx->id), 'view' => (new OpenDataController)->encryptWithKey('pdf'), 'id_status' => $request->status_pengajuan]), 'id_trx' => $trx->id];
        //     if($trx->plafon > 0){
        //         // jika bukan Ekses
        //         if(!$check_ekses){
        //             $this->sendEmail($mailData);
        //         }
        //     }
        //     // finish
        // }
        return view('emails.reimburse_ok');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($id);
    }
}