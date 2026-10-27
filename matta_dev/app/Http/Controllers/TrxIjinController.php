<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
// 
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Level;
use App\Models\Perusahaan;
use App\Models\Divisi;
use App\Models\Departemen;
use App\Models\SubDepartemen;
use App\Models\Holiday;
// 
use App\Models\IjinTipe;
use App\Models\IjinStatus;
use App\Models\TrxIjin;
use App\Models\TrxIjinLog;
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
// 

class TrxIjinController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show','update']]);
    }

    public function detail(Request $request)
    {
        $karyawan = (new OpenDataController)->decryptWithKey($request->karyawan);
        $tipe = (new OpenDataController)->decryptWithKey($request->tipe);
        $date_from = $request->date_from;
        $date_to = $request->date_to;
        $where = ['karyawan' => $karyawan, 'tipe' => $tipe];
        $trx = TrxIjin::where($where)->whereBetween('tanggal', [$date_from, $date_to])->orderBy('id', 'desc')->get();
        return view( 'formulir.izin.recap.detail', compact('trx'));
    }
    
    public function recap(Request $request)
    {
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        // 0=NA
        // 3=SMS Project
        $perusahaan = Perusahaan::whereNotIn('id', [0,3])->orderBy('nama', 'asc')->get();
        $divisi = [];
        $departemen = [];
        $sub_departemen = [];
        $tipe = IjinTipe::orderBy('tipe', 'asc')->get();
        //
        $arr_perusahaan = [];
        $arr_divisi = [];
        $arr_departemen = [];
        $arr_sub_departemen = [];
        $arr_tipe = [];
        // $limit = 25000;
        $trx = [];
        $arr_is_aktif = [['id' => 1, 'is_aktif' => 'Aktif'], ['id' => 0, 'is_aktif' => 'Non Aktif']];
        $is_aktif = $arr_is_aktif;
        $date_from = '';
        $date_to = '';
        // start filter
        if ($request->filter == true) {
            $date_from = $request->date_from;
            $date_to = $request->date_to;
            $_perusahaan = $request->input('kode_perusahaan', []);
            $_divisi = $request->input('kode_divisi', []);
            $_departemen = $request->input('kode_departemen', []);
            $_sub_departemen = $request->input('kode_sub_departemen', []);
            $_is_aktif = $request->input('is_aktif', []);
            $_tipe = $request->input('tipe', []);
            if (count($_perusahaan) > 0) {
                $arr_perusahaan = $_perusahaan;
            }
            if (count($_divisi) > 0) {
                $arr_divisi = $_divisi;
            }
            if (count($_departemen) > 0) {
                $arr_departemen = $_departemen;
            }
            if (count($_sub_departemen) > 0) {
                $arr_sub_departemen = $_sub_departemen;
            }
            if (count($_is_aktif) > 0) {
                $arr_is_aktif = $_is_aktif;
            }
            if (count($_tipe) > 0) {
                $arr_tipe = $_tipe;
            }
            $divisi = Divisi::whereIn('kode_perusahaan', $arr_perusahaan)->get();
            $departemen = Departemen::whereIn('kode_divisi', $arr_divisi)->get();
            $sub_departemen = SubDepartemen::whereIn('kode_departemen', $arr_departemen)->get();
            $karyawan = Karyawan::whereIn('kode_sub_departemen', $arr_sub_departemen)->whereNotIn('nik', ['Hco.all', 'IT.BM'])->whereIn('is_aktif', $arr_is_aktif)->get();
            $_karyawan = [];
            foreach($karyawan as $data){
                array_push($_karyawan, $data->nik);
            }
            // dd($karyawan);
            // $trx = DB::select("SELECT COUNT(karyawan) AS jumlah,karyawan,tipe FROM trx_tbl_ijin WHERE tanggal BETWEEN '".$date_from."' AND '".$date_to."' AND karyawan IN ('01021403097','CV2023681') GROUP BY karyawan,tipe");
            $trx = TrxIjin::select('karyawan','karyawan_property','tipe', DB::raw('COUNT(karyawan) AS jumlah'))->whereIn('karyawan', $_karyawan)->whereIn('tipe', $arr_tipe)->whereBetween('tanggal', [$date_from, $date_to])->groupBy('karyawan', 'tipe')->orderBy('jumlah', 'desc')->get();
            if(Gate::allows('hc_nkp')){
                $trx = TrxIjin::select('karyawan','karyawan_property','tipe', DB::raw('COUNT(karyawan) AS jumlah'))->whereIn('karyawan',$arr_nik_nkp)->whereIn('karyawan', $_karyawan)->whereIn('tipe', $arr_tipe)->whereBetween('tanggal', [$date_from, $date_to])->groupBy('karyawan', 'tipe')->orderBy('jumlah', 'desc')->get();
            }
        }
        // end filter
        return view( 'formulir.izin.recap.index', compact('trx', 'date_from', 'date_to', 'arr_is_aktif', 'is_aktif', 'perusahaan', 'divisi', 'departemen', 'sub_departemen', 'tipe', 'arr_perusahaan', 'arr_divisi', 'arr_departemen', 'arr_sub_departemen', 'arr_tipe'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $trx = TrxIjin::orderBy('id', 'desc')->get();
        if(Gate::allows('hc_nkp')){
            $trx = TrxIjin::whereIn('karyawan',$arr_nik_nkp)->orderBy('id', 'desc')->get();
        }
        // foreach ($trx as $value) {
        //     $trxM = TrxIjin::find( $value->id );
        //     $trxM->karyawan_property = $this->getNikProperty($value->karyawan);
        //     $trxM->atasan_property = $this->getNikProperty($value->atasan);
        //     $trxM->update();
        // }
        if(auth()->user()->userrole == 'nhc'){
            // $where = ['karyawan' => auth()->user()->nik];
            // $trx = TrxIjin::where($where)->orderBy('id', 'desc')->get();
            // $where = ['layer1' => auth()->user()->nik];
            // $bawahan = Karyawan::select('nik')->where($where)->get();
            // $karyawan = array();
            // array_push($karyawan, auth()->user()->nik);
            // foreach($bawahan as $data){
            //     array_push($karyawan, $data->nik);
            // }
            // $trx = TrxIjin::whereIn('karyawan', $karyawan)->orderBy('id', 'desc')->get();
            $trx = TrxIjin::where('karyawan', [auth()->user()->nik])->orWhere('atasan', [auth()->user()->nik])->orWhere('layer2', [auth()->user()->nik])->orderBy('id', 'desc')->get();
            // 
            $arr_id = [];
            foreach($trx as $_trx){
                array_push($arr_id, $_trx->id);
            }
            $where = ['nik_pic' => auth()->user()->nik];
            $trx = TrxIjinLog::where($where)->orderBy('id_trx', 'desc')->get();
            foreach($trx as $_trx){
                array_push($arr_id, $_trx->id_trx);
            }
            // 
            $trx = TrxIjin::whereIn('id', $arr_id)->orderBy('id', 'desc')->get();
        }
        // filter parameter
        $arr_status = array();
        $arr_tipe = array();
        // $arr_karyawan = array();
        foreach ($trx as $data){
            array_push($arr_status, $data->status);
            array_push($arr_tipe, $data->tipe);
            // array_push($arr_karyawan, $data->karyawan);
        }
        $arr_is_aktif = [['id' => 1, 'is_aktif' => 'Aktif'], ['id' => 0, 'is_aktif' => 'Non Aktif']];
        $is_aktif = $arr_is_aktif;
        $status = IjinStatus::whereIn('id', $arr_status)->orderBy('id', 'asc')->get();
        $tipe = IjinTipe::whereIn('id', $arr_tipe)->orderBy('id', 'asc')->get();
        // $karyawan = Karyawan::whereIn('nik', $arr_karyawan)->orderBy('nama', 'asc')->get();
        // start dibatasi 30 hari terakhir dari created_at
        $trx = $trx->filter(function ($item) {
            return Carbon::parse($item->created_at)->gte(Carbon::now()->subDays(30));
        });
        // end
        $date_from = '';
        $date_to = '';
        // start filter
        if($request->filter == true){
            $date_from = $request->date_from;
            $date_to = $request->date_to;
            // 
            $_status = $request->input('status', []);
            $_tipe = $request->input('tipe', []);
            // $_karyawan = $request->input('karyawan', []);
            $_is_aktif = $request->input('is_aktif', []);
            if(count($_status) > 0){
                $arr_status = $_status;
            }
            else{
                $arr_status = array();
            }
            if(count($_tipe) > 0){
                $arr_tipe = $_tipe;
            }
            else{
                $arr_tipe = array();
            }
            // if(count($_karyawan) > 0){
            //     $arr_karyawan = $_karyawan;
            // }
            // else{
            //     $arr_karyawan = array();
            // }
            if (count($_is_aktif) > 0) {
                $arr_is_aktif = $_is_aktif;
            } else {
                $arr_is_aktif = [];
            }
            $trx = TrxIjin::whereIn('status', $arr_status)->whereIn('tipe', $arr_tipe)->whereIn('karyawan', Karyawan::select('nik')->whereNotIn('nik', ['Hco.all', 'IT.BM'])->whereIn('is_aktif', $arr_is_aktif)->get())->whereBetween('tanggal', [$date_from, $date_to])->orderBy('id', 'desc')->get();
            if(Gate::allows('hc_nkp')){
                $trx = TrxIjin::whereIn('karyawan',$arr_nik_nkp)->whereIn('status', $arr_status)->whereIn('tipe', $arr_tipe)->whereIn('karyawan', Karyawan::select('nik')->whereNotIn('nik', ['Hco.all', 'IT.BM'])->whereIn('is_aktif', $arr_is_aktif)->get())->whereBetween('tanggal', [$date_from, $date_to])->orderBy('id', 'desc')->get();
            }
            if(auth()->user()->userrole == 'nhc'){
                // $where = ['layer1' => auth()->user()->nik];
                // $bawahan = Karyawan::select('nik')->where($where)->get();
                // $karyawan = array();
                // array_push($karyawan, auth()->user()->nik);
                // foreach($bawahan as $data){
                //     array_push($karyawan, $data->nik);
                // }
                // $trx = TrxIjin::whereIn('status', $arr_status)->whereIn('tipe', $arr_tipe)->whereIn('karyawan', $karyawan)->whereIn('karyawan', Karyawan::select('nik')->whereNotIn('nik', ['Hco.all', 'IT.BM'])->whereIn('is_aktif', $arr_is_aktif)->get())->whereBetween('tanggal', [$date_from, $date_to])->orderBy('id', 'desc')->get();
                $trx = TrxIjin::where(function($query) {$query->where('karyawan', [auth()->user()->nik])->orWhere('atasan', [auth()->user()->nik])->orWhere('layer2', [auth()->user()->nik]);})->whereIn('status', $arr_status)->whereIn('tipe', $arr_tipe)->whereIn('karyawan', Karyawan::select('nik')->whereNotIn('nik', ['Hco.all', 'IT.BM'])->whereIn('is_aktif', $arr_is_aktif)->get())->whereBetween('tanggal', [$date_from, $date_to])->orderBy('id', 'desc')->get();
                // 
                $arr_id = [];
                foreach($trx as $_trx){
                    array_push($arr_id, $_trx->id);
                }
                $where = ['nik_pic' => auth()->user()->nik];
                $trx = TrxIjinLog::where($where)->orderBy('id_trx', 'desc')->get();
                foreach($trx as $_trx){
                    array_push($arr_id, $_trx->id_trx);
                }
                // 
                $trx = TrxIjin::whereIn('id', $arr_id)->where(function($query) {$query->where('karyawan', [auth()->user()->nik])->orWhere('atasan', [auth()->user()->nik])->orWhere('layer2', [auth()->user()->nik]);})->whereIn('status', $arr_status)->whereIn('tipe', $arr_tipe)->whereIn('karyawan', Karyawan::select('nik')->whereNotIn('nik', ['Hco.all', 'IT.BM'])->whereIn('is_aktif', $arr_is_aktif)->get())->whereBetween('tanggal', [$date_from, $date_to])->orderBy('id', 'desc')->get();
            }
        }else{
            $arr_status = array();
            $arr_tipe = array();
        }
        // end filter
        return view( 'formulir.izin.index', compact( 'trx', 'status', 'tipe', 'arr_status', 'arr_tipe', 'date_from', 'date_to', 'arr_is_aktif', 'is_aktif'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 1=Izin 1 hari
        // 2=Izin 1/2 hari
        $tipe = IjinTipe::whereIn('id', [1,2])->orderBy('tipe', 'asc')->get();
        return view('formulir.izin.insert', compact('tipe'));
    }
    
    public function isOverlapping($startA, $endA, $startB, $endB)
    {
        return $startA <= $endB && $startB <= $endA;
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
        // return response()->json( [ 'request' => $request->all() ] );
        // karyawan masih di hardcode = Achmad Fahrizal
        // $karyawan = '01031609488';
        $karyawan = auth()->user()->nik;
        $karyawan_property = $this->getNikProperty($karyawan);
        // 
        $where = ['nik' => $karyawan, 'is_aktif' => 1];
        $dataKaryawan = Karyawan::where($where)->first();
        $atasan = $dataKaryawan->layer1;
        $atasan_property = $this->getNikProperty($atasan);
        $layer2 = $dataKaryawan->layer2;
        $layer2_property = $this->getNikProperty($layer2);
        // kode_level=14 adalah direktur
        $where = [ 'nik' => $layer2, 'kode_level' => 14 ];
        $check = Karyawan::where($where)->exists();
        // check apakah layer2 = direktur
        if($check){
            $layer2 = null;
            $layer2_property = null;
        }
        // 1=Izin 1 hari
        // 2=Izin 1/2 hari
        // 3=Work From Anywhere (WFA)
        // 4=Work From Home (WFH)
        $where = [ 'karyawan' => $karyawan, 'tanggal' => $request->tanggal ];
        // 3=Reject by L1
        // 7=Reject by L2
        // 8=Cancel by Requestor
        // $check = TrxIjin::where($where)->whereIn('tipe', [2,3,4])->whereNotIn('status', [3,7,8])->exists();
        $check = TrxIjin::where($where)->whereIn('tipe', [1,2])->whereNotIn('status', [3,7,8])->exists();
        // Izin tidak boleh di tanggal yang sama
        if($check){
            return redirect()->back()->with('error', 'Izin tidak boleh di tanggal yang sama');
        }
        // 
        $where = [ 'nik' => $karyawan, 'is_aktif' => 1, 'wg_id' => 1 ];
        $isreguler = Karyawan::where($where)->exists();
        // $check = Carbon::parse($request->tanggal)->isMonday() || Carbon::parse($request->tanggal)->isFriday();
        // $isPast = Carbon::createFromFormat('Y-m-d', $request->tanggal)->isPast();
        $isPast = Carbon::parse($request->tanggal)->startOfDay()->lt(now()->startOfDay());
        // dd($isPast, $request->tanggal);
        $where = [ 'tanggal' => $request->tanggal ];
        $isholiday = Holiday::where($where)->exists();
        // tidak digunakan
        // Untuk karyawan reguler, Izin Work From Anywhere (WFA)/Work From Home (WFH) hanya di Hari Selasa, Rabu dan Kamis dan bukan libur nasional
        // if($isreguler){
        //     if($check && in_array($request->tipe, [3,4])){
        //         if($isPast){
        //             return redirect()->back()->with('error', 'Izin Work From Anywhere (WFA)/Work From Home (WFH) tidak bisa backdate');
        //         }
        //         else{
        //             return redirect()->back()->with('error', 'Izin Work From Anywhere (WFA)/Work From Home (WFH) hanya di Hari Selasa, Rabu dan Kamis dan bukan libur nasional');
        //         }
        //     }else if(Carbon::parse($request->tanggal)->isWeekend() || $isholiday){
        //         return redirect()->back()->with('error', 'Izin hanya di hari kerja dan bukan libur nasional');
        //     }
        // }
        // 
        if($isPast){
            return redirect()->back()->with('error', 'Izin tidak bisa backdate');
        }
        if($isreguler && (Carbon::parse($request->tanggal)->isWeekend() || $isholiday)){
            return redirect()->back()->with('error', 'Izin hanya di hari kerja dan bukan libur nasional');
        }
        // tidak digunakan
        // $startDate = Carbon::parse($request->tanggal)->startOfWeek()->format('Y-m-d');
        // $endDate = Carbon::parse($request->tanggal)->endOfWeek()->format('Y-m-d');
        // Work From Anywhere (WFA)/Work From Home (WFH) tidak boleh lebih dari 2x dalam 1 minggu pengajuan
        // if($check==2 && in_array($request->tipe, [3,4])){
        //     return redirect()->back()->with('error', 'Izin Work From Anywhere (WFA)/Work From Home (WFH) maksimal 2x dalam 1 minggu');
        // }
        // Maksimal 1 bulan => 2x
        $startDate = Carbon::parse($request->tanggal)->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::parse($request->tanggal)->endOfMonth()->format('Y-m-d');
        $where = [ 'karyawan' => $karyawan ];
        $check = TrxIjin::where($where)->whereBetween('tanggal', [$startDate, $endDate])->whereNotIn('status', [3,7,8])->count();
        if($check==2 && in_array($request->tipe, [1,2])){
            return redirect()->back()->with('error', 'Izin maksimal 2x dalam 1 bulan');
        }
        // Maksimal 1 tahun => 12x
        $startDate = Carbon::parse($request->tanggal)->startOfYear()->format('Y-m-d');
        $endDate = Carbon::parse($request->tanggal)->endOfYear()->format('Y-m-d');
        $where = [ 'karyawan' => $karyawan ];
        $check = TrxIjin::where($where)->whereBetween('tanggal', [$startDate, $endDate])->whereNotIn('status', [3,7,8])->count();
        if($check==12 && in_array($request->tipe, [1,2])){
            return redirect()->back()->with('error', 'Izin maksimal 12x dalam 1 tahun');
        }
        // menunggu penjelasan lebih lanjut
        // $where = [ 'is_aktif' => 1, 'wg_id' => 1, 'kode_sub_departemen' => $dataKaryawan->kode_sub_departemen ];
        // $jumlah = Karyawan::where($where)->count();
        // dd($jumlah);
        // 2=Izin 1/2 hari
        // $where = [ 'karyawan' => $karyawan, 'tanggal' => $request->tanggal ];
        // 3=Reject by L1
        // 7=Reject by L2
        // 8=Cancel by Requestor
        // $check = TrxIjin::where($where)->whereIn('tipe', [2])->whereNotIn('status', [3,7,8])->orderBy('id', 'desc')->first();
        // if(!empty($check)){
        //     $startA = Carbon::parse($check->tanggal.' '.$check->jam_awal);
        //     $endA = Carbon::parse($check->tanggal.' '.$check->jam_akhir);
        //     $startB = Carbon::parse($request->tanggal.' '.$request->jam_awal);
        //     $endB = Carbon::parse($request->tanggal.' '.$request->jam_akhir);
        //     // Izin 1/2 hari tidak boleh waktunya beririsan
        //     if($this->isOverlapping($startA, $endA, $startB, $endB) && in_array($request->tipe, [2])){
        //         return redirect()->back()->with('error', 'Izin 1/2 hari tidak boleh beririsan dengan pengajuan izin sebelumnya');
        //     }
        // }
        // // 2=Izin 1/2 hari
        // $where = [ 'karyawan' => $karyawan, 'tanggal' => $request->tanggal ];
        // 3=Reject by L1
        // 7=Reject by L2
        // 8=Cancel by Requestor
        // $check = TrxIjin::where($where)->whereIn('tipe', [2])->whereNotIn('status', [3,7,8])->count();
        // // Izin 1/2 hari tidak boleh lebih 2x sehari
        // if($check == 2 && in_array($request->tipe, [2])){
        //     return redirect()->back()->with('error', 'Izin 1/2 hari tidak boleh melebihi 2x');
        // }
        // 1 = Submit by Requestor
        $status = 1;
        // 
        $trx = new TrxIjin;
        $trx->karyawan = $karyawan;
        $trx->karyawan_property = $karyawan_property;
        $trx->atasan = $atasan;
        $trx->atasan_property = $atasan_property;
        $trx->layer2 = $layer2;
        $trx->layer2_property = $layer2_property;
        $trx->tipe = $request->tipe;
        $trx->tanggal = $request->tanggal;
        $trx->jam_awal = $request->jam_awal;
        $trx->jam_akhir = $request->jam_akhir;
        $trx->alasan = $request->alasan;
        $trx->status = $status;
        $trx->save();
        $this->insertLog($trx->id, $status, $karyawan, 'insert');
        $_karyawan = json_decode($karyawan_property, true);
        $_atasan = json_decode($atasan_property, true);
        // parameter yang dipassing ke sendemail
        $mailData['title'] = 'Formulir Izin';
        $mailData['data'] = ['nama'=>$_karyawan['nama'].'/'.$_karyawan['nik'], 'jabatan'=>$_karyawan['jabatan'], 'unit_kerja'=>$_karyawan['divisi'].'/'.$_karyawan['dep'].'/'.$_karyawan['sub_dep'], 'atasan'=>$_atasan['nama'], 'link'=> route('izin.show', [(new OpenDataController)->encryptWithKey($trx->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($_atasan['nik']), 'id_status' => (new OpenDataController)->encryptWithKey($status), 'pic' => (new OpenDataController)->encryptWithKey('atasan')]), 'id_trx' => $trx->id ];
        $mailData['filename'] = 'emails.izin0';
        $mailData['to'] = $_atasan['email'];
        $todolist = ['id_trx' => $trx->id, 'nik' => $_atasan['nik'], 'id_status' => $status, 'pic' => 'atasan'];
        $this->insertToDoList($todolist, $mailData);
        $this->sendEmail($mailData);
        // 
        return redirect('izin')->with('msg', 'Data is saved successfully.');
    }

    public function getNikProperty($nik)
    {
        $where = ['nik' => $nik, 'is_aktif' => 1];
        $data = Karyawan::select('mtr_tbl_karyawan.nik', 'mtr_tbl_karyawan.nama', 'mtr_tbl_karyawan.email', 'mtr_tbl_karyawan.telepon1', 'mtr_tbl_perusahaan.nama as perusahaan', 'mtr_tbl_divisi.nama as divisi', 'mtr_tbl_departemen.nama as dep', 'mtr_tbl_sub_departemen.nama as sub_dep', 'mtr_tbl_level.level as level', 'mtr_tbl_jabatan.jabatan as jabatan', 'mtr_tbl_level.id as id_level')
            ->leftJoin('mtr_tbl_perusahaan', 'mtr_tbl_perusahaan.id', '=', 'mtr_tbl_karyawan.kode_perusahaan')
            ->leftJoin('mtr_tbl_divisi', 'mtr_tbl_divisi.id', '=', 'mtr_tbl_karyawan.kode_divisi')
            ->leftJoin('mtr_tbl_departemen', 'mtr_tbl_departemen.id', '=', 'mtr_tbl_karyawan.kode_departemen')
            ->leftJoin('mtr_tbl_sub_departemen', 'mtr_tbl_sub_departemen.id', '=', 'mtr_tbl_karyawan.kode_sub_departemen')
            ->leftJoin('mtr_tbl_level', 'mtr_tbl_level.id', '=', 'mtr_tbl_karyawan.kode_level')
            ->leftJoin('mtr_tbl_jabatan', 'mtr_tbl_jabatan.id', '=', 'mtr_tbl_karyawan.kode_jabatan')
            ->where($where)->first();
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

    public function insertLog($id_trx, $id_status, $nik_pic, $keterangan)
    {
        $data = new TrxIjinLog;
        $data->id_trx = $id_trx;
        $data->id_status = $id_status;
        $data->nik_pic = $nik_pic;
        $data->keterangan = $keterangan;
        $data->save();
    }

    public function sendEmail($mailData)
    {
        $ccEmails = explode(',', MtrUtility::find(11)->informasi);
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
        $linkAction = route('izin.update', $id);
        $id = (new OpenDataController)->decryptWithKey($id);
        // dd($request->all(), $id);
        $where = ['id_trx' => $id];
        $history = TrxIjinLog::where($where)->orderBy('created_at', 'desc')->get();
        $trx = TrxIjin::find( $id );
        // 
        $karyawan = json_decode($trx->karyawan_property, true);
        $atasan = json_decode($trx->atasan_property, true);
        $layer2 = json_decode($trx->layer2_property, true);
        // 
        $view = $request->view;
        $pic = $request->pic;
        $nik = $request->nik;
        $id_status = $request->id_status;
        // 
        $view = (new OpenDataController)->decryptWithKey($view);
        if(!empty($pic)){
            $pic = (new OpenDataController)->decryptWithKey($pic);
        }
        if(!empty($nik)){
            $nik = (new OpenDataController)->decryptWithKey($nik);
        }
        if(!empty($id_status)){
            $id_status = (new OpenDataController)->decryptWithKey($id_status);
        }
        // 1 = Submit by Requestor
        // 2 = Approve by L1
        // 3 = Reject by L1
        $where = ['id_trx' => $id, 'nik_pic' => $nik];
        $nAtasan = TrxIjinLog::where($where)->whereIn('id_status', [2,3])->count();
        // 6 = Approve by L2
        // 7 = Reject by L2
        $where = ['id_trx' => $id, 'nik_pic' => $nik];
        $nLayer2 = TrxIjinLog::where($where)->whereIn('id_status', [6,7])->count();
        // 
        switch ($view) {
            case 'approval':
                // 3=Reject by L1
                // 7=Reject by L2
                // 8=Cancel by Requestor
                if(TrxIjinLog::where(['id_trx'=>$id])->whereIn('id_status', [3,7,8])->exists()){
                    return view('emails.izin_ok');
                }
                // atasan
                if ($pic == 'atasan' && $nik == $trx->atasan && $nAtasan == 0 && $id_status == 1) {
                    // 1 = Submit by Requestor
                    // 2 = Approve by L1
                    // 3 = Reject by L1
                    $approve = 2;
                    $reject = 3;
                    return view('emails.izin', compact('trx', 'nik', 'pic', 'approve', 'reject', 'karyawan', 'linkAction', 'history'));
                } 
                // layer2
                else if ($pic == 'layer2' && $nik == $trx->layer2 && $nLayer2 == 0 && $id_status == 2) {
                    // 6 = Approve by L2
                    // 7 = Reject by L2
                    $approve = 6;
                    $reject = 7;
                    return view('emails.izin', compact('trx', 'nik', 'pic', 'approve', 'reject', 'karyawan', 'linkAction', 'history'));
                } 
                // selainnya
                else {
                    return view('emails.izin_ok');
                }
                break;
            case 'detail':
                $this->authorize('auth');
                $where = ['id_trx' => $id];
                $history = TrxIjinLog::where($where)->orderBy('created_at', 'desc')->get();
                // 
                return view('formulir.izin.view', compact('history', 'trx', 'karyawan', 'atasan', 'layer2'));
                break;
            case 'pdf':
                $this->authorize('auth');
                // print to pdf
                $waktu = date('YmdHis');
                // start
                $htmlLog = '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0"><tr>';
                // karyawan
                // 1 = Submit by Requestor
                $where = ['id_trx' => $id, 'nik_pic' => $karyawan['nik'], 'id_status' => 1];
                $izinLog = TrxIjinLog::where($where)->orderBy('id', 'desc')->first();
                $htmlLog .= '<td>Mengajukan<br/><img src="' . public_path('assets/images/matta/requested_new.png') . '" height="30" /><br/>' . $karyawan['nama'] . '<br/>' . $karyawan['jabatan'] . '<br/>' . $izinLog->created_at . '</td>';
                // atasan
                // 2 = Approve by L1
                // 3 = Reject by L1
                $where = ['id_trx' => $id, 'nik_pic' => $trx->atasan];
                $izinLog = TrxIjinLog::where($where)->whereIn('id_status', [2,3])->orderBy('id', 'desc')->first();
                if (!empty($izinLog)) {
                    if($izinLog->id_status == 2){
                        $htmlLog .= '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $atasan['nama'] . '<br/>' . $atasan['jabatan'] . '<br/>' . $izinLog->created_at . '</td>';
                    }else if($izinLog->id_status == 3){
                        $htmlLog .= '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $atasan['nama'] . '<br/>' . $atasan['jabatan'] . '<br/>' . $izinLog->created_at . '</td>';
                    }
                }
                // layer2
                // 6 = Approve by L2
                // 7 = Reject by L2
                $where = ['id_trx' => $id, 'nik_pic' => $trx->layer2];
                $izinLog = TrxIjinLog::where($where)->whereIn('id_status', [6,7])->orderBy('id', 'desc')->first();
                if (!empty($izinLog)) {
                    if($izinLog->id_status == 6){
                        $htmlLog .= '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $layer2['nama'] . '<br/>' . $layer2['jabatan'] . '<br/>' . $izinLog->created_at . '</td>';
                    }else if($izinLog->id_status == 7){
                        $htmlLog .= '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $layer2['nama'] . '<br/>' . $layer2['jabatan'] . '<br/>' . $izinLog->created_at . '</td>';
                    }
                }
                // 8=Cancel by Requestor
                $where = ['id_trx' => $id, 'nik_pic' => $trx->karyawan];
                $izinLog = TrxIjinLog::where($where)->whereIn('id_status', [8])->orderBy('id', 'desc')->first();
                if (!empty($izinLog)) {
                    $htmlLog .= '<td>Membatalkan<br/><img src="' . public_path('assets/images/matta/cancelled.png') . '" height="30" /><br/>' . $karyawan['nama'] . '<br/>' . $karyawan['jabatan'] . '<br/>' . $izinLog->created_at . '</td>';
                }
                // // hco
                // $where = ['id_trx' => $id, 'id_status' => 6];
                // $izinLog = TrxIjinLog::where($where)->orderBy('id', 'desc')->first();
                // if (!empty($izinLog)) {
                //     $htmlLog .= '<td>Mengetahui<br/><img src="' . public_path('assets/images/matta/informed_new.png') . '" height="30" /><br/>' . $izinLog->nik_pic . '<br/>' . $izinLog->created_at . '</td>';
                // }
                // finish
                $htmlLog .= '</tr></table>';
                // 
                $filename = $waktu . '_formulir_izin.pdf';
                $view = \View::make('formulir.izin.pdf', compact('trx', 'karyawan', 'htmlLog'));
                $html = $view->render();
                $pdf = new TCPDF;
                $pdf::SetTitle('Formulir Izin');
                $pdf::SetFont('times', '', 10, '', true);
                $pdf::AddPage();
                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output(public_path($filename), 'I');
                return response()->download(public_path($filename));
                break;
            default:
                return view('emails.izin_ok');
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
        // masih di hardcode pic hco -> Khatrin Rismasany
        // $hco = '01031908799';
        // $hco = auth()->user()->nik;
		$hco = MtrUtility::find(20)->informasi;
        $id = (new OpenDataController)->decryptWithKey($id);
        // dd($request->all(), $id);
        // dd($request->all(), $id);
        $trx = TrxIjin::find( $id );
        $karyawan = json_decode($trx->karyawan_property, true);
        $atasan = json_decode($trx->atasan_property, true);
        $layer2 = json_decode($trx->layer2_property, true);
        // 
        $pic = $request->pic;
        $nik = $request->nik;
        $status = $request->status;
        $keterangan = $request->keterangan;
        // 2 = Approve by L1
        // 3 = Reject by L1
        if (($status == 2 || $status == 3) && $pic == 'atasan' && $nik == $trx->atasan) {
            $trx->status = $status;
            $trx->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.izin0']);
            $this->insertLog($id, $status, $nik, $keterangan);
            if($status == 2){
                if(!empty($layer2)){
                    // // start
                    // // parameter yang dipassing ke sendemail
                    // $mailData['title'] = 'Formulir Izin';
                    // $mailData['data'] = ['nama'=>$karyawan['nama'].'/'.$karyawan['nik'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('izin.show', [(new OpenDataController)->encryptWithKey($trx->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($layer2['nik']), 'id_status' => (new OpenDataController)->encryptWithKey($status), 'pic' => (new OpenDataController)->encryptWithKey('layer2')]), 'id_trx' => $trx->id ];
                    // $mailData['filename'] = 'emails.izin0';
                    // $mailData['to'] = $layer2['email'];
                    // $todolist = ['id_trx' => $trx->id, 'nik' => $layer2['nik'], 'id_status' => $status, 'pic' => 'layer2'];
                    // $this->insertToDoList($todolist, $mailData);
                    // $this->sendEmail($mailData);
                    // check apakah $atasan == $layer2 jika sama maka auto approve oleh $layer2
                    if($atasan['nik'] == $layer2['nik']){
                        // 6 = Approve by L2
                        $status = 6;
                        $trx->status = $status;
                        $trx->update();
                        $this->insertLog($id, $status, $nik, $keterangan);
                        // start
                        // parameter yang dipassing ke sendemail
                        $mailData['title'] = 'Formulir Izin';
                        // $mailData['filename'] = 'emails.izin1';
                        $mailData['filename'] = 'emails.izin_setuju';
                        $mailData['data'] = ['nama' => $karyawan['nama'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link' => route('izin.show', [(new OpenDataController)->encryptWithKey($trx->id), 'view' => (new OpenDataController)->encryptWithKey('pdf')]), 'id_trx' => $trx->id];
                        // $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
                        $mailData['to'] = $karyawan['email'];
                        $this->sendEmail($mailData);
                        return view('emails.izin_ok');
                        // finish
                    }
                    else{
                        // start
                        // parameter yang dipassing ke sendemail
                        $mailData['title'] = 'Formulir Izin';
                        $mailData['data'] = ['nama'=>$karyawan['nama'].'/'.$karyawan['nik'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('izin.show', [(new OpenDataController)->encryptWithKey($trx->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($layer2['nik']), 'id_status' => (new OpenDataController)->encryptWithKey($status), 'pic' => (new OpenDataController)->encryptWithKey('layer2')]), 'id_trx' => $trx->id ];
                        $mailData['filename'] = 'emails.izin0';
                        $mailData['to'] = $layer2['email'];
                        $todolist = ['id_trx' => $trx->id, 'nik' => $layer2['nik'], 'id_status' => $status, 'pic' => 'layer2'];
                        $this->insertToDoList($todolist, $mailData);
                        $this->sendEmail($mailData);
                    }
                }else{
                    // start
                    // parameter yang dipassing ke sendemail
                    $mailData['title'] = 'Formulir Izin';
                    $mailData['filename'] = 'emails.izin_setuju';
                    $mailData['data'] = ['nama' => $karyawan['nama'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link' => route('izin.show', [(new OpenDataController)->encryptWithKey($trx->id), 'view' => (new OpenDataController)->encryptWithKey('pdf')]), 'id_trx' => $trx->id];
                    // $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
                    $mailData['to'] = $karyawan['email'];
                    $this->sendEmail($mailData);}
            }
            return view('emails.izin_ok');
            // finish
        }
        // 6 = Approve by L2
        // 7 = Reject by L2
        else if (($status == 6 || $status == 7) && $pic == 'layer2' && $nik == $trx->layer2) {
            $trx->status = $status;
            $trx->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.izin0']);
            $this->insertLog($id, $status, $nik, $keterangan);
            // start
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Formulir Izin';
            // $mailData['filename'] = 'emails.izin1';
            $mailData['filename'] = 'emails.izin_setuju';
            $mailData['data'] = ['nama' => $karyawan['nama'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link' => route('izin.show', [(new OpenDataController)->encryptWithKey($trx->id), 'view' => (new OpenDataController)->encryptWithKey('pdf')]), 'id_trx' => $trx->id];
            // $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
            $mailData['to'] = $karyawan['email'];
            $this->sendEmail($mailData);
            return view('emails.izin_ok');
            // finish
        }
        // 4 = Done by HC
        else if ($status == 4) {
            $trx->status = $status;
            // 
            $trx->update();
            $this->insertLog($id, $status, $hco, 'Done by HC');
            // 
            return redirect('izin')->with('msg', 'Data is saved successfully.');
        }
        // 8=Cancel by Requestor
        else if ($status == 8) {
            $trx->status = $status;
            $trx->update();
            $this->deleteToDoList(['id_trx'=>$id, 'filename'=>'emails.izin0']);
            $this->insertLog($id, $status, $nik, $keterangan);
            // start
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Formulir Izin';
            $mailData['data'] = ['nama'=>$karyawan['nama'].'/'.$karyawan['nik'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('izin.show', [(new OpenDataController)->encryptWithKey($trx->id), 'view' => (new OpenDataController)->encryptWithKey('pdf')]), 'id_trx' => $trx->id];
            $mailData['filename'] = 'emails.izin_cancel';
            $mailData['to'] = $atasan['email'];
            $this->sendEmail($mailData);
            $mailData['to'] = $layer2['email'];
            $this->sendEmail($mailData);
            // 
            return redirect('izin')->with('msg', 'Data is saved successfully.');
        }
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

    public static function getTanggalIndonesia($tgl)
    {
        $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $strTgl = $hari[Carbon::createFromFormat('Y-m-d', $tgl)->dayOfWeek] . ', ';
        $strTgl .= Carbon::createFromFormat('Y-m-d', $tgl)->format('d-m-Y');
        return $strTgl;
    }
}
