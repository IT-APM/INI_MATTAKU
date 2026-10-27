<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
// 
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Level;
// 
use App\Models\RekomendasiPengajuan;
use App\Models\RekomendasiRencana;
use App\Models\RekomendasiStatus;
use App\Models\TrxRekomendasiMaster;
use App\Models\TrxRekomendasiDetail;
use App\Models\TrxRekomendasiLog;
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

class TrxRekomendasiController extends Controller
{   
    public function __construct()
    {
        $this->middleware('can:asm', ['except' => ['show','update']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // start refer to cm 464
        if(auth()->user()->nik == '01032509884'){
            return redirect()->route('error-403');
        }
        // end
        $no = 0;
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $where = ['is_aktif' => 1];
        // role asm kode_level >= 4
        $layer1 = Karyawan::select('nik', 'nama')->where($where)->where('kode_level', '>=', 4)->whereNotIn('nik', ['Hco.all','IT.BM','IT Infras'])->orderBy('nama', 'asc')->get();
        $trx = TrxRekomendasiMaster::orderBy('id', 'desc')->get();
        if(Gate::allows('hc_nkp')){
            $layer1 = Karyawan::select('nik', 'nama')->whereIn('nik',$arr_nik_nkp)->where($where)->where('kode_level', '>=', 4)->whereNotIn('nik', ['Hco.all','IT.BM','IT Infras'])->orderBy('nama', 'asc')->get();
            $trx = TrxRekomendasiMaster::whereIn('nik_karyawan',$arr_nik_nkp)->orderBy('id', 'desc')->get();
        }
        if(auth()->user()->userrole == 'nhc'){
            // $where = ['nik_atasan' => auth()->user()->nik];
            // $trx = TrxRekomendasiMaster::where($where)->orderBy('id', 'desc')->get();
            $trx = TrxRekomendasiMaster::where('nik_karyawan', [auth()->user()->nik])->orWhere('nik_atasan', [auth()->user()->nik])->orWhere('nik_reviewer1', [auth()->user()->nik])->orWhere('nik_reviewer2', [auth()->user()->nik])->orWhere('nik_reviewer3', [auth()->user()->nik])->orderBy('id', 'desc')->get();
            // 
            $arr_id = [];
            foreach($trx as $_trx){
                array_push($arr_id, $_trx->id);
            }
            $where = ['nik_pic' => auth()->user()->nik];
            $trx = TrxRekomendasiLog::where($where)->orderBy('id_trx', 'desc')->get();
            foreach($trx as $_trx){
                array_push($arr_id, $_trx->id_trx);
            }
            // 
            $trx = TrxRekomendasiMaster::whereIn('id', $arr_id)->orderBy('id', 'desc')->get();
        }
        // 
        // foreach ($trx as $value) {
        //     $trxM = TrxRekomendasiMaster::find( $value->id );
        //     $trxM->karyawan_property = $this->getNikProperty($value->nik_karyawan);
        //     $trxM->atasan_property = $this->getNikProperty($value->nik_atasan);
        //     $trxM->reviewer1_property = $this->getNikProperty($value->nik_reviewer1);
        //     $trxM->reviewer2_property = $this->getNikProperty($value->nik_reviewer2);
        //     $trxM->reviewer3_property = $this->getNikProperty($value->nik_reviewer3);
        //     $trxM->update();
        // }
        // return response()->json( [ 'trx' => $trx ] );
        // 
        // filter parameter
        $arr_status_rekomendasi = array();
        $arr_nik_karyawan = array();
        $arr_nik_atasan = array();
        foreach ($trx as $data){
            array_push($arr_status_rekomendasi, $data->status_rekomendasi);
            array_push($arr_nik_karyawan, $data->nik_karyawan);
            array_push($arr_nik_atasan, $data->nik_atasan);
        }
        $status_rekomendasi = RekomendasiStatus::whereIn('id', $arr_status_rekomendasi)->orderBy('id', 'asc')->get();
        $nik_karyawan = Karyawan::whereIn('nik', $arr_nik_karyawan)->orderBy('nama', 'asc')->get();
        $nik_atasan = Karyawan::whereIn('nik', $arr_nik_atasan)->orderBy('nama', 'asc')->get();
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
            $_status_rekomendasi = $request->input('status_rekomendasi', []);
            $_nik_karyawan = $request->input('nik_karyawan', []);
            $_nik_atasan = $request->input('nik_atasan', []);
            if(count($_status_rekomendasi) > 0){
                $arr_status_rekomendasi = $_status_rekomendasi;
            }
            else{
                $arr_status_rekomendasi = array();
            }
            if(count($_nik_karyawan) > 0){
                $arr_nik_karyawan = $_nik_karyawan;
            }
            // else{
            //     $arr_nik_karyawan = array();
            // }
            if(count($_nik_atasan) > 0){
                $arr_nik_atasan = $_nik_atasan;
            }
            // else{
            //     $arr_nik_atasan = array();
            // }
            $trx = TrxRekomendasiMaster::whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->whereIn('status_rekomendasi', $arr_status_rekomendasi)->whereIn('nik_karyawan', $arr_nik_karyawan)->whereIn('nik_atasan', $arr_nik_atasan)->orderBy('id', 'desc')->get();
            if(Gate::allows('hc_nkp')){
                $trx = TrxRekomendasiMaster::whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->whereIn('nik_karyawan',$arr_nik_nkp)->whereIn('status_rekomendasi', $arr_status_rekomendasi)->whereIn('nik_karyawan', $arr_nik_karyawan)->whereIn('nik_atasan', $arr_nik_atasan)->orderBy('id', 'desc')->get();
            }
            if(auth()->user()->userrole == 'nhc'){
                // $where = ['nik_atasan' => auth()->user()->nik];
                // $trx = TrxRekomendasiMaster::whereIn('status_rekomendasi', $arr_status_rekomendasi)->whereIn('nik_karyawan', $arr_nik_karyawan)->whereIn('nik_atasan', $arr_nik_atasan)->where($where)->orderBy('id', 'desc')->get();
                $trx = TrxRekomendasiMaster::whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->where(function($query) {$query->where('nik_karyawan', [auth()->user()->nik])->orWhere('nik_atasan', [auth()->user()->nik])->orWhere('nik_reviewer1', [auth()->user()->nik])->orWhere('nik_reviewer2', [auth()->user()->nik])->orWhere('nik_reviewer3', [auth()->user()->nik]);})->whereIn('status_rekomendasi', $arr_status_rekomendasi)->whereIn('nik_karyawan', $arr_nik_karyawan)->whereIn('nik_atasan', $arr_nik_atasan)->orderBy('id', 'desc')->get();
                // 
                $arr_id = [];
                foreach($trx as $_trx){
                    array_push($arr_id, $_trx->id);
                }
                $where = ['nik_pic' => auth()->user()->nik];
                $trx = TrxRekomendasiLog::where($where)->orderBy('id_trx', 'desc')->get();
                foreach($trx as $_trx){
                    array_push($arr_id, $_trx->id_trx);
                }
                // 
                $trx = TrxRekomendasiMaster::whereIn('id', $arr_id)->where(function($query) {$query->where('nik_karyawan', [auth()->user()->nik])->orWhere('nik_atasan', [auth()->user()->nik])->orWhere('nik_reviewer1', [auth()->user()->nik])->orWhere('nik_reviewer2', [auth()->user()->nik])->orWhere('nik_reviewer3', [auth()->user()->nik]);})->whereIn('status_rekomendasi', $arr_status_rekomendasi)->whereIn('nik_karyawan', $arr_nik_karyawan)->whereIn('nik_atasan', $arr_nik_atasan)->orderBy('id', 'desc')->get();
            }
        }else{
            $arr_status_rekomendasi = array();
            $arr_nik_karyawan = array();
            $arr_nik_atasan = array();
        }
        // end filter
        return view('formulir.rekomendasi.index', compact('layer1', 'trx', 'no', 'status_rekomendasi', 'nik_karyawan', 'nik_atasan','arr_status_rekomendasi', 'arr_nik_karyawan', 'arr_nik_atasan', 'tanggal_mulai', 'tanggal_akhir'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        // karyawan masih di hardcode dengan level minimal 4 = Assistant Manager
        // $nik_atasan = '01021403097';
        // $nik_atasan = auth()->user()->nik;
        if (!auth()->check()) {
            return redirect()->route('login-one');
        }
        $nik_atasan = Crypt::decrypt($request->nik_atasan);
        $where = ['nik' => $nik_atasan];
        $pengaju = Karyawan::select('nik', 'nama')->where($where)->first();
        $pengaju = $pengaju->nama.' / '.$pengaju->nik;
        $arr_karyawan = [];
        // karyawan yang bisa direkomendasikan adalah bawahan langsung pengaju
        $where = ['layer1' => $nik_atasan, 'is_aktif' => 1];
        $karyawan = Karyawan::where($where)->whereNotIn('nik', ['Hco.all','IT.BM','IT Infras'])->orderBy('nama', 'asc')->get();
        foreach ($karyawan as $data){
            array_push($arr_karyawan, $data->nik);
        }
        // juga bawahannya lagi
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::whereIn('layer1', $arr_karyawan)->where($where)->orderBy('nama', 'asc')->get();
        foreach ($karyawan as $data){
            array_push($arr_karyawan, $data->nik);
        }
        // dd($arr_karyawan);
        $karyawan = Karyawan::whereIn('nik', $arr_karyawan)->orderBy('nama', 'asc')->get();
        // approval dengan level minimal 4 = Assistant Manager
        $where = [ 'is_aktif' => 1];
        $approval = Karyawan::where($where)->where('kode_level','>=',4)->orderBy('nama', 'asc')->get();
        // 
        // $pengajuan = RekomendasiPengajuan::whereIn('nik_pengaju', ['system', $nik_atasan])->orderBy('id', 'asc')->get();
        $pengajuan = RekomendasiPengajuan::whereIn('nik_pengaju', ['system'])->orderBy('id', 'asc')->get();
        $jabatan = Jabatan::whereNotIn('id', [0])->orderBy('jabatan', 'asc')->get();
        $level = Level::whereNotIn('id', [0])->orderBy('id', 'asc')->get();
        $rencana = RekomendasiRencana::whereNotIn('id', [0])->orderBy('rencana', 'asc')->get();
        // 
        return view('formulir.rekomendasi.insert', compact('pengaju', 'nik_atasan', 'karyawan', 'approval', 'pengajuan', 'jabatan', 'level', 'rencana'));
        // return response()->json( [ 'data' => $karyawan ] );
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
        // return response()->json( [ 'data' => $request->all() ], 200 );
        $error = '<h4>ERROR:</h4>';
        if(!($request->has('pengajuan_id_2') || $request->has('pengajuan_id_3') || 
        $request->has('pengajuan_id_4') || $request->has('pengajuan_id_5') || 
        $request->has('pengajuan_id_14') || $request->has('pengajuan_id_1000'))){
            $error .= '<h5>Jenis pengajuan wajib dipilih. Minimal 1 jenis pengajuan.
            <br/>Terima kasih.</h5>';
            return redirect()->back()->with('error', $error);
        }
        // 
        $nik_karyawan = $request->nik_karyawan;
        // 
        // $where = ['nik' => $nik_karyawan];
        // $karyawan = Karyawan::where($where)->first();
        // $nik_atasan = $karyawan->layer1;
        // 
        // pengaju masih di hardcode dengan level minimal 4 = Assistant Manager
        // $pengaju = '01021403097';
        // $pengaju = auth()->user()->nik;
        $pengaju = $request->nik_atasan;
        $nik_atasan = $pengaju;
        $where = ['nik' => $pengaju];
        $karyawan = Karyawan::where($where)->first();
        $nik_reviewer1 = $karyawan->layer1;
        // 
        $trxM = new TrxRekomendasiMaster;
        $trxM->nik_karyawan = $nik_karyawan;
        $karyawan_property = $this->getNikProperty($nik_karyawan);
        $trxM->karyawan_property = $karyawan_property;
        $trxM->nik_atasan = $nik_atasan;
        $atasan_property = $this->getNikProperty($nik_atasan);
        $trxM->atasan_property = $atasan_property;
        $trxM->terhitung_tanggal = $request->terhitung_tanggal;
        $trxM->dasar_pengajuan = $request->dasar_pengajuan;
        $trxM->catatan_pengajuan = $request->catatan_pengajuan;
        $trxM->nik_reviewer1 = $nik_reviewer1;
        $reviewer1_property = $this->getNikProperty($nik_reviewer1);
        $trxM->reviewer1_property = $reviewer1_property;
        // $trxM->nik_reviewer2 = $request->nik_reviewer2;
        // if($request->has('nik_reviewer2')){
        //     $trxM->reviewer2_property = $this->getNikProperty($trxM->nik_reviewer2);
        // }
        // $trxM->nik_reviewer3 = $request->nik_reviewer3;
        // if($request->has('nik_reviewer3')){
        //     $trxM->reviewer3_property = $this->getNikProperty($trxM->nik_reviewer3);
        // }
        $status_rekomendasi = 1;
        $trxM->status_rekomendasi = $status_rekomendasi;
        $trxM->save();
        $this->insertLog($trxM->id, $status_rekomendasi, $pengaju, '');
        // 2 Kenaikan jabatan
        if($request->has('pengajuan_id_2')){
            $trxD = new TrxRekomendasiDetail;
            $trxD->id_trx = $trxM->id;
            $trxD->id_pengajuan = 2;
            $trxD->jenis_pengajuan = $request->pengajuan_id_2;
            $trxD->sebelum = $request->sebelum_id_2;
            $trxD->setelah = $request->setelah_id_2;
            $trxD->save();
        }
        // 3 Kenaikan level
        if($request->has('pengajuan_id_3')){
            $trxD = new TrxRekomendasiDetail;
            $trxD->id_trx = $trxM->id;
            $trxD->id_pengajuan = 3;
            $trxD->jenis_pengajuan = $request->pengajuan_id_3;
            $trxD->sebelum = $request->sebelum_id_3;
            $trxD->setelah = $request->setelah_id_3;
            $trxD->save();
        }
        // 4 Status kepegawaian
        if($request->has('pengajuan_id_4')){
            $trxD = new TrxRekomendasiDetail;
            $trxD->id_trx = $trxM->id;
            $trxD->id_pengajuan = 4;
            $trxD->jenis_pengajuan = $request->pengajuan_id_4;
            $trxD->sebelum = $request->sebelum_id_4;
            $trxD->setelah = $request->setelah_id_4;
            $trxD->save();
        }
        // 5 Insentif	
        if($request->has('pengajuan_id_5')){
            $trxD = new TrxRekomendasiDetail;
            $trxD->id_trx = $trxM->id;
            $trxD->id_pengajuan = 5;
            $trxD->jenis_pengajuan = $request->pengajuan_id_5;
            $trxD->sebelum = 'Rp. '.number_format($request->sebelum_id_5, 2);
            // $trxD->setelah = 'Rp. '.number_format($request->setelah_id_5, 2);
            $trxD->setelah = $request->setelah_id_5;
            $trxD->save();
        }
        // 14 Nilai Kenaikan gaji	
        if($request->has('pengajuan_id_14')){
            $trxD = new TrxRekomendasiDetail;
            $trxD->id_trx = $trxM->id;
            $trxD->id_pengajuan = 14;
            $trxD->jenis_pengajuan = $request->pengajuan_id_14;
            // $trxD->sebelum = 'Rp. '.number_format(0, 2);
            // $trxD->setelah = 'Rp. '.number_format($request->setelah_id_14, 2);
            $trxD->sebelum = 0;
            $trxD->setelah = $request->setelah_id_14;
            $trxD->save();
        }
        // 1000 Lainnya
        if($request->has('pengajuan_id_1000')){
            $trxD = new TrxRekomendasiDetail;
            $trxD->id_trx = $trxM->id;
            $trxD->id_pengajuan = 1000;
            $trxD->jenis_pengajuan = $request->pengajuan_id_1000;
            $trxD->sebelum = $request->sebelum_id_1000;
            $trxD->setelah = $request->setelah_id_1000;
            $trxD->save();
        }
        // 
        $_karyawan = json_decode($karyawan_property, true);
        $_atasan = json_decode($atasan_property, true);
        $_reviewer1 = json_decode($reviewer1_property, true);
        // parameter yang dipassing ke sendemail
        $mailData['title'] = 'Formulir Rekomendasi Karyawan';
        $mailData['data'] = ['nama'=>$_karyawan['nama'].'/'.$_karyawan['nik'], 'jabatan'=>$_karyawan['jabatan'], 'unit_kerja'=>$_karyawan['divisi'].'/'.$_karyawan['dep'].'/'.$_karyawan['sub_dep'], 'atasan'=>$_atasan['nama'], 'link'=> route('rekomendasi.show', [(new OpenDataController)->encryptWithKey($trxM->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($_reviewer1['nik']), 'id_status' => (new OpenDataController)->encryptWithKey($status_rekomendasi), 'pic' => (new OpenDataController)->encryptWithKey('reviewer1')]), 'id_trx' => $trxM->id ];
        $mailData['filename'] = 'emails.rekomendasi0';
        $mailData['to'] = $_reviewer1['email'];
        $todolist = ['id_trx' => $trxM->id, 'nik' => $_reviewer1['nik'], 'id_status' => $status_rekomendasi, 'pic' => 'reviewer1'];
        $this->insertToDoList($todolist, $mailData);
        $this->sendEmail($mailData);
        // 
        return redirect('rekomendasi')->with('msg', 'Data is saved successfully.');
    }

    public function getNikProperty($nik)
    {
        $where = ['nik' => $nik, 'is_aktif' => 1];
        $data = Karyawan::select('mtr_tbl_karyawan.nik', 'mtr_tbl_karyawan.nama', 'mtr_tbl_karyawan.email', 'mtr_tbl_karyawan.telepon1', 'mtr_tbl_perusahaan.nama as perusahaan', 'mtr_tbl_divisi.nama as divisi', 'mtr_tbl_departemen.nama as dep', 'mtr_tbl_sub_departemen.nama as sub_dep', 'mtr_tbl_level.level as level', 'mtr_tbl_jabatan.jabatan as jabatan', 'mtr_tbl_level.id as id_level','mtr_tbl_karyawan.tanggal_bergabung','mtr_tbl_karyawan.tanggal_berakhir','mtr_tbl_karyawan.tanggal_karyawan_tetap','mtr_tbl_status_karyawan.status_karyawan')
            ->leftJoin('mtr_tbl_perusahaan', 'mtr_tbl_perusahaan.id', '=', 'mtr_tbl_karyawan.kode_perusahaan')
            ->leftJoin('mtr_tbl_divisi', 'mtr_tbl_divisi.id', '=', 'mtr_tbl_karyawan.kode_divisi')
            ->leftJoin('mtr_tbl_departemen', 'mtr_tbl_departemen.id', '=', 'mtr_tbl_karyawan.kode_departemen')
            ->leftJoin('mtr_tbl_sub_departemen', 'mtr_tbl_sub_departemen.id', '=', 'mtr_tbl_karyawan.kode_sub_departemen')
            ->leftJoin('mtr_tbl_level', 'mtr_tbl_level.id', '=', 'mtr_tbl_karyawan.kode_level')
            ->leftJoin('mtr_tbl_jabatan', 'mtr_tbl_jabatan.id', '=', 'mtr_tbl_karyawan.kode_jabatan')
            ->leftJoin('mtr_tbl_status_karyawan', 'mtr_tbl_status_karyawan.id', '=', 'mtr_tbl_karyawan.kode_status')
            ->where($where)->first();
        return json_encode($data);
    }

    public function insertLog($id_trx, $id_status, $nik_pic, $keterangan)
    {
        $data = new TrxRekomendasiLog;
        $data->id_trx = $id_trx;
        $data->id_status = $id_status;
        $data->nik_pic = $nik_pic;
        $data->keterangan = $keterangan;
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
        // dd($request->all(), $id);
        $linkAction = route('rekomendasi.update', $id);
        $id = (new OpenDataController)->decryptWithKey($id);
        $where = ['id_trx' => $id];
        $history = TrxRekomendasiLog::where($where)->orderBy('created_at', 'desc')->get();
        $trxM = TrxRekomendasiMaster::find( $id );
        // 
        $where = ['id_trx' => $id];
        $trxD = TrxRekomendasiDetail::where($where)->get();
        // ambil property para pihak
        $karyawan = json_decode($trxM->karyawan_property, true);
        $atasan = json_decode($trxM->atasan_property, true);
        $reviewer1 = json_decode($trxM->reviewer1_property, true);
        $reviewer2 = '';
        $reviewer3 = '';
        if(!empty($trxM->nik_reviewer2)){
            $reviewer2 = json_decode($trxM->reviewer2_property, true);
        }
        if(!empty($trxM->nik_reviewer3)){
            $reviewer3 = json_decode($trxM->reviewer3_property, true);
        }
        // 
        $where = ['nik' => $karyawan['nik']];
        $tanggal_bergabung = Karyawan::where($where)->first()->tanggal_bergabung;
        array_push($karyawan, ['tanggal_bergabung'=>$tanggal_bergabung]);
        // return response()->json( [ 'karyawan' => $karyawan ] );
        // 
        // $view = $request->view;
        $pic = $request->pic;
        $nik = $request->nik;
        $id_status = $request->id_status;
        // 
        $view = (new OpenDataController)->decryptWithKey($request->view);
        if(!empty($pic)){
            $pic = (new OpenDataController)->decryptWithKey($request->pic);
        }
        if(!empty($nik)){
            $nik = (new OpenDataController)->decryptWithKey($request->nik);
        }
        if(!empty($nik)){
            $id_status = (new OpenDataController)->decryptWithKey($request->id_status);
        }
        // 
        $where = ['id_trx' => $id, 'nik_pic' => $nik];
        $nReviewer1 = TrxRekomendasiLog::where($where)->whereIn('id_status', [2, 4])->count();
        $nReviewer2 = TrxRekomendasiLog::where($where)->whereIn('id_status', [7, 9])->count();
        $nReviewer2_1 = TrxRekomendasiLog::where($where)->whereIn('id_status', [12, 14])->count();
        $nReviewer3 = TrxRekomendasiLog::where($where)->whereIn('id_status', [15, 17])->count();
        switch ($view) {
            case 'approval':
                // return response()->json( [ 'data' => $id_status ] );
                // reviewer1 - 1 = Recommended by L1
                if ($pic == 'reviewer1' && $nik == $trxM->nik_reviewer1 && $nReviewer1 == 0 && $id_status == 1) {
                    $approve = 4;
                    $reject = 2;
                    return view('emails.rekomendasi', compact('trxM', 'trxD', 'nik', 'pic', 'approve', 'reject', 'karyawan', 'atasan', 'linkAction', 'history'));
                } 
                // reviewer2 - 6 = Next to Reviewer Approval-2
                else if ($pic == 'reviewer2' && $nik == $trxM->nik_reviewer2 && $nReviewer2 == 0 && $id_status == 6) {
                    $approve = 9;
                    $reject = 7;
                    return view('emails.rekomendasi', compact('trxM', 'trxD', 'nik', 'pic', 'approve', 'reject', 'karyawan', 'atasan', 'linkAction', 'history'));
                } 
                // reviewer2 - 11 = Next to Reviewer Approval-2 & 3
                else if ($pic == 'reviewer2' && $nik == $trxM->nik_reviewer2 && $nReviewer2_1 == 0 && $id_status == 11) {
                    $approve = 14;
                    $reject = 12;
                    return view('emails.rekomendasi', compact('trxM', 'trxD', 'nik', 'pic', 'approve', 'reject', 'karyawan', 'atasan', 'linkAction', 'history'));
                } 
                // reviewer3 - 11 = Next to Reviewer Approval-2 & 3
                else if ($pic == 'reviewer3' && $nik == $trxM->nik_reviewer3 && $nReviewer3 == 0 && $id_status == 11) {
                    $approve = 17;
                    $reject = 15;
                    return view('emails.rekomendasi', compact('trxM', 'trxD', 'nik', 'pic', 'approve', 'reject', 'karyawan', 'atasan', 'linkAction', 'history'));
                } 
                // selainnya
                else {
                    return view('emails.rekomendasi_ok');
                }
                break;
            case 'detail':
                $this->authorize('auth');
                $where = ['id_trx' => $id];
                $history = TrxRekomendasiLog::where($where)->orderBy('created_at', 'desc')->get();
                return view('formulir.rekomendasi.view', compact('trxM', 'trxD', 'karyawan', 'atasan', 'reviewer1', 'reviewer2', 'reviewer3', 'history'));
                break;
            case 'pdf':
                $this->authorize('auth');
                // print to pdf
                $waktu = date('YmdHis');
                $no = 0;
                // 
                $where = ['nik' => $trxM->nik_karyawan];
                $_karyawan = Karyawan::where($where)->first();
                $status_karyawan = $_karyawan->_status->status_karyawan;
                // start
                $htmlLog = '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0"><tr>';
                // atasan
                $where = ['id_trx' => $id, 'nik_pic' => $trxM->nik_atasan, 'id_status' => 1];
                $rekomendasiLog = TrxRekomendasiLog::where($where)->orderBy('id', 'desc')->first();
                $htmlLog .= '<td>Mengajukan<br/><img src="' . public_path('assets/images/matta/requested_new.png') . '" height="30" /><br/>' . $atasan['nama'] . '<br/>' . $atasan['jabatan'] . '<br/>' . $rekomendasiLog->created_at . '</td>';
                // reviewer1
                $where = ['id_trx' => $id, 'nik_pic' => $trxM->nik_reviewer1];
                $rekomendasiLog = TrxRekomendasiLog::where($where)->whereIn('id_status', [2, 4])->orderBy('id', 'desc')->first();
                // 4 = Menyetujui
                // 2 = Menolak
                if (!empty($rekomendasiLog)) {
                    if($rekomendasiLog->id_status == 4){
                        $htmlLog .= '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $reviewer1['nama'] . '<br/>' . $reviewer1['jabatan'] . '<br/>' . $rekomendasiLog->created_at . '</td>';
                    }else{
                        $htmlLog .= '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $reviewer1['nama'] . '<br/>' . $reviewer1['jabatan'] . '<br/>' . $rekomendasiLog->created_at . '</td>';
                    }
                }
                // reviewer2
                $where = ['id_trx' => $id, 'nik_pic' => $trxM->nik_reviewer2];
                $rekomendasiLog = TrxRekomendasiLog::where($where)->whereIn('id_status', [9, 14, 7, 12])->orderBy('id', 'desc')->first();
                // 9, 14 = Menyetujui
                // 7, 12 = Menolak
                if (!empty($rekomendasiLog)) {
                    if($rekomendasiLog->id_status == 9 || $rekomendasiLog->id_status == 14){
                        $htmlLog .= '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $reviewer2['nama'] . '<br/>' . $reviewer2['jabatan'] . '<br/>' . $rekomendasiLog->created_at . '</td>';
                    }else{
                        $htmlLog .= '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $reviewer2['nama'] . '<br/>' . $reviewer2['jabatan'] . '<br/>' . $rekomendasiLog->created_at . '</td>';
                    }
                }
                // reviewer3
                $where = ['id_trx' => $id, 'nik_pic' => $trxM->nik_reviewer3];
                $rekomendasiLog = TrxRekomendasiLog::where($where)->whereIn('id_status', [15, 17])->orderBy('id', 'desc')->first();
                // 17 = Menyetujui
                // 15 = Menolak
                if (!empty($rekomendasiLog)) {
                    if($rekomendasiLog->id_status == 17){
                        $htmlLog .= '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $reviewer3['nama'] . '<br/>' . $reviewer3['jabatan'] . '<br/>' . $rekomendasiLog->created_at . '</td>';
                    }else{
                        $htmlLog .= '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $reviewer3['nama'] . '<br/>' . $reviewer3['jabatan'] . '<br/>' . $rekomendasiLog->created_at . '</td>';
                    }
                }
                // hco
                $where = ['id_trx' => $id];
                $rekomendasiLog = TrxRekomendasiLog::where($where)->whereIn('id_status', [5, 10, 18])->orderBy('id', 'desc')->first();
                if (!empty($rekomendasiLog)) {
                    if($rekomendasiLog->id_status == 18){
                        $htmlLog .= '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $rekomendasiLog->_pic->nama . '<br/>' . $rekomendasiLog->_pic->_jabatan->jabatan . '<br/>' . $rekomendasiLog->created_at . '</td>';
                    }else{
                        $htmlLog .= '<td>Mengetahui<br/><img src="' . public_path('assets/images/matta/informed_new.png') . '" height="30" /><br/>' . $rekomendasiLog->_pic->nama . '<br/>' . $rekomendasiLog->_pic->_jabatan->jabatan . '<br/>' . $rekomendasiLog->created_at . '</td>';
                    }
                }
                // finish
                $htmlLog .= '</tr></table>';
                // 
                $filename = $waktu . '_formulir_rekomendasi_karyawan.pdf';
                $view = \View::make('formulir.rekomendasi.pdf', compact('trxM', 'trxD', 'karyawan', 'status_karyawan', 'atasan', 'no', 'reviewer1', 'reviewer2', 'reviewer3', 'htmlLog', 'rekomendasiLog'));
                $html = $view->render();
                $pdf = new TCPDF;
                $pdf::SetTitle('Formulir Rekomendasi Karyawan');
                $pdf::SetFont('times', '', 10, '', true);
                $pdf::AddPage();
                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output(public_path($filename), 'I');
                return response()->download(public_path($filename));
                break;
            default:
                return view('emails.rekomendasi_ok');
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
        $this->authorize('hc');
        $id = (new OpenDataController)->decryptWithKey($id);
        $trxM = TrxRekomendasiMaster::find( $id );
        // 
        $status = RekomendasiStatus::whereIn('id', [5,6,10,11,18,$trxM->status_rekomendasi])->orderBy('id', 'asc')->get();
        // approval dengan level minimal 4 = Assistant Manager
        $where = [ 'is_aktif' => 1];
        $approval = Karyawan::where($where)->where('kode_level','>=',4)->orderBy('nama', 'asc')->get();
        // 
        $where = ['id_trx' => $id];
        $trxD = TrxRekomendasiDetail::where($where)->get();
        // ambil property para pihak
        $karyawan = json_decode($trxM->karyawan_property, true);
        $atasan = json_decode($trxM->atasan_property, true);
        $reviewer1 = json_decode($trxM->reviewer1_property, true);
        $reviewer2 = '';
        $reviewer3 = '';
        if(!empty($trxM->nik_reviewer2)){
            $reviewer2 = json_decode($trxM->reviewer2_property, true);
        }
        if(!empty($trxM->nik_reviewer3)){
            $reviewer3 = json_decode($trxM->reviewer3_property, true);
        }
        $where = ['id_trx' => $id];
        $keterangan = TrxRekomendasiLog::where($where)->whereIn('id_status', [5,10,18])->orderBy('id', 'desc')->first();
        return view( 'formulir.rekomendasi.update', compact( 'trxM', 'karyawan', 'atasan', 'reviewer1', 'reviewer2', 'reviewer3', 'status', 'approval', 'keterangan' ) );
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
        // $status_pengajuan = $request->status_pengajuan;
        // return response()->json( [ 'id' => $id, 'data' => $request->all() ] );
        // masih di hardcode pic hco -> Khatrin Rismasany
        // $hco = '01031908799';
        // $hco = auth()->user()->nik;
        $id = (new OpenDataController)->decryptWithKey($id);
		$hco = MtrUtility::find(17)->informasi;
        $trxM = TrxRekomendasiMaster::find( $id );
        $karyawan = json_decode($trxM->karyawan_property, true);
        // return response()->json( [ 'data' => $karyawan ] );
        $atasan = json_decode($trxM->atasan_property, true);
        $pic = $request->pic;
        $nik = $request->nik;
        $status_rekomendasi = $request->status_rekomendasi;
        // 
        // $pic = (new OpenDataController)->decryptWithKey($request->pic);
        // $nik = (new OpenDataController)->decryptWithKey($request->nik);
        // $status_rekomendasi = (new OpenDataController)->decryptWithKey($request->status_rekomendasi);
        // 
        $catatan = $request->catatan;
        // 2 = Rejected by Approval-1
        // 4 = Approved by Approval-1
        // dd($request->all(), $id);
        if (($status_rekomendasi == 4 || $status_rekomendasi == 2) && $pic == 'reviewer1' && $nik == $trxM->nik_reviewer1) {
            $trxM->status_rekomendasi = $status_rekomendasi;
            $trxM->catatan_reviewer1 = $catatan;
            // if($status_rekomendasi == 4){
            //     $trxM->catatan_reviewer1 = $request->keterangan_approve;
            // }else{
            //     $trxM->catatan_reviewer1 = $request->keterangan_reject;
            // }
            $trxM->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.rekomendasi0']);
            $this->insertLog($id, $status_rekomendasi, $nik, '');
            // start
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Formulir Rekomendasi Karyawan';
            $mailData['filename'] = 'emails.rekomendasi1';
            $mailData['data'] = ['nama' => $karyawan['nama'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'], 'atasan' => $atasan['nama'], 'id_trx' => $id];
            $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
            $this->sendEmail($mailData);
            return view('emails.rekomendasi_ok');
            // finish
        }
        // 5 = Done by HC (180) || 10 = Done by HC (360) || 18 = Rejected by HC
        else if ($status_rekomendasi == 5 || $status_rekomendasi == 10 || $status_rekomendasi == 18) {
            $trxM->status_rekomendasi = $status_rekomendasi;
            // 
            $trxM->update();
            $this->insertLog($id, $status_rekomendasi, $hco, $request->keterangan);
            // 
            return redirect('rekomendasi')->with('msg', 'Data is saved successfully.');
        }
        // 6 = Next to Reviewer Approval-2
        else if ($status_rekomendasi == 6) {
            $trxM->status_rekomendasi = $status_rekomendasi;
            // reviewer2
            $nik_reviewer2 = $request->reviewer2;
            $reviewer2_property = $this->getNikProperty($nik_reviewer2);
            $trxM->nik_reviewer2 = $nik_reviewer2;
            $trxM->reviewer2_property = $reviewer2_property;
            // 
            $trxM->update();
            $this->insertLog($id, $status_rekomendasi, $hco, '');
            // 
            $reviewer2 = json_decode($trxM->reviewer2_property, true);
            // parameter yang dipassing ke sendemail
            // start
            $mailData['title'] = 'Formulir Rekomendasi Karyawan';
            $mailData['data'] = ['nama'=>$karyawan['nama'].'/'.$karyawan['nik'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('rekomendasi.show', [(new OpenDataController)->encryptWithKey($trxM->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($reviewer2['nik']), 'id_status' => (new OpenDataController)->encryptWithKey($status_rekomendasi), 'pic' => (new OpenDataController)->encryptWithKey('reviewer2')]), 'id_trx' => $trxM->id ];
            $mailData['filename'] = 'emails.rekomendasi0';
            $mailData['to'] = $reviewer2['email'];
            $todolist = ['id_trx' => $trxM->id, 'nik' => $reviewer2['nik'], 'id_status' => $status_rekomendasi, 'pic' => 'reviewer2'];
            $this->insertToDoList($todolist, $mailData);
            $this->sendEmail($mailData);
            return redirect('rekomendasi')->with('msg', 'Data is saved successfully.');
            // finish
        }
        // 11 = Next to Reviewer Approval-2 & 3
        else if ($status_rekomendasi == 11) {
            $trxM->status_rekomendasi = $status_rekomendasi;
            // reviewer2
            $nik_reviewer2 = $request->reviewer2;
            $reviewer2_property = $this->getNikProperty($nik_reviewer2);
            $trxM->nik_reviewer2 = $nik_reviewer2;
            $trxM->reviewer2_property = $reviewer2_property;
            // reviewer3
            $nik_reviewer3 = $request->reviewer3;
            $reviewer3_property = $this->getNikProperty($nik_reviewer3);
            $trxM->nik_reviewer3 = $nik_reviewer3;
            $trxM->reviewer3_property = $reviewer3_property;
            // 
            $trxM->update();
            $this->insertLog($id, $status_rekomendasi, $hco, '');
            // 
            $reviewer2 = json_decode($trxM->reviewer2_property, true);
            $reviewer3 = json_decode($trxM->reviewer3_property, true);
            // reviewer2
            // parameter yang dipassing ke sendemail
            // start
            $mailData['title'] = 'Formulir Rekomendasi Karyawan';
            $mailData['data'] = ['nama'=>$karyawan['nama'].'/'.$karyawan['nik'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('rekomendasi.show', [(new OpenDataController)->encryptWithKey($trxM->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($reviewer2['nik']), 'id_status' => (new OpenDataController)->encryptWithKey($status_rekomendasi), 'pic' => (new OpenDataController)->encryptWithKey('reviewer2')]), 'id_trx' => $trxM->id ];
            $mailData['filename'] = 'emails.rekomendasi0';
            $mailData['to'] = $reviewer2['email'];
            $todolist = ['id_trx' => $trxM->id, 'nik' => $reviewer2['nik'], 'id_status' => $status_rekomendasi, 'pic' => 'reviewer2'];
            $this->insertToDoList($todolist, $mailData);
            $this->sendEmail($mailData);
            // reviewer3
            // parameter yang dipassing ke sendemail
            // start
            $mailData['title'] = 'Formulir Rekomendasi Karyawan';
            $mailData['data'] = ['nama'=>$karyawan['nama'].'/'.$karyawan['nik'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('rekomendasi.show', [(new OpenDataController)->encryptWithKey($trxM->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($reviewer3['nik']), 'id_status' => (new OpenDataController)->encryptWithKey($status_rekomendasi), 'pic' => (new OpenDataController)->encryptWithKey('reviewer3')]), 'id_trx' => $trxM->id ];
            $mailData['filename'] = 'emails.rekomendasi0';
            $mailData['to'] = $reviewer3['email'];
            $todolist = ['id_trx' => $trxM->id, 'nik' => $reviewer3['nik'], 'id_status' => $status_rekomendasi, 'pic' => 'reviewer3'];
            $this->insertToDoList($todolist, $mailData);
            $this->sendEmail($mailData);
            return redirect('rekomendasi')->with('msg', 'Data is saved successfully.');
            // finish
        }
        // 7 = Rejected by Approval-2
        // 9 = Approved by Approval-2
        else if (($status_rekomendasi == 7 || $status_rekomendasi == 9) && $pic == 'reviewer2' && $nik == $trxM->nik_reviewer2) {
            $trxM->status_rekomendasi = $status_rekomendasi;
            $trxM->catatan_reviewer2 = $catatan;
            // if($status_rekomendasi == 9){
            //     $trxM->catatan_reviewer2 = $request->keterangan_approve;
            // }else{
            //     $trxM->catatan_reviewer2 = $request->keterangan_reject;
            // }
            $trxM->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.rekomendasi0']);
            $this->insertLog($id, $status_rekomendasi, $nik, '');
            // start
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Formulir Rekomendasi Karyawan';
            $mailData['filename'] = 'emails.rekomendasi1';
            $mailData['data'] = ['nama' => $karyawan['nama'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'], 'atasan' => $atasan['nama'], 'id_trx' => $id];
            $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
            $this->sendEmail($mailData);
            return view('emails.rekomendasi_ok');
            // finish
        }
        // 12 = Rejected by Approval-2
        // 14 = Approved by Approval-2
        else if (($status_rekomendasi == 12 || $status_rekomendasi == 14) && $pic == 'reviewer2' && $nik == $trxM->nik_reviewer2) {
            $trxM->status_rekomendasi = $status_rekomendasi;
            $trxM->catatan_reviewer2 = $catatan;
            // if($status_rekomendasi == 14){
            //     $trxM->catatan_reviewer2 = $request->keterangan_approve;
            // }else{
            //     $trxM->catatan_reviewer2 = $request->keterangan_reject;
            // }
            $trxM->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.rekomendasi0']);
            $this->insertLog($id, $status_rekomendasi, $nik, '');
            // start
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Formulir Rekomendasi Karyawan';
            $mailData['filename'] = 'emails.rekomendasi1';
            $mailData['data'] = ['nama' => $karyawan['nama'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'], 'atasan' => $atasan['nama'], 'id_trx' => $id];
            $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
            $this->sendEmail($mailData);
            return view('emails.rekomendasi_ok');
            // finish
        }
        // 15 = Rejected by Approval-3
        // 17 = Approved by Approval-3
        else if (($status_rekomendasi == 15 || $status_rekomendasi == 17) && $pic == 'reviewer3' && $nik == $trxM->nik_reviewer3) {
            $trxM->status_rekomendasi = $status_rekomendasi;
            $trxM->catatan_reviewer3 = $catatan;
            // if($status_rekomendasi == 17){
            //     $trxM->catatan_reviewer3 = $request->keterangan_approve;
            // }else{
            //     $trxM->catatan_reviewer3 = $request->keterangan_reject;
            // }
            $trxM->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.rekomendasi0']);
            $this->insertLog($id, $status_rekomendasi, $nik, '');
            // start
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Formulir Rekomendasi Karyawan';
            $mailData['filename'] = 'emails.rekomendasi1';
            $mailData['data'] = ['nama' => $karyawan['nama'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'], 'atasan' => $atasan['nama'], 'id_trx' => $id];
            $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
            $this->sendEmail($mailData);
            return view('emails.rekomendasi_ok');
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
        //
    }

    public function layer($id=0)
    {
        // ambil parameter karyawan dan semua layer
        $where = ['is_aktif' => 1, 'nik'=> $id];
        $layer = Karyawan::select('layer1', 'layer2', 'layer3')->where($where)->first();
        // approval dengan level minimal 4 = Assistant Manager
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->where('kode_level','>=',4)->orderBy('nama', 'asc')->get();
        return response()->json(['layer' => $layer, 'karyawan' => $karyawan]);
    }
}