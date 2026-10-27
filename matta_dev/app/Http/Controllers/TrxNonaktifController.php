<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
// 
use App\Models\Karyawan;
use App\Models\Perusahaan;
use App\Models\Jabatan;
use App\Models\Level;
use App\Models\MtrPenilaianPerpanjang;
use App\Models\TrxPenilaian;
// 
use App\Models\NonaktifAlasan;
use App\Models\NonaktifObyek;
use App\Models\NonaktifStatus;
use App\Models\TrxNonaktifMaster;
use App\Models\TrxNonaktifDetail;
use App\Models\TrxNonaktifLog;
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

class TrxNonaktifController extends Controller
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
        //
        function forgetById($collection,$id){
            foreach($collection as $key => $item){
                if($item->id == $id){
                    $collection->forget($key);
                    break;
                }
            }
            return $collection;
        }
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $no = 0;
        $trx = TrxNonaktifMaster::orderBy('id', 'desc')->get();
        if(Gate::allows('hc_nkp')){
            $trx = TrxNonaktifMaster::whereIn('pengaju',$arr_nik_nkp)->orderBy('id', 'desc')->get();
        }
        // foreach ($trx as $value) {
        //     $trxM = TrxNonaktifMaster::find( $value->id );
        //     $trxM->pengaju_property = $this->getNikProperty($value->pengaju);
        //     $trxM->atasan_property = $this->getNikProperty($value->atasan);
        //     $trxM->update();
        // }
        // $_trx = TrxNonaktifDetail::orderBy('id', 'desc')->get();
        // foreach ($_trx as $value) {
        //     $trxD = TrxNonaktifDetail::find( $value->id );
        //     $trxD->kepada_property = $this->getNikProperty($value->kepada);
        //     $trxD->update();
        // }
        if(auth()->user()->userrole == 'nhc'){
            $where = ['pengaju' => auth()->user()->nik];
            $trx = TrxNonaktifMaster::where($where)->orderBy('id', 'desc')->get();
            // 
            $arr_id = [];
            foreach($trx as $_trx){
                array_push($arr_id, $_trx->id);
            }
            $where = ['nik_pic' => auth()->user()->nik];
            $trx = TrxNonaktifLog::where($where)->orderBy('id_trx', 'desc')->get();
            foreach($trx as $_trx){
                array_push($arr_id, $_trx->id_trx);
            }
            // 
            $trx = TrxNonaktifMaster::whereIn('id', $arr_id)->orderBy('id', 'desc')->get();
        }
        // filter parameter
        $nama = '';
        $atasan = '';
        $arr_status = array();
        $arr_alasan = array();
        $arr_perusahaan = array();
        $arr_perpanjangan = array();
        $perusahaan = Perusahaan::select('id','nama')->whereNotIn('id', [0,3])->orderBy('nama', 'asc')->get();
        $perpanjangan = MtrPenilaianPerpanjang::select('id','informasi')->orderBy('informasi', 'asc')->get();
        foreach ($trx as $data){
            array_push($arr_status, $data->status);
            array_push($arr_alasan, $data->alasan);
        }
        $status = NonaktifStatus::whereIn('id', $arr_status)->orderBy('id', 'asc')->get();
        $alasan = NonaktifAlasan::whereIn('id', $arr_alasan)->orderBy('id', 'asc')->get();
        // start dibatasi 30 hari terakhir dari created_at
        $trx = $trx->filter(function ($item) {
            return Carbon::parse($item->created_at)->gte(Carbon::now()->subDays(30));
        });
        $tanggal_mulai = '';
        $tanggal_akhir = '';
        // end
        // start filter
        if($request->filter == true){
            $tanggal_mulai = $request->tanggal_mulai;
            $tanggal_akhir = $request->tanggal_akhir;
            $nama = $request->nama;
            $atasan = $request->atasan;
            $_status = $request->input('status', []);
            $_alasan = $request->input('alasan', []);
            $_perusahaan = $request->input('perusahaan', []);
            $_perpanjangan = $request->input('perpanjangan', []);
            if(count($_status) > 0){
                $arr_status = $_status;
            }
            else{
                $arr_status = array();
            }
            if(count($_alasan) > 0){
                $arr_alasan = $_alasan;
            }
            else{
                $arr_alasan = array();
            }
            if(count($_perusahaan) > 0){
                $arr_perusahaan = $_perusahaan;
            }
            else{
                $arr_perusahaan = array();
            }
            if(count($_perpanjangan) > 0){
                $arr_perpanjangan = $_perpanjangan;
            }
            else{
                $arr_perpanjangan = array();
            }
            $_nama = Karyawan::select('nik')->where('nama','LIKE','%'.$nama.'%')->get();
            $_atasan = Karyawan::select('nik')->where('nama','LIKE','%'.$atasan.'%')->get();
            $nik_karyawan = Karyawan::select('nik')->whereIn('kode_perusahaan', $arr_perusahaan)->get()->toArray();
            $trx = TrxNonaktifMaster::whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->whereIn('pengaju', $nik_karyawan)->whereIn('pengaju', $_nama)->whereIn('atasan', $_atasan)->whereIn('status', $arr_status)->whereIn('alasan', $arr_alasan)->orderBy('id', 'desc')->get();
            if(Gate::allows('hc_nkp')){
                $trx = TrxNonaktifMaster::whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->whereIn('pengaju',$arr_nik_nkp)->whereIn('pengaju', $nik_karyawan)->whereIn('pengaju', $_nama)->whereIn('atasan', $_atasan)->whereIn('status', $arr_status)->whereIn('alasan', $arr_alasan)->orderBy('id', 'desc')->get();
            }
            foreach ($trx as $_trx) {
                $where = ['nik_karyawan' => $_trx->pengaju];
                $check = TrxPenilaian::where($where)->whereIn('id_status', [4,8])->whereIn('is_perpanjang', $arr_perpanjangan)->get();
                if(count($check)==0){
                    $trx = forgetById($trx, $_trx->id);
                }
            }
            if(auth()->user()->userrole == 'nhc'){
                $where = ['pengaju' => auth()->user()->nik];
                $trx = TrxNonaktifMaster::whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->whereIn('pengaju', $nik_karyawan)->whereIn('pengaju', $_nama)->whereIn('atasan', $_atasan)->whereIn('status', $arr_status)->whereIn('alasan', $arr_alasan)->where($where)->orderBy('id', 'desc')->get();
                // 
                $arr_id = [];
                foreach($trx as $_trx){
                    array_push($arr_id, $_trx->id);
                }
                $where = ['nik_pic' => auth()->user()->nik];
                $trx = TrxNonaktifLog::where($where)->orderBy('id_trx', 'desc')->get();
                foreach($trx as $_trx){
                    array_push($arr_id, $_trx->id_trx);
                }
                // 
                $trx = TrxNonaktifMaster::whereIn('id', $arr_id)->whereIn('pengaju', $nik_karyawan)->whereIn('pengaju', $_nama)->whereIn('atasan', $_atasan)->whereIn('status', $arr_status)->whereIn('alasan', $arr_alasan)->orderBy('id', 'desc')->get();
                foreach ($trx as $_trx) {
                    $where = ['nik_karyawan' => $_trx->pengaju];
                    $check = TrxPenilaian::where($where)->whereIn('id_status', [4,8])->whereIn('is_perpanjang', $arr_perpanjangan)->get();
                    if(count($check)==0){
                        $trx = forgetById($trx, $_trx->id);
                    }
                }
            }
        }else{
            $arr_status = array();
            $arr_alasan = array();
            $arr_perusahaan = array();
            $arr_perpanjangan = array();
        }
        // end filter
        return view( 'formulir.nonaktif.index', compact( 'trx', 'no', 'status', 'alasan', 'arr_status', 'arr_alasan', 'nama', 'atasan', 'perusahaan', 'arr_perusahaan', 'perpanjangan', 'arr_perpanjangan', 'tanggal_mulai', 'tanggal_akhir'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // dd($request->all());
        if(empty($request->created_at)){
            // return redirect()->route('nonaktif.index', ['is_setuju' => 1, 'created_at' => Crypt::encrypt(Carbon::now())]);
            return redirect()->route('nonaktif.index')->with('show_modal', true);
        }
        $is_setuju = $request->is_setuju;
        $created_at = Crypt::decrypt($request->created_at);
        // $is_setuju = 1;
        // if(!empty($request->is_setuju)){
        //     $is_setuju = $request->is_setuju;
        // }
        // $created_at = Carbon::now();
        // if(!empty($request->created_at)){
        //     $created_at = Crypt::decrypt($request->created_at);
        // }
        // dd($is_setuju, $created_at);
        //
        $alasan = NonaktifAlasan::orderBy('alasan', 'asc')->get();
        // di = divisi
        $where = ['_into_' => 'di', 'is_active' => 1];
        $obyek_di = NonaktifObyek::where($where)->orderBy('obyek', 'asc')->get();
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        // ga
        $where = ['_into_' => 'ga', 'is_active' => 1];
        $obyek_ga = NonaktifObyek::where($where)->orderBy('obyek', 'asc')->get();
        $where = ['is_aktif' => 1, 'nik' => MtrUtility::find(13)->informasi];
        $pic_ga = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        // it
        $where = ['_into_' => 'it', 'is_active' => 1];
        $obyek_it = NonaktifObyek::where($where)->orderBy('obyek', 'asc')->get();
        $where = ['is_aktif' => 1, 'nik' => MtrUtility::find(14)->informasi];
        $pic_it = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        // hc
        $where = ['_into_' => 'hc', 'is_active' => 1];
        $obyek_hc = NonaktifObyek::where($where)->orderBy('obyek', 'asc')->get();
        $where = ['is_aktif' => 1, 'nik' => MtrUtility::find(15)->informasi];
        $pic_hc = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        // ko = koperasi
        $where = ['_into_' => 'ko', 'is_active' => 1];
        $obyek_ko = NonaktifObyek::where($where)->orderBy('obyek', 'asc')->get();
        $where = ['is_aktif' => 1, 'nik' => MtrUtility::find(16)->informasi];
        $pic_ko = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        // am = application management
        $where = ['_into_' => 'am', 'is_active' => 1];
        $obyek_am = NonaktifObyek::where($where)->orderBy('obyek', 'asc')->get();
        $where = ['is_aktif' => 1, 'nik' => MtrUtility::find(42)->informasi];
        $pic_am = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        // 
        return view('formulir.nonaktif.insert', compact('alasan', 'karyawan', 'obyek_di', 'pic_ga', 'obyek_ga', 'pic_it', 'obyek_it', 'pic_hc', 'obyek_hc', 'pic_ko', 'obyek_ko', 'pic_am', 'obyek_am', 'is_setuju', 'created_at'));
        // return response()->json( [ 'data' => $_pic_ga ] );die;
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
        // cek jika hanya yang belum pernah mengajukan
        $exists = false;
        $where = ['nik' => auth()->user()->nik];
        $karyawan = Karyawan::where($where)->get();
        foreach($karyawan as $_karyawan){
            $where = ['pengaju' => auth()->user()->nik];
            if(TrxNonaktifMaster::where($where)->exists()){
                $exists = true;
                break;
            }
            // foreach($trxM as $_trxM){
            //     $_pengaju = json_decode($_trxM->pengaju_property, true);
            //     if($_karyawan->tanggal_bergabung == $_pengaju['tanggal_bergabung']){
            //         $exists = true;
            //         break;
            //     }
            // }
        }
        if($exists){
            return redirect()->back()->with('error', 'Formulir Karyawan Non Aktif -> NOK');
        }
        // 
        $pengaju = auth()->user()->nik;
        $pengaju_property = $this->getNikProperty($pengaju);
        $_pengaju = json_decode($pengaju_property, true);
        $where = ['nik' => $pengaju];
        $karyawan = Karyawan::where($where)->first();
        $atasan = $karyawan->layer1;
        $atasan_property = $this->getNikProperty($atasan);
        // return response()->json( [ 'atasan_property' => $atasan_property ] );die;
        // 
        $trxM = new TrxNonaktifMaster;
        $trxM->pengaju = $pengaju;
        $trxM->pengaju_property = $pengaju_property;
        $trxM->atasan = $atasan;
        $trxM->atasan_property = $atasan_property;
        $trxM->alasan = $request->alasan;
        $trxM->tanggal = $request->tanggal;
        $trxM->keterangan = $request->keterangan;
        // 1 = Submit to All PIC
        $status = 1;
        $trxM->status = $status;
        $trxM->save();
        $this->insertLog($trxM->id, $status, $pengaju, 'insert');
        // 31 = Statement Of Compliance
        $status = 31;
        $this->insertLog($trxM->id, $status, $pengaju, $request->created_at);
        // agregate semua input array
        // _di
        $kepada_di = $request->input('kepada_di', []);
        $obyek_di = $request->input('obyek_di', []);
        $keterangan_di = $request->input('keterangan_di', []);
        // _ga
        $kepada_ga = $request->input('kepada_ga', []);
        $obyek_ga = $request->input('obyek_ga', []);
        $keterangan_ga = $request->input('keterangan_ga', []);
        // _it
        $kepada_it = $request->input('kepada_it', []);
        $obyek_it = $request->input('obyek_it', []);
        $keterangan_it = $request->input('keterangan_it', []);
        // _hc
        $kepada_hc = $request->input('kepada_hc', []);
        $obyek_hc = $request->input('obyek_hc', []);
        $keterangan_hc = $request->input('keterangan_hc', []);
        // _ko
        $kepada_ko = $request->input('kepada_ko', []);
        $obyek_ko = $request->input('obyek_ko', []);
        $keterangan_ko = $request->input('keterangan_ko', []);
        // _am
        $kepada_am = $request->input('kepada_am', []);
        $obyek_am = $request->input('obyek_am', []);
        $keterangan_am = $request->input('keterangan_am', []);
        // _di
        for ($x = 0; $x < count($kepada_di); $x++) {
            $trxD = new TrxNonaktifDetail;
            $trxD->id_trx = $trxM->id;
            $trxD->_into_ = 'di';
            $trxD->kepada = $kepada_di[$x];
            $kepada_property = $this->getNikProperty($kepada_di[$x]);
            $trxD->kepada_property = $kepada_property;
            $trxD->obyek = $obyek_di[$x];
            $trxD->keterangan = $keterangan_di[$x];
            $trxD->save();
        }
        // _ga
        for ($x = 0; $x < count($kepada_ga); $x++) {
            $trxD = new TrxNonaktifDetail;
            $trxD->id_trx = $trxM->id;
            $trxD->_into_ = 'ga';
            $trxD->kepada = $kepada_ga[$x];
            $kepada_property = $this->getNikProperty($kepada_ga[$x]);
            $trxD->kepada_property = $kepada_property;
            $trxD->obyek = $obyek_ga[$x];
            $trxD->keterangan = $keterangan_ga[$x];
            $trxD->save();
        }
        // _it
        for ($x = 0; $x < count($kepada_it); $x++) {
            $trxD = new TrxNonaktifDetail;
            $trxD->id_trx = $trxM->id;
            $trxD->_into_ = 'it';
            $trxD->kepada = $kepada_it[$x];
            $kepada_property = $this->getNikProperty($kepada_it[$x]);
            $trxD->kepada_property = $kepada_property;
            $trxD->obyek = $obyek_it[$x];
            $trxD->keterangan = $keterangan_it[$x];
            $trxD->save();
        }
        // _hc
        for ($x = 0; $x < count($kepada_hc); $x++) {
            $trxD = new TrxNonaktifDetail;
            $trxD->id_trx = $trxM->id;
            $trxD->_into_ = 'hc';
            $trxD->kepada = $kepada_hc[$x];
            $kepada_property = $this->getNikProperty($kepada_hc[$x]);
            $trxD->kepada_property = $kepada_property;
            $trxD->obyek = $obyek_hc[$x];
            $trxD->keterangan = $keterangan_hc[$x];
            $trxD->save();
        }
        // _ko
        for ($x = 0; $x < count($kepada_ko); $x++) {
            $trxD = new TrxNonaktifDetail;
            $trxD->id_trx = $trxM->id;
            $trxD->_into_ = 'ko';
            $trxD->kepada = $kepada_ko[$x];
            $kepada_property = $this->getNikProperty($kepada_ko[$x]);
            $trxD->kepada_property = $kepada_property;
            $trxD->obyek = $obyek_ko[$x];
            $trxD->keterangan = $keterangan_ko[$x];
            $trxD->save();
        }
        // _am
        for ($x = 0; $x < count($kepada_am); $x++) {
            $trxD = new TrxNonaktifDetail;
            $trxD->id_trx = $trxM->id;
            $trxD->_into_ = 'am';
            $trxD->kepada = $kepada_am[$x];
            $kepada_property = $this->getNikProperty($kepada_am[$x]);
            $trxD->kepada_property = $kepada_property;
            $trxD->obyek = $obyek_am[$x];
            $trxD->keterangan = $keterangan_am[$x];
            $trxD->save();
        }
        // sendEmail
        // _di
        $pic = 'di';
        $where = ['id_trx' => $trxM->id, '_into_' => $pic];
        $_trxD = TrxNonaktifDetail::select('kepada_property')->where($where)->groupBy('kepada_property')->get();
        foreach ($_trxD as $value) {
            // start
            // parameter yang dipassing ke sendemail
            // 5 = Submit to PIC DIVISI
            $status = 5;
            $mailData['title'] = 'Formulir Karyawan Non Aktif - '.$pic;
            $mailData['filename'] = 'emails.nonaktif0';
            $_kepada = json_decode($value->kepada_property, true);
            $mailData['data'] = ['pesan' => 'Terdapat Formulir Karyawan Non Aktif yang harus anda review', 'nama'=>$_pengaju['nama'].'/'.$_pengaju['nik'], 'jabatan'=>$_pengaju['jabatan'], 'unit_kerja'=>$_pengaju['divisi'].'/'.$_pengaju['dep'].'/'.$_pengaju['sub_dep'], 'link'=> route('nonaktif.show', [(new OpenDataController)->encryptWithKey($trxM->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($_kepada['nik']), 'id_status' => (new OpenDataController)->encryptWithKey($status), 'pic' => (new OpenDataController)->encryptWithKey($pic)]), 'id_trx' => $trxM->id ];
            $mailData['to'] = $_kepada['email'];
            $todolist = ['id_trx' => $trxM->id, 'nik' => $_kepada['nik'], 'id_status' => $status, 'pic' => $pic];
            $this->insertToDoList($todolist, $mailData);
            $this->sendEmail($mailData);
            // finish
        }
        // _ga
        $pic = 'ga';
        $where = ['id_trx' => $trxM->id, '_into_' => $pic];
        $_trxD = TrxNonaktifDetail::select('kepada_property')->where($where)->groupBy('kepada_property')->get();
        foreach ($_trxD as $value) {
            // start
            // parameter yang dipassing ke sendemail
            // 9 = Submit to PIC GA
            $status = 9;
            $mailData['title'] = 'Formulir Karyawan Non Aktif - '.$pic;
            $mailData['filename'] = 'emails.nonaktif0';
            $_kepada = json_decode($value->kepada_property, true);
            $mailData['data'] = ['pesan' => 'Terdapat Formulir Karyawan Non Aktif yang harus anda review', 'nama'=>$_pengaju['nama'].'/'.$_pengaju['nik'], 'jabatan'=>$_pengaju['jabatan'], 'unit_kerja'=>$_pengaju['divisi'].'/'.$_pengaju['dep'].'/'.$_pengaju['sub_dep'], 'link'=> route('nonaktif.show', [(new OpenDataController)->encryptWithKey($trxM->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($_kepada['nik']), 'id_status' => (new OpenDataController)->encryptWithKey($status), 'pic' => (new OpenDataController)->encryptWithKey($pic)]), 'id_trx' => $trxM->id ];
            $mailData['to'] = $_kepada['email'];
            $todolist = ['id_trx' => $trxM->id, 'nik' => $_kepada['nik'], 'id_status' => $status, 'pic' => $pic];
            $this->insertToDoList($todolist, $mailData);
            $this->sendEmail($mailData);
            // finish
        }
        // _it
        $pic = 'it';
        $where = ['id_trx' => $trxM->id, '_into_' => $pic];
        $_trxD = TrxNonaktifDetail::select('kepada_property')->where($where)->groupBy('kepada_property')->get();
        foreach ($_trxD as $value) {
            // start
            // parameter yang dipassing ke sendemail
            // 13 = Submit to PIC IT
            $status = 13;
            $mailData['title'] = 'Formulir Karyawan Non Aktif - '.$pic;
            $mailData['filename'] = 'emails.nonaktif0';
            $_kepada = json_decode($value->kepada_property, true);
            $mailData['data'] = ['pesan' => 'Terdapat Formulir Karyawan Non Aktif yang harus anda review', 'nama'=>$_pengaju['nama'].'/'.$_pengaju['nik'], 'jabatan'=>$_pengaju['jabatan'], 'unit_kerja'=>$_pengaju['divisi'].'/'.$_pengaju['dep'].'/'.$_pengaju['sub_dep'], 'link'=> route('nonaktif.show', [(new OpenDataController)->encryptWithKey($trxM->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($_kepada['nik']), 'id_status' => (new OpenDataController)->encryptWithKey($status), 'pic' => (new OpenDataController)->encryptWithKey($pic)]), 'id_trx' => $trxM->id ];
            $mailData['to'] = $_kepada['email'];
            $todolist = ['id_trx' => $trxM->id, 'nik' => $_kepada['nik'], 'id_status' => $status, 'pic' => $pic];
            $this->insertToDoList($todolist, $mailData);
            $this->sendEmail($mailData);
            // finish
        }
        // _hc
        $pic = 'hc';
        $where = ['id_trx' => $trxM->id, '_into_' => $pic];
        $_trxD = TrxNonaktifDetail::select('kepada_property')->where($where)->groupBy('kepada_property')->get();
        foreach ($_trxD as $value) {
            // start
            // parameter yang dipassing ke sendemail
            // 21 = Submit to PIC HC
            $status = 21;
            $mailData['title'] = 'Formulir Karyawan Non Aktif - '.$pic;
            $mailData['filename'] = 'emails.nonaktif0';
            $_kepada = json_decode($value->kepada_property, true);
            $mailData['data'] = ['pesan' => 'Terdapat Formulir Karyawan Non Aktif yang harus anda review', 'nama'=>$_pengaju['nama'].'/'.$_pengaju['nik'], 'jabatan'=>$_pengaju['jabatan'], 'unit_kerja'=>$_pengaju['divisi'].'/'.$_pengaju['dep'].'/'.$_pengaju['sub_dep'], 'link'=> route('nonaktif.show', [(new OpenDataController)->encryptWithKey($trxM->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($_kepada['nik']), 'id_status' => (new OpenDataController)->encryptWithKey($status), 'pic' => (new OpenDataController)->encryptWithKey($pic)]), 'id_trx' => $trxM->id ];
            $mailData['to'] = $_kepada['email'];
            $todolist = ['id_trx' => $trxM->id, 'nik' => $_kepada['nik'], 'id_status' => $status, 'pic' => $pic];
            $this->insertToDoList($todolist, $mailData);
            $this->sendEmail($mailData);
            // finish
        }
        // _ko
        $pic = 'ko';
        $where = ['id_trx' => $trxM->id, '_into_' => $pic];
        $_trxD = TrxNonaktifDetail::select('kepada_property')->where($where)->groupBy('kepada_property')->get();
        foreach ($_trxD as $value) {
            // start
            // parameter yang dipassing ke sendemail
            // 17 = Submit to PIC KOPERASI
            $status = 17;
            $mailData['title'] = 'Formulir Karyawan Non Aktif - '.$pic;
            $mailData['filename'] = 'emails.nonaktif0';
            $_kepada = json_decode($value->kepada_property, true);
            $mailData['data'] = ['pesan' => 'Terdapat Formulir Karyawan Non Aktif yang harus anda review', 'nama'=>$_pengaju['nama'].'/'.$_pengaju['nik'], 'jabatan'=>$_pengaju['jabatan'], 'unit_kerja'=>$_pengaju['divisi'].'/'.$_pengaju['dep'].'/'.$_pengaju['sub_dep'], 'link'=> route('nonaktif.show', [(new OpenDataController)->encryptWithKey($trxM->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($_kepada['nik']), 'id_status' => (new OpenDataController)->encryptWithKey($status), 'pic' => (new OpenDataController)->encryptWithKey($pic)]), 'id_trx' => $trxM->id ];
            $mailData['to'] = $_kepada['email'];
            $todolist = ['id_trx' => $trxM->id, 'nik' => $_kepada['nik'], 'id_status' => $status, 'pic' => $pic];
            $this->insertToDoList($todolist, $mailData);
            $this->sendEmail($mailData);
            // finish
        }
        // _am
        $pic = 'am';
        $where = ['id_trx' => $trxM->id, '_into_' => $pic];
        $_trxD = TrxNonaktifDetail::select('kepada_property')->where($where)->groupBy('kepada_property')->get();
        foreach ($_trxD as $value) {
            // start
            // parameter yang dipassing ke sendemail
            // 27 = Submit to PIC Application Management
            $status = 27;
            $mailData['title'] = 'Formulir Karyawan Non Aktif - '.$pic;
            $mailData['filename'] = 'emails.nonaktif0';
            $_kepada = json_decode($value->kepada_property, true);
            $mailData['data'] = ['pesan' => 'Terdapat Formulir Karyawan Non Aktif yang harus anda review', 'nama'=>$_pengaju['nama'].'/'.$_pengaju['nik'], 'jabatan'=>$_pengaju['jabatan'], 'unit_kerja'=>$_pengaju['divisi'].'/'.$_pengaju['dep'].'/'.$_pengaju['sub_dep'], 'link'=> route('nonaktif.show', [(new OpenDataController)->encryptWithKey($trxM->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($_kepada['nik']), 'id_status' => (new OpenDataController)->encryptWithKey($status), 'pic' => (new OpenDataController)->encryptWithKey($pic)]), 'id_trx' => $trxM->id ];
            $mailData['to'] = $_kepada['email'];
            $todolist = ['id_trx' => $trxM->id, 'nik' => $_kepada['nik'], 'id_status' => $status, 'pic' => $pic];
            $this->insertToDoList($todolist, $mailData);
            $this->sendEmail($mailData);
            // finish
        }
        // return response()->json( [ '_hasil' => $_hasil['nama'] ] );die;
        return redirect('nonaktif')->with('msg', 'Data is saved successfully.');
    }

    public function getNikProperty($nik)
    {
        $where = [ 'nik' => $nik ];
        $data = Karyawan::select('mtr_tbl_karyawan.nik','mtr_tbl_karyawan.nama','mtr_tbl_karyawan.email','mtr_tbl_perusahaan.nama as perusahaan','mtr_tbl_divisi.nama as divisi','mtr_tbl_departemen.nama as dep','mtr_tbl_sub_departemen.nama as sub_dep','mtr_tbl_level.level as level','mtr_tbl_jabatan.jabatan as jabatan','mtr_tbl_level.id as id_level','mtr_tbl_karyawan.tanggal_bergabung','mtr_tbl_karyawan.tanggal_berakhir','mtr_tbl_karyawan.tanggal_karyawan_tetap')
        ->leftJoin('mtr_tbl_perusahaan', 'mtr_tbl_perusahaan.id', '=', 'mtr_tbl_karyawan.kode_perusahaan')
        ->leftJoin('mtr_tbl_divisi', 'mtr_tbl_divisi.id', '=', 'mtr_tbl_karyawan.kode_divisi')
        ->leftJoin('mtr_tbl_departemen', 'mtr_tbl_departemen.id', '=', 'mtr_tbl_karyawan.kode_departemen')
        ->leftJoin('mtr_tbl_sub_departemen', 'mtr_tbl_sub_departemen.id', '=', 'mtr_tbl_karyawan.kode_sub_departemen')
        ->leftJoin('mtr_tbl_level', 'mtr_tbl_level.id', '=', 'mtr_tbl_karyawan.kode_level')
        ->leftJoin('mtr_tbl_jabatan', 'mtr_tbl_jabatan.id', '=', 'mtr_tbl_karyawan.kode_jabatan')
        ->where($where)->first();
        return json_encode( $data );
    }

    public function insertLog($id_trx, $id_status, $nik_pic, $keterangan)
    {
        $data = new TrxNonaktifLog;
        $data->id_trx = $id_trx;
        $data->id_status = $id_status;
        $data->nik_pic = $nik_pic;
        $data->keterangan = $keterangan;
        // 31 = Statement Of Compliance
        if($id_status == 31){
            $data->keterangan = 1;
            $data->created_at = $keterangan;
            $data->updated_at = $keterangan;
        }
        $data->save();
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

    public function sendEmail($mailData)
    {
        $ccEmails = explode(',', MtrUtility::find(11)->informasi);
		if($mailData['title'] == 'Formulir Karyawan Non Aktif - it'){
			$ccEmails = explode(',', MtrUtility::find(19)->informasi);
		}
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
        $linkAction = route('nonaktif.update', $id);
        $id = (new OpenDataController)->decryptWithKey($id);
        $where = ['id_trx' => $id];
        $history = TrxNonaktifLog::where($where)->orderBy('created_at', 'desc')->get();
        $trxM = TrxNonaktifMaster::find($id);
        $where = ['nik' => $trxM->pengaju];
        $karyawan_properties = Karyawan::where($where)->first();
        // 
        $pengaju = json_decode($trxM->pengaju_property, true);
        // 
        $view = $request->view;
        $pic = $request->pic;
        $nik = $request->nik;
        $id_status = $request->id_status;
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
        // 
        $where = ['id_trx' => $id, 'nik_pic' => $nik];
        // di
        // 5 = Submit to PIC DIVISI
        // 6 = Reject by PIC DIVISI
        // 7 = Resubmit to PIC DIVISI
        // 8 = Approve by PIC DIVISI
        // $nDI = TrxNonaktifLog::where($where)->whereIn('id_status', [6,8])->count();
        $nDI = false;
        $log = TrxNonaktifLog::where($where)->whereIn('id_status', [5, 6, 7, 8])->orderBy('id', 'desc')->first();
        if(!empty($log) && in_array($log->id_status, [5, 7])){
            $nDI = true;
        }else if(empty($log)){
            $nDI = true;
        }
        // ga
        // 9 = Submit to PIC GA
        // 10 = Reject by PIC GA
        // 11 = Resubmit to PIC GA
        // 12 = Approve by PIC GA
        // $nGA = TrxNonaktifLog::where($where)->whereIn('id_status', [10,12])->count();
        $nGA = false;
        $log = TrxNonaktifLog::where($where)->whereIn('id_status', [9, 10, 11, 12])->orderBy('id', 'desc')->first();
        if(!empty($log) && in_array($log->id_status, [9, 11])){
            $nGA = true;
        }else if(empty($log)){
            $nGA = true;
        }
        // it
        // 13 = Submit to PIC IT
        // 14 = Reject by PIC IT
        // 15 = Resubmit to PIC IT
        // 16 = Approve by PIC IT
        // $nIT = TrxNonaktifLog::where($where)->whereIn('id_status', [14,16])->count();
        $nIT = false;
        $log = TrxNonaktifLog::where($where)->whereIn('id_status', [13, 14, 15, 16])->orderBy('id', 'desc')->first();
        if(!empty($log) && in_array($log->id_status, [13, 15])){
            $nIT = true;
        }else if(empty($log)){
            $nIT = true;
        }
        // hc
        // 21 = Submit to PIC HC
        // 22 = Reject by PIC HC
        // 23 = Resubmit to PIC HC
        // 24 = Approve by PIC HC
        // $nHC = TrxNonaktifLog::where($where)->whereIn('id_status', [22,24])->count();
        $nHC = false;
        $log = TrxNonaktifLog::where($where)->whereIn('id_status', [21, 22, 23, 24])->orderBy('id', 'desc')->first();
        if(!empty($log) && in_array($log->id_status, [21, 23])){
            $nHC = true;
        }else if(empty($log)){
            $nHC = true;
        }
        // ko
        // 17 = Submit to PIC KOPERASI
        // 18 = Reject by PIC KOPERASI
        // 19 = Resubmit to PIC KOPERASI
        // 20 = Approve by PIC KOPERASI
        // $nKO = TrxNonaktifLog::where($where)->whereIn('id_status', [18,20])->count();
        $nKO = false;
        $log = TrxNonaktifLog::where($where)->whereIn('id_status', [17, 18, 19, 20])->orderBy('id', 'desc')->first();
        if(!empty($log) && in_array($log->id_status, [17, 19])){
            $nKO = true;
        }else if(empty($log)){
            $nKO = true;
        }
        // am
        // 27 = Submit to PIC Application Management
        // 28 = Reject by PIC Application Management
        // 29 = Resubmit to PIC Application Management
        // 30 = Approve by PIC Application Management
        // $nAM = TrxNonaktifLog::where($where)->whereIn('id_status', [28,30])->count();
        $nAM = false;
        $log = TrxNonaktifLog::where($where)->whereIn('id_status', [27, 28, 29, 30])->orderBy('id', 'desc')->first();
        if(!empty($log) && in_array($log->id_status, [27, 29])){
            $nAM = true;
        }else if(empty($log)){
            $nAM = true;
        }
        // atasan
        // 25 = Submit to L1
        // 2 = Reject by L1
        // 4 = Done All
        $nAtasan = TrxNonaktifLog::where($where)->whereIn('id_status', [2,4])->count();
        // 
        switch ($view) {
            case 'approval':
                // di
                if ($pic == 'di' && $nDI) {
                    // 6 = Reject by PIC DIVISI
                    // 8 = Approve by PIC DIVISI
                    $approve = 8;
                    $reject = 6;
                    $where = ['id_trx' => $id, '_into_' => $pic, 'kepada' => $nik];
                    $trxD = TrxNonaktifDetail::where($where)->get();
                    return view('emails.nonaktif', compact('trxM', 'trxD','nik', 'pic', 'approve', 'reject', 'pengaju', 'linkAction', 'history'));
                }
                // ga
                else if ($pic == 'ga' && $nGA) {
                    // 10 = Reject by PIC GA
                    // 12 = Approve by PIC GA
                    $approve = 12;
                    $reject = 10;
                    $where = ['id_trx' => $id, '_into_' => $pic, 'kepada' => $nik];
                    $trxD = TrxNonaktifDetail::where($where)->get();
                    return view('emails.nonaktif', compact('trxM', 'trxD','nik', 'pic', 'approve', 'reject', 'pengaju', 'linkAction', 'history'));
                }
                // it
                else if ($pic == 'it' && $nIT) {
                    // 14 = Reject by PIC IT
                    // 16 = Approve by PIC IT
                    $approve = 16;
                    $reject = 14;
                    $where = ['id_trx' => $id, '_into_' => $pic, 'kepada' => $nik];
                    $trxD = TrxNonaktifDetail::where($where)->get();
                    return view('emails.nonaktif', compact('trxM', 'trxD','nik', 'pic', 'approve', 'reject', 'pengaju', 'linkAction', 'history'));
                }
                // hc
                else if ($pic == 'hc' && $nHC) {
                    // 22 = Reject by PIC HC
                    // 24 = Approve by PIC HC
                    $approve = 24;
                    $reject = 22;
                    $where = ['id_trx' => $id, '_into_' => $pic, 'kepada' => $nik];
                    $trxD = TrxNonaktifDetail::where($where)->get();
                    return view('emails.nonaktif', compact('trxM', 'trxD','nik', 'pic', 'approve', 'reject', 'pengaju', 'linkAction', 'history'));
                }
                // ko
                else if ($pic == 'ko' && $nKO) {
                    // 18 = Reject by PIC KOPERASI
                    // 20 = Approve by PIC KOPERASI
                    $approve = 20;
                    $reject = 18;
                    $where = ['id_trx' => $id, '_into_' => $pic, 'kepada' => $nik];
                    $trxD = TrxNonaktifDetail::where($where)->get();
                    return view('emails.nonaktif', compact('trxM', 'trxD','nik', 'pic', 'approve', 'reject', 'pengaju', 'linkAction', 'history'));
                }
                // am
                else if ($pic == 'am' && $nAM) {
                    // 28 = Reject by PIC Application Management
                    // 30 = Approve by PIC Application Management
                    $approve = 30;
                    $reject = 28;
                    $where = ['id_trx' => $id, '_into_' => $pic, 'kepada' => $nik];
                    $trxD = TrxNonaktifDetail::where($where)->get();
                    return view('emails.nonaktif', compact('trxM', 'trxD','nik', 'pic', 'approve', 'reject', 'pengaju', 'linkAction', 'history'));
                }
                // atasan
                // 25 = Submit to L1
                else if ($pic == 'atasan' && $nAtasan == 0 && $id_status == 25) {
                    // 2 = Reject by L1
                    // 4 = Done All
                    $approve = 4;
                    $reject = 2;
                    $where = ['id_trx' => $id];
                    $trxD = TrxNonaktifDetail::where($where)->get();
                    return view('emails.nonaktif', compact('trxM', 'trxD','nik', 'pic', 'approve', 'reject', 'pengaju', 'linkAction', 'history'));
                }
                // selainnya
                else {
                    return view('emails.nonaktif_ok');
                }
                break;
            case 'detail':
                $this->authorize('auth');
                // 
                $where = ['id_trx' => $id];
                $history = TrxNonaktifLog::where($where)->orderBy('created_at', 'desc')->get();
                $where = ['id_trx' => $id];
                $trxD = TrxNonaktifDetail::where($where)->get();
                // 
                return view('formulir.nonaktif.view', compact('history', 'trxM', 'trxD', 'pengaju', 'karyawan_properties'));
                break;
            case 'pdf':
                $this->authorize('auth');
                // print to pdf
                // return response()->json( [ 'asset' => public_path('assets\images\matta\approved_new.png') ] );die;
                $waktu = date('YmdHis');
                $no = 0;
                $where = ['id_trx' => $id];
                $trxD = TrxNonaktifDetail::where($where)->get();
                // 
                $karyawan = json_decode($trxM->pengaju_property, true);
                $atasan = json_decode($trxM->atasan_property, true);
                // di
                $where = ['id_trx' => $id, '_into_' => 'di'];
                $trxD_di = TrxNonaktifDetail::where($where)->get();
                // ga
                $where = ['id_trx' => $id, '_into_' => 'ga'];
                $trxD_ga = TrxNonaktifDetail::where($where)->get();
                // it
                $where = ['id_trx' => $id, '_into_' => 'it'];
                $trxD_it = TrxNonaktifDetail::where($where)->get();
                // hc
                $where = ['id_trx' => $id, '_into_' => 'hc'];
                $trxD_hc = TrxNonaktifDetail::where($where)->get();
                // ko
                $where = ['id_trx' => $id, '_into_' => 'ko'];
                $trxD_ko = TrxNonaktifDetail::where($where)->get();
                // am
                $where = ['id_trx' => $id, '_into_' => 'am'];
                $trxD_am = TrxNonaktifDetail::where($where)->get();
                // dd($trxD_am);
                // start
                $arr_htmlLog = array();
                // $htmlLog = '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0"><tr>';
                $htmlLog = '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">';
                // karyawan
                $where = ['id_trx' => $id, 'nik_pic' => $trxM->pengaju, 'id_status' => 1];
                $nonaktifLog = TrxNonaktifLog::where($where)->orderBy('id', 'desc')->first();
                $_htmlLog = '<td>Mengajukan<br/><img src="' . public_path('assets/images/matta/requested_new.png') . '" height="30" /><br/>' . $karyawan['nama'] . '<br/>' . $karyawan['jabatan'] . '<br/>' . $nonaktifLog->created_at . '</td>';
                array_push($arr_htmlLog, $_htmlLog);
                // _di
                $pic = 'di';
                $where = ['id_trx' => $trxM->id, '_into_' => $pic];
                $_trxD = TrxNonaktifDetail::select('kepada_property')->where($where)->groupBy('kepada_property')->get();
                foreach ($_trxD as $value) {
                    // 6 = Reject by PIC DIVISI
                    // 8 = Approve by PIC DIVISI
                    $kepada = json_decode($value->kepada_property, true);
                    $where = ['id_trx' => $id, 'nik_pic' => $kepada['nik']];
                    $nonaktifLog = TrxNonaktifLog::where($where)->whereIn('id_status', [6,8])->orderBy('id', 'desc')->first();
                    if (!empty($nonaktifLog)) {
                        if($nonaktifLog->id_status == 8){
                            $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $kepada['nama'] . '<br/>' . $kepada['jabatan'] . '<br/>' . $nonaktifLog->created_at . '</td>';
                            array_push($arr_htmlLog, $_htmlLog);
                        }else if($nonaktifLog->id_status == 6){
                            $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $kepada['nama'] . '<br/>' . $kepada['jabatan'] . '<br/>' . $nonaktifLog->created_at . '</td>';
                            array_push($arr_htmlLog, $_htmlLog);
                        }
                    }
                }
                // _ga
                $pic = 'ga';
                $where = ['id_trx' => $trxM->id, '_into_' => $pic];
                $_trxD = TrxNonaktifDetail::select('kepada_property')->where($where)->groupBy('kepada_property')->get();
                foreach ($_trxD as $value) {
                    // 10 = Reject by PIC GA
                    // 12 = Approve by PIC GA
                    $kepada = json_decode($value->kepada_property, true);
                    $where = ['id_trx' => $id, 'nik_pic' => $kepada['nik']];
                    $nonaktifLog = TrxNonaktifLog::where($where)->whereIn('id_status', [10,12])->orderBy('id', 'desc')->first();
                    if (!empty($nonaktifLog)) {
                        if($nonaktifLog->id_status == 12){
                            $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $kepada['nama'] . '<br/>' . $kepada['jabatan'] . '<br/>' . $nonaktifLog->created_at . '</td>';
                            array_push($arr_htmlLog, $_htmlLog);
                        }else if($nonaktifLog->id_status == 10){
                            $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $kepada['nama'] . '<br/>' . $kepada['jabatan'] . '<br/>' . $nonaktifLog->created_at . '</td>';
                            array_push($arr_htmlLog, $_htmlLog);
                        }
                    }
                }
                // _it
                $pic = 'it';
                $where = ['id_trx' => $trxM->id, '_into_' => $pic];
                $_trxD = TrxNonaktifDetail::select('kepada_property')->where($where)->groupBy('kepada_property')->get();
                foreach ($_trxD as $value) {
                    // 14 = Reject by PIC IT
                    // 16 = Approve by PIC IT
                    $kepada = json_decode($value->kepada_property, true);
                    $where = ['id_trx' => $id, 'nik_pic' => $kepada['nik']];
                    $nonaktifLog = TrxNonaktifLog::where($where)->whereIn('id_status', [14,16])->orderBy('id', 'desc')->first();
                    if (!empty($nonaktifLog)) {
                        if($nonaktifLog->id_status == 16){
                            $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $kepada['nama'] . '<br/>' . $kepada['jabatan'] . '<br/>' . $nonaktifLog->created_at . '</td>';
                            array_push($arr_htmlLog, $_htmlLog);
                        }else if($nonaktifLog->id_status == 14){
                            $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $kepada['nama'] . '<br/>' . $kepada['jabatan'] . '<br/>' . $nonaktifLog->created_at . '</td>';
                            array_push($arr_htmlLog, $_htmlLog);
                        }
                    }
                }
                // _hc
                $pic = 'hc';
                $where = ['id_trx' => $trxM->id, '_into_' => $pic];
                $_trxD = TrxNonaktifDetail::select('kepada_property')->where($where)->groupBy('kepada_property')->get();
                foreach ($_trxD as $value) {
                    // 22 = Reject by PIC HC
                    // 24 = Approve by PIC HC
                    $kepada = json_decode($value->kepada_property, true);
                    $where = ['id_trx' => $id, 'nik_pic' => $kepada['nik']];
                    $nonaktifLog = TrxNonaktifLog::where($where)->whereIn('id_status', [22,24])->orderBy('id', 'desc')->first();
                    if (!empty($nonaktifLog)) {
                        if($nonaktifLog->id_status == 24){
                            $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $kepada['nama'] . '<br/>' . $kepada['jabatan'] . '<br/>' . $nonaktifLog->created_at . '</td>';
                            array_push($arr_htmlLog, $_htmlLog);
                        }else if($nonaktifLog->id_status == 22){
                            $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $kepada['nama'] . '<br/>' . $kepada['jabatan'] . '<br/>' . $nonaktifLog->created_at . '</td>';
                            array_push($arr_htmlLog, $_htmlLog);
                        }
                    }
                }
                // _ko
                $pic = 'ko';
                $where = ['id_trx' => $trxM->id, '_into_' => $pic];
                $_trxD = TrxNonaktifDetail::select('kepada_property')->where($where)->groupBy('kepada_property')->get();
                foreach ($_trxD as $value) {
                    // 18 = Reject by PIC KOPERASI
                    // 20 = Approve by PIC KOPERASI
                    $kepada = json_decode($value->kepada_property, true);
                    $where = ['id_trx' => $id, 'nik_pic' => $kepada['nik']];
                    $nonaktifLog = TrxNonaktifLog::where($where)->whereIn('id_status', [18,20])->orderBy('id', 'desc')->first();
                    if (!empty($nonaktifLog)) {
                        if($nonaktifLog->id_status == 20){
                            $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $kepada['nama'] . '<br/>' . $kepada['jabatan'] . '<br/>' . $nonaktifLog->created_at . '</td>';
                            array_push($arr_htmlLog, $_htmlLog);
                        }else if($nonaktifLog->id_status == 18){
                            $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $kepada['nama'] . '<br/>' . $kepada['jabatan'] . '<br/>' . $nonaktifLog->created_at . '</td>';
                            array_push($arr_htmlLog, $_htmlLog);
                        }
                    }
                }
                // _am
                $pic = 'am';
                $where = ['id_trx' => $trxM->id, '_into_' => $pic];
                $_trxD = TrxNonaktifDetail::select('kepada_property')->where($where)->groupBy('kepada_property')->get();
                foreach ($_trxD as $value) {
                    // 28 = Reject by PIC Application Management
                    // 30 = Approve by PIC Application Management
                    $kepada = json_decode($value->kepada_property, true);
                    $where = ['id_trx' => $id, 'nik_pic' => $kepada['nik']];
                    $nonaktifLog = TrxNonaktifLog::where($where)->whereIn('id_status', [28,30])->orderBy('id', 'desc')->first();
                    if (!empty($nonaktifLog)) {
                        if($nonaktifLog->id_status == 30){
                            $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $kepada['nama'] . '<br/>' . $kepada['jabatan'] . '<br/>' . $nonaktifLog->created_at . '</td>';
                            array_push($arr_htmlLog, $_htmlLog);
                        }else if($nonaktifLog->id_status == 28){
                            $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $kepada['nama'] . '<br/>' . $kepada['jabatan'] . '<br/>' . $nonaktifLog->created_at . '</td>';
                            array_push($arr_htmlLog, $_htmlLog);
                        }
                    }
                }
                // atasan
                // 2 = Reject by L1
                // 4 = Done All
                $where = ['id_trx' => $id, 'nik_pic' => $trxM->atasan];
                $nonaktifLog = TrxNonaktifLog::where($where)->whereIn('id_status', [2, 4])->orderBy('id', 'desc')->first();
                if (!empty($nonaktifLog)) {
                    if($nonaktifLog->id_status == 4){
                        $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $atasan['nama'] . '<br/>' . $atasan['jabatan'] . '<br/>' . $nonaktifLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }else if($nonaktifLog->id_status == 2){
                        $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $atasan['nama'] . '<br/>' . $atasan['jabatan'] . '<br/>' . $nonaktifLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }
                }
                // // hco
                // $where = ['id_trx' => $id];
                // $nonaktifLog = TrxNonaktifLog::where($where)->whereIn('id_status', [5, 10])->orderBy('id', 'desc')->first();
                // if (!empty($nonaktifLog)) {
                    // $_htmlLog = '<td>Mengetahui<br/><img src="' . public_path('assets/images/matta/informed_new.png') . '" height="30" /><br/>' . $nonaktifLog->_pic->nama . '<br/>' . $nonaktifLog->_pic->_jabatan->jabatan . '<br/>' . $nonaktifLog->created_at . '</td>';
                    // array_push($arr_htmlLog, $_htmlLog);
                // }
                // finish
                // $htmlLog .= '</tr></table>';
                // start looping satu baris terdiri dari $pembagi
                // jumlah ttd dalam 1 baris
                $pembagi = 4;
                $jumlah = count($arr_htmlLog);
                $sisa = $jumlah % $pembagi;
                $hasil = ($jumlah - $sisa) / $pembagi;
                if($hasil > 0){
                    for ($y = 0; $y < $hasil; $y++) {
                        $htmlLog .= '<tr>';
                        for ($x = $y*$pembagi; $x < ($y+1)*$pembagi; $x++) {
                            $htmlLog .= $arr_htmlLog[$x];
                        }
                        $htmlLog .= '</tr>';
                    }
                    if($sisa>0){
                        $htmlLog .= '<tr>';
                        for ($x = $hasil*$pembagi; $x < $jumlah; $x++) {
                            $htmlLog .= $arr_htmlLog[$x];
                        }
                        $htmlLog .= '</tr>';
                    }
                }else{
                    $htmlLog .= '<tr>';
                    foreach($arr_htmlLog as $value){
                        $htmlLog .= $value;
                    }
                    $htmlLog .= '</tr>';
                }
                $htmlLog .= '</table>';
                // end looping satu baris terdiri dari $pembagi
                // 
                $filename = $waktu . '_formulir_nonaktif.pdf';
                $view = \View::make('formulir.nonaktif.pdf', compact('trxM', 'trxD', 'karyawan', 'karyawan_properties','atasan', 'trxD_di', 'trxD_ga', 'trxD_it', 'trxD_hc', 'trxD_ko', 'trxD_am', 'htmlLog'));
                $html = $view->render();
                $pdf = new TCPDF;
                $pdf::SetTitle('Formulir Karyawan Non Aktif');
                $pdf::SetFont('times', '', 10, '', true);
                $pdf::AddPage();
                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output(public_path($filename), 'I');
                return response()->download(public_path($filename));
                break;
            case 'sanggup':
                $this->authorize('auth');
                $trxM = TrxNonaktifMaster::find($id);
                // 31 = Statement Of Compliance
                $trxLog = TrxNonaktifLog::where(['id_trx'=>$id, 'id_status'=>31])->first();
                // 
                $filename = $id . '_surat_pernyataan_kesanggupan.pdf';
                $view = \View::make('formulir.nonaktif.pdf_sanggup', compact('trxM', 'trxLog'));
                $html = $view->render();
                $pdf = new TCPDF;
                $pdf::SetTitle('Surat Pernyataan Kesanggupan');
                $pdf::SetFont('times', '', 10, '', true);
                $pdf::AddPage();
                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output(public_path($filename), 'I');
                return response()->download(public_path($filename));
                break;
            default:
                return view('emails.nonaktif_ok');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $this->authorize('auth');
        $id = (new OpenDataController)->decryptWithKey($id);
        // dd($request->all(), $id);
        $trxD = TrxNonaktifDetail::find($id);
        $back_url = $request->back_url;
        // di = divisi
        $where = ['_into_' => 'di'];
        $obyek_di = NonaktifObyek::where($where)->orderBy('obyek', 'asc')->get();
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        // ga
        $where = ['_into_' => 'ga'];
        $obyek_ga = NonaktifObyek::where($where)->orderBy('obyek', 'asc')->get();
        $where = ['is_aktif' => 1, 'nik' => MtrUtility::find(13)->informasi];
        $pic_ga = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        // it
        $where = ['_into_' => 'it'];
        $obyek_it = NonaktifObyek::where($where)->orderBy('obyek', 'asc')->get();
        $where = ['is_aktif' => 1, 'nik' => MtrUtility::find(14)->informasi];
        $pic_it = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        // hc
        $where = ['_into_' => 'hc'];
        $obyek_hc = NonaktifObyek::where($where)->orderBy('obyek', 'asc')->get();
        $where = ['is_aktif' => 1, 'nik' => MtrUtility::find(15)->informasi];
        $pic_hc = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        // ko = koperasi
        $where = ['_into_' => 'ko'];
        $obyek_ko = NonaktifObyek::where($where)->orderBy('obyek', 'asc')->get();
        $where = ['is_aktif' => 1, 'nik' => MtrUtility::find(16)->informasi];
        $pic_ko = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        // am = Application Management
        $where = ['_into_' => 'am'];
        $obyek_am = NonaktifObyek::where($where)->orderBy('obyek', 'asc')->get();
        $where = ['is_aktif' => 1, 'nik' => MtrUtility::find(42)->informasi];
        $pic_am = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        // 
        return view('formulir.nonaktif.edit', compact('back_url', 'trxD', 'karyawan', 'obyek_di', 'pic_ga', 'obyek_ga', 'pic_it', 'obyek_it', 'pic_hc', 'obyek_hc', 'pic_ko', 'obyek_ko', 'pic_am', 'obyek_am'));
    }
    
    public function save_edit(Request $request, $id)
    {
        // dd($request->all(), $id);
        $this->authorize('auth');
        $id = (new OpenDataController)->decryptWithKey($id);
        $trxD = TrxNonaktifDetail::find($id);
        $trxD->kepada = $request->kepada;
        $trxD->obyek = $request->obyek;
        $trxD->keterangan = $request->keterangan;
        $trxD->kepada_property = $this->getNikProperty($request->kepada);
        $trxD->update();
        $trxM = TrxNonaktifMaster::find( $trxD->id_trx );
        $status = 0;
        $keterangan = '';
        $pic = $trxD->_into_;
        if($pic == 'di'){
            $status = 7;
            $keterangan = 'Resubmit to PIC DIVISI';
        }else if($pic == 'ga'){
            $status = 11;
            $keterangan = 'Resubmit to PIC GA';
        }else if($pic == 'it'){
            $status = 15;
            $keterangan = 'Resubmit to PIC IT';
        }else if($pic == 'ko'){
            $status = 19;
            $keterangan = 'Resubmit to PIC KOPERASI';
        }else if($pic == 'am'){
            $status = 29;
            $keterangan = 'Resubmit to PIC Application Management';
        }else if($pic == 'hc'){
            $status = 23;
            $keterangan = 'Resubmit to PIC HC';
        }
        $trxM->status = $status;
        $trxM->update();
        $this->insertLog($trxD->id_trx, $status, $request->kepada, $keterangan);
        $this->deleteToDoList(['id_trx'=>$trxD->id_trx, 'nik'=>$trxM->pengaju, 'pic'=>$trxD->_into_, 'filename'=>'emails.nonaktif0']);
        // start
        // parameter yang dipassing ke sendemail
        $mailData['title'] = 'Formulir Karyawan Non Aktif - '.$pic;
        $mailData['filename'] = 'emails.nonaktif0';
        $_pengaju = json_decode($trxM->pengaju_property, true);
        $_kepada = json_decode($trxD->kepada_property, true);
        $mailData['data'] = ['pesan' => 'Terdapat Formulir Karyawan Non Aktif yang harus anda review', 'nama'=>$_pengaju['nama'].'/'.$_pengaju['nik'], 'jabatan'=>$_pengaju['jabatan'], 'unit_kerja'=>$_pengaju['divisi'].'/'.$_pengaju['dep'].'/'.$_pengaju['sub_dep'], 'link'=> route('nonaktif.show', [(new OpenDataController)->encryptWithKey($trxM->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($_kepada['nik']), 'id_status' => (new OpenDataController)->encryptWithKey($status), 'pic' => (new OpenDataController)->encryptWithKey($pic)]), 'id_trx' => $trxM->id ];
        $mailData['to'] = $_kepada['email'];
        $todolist = ['id_trx' => $trxM->id, 'nik' => $_kepada['nik'], 'id_status' => $status, 'pic' => $pic];
        $this->insertToDoList($todolist, $mailData);
        $this->sendEmail($mailData);
        // finish
        return redirect()->to($request->back_url)->with( 'msg', 'Data is saved successfully.' );
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
        $trxM = TrxNonaktifMaster::find($id);
        $pic = $request->pic;
        $nik = $request->nik;
        $status = $request->status;
        $keterangan = $request->keterangan;
        // 
        $karyawan = json_decode($trxM->pengaju_property, true);
        $atasan = json_decode($trxM->atasan_property, true);
        // 
        $where = ['id_trx' => $id, 'kepada' => $nik];
        $trxD = TrxNonaktifDetail::select('kepada')->where($where)->get();
        $kepada = array();
        foreach ($trxD as $value) {
            array_push($kepada, $value->kepada);
        }
        // 
        // di
        // 5 = Submit to PIC DIVISI
        // 6 = Reject by PIC DIVISI
        // 8 = Approve by PIC DIVISI
        if (($status == 6 || $status == 8) && $pic == 'di' && in_array($nik, $kepada)) {
            $trxM->status = $status;
            $trxM->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.nonaktif0']);
            $this->insertLog($id, $status, $nik, $keterangan);
            $this->checkApproval($id, $trxM);
            if($status == 6){
                // start
                // parameter yang dipassing ke sendemail
                $mailData['title'] = 'Formulir Karyawan Non Aktif - '.$pic;
                $mailData['filename'] = 'emails.nonaktif0';
                $_pengaju = json_decode($trxM->pengaju_property, true);
                $mailData['data'] = ['pesan' => 'Formulir Karyawan Non Aktif yang anda ajukan harus direvisi', 'nama'=>$_pengaju['nama'].'/'.$_pengaju['nik'], 'jabatan'=>$_pengaju['jabatan'], 'unit_kerja'=>$_pengaju['divisi'].'/'.$_pengaju['dep'].'/'.$_pengaju['sub_dep'], 'link'=> route('nonaktif.show', [(new OpenDataController)->encryptWithKey($trxM->id), 'view' => (new OpenDataController)->encryptWithKey('detail')]), 'id_trx' => $trxM->id ];
                $mailData['to'] = $_pengaju['email'];
                $todolist = ['id_trx' => $trxM->id, 'nik' => $_pengaju['nik'], 'id_status' => $status, 'pic' => $pic];
                $this->insertToDoList($todolist, $mailData);
                $this->sendEmail($mailData);
                // finish
            }
            return view('emails.nonaktif_ok');
        }
        // ga
        // 9 = Submit to PIC GA
        // 10 = Reject by PIC GA
        // 12 = Approve by PIC GA
        else if (($status == 10 || $status == 12) && $pic == 'ga' && in_array($nik, $kepada)) {
            $trxM->status = $status;
            $trxM->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.nonaktif0']);
            $this->insertLog($id, $status, $nik, $keterangan);
            $this->checkApproval($id, $trxM);
            if($status == 10){
                // start
                // parameter yang dipassing ke sendemail
                $mailData['title'] = 'Formulir Karyawan Non Aktif - '.$pic;
                $mailData['filename'] = 'emails.nonaktif0';
                $_pengaju = json_decode($trxM->pengaju_property, true);
                $mailData['data'] = ['pesan' => 'Formulir Karyawan Non Aktif yang anda ajukan harus direvisi', 'nama'=>$_pengaju['nama'].'/'.$_pengaju['nik'], 'jabatan'=>$_pengaju['jabatan'], 'unit_kerja'=>$_pengaju['divisi'].'/'.$_pengaju['dep'].'/'.$_pengaju['sub_dep'], 'link'=> route('nonaktif.show', [(new OpenDataController)->encryptWithKey($trxM->id), 'view' => (new OpenDataController)->encryptWithKey('detail')]), 'id_trx' => $trxM->id ];
                $mailData['to'] = $_pengaju['email'];
                $todolist = ['id_trx' => $trxM->id, 'nik' => $_pengaju['nik'], 'id_status' => $status, 'pic' => $pic];
                $this->insertToDoList($todolist, $mailData);
                $this->sendEmail($mailData);
                // finish
            }
            return view('emails.nonaktif_ok');
        }
        // it
        // 13 = Submit to PIC IT
        // 14 = Reject by PIC IT
        // 16 = Approve by PIC IT
        else if (($status == 14 || $status == 16) && $pic == 'it' && in_array($nik, $kepada)) {
            $trxM->status = $status;
            $trxM->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.nonaktif0']);
            $this->insertLog($id, $status, $nik, $keterangan);
            $this->checkApproval($id, $trxM);
            if($status == 14){
                // start
                // parameter yang dipassing ke sendemail
                $mailData['title'] = 'Formulir Karyawan Non Aktif - '.$pic;
                $mailData['filename'] = 'emails.nonaktif0';
                $_pengaju = json_decode($trxM->pengaju_property, true);
                $mailData['data'] = ['pesan' => 'Formulir Karyawan Non Aktif yang anda ajukan harus direvisi', 'nama'=>$_pengaju['nama'].'/'.$_pengaju['nik'], 'jabatan'=>$_pengaju['jabatan'], 'unit_kerja'=>$_pengaju['divisi'].'/'.$_pengaju['dep'].'/'.$_pengaju['sub_dep'], 'link'=> route('nonaktif.show', [(new OpenDataController)->encryptWithKey($trxM->id), 'view' => (new OpenDataController)->encryptWithKey('detail')]), 'id_trx' => $trxM->id ];
                $mailData['to'] = $_pengaju['email'];
                $todolist = ['id_trx' => $trxM->id, 'nik' => $_pengaju['nik'], 'id_status' => $status, 'pic' => $pic];
                $this->insertToDoList($todolist, $mailData);
                $this->sendEmail($mailData);
                // finish
            }
            return view('emails.nonaktif_ok');
        }
        // hc
        // 21 = Submit to PIC HC
        // 22 = Reject by PIC HC
        // 24 = Approve by PIC HC
        else if (($status == 22 || $status == 24) && $pic == 'hc' && in_array($nik, $kepada)) {
            $trxM->status = $status;
            $trxM->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.nonaktif0']);
            $this->insertLog($id, $status, $nik, $keterangan);
            $this->checkApproval($id, $trxM);
            if($status == 22){
                // start
                // parameter yang dipassing ke sendemail
                $mailData['title'] = 'Formulir Karyawan Non Aktif - '.$pic;
                $mailData['filename'] = 'emails.nonaktif0';
                $_pengaju = json_decode($trxM->pengaju_property, true);
                $mailData['data'] = ['pesan' => 'Formulir Karyawan Non Aktif yang anda ajukan harus direvisi', 'nama'=>$_pengaju['nama'].'/'.$_pengaju['nik'], 'jabatan'=>$_pengaju['jabatan'], 'unit_kerja'=>$_pengaju['divisi'].'/'.$_pengaju['dep'].'/'.$_pengaju['sub_dep'], 'link'=> route('nonaktif.show', [(new OpenDataController)->encryptWithKey($trxM->id), 'view' => (new OpenDataController)->encryptWithKey('detail')]), 'id_trx' => $trxM->id ];
                $mailData['to'] = $_pengaju['email'];
                $todolist = ['id_trx' => $trxM->id, 'nik' => $_pengaju['nik'], 'id_status' => $status, 'pic' => $pic];
                $this->insertToDoList($todolist, $mailData);
                $this->sendEmail($mailData);
                // finish
            }
            return view('emails.nonaktif_ok');
        }
        // ko
        // 17 = Submit to PIC KOPERASI
        // 18 = Reject by PIC KOPERASI
        // 20 = Approve by PIC KOPERASI
        else if (($status == 18 || $status == 20) && $pic == 'ko' && in_array($nik, $kepada)) {
            $trxM->status = $status;
            $trxM->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.nonaktif0']);
            $this->insertLog($id, $status, $nik, $keterangan);
            $this->checkApproval($id, $trxM);
            if($status == 18){
                // start
                // parameter yang dipassing ke sendemail
                $mailData['title'] = 'Formulir Karyawan Non Aktif - '.$pic;
                $mailData['filename'] = 'emails.nonaktif0';
                $_pengaju = json_decode($trxM->pengaju_property, true);
                $mailData['data'] = ['pesan' => 'Formulir Karyawan Non Aktif yang anda ajukan harus direvisi', 'nama'=>$_pengaju['nama'].'/'.$_pengaju['nik'], 'jabatan'=>$_pengaju['jabatan'], 'unit_kerja'=>$_pengaju['divisi'].'/'.$_pengaju['dep'].'/'.$_pengaju['sub_dep'], 'link'=> route('nonaktif.show', [(new OpenDataController)->encryptWithKey($trxM->id), 'view' => (new OpenDataController)->encryptWithKey('detail')]), 'id_trx' => $trxM->id ];
                $mailData['to'] = $_pengaju['email'];
                $todolist = ['id_trx' => $trxM->id, 'nik' => $_pengaju['nik'], 'id_status' => $status, 'pic' => $pic];
                $this->insertToDoList($todolist, $mailData);
                $this->sendEmail($mailData);
                // finish
            }
            return view('emails.nonaktif_ok');
        }
        // am
        // 27 = Submit to PIC Application Management
        // 28 = Reject by PIC Application Management
        // 30 = Approve by PIC Application Management
        else if (($status == 28 || $status == 30) && $pic == 'am' && in_array($nik, $kepada)) {
            $trxM->status = $status;
            $trxM->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.nonaktif0']);
            $this->insertLog($id, $status, $nik, $keterangan);
            $this->checkApproval($id, $trxM);
            if($status == 28){
                // start
                // parameter yang dipassing ke sendemail
                $mailData['title'] = 'Formulir Karyawan Non Aktif - '.$pic;
                $mailData['filename'] = 'emails.nonaktif0';
                $_pengaju = json_decode($trxM->pengaju_property, true);
                $mailData['data'] = ['pesan' => 'Formulir Karyawan Non Aktif yang anda ajukan harus direvisi', 'nama'=>$_pengaju['nama'].'/'.$_pengaju['nik'], 'jabatan'=>$_pengaju['jabatan'], 'unit_kerja'=>$_pengaju['divisi'].'/'.$_pengaju['dep'].'/'.$_pengaju['sub_dep'], 'link'=> route('nonaktif.show', [(new OpenDataController)->encryptWithKey($trxM->id), 'view' => (new OpenDataController)->encryptWithKey('detail')]), 'id_trx' => $trxM->id ];
                $mailData['to'] = $_pengaju['email'];
                $todolist = ['id_trx' => $trxM->id, 'nik' => $_pengaju['nik'], 'id_status' => $status, 'pic' => $pic];
                $this->insertToDoList($todolist, $mailData);
                $this->sendEmail($mailData);
                // finish
            }
            return view('emails.nonaktif_ok');
        }
        // atasan
        // 25 = Submit to L1
        // 2 = Reject by L1
        // 4 = Done All
        else if (($status == 2 || $status == 4) && $pic == 'atasan' && $nik == $trxM->atasan) {
            $trxM->status = $status;
            $trxM->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.nonaktif0']);
            $this->insertLog($id, $status, $nik, $keterangan);
            // start
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Formulir Karyawan Non Aktif';
            $mailData['filename'] = 'emails.nonaktif1';
            $mailData['data'] = ['pesan' => 'Terdapat Formulir Karyawan Non Aktif yang harus anda review', 'nama' => $karyawan['nama'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'], 'atasan' => $atasan['nama'], 'id_trx' => $trxM->id];
            $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
            $this->sendEmail($mailData);
            // finish
            return view('emails.nonaktif_ok');
        }
        
    }
    
    // fungsi untuk cek apakah semua pic sudah approve
    public function checkApproval($id_trx, $trxM){
        // return response()->json( [ 'trxM' => $trxM ] );die;
        $check = 0;
        $n_pic = 5;
        // di
        // 8 = Approve by PIC DIVISI
        if(TrxNonaktifLog::where(['id_trx' => $id_trx, 'id_status' => 8])->exists()){
            $check += 1;
        }
        // ga
        // 12 = Approve by PIC GA
        if(TrxNonaktifLog::where(['id_trx' => $id_trx, 'id_status' => 12])->exists()){
            $check += 1;
        }
        // it
        // 16 = Approve by PIC IT
        if(TrxNonaktifLog::where(['id_trx' => $id_trx, 'id_status' => 16])->exists()){
            $check += 1;
        }
        // hc
        // 24 = Approve by PIC HC
        if(TrxNonaktifLog::where(['id_trx' => $id_trx, 'id_status' => 24])->exists()){
            $check += 1;
        }
        // ko
        // 20 = Approve by PIC KOPERASI
        if(TrxNonaktifLog::where(['id_trx' => $id_trx, 'id_status' => 20])->exists()){
            $check += 1;
        }
        // am
        // 30 = Approve by PIC Application Management
        if(TrxNonaktifLog::where($where = ['id_trx' => $id_trx, 'id_status' => 30])->exists()){
            $check += 1;
        }
        // jika ada Statement Of Compliance
        // 31 = Statement Of Compliance
        if(TrxNonaktifLog::where(['id_trx' => $id_trx, 'id_status' => 31])->exists()){
            $n_pic = 6;
        }
        // jika semua pic sudah approve
        if($check >= $n_pic){
            // start
            // parameter yang dipassing ke sendemail
            // 25 = Submit to L1
            $status = 25;
            $mailData['title'] = 'Formulir Karyawan Non Aktif - Atasan';
            $mailData['filename'] = 'emails.nonaktif0';
            $_pengaju = json_decode($trxM->pengaju_property, true);
            $_kepada = json_decode($trxM->atasan_property, true);
            $pic = 'atasan';
            $mailData['data'] = ['pesan' => 'Terdapat Formulir Karyawan Non Aktif yang harus anda review', 'nama'=>$_pengaju['nama'].'/'.$_pengaju['nik'], 'jabatan'=>$_pengaju['jabatan'], 'unit_kerja'=>$_pengaju['divisi'].'/'.$_pengaju['dep'].'/'.$_pengaju['sub_dep'], 'link'=> route('nonaktif.show', [(new OpenDataController)->encryptWithKey($trxM->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($_kepada['nik']), 'id_status' => (new OpenDataController)->encryptWithKey($status), 'pic' => (new OpenDataController)->encryptWithKey($pic)]), 'id_trx' => $trxM->id ];
            $mailData['to'] = $_kepada['email'];
            $todolist = ['id_trx' => $trxM->id, 'nik' => $_kepada['nik'], 'id_status' => $status, 'pic' => $pic];
            $this->insertToDoList($todolist, $mailData);
            $this->sendEmail($mailData);
            // finish
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
        $this->authorize('auth');
        $id = (new OpenDataController)->decryptWithKey($id);
        $trxM = TrxNonaktifMaster::find($id);
        $trxM->delete();
        ToDoList::where('id_trx', '=', $id)->where('title', 'like', 'Formulir Karyawan Non Aktif%')->delete();
        return redirect( 'nonaktif' )->with( 'msg', 'Data is deleted successfully.' );
    }
}
