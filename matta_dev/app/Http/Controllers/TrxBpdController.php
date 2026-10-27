<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Gate;
// 
use App\Models\Propinsi;
use App\Models\PropinsiKota;
use App\Models\Negara;
use App\Models\Karyawan;
use App\Models\Perusahaan;
use App\Models\BpdBiaya;
use App\Models\BpdCoa;
use App\Models\BpdKelompok;
use App\Models\BpdNikPeran;
use App\Models\BpdNikPC;
use App\Models\BpdNikBod;
use App\Models\BpdNonProyekBiayaDalamNegeri;
use App\Models\BpdNonProyekBiayaLuarNegeri;
use App\Models\BpdPeran;
use App\Models\BpdProyek;
use App\Models\BpdProyekAdmin;
use App\Models\BpdProyekUangSaku;
use App\Models\BpdProyekUangSakuKelompok;
use App\Models\BpdProyekUangSakuInstalasi;
use App\Models\BpdStatus;
// 
use App\Models\TrxBpdDetailBiaya;
use App\Models\TrxBpdDetailInfo;
use App\Models\TrxBpdLog;
use App\Models\TrxBpdMaster;
use App\Models\ToDoList;
use App\Models\ToDoListLog;
// 
use App\Models\MtrUtility;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
// 
use Carbon\Carbon;
use DB;
use Elibyy\TCPDF\Facades\TCPDF;
use App\Exports\TrxBpdMasterExportInfo;
use App\Exports\TrxBpdMasterExportBiaya;
use Maatwebsite\Excel\Facades\Excel;
// 

class TrxBpdController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:bpd');
        // $this->middleware('can:bpd', ['except' => ['show','update']]);
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
        $no = 0;
        // id_peran=2 adalah Project Admin
        $nik = auth()->user()->nik;
        // dd($nik);
        $where = ['admin' => $nik];
        $admin = BpdNikPeran::where($where)->get();
        // ambil data manajemen berdasarkan adminnya
        $where = ['id_peran' => 3, 'is_active' => 1, 'admin' => $nik];
        $mgt = BpdNikPeran::where($where)->get();
        $trx = TrxBpdMaster::where('created_at', '>=', Carbon::now()->subDays(30))->orderBy('id', 'desc')->get();
        $_trx = TrxBpdMaster::orderBy('id', 'desc')->get();
        if(Gate::allows('hc_nkp')){
            $trx = TrxBpdMaster::whereIn('pengaju',$arr_nik_nkp)->where('created_at', '>=', Carbon::now()->subDays(30))->orderBy('id', 'desc')->get();
            $_trx = TrxBpdMaster::whereIn('pengaju',$arr_nik_nkp)->orderBy('id', 'desc')->get();
        }
        if(auth()->user()->userrole == 'nhc'){
            $trx = TrxBpdMaster::where('admin', $nik)->where('created_at', '>=', Carbon::now()->subDays(30))->orderBy('id', 'desc')->get();
            $_trx = TrxBpdMaster::where('admin', $nik)->orderBy('id', 'desc')->get();
            // jika bukan admin
            if(count($admin)==0){
                // $trx = TrxBpdMaster::orWhere('pengaju', $nik)->orWhere('koordinator', $nik)->orWhere('layer1', $nik)->orWhere('layer2', $nik)->orWhere('hco', $nik)->where('created_at', '>=', Carbon::now()->subDays(30))->orderBy('id', 'desc')->get();
                $trx = TrxBpdMaster::where(function ($query) use ($nik){
                    $query->where('pengaju', $nik)->orWhere('koordinator', $nik)->orWhere('layer1', $nik)->orWhere('layer2', $nik);
                })->where('created_at', '>=', Carbon::now()->subDays(30))->orderBy('id', 'desc')->get();
                $_trx = TrxBpdMaster::orWhere('pengaju', $nik)->orWhere('koordinator', $nik)->orWhere('layer1', $nik)->orWhere('layer2', $nik)->orWhere('hco', $nik)->orderBy('id', 'desc')->get();
            }
        }
        // filter parameter
        $pengaju = '';
        $arr_perusahaan = array();
        $arr_luar_dalam = array();
        $arr_status_id = array();
        $tanggal_mulai = '';
        $tanggal_akhir = '';
        $perusahaan = Perusahaan::select('id','nama')->whereNotIn('id', [0,3])->orderBy('nama', 'asc')->get();
        foreach ($_trx as $data){
            array_push($arr_luar_dalam, $data->luar_dalam);
            array_push($arr_status_id, $data->status_id);
        }
        $luar_dalam = array('ca_dalam' => 'CA Dalam Negeri','ca_luar' => 'CA Luar Negeri','reimburse' => 'Reimbursement Dalam Negeri','reimburse_luar' => 'Reimbursement Luar Negeri');
        $status_id = BpdStatus::select('id','status')->whereIn('id', $arr_status_id)->orderBy('id', 'asc')->get();
        // start filter
        if($request->filter == true){
            // dd($request->all());
            $pengaju = $request->pengaju;
            $_perusahaan = $request->input('perusahaan', []);
            $_luar_dalam = $request->input('luar_dalam', []);
            $_status_id = $request->input('status_id', []);
            $tanggal_mulai = $request->tanggal_mulai;
            $tanggal_akhir = $request->tanggal_akhir;
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
            $_pengaju = Karyawan::select('nik')->where('nama','LIKE','%'.$pengaju.'%')->get();
            $nik_karyawan = Karyawan::select('nik')->whereIn('kode_perusahaan', $arr_perusahaan)->get()->toArray();
            $trx = TrxBpdMaster::whereIn('pengaju', $nik_karyawan)->whereIn('pengaju', $_pengaju)->whereIn('luar_dalam', $arr_luar_dalam)->whereIn('status_id', $arr_status_id)->whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->orderBy('id', 'desc')->get();
            if(Gate::allows('hc_nkp')){
                $trx = TrxBpdMaster::whereIn('pengaju',$arr_nik_nkp)->whereIn('pengaju', $nik_karyawan)->whereIn('pengaju', $_pengaju)->whereIn('luar_dalam', $arr_luar_dalam)->whereIn('status_id', $arr_status_id)->whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->orderBy('id', 'desc')->get();
            }
            if(auth()->user()->userrole == 'nhc'){
                $trx = TrxBpdMaster::where('admin', $nik)->whereIn('pengaju', $nik_karyawan)->whereIn('pengaju', $_pengaju)->whereIn('luar_dalam', $arr_luar_dalam)->whereIn('status_id', $arr_status_id)->whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->orderBy('id', 'desc')->get();
                // jika bukan admin
                if(count($admin)==0){
                    // $trx = TrxBpdMaster::orWhere('pengaju', $nik)->orWhere('koordinator', $nik)->orWhere('layer1', $nik)->orWhere('layer2', $nik)->orWhere('hco', $nik)->whereIn('pengaju', $nik_karyawan)->whereIn('pengaju', $_pengaju)->whereIn('luar_dalam', $arr_luar_dalam)->whereIn('status_id', $arr_status_id)->whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->orderBy('id', 'desc')->get();
                    $trx = TrxBpdMaster::where(function ($query) use ($nik){
                        $query->where('pengaju', $nik)->orWhere('koordinator', $nik)->orWhere('layer1', $nik)->orWhere('layer2', $nik)->orWhere('hco', $nik);
                    })->whereIn('pengaju', $nik_karyawan)->whereIn('pengaju', $_pengaju)->whereIn('luar_dalam', $arr_luar_dalam)->whereIn('status_id', $arr_status_id)->whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->orderBy('id', 'desc')->get();
                }
            }
        }else{
            $arr_perusahaan = array();
            $arr_luar_dalam = array();
            $arr_status_id = array();
        }
        // end filter
        return view( 'formulir.bpd.index', compact( 'trx', 'no', 'mgt', 'pengaju', 'perusahaan', 'luar_dalam', 'status_id', 'arr_perusahaan', 'arr_luar_dalam', 'arr_status_id', 'tanggal_mulai', 'tanggal_akhir'));
    }

    // public function get_data($id=0)
    // {
    //     $no = 0;
    //     $trx = TrxBpdMaster::orderBy('updated_at', 'desc')->get();
    //     return view( 'formulir.bpd.raw_data', compact( 'trx', 'no'));
    // }

    public function exportexcel(Request $request, $id)
    {
        if($id == 0){
            // dd($request->all(), $id);
            return Excel::download(new TrxBpdMasterExportInfo($request), 'bpd_info_'.Carbon::now()->format('YmdHis').'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }elseif($id == 1){
            return Excel::download(new TrxBpdMasterExportBiaya($request), 'bpd_biaya_'.Carbon::now()->format('YmdHis').'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $new = $request->new;
        $mgt = $request->mgt;
        $periode = Carbon::now()->format('Y');
        $pengaju = auth()->user()->nik;
        // jika admin input bpd untuk grup manajemen
        if($request->mgt <> '0'){
            $pengaju = $request->mgt;
        }
        $pengaju_property = $this->getNikProperty($pengaju);
        $_pengaju_property = json_decode($pengaju_property, true);
        $id_level = $_pengaju_property['id_level'];
        // 
        $kelompok = array();
        $where = ['is_project' => 1];
        $_data = BpdKelompok::where($where)->get();
        foreach ($_data as $data){
            $_kelompok = explode(',', $data->level);
            if (in_array($id_level, $_kelompok)){
                $kelompok['project'] = $data->kelompok;
                break;
            }
        }
        $where = ['is_project' => 0];
        $_data = BpdKelompok::where($where)->get();
        foreach ($_data as $data){
            $_kelompok = explode(',', $data->level);
            if (in_array($id_level, $_kelompok)){
                $kelompok['nonproject'] = $data->kelompok;
                break;
            }
        }
        // ini akan dilookup dari table mtr_tbl_bpd_nik_peran is_active=1
        // 1 = Leader
        // 2 = Member
        // 3 = Management
        $where = ['nik' => $pengaju, 'is_active' => 1];
        $is_leader = BpdNikPeran::where($where)->first()->id_peran;
        $where = ['nik' => $pengaju];
        $atasan = Karyawan::where($where)->first()->layer1;
        $atasan_property = $this->getNikProperty($atasan);
        $area = Propinsi::whereNotIn('id', [0, 1])->orderBy('id', 'asc')->get();
        $negara = Negara::orderBy('negara', 'asc')->get();
        $status = 'insert';
        $data = ['status'=>$status, 'luar_dalam'=>$new, 'is_leader'=>$is_leader, 'pengaju_property'=>$pengaju_property, 'atasan_property'=>$atasan_property, 'kelompok'=>$kelompok, 'periode'=>$periode, 'mgt'=>$mgt];
        // return response()->json( [ 'success' => true, 'data' => $data ], 200 );
        // jika jenis BPD adalah reimburse
        if(in_array($new, ['reimburse', 'reimburse_luar'])){
            return view( 'formulir.bpd.reimburse', compact('data', 'area', 'negara'));
        }
        // jika selain reimburse
        else{
            return view( 'formulir.bpd.upsert', compact('data', 'area', 'negara'));
        }
    }

    public function getNikProperty($nik)
    {
        $where = ['nik' => $nik];
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $request->all();
        // dd($data);
        $new = $request->new;
        $status = $request->status;
        $pengaju = auth()->user()->nik;
        $id_trx = $request->id_trx;;
        if($status == 'update'){
            $id_trx = Crypt::decrypt($id_trx);
        }
        $trx = new TrxBpdMaster;
        if($status == 'update'){
            $trx = TrxBpdMaster::find($id_trx);
            // ca
            // 1 = Draf CA
            // 16 = Selesai CA
            if($trx->status_id >= 1 && $trx->status_id <= 16){
                $where = ['id_trx' => $id_trx, 'jenis' => 'ca'];
                $check = TrxBpdDetailInfo::where($where)->exists();
                if(!$check && count($request->input('tanggal_mulai', [])) == 0){
                    return back()->with('error', 'ERROR: Tujuan wajib diisi.');
                }
            }
            // realisasi
            // 17 = Pra-Realisasi CA
            // 31 = Selesai Realisasi
            else if($trx->status_id >= 17 && $trx->status_id <= 31){
                $where = ['id_trx' => $id_trx, 'jenis' => 'realisasi'];
                $check = TrxBpdDetailInfo::where($where)->exists();
                if(!$check && count($request->input('tanggal_mulai', [])) == 0){
                    return back()->with('error', 'ERROR: Tujuan wajib diisi.');
                }
            }
            // reimburse
            // 32 = Draf Reimburse
            // 47 = Selesai Reimburse
            else if($trx->status_id >= 32 && $trx->status_id <= 47){
                $where = ['id_trx' => $id_trx, 'jenis' => 'reimburse'];
                $check = TrxBpdDetailInfo::where($where)->exists();
                if(!$check && count($request->input('tanggal_mulai', [])) == 0){
                    return back()->with('error', 'ERROR: Tujuan wajib diisi.');
                }
            }
        }
        // jika admin input bpd untuk grup manajemen
        if($request->mgt <> '0'){
            $pengaju = $request->mgt;
        }
        $where = ['pengaju' => $pengaju];
        $is_dua = TrxBpdMaster::where($where)->whereNotIn('luar_dalam', ['reimburse'])->whereIn('status_id', [13,18,19,21,22,24,26,30])->count();
        $where = ['nik' => $pengaju];
        $is_bod = BpdNikBod::where($where)->exists();
        // dd($is_dua, $pengaju, $data);
        if(!in_array($new, ['reimburse', 'reimburse_luar']) && $status == 'insert' && $is_dua >= 2 && !$is_bod){
            return redirect('bpd')->with('error', 'ERROR: Pengajuan CA tidak dapat dilakukan karena CA sebelumnya belum selesai direalisasikan.');
        }
        foreach($data as $_id => $value){
            if(str_starts_with($_id, 'lampiran_')){
                $request->validate([
                    $_id => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                ]);
            }
        }
        $pengaju_property = $this->getNikProperty($pengaju);
        $_pengaju_property = json_decode($pengaju_property, true);
        // dd($pengaju_property);
        $id_level = $_pengaju_property['id_level'];
        // 
        $kelompok = array();
        $where = ['is_project' => 1];
        $_data = BpdKelompok::where($where)->get();
        foreach ($_data as $data){
            $_kelompok = explode(',', $data->level);
            if (in_array($id_level, $_kelompok)){
                $kelompok['project'] = $data->kelompok;
                break;
            }
        }
        $where = ['is_project' => 0];
        $_data = BpdKelompok::where($where)->get();
        foreach ($_data as $data){
            $_kelompok = explode(',', $data->level);
            if (in_array($id_level, $_kelompok)){
                $kelompok['nonproject'] = $data->kelompok;
                break;
            }
        }
        $periode = Carbon::now()->format('Y');
        $trx->periode = $periode;
        // ini akan dilookup dari table mtr_tbl_bpd_nik_peran is_active=1
        // 1 = Leader
        // 2 = Member
        // 3 = Management
        $where = ['nik' => $pengaju, 'is_active' => 1];
        $is_leader = BpdNikPeran::where($where)->first()->id_peran;
        $trx->is_leader = $is_leader;
        // 2 = Pengajuan CA
        $status_id = 2;
        // 6 = Perbaikan CA oleh Pengaju
        if($status == 'update'){
            $status_id = 6;
            // jika direject maka uang saku harus dihapus semua
            // selanjutnya akan dihitung ulang terhadap updatenya
            $where = ['id_trx' => $id_trx, 'jenis' => 'coa'];
            $_result = TrxBpdDetailBiaya::where($where)->whereIn('id_coa', [1,2,76,89])->delete();
        }
        if(in_array($new, ['reimburse', 'reimburse_luar'])){
            // 33 = Pengajuan Reimburse
            $status_id = 33;
            if($status == 'update'){
                // jika direject maka uang saku harus dihapus semua
                // selanjutnya akan dihitung ulang terhadap updatenya
                // 37 = Perbaikan Reimburse oleh Pengaju
                $status_id = 37;
                $where = ['id_trx' => $id_trx, 'jenis' => 'coa'];
                $_result = TrxBpdDetailBiaya::where($where)->whereIn('id_coa', [1,2,76,89])->delete();
            }
        }
        // 
        $trx->pengaju = $pengaju;
        $trx->pengaju_property = $this->getNikProperty($pengaju);
        // 
        $where = ['nik' => $pengaju];
        $atasan = Karyawan::where($where)->first()->layer1;
        $atasan_property = $this->getNikProperty($atasan);
        $trx->atasan = $atasan;
        $trx->atasan_property = $this->getNikProperty($atasan);
        // jika hanya ada 1 admin
        $where = ['nik' => $pengaju, 'is_active' => 1];
        $admin = BpdNikPeran::where($where)->get();
        // 
        $trx->status_id = $status_id;
        $trx->uraian = $request->uraian;
        // 
        $lampiran = $request->file('lampiran');
        $request->validate([
            'lampiran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        $extension = $lampiran->getClientOriginalExtension();
        $namafile = 'bpd_'.$pengaju.'_'.now()->getTimestampMs().'.'.$extension;
        $lampiran->move('public/assets/bpd/', $namafile);
        $trx->lampiran = $namafile;
        // 
        // CA Dalam Negeri
        // CA Luar Negeri
        if(in_array($new, ['ca_dalam', 'ca_luar'])){
            $trx->is_ca = 1;
            $trx->luar_dalam = $new;
        }
        // Reimbursement Dalam Negeri
        // Reimbursement Luar Negeri
        else if(in_array($new, ['reimburse', 'reimburse_luar'])){
            $trx->is_ca = 0;
            $trx->luar_dalam = $new;
        }
        if($status == 'insert'){
            $trx->save();
        }else{
            // dd($request->all(), $id_trx);
            $this->deleteToDoList(['id_trx'=>$id_trx, 'pic'=>'reject_pa', 'filename'=>'emails.bpd0']);
            $this->deleteToDoList(['id_trx'=>$id_trx, 'pic'=>'reject_realisasi_pa', 'filename'=>'emails.bpd0']);
            $this->deleteToDoList(['id_trx'=>$id_trx, 'pic'=>'reject_reimburse_pa', 'filename'=>'emails.bpd0']);
            $trx->update();
        }
        // TrxBpdLog
        $keterangan = 'new insert';
        if($status == 'update'){
            $keterangan = 'update data';
        }
        $this->insertLog($trx->id, $status_id, $pengaju, $keterangan);
        // TrxBpdDetailInfo
        $tanggal_mulai = $request->input('tanggal_mulai', []);
        $tanggal_akhir = $request->input('tanggal_akhir', []);
        $tujuan1 = array();
        $tujuan2 = array();
        // 
        $data = $request->all();
        foreach($data as $_id => $value){
            if(str_starts_with($_id, 'tujuan1_')){
                array_push($tujuan1, $value);
            }else if(str_starts_with($_id, 'tujuan2_')){
                array_push($tujuan2, $value);
            }
        }
        // 
        $jenis = 'ca';
        if(in_array($new, ['reimburse', 'reimburse_luar'])){
            $jenis = 'reimburse';
        }
        $bpd_info = ['id_trx'=>$trx->id, 'luar_dalam'=>$trx->luar_dalam, 'jenis'=>$jenis, 'tanggal_mulai'=>$tanggal_mulai, 'tanggal_akhir'=>$tanggal_akhir, 'tujuan1'=>$tujuan1, 'tujuan2'=>$tujuan2];
        // return response()->json( [ 'success' => true, 'request' => $request->all(), 'bpd_info' => $bpd_info ], 200 );
        $this->insertBpdInfo($bpd_info);
        // insertBpdBiaya bukan member atau reimburse pertama kali oleh pengaju
        if($is_leader<>2 || in_array($new, ['reimburse', 'reimburse_luar'])){
            $biaya = $request->input('biaya', []);
            $satuan = $request->input('satuan', []);
            $nominal = array();
            foreach($data as $_id => $value){
                if(str_starts_with($_id, 'nominal_')){
                    array_push($nominal, $value);
                }
            }
            $nominal = str_replace( ',', '', $nominal );
            $jenis = 'ca';
            $bpd_biaya = ['jenis'=>$jenis, 'id'=>$trx->id, 'biaya'=>$biaya, 'satuan'=>$satuan, 'nominal'=>$nominal];
            // jika pengajuan reimburse
            if(in_array($new, ['reimburse', 'reimburse_luar'])){
                $lampiran = array();
                foreach($data as $_id => $value){
                    if(str_starts_with($_id, 'lampiran_')){
                        array_push($lampiran, $value);
                    }
                }
                $jenis = 'reimburse';
                $bpd_biaya = ['jenis'=>$jenis, 'id'=>$trx->id, 'biaya'=>$biaya, 'satuan'=>$satuan, 'nominal'=>$nominal, 'lampiran'=>$lampiran];
            }
            $this->insertBpdBiaya($bpd_biaya);
        }
        $view = 'detail';
        if(in_array($new, ['reimburse', 'reimburse_luar'])){
            $view = 'reimburse';
        }
        // kirim email ke admin
        $email_to = array();
        if($status == 'insert'){
            foreach ($admin as $data){
                $where = ['nik' => $data->admin];
                $_karyawan = Karyawan::where($where)->first();
                $arr['email_to'] = $_karyawan->email;
                $arr['nama_to'] = $_karyawan->nama;
                $arr['nik_to'] = $_karyawan->nik;
                $arr['id_trx'] = $trx->id;
                $arr['id_status'] = $status_id;
                $arr['pic'] = $view;
                array_push($email_to, $arr);
            }
        }else{
            $arr['email_to'] = json_decode($trx->admin_property, true)['email'];
            $arr['nama_to'] = json_decode($trx->admin_property, true)['nama'];
            $arr['nik_to'] = json_decode($trx->admin_property, true)['nik'];
            $arr['id_trx'] = $trx->id;
            $arr['id_status'] = $status_id;
            $arr['pic'] = $view;
            array_push($email_to, $arr);
        }
        $email_info = ['pengaju_property'=>$trx->pengaju_property, 'atasan_property'=>$trx->atasan_property, 'luar_dalam'=>$trx->luar_dalam, 'email_to'=>$email_to, 'id'=>Crypt::encrypt($trx->id), 'view'=>$view];
        // return response()->json( [ 'success' => true, 'email_info' => $email_info ], 200 );
        $this->kirimEmail($email_info);
        // 
        return redirect('bpd')->with('msg', 'Data is saved successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        // dd($request->all(), $id);
        $area = Propinsi::whereNotIn('id', [0, 1])->orderBy('id', 'asc')->get();
        $negara = Negara::orderBy('negara', 'asc')->get();
        // 
        $no_info = 0;
        $no_info_realisasi = 0;
        $no_biaya = 0;
        $no_biaya_info = 0;
        $no_biaya_realisasi = 0;
        $no_biaya_info_realisasi = 0;
        $view = $request->view;
        $trxMaster = TrxBpdMaster::find($id);
        $status_id = $trxMaster->status_id;
        $is_project = $trxMaster->is_project;
        $luar_dalam = $trxMaster->luar_dalam;
        // 1 = 500240 = BIAYA UANG SAKU = Project
        // 2 = 500240 = BIAYA UANG SAKU = Support
        // $coa_all = BpdCoa::whereNotIn('id', [1,2])->orderBy('account', 'asc')->get();
        // $coa_0 = BpdCoa::whereIn('divisi', ['Support'])->whereNotIn('id', [1,2])->orderBy('account', 'asc')->get();
        // $coa_1 = BpdCoa::whereIn('divisi', ['Project'])->whereNotIn('id', [1,2])->orderBy('account', 'asc')->get();
        $coa_all = BpdCoa::orderBy('account', 'asc')->get();
        $coa_0 = BpdCoa::whereIn('divisi', ['Support'])->orderBy('account', 'asc')->get();
        $coa_1 = BpdCoa::whereIn('divisi', ['Project'])->orderBy('account', 'asc')->get();
        $coa = (object)['coa_all'=>$coa_all, 'coa_0'=>$coa_0, 'coa_1'=>$coa_1];
        // 
        $pengaju_property = json_decode($trxMaster->pengaju_property, true);
        $admin_property = json_decode($trxMaster->admin_property, true);
        $koordinator_property = json_decode($trxMaster->koordinator_property, true);
        $layer1_property = json_decode($trxMaster->layer1_property, true);
        $layer2_property = json_decode($trxMaster->layer2_property, true);
        $hco_property = json_decode($trxMaster->hco_property, true);
        // 
        $id_level = $pengaju_property['id_level'];
        // 
        $kelompok = array();
        $where = ['is_project' => 1];
        $_data = BpdKelompok::where($where)->get();
        foreach ($_data as $data){
            $_kelompok = explode(',', $data->level);
            if (in_array($id_level, $_kelompok)){
                $kelompok['project'] = $data->kelompok;
                break;
            }
        }
        $where = ['is_project' => 0];
        $_data = BpdKelompok::where($where)->get();
        foreach ($_data as $data){
            $_kelompok = explode(',', $data->level);
            if (in_array($id_level, $_kelompok)){
                $kelompok['nonproject'] = $data->kelompok;
                break;
            }
        }
        // 
        $where = ['id_trx' => $id, 'jenis' => 'ca'];
        if(in_array($luar_dalam, ['reimburse', 'reimburse_luar'])){
            $where = ['id_trx' => $id, 'jenis' => 'reimburse'];
        }
        $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
        // 
        // ca
        $where = ['id_trx' => $id, 'is_approve' => 1];
        $trxDetailBiaya = TrxBpdDetailBiaya::whereIn('jenis', ['ca', 'coa', 'reimburse'])->where($where)->where('kode_coa', 'NOT LIKE', '%500240%')->orderBy('id', 'asc')->get();
        // coa = 500240 = biaya personel
        // 500240 = BIAYA TRANSPORT/PERJ.DINAS/AKOMODASI
        $trxDetailBiayaPersonel = TrxBpdDetailBiaya::whereIn('jenis', ['ca', 'coa', 'reimburse'])->where($where)->where('kode_coa', 'LIKE', '%500240%')->orderBy('id', 'asc')->get();
        // 
        // realisasi
        // $trxDetailBiayaRealisasi = TrxBpdDetailBiaya::whereIn('jenis', ['realisasi', 'realisasi_coa'])->where($where)->where('kode_coa', 'NOT LIKE', '%500240%')->orderBy('id', 'asc')->get();
        $trxDetailBiayaRealisasi = TrxBpdDetailBiaya::whereIn('jenis', ['realisasi', 'realisasi_coa'])->where($where)->whereNull('id_info')->orderBy('id', 'asc')->get();
        // coa = 500240 = biaya personel
        // 500240 = BIAYA TRANSPORT/PERJ.DINAS/AKOMODASI
        $trxDetailBiayaPersonelRealisasi = TrxBpdDetailBiaya::whereIn('jenis', ['realisasi_coa'])->where($where)->where('kode_coa', 'LIKE', '%500240%')->orderBy('id', 'asc')->get();
        // 
        $linkAction = route('bpd.update', [Crypt::encrypt($id), 'view'=>$view]);
        $nik = auth()->user()->nik;
        // $where = ['pic' => $request->view, 'id_trx' => $id];
        // $nik = ToDoList::where($where)->first()->nik;
        // if(!empty(auth()->user())){
        //     $nik = auth()->user()->nik;    
        // }
        // dd($nik);
        // daftar Project Coordinator
        $where = ['is_active' => 1];
        // $_koordinator = BpdNikPC::where($where)->get();
        $_koordinator = BpdNikPC::where($where)->whereIn('nik', Karyawan::select('nik')->where(['is_aktif' => 1])->get())->get();
        $koordinator = array();
        foreach ($_koordinator as $data){
            $where = ['nik' => $data->nik];
            array_push($koordinator, Karyawan::where($where)->first());
        }
        // 
        $where = [ 'id_trx' => $id ];
        $history = TrxBpdLog::where($where)->orderBy('id','desc')->get();
        // 
        // $where = ['id_trx' => $id, 'jenis' => 'realisasi'];
        // $trxDetailBiayaRealisasi = TrxBpdDetailBiaya::where($where)->orderBy('id', 'asc')->get();
        // $where = ['id_trx' => $id, 'jenis' => 'realisasi_coa'];
        // $trxDetailBiayaRealisasiCoa = TrxBpdDetailBiaya::where($where)->orderBy('id', 'asc')->get();
        // 
        $luar_dalam = $trxMaster->luar_dalam;
        // jumlah ttd dalam 1 baris
        $pembagi = 4;
        // 
        switch ($view) {
            // 5 = CA Ditolak Admin
            case 'reject_pa':
                if($status_id <> 5){
                    return view('emails.bpd_ok');
                    break;
                }
                $status = 'update';
                $data = ['status'=>$status, 'luar_dalam'=>$trxMaster->luar_dalam, 'is_leader'=>$trxMaster->is_leader, 'pengaju_property'=>$trxMaster->pengaju_property, 'atasan_property'=>$trxMaster->atasan_property, 'kelompok'=>$kelompok, 'periode'=>$trxMaster->periode, 'mgt'=>0];
                $where = [ 'id_trx' => $id, 'id_status' => 5 ];
                $catatan = TrxBpdLog::where($where)->orderBy('created_at','desc')->first()->keterangan;
                $where = ['id_trx' => $id];
                $trxDetailBiaya = TrxBpdDetailBiaya::where($where)->whereIn('jenis', ['ca', 'coa', 'reimburse'])->orderBy('id', 'asc')->get();
                return view( 'formulir.bpd.upsert', compact('catatan', 'data', 'area', 'negara', 'trxMaster', 'trxDetailInfo', 'trxDetailBiaya'));
                break;
            // 8 = CA Ditolak PC
            case 'reject_pc':
                $where = [ 'id_trx' => $id, 'id_status' => 8 ];
                $catatan = TrxBpdLog::where($where)->orderBy('created_at','desc')->first()->keterangan;
                // 8 = CA Ditolak PC
                if($status_id <> 8){
                    return view('emails.bpd_ok');
                    break;
                }
                // menampilkan input biaya oleh pengaju / admin
                $where = ['id_trx' => $id];
                $trxDetailBiaya = TrxBpdDetailBiaya::where($where)->whereNull('id_info')->orderBy('id', 'asc')->get();
                // 15 = Perbaikan CA oleh Admin
                // 5 = CA Ditolak Admin
                $approve = 15;
                $reject = 5;
                // 
                return view('formulir.bpd.view', compact('catatan', 'trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            // 10 = CA Ditolak L1
            case 'reject_l1':
                $where = [ 'id_trx' => $id, 'id_status' => 10 ];
                $catatan = TrxBpdLog::where($where)->orderBy('created_at','desc')->first()->keterangan;
                // 10 = CA Ditolak L1
                if($status_id <> 10){
                    return view('emails.bpd_ok');
                    break;
                }
                // menampilkan input biaya oleh pengaju / admin
                $where = ['id_trx' => $id];
                $trxDetailBiaya = TrxBpdDetailBiaya::where($where)->whereNull('id_info')->orderBy('id', 'asc')->get();
                // 15 = Perbaikan CA oleh Admin
                // 5 = CA Ditolak Admin
                $approve = 15;
                $reject = 5;
                // 
                return view('formulir.bpd.view', compact('catatan', 'trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            // 12 = CA Ditolak L2
            case 'reject_l2':
                $where = [ 'id_trx' => $id, 'id_status' => 12 ];
                $catatan = TrxBpdLog::where($where)->orderBy('created_at','desc')->first()->keterangan;
                // 12 = CA Ditolak L2
                if($status_id <> 12){
                    return view('emails.bpd_ok');
                    break;
                }
                // menampilkan input biaya oleh pengaju / admin
                $where = ['id_trx' => $id];
                $trxDetailBiaya = TrxBpdDetailBiaya::where($where)->whereNull('id_info')->orderBy('id', 'asc')->get();
                // 15 = Perbaikan CA oleh Admin
                // 5 = CA Ditolak Admin
                $approve = 15;
                $reject = 5;
                // 
                return view('formulir.bpd.view', compact('catatan', 'trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            // 14 = CA Ditolak PC
            case 'reject_hco':
                $where = [ 'id_trx' => $id, 'id_status' => 14 ];
                $catatan = TrxBpdLog::where($where)->orderBy('created_at','desc')->first()->keterangan;
                // 14 = CA Ditolak PC
                if($status_id <> 14){
                    return view('emails.bpd_ok');
                    break;
                }
                // menampilkan input biaya oleh pengaju / admin
                $where = ['id_trx' => $id];
                $trxDetailBiaya = TrxBpdDetailBiaya::where($where)->whereNull('id_info')->orderBy('id', 'asc')->get();
                // 15 = Perbaikan CA oleh Admin
                // 5 = CA Ditolak Admin
                $approve = 15;
                $reject = 5;
                // 
                return view('formulir.bpd.view', compact('catatan', 'trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            // history pengajuan bpd
            case 'history':
                // 0 = NA
                $approve = 0;
                $reject = 0;
                return view('formulir.bpd.history', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            // cancel pengajuan bpd
            case 'cancel':
                // 0 = NA
                $approve = 48;
                $reject = 0;
                return view('formulir.bpd.cancel', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            case 'edit':
                // 4=CA Disetujui Admin
                // 15=Perbaikan CA oleh Admin
                // 19=Realisasi Disetujui Admin
                // 30=Perbaikan Realisasi oleh Admin
                // 35=Reimburse Disetujui Admin
                // 46=Perbaikan Reimburse oleh Admin
                if($status_id <> 4 && $status_id <> 15 && $status_id <> 19  && $status_id <> 30  && $status_id <> 35 && $status_id <> 46){
                    return view('emails.bpd_ok');
                    break;
                }
                if($status_id == 4 || $status_id == 15 || $status_id == 35 || $status_id == 46){
                    return view('formulir.bpd.edit', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'no_info', 'no_biaya', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                }else if($status_id == 19 || $status_id == 30){
                    $where = ['id_trx' => $id, 'jenis' => 'ca'];
                    $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                    $where = ['id_trx' => $id, 'jenis' => 'realisasi'];
                    $trxDetailInfoRealisasi = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                    return view('formulir.bpd.edit_realisasi', compact('trxMaster', 'trxDetailInfo', 'trxDetailInfoRealisasi', 'trxDetailBiaya', 'trxDetailBiayaRealisasi', 'trxDetailBiayaPersonelRealisasi', 'kelompok', 'no_info', 'no_info_realisasi', 'no_biaya', 'no_biaya_realisasi', 'no_biaya_info_realisasi', 'nik', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                }
                break;
            // proses CA
            case 'detail':
                // 2 = Pengajuan CA
                if($status_id <> 2 && $status_id <> 6){
                    return view('emails.bpd_ok');
                    break;
                }
                // 3 = CA Diproses PA
                // 5 = CA Ditolak PA
                $approve = 3;
                $reject = 5;
                // 
                $where = ['id_trx' => $id, 'jenis' => 'ca'];
                $trxDetailBiaya = TrxBpdDetailBiaya::where($where)->orderBy('id', 'asc')->get();
                return view('formulir.bpd.view', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            // pengajuan CA
            case 'approval_pa':
                // 3 = CA Diproses Admin
                if($status_id <> 3){
                    return view('emails.bpd_ok');
                    break;
                }
                // 4 = CA Disetujui PA
                // 5 = CA Ditolak PA
                $approve = 4;
                $reject = 5;
                // 
                if(count($trxDetailBiaya)==0){
                    $where = ['id_trx' => $id, 'jenis' => 'ca'];
                    $trxDetailBiaya = TrxBpdDetailBiaya::where($where)->orderBy('id', 'asc')->get();
                }
                return view('formulir.bpd.view', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            case 'approval_pc':
                // 4 = CA Disetujui Admin
                // 15 = Perbaikan CA oleh Admin
                if($status_id <> 4 && $status_id <> 15){
                    return view('emails.bpd_ok');
                    break;
                }
                // 7 = CA Disetujui PC
                // 8 = CA Ditolak PC
                $approve = 7;
                $reject = 8;
                return view('formulir.bpd.view', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                // return view('formulir.bpd.view_approval', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            case 'approval_l1':
                // 7 = CA Disetujui PC
                // 15 = Perbaikan CA oleh Admin
                // is_project = 1
                if($status_id <> 7 && $status_id <> 15 && $is_project == 1){
                    return view('emails.bpd_ok');
                    break;
                }
                // 4 = CA Disetujui Admin
                // 15 = Perbaikan CA oleh Admin
                // is_project = 0
                else if($status_id <> 4 && $status_id <> 15 && $is_project == 0){
                    return view('emails.bpd_ok');
                    break;
                }
                // 9 = CA Disetujui L1
                // 10 = CA Ditolak L1
                $approve = 9;
                $reject = 10;
                return view('formulir.bpd.view', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                // return view('formulir.bpd.view_approval', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            case 'approval_l2':
                // 9 = CA Disetujui L1
                if($status_id <> 9){
                    return view('emails.bpd_ok');
                    break;
                }
                // 11 = CA Disetujui L2
                // 12 = CA Ditolak L2
                $approve = 11;
                $reject = 12;
                return view('formulir.bpd.view', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                // return view('formulir.bpd.view_approval', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            case 'approval_hco':
                // 11 = CA Disetujui L2
                // 13 = CA Disetujui PC
                if($status_id <> 11 && $status_id <> 13){
                    return view('emails.bpd_ok');
                    break;
                }
                // 13 = CA Disetujui PC
                // 14 = CA Ditolak PC
                $approve = 13;
                $reject = 14;
                return view('formulir.bpd.view', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                // return view('formulir.bpd.view_approval', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            case 'view_ca':
                $approve = 0;
                $reject = 0;
                return view('formulir.bpd.detail_ca', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            case 'pdf_draft':
                if(count($trxDetailBiaya)==0){
                    $where = ['id_trx' => $id, 'jenis' => 'ca'];
                    if(in_array($luar_dalam, ['reimburse', 'reimburse_luar'])){
                        $where = ['id_trx' => $id, 'jenis' => 'reimburse'];
                    }
                    $trxDetailBiaya = TrxBpdDetailBiaya::where($where)->orderBy('id', 'asc')->get();
                }
                // 0 = NA
                $approve = 0;
                $reject = 0;
                // print to pdf
                $waktu = date('YmdHis');
                // start
                $htmlLog = '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0"><tr>';
                // pengaju
                $where = ['id_trx' => $id, 'nik_pic' => $trxMaster->pengaju];
                // 2 = Pengajuan CA
                // 6 = Perbaikan CA oleh Pengaju
                $bpdLog = TrxBpdLog::where($where)->whereIn('id_status', [2,6])->orderBy('id', 'desc')->first();
                // 33 = Pengajuan Reimburse
                // 37 = Perbaikan Reimburse oleh Pengaju
                if(in_array($luar_dalam, ['reimburse', 'reimburse_luar'])){
                    $bpdLog = TrxBpdLog::where($where)->whereIn('id_status', [33,37])->orderBy('id', 'desc')->first();
                }
                if (!empty($bpdLog)) {
                    $htmlLog .= '<td>Mengajukan<br/><img src="' . public_path('assets/images/matta/requested_new.png') . '" height="30" /><br/>' . $pengaju_property['nama'] . '<br/>' . $pengaju_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                }
                // admin
                $where = ['id_trx' => $id, 'nik_pic' => $trxMaster->admin];
                // 4 = CA Disetujui Admin
                // 5 = CA Ditolak Admin
                $bpdLog = TrxBpdLog::where($where)->whereIn('id_status', [4,5])->orderBy('id', 'desc')->first();
                if (!empty($bpdLog)) {
                    if($bpdLog->id_status == 4){
                        $htmlLog .= '<td>Memeriksa<br/><img src="' . public_path('assets/images/matta/verified_new.png') . '" height="30" /><br/>' . $admin_property['nama'] . '<br/>' . $admin_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                    }else if($bpdLog->id_status == 5){
                        $htmlLog .= '<td>Memeriksa<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $admin_property['nama'] . '<br/>' . $admin_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                    }
                }
                // 35 = Reimburse Disetujui Admin
                // 36 = Reimburse Ditolak Admin
                $bpdLog = TrxBpdLog::where($where)->whereIn('id_status', [35,36])->orderBy('id', 'desc')->first();
                if (!empty($bpdLog)) {
                    if($bpdLog->id_status == 35){
                        $htmlLog .= '<td>Memeriksa<br/><img src="' . public_path('assets/images/matta/verified_new.png') . '" height="30" /><br/>' . $admin_property['nama'] . '<br/>' . $admin_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                    }else if($bpdLog->id_status == 36){
                        $htmlLog .= '<td>Memeriksa<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $admin_property['nama'] . '<br/>' . $admin_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                    }
                }
                // finish
                $htmlLog .= '</tr></table>';
                // 
                $filename = $waktu . '_bpd.pdf';
                $view = \View::make('formulir.bpd.pdf_draft', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info', 'htmlLog'));
                $html = $view->render();
                $pdf = new TCPDF;
                $pdf::SetTitle('Formulir BPD');
                $pdf::SetFont('times', '', 10, '', true);
                $pdf::AddPage();
                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output(public_path($filename), 'I');
                return response()->download(public_path($filename));
                break;
            // Pengajuan CA
            case 'pdf':
                // 0 = NA
                $approve = 0;
                $reject = 0;
                // print to pdf
                $waktu = date('YmdHis');
                // start
                $arr_htmlLog = array();
                $htmlLog = '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">';
                // pengaju
                $where = ['id_trx' => $id, 'nik_pic' => $trxMaster->pengaju];
                // 2 = Pengajuan CA
                // 6 = Perbaikan CA oleh Pengaju
                $bpdLog = TrxBpdLog::where($where)->whereIn('id_status', [2,6])->orderBy('id', 'desc')->first();
                if (!empty($bpdLog)) {
                    $_htmlLog = '<td>Mengajukan<br/><img src="' . public_path('assets/images/matta/requested_new.png') . '" height="30" /><br/>' . $pengaju_property['nama'] . '<br/>' . $pengaju_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                    array_push($arr_htmlLog, $_htmlLog);
                }
                // admin
                $where = ['id_trx' => $id, 'nik_pic' => $trxMaster->admin];
                // 4 = CA Disetujui Admin
                // 5 = CA Ditolak Admin
                // 15 = Perbaikan CA oleh Admin
                $bpdLog = TrxBpdLog::where($where)->whereIn('id_status', [4,5,15])->orderBy('id', 'desc')->first();
                if (!empty($bpdLog)) {
                    if($bpdLog->id_status == 4 || $bpdLog->id_status == 15){
                        $_htmlLog = '<td>Memeriksa<br/><img src="' . public_path('assets/images/matta/verified_new.png') . '" height="30" /><br/>' . $admin_property['nama'] . '<br/>' . $admin_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }else if($bpdLog->id_status == 5){
                        $_htmlLog = '<td>Memeriksa<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $admin_property['nama'] . '<br/>' . $admin_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }
                }
                // koordinator
                $where = ['id_trx' => $id, 'nik_pic' => $trxMaster->koordinator];
                // 7 = CA Disetujui PC
                // 8 = CA Ditolak PC
                $bpdLog = TrxBpdLog::where($where)->whereIn('id_status', [7,8])->orderBy('id', 'desc')->first();
                if (!empty($bpdLog)) {
                    if($bpdLog->id_status == 7){
                        $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $koordinator_property['nama'] . '<br/>' . $koordinator_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }else if($bpdLog->id_status == 8){
                        $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $koordinator_property['nama'] . '<br/>' . $koordinator_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }
                }
                // layer1
                $where = ['id_trx' => $id, 'nik_pic' => $trxMaster->layer1];
                // 9 = CA Disetujui L1
                // 10 = CA Ditolak L1
                $bpdLog = TrxBpdLog::where($where)->whereIn('id_status', [9,10])->orderBy('id', 'desc')->first();
                if (!empty($bpdLog)) {
                    if($bpdLog->id_status == 9){
                        $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $layer1_property['nama'] . '<br/>' . $layer1_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }else if($bpdLog->id_status == 10){
                        $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $layer1_property['nama'] . '<br/>' . $layer1_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }
                }
                // layer2
                $where = ['id_trx' => $id, 'nik_pic' => $trxMaster->layer2];
                // 11 = CA Disetujui L2
                // 12 = CA Ditolak L2
                $bpdLog = TrxBpdLog::where($where)->whereIn('id_status', [11,12])->orderBy('id', 'desc')->first();
                if (!empty($bpdLog)) {
                    if($bpdLog->id_status == 11){
                        $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $layer2_property['nama'] . '<br/>' . $layer2_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }else if($bpdLog->id_status == 12){
                        $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $layer2_property['nama'] . '<br/>' . $layer2_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }
                }
                // hco
                $where = ['id_trx' => $id, 'nik_pic' => $trxMaster->hco];
                // 13 = CA Disetujui PC
                // 14 = CA Ditolak PC
                $bpdLog = TrxBpdLog::where($where)->whereIn('id_status', [13,14])->orderBy('id', 'desc')->first();
                if (!empty($bpdLog)) {
                    if($bpdLog->id_status == 13){
                        $_htmlLog = '<td>Mengetahui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $hco_property['nama'] . '<br/>' . $hco_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }else if($bpdLog->id_status == 14){
                        $_htmlLog = '<td>Mengetahui<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $hco_property['nama'] . '<br/>' . $layer2_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }
                }
                // finish
                // start looping satu baris terdiri dari $pembagi
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
                $filename = $waktu . '_bpd.pdf';
                $view = \View::make('formulir.bpd.pdf', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info', 'htmlLog'));
                $html = $view->render();
                $pdf = new TCPDF;
                $pdf::SetTitle('Formulir BPD');
                $pdf::SetFont('times', '', 10, '', true);
                $pdf::AddPage();
                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output(public_path($filename), 'I');
                return response()->download(public_path($filename));
                break;
            // 20 = Realisasi Ditolak Admin
            case 'reject_realisasi_pa':
                if($status_id <> 20){
                    return view('emails.bpd_ok');
                    break;
                }
                $where = [ 'id_trx' => $id, 'id_status' => 20 ];
                $catatan = TrxBpdLog::where($where)->orderBy('created_at','desc')->first()->keterangan;
                $where = ['id_trx' => $id, 'jenis' => 'ca'];
                $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                $where = ['id_trx' => $id, 'jenis' => 'realisasi'];
                $trxDetailInfoRealisasi = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                // 21 = Perbaikan Realisasi oleh Pengaju
                $approve = 21;
                $reject = 0;
                return view('formulir.bpd.realisasi', compact('catatan', 'trxMaster', 'trxDetailInfo', 'trxDetailInfoRealisasi', 'trxDetailBiaya', 'trxDetailBiayaRealisasi', 'kelompok', 'no_info', 'no_info_realisasi', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info', 'no_biaya_realisasi', 'area', 'negara'));
                break;
            // 23 = Realisasi Ditolak PC
            case 'reject_realisasi_pc':
                if($status_id <> 23){
                    return view('emails.bpd_ok');
                    break;
                }
                $where = [ 'id_trx' => $id, 'id_status' => 23 ];
                $catatan = TrxBpdLog::where($where)->orderBy('created_at','desc')->first()->keterangan;
                $where = ['id_trx' => $id, 'jenis' => 'ca'];
                $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                $where = ['id_trx' => $id, 'jenis' => 'realisasi'];
                $trxDetailInfoRealisasi = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                // 30 = Perbaikan Realisasi oleh Admin
                $approve = 30;
                $reject = 20;
                return view('formulir.bpd.realisasi_review', compact('catatan', 'trxMaster', 'trxDetailInfo', 'trxDetailInfoRealisasi', 'trxDetailBiaya', 'trxDetailBiayaRealisasi', 'trxDetailBiayaPersonelRealisasi', 'kelompok', 'no_info', 'no_info_realisasi', 'no_biaya', 'no_biaya_realisasi', 'no_biaya_info_realisasi', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            // 25 = Realisasi Ditolak L1
            case 'reject_realisasi_l1':
                if($status_id <> 25){
                    return view('emails.bpd_ok');
                    break;
                }
                $where = [ 'id_trx' => $id, 'id_status' => 25 ];
                $catatan = TrxBpdLog::where($where)->orderBy('created_at','desc')->first()->keterangan;
                $where = ['id_trx' => $id, 'jenis' => 'ca'];
                $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                $where = ['id_trx' => $id, 'jenis' => 'realisasi'];
                $trxDetailInfoRealisasi = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                // 30 = Perbaikan Realisasi oleh Admin
                $approve = 30;
                $reject = 20;
                return view('formulir.bpd.realisasi_review', compact('catatan', 'trxMaster', 'trxDetailInfo', 'trxDetailInfoRealisasi', 'trxDetailBiaya', 'trxDetailBiayaRealisasi', 'trxDetailBiayaPersonelRealisasi', 'kelompok', 'no_info', 'no_info_realisasi', 'no_biaya', 'no_biaya_realisasi', 'no_biaya_info_realisasi', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            // 27 = Realisasi Ditolak L2
            case 'reject_realisasi_l2':
                if($status_id <> 27){
                    return view('emails.bpd_ok');
                    break;
                }
                $where = [ 'id_trx' => $id, 'id_status' => 27 ];
                $catatan = TrxBpdLog::where($where)->orderBy('created_at','desc')->first()->keterangan;
                $where = ['id_trx' => $id, 'jenis' => 'ca'];
                $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                $where = ['id_trx' => $id, 'jenis' => 'realisasi'];
                $trxDetailInfoRealisasi = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                // 30 = Perbaikan Realisasi oleh Admin
                $approve = 30;
                $reject = 20;
                return view('formulir.bpd.realisasi_review', compact('catatan', 'trxMaster', 'trxDetailInfo', 'trxDetailInfoRealisasi', 'trxDetailBiaya', 'trxDetailBiayaRealisasi', 'trxDetailBiayaPersonelRealisasi', 'kelompok', 'no_info', 'no_info_realisasi', 'no_biaya', 'no_biaya_realisasi', 'no_biaya_info_realisasi', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            // 29 = Realisasi Ditolak PC
            case 'reject_realisasi_hco':
                if($status_id <> 29){
                    return view('emails.bpd_ok');
                    break;
                }
                $where = [ 'id_trx' => $id, 'id_status' => 29 ];
                $catatan = TrxBpdLog::where($where)->orderBy('created_at','desc')->first()->keterangan;
                $where = ['id_trx' => $id, 'jenis' => 'ca'];
                $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                $where = ['id_trx' => $id, 'jenis' => 'realisasi'];
                $trxDetailInfoRealisasi = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                // 30 = Perbaikan Realisasi oleh Admin
                $approve = 30;
                $reject = 20;
                return view('formulir.bpd.realisasi_review', compact('catatan', 'trxMaster', 'trxDetailInfo', 'trxDetailInfoRealisasi', 'trxDetailBiaya', 'trxDetailBiayaRealisasi', 'trxDetailBiayaPersonelRealisasi', 'kelompok', 'no_info', 'no_info_realisasi', 'no_biaya', 'no_biaya_realisasi', 'no_biaya_info_realisasi', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            // realisasi CA
            case 'realisasi':
                // $where = ['id_trx' => $id, 'jenis' => 'realisasi'];
                // $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                // 18 = Realisasi CA
                $approve = 18;
                $reject = 0;
                return view('formulir.bpd.realisasi', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info', 'area', 'negara'));
                break;
            // review realisasi CA oleh project admin
            case 'realisasi_pa':
                $where = ['id_trx' => $id, 'jenis' => 'ca'];
                $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                $where = ['id_trx' => $id, 'jenis' => 'realisasi'];
                $trxDetailInfoRealisasi = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                // 18 = Realisasi CA
                if($status_id <> 18 && $status_id <> 21){
                    return view('emails.bpd_ok');
                    break;
                }
                // 19 = Realisasi Disetujui Admin
                // 20 = Realisasi Ditolak Admin
                $approve = 19;
                $reject = 20;
                return view('formulir.bpd.realisasi_review', compact('trxMaster', 'trxDetailInfo', 'trxDetailInfoRealisasi', 'trxDetailBiaya', 'trxDetailBiayaRealisasi', 'trxDetailBiayaPersonelRealisasi', 'kelompok', 'no_info', 'no_info_realisasi', 'no_biaya', 'no_biaya_realisasi', 'no_biaya_info_realisasi', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            // review realisasi CA oleh project coordinator
            case 'realisasi_pc':
                $where = ['id_trx' => $id, 'jenis' => 'ca'];
                $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                $where = ['id_trx' => $id, 'jenis' => 'realisasi'];
                $trxDetailInfoRealisasi = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                // 19 = Realisasi Disetujui Admin
                // 30 = Perbaikan Realisasi oleh Admin
                if($status_id <> 19 && $status_id <> 30){
                    return view('emails.bpd_ok');
                    break;
                }
                // 22 = Realisasi Disetujui PC
                // 23 = Realisasi Ditolak PC
                $approve = 22;
                $reject = 23;
                return view('formulir.bpd.realisasi_approval', compact('trxMaster', 'trxDetailInfo', 'trxDetailInfoRealisasi', 'trxDetailBiaya', 'trxDetailBiayaRealisasi', 'trxDetailBiayaPersonelRealisasi', 'kelompok', 'no_info', 'no_info_realisasi', 'no_biaya', 'no_biaya_realisasi', 'no_biaya_info_realisasi', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            // review realisasi CA oleh layer 1
            case 'realisasi_l1':
                $where = ['id_trx' => $id, 'jenis' => 'ca'];
                $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                $where = ['id_trx' => $id, 'jenis' => 'realisasi'];
                $trxDetailInfoRealisasi = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                // 22 = Realisasi Disetujui PC
                if($status_id <> 22 && $status_id <> 30 && $is_project == 1){
                    return view('emails.bpd_ok');
                    break;
                }
                // 19 = Realisasi Disetujui Admin
                else if($status_id <> 19 && $status_id <> 30 && $is_project == 0){
                    return view('emails.bpd_ok');
                    break;
                }
                // 24 = Realisasi Disetujui L1
                // 25 = Realisasi Ditolak L1
                $approve = 24;
                $reject = 25;
                return view('formulir.bpd.realisasi_approval', compact('trxMaster', 'trxDetailInfo', 'trxDetailInfoRealisasi', 'trxDetailBiaya', 'trxDetailBiayaRealisasi', 'trxDetailBiayaPersonelRealisasi', 'kelompok', 'no_info', 'no_info_realisasi', 'no_biaya', 'no_biaya_realisasi', 'no_biaya_info_realisasi', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            // review realisasi CA oleh layer 2
            case 'realisasi_l2':
                $where = ['id_trx' => $id, 'jenis' => 'ca'];
                $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                $where = ['id_trx' => $id, 'jenis' => 'realisasi'];
                $trxDetailInfoRealisasi = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                // 24 = Realisasi Disetujui L1
                if($status_id <> 24){
                    return view('emails.bpd_ok');
                    break;
                }
                // 26 = Realisasi Disetujui L2
                // 27 = Realisasi Ditolak L2
                $approve = 26;
                $reject = 27;
                return view('formulir.bpd.realisasi_approval', compact('trxMaster', 'trxDetailInfo', 'trxDetailInfoRealisasi', 'trxDetailBiaya', 'trxDetailBiayaRealisasi', 'trxDetailBiayaPersonelRealisasi', 'kelompok', 'no_info', 'no_info_realisasi', 'no_biaya', 'no_biaya_realisasi', 'no_biaya_info_realisasi', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            // review realisasi CA oleh hco
            case 'realisasi_hco':
                $where = ['id_trx' => $id, 'jenis' => 'ca'];
                $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                $where = ['id_trx' => $id, 'jenis' => 'realisasi'];
                $trxDetailInfoRealisasi = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                // 26 = Realisasi Disetujui L2
                if($status_id <> 26 && $status_id <> 28){
                    return view('emails.bpd_ok');
                    break;
                }
                // 28 = Realisasi Disetujui PC
                // 29 = Realisasi Ditolak PC
                $approve = 28;
                $reject = 29;
                return view('formulir.bpd.realisasi_approval', compact('trxMaster', 'trxDetailInfo', 'trxDetailInfoRealisasi', 'trxDetailBiaya', 'trxDetailBiayaRealisasi', 'trxDetailBiayaPersonelRealisasi', 'kelompok', 'no_info', 'no_info_realisasi', 'no_biaya', 'no_biaya_realisasi', 'no_biaya_info_realisasi', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            case 'view_realisasi':
                $where = ['id_trx' => $id, 'jenis' => 'ca'];
                $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                $where = ['id_trx' => $id, 'jenis' => 'realisasi'];
                $trxDetailInfoRealisasi = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                $approve = 0;
                $reject = 0;
                return view('formulir.bpd.detail_realisasi', compact('trxMaster', 'trxDetailInfo', 'trxDetailInfoRealisasi', 'trxDetailBiaya', 'trxDetailBiayaRealisasi', 'trxDetailBiayaPersonelRealisasi', 'kelompok', 'no_info', 'no_info_realisasi', 'no_biaya', 'no_biaya_realisasi', 'no_biaya_info_realisasi', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            case 'pdf_realisasi':
                $where = ['id_trx' => $id, 'jenis' => 'realisasi'];
                $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                // 0 = NA
                $approve = 0;
                $reject = 0;
                // print to pdf
                $waktu = date('YmdHis');
                // start
                $arr_htmlLog = array();
                $htmlLog = '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">';
                // pengaju
                $where = ['id_trx' => $id, 'nik_pic' => $trxMaster->pengaju];
                // 18 = Realisasi CA
                // 21 = Perbaikan Realisasi oleh Pengaju
                $bpdLog = TrxBpdLog::where($where)->whereIn('id_status', [18,21])->orderBy('id', 'desc')->first();
                if (!empty($bpdLog)) {
                    $_htmlLog = '<td>Mengajukan<br/><img src="' . public_path('assets/images/matta/requested_new.png') . '" height="30" /><br/>' . $pengaju_property['nama'] . '<br/>' . $pengaju_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                    array_push($arr_htmlLog, $_htmlLog);
                }
                // admin
                $where = ['id_trx' => $id, 'nik_pic' => $trxMaster->admin];
                // 19 = Realisasi Disetujui Admin
                // 20 = Realisasi Ditolak Admin
                // 30 = Perbaikan Realisasi oleh Admin
                $bpdLog = TrxBpdLog::where($where)->whereIn('id_status', [19,20,30])->orderBy('id', 'desc')->first();
                if (!empty($bpdLog)) {
                    if($bpdLog->id_status == 19 || $bpdLog->id_status == 30){
                        $_htmlLog = '<td>Memeriksa<br/><img src="' . public_path('assets/images/matta/verified_new.png') . '" height="30" /><br/>' . $admin_property['nama'] . '<br/>' . $admin_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }else if($bpdLog->id_status == 20){
                        $_htmlLog = '<td>Memeriksa<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $admin_property['nama'] . '<br/>' . $admin_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }
                }
                // koordinator
                $where = ['id_trx' => $id, 'nik_pic' => $trxMaster->koordinator];
                // 22 = Realisasi Disetujui PC
                // 23 = Realisasi Ditolak PC
                // 30 = Perbaikan Realisasi oleh Admin
                $bpdLog = TrxBpdLog::where($where)->whereIn('id_status', [22,23])->orderBy('id', 'desc')->first();
                if (!empty($bpdLog)) {
                    if($bpdLog->id_status == 22){
                        $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $koordinator_property['nama'] . '<br/>' . $koordinator_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }else if($bpdLog->id_status == 23){
                        $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $koordinator_property['nama'] . '<br/>' . $koordinator_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }
                }
                // layer1
                $where = ['id_trx' => $id, 'nik_pic' => $trxMaster->layer1];
                // 24 = Realisasi Disetujui L1
                // 25 = Realisasi Ditolak L1
                $bpdLog = TrxBpdLog::where($where)->whereIn('id_status', [24,25])->orderBy('id', 'desc')->first();
                if (!empty($bpdLog)) {
                    if($bpdLog->id_status == 24){
                        $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $layer1_property['nama'] . '<br/>' . $layer1_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }else if($bpdLog->id_status == 25){
                        $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $layer1_property['nama'] . '<br/>' . $layer1_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }
                }
                // layer2
                $where = ['id_trx' => $id, 'nik_pic' => $trxMaster->layer2];
                // 26 = Realisasi Disetujui L2
                // 27 = Realisasi Ditolak L2
                $bpdLog = TrxBpdLog::where($where)->whereIn('id_status', [26,27])->orderBy('id', 'desc')->first();
                if (!empty($bpdLog)) {
                    if($bpdLog->id_status == 26){
                        $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $layer2_property['nama'] . '<br/>' . $layer2_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }else if($bpdLog->id_status == 27){
                        $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $layer2_property['nama'] . '<br/>' . $layer2_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }
                }
                // hco
                $where = ['id_trx' => $id, 'nik_pic' => $trxMaster->hco];
                // 28 = Realisasi Disetujui PC
                // 29 = Realisasi Ditolak PC
                $bpdLog = TrxBpdLog::where($where)->whereIn('id_status', [28,29])->orderBy('id', 'desc')->first();
                if (!empty($bpdLog)) {
                    if($bpdLog->id_status == 28){
                        $_htmlLog = '<td>Mengetahui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $hco_property['nama'] . '<br/>' . $hco_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }else if($bpdLog->id_status == 29){
                        $_htmlLog = '<td>Mengetahui<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $hco_property['nama'] . '<br/>' . $layer2_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }
                }
                // finish
                // start looping satu baris terdiri dari $pembagi
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
                $filename = $waktu . '_bpd.pdf';
                $view = \View::make('formulir.bpd.pdf_realisasi', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info', 'htmlLog', 'trxDetailBiayaPersonelRealisasi', 'trxDetailBiayaRealisasi'));
                $html = $view->render();
                $pdf = new TCPDF;
                $pdf::SetTitle('Formulir BPD');
                $pdf::SetFont('times', '', 10, '', true);
                $pdf::AddPage();
                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output(public_path($filename), 'I');
                return response()->download(public_path($filename));
                break;
            // 36 = Reimburse Ditolak Admin
            case 'reject_reimburse_pa':
                if($status_id <> 36){
                    return view('emails.bpd_ok');
                    break;
                }
                $status = 'update';
                $data = ['status'=>$status, 'luar_dalam'=>$trxMaster->luar_dalam, 'is_leader'=>$trxMaster->is_leader, 'pengaju_property'=>$trxMaster->pengaju_property, 'atasan_property'=>$trxMaster->atasan_property, 'kelompok'=>$kelompok, 'periode'=>$trxMaster->periode, 'mgt'=>0];
                $where = [ 'id_trx' => $id, 'id_status' => 36 ];
                $catatan = TrxBpdLog::where($where)->orderBy('created_at','desc')->first()->keterangan;
                $where = ['id_trx' => $id];
                $trxDetailBiaya = TrxBpdDetailBiaya::where($where)->whereIn('jenis', ['ca', 'coa', 'reimburse'])->orderBy('id', 'asc')->get();
                return view( 'formulir.bpd.reimburse', compact('catatan', 'data', 'area', 'negara', 'trxMaster', 'trxDetailInfo', 'trxDetailBiaya'));
                break;
            // 39 = Reimburse Ditolak PC
            case 'reject_reimburse_pc':
                // 39 = Reimburse Ditolak PC
                if($status_id <> 39){
                    return view('emails.bpd_ok');
                    break;
                }
                $where = [ 'id_trx' => $id, 'id_status' => 39 ];
                $catatan = TrxBpdLog::where($where)->orderBy('created_at','desc')->first()->keterangan;
                // menampilkan input biaya oleh pengaju / admin
                $where = ['id_trx' => $id];
                $trxDetailBiaya = TrxBpdDetailBiaya::where($where)->whereNull('id_info')->orderBy('id', 'asc')->get();
                // 46 = Perbaikan Reimburse oleh Admin
                // 36 = Reimburse Ditolak Admin
                $approve = 46;
                $reject = 36;
                // 
                return view('formulir.bpd.reimburse_view', compact('catatan', 'trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            // 41 = Reimburse Ditolak L1
            case 'reject_reimburse_l1':
                // 41 = Reimburse Ditolak L1
                if($status_id <> 41){
                    return view('emails.bpd_ok');
                    break;
                }
                $where = [ 'id_trx' => $id, 'id_status' => 41 ];
                $catatan = TrxBpdLog::where($where)->orderBy('created_at','desc')->first()->keterangan;
                // menampilkan input biaya oleh pengaju / admin
                $where = ['id_trx' => $id];
                $trxDetailBiaya = TrxBpdDetailBiaya::where($where)->whereNull('id_info')->orderBy('id', 'asc')->get();
                // 46 = Perbaikan Reimburse oleh Admin
                // 36 = Reimburse Ditolak Admin
                $approve = 46;
                $reject = 36;
                // 
                return view('formulir.bpd.reimburse_view', compact('catatan', 'trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            // 43 = Reimburse Ditolak L2
            case 'reject_reimburse_l2':
                // 43 = Reimburse Ditolak L2
                if($status_id <> 43){
                    return view('emails.bpd_ok');
                    break;
                }
                $where = [ 'id_trx' => $id, 'id_status' => 43 ];
                $catatan = TrxBpdLog::where($where)->orderBy('created_at','desc')->first()->keterangan;
                // menampilkan input biaya oleh pengaju / admin
                $where = ['id_trx' => $id];
                $trxDetailBiaya = TrxBpdDetailBiaya::where($where)->whereNull('id_info')->orderBy('id', 'asc')->get();
                // 46 = Perbaikan Reimburse oleh Admin
                // 36 = Reimburse Ditolak Admin
                $approve = 46;
                $reject = 36;
                // 
                return view('formulir.bpd.reimburse_view', compact('catatan', 'trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            // 45 = Reimburse Ditolak PC
            case 'reject_reimburse_hco':
                // 45 = Reimburse Ditolak PC
                if($status_id <> 45){
                    return view('emails.bpd_ok');
                    break;
                }
                $where = [ 'id_trx' => $id, 'id_status' => 45 ];
                $catatan = TrxBpdLog::where($where)->orderBy('created_at','desc')->first()->keterangan;
                // menampilkan input biaya oleh pengaju / admin
                $where = ['id_trx' => $id];
                $trxDetailBiaya = TrxBpdDetailBiaya::where($where)->whereNull('id_info')->orderBy('id', 'asc')->get();
                // 46 = Perbaikan Reimburse oleh Admin
                // 36 = Reimburse Ditolak Admin
                $approve = 46;
                $reject = 36;
                // 
                return view('formulir.bpd.reimburse_view', compact('catatan', 'trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            // reimburse
            case 'reimburse':
                // 33 = Pengajuan Reimburse
                // 37 = Perbaikan Reimburse oleh Pengaju
                if($status_id <> 33 && $status_id <> 37){
                    return view('emails.bpd_ok');
                    break;
                }
                // 34 = Reimburse Diproses Admin
                // 36 = Reimburse Ditolak Admin
                $approve = 34;
                $reject = 36;
                // 
                if(count($trxDetailBiaya)==0){
                }
                $where = ['id_trx' => $id, 'jenis' => 'reimburse'];
                $trxDetailBiaya = TrxBpdDetailBiaya::where($where)->orderBy('id', 'asc')->get();
                // dd($trxDetailBiaya);
                return view('formulir.bpd.reimburse_view', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            // pengajuan reimburse
            case 'reimburse_pa':
                // 34 = Reimburse Diproses Admin
                if($status_id <> 34){
                    return view('emails.bpd_ok');
                    break;
                }
                // 35 = Reimburse Disetujui Admin
                // 36 = Reimburse Ditolak Admin
                $approve = 35;
                $reject = 36;
                // 
                $where = ['id_trx' => $id, 'jenis' => 'reimburse'];
                $trxDetailBiaya = TrxBpdDetailBiaya::where($where)->orderBy('id', 'asc')->get();
                return view('formulir.bpd.reimburse_view', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            case 'reimburse_pc':
                // 35 = Reimburse Disetujui Admin
                // 46 = Perbaikan Reimburse oleh Admin
                if($status_id <> 35 && $status_id <> 46){
                    return view('emails.bpd_ok');
                    break;
                }
                // 38 = Reimburse Disetujui PC
                // 39 = Reimburse Ditolak PC
                $approve = 38;
                $reject = 39;
                return view('formulir.bpd.reimburse_view', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            case 'reimburse_l1':
                // 38 = Reimburse Disetujui PC
                // 46 = Perbaikan Reimburse oleh Admin
                // is_project = 1
                if($status_id <> 38 && $status_id <> 46 && $is_project == 1){
                    return view('emails.bpd_ok');
                    break;
                }
                // 35 = Reimburse Disetujui Admin
                // 46 = Perbaikan Reimburse oleh Admin
                // is_project = 0
                else if($status_id <> 35 && $status_id <> 46 && $is_project == 0){
                    return view('emails.bpd_ok');
                    break;
                }
                // 40 = Reimburse Disetujui L1
                // 41 = Reimburse Ditolak L1
                $approve = 40;
                $reject = 41;
                return view('formulir.bpd.reimburse_view', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            case 'reimburse_l2':
                // 40 = Reimburse Disetujui L1
                if($status_id <> 40){
                    return view('emails.bpd_ok');
                    break;
                }
                // 42 = Reimburse Disetujui L2
                // 43 = Reimburse Ditolak L2
                $approve = 42;
                $reject = 43;
                return view('formulir.bpd.reimburse_view', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            case 'reimburse_hco':
                // 42 = Reimburse Disetujui L2
                // 44 = Reimburse Disetujui PC
                if($status_id <> 42 && $status_id <> 44){
                    return view('emails.bpd_ok');
                    break;
                }
                // 44 = Reimburse Disetujui PC
                // 45 = Reimburse Ditolak PC
                $approve = 44;
                $reject = 45;
                return view('formulir.bpd.reimburse_view', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            case 'view_reimburse':
                $approve = 0;
                $reject = 0;
                return view('formulir.bpd.detail_reimburse', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info'));
                break;
            case 'pdf_reimburse':
                // 0 = NA
                $approve = 0;
                $reject = 0;
                // print to pdf
                $waktu = date('YmdHis');
                // start
                $arr_htmlLog = array();
                $htmlLog = '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">';
                // pengaju
                $where = ['id_trx' => $id, 'nik_pic' => $trxMaster->pengaju];
                // 33 = Pengajuan Reimburse
                // 37 = Perbaikan Reimburse oleh Pengaju
                $bpdLog = TrxBpdLog::where($where)->whereIn('id_status', [33,37])->orderBy('id', 'desc')->first();
                if (!empty($bpdLog)) {
                    $_htmlLog = '<td>Mengajukan<br/><img src="' . public_path('assets/images/matta/requested_new.png') . '" height="30" /><br/>' . $pengaju_property['nama'] . '<br/>' . $pengaju_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                    array_push($arr_htmlLog, $_htmlLog);
                }
                // admin
                $where = ['id_trx' => $id, 'nik_pic' => $trxMaster->admin];
                // 35 = Reimburse Disetujui Admin
                // 36 = Reimburse Ditolak Admin
                // 46 = Perbaikan Reimburse oleh Admin
                $bpdLog = TrxBpdLog::where($where)->whereIn('id_status', [35,36,46])->orderBy('id', 'desc')->first();
                if (!empty($bpdLog)) {
                    if($bpdLog->id_status == 35 || $bpdLog->id_status == 46){
                        $_htmlLog = '<td>Memeriksa<br/><img src="' . public_path('assets/images/matta/verified_new.png') . '" height="30" /><br/>' . $admin_property['nama'] . '<br/>' . $admin_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }else if($bpdLog->id_status == 36){
                        $_htmlLog = '<td>Memeriksa<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $admin_property['nama'] . '<br/>' . $admin_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }
                }
                // koordinator
                $where = ['id_trx' => $id, 'nik_pic' => $trxMaster->koordinator];
                // 38 = Reimburse Disetujui PC
                // 39 = Reimburse Ditolak PC
                $bpdLog = TrxBpdLog::where($where)->whereIn('id_status', [38,39])->orderBy('id', 'desc')->first();
                if (!empty($bpdLog)) {
                    if($bpdLog->id_status == 38){
                        $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $koordinator_property['nama'] . '<br/>' . $koordinator_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }else if($bpdLog->id_status == 39){
                        $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $koordinator_property['nama'] . '<br/>' . $koordinator_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }
                }
                // layer1
                $where = ['id_trx' => $id, 'nik_pic' => $trxMaster->layer1];
                // 40 = Reimburse Disetujui L1
                // 41 = Reimburse Ditolak L1
                $bpdLog = TrxBpdLog::where($where)->whereIn('id_status', [40,41])->orderBy('id', 'desc')->first();
                if (!empty($bpdLog)) {
                    if($bpdLog->id_status == 40){
                        $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $layer1_property['nama'] . '<br/>' . $layer1_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }else if($bpdLog->id_status == 41){
                        $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $layer1_property['nama'] . '<br/>' . $layer1_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }
                }
                // layer2
                $where = ['id_trx' => $id, 'nik_pic' => $trxMaster->layer2];
                // 42 = Reimburse Disetujui L2
                // 43 = Reimburse Ditolak L2
                $bpdLog = TrxBpdLog::where($where)->whereIn('id_status', [42,43])->orderBy('id', 'desc')->first();
                if (!empty($bpdLog)) {
                    if($bpdLog->id_status == 42){
                        $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $layer2_property['nama'] . '<br/>' . $layer2_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }else if($bpdLog->id_status == 43){
                        $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $layer2_property['nama'] . '<br/>' . $layer2_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }
                }
                // hco
                $where = ['id_trx' => $id, 'nik_pic' => $trxMaster->hco];
                // 44 = Reimburse Disetujui PC
                // 45 = Reimburse Ditolak PC
                $bpdLog = TrxBpdLog::where($where)->whereIn('id_status', [44,45])->orderBy('id', 'desc')->first();
                if (!empty($bpdLog)) {
                    if($bpdLog->id_status == 44){
                        $_htmlLog = '<td>Mengetahui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $hco_property['nama'] . '<br/>' . $hco_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }else if($bpdLog->id_status == 45){
                        $_htmlLog = '<td>Mengetahui<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $hco_property['nama'] . '<br/>' . $layer2_property['jabatan'] . '<br/>' . $bpdLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }
                }
                // finish
                // start looping satu baris terdiri dari $pembagi
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
                $filename = $waktu . '_bpd.pdf';
                $view = \View::make('formulir.bpd.pdf', compact('trxMaster', 'trxDetailInfo', 'trxDetailBiaya', 'kelompok', 'no_info', 'no_biaya', 'linkAction', 'nik', 'approve', 'reject', 'koordinator', 'coa', 'history', 'trxDetailBiayaPersonel', 'no_biaya_info', 'htmlLog'));
                $html = $view->render();
                $pdf = new TCPDF;
                $pdf::SetTitle('Formulir BPD');
                $pdf::SetFont('times', '', 10, '', true);
                $pdf::AddPage();
                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output(public_path($filename), 'I');
                return response()->download(public_path($filename));
                break;
            default:
                return view('emails.bpd_ok');
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
        //
        $id = Crypt::decrypt($id);
        // dd($request->all(), $id);
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $view = $request->view;
        $is_project = $request->is_project;
        $nik = $request->nik;
        $status_id = $request->status_id;
        // status_id = 0 untuk preview
        if($status_id <> 0){
            // ToDoList
            if($view == 'detail' || $view == 'realisasi' || $view == 'reimburse'){
                $this->deleteToDoList(['id_trx'=>$id, 'pic'=>$view, 'filename'=>'emails.bpd0']);
            }else{
                $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$view, 'filename'=>'emails.bpd0']);
            }
        }
        // TrxBpdMaster
        $trxMaster = TrxBpdMaster::find($id);
        // process by project admin untuk ca
        if($view == 'detail'){
            $admin = $nik;
            $admin_property = $this->getNikProperty($admin);
            $trxMaster->admin = $admin;
            $trxMaster->admin_property = $admin_property;
            $trxMaster->status_id = $status_id;
            $trxMaster->update();
            // TrxBpdLog
            $keterangan = $request->catatan;
            $this->insertLog($id, $status_id, $admin, $keterangan);
            // 
            $view = 'approval_pa';
            $email_to = array();
            $arr['email_to'] = json_decode($trxMaster->admin_property, true)['email'];
            $arr['nama_to'] = json_decode($trxMaster->admin_property, true)['nama'];
            $arr['nik_to'] = json_decode($trxMaster->admin_property, true)['nik'];
            $arr['id_trx'] = $id;
            $arr['id_status'] = $status_id;
            $arr['pic'] = $view;
            // 5 = CA Ditolak Admin
            if($status_id == 5){
                $view = 'reject_pa';
                $arr['email_to'] = json_decode($trxMaster->pengaju_property, true)['email'];
                $arr['nama_to'] = json_decode($trxMaster->pengaju_property, true)['nama'];
                $arr['nik_to'] = json_decode($trxMaster->pengaju_property, true)['nik'];
                $arr['id_trx'] = $id;
                $arr['id_status'] = $status_id;
                $arr['pic'] = $view;
            }
            array_push($email_to, $arr);
            $email_info = ['pengaju_property'=>$trxMaster->pengaju_property, 'atasan_property'=>$trxMaster->atasan_property, 'luar_dalam'=>$trxMaster->luar_dalam, 'email_to'=>$email_to, 'id'=>Crypt::encrypt($trxMaster->id), 'view'=>$view];
            $this->kirimEmail($email_info);
        }
        // review by project admin
        else if($view == 'approval_pa' || $view == 'reject_pc' || $view == 'reject_l1' || $view == 'reject_l2' || $view == 'reject_hco'){
            // dd($request->all());
            // jika direject maka uang saku harus dihapus semua
            // selanjutnya akan dihitung ulang terhadap updatenya
            if($view <> 'approval_pa'){
                $where = ['id_trx' => $id, 'jenis' => 'coa'];
                $_result = TrxBpdDetailBiaya::where($where)->whereIn('id_coa', [1,2,76,89])->delete();

            }
            // get kelompok
            $_pengaju_property = json_decode($trxMaster->pengaju_property, true);
            $id_level = $_pengaju_property['id_level'];
            $kelompok = array();
            $kelompok_id = 0;
            $where = ['is_project' => $is_project];
            $_data = BpdKelompok::where($where)->get();
            foreach ($_data as $data){
                $_kelompok = explode(',', $data->level);
                if (in_array($id_level, $_kelompok)){
                    $kelompok_id = $data->id;
                    break;
                }
            }
            $pengaju = $trxMaster->pengaju;
            $trxMaster->kelompok_id = $kelompok_id;
            // 
            $trxMaster->is_project = $is_project;
            // 
            $project_id = $request->project_id;
            $trxMaster->project_id = $project_id;
            // 
            $is_leader = $request->is_leader;
            $trxMaster->is_leader = $is_leader;
            // status_id = 0 untuk preview
            if($status_id <> 0){
                $trxMaster->status_id = $status_id;
            }
            // 
            $pic_nik = $nik;
            // 
            $email_to = array();
            // 
            $view = 'approval_pc';
            if($is_project == 0){
                $view = 'approval_l1';
            }
            // jika project maka flownya adalah pengaju->koordinator->layer1->layer2
            if($is_project == 1){
                $koordinator = $request->koordinator;
                $trxMaster->koordinator = $koordinator;
                $koordinator_property = $this->getNikProperty($koordinator);
                $trxMaster->koordinator_property = $koordinator_property;
                // 
                $where = ['nik' => $koordinator];
                $result = Karyawan::where($where)->first();
                $layer1 = $result->layer1;
                $layer2 = $result->layer2;
                // pengecualian line approval
                // 4 = Assistant Manager
                // 1 = Leader = 1
                if(!empty(DB::select(DB::raw("SELECT a.nik,b.nama,b.layer1,b.layer2,c.nama FROM mtr_tbl_bpd_nik_peran AS a LEFT JOIN mtr_tbl_karyawan AS b ON a.nik=b.nik LEFT JOIN mtr_tbl_karyawan AS c ON b.layer2=c.nik WHERE b.kode_level=4 AND a.id_peran=1 AND c.kode_level=14 AND a.nik='".$trxMaster->koordinator."'")))){
                    $layer2 = $layer1;
                }
                // 5 = Junior Manager (JM)
                // 1 = Leader = 1
                else if(!empty(DB::select(DB::raw("SELECT a.nik,b.nama,b.layer1,b.layer2,c.nama FROM mtr_tbl_bpd_nik_peran AS a LEFT JOIN mtr_tbl_karyawan AS b ON a.nik=b.nik LEFT JOIN mtr_tbl_karyawan AS c ON b.layer2=c.nik WHERE b.kode_level=5 AND a.id_peran=1 AND c.kode_level=14 AND a.nik='".$trxMaster->koordinator."'")))){
                    $layer2 = $layer1;
                }
                // 1 = Non Staff
                // 2 = Member = 2
                else if(!empty(DB::select(DB::raw("SELECT a.nik,b.nama,b.layer1,b.layer2,c.nama FROM mtr_tbl_bpd_nik_peran AS a LEFT JOIN mtr_tbl_karyawan AS b ON a.nik=b.nik LEFT JOIN mtr_tbl_karyawan AS c ON b.layer2=c.nik WHERE b.kode_level=1 AND a.id_peran=2 AND c.kode_level=14 AND a.nik='".$trxMaster->koordinator."'")))){
                    $layer2 = $layer1;
                }
                // 2 = Staff
                // 2 = Member = 2
                else if(!empty(DB::select(DB::raw("SELECT a.nik,b.nama,b.layer1,b.layer2,c.nama FROM mtr_tbl_bpd_nik_peran AS a LEFT JOIN mtr_tbl_karyawan AS b ON a.nik=b.nik LEFT JOIN mtr_tbl_karyawan AS c ON b.layer2=c.nik WHERE b.kode_level=2 AND a.id_peran=2 AND c.kode_level=14 AND a.nik='".$trxMaster->koordinator."'")))){
                    $layer2 = $layer1;
                }
                // 3 = Supervisor (SPV)
                // 2 = Member = 2
                else if(!empty(DB::select(DB::raw("SELECT a.nik,b.nama,b.layer1,b.layer2,c.nama FROM mtr_tbl_bpd_nik_peran AS a LEFT JOIN mtr_tbl_karyawan AS b ON a.nik=b.nik LEFT JOIN mtr_tbl_karyawan AS c ON b.layer2=c.nik WHERE b.kode_level=3 AND a.id_peran=2 AND c.kode_level=14 AND a.nik='".$trxMaster->koordinator."'")))){
                    $layer2 = $layer1;
                }
                // pengecualian line approval untuk data di table mtr_tbl_bpd_nik_bod 
                $where = ['is_active' => 1 , 'nik' => $trxMaster->pengaju];
                $bod = BpdNikBod::where($where)->first();
                if(!empty($bod)){
                    $layer1 = $bod->layer1;
                    $layer2 = $bod->layer2;
                }
                $trxMaster->layer1 = $layer1;
                $trxMaster->layer2 = $layer2;
                $layer1_property = $this->getNikProperty($layer1);
                $trxMaster->layer1_property = $layer1_property;
                $layer2_property = $this->getNikProperty($layer2);
                $trxMaster->layer2_property = $layer2_property;
                // 
                $email_to = array();
                $arr['email_to'] = json_decode($trxMaster->koordinator_property, true)['email'];
                $arr['nama_to'] = json_decode($trxMaster->koordinator_property, true)['nama'];
                $arr['nik_to'] = json_decode($trxMaster->koordinator_property, true)['nik'];
                $arr['id_trx'] = $id;
                $arr['id_status'] = $status_id;
                $arr['pic'] = $view;
                // 5 = CA Ditolak Admin
                if($status_id == 5){
                    $view = 'reject_pa';
                    $arr['email_to'] = json_decode($trxMaster->pengaju_property, true)['email'];
                    $arr['nama_to'] = json_decode($trxMaster->pengaju_property, true)['nama'];
                    $arr['nik_to'] = json_decode($trxMaster->pengaju_property, true)['nik'];
                    $arr['id_trx'] = $id;
                    $arr['id_status'] = $status_id;
                    $arr['pic'] = $view;
                }
                array_push($email_to, $arr);
            }
            // jika non project maka flownya adalah pengaju->layer1->layer2
            else{
                $where = ['nik' => $pengaju];
                $result = Karyawan::where($where)->first();
                $layer1 = $result->layer1;
                $layer2 = $result->layer2;
                // pengecualian line approval
                // 4 = Assistant Manager
                // 1 = Leader = 1
                if(!empty(DB::select(DB::raw("SELECT a.nik,b.nama,b.layer1,b.layer2,c.nama FROM mtr_tbl_bpd_nik_peran AS a LEFT JOIN mtr_tbl_karyawan AS b ON a.nik=b.nik LEFT JOIN mtr_tbl_karyawan AS c ON b.layer2=c.nik WHERE b.kode_level=4 AND a.id_peran=1 AND c.kode_level=14 AND a.nik='".$trxMaster->pengaju."'")))){
                    $layer2 = $layer1;
                }
                // 5 = Junior Manager (JM)
                // 1 = Leader = 1
                else if(!empty(DB::select(DB::raw("SELECT a.nik,b.nama,b.layer1,b.layer2,c.nama FROM mtr_tbl_bpd_nik_peran AS a LEFT JOIN mtr_tbl_karyawan AS b ON a.nik=b.nik LEFT JOIN mtr_tbl_karyawan AS c ON b.layer2=c.nik WHERE b.kode_level=5 AND a.id_peran=1 AND c.kode_level=14 AND a.nik='".$trxMaster->pengaju."'")))){
                    $layer2 = $layer1;
                }
                // 1 = Non Staff
                // 2 = Member = 2
                else if(!empty(DB::select(DB::raw("SELECT a.nik,b.nama,b.layer1,b.layer2,c.nama FROM mtr_tbl_bpd_nik_peran AS a LEFT JOIN mtr_tbl_karyawan AS b ON a.nik=b.nik LEFT JOIN mtr_tbl_karyawan AS c ON b.layer2=c.nik WHERE b.kode_level=1 AND a.id_peran=2 AND c.kode_level=14 AND a.nik='".$trxMaster->pengaju."'")))){
                    $layer2 = $layer1;
                }
                // 2 = Staff
                // 2 = Member = 2
                else if(!empty(DB::select(DB::raw("SELECT a.nik,b.nama,b.layer1,b.layer2,c.nama FROM mtr_tbl_bpd_nik_peran AS a LEFT JOIN mtr_tbl_karyawan AS b ON a.nik=b.nik LEFT JOIN mtr_tbl_karyawan AS c ON b.layer2=c.nik WHERE b.kode_level=2 AND a.id_peran=2 AND c.kode_level=14 AND a.nik='".$trxMaster->pengaju."'")))){
                    $layer2 = $layer1;
                }
                // 3 = Supervisor (SPV)
                // 2 = Member = 2
                else if(!empty(DB::select(DB::raw("SELECT a.nik,b.nama,b.layer1,b.layer2,c.nama FROM mtr_tbl_bpd_nik_peran AS a LEFT JOIN mtr_tbl_karyawan AS b ON a.nik=b.nik LEFT JOIN mtr_tbl_karyawan AS c ON b.layer2=c.nik WHERE b.kode_level=3 AND a.id_peran=2 AND c.kode_level=14 AND a.nik='".$trxMaster->pengaju."'")))){
                    $layer2 = $layer1;
                }
                // pengecualian line approval untuk data di table mtr_tbl_bpd_nik_bod 
                $where = ['is_active' => 1 , 'nik' => $trxMaster->pengaju];
                $bod = BpdNikBod::where($where)->first();
                if(!empty($bod)){
                    $layer1 = $bod->layer1;
                    $layer2 = $bod->layer2;
                }
                $trxMaster->layer1 = $layer1;
                $trxMaster->layer2 = $layer2;
                $layer1_property = $this->getNikProperty($layer1);
                $trxMaster->layer1_property = $layer1_property;
                $layer2_property = $this->getNikProperty($layer2);
                $trxMaster->layer2_property = $layer2_property;
                // 
                $email_to = array();
                $arr['email_to'] = json_decode($trxMaster->layer1_property, true)['email'];
                $arr['nama_to'] = json_decode($trxMaster->layer1_property, true)['nama'];
                $arr['nik_to'] = json_decode($trxMaster->layer1_property, true)['nik'];
                $arr['id_trx'] = $id;
                $arr['id_status'] = $status_id;
                $arr['pic'] = $view;
                // 5 = CA Ditolak Admin
                if($status_id == 5){
                    $view = 'reject_pa';
                    $arr['email_to'] = json_decode($trxMaster->pengaju_property, true)['email'];
                    $arr['nama_to'] = json_decode($trxMaster->pengaju_property, true)['nama'];
                    $arr['nik_to'] = json_decode($trxMaster->pengaju_property, true)['nik'];
                    $arr['id_trx'] = $id;
                    $arr['id_status'] = $status_id;
                    $arr['pic'] = $view;
                }
                array_push($email_to, $arr);
            }
            // 24 = PIC BPD Non Manajemen
            $hco = MtrUtility::find(24)->informasi;
            if (in_array($trxMaster->pengaju, $arr_nik_nkp)){
                // 46 = NIP PIC BPD Non Manajemen (Nasio)
                $hco = MtrUtility::find(46)->informasi;
            }
            // id_peran=3 adalah Management
            $where = ['nik' => $pengaju, 'id_peran' => 3, 'is_active' => 1];
            $is_mgt = BpdNikPeran::where($where)->exists();
            // 25 = PIC BPD Manajemen
            if($is_mgt){
                $hco = MtrUtility::find(25)->informasi;
            }
            $trxMaster->hco = $hco;
            $hco_property = $this->getNikProperty($hco);
            $trxMaster->hco_property = $hco_property;
            $trxMaster->update();
            // TrxBpdLog
            $keterangan = $request->catatan;
            // status_id = 0 untuk preview
            if($status_id <> 0){
                $this->insertLog($id, $status_id, $pic_nik, $keterangan);
            }
            // insertBpdBiaya dengan kode coa Tambahan Biaya oleh admin
            if(count($request->input('biaya', []))>0){
                $biaya = $request->input('biaya', []);
                $satuan = $request->input('satuan', []);
                $data = $request->all();
                $nominal = array();
                foreach($data as $_id => $value){
                    if(str_starts_with($_id, 'nominal_')){
                        array_push($nominal, $value);
                    }
                }
                $nominal = str_replace( ',', '', $nominal );
                $jumlah = $request->input('jumlah', []);
                $jenis = 'coa';
                $is_approve = 1;
                $bpd_biaya = ['jenis'=>$jenis, 'id'=>$id, 'biaya'=>$biaya, 'satuan'=>$satuan, 'nominal'=>$nominal, 'jumlah'=>$jumlah, 'is_approve'=>$is_approve];
                $this->insertBpdBiaya($bpd_biaya);
            }
            // updateBpdBiaya dengan kode coa terhadap ca pengaju
            $data = $request->all();
            $arr_bpd = array();
            $arr_bpd_biaya = array();
            $arr_bpd_approve = array();
            foreach($data as $_id => $value){
                if(str_starts_with($_id, 'biayacoa_')){
                    array_push($arr_bpd_biaya, ['id'=>explode('_', $_id)[1], 'biaya'=>$value]);
                }
                if(str_starts_with($_id, 'isapprove_')){
                    array_push($arr_bpd_approve, ['is_approve'=>$value]);
                }
            }
            for ($x = 0; $x < count($arr_bpd_biaya); $x++){
                $_arr_bpd['id_biaya'] = $arr_bpd_biaya[$x];
                $_arr_bpd['is_approve'] = $arr_bpd_approve[$x];
                array_push($arr_bpd, $_arr_bpd);
            }
            foreach($arr_bpd as $_arr_bpd){
                $this->updateBpdBiaya(['id'=>$_arr_bpd['id_biaya']['id'], 'biaya'=>$_arr_bpd['id_biaya']['biaya'], 'is_approve'=>$_arr_bpd['is_approve']['is_approve']]);
            }
            // updateBpdInfo dengan ada atau tidak ada uang saku
            foreach($data as $_id => $value){
                if(str_starts_with($_id, 'uangsaku_')){
                    $this->updateBpdInfo(['id'=>explode('_', $_id)[1], 'is_approve'=>$value]);
                }
            }
            // uang saku insertBpdUangSaku pengaju
            $_uang_saku = 0;
            // project
            if($is_project == 1){
                // jika project dan ca dalam negeri
                if($trxMaster->luar_dalam == 'ca_dalam'){
                    // $where = ['id_trx'=>$id, 'luar_dalam'=>$trxMaster->luar_dalam, 'jenis'=>'ca'];
                    // $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                    // jika dalam 1 propinsi yang sama maka jumlah hari digabungkan
                    $trxDetailInfo = DB::select(DB::raw("SELECT id,id_trx,jenis,luar_dalam,tujuan1,SUM(DATEDIFF(DATE_ADD(tanggal_akhir, INTERVAL 1 DAY),tanggal_mulai)) AS hari FROM trx_tbl_bpd_detail_info WHERE id_trx=".$id." AND luar_dalam='ca_dalam' AND jenis='ca' AND is_approve=1 GROUP BY tujuan1"));
                    $this->insertBpdUangSaku($trxDetailInfo, $is_project, $trxMaster->luar_dalam, $kelompok_id, $project_id, $id, 'coa');
                }
                // jika project dan ca luar negeri
                else if($trxMaster->luar_dalam == 'ca_luar'){
                    // NA
                }
            }
            // non project
            else if($is_project == 0){
                // jika non project dan ca dalam negeri
                if($trxMaster->luar_dalam == 'ca_dalam'){
                    $where = ['id_trx'=>$id, 'luar_dalam'=>$trxMaster->luar_dalam, 'jenis'=>'ca', 'is_approve'=>1];
                    $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                    $this->insertBpdUangSaku($trxDetailInfo, $is_project, $trxMaster->luar_dalam, $kelompok_id, $project_id, $id, 'coa');
                }
                // jika non project dan ca luar negeri
                else if($trxMaster->luar_dalam == 'ca_luar'){
                    $where = ['id_trx'=>$id, 'luar_dalam'=>$trxMaster->luar_dalam, 'jenis'=>'ca', 'is_approve'=>1];
                    $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                    $this->insertBpdUangSaku($trxDetailInfo, $is_project, $trxMaster->luar_dalam, $kelompok_id, $project_id, $id, 'coa');
                }
            }
            // 
            $email_info = ['pengaju_property'=>$trxMaster->pengaju_property, 'atasan_property'=>$trxMaster->atasan_property, 'luar_dalam'=>$trxMaster->luar_dalam, 'email_to'=>$email_to, 'id'=>Crypt::encrypt($trxMaster->id), 'view'=>$view];
            // status_id = 0 untuk preview
            if($status_id <> 0){
                $this->kirimEmail($email_info);
            }else{
                return redirect('bpd')->with('msg', 'Click PDF to see preview result!.');
                // return redirect()->route('bpd.show', [Crypt::encrypt($id), 'view'=>'preview'])->with('_blank');
                // return Redirect::to('https://www.pakainfo.com')->with('_blank');
                // return redirect()->route('bpd.show', [Crypt::encrypt($id), 'view'=>'pdf'])->with('_blank');
                // return view('formulir.bpd.preview', ['url' => route('bpd.show', [Crypt::encrypt($id), 'view'=>'pdf'])]);
                // Redirect::away(route('bpd.show', [Crypt::encrypt($id), 'view'=>'pdf']));
                // return redirect()->away(route('bpd.show', [Crypt::encrypt($id), 'view'=>'pdf']))->with('_blank');

            }
        }
        // review by project coordinator - layer 1 - layer 2 - hco
        else if($view == 'approval_pc' || $view == 'approval_l1' || $view == 'approval_l2' || $view == 'approval_hco'){
            $trxMaster->status_id = $status_id;
            // 
            $pic_nik = $nik;
            $trxMaster->update();
            // TrxBpdLog
            $keterangan = $request->catatan;
            $this->insertLog($id, $status_id, $pic_nik, $keterangan);
            // 
            $email_to = array();
            if($view == 'approval_pc'){
                $view = 'approval_l1';
                $email_to = array();
                $arr['email_to'] = json_decode($trxMaster->layer1_property, true)['email'];
                $arr['nama_to'] = json_decode($trxMaster->layer1_property, true)['nama'];
                $arr['nik_to'] = json_decode($trxMaster->layer1_property, true)['nik'];
                $arr['id_trx'] = $id;
                $arr['id_status'] = $status_id;
                $arr['pic'] = $view;
                // 8 = CA Ditolak PC
                if($status_id == 8){
                    $view = 'reject_pc';
                    $arr['email_to'] = json_decode($trxMaster->admin_property, true)['email'];
                    $arr['nama_to'] = json_decode($trxMaster->admin_property, true)['nama'];
                    $arr['nik_to'] = json_decode($trxMaster->admin_property, true)['nik'];
                    $arr['id_trx'] = $id;
                    $arr['id_status'] = $status_id;
                    $arr['pic'] = $view;
                }
                array_push($email_to, $arr);
                $email_info = ['pengaju_property'=>$trxMaster->pengaju_property, 'atasan_property'=>$trxMaster->atasan_property, 'luar_dalam'=>$trxMaster->luar_dalam, 'email_to'=>$email_to, 'id'=>Crypt::encrypt($trxMaster->id), 'view'=>$view];
                $this->kirimEmail($email_info);
            }else if($view == 'approval_l1'){
                $view = 'approval_l2';
                $email_to = array();
                $arr['email_to'] = json_decode($trxMaster->layer2_property, true)['email'];
                $arr['nama_to'] = json_decode($trxMaster->layer2_property, true)['nama'];
                $arr['nik_to'] = json_decode($trxMaster->layer2_property, true)['nik'];
                $arr['id_trx'] = $id;
                $arr['id_status'] = $status_id;
                $arr['pic'] = $view;
                // check apakah layer1 == layer2, jika iya maka dibypass dan laye1 setuju
                if($trxMaster->layer1 == $trxMaster->layer2 && $status_id == 9){
                    // 11 = CA Disetujui L2
                    $status_id = 11;
                    $view = 'approval_hco';
                    $arr['email_to'] = json_decode($trxMaster->hco_property, true)['email'];
                    $arr['nama_to'] = json_decode($trxMaster->hco_property, true)['nama'];
                    $arr['nik_to'] = json_decode($trxMaster->hco_property, true)['nik'];
                    $arr['id_trx'] = $id;
                    $arr['id_status'] = $status_id;
                    $arr['pic'] = $view;
                    // 
                    $trxMaster->status_id = $status_id;
                    $pic_nik = $nik;
                    $trxMaster->update();
                    // TrxBpdLog
                    $keterangan = $request->catatan;
                    $this->insertLog($id, $status_id, $pic_nik, $keterangan);
                }
                // 10 = CA Ditolak L1
                if($status_id == 10){
                    $view = 'reject_l1';
                    $arr['email_to'] = json_decode($trxMaster->admin_property, true)['email'];
                    $arr['nama_to'] = json_decode($trxMaster->admin_property, true)['nama'];
                    $arr['nik_to'] = json_decode($trxMaster->admin_property, true)['nik'];
                    $arr['id_trx'] = $id;
                    $arr['id_status'] = $status_id;
                    $arr['pic'] = $view;
                }
                array_push($email_to, $arr);
                $email_info = ['pengaju_property'=>$trxMaster->pengaju_property, 'atasan_property'=>$trxMaster->atasan_property, 'luar_dalam'=>$trxMaster->luar_dalam, 'email_to'=>$email_to, 'id'=>Crypt::encrypt($trxMaster->id), 'view'=>$view];
                $this->kirimEmail($email_info);
            }else if($view == 'approval_l2'){
                $view = 'approval_hco';
                $email_to = array();
                $arr['email_to'] = json_decode($trxMaster->hco_property, true)['email'];
                $arr['nama_to'] = json_decode($trxMaster->hco_property, true)['nama'];
                $arr['nik_to'] = json_decode($trxMaster->hco_property, true)['nik'];
                $arr['id_trx'] = $id;
                $arr['id_status'] = $status_id;
                $arr['pic'] = $view;
                // 12 = CA Ditolak L2
                if($status_id == 12){
                    $view = 'reject_l2';
                    $arr['email_to'] = json_decode($trxMaster->admin_property, true)['email'];
                    $arr['nama_to'] = json_decode($trxMaster->admin_property, true)['nama'];
                    $arr['nik_to'] = json_decode($trxMaster->admin_property, true)['nik'];
                    $arr['id_trx'] = $id;
                    $arr['id_status'] = $status_id;
                    $arr['pic'] = $view;
                }
                array_push($email_to, $arr);
                $email_info = ['pengaju_property'=>$trxMaster->pengaju_property, 'atasan_property'=>$trxMaster->atasan_property, 'luar_dalam'=>$trxMaster->luar_dalam, 'email_to'=>$email_to, 'id'=>Crypt::encrypt($trxMaster->id), 'view'=>$view];
                $this->kirimEmail($email_info);
            }else if($view == 'approval_hco'){
                $email_to = array();
                $view = 'approval_done';
                $arr['email_to'] = json_decode($trxMaster->pengaju_property, true)['email'];
                $arr['nama_to'] = json_decode($trxMaster->pengaju_property, true)['nama'];
                $arr['nik_to'] = json_decode($trxMaster->pengaju_property, true)['nik'];
                $arr['id_trx'] = $id;
                $arr['id_status'] = $status_id;
                $arr['pic'] = $view;
                // 14 = CA Ditolak PC
                if($status_id == 14){
                    $view = 'reject_hco';
                    $arr['email_to'] = json_decode($trxMaster->admin_property, true)['email'];
                    $arr['nama_to'] = json_decode($trxMaster->admin_property, true)['nama'];
                    $arr['nik_to'] = json_decode($trxMaster->admin_property, true)['nik'];
                    $arr['id_trx'] = $id;
                    $arr['id_status'] = $status_id;
                    $arr['pic'] = $view;
                }else{
                    $view = 'approval_done';
                    $_arr['email_to'] = json_decode($trxMaster->admin_property, true)['email'];
                    $_arr['nama_to'] = json_decode($trxMaster->admin_property, true)['nama'];
                    $_arr['nik_to'] = json_decode($trxMaster->admin_property, true)['nik'];
                    $_arr['id_trx'] = $id;
                    $_arr['id_status'] = $status_id;
                    $_arr['pic'] = $view;
                    array_push($email_to, $_arr);
                }
                array_push($email_to, $arr);
                $email_info = ['pengaju_property'=>$trxMaster->pengaju_property, 'atasan_property'=>$trxMaster->atasan_property, 'luar_dalam'=>$trxMaster->luar_dalam, 'email_to'=>$email_to, 'id'=>Crypt::encrypt($trxMaster->id), 'view'=>$view];
                $this->kirimEmail($email_info);
            }
        }
        // realisasi pengaju
        else if($view == 'realisasi' || $view == 'reject_realisasi_pa'){
            // 
            $data = $request->all();
            foreach($data as $_id => $value){
                if(str_starts_with($_id, 'lampiran_')){
                    $request->validate([
                        $_id => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    ]);
                }
            }
            // 
            $pic_nik = $nik;
            // khusus mgt, jika dilakukan oleh admin maka yang terinsert adalah pengajunya = mgt
            if($trxMaster->admin == $nik){
                $pic_nik = $trxMaster->pengaju;
            }
            $trxMaster->status_id = $status_id;
            $trxMaster->update();
            // TrxBpdLog
            $keterangan = $request->catatan;
            $this->insertLog($id, $status_id, $pic_nik, $keterangan);
            // jika direject maka uang saku harus dihapus semua
            // selanjutnya akan dihitung ulang terhadap updatenya
            if($view == 'reject_realisasi_pa'){
                $where = ['id_trx' => $id, 'jenis' => 'realisasi_coa'];
                $_result = TrxBpdDetailBiaya::where($where)->whereIn('id_coa', [1,2,76,89])->delete();

            }
            // ada perubahan tujuan/tanggal
            // TrxBpdDetailInfo
            if(count($request->input('tanggal_mulai', []))>0){
                $tanggal_mulai = $request->input('tanggal_mulai', []);
                $tanggal_akhir = $request->input('tanggal_akhir', []);
                $tujuan1 = array();
                $tujuan2 = array();
                // 
                foreach($data as $_id => $value){
                    if(str_starts_with($_id, 'tujuan1_')){
                        array_push($tujuan1, $value);
                    }else if(str_starts_with($_id, 'tujuan2_')){
                        array_push($tujuan2, $value);
                    }
                }
                // 
                $jenis = 'realisasi';
                $bpd_info = ['id_trx'=>$id, 'luar_dalam'=>$trxMaster->luar_dalam, 'jenis'=>$jenis, 'tanggal_mulai'=>$tanggal_mulai, 'tanggal_akhir'=>$tanggal_akhir, 'tujuan1'=>$tujuan1, 'tujuan2'=>$tujuan2];
                $this->insertBpdInfo($bpd_info);
                // 
                // uang saku insertBpdUangSaku pengaju
                $_uang_saku = 0;
                // project
                if($trxMaster->is_project == 1){
                    // jika project dan ca dalam negeri
                    if($trxMaster->luar_dalam == 'ca_dalam'){
                        // $where = ['id_trx'=>$id, 'luar_dalam'=>$trxMaster->luar_dalam, 'jenis'=>'ca'];
                        // $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                        // jika dalam 1 propinsi yang sama maka jumlah hari digabungkan
                        $trxDetailInfo = DB::select(DB::raw("SELECT id,id_trx,jenis,luar_dalam,tujuan1,SUM(DATEDIFF(DATE_ADD(tanggal_akhir, INTERVAL 1 DAY),tanggal_mulai)) AS hari FROM trx_tbl_bpd_detail_info WHERE id_trx=".$id." AND luar_dalam='ca_dalam' AND jenis='realisasi' AND is_approve=1 GROUP BY tujuan1"));
                        $this->insertBpdUangSaku($trxDetailInfo, $trxMaster->is_project, $trxMaster->luar_dalam, $trxMaster->kelompok_id, $trxMaster->project_id, $id, 'realisasi_coa');
                    }
                    // jika project dan ca luar negeri
                    else if($trxMaster->luar_dalam == 'ca_luar'){
                        // NA
                    }
                }
                // non project
                else if($trxMaster->is_project == 0){
                    // jika non project dan ca dalam negeri
                    if($trxMaster->luar_dalam == 'ca_dalam'){
                        $where = ['id_trx'=>$id, 'luar_dalam'=>$trxMaster->luar_dalam, 'jenis'=>'realisasi', 'is_approve'=>1];
                        $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                        $this->insertBpdUangSaku($trxDetailInfo, $trxMaster->is_project, $trxMaster->luar_dalam, $trxMaster->kelompok_id, $trxMaster->project_id, $id, 'realisasi_coa');
                    }
                    // jika non project dan ca luar negeri
                    else if($trxMaster->luar_dalam == 'ca_luar'){
                        $where = ['id_trx'=>$id, 'luar_dalam'=>$trxMaster->luar_dalam, 'jenis'=>'realisasi', 'is_approve'=>1];
                        $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                        $this->insertBpdUangSaku($trxDetailInfo, $trxMaster->is_project, $trxMaster->luar_dalam, $trxMaster->kelompok_id, $trxMaster->project_id, $id, 'realisasi_coa');
                    }
                }
            }
            // tidak ada perubahan tujuan/tanggal
            else{
                $_uang_saku = 0;
                // project
                if($trxMaster->is_project == 1){
                    // jika project dan ca dalam negeri
                    if($trxMaster->luar_dalam == 'ca_dalam'){
                        // $where = ['id_trx'=>$id, 'luar_dalam'=>$trxMaster->luar_dalam, 'jenis'=>'ca'];
                        // $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                        // jika dalam 1 propinsi yang sama maka jumlah hari digabungkan
                        $trxDetailInfo = DB::select(DB::raw("SELECT id,id_trx,jenis,luar_dalam,tujuan1,SUM(DATEDIFF(DATE_ADD(tanggal_akhir, INTERVAL 1 DAY),tanggal_mulai)) AS hari FROM trx_tbl_bpd_detail_info WHERE id_trx=".$id." AND luar_dalam='ca_dalam' AND jenis='realisasi' AND is_approve=1 GROUP BY tujuan1"));
                        $this->insertBpdUangSaku($trxDetailInfo, $trxMaster->is_project, $trxMaster->luar_dalam, $trxMaster->kelompok_id, $trxMaster->project_id, $id, 'realisasi_coa');
                    }
                    // jika project dan ca luar negeri
                    else if($trxMaster->luar_dalam == 'ca_luar'){
                        // NA
                    }
                }
                // non project
                else if($trxMaster->is_project == 0){
                    // jika non project dan ca dalam negeri
                    if($trxMaster->luar_dalam == 'ca_dalam'){
                        $where = ['id_trx'=>$id, 'luar_dalam'=>$trxMaster->luar_dalam, 'jenis'=>'realisasi', 'is_approve'=>1];
                        $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                        $this->insertBpdUangSaku($trxDetailInfo, $trxMaster->is_project, $trxMaster->luar_dalam, $trxMaster->kelompok_id, $trxMaster->project_id, $id, 'realisasi_coa');
                    }
                    // jika non project dan ca luar negeri
                    else if($trxMaster->luar_dalam == 'ca_luar'){
                        $where = ['id_trx'=>$id, 'luar_dalam'=>$trxMaster->luar_dalam, 'jenis'=>'realisasi', 'is_approve'=>1];
                        $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                        $this->insertBpdUangSaku($trxDetailInfo, $trxMaster->is_project, $trxMaster->luar_dalam, $trxMaster->kelompok_id, $trxMaster->project_id, $id, 'realisasi_coa');
                    }
                }
            }
            // TrxBpdDetailBiaya
            if(count($request->input('biaya', []))>0){
                $biaya = $request->input('biaya', []);
                $satuan = $request->input('satuan', []);
                $nominal = array();
                foreach($data as $_id => $value){
                    if(str_starts_with($_id, 'nominal_')){
                        array_push($nominal, $value);
                    }
                }
                $nominal = str_replace( ',', '', $nominal );
                $jumlah = $request->input('jumlah', []);
                // 
                // $lampiran = $request->file('lampiran', []);
                $lampiran = array();
                foreach($data as $_id => $value){
                    if(str_starts_with($_id, 'lampiran_')){
                        array_push($lampiran, $value);
                    }
                }
                // 
                $jenis = 'realisasi';
                $bpd_biaya = ['jenis'=>$jenis, 'id'=>$id, 'biaya'=>$biaya, 'satuan'=>$satuan, 'nominal'=>$nominal, 'jumlah'=>$jumlah, 'lampiran'=>$lampiran, 'is_approve'=>1];
                // return response()->json( [ 'success' => true, 'bpd_biaya' => $bpd_biaya ], 200 );
                $this->insertBpdBiaya($bpd_biaya);
            }
            // 
            $email_to = array();
            $view = 'realisasi_pa';
            $arr['email_to'] = json_decode($trxMaster->admin_property, true)['email'];
            $arr['nama_to'] = json_decode($trxMaster->admin_property, true)['nama'];
            $arr['nik_to'] = json_decode($trxMaster->admin_property, true)['nik'];
            $arr['id_trx'] = $id;
            $arr['id_status'] = $status_id;
            $arr['pic'] = $view;
            // 20 = Realisasi Ditolak Admin
            if($status_id == 20){
                $view = 'reject_realisasi_pa';
                $arr['email_to'] = json_decode($trxMaster->pengaju_property, true)['email'];
                $arr['nama_to'] = json_decode($trxMaster->pengaju_property, true)['nama'];
                $arr['nik_to'] = json_decode($trxMaster->pengaju_property, true)['nik'];
                $arr['id_trx'] = $id;
                $arr['id_status'] = $status_id;
                $arr['pic'] = $view;
            }
            array_push($email_to, $arr);
            $email_info = ['pengaju_property'=>$trxMaster->pengaju_property, 'atasan_property'=>$trxMaster->atasan_property, 'luar_dalam'=>'realisasi_'.$trxMaster->luar_dalam, 'email_to'=>$email_to, 'id'=>Crypt::encrypt($trxMaster->id), 'view'=>$view];
            $this->kirimEmail($email_info);
        }
        // realisasi project admin
        else if($view == 'realisasi_pa' || $view == 'reject_realisasi_pc' || $view == 'reject_realisasi_l1' || $view == 'reject_realisasi_l2' || $view == 'reject_realisasi_hco'){
            // dd($request->all());
            $data = $request->all();
            foreach($data as $_id => $value){
                if(str_starts_with($_id, 'lampiran_')){
                    $request->validate([
                        $_id => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    ]);
                }
            }
            // 
            $pic_nik = $nik;
            $trxMaster->status_id = $status_id;
            $trxMaster->update();
            // TrxBpdLog
            $keterangan = $request->catatan;
            $this->insertLog($id, $status_id, $pic_nik, $keterangan);
            // TrxBpdDetailBiaya
            if(count($request->input('biaya', []))>0){
                $biaya = $request->input('biaya', []);
                $satuan = $request->input('satuan', []);
                $is_ca_realisasi = $request->input('is_ca_realisasi', []);
                // $data = $request->all();
                $nominal = array();
                foreach($data as $_id => $value){
                    if(str_starts_with($_id, 'nominal_')){
                        array_push($nominal, $value);
                    }
                }
                $nominal = str_replace( ',', '', $nominal );
                $jumlah = $request->input('jumlah', []);
                $jenis = 'realisasi_coa';
                // 
                // $lampiran = $request->file('lampiran', []);
                $lampiran = array();
                foreach($data as $_id => $value){
                    if(str_starts_with($_id, 'lampiran_')){
                        array_push($lampiran, $value);
                    }
                }
                // 
                $jenis = 'realisasi_coa';
                $bpd_biaya = ['jenis'=>$jenis, 'id'=>$id, 'biaya'=>$biaya, 'satuan'=>$satuan, 'nominal'=>$nominal, 'jumlah'=>$jumlah, 'lampiran'=>$lampiran, 'is_ca_realisasi'=>$is_ca_realisasi, 'is_approve'=>1];
                // return response()->json( [ 'success' => true, 'bpd_biaya' => $bpd_biaya ], 200 );
                $this->insertBpdBiaya($bpd_biaya);
                // 
            }
            // updateBpdBiayaRealisasi dengan kode coa terhadap realisasi pengaju
            // $data = $request->all();
            $i = 0;
            $is_ca = $request->input('is_ca', []);
            foreach($data as $_id => $value){
                if(str_starts_with($_id, 'biayacoa_')){
                    $this->updateBpdBiayaRealisasi(['id'=>explode('_', $_id)[1], 'biaya'=>$value, 'is_ca'=>$is_ca[$i]]);
                    $i++;
                }
            }
            // updateBpdInfo dengan ada atau tidak ada uang saku
            foreach($data as $_id => $value){
                if(str_starts_with($_id, 'uangsaku_')){
                    $this->updateBpdInfo(['id'=>explode('_', $_id)[1], 'is_approve'=>$value]);
                }
            }
            // jika direject maka uang saku harus dihapus semua
            // selanjutnya akan dihitung ulang terhadap updatenya
            $where = ['id_trx' => $id, 'jenis' => 'realisasi_coa'];
            $_result = TrxBpdDetailBiaya::where($where)->whereIn('id_coa', [1,2,76,89])->delete();
            // uang saku insertBpdUangSaku pengaju
            $_uang_saku = 0;
            // project
            if($trxMaster->is_project == 1){
                // jika project dan ca dalam negeri
                if($trxMaster->luar_dalam == 'ca_dalam'){
                    // $where = ['id_trx'=>$id, 'luar_dalam'=>$trxMaster->luar_dalam, 'jenis'=>'ca'];
                    // $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                    // jika dalam 1 propinsi yang sama maka jumlah hari digabungkan
                    $trxDetailInfo = DB::select(DB::raw("SELECT id,id_trx,jenis,luar_dalam,tujuan1,SUM(DATEDIFF(DATE_ADD(tanggal_akhir, INTERVAL 1 DAY),tanggal_mulai)) AS hari FROM trx_tbl_bpd_detail_info WHERE id_trx=".$id." AND luar_dalam='ca_dalam' AND jenis='realisasi' AND is_approve=1 GROUP BY tujuan1"));
                    $this->insertBpdUangSaku($trxDetailInfo, $trxMaster->is_project, $trxMaster->luar_dalam, $trxMaster->kelompok_id, $trxMaster->project_id, $id, 'realisasi_coa');
                }
                // jika project dan ca luar negeri
                else if($trxMaster->luar_dalam == 'ca_luar'){
                    // NA
                }
            }
            // non project
            else if($trxMaster->is_project == 0){
                // jika non project dan ca dalam negeri
                if($trxMaster->luar_dalam == 'ca_dalam'){
                    $where = ['id_trx'=>$id, 'luar_dalam'=>$trxMaster->luar_dalam, 'jenis'=>'realisasi', 'is_approve'=>1];
                    $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                    $this->insertBpdUangSaku($trxDetailInfo, $trxMaster->is_project, $trxMaster->luar_dalam, $trxMaster->kelompok_id, $trxMaster->project_id, $id, 'realisasi_coa');
                }
                // jika non project dan ca luar negeri
                else if($trxMaster->luar_dalam == 'ca_luar'){
                    $where = ['id_trx'=>$id, 'luar_dalam'=>$trxMaster->luar_dalam, 'jenis'=>'realisasi', 'is_approve'=>1];
                    $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                    $this->insertBpdUangSaku($trxDetailInfo, $trxMaster->is_project, $trxMaster->luar_dalam, $trxMaster->kelompok_id, $trxMaster->project_id, $id, 'realisasi_coa');
                }
            }
            // 
            $view = 'realisasi_pc';
            if($trxMaster->is_project == 0){
                $view = 'realisasi_l1';
            }
            $email_to = array();
            // jika project maka flownya adalah pengaju->koordinator->layer1->layer2
            if($trxMaster->is_project == 1){
                $email_to = array();
                $arr['email_to'] = json_decode($trxMaster->koordinator_property, true)['email'];
                $arr['nama_to'] = json_decode($trxMaster->koordinator_property, true)['nama'];
                $arr['nik_to'] = json_decode($trxMaster->koordinator_property, true)['nik'];
                $arr['id_trx'] = $id;
                $arr['id_status'] = $status_id;
                $arr['pic'] = $view;
                // 20 = Realisasi Ditolak Admin
                if($status_id == 20){
                    $view = 'reject_realisasi_pa';
                    $arr['email_to'] = json_decode($trxMaster->pengaju_property, true)['email'];
                    $arr['nama_to'] = json_decode($trxMaster->pengaju_property, true)['nama'];
                    $arr['nik_to'] = json_decode($trxMaster->pengaju_property, true)['nik'];
                    $arr['id_trx'] = $id;
                    $arr['id_status'] = $status_id;
                    $arr['pic'] = $view;
                }
                array_push($email_to, $arr);
            }
            // jika non project maka flownya adalah pengaju->layer1->layer2
            else{
                $email_to = array();
                $arr['email_to'] = json_decode($trxMaster->layer1_property, true)['email'];
                $arr['nama_to'] = json_decode($trxMaster->layer1_property, true)['nama'];
                $arr['nik_to'] = json_decode($trxMaster->layer1_property, true)['nik'];
                $arr['id_trx'] = $id;
                $arr['id_status'] = $status_id;
                $arr['pic'] = $view;
                // 20 = Realisasi Ditolak Admin
                if($status_id == 20){
                    $view = 'reject_realisasi_pa';
                    $arr['email_to'] = json_decode($trxMaster->pengaju_property, true)['email'];
                    $arr['nama_to'] = json_decode($trxMaster->pengaju_property, true)['nama'];
                    $arr['nik_to'] = json_decode($trxMaster->pengaju_property, true)['nik'];
                    $arr['id_trx'] = $id;
                    $arr['id_status'] = $status_id;
                    $arr['pic'] = $view;
                }
                array_push($email_to, $arr);
            }
            $email_info = ['pengaju_property'=>$trxMaster->pengaju_property, 'atasan_property'=>$trxMaster->atasan_property, 'luar_dalam'=>'realisasi_'.$trxMaster->luar_dalam, 'email_to'=>$email_to, 'id'=>Crypt::encrypt($trxMaster->id), 'view'=>$view];
            $this->kirimEmail($email_info);
        }
        // review by project coordinator - layer 1 - layer 2 - hco
        else if($view == 'realisasi_pc' || $view == 'realisasi_l1' || $view == 'realisasi_l2' || $view == 'realisasi_hco'){
            $trxMaster->status_id = $status_id;
            // 
            $pic_nik = $nik;
            $trxMaster->update();
            // TrxBpdLog
            $keterangan = $request->catatan;
            $this->insertLog($id, $status_id, $pic_nik, $keterangan);
            // 
            $email_to = array();
            if($view == 'realisasi_pc'){
                $view = 'realisasi_l1';
                $email_to = array();
                $arr['email_to'] = json_decode($trxMaster->layer1_property, true)['email'];
                $arr['nama_to'] = json_decode($trxMaster->layer1_property, true)['nama'];
                $arr['nik_to'] = json_decode($trxMaster->layer1_property, true)['nik'];
                $arr['id_trx'] = $id;
                $arr['id_status'] = $status_id;
                $arr['pic'] = $view;
                // 23 = Realisasi Ditolak PC
                if($status_id == 23){
                    $view = 'reject_realisasi_pc';
                    $arr['email_to'] = json_decode($trxMaster->admin_property, true)['email'];
                    $arr['nama_to'] = json_decode($trxMaster->admin_property, true)['nama'];
                    $arr['nik_to'] = json_decode($trxMaster->admin_property, true)['nik'];
                    $arr['id_trx'] = $id;
                    $arr['id_status'] = $status_id;
                    $arr['pic'] = $view;
                }
                array_push($email_to, $arr);
                $email_info = ['pengaju_property'=>$trxMaster->pengaju_property, 'atasan_property'=>$trxMaster->atasan_property, 'luar_dalam'=>'realisasi_'.$trxMaster->luar_dalam, 'email_to'=>$email_to, 'id'=>Crypt::encrypt($trxMaster->id), 'view'=>$view];
                $this->kirimEmail($email_info);
            }else if($view == 'realisasi_l1'){
                $view = 'realisasi_l2';
                $email_to = array();
                $arr['email_to'] = json_decode($trxMaster->layer2_property, true)['email'];
                $arr['nama_to'] = json_decode($trxMaster->layer2_property, true)['nama'];
                $arr['nik_to'] = json_decode($trxMaster->layer2_property, true)['nik'];
                $arr['id_trx'] = $id;
                $arr['id_status'] = $status_id;
                $arr['pic'] = $view;
                // check apakah layer1 == layer2, jika iya maka dibypass dan laye1 setuju
                if($trxMaster->layer1 == $trxMaster->layer2 && $status_id == 24){
                    // 26 = Realisasi Disetujui L2
                    $status_id = 26;
                    $view = 'realisasi_hco';
                    $arr['email_to'] = json_decode($trxMaster->hco_property, true)['email'];
                    $arr['nama_to'] = json_decode($trxMaster->hco_property, true)['nama'];
                    $arr['nik_to'] = json_decode($trxMaster->hco_property, true)['nik'];
                    $arr['id_trx'] = $id;
                    $arr['id_status'] = $status_id;
                    $arr['pic'] = $view;
                    // 
                    $trxMaster->status_id = $status_id;
                    $pic_nik = $nik;
                    $trxMaster->update();
                    // TrxBpdLog
                    $keterangan = $request->catatan;
                    $this->insertLog($id, $status_id, $pic_nik, $keterangan);
                }
                // 25 = Realisasi Ditolak L1
                if($status_id == 25){
                    $view = 'reject_realisasi_l1';
                    $arr['email_to'] = json_decode($trxMaster->admin_property, true)['email'];
                    $arr['nama_to'] = json_decode($trxMaster->admin_property, true)['nama'];
                    $arr['nik_to'] = json_decode($trxMaster->admin_property, true)['nik'];
                    $arr['id_trx'] = $id;
                    $arr['id_status'] = $status_id;
                    $arr['pic'] = $view;
                }
                array_push($email_to, $arr);
                $email_info = ['pengaju_property'=>$trxMaster->pengaju_property, 'atasan_property'=>$trxMaster->atasan_property, 'luar_dalam'=>'realisasi_'.$trxMaster->luar_dalam, 'email_to'=>$email_to, 'id'=>Crypt::encrypt($trxMaster->id), 'view'=>$view];
                $this->kirimEmail($email_info);
            }else if($view == 'realisasi_l2'){
                $view = 'realisasi_hco';
                $email_to = array();
                $arr['email_to'] = json_decode($trxMaster->hco_property, true)['email'];
                $arr['nama_to'] = json_decode($trxMaster->hco_property, true)['nama'];
                $arr['nik_to'] = json_decode($trxMaster->hco_property, true)['nik'];
                $arr['id_trx'] = $id;
                $arr['id_status'] = $status_id;
                $arr['pic'] = $view;
                // 27 = Realisasi Ditolak L2
                if($status_id == 27){
                    $view = 'reject_realisasi_l2';
                    $arr['email_to'] = json_decode($trxMaster->admin_property, true)['email'];
                    $arr['nama_to'] = json_decode($trxMaster->admin_property, true)['nama'];
                    $arr['nik_to'] = json_decode($trxMaster->admin_property, true)['nik'];
                    $arr['id_trx'] = $id;
                    $arr['id_status'] = $status_id;
                    $arr['pic'] = $view;
                }
                array_push($email_to, $arr);
                $email_info = ['pengaju_property'=>$trxMaster->pengaju_property, 'atasan_property'=>$trxMaster->atasan_property, 'luar_dalam'=>'realisasi_'.$trxMaster->luar_dalam, 'email_to'=>$email_to, 'id'=>Crypt::encrypt($trxMaster->id), 'view'=>$view];
                $this->kirimEmail($email_info);
            }else if($view == 'realisasi_hco'){
                $view = 'realisasi_done';
                $email_to = array();
                $arr['email_to'] = json_decode($trxMaster->pengaju_property, true)['email'];
                $arr['nama_to'] = json_decode($trxMaster->pengaju_property, true)['nama'];
                $arr['nik_to'] = json_decode($trxMaster->pengaju_property, true)['nik'];
                $arr['id_trx'] = $id;
                $arr['id_status'] = $status_id;
                $arr['pic'] = $view;
                // 29 = Realisasi Ditolak PC
                if($status_id == 29){
                    $view = 'reject_realisasi_hco';
                    $arr['email_to'] = json_decode($trxMaster->admin_property, true)['email'];
                    $arr['nama_to'] = json_decode($trxMaster->admin_property, true)['nama'];
                    $arr['nik_to'] = json_decode($trxMaster->admin_property, true)['nik'];
                    $arr['id_trx'] = $id;
                    $arr['id_status'] = $status_id;
                    $arr['pic'] = $view;
                }else{
                    $view = 'realisasi_done';
                    $_arr['email_to'] = json_decode($trxMaster->admin_property, true)['email'];
                    $_arr['nama_to'] = json_decode($trxMaster->admin_property, true)['nama'];
                    $_arr['nik_to'] = json_decode($trxMaster->admin_property, true)['nik'];
                    $_arr['id_trx'] = $id;
                    $_arr['id_status'] = $status_id;
                    $_arr['pic'] = $view;
                    array_push($email_to, $_arr);
                }
                array_push($email_to, $arr);
                $email_info = ['pengaju_property'=>$trxMaster->pengaju_property, 'atasan_property'=>$trxMaster->atasan_property, 'luar_dalam'=>'realisasi_'.$trxMaster->luar_dalam, 'email_to'=>$email_to, 'id'=>Crypt::encrypt($trxMaster->id), 'view'=>$view];
                $this->kirimEmail($email_info);
            }
        }
        // process by project admin untuk reimburse
        else if($view == 'reimburse'){
            $admin = $nik;
            $admin_property = $this->getNikProperty($admin);
            $trxMaster->admin = $admin;
            $trxMaster->admin_property = $admin_property;
            $trxMaster->status_id = $status_id;
            $trxMaster->update();
            // TrxBpdLog
            $keterangan = $request->catatan;
            $this->insertLog($id, $status_id, $admin, $keterangan);
            // 
            $view = 'reimburse_pa';
            $email_to = array();
            $arr['email_to'] = json_decode($trxMaster->admin_property, true)['email'];
            $arr['nama_to'] = json_decode($trxMaster->admin_property, true)['nama'];
            $arr['nik_to'] = json_decode($trxMaster->admin_property, true)['nik'];
            $arr['id_trx'] = $id;
            $arr['id_status'] = $status_id;
            $arr['pic'] = $view;
            // 36 = Reimburse Ditolak Admin
            if($status_id == 36){
                $view = 'reject_reimburse_pa';
                $arr['email_to'] = json_decode($trxMaster->pengaju_property, true)['email'];
                $arr['nama_to'] = json_decode($trxMaster->pengaju_property, true)['nama'];
                $arr['nik_to'] = json_decode($trxMaster->pengaju_property, true)['nik'];
                $arr['id_trx'] = $id;
                $arr['id_status'] = $status_id;
                $arr['pic'] = $view;
            }
            array_push($email_to, $arr);
            $email_info = ['pengaju_property'=>$trxMaster->pengaju_property, 'atasan_property'=>$trxMaster->atasan_property, 'luar_dalam'=>$trxMaster->luar_dalam, 'email_to'=>$email_to, 'id'=>Crypt::encrypt($trxMaster->id), 'view'=>$view];
            $this->kirimEmail($email_info);
        }
        // review by project admin untuk reimburse
        else if($view == 'reimburse_pa' || $view == 'reject_reimburse_pc' || $view == 'reject_reimburse_l1' || $view == 'reject_reimburse_l2' || $view == 'reject_reimburse_hco'){
            // jika direject maka uang saku harus dihapus semua
            // selanjutnya akan dihitung ulang terhadap updatenya
            if($view <> 'reimburse_pa'){
                $where = ['id_trx' => $id, 'jenis' => 'coa'];
                $_result = TrxBpdDetailBiaya::where($where)->whereIn('id_coa', [1,2,76,89])->delete();

            }
            // get kelompok
            $_pengaju_property = json_decode($trxMaster->pengaju_property, true);
            $id_level = $_pengaju_property['id_level'];
            $kelompok = array();
            $kelompok_id = 0;
            $where = ['is_project' => $is_project];
            $_data = BpdKelompok::where($where)->get();
            foreach ($_data as $data){
                $_kelompok = explode(',', $data->level);
                if (in_array($id_level, $_kelompok)){
                    $kelompok_id = $data->id;
                    break;
                }
            }
            $pengaju = $trxMaster->pengaju;
            $trxMaster->kelompok_id = $kelompok_id;
            // 
            $trxMaster->is_project = $is_project;
            // 
            $project_id = $request->project_id;
            $trxMaster->project_id = $project_id;
            // 
            $is_leader = $request->is_leader;
            $trxMaster->is_leader = $is_leader;
            // 
            $trxMaster->status_id = $status_id;
            // 
            $pic_nik = $nik;
            // 
            $view = 'reimburse_pc';
            if($is_project == 0){
                $view = 'reimburse_l1';
            }
            $email_to = array();
            // jika project maka flownya adalah pengaju->koordinator->layer1->layer2
            if($is_project == 1){
                $koordinator = $request->koordinator;
                $trxMaster->koordinator = $koordinator;
                $koordinator_property = $this->getNikProperty($koordinator);
                $trxMaster->koordinator_property = $koordinator_property;
                // 
                $where = ['nik' => $koordinator];
                $result = Karyawan::where($where)->first();
                $layer1 = $result->layer1;
                $layer2 = $result->layer2;
                // pengecualian line approval
                // 4 = Assistant Manager
                // 1 = Leader = 1
                if(!empty(DB::select(DB::raw("SELECT a.nik,b.nama,b.layer1,b.layer2,c.nama FROM mtr_tbl_bpd_nik_peran AS a LEFT JOIN mtr_tbl_karyawan AS b ON a.nik=b.nik LEFT JOIN mtr_tbl_karyawan AS c ON b.layer2=c.nik WHERE b.kode_level=4 AND a.id_peran=1 AND c.kode_level=14 AND a.nik='".$trxMaster->koordinator."'")))){
                    $layer2 = $layer1;
                }
                // 5 = Junior Manager (JM)
                // 1 = Leader = 1
                else if(!empty(DB::select(DB::raw("SELECT a.nik,b.nama,b.layer1,b.layer2,c.nama FROM mtr_tbl_bpd_nik_peran AS a LEFT JOIN mtr_tbl_karyawan AS b ON a.nik=b.nik LEFT JOIN mtr_tbl_karyawan AS c ON b.layer2=c.nik WHERE b.kode_level=5 AND a.id_peran=1 AND c.kode_level=14 AND a.nik='".$trxMaster->koordinator."'")))){
                    $layer2 = $layer1;
                }
                // 1 = Non Staff
                // 2 = Member = 2
                else if(!empty(DB::select(DB::raw("SELECT a.nik,b.nama,b.layer1,b.layer2,c.nama FROM mtr_tbl_bpd_nik_peran AS a LEFT JOIN mtr_tbl_karyawan AS b ON a.nik=b.nik LEFT JOIN mtr_tbl_karyawan AS c ON b.layer2=c.nik WHERE b.kode_level=1 AND a.id_peran=2 AND c.kode_level=14 AND a.nik='".$trxMaster->koordinator."'")))){
                    $layer2 = $layer1;
                }
                // 2 = Staff
                // 2 = Member = 2
                else if(!empty(DB::select(DB::raw("SELECT a.nik,b.nama,b.layer1,b.layer2,c.nama FROM mtr_tbl_bpd_nik_peran AS a LEFT JOIN mtr_tbl_karyawan AS b ON a.nik=b.nik LEFT JOIN mtr_tbl_karyawan AS c ON b.layer2=c.nik WHERE b.kode_level=2 AND a.id_peran=2 AND c.kode_level=14 AND a.nik='".$trxMaster->koordinator."'")))){
                    $layer2 = $layer1;
                }
                // 3 = Supervisor (SPV)
                // 2 = Member = 2
                else if(!empty(DB::select(DB::raw("SELECT a.nik,b.nama,b.layer1,b.layer2,c.nama FROM mtr_tbl_bpd_nik_peran AS a LEFT JOIN mtr_tbl_karyawan AS b ON a.nik=b.nik LEFT JOIN mtr_tbl_karyawan AS c ON b.layer2=c.nik WHERE b.kode_level=3 AND a.id_peran=2 AND c.kode_level=14 AND a.nik='".$trxMaster->koordinator."'")))){
                    $layer2 = $layer1;
                }
                // pengecualian line approval untuk data di table mtr_tbl_bpd_nik_bod 
                $where = ['is_active' => 1 , 'nik' => $trxMaster->pengaju];
                $bod = BpdNikBod::where($where)->first();
                if(!empty($bod)){
                    $layer1 = $bod->layer1;
                    $layer2 = $bod->layer2;
                }
                $trxMaster->layer1 = $layer1;
                $trxMaster->layer2 = $layer2;
                $layer1_property = $this->getNikProperty($layer1);
                $trxMaster->layer1_property = $layer1_property;
                $layer2_property = $this->getNikProperty($layer2);
                $trxMaster->layer2_property = $layer2_property;
                // 
                $email_to = array();
                $arr['email_to'] = json_decode($trxMaster->koordinator_property, true)['email'];
                $arr['nama_to'] = json_decode($trxMaster->koordinator_property, true)['nama'];
                $arr['nik_to'] = json_decode($trxMaster->koordinator_property, true)['nik'];
                $arr['id_trx'] = $id;
                $arr['id_status'] = $status_id;
                $arr['pic'] = $view;
                // 36 = Reimburse Ditolak Admin
                if($status_id == 36){
                    $view = 'reject_reimburse_pa';
                    $arr['email_to'] = json_decode($trxMaster->pengaju_property, true)['email'];
                    $arr['nama_to'] = json_decode($trxMaster->pengaju_property, true)['nama'];
                    $arr['nik_to'] = json_decode($trxMaster->pengaju_property, true)['nik'];
                    $arr['id_trx'] = $id;
                    $arr['id_status'] = $status_id;
                    $arr['pic'] = $view;
                }
                array_push($email_to, $arr);
            }
            // jika non project maka flownya adalah pengaju->layer1->layer2
            else{
                $where = ['nik' => $pengaju];
                $result = Karyawan::where($where)->first();
                $layer1 = $result->layer1;
                $layer2 = $result->layer2;
                // pengecualian line approval
                // 4 = Assistant Manager
                // 1 = Leader = 1
                if(!empty(DB::select(DB::raw("SELECT a.nik,b.nama,b.layer1,b.layer2,c.nama FROM mtr_tbl_bpd_nik_peran AS a LEFT JOIN mtr_tbl_karyawan AS b ON a.nik=b.nik LEFT JOIN mtr_tbl_karyawan AS c ON b.layer2=c.nik WHERE b.kode_level=4 AND a.id_peran=1 AND c.kode_level=14 AND a.nik='".$trxMaster->pengaju."'")))){
                    $layer2 = $layer1;
                }
                // 5 = Junior Manager (JM)
                // 1 = Leader = 1
                else if(!empty(DB::select(DB::raw("SELECT a.nik,b.nama,b.layer1,b.layer2,c.nama FROM mtr_tbl_bpd_nik_peran AS a LEFT JOIN mtr_tbl_karyawan AS b ON a.nik=b.nik LEFT JOIN mtr_tbl_karyawan AS c ON b.layer2=c.nik WHERE b.kode_level=5 AND a.id_peran=1 AND c.kode_level=14 AND a.nik='".$trxMaster->pengaju."'")))){
                    $layer2 = $layer1;
                }
                // 1 = Non Staff
                // 2 = Member = 2
                else if(!empty(DB::select(DB::raw("SELECT a.nik,b.nama,b.layer1,b.layer2,c.nama FROM mtr_tbl_bpd_nik_peran AS a LEFT JOIN mtr_tbl_karyawan AS b ON a.nik=b.nik LEFT JOIN mtr_tbl_karyawan AS c ON b.layer2=c.nik WHERE b.kode_level=1 AND a.id_peran=2 AND c.kode_level=14 AND a.nik='".$trxMaster->pengaju."'")))){
                    $layer2 = $layer1;
                }
                // 2 = Staff
                // 2 = Member = 2
                else if(!empty(DB::select(DB::raw("SELECT a.nik,b.nama,b.layer1,b.layer2,c.nama FROM mtr_tbl_bpd_nik_peran AS a LEFT JOIN mtr_tbl_karyawan AS b ON a.nik=b.nik LEFT JOIN mtr_tbl_karyawan AS c ON b.layer2=c.nik WHERE b.kode_level=2 AND a.id_peran=2 AND c.kode_level=14 AND a.nik='".$trxMaster->pengaju."'")))){
                    $layer2 = $layer1;
                }
                // 3 = Supervisor (SPV)
                // 2 = Member = 2
                else if(!empty(DB::select(DB::raw("SELECT a.nik,b.nama,b.layer1,b.layer2,c.nama FROM mtr_tbl_bpd_nik_peran AS a LEFT JOIN mtr_tbl_karyawan AS b ON a.nik=b.nik LEFT JOIN mtr_tbl_karyawan AS c ON b.layer2=c.nik WHERE b.kode_level=3 AND a.id_peran=2 AND c.kode_level=14 AND a.nik='".$trxMaster->pengaju."'")))>0){
                    $layer2 = $layer1;
                }
                // pengecualian line approval untuk data di table mtr_tbl_bpd_nik_bod 
                $where = ['is_active' => 1 , 'nik' => $trxMaster->pengaju];
                $bod = BpdNikBod::where($where)->first();
                if(!empty($bod)){
                    $layer1 = $bod->layer1;
                    $layer2 = $bod->layer2;
                }
                $trxMaster->layer1 = $layer1;
                $trxMaster->layer2 = $layer2;
                $layer1_property = $this->getNikProperty($layer1);
                $trxMaster->layer1_property = $layer1_property;
                $layer2_property = $this->getNikProperty($layer2);
                $trxMaster->layer2_property = $layer2_property;
                // 
                $email_to = array();
                $arr['email_to'] = json_decode($trxMaster->layer1_property, true)['email'];
                $arr['nama_to'] = json_decode($trxMaster->layer1_property, true)['nama'];
                $arr['nik_to'] = json_decode($trxMaster->layer1_property, true)['nik'];
                $arr['id_trx'] = $id;
                $arr['id_status'] = $status_id;
                $arr['pic'] = $view;
                // 36 = Reimburse Ditolak Admin
                if($status_id == 36){
                    $view = 'reject_reimburse_pa';
                    $arr['email_to'] = json_decode($trxMaster->pengaju_property, true)['email'];
                    $arr['nama_to'] = json_decode($trxMaster->pengaju_property, true)['nama'];
                    $arr['nik_to'] = json_decode($trxMaster->pengaju_property, true)['nik'];
                    $arr['id_trx'] = $id;
                    $arr['id_status'] = $status_id;
                    $arr['pic'] = $view;
                }
                array_push($email_to, $arr);
            }
            // 24 = PIC BPD Non Manajemen
            $hco = MtrUtility::find(24)->informasi;
            if (in_array($trxMaster->pengaju, $arr_nik_nkp)){
                // 46 = NIP PIC BPD Non Manajemen (Nasio)
                $hco = MtrUtility::find(46)->informasi;
            }
            // id_peran=3 adalah Management
            $where = ['nik' => $pengaju, 'id_peran' => 3, 'is_active' => 1];
            $is_mgt = BpdNikPeran::where($where)->exists();
            // 25 = PIC BPD Manajemen
            if($is_mgt){
                $hco = MtrUtility::find(25)->informasi;
            }
            $trxMaster->hco = $hco;
            $hco_property = $this->getNikProperty($hco);
            $trxMaster->hco_property = $hco_property;
            $trxMaster->update();
            // TrxBpdLog
            $keterangan = $request->catatan;
            $this->insertLog($id, $status_id, $pic_nik, $keterangan);
            // updateBpdBiaya dengan kode coa terhadap ca pengaju
            $data = $request->all();
            $arr_bpd = array();
            $arr_bpd_biaya = array();
            $arr_bpd_approve = array();
            foreach($data as $_id => $value){
                if(str_starts_with($_id, 'biayacoa_')){
                    array_push($arr_bpd_biaya, ['id'=>explode('_', $_id)[1], 'biaya'=>$value]);
                }
                if(str_starts_with($_id, 'isapprove_')){
                    array_push($arr_bpd_approve, ['is_approve'=>$value]);
                }
            }
            for ($x = 0; $x < count($arr_bpd_biaya); $x++){
                $_arr_bpd['id_biaya'] = $arr_bpd_biaya[$x];
                $_arr_bpd['is_approve'] = $arr_bpd_approve[$x];
                array_push($arr_bpd, $_arr_bpd);
            }
            foreach($arr_bpd as $_arr_bpd){
                $this->updateBpdBiaya(['id'=>$_arr_bpd['id_biaya']['id'], 'biaya'=>$_arr_bpd['id_biaya']['biaya'], 'is_approve'=>$_arr_bpd['is_approve']['is_approve']]);
            }
            // updateBpdInfo dengan ada atau tidak ada uang saku
            foreach($data as $_id => $value){
                if(str_starts_with($_id, 'uangsaku_')){
                    $this->updateBpdInfo(['id'=>explode('_', $_id)[1], 'is_approve'=>$value]);
                }
            }
            // uang saku insertBpdUangSaku pengaju
            $_uang_saku = 0;
            // project
            if($is_project == 1){
                // jika project dan reimburse
                if($trxMaster->luar_dalam == 'reimburse'){
                    // $where = ['id_trx'=>$id, 'luar_dalam'=>$trxMaster->luar_dalam, 'jenis'=>'ca'];
                    // $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                    // jika dalam 1 propinsi yang sama maka jumlah hari digabungkan
                    $trxDetailInfo = DB::select(DB::raw("SELECT id,id_trx,jenis,luar_dalam,tujuan1,SUM(DATEDIFF(DATE_ADD(tanggal_akhir, INTERVAL 1 DAY),tanggal_mulai)) AS hari FROM trx_tbl_bpd_detail_info WHERE id_trx=".$id." AND luar_dalam='reimburse' AND jenis='reimburse' AND is_approve=1 GROUP BY tujuan1"));
                    $this->insertBpdUangSaku($trxDetailInfo, $is_project, $trxMaster->luar_dalam, $kelompok_id, $project_id, $id, 'coa');
                }
                // tidak ada untuk reimburse
                // jika project dan ca luar negeri
                else if($trxMaster->luar_dalam == 'reimburse_luar'){
                    // NA
                }
            }
            // non project
            else if($is_project == 0){
                // jika non project dan reimburse
                if($trxMaster->luar_dalam == 'reimburse'){
                    $where = ['id_trx'=>$id, 'luar_dalam'=>$trxMaster->luar_dalam, 'jenis'=>'reimburse', 'is_approve'=>1];
                    $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                    $this->insertBpdUangSaku($trxDetailInfo, $is_project, $trxMaster->luar_dalam, $kelompok_id, $project_id, $id, 'coa');
                }
                // refer to cm 372
                // jika non project dan reimburse luar negeri
                else if($trxMaster->luar_dalam == 'reimburse_luar'){
                    $where = ['id_trx'=>$id, 'luar_dalam'=>$trxMaster->luar_dalam, 'jenis'=>'reimburse', 'is_approve'=>1];
                    $trxDetailInfo = TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                    $this->insertBpdUangSaku($trxDetailInfo, $is_project, $trxMaster->luar_dalam, $kelompok_id, $project_id, $id, 'coa');
                }
            }
            // 
            $email_info = ['pengaju_property'=>$trxMaster->pengaju_property, 'atasan_property'=>$trxMaster->atasan_property, 'luar_dalam'=>$trxMaster->luar_dalam, 'email_to'=>$email_to, 'id'=>Crypt::encrypt($trxMaster->id), 'view'=>$view];
            $this->kirimEmail($email_info);
        }
        // review by project coordinator - layer 1 - layer 2 - hco
        else if($view == 'reimburse_pc' || $view == 'reimburse_l1' || $view == 'reimburse_l2' || $view == 'reimburse_hco'){
            $trxMaster->status_id = $status_id;
            // 
            $pic_nik = $nik;
            $trxMaster->update();
            // TrxBpdLog
            $keterangan = $request->catatan;
            $this->insertLog($id, $status_id, $pic_nik, $keterangan);
            // 
            $email_to = array();
            if($view == 'reimburse_pc'){
                $view = 'reimburse_l1';
                $email_to = array();
                $arr['email_to'] = json_decode($trxMaster->layer1_property, true)['email'];
                $arr['nama_to'] = json_decode($trxMaster->layer1_property, true)['nama'];
                $arr['nik_to'] = json_decode($trxMaster->layer1_property, true)['nik'];
                $arr['id_trx'] = $id;
                $arr['id_status'] = $status_id;
                $arr['pic'] = $view;
                // 39 = Reimburse Ditolak PC
                if($status_id == 39){
                    $view = 'reject_reimburse_pc';
                    $arr['email_to'] = json_decode($trxMaster->admin_property, true)['email'];
                    $arr['nama_to'] = json_decode($trxMaster->admin_property, true)['nama'];
                    $arr['nik_to'] = json_decode($trxMaster->admin_property, true)['nik'];
                    $arr['id_trx'] = $id;
                    $arr['id_status'] = $status_id;
                    $arr['pic'] = $view;
                }
                array_push($email_to, $arr);
                $email_info = ['pengaju_property'=>$trxMaster->pengaju_property, 'atasan_property'=>$trxMaster->atasan_property, 'luar_dalam'=>$trxMaster->luar_dalam, 'email_to'=>$email_to, 'id'=>Crypt::encrypt($trxMaster->id), 'view'=>$view];
                $this->kirimEmail($email_info);
            }else if($view == 'reimburse_l1'){
                $view = 'reimburse_l2';
                $email_to = array();
                $arr['email_to'] = json_decode($trxMaster->layer2_property, true)['email'];
                $arr['nama_to'] = json_decode($trxMaster->layer2_property, true)['nama'];
                $arr['nik_to'] = json_decode($trxMaster->layer2_property, true)['nik'];
                $arr['id_trx'] = $id;
                $arr['id_status'] = $status_id;
                $arr['pic'] = $view;
                // check apakah layer1 == layer2, jika iya maka dibypass dan laye1 setuju
                if($trxMaster->layer1 == $trxMaster->layer2 && $status_id == 40){
                    // 42 = Reimburse Disetujui L2
                    $status_id = 42;
                    $view = 'reimburse_hco';
                    $arr['email_to'] = json_decode($trxMaster->hco_property, true)['email'];
                    $arr['nama_to'] = json_decode($trxMaster->hco_property, true)['nama'];
                    $arr['nik_to'] = json_decode($trxMaster->hco_property, true)['nik'];
                    $arr['id_trx'] = $id;
                    $arr['id_status'] = $status_id;
                    $arr['pic'] = $view;
                    // 
                    $trxMaster->status_id = $status_id;
                    $pic_nik = $nik;
                    $trxMaster->update();
                    // TrxBpdLog
                    $keterangan = $request->catatan;
                    $this->insertLog($id, $status_id, $pic_nik, $keterangan);
                }
                // 41 = Reimburse Ditolak L1
                if($status_id == 41){
                    $view = 'reject_reimburse_l1';
                    $arr['email_to'] = json_decode($trxMaster->admin_property, true)['email'];
                    $arr['nama_to'] = json_decode($trxMaster->admin_property, true)['nama'];
                    $arr['nik_to'] = json_decode($trxMaster->admin_property, true)['nik'];
                    $arr['id_trx'] = $id;
                    $arr['id_status'] = $status_id;
                    $arr['pic'] = $view;
                }
                array_push($email_to, $arr);
                $email_info = ['pengaju_property'=>$trxMaster->pengaju_property, 'atasan_property'=>$trxMaster->atasan_property, 'luar_dalam'=>$trxMaster->luar_dalam, 'email_to'=>$email_to, 'id'=>Crypt::encrypt($trxMaster->id), 'view'=>$view];
                $this->kirimEmail($email_info);
            }else if($view == 'reimburse_l2'){
                $view = 'reimburse_hco';
                $email_to = array();
                $arr['email_to'] = json_decode($trxMaster->hco_property, true)['email'];
                $arr['nama_to'] = json_decode($trxMaster->hco_property, true)['nama'];
                $arr['nik_to'] = json_decode($trxMaster->hco_property, true)['nik'];
                $arr['id_trx'] = $id;
                $arr['id_status'] = $status_id;
                $arr['pic'] = $view;
                // 43 = Reimburse Ditolak L2
                if($status_id == 43){
                    $view = 'reject_reimburse_l2';
                    $arr['email_to'] = json_decode($trxMaster->admin_property, true)['email'];
                    $arr['nama_to'] = json_decode($trxMaster->admin_property, true)['nama'];
                    $arr['nik_to'] = json_decode($trxMaster->admin_property, true)['nik'];
                    $arr['id_trx'] = $id;
                    $arr['id_status'] = $status_id;
                    $arr['pic'] = $view;
                }
                array_push($email_to, $arr);
                $email_info = ['pengaju_property'=>$trxMaster->pengaju_property, 'atasan_property'=>$trxMaster->atasan_property, 'luar_dalam'=>$trxMaster->luar_dalam, 'email_to'=>$email_to, 'id'=>Crypt::encrypt($trxMaster->id), 'view'=>$view];
                $this->kirimEmail($email_info);
            }else if($view == 'reimburse_hco'){
                $view = 'reimburse_done';
                $email_to = array();
                $arr['email_to'] = json_decode($trxMaster->pengaju_property, true)['email'];
                $arr['nama_to'] = json_decode($trxMaster->pengaju_property, true)['nama'];
                $arr['nik_to'] = json_decode($trxMaster->pengaju_property, true)['nik'];
                $arr['id_trx'] = $id;
                $arr['id_status'] = $status_id;
                $arr['pic'] = $view;
                // 45 = Reimburse Ditolak PC
                if($status_id == 45){
                    $view = 'reject_reimburse_hco';
                    $arr['email_to'] = json_decode($trxMaster->admin_property, true)['email'];
                    $arr['nama_to'] = json_decode($trxMaster->admin_property, true)['nama'];
                    $arr['nik_to'] = json_decode($trxMaster->admin_property, true)['nik'];
                    $arr['id_trx'] = $id;
                    $arr['id_status'] = $status_id;
                    $arr['pic'] = $view;
                }else{
                    $view = 'reimburse_done';
                    $_arr['email_to'] = json_decode($trxMaster->admin_property, true)['email'];
                    $_arr['nama_to'] = json_decode($trxMaster->admin_property, true)['nama'];
                    $_arr['nik_to'] = json_decode($trxMaster->admin_property, true)['nik'];
                    $_arr['id_trx'] = $id;
                    $_arr['id_status'] = $status_id;
                    $_arr['pic'] = $view;
                    array_push($email_to, $_arr);
                }
                array_push($email_to, $arr);
                $email_info = ['pengaju_property'=>$trxMaster->pengaju_property, 'atasan_property'=>$trxMaster->atasan_property, 'luar_dalam'=>$trxMaster->luar_dalam, 'email_to'=>$email_to, 'id'=>Crypt::encrypt($trxMaster->id), 'view'=>$view];
                $this->kirimEmail($email_info);
            }
        }
        // cancel pengajuan bpd
        else if($view == 'cancel'){
            $trxMaster->status_id = $status_id;
            // 
            $pic_nik = $nik;
            $trxMaster->update();
            // TrxBpdLog
            $keterangan = $request->catatan;
            $this->insertLog($id, $status_id, $pic_nik, $keterangan);
            $this->deleteToDoList(['id_trx'=>$id, 'filename'=>'emails.bpd0']);
        }
        return redirect('bpd')->with('msg', 'Data is saved successfully.');
    }

    // delete trxDetailInfo
    public function delete_info(Request $request, $id)
    {
        // 
        $data = TrxBpdDetailInfo::find($request->id_data);
        $data->delete();
        return redirect()->route('bpd.show', [$id, 'view'=>$request->view])->with( 'msg', 'Data is deleted successfully.' );
    }

    // delete TrxBpdDetailBiaya
    public function delete_biaya(Request $request, $id)
    {
        //
        $data = TrxBpdDetailBiaya::find($request->id_data);
        $data->delete();
        return redirect()->route('bpd.show', [$id, 'view'=>$request->view])->with( 'msg', 'Data is deleted successfully.' );
    }

    // update TrxBpdDetailBiaya
    public function update_biaya(Request $request, $id)
    {
        $data = $request->all();
        $nominal = 0;
        foreach($data as $_id => $value){
            if(str_starts_with($_id, 'nominal')){
                $nominal = $value;
            }
        }
        $nominal = str_replace( ',', '', $nominal );
        //
        $trx = TrxBpdDetailBiaya::find($request->id_data);
        $trx->nominal = $nominal;
        $trx->update();
        return redirect()->route('bpd.show', [$id, 'view'=>'edit'])->with( 'msg', 'Data is updated successfully.' );
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
        // dd($id);
    }

    public function insertBpdInfo($bpd)
    {
        for ($x = 0; $x < count($bpd['tanggal_mulai']); $x++) {
            $data = new TrxBpdDetailInfo;
            $data->id_trx = $bpd['id_trx'];
            $data->jenis = $bpd['jenis'];
            $data->luar_dalam = $bpd['luar_dalam'];
            $data->tujuan1 = $bpd['tujuan1'][$x];
            if($bpd['luar_dalam']=='ca_dalam' || $bpd['luar_dalam']=='reimburse'){
                $data->tujuan2 = $bpd['tujuan2'][$x];
            }
            $data->tanggal_mulai = $bpd['tanggal_mulai'][$x];
            $data->tanggal_akhir = $bpd['tanggal_akhir'][$x];
            // 18 = Realisasi CA
            // 21 = Perbaikan Realisasi oleh Pengaju
            $where = ['id' => $bpd['id_trx']];
            $check = TrxBpdMaster::where($where)->whereIn('status_id', [18, 21])->exists();
            if($check){
                $data->is_approve = 1;
            }
            $data->save();
        }
    }

    public function insertBpdUangSaku($trxDetailInfo, $is_project, $luar_dalam, $kelompok_id, $project_id, $id, $jenis)
    {
        // dd($trxDetailInfo, $is_project, $luar_dalam, $kelompok_id, $project_id, $id, $jenis);
        $is_approve = 1;
        // project
        if($is_project == 1){
            // ca_dalam
            if(in_array($luar_dalam, ['ca_dalam', 'reimburse'])){
                foreach ($trxDetailInfo as $data){
                    // $hari = Carbon::parse($data->tanggal_mulai)->diffInDays(Carbon::parse($data->tanggal_akhir))+1;
                    $hari = $data->hari;
                    // dd($hari);
                    // jika Project IOH dan ada id_kelompok nya
                    $_where = ['id_proyek'=>$project_id, 'id_kelompok'=>$kelompok_id];
                    $uang_saku = BpdProyekUangSakuKelompok::where($_where)->first();
                    if(!empty($uang_saku)){
                        $_uang_saku = $uang_saku->uangsaku;
                        // 1 = 500240 = BIAYA UANG SAKU = Project
                        // 2 = 500240 = BIAYA UANG SAKU = Support
                        $biaya = 1;
                        $satuan = 'IDR';
                        $bpd_biaya = ['id_info'=>$data->id, 'jenis'=>$jenis, 'id'=>$id, 'biaya'=>array($biaya), 'satuan'=>array($satuan), 'nominal'=>array($_uang_saku), 'jumlah'=>array($hari), 'is_approve'=>$is_approve];
                        $this->insertBpdBiaya($bpd_biaya);
                    }
                    // jika bukan Project IOH atau tidak ada id_kelompok nya
                    else{
                        // pecah-pecah jumlah hari sesuai dengan batasan jumlah hari berdasarkan kelompok
                        $arr_hari['hari'] = array();
                        $arr_hari['kelompok'] = array();
                        // jumlah hari dari 1 sd 7 hari
                        if($hari < 8){
                            array_push($arr_hari['hari'], $hari);
                            array_push($arr_hari['kelompok'], 7);
                        }
                        // jumlah hari dari 8 sd 30 hari
                        else if($hari > 7 && $hari < 31){
                            array_push($arr_hari['hari'], 7);
                            array_push($arr_hari['kelompok'], 7);
                            array_push($arr_hari['hari'], $hari-7);
                            array_push($arr_hari['kelompok'], 30);
                        }
                        // jumlah hari lebih dari 30 hari
                        else if($hari > 30){
                            array_push($arr_hari['hari'], 7);
                            array_push($arr_hari['kelompok'], 7);
                            array_push($arr_hari['hari'], 23);
                            array_push($arr_hari['kelompok'], 30);
                            array_push($arr_hari['hari'], $hari-30);
                            array_push($arr_hari['kelompok'], 365);
                        }
                        // 
                        for ($x = 0; $x < count($arr_hari['hari']); $x++){
                            $_where = ['id_propinsi'=>$data->tujuan1];
                            $uang_saku = BpdProyekUangSaku::where($_where)->where('awal','<=', $arr_hari['kelompok'][$x])->where('akhir','>=', $arr_hari['kelompok'][$x])->first();
                            // 1 = Kelompok I
                            if($kelompok_id == 1){
                                $_uang_saku = $uang_saku->uangsaku_1;
                            }
                            // 2 = Kelompok II
                            else if($kelompok_id == 2){
                                $_uang_saku = $uang_saku->uangsaku_2;
                            }
                            // 3 = Kelompok III
                            else if($kelompok_id == 3){
                                $_uang_saku = $uang_saku->uangsaku_3;
                            }
                            // 4 = Kelompok IV
                            else if($kelompok_id == 4){
                                $_uang_saku = $uang_saku->uangsaku_4;
                            }
                            // 5 = Kelompok V
                            else if($kelompok_id == 5){
                                $_uang_saku = $uang_saku->uangsaku_5;
                            }
                            // Kecuali BpdProyekUangSakuInstalasi
                            $install = BpdProyekUangSakuInstalasi::where(['nik'=>TrxBpdMaster::find($data->id_trx)->pengaju])->first();
                            if(!empty($install)){
                                $_uang_saku = $install->uangsaku;
                            }
                            // 1 = 500240 = BIAYA UANG SAKU = Project
                            // 2 = 500240 = BIAYA UANG SAKU = Support
                            $biaya = 1;
                            $satuan = 'IDR';
                            $bpd_biaya = ['id_info'=>$data->id, 'jenis'=>$jenis, 'id'=>$id, 'biaya'=>array($biaya), 'satuan'=>array($satuan), 'nominal'=>array($_uang_saku), 'jumlah'=>array($arr_hari['hari'][$x]), 'is_approve'=>$is_approve];
                            $this->insertBpdBiaya($bpd_biaya);
                        }
                    }
                }
            }
            // ca_luar
            // else if($luar_dalam == 'ca_luar'){
            //     // NA
            // }
        }
        // non project
        else if($is_project == 0){
            // 
            if(in_array($luar_dalam, ['ca_dalam', 'reimburse'])){
                foreach ($trxDetailInfo as $data){
                    $hari = Carbon::parse($data->tanggal_mulai)->diffInDays(Carbon::parse($data->tanggal_akhir))+1;
                    $uang_saku = BpdNonProyekBiayaDalamNegeri::find(1);
                    // 6 = Kelompok I
                    if($kelompok_id == 6){
                        $_uang_saku = $uang_saku->kelompok_1;
                    }
                    // 7 = Kelompok II
                    else if($kelompok_id == 7){
                        $_uang_saku = $uang_saku->kelompok_2;
                    }
                    // 8 = Kelompok III
                    else if($kelompok_id == 8){
                        $_uang_saku = $uang_saku->kelompok_3;
                    }
                    // 9 = Kelompok IV
                    else if($kelompok_id == 9){
                        $_uang_saku = $uang_saku->kelompok_4;
                    }
                    // 10 = Kelompok V
                    else if($kelompok_id == 10){
                        $_uang_saku = $uang_saku->kelompok_5;
                    }
                    // 11 = Kelompok VI
                    else if($kelompok_id == 11){
                        $_uang_saku = $uang_saku->kelompok_6;
                    }
                    // 1 = 500240 = BIAYA UANG SAKU = Project
                    // 2 = 500240 = BIAYA UANG SAKU = Support
                    $biaya = 2;
                    $satuan = 'IDR';
                    $bpd_biaya = ['id_info'=>$data->id, 'jenis'=>$jenis, 'id'=>$id, 'biaya'=>array($biaya), 'satuan'=>array($satuan), 'nominal'=>array($_uang_saku), 'jumlah'=>array($hari), 'is_approve'=>$is_approve];
                    $this->insertBpdBiaya($bpd_biaya);
                }
            }
            // ca_luar
            // reimburse_luar
            else if(in_array($luar_dalam, ['ca_luar', 'reimburse_luar'])){
                // dd($trxDetailInfo);
                foreach ($trxDetailInfo as $data){
                    $hari = Carbon::parse($data->tanggal_mulai)->diffInDays(Carbon::parse($data->tanggal_akhir))+1;
                    $where = ['id_negara'=>$data->tujuan1, 'id_biaya'=>1];
                    $uang_saku = BpdNonProyekBiayaLuarNegeri::where($where)->first();
                    // 6 = Kelompok I
                    if($kelompok_id == 6){
                        $_uang_saku = $uang_saku->kelompok_1;
                    }
                    // 7 = Kelompok II
                    else if($kelompok_id == 7){
                        $_uang_saku = $uang_saku->kelompok_2;
                    }
                    // 8 = Kelompok III
                    else if($kelompok_id == 8){
                        $_uang_saku = $uang_saku->kelompok_3;
                    }
                    // 9 = Kelompok IV
                    else if($kelompok_id == 9){
                        $_uang_saku = $uang_saku->kelompok_4;
                    }
                    // 10 = Kelompok V
                    else if($kelompok_id == 10){
                        $_uang_saku = $uang_saku->kelompok_5;
                    }
                    // 11 = Kelompok VI
                    else if($kelompok_id == 11){
                        $_uang_saku = $uang_saku->kelompok_6;
                    }
                    // 1 = 500240 = BIAYA UANG SAKU = Project
                    // 2 = 500240 = BIAYA UANG SAKU = Support
                    $biaya = 2;
                    $satuan = 'USD';
                    $bpd_biaya = ['id_info'=>$data->id, 'jenis'=>$jenis, 'id'=>$id, 'biaya'=>array($biaya), 'satuan'=>array($satuan), 'nominal'=>array($_uang_saku), 'jumlah'=>array($hari), 'is_approve'=>$is_approve];
                    $this->insertBpdBiaya($bpd_biaya);
                }
            }
        }
    }
    
    public function insertBpdBiaya($bpd)
    {
        for ($x = 0; $x < count($bpd['biaya']); $x++) {
            $data = new TrxBpdDetailBiaya;
            $data->id_trx = $bpd['id'];
            $data->jenis = $bpd['jenis'];
            $id_coa = $bpd['biaya'][$x];
            // start fungsi ambil coa_prefix uang saku mengikuti project_id
            // ambil project_id dari TrxBpdMaster
            $project_id = TrxBpdMaster::find($bpd['id'])->project_id;
            if(!empty($project_id)){
                $coa_prefix = BpdProyek::find($project_id)->coa_prefix;
                if(in_array($id_coa, [1, 2])){
                    if($coa_prefix == 5){
                        // 76 = 514004
                        $id_coa = 76;
                    }elseif($coa_prefix == 6){
                        // 89 = 622002
                        $id_coa = 89;
                    }
                }
            }
            // end
            $_coa = BpdCoa::find($id_coa);
            if(!empty($_coa)){
                $data->id_coa = $id_coa;
                $data->kode_coa = $_coa->account.'-'.$_coa->name;
                $data->biaya = $_coa->account.'-'.$_coa->name;
            }else{
                $data->biaya = $bpd['biaya'][$x];
            }
            // 
            $data->satuan = $bpd['satuan'][$x];
            // 
            if(!empty($bpd['jumlah'][$x])){
                $data->jumlah = $bpd['jumlah'][$x];
            }else{
                $data->jumlah = 1;
            }
            // 
            $data->nominal = $bpd['nominal'][$x];
            // 
            if(!empty($bpd['id_info'])){
                $data->id_info = $bpd['id_info'];
            }
            // 
            if(!empty($bpd['is_approve'])){
                $data->is_approve = $bpd['is_approve'];
            }
            // 
            if(($bpd['jenis']=='realisasi' || $bpd['jenis']=='realisasi_coa' || $bpd['jenis']=='reimburse') && !empty($bpd['lampiran'][$x])){
                $extension = $bpd['lampiran'][$x]->getClientOriginalExtension();
                $namafile = 'bpd_'.$bpd['id'].'_'.now()->getTimestampMs().'.'.$extension;
                $bpd['lampiran'][$x]->move('public/assets/bpd/', $namafile);
                $data->lampiran = $namafile;
            }
            // 
            if(!empty($bpd['is_ca_realisasi'][$x])){
                $data->is_ca = $bpd['is_ca_realisasi'][$x];
            }
            // 
            $data->save();
        }
    }
    
    public function updateBpdBiaya($bpd)
    {
        $data = TrxBpdDetailBiaya::find($bpd['id']);
        $id_coa = $bpd['biaya'];
        $_coa = BpdCoa::find($id_coa);
        if(!empty($_coa)){
            $data->id_coa = $id_coa;
            $data->kode_coa = $_coa->account.'-'.$_coa->name;
            $data->is_approve = $bpd['is_approve'];
            $data->update();
        }
    }
    
    public function updateBpdInfo($bpd)
    {
        $data = TrxBpdDetailInfo::find($bpd['id']);
        $data->is_approve = $bpd['is_approve'];
        $data->update();
    }
    
    public function updateBpdBiayaRealisasi($bpd)
    {
        // dd($bpd);
        $data = TrxBpdDetailBiaya::find($bpd['id']);
        $id_coa = $bpd['biaya'];
        $is_ca = $bpd['is_ca'];
        $_coa = BpdCoa::find($id_coa);
        if(!empty($_coa)){
            $data->id_coa = $id_coa;
            $data->is_ca = $is_ca;
            $data->kode_coa = $_coa->account.'-'.$_coa->name;
            $data->update();
        }
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
        $trx = ToDoList::where($where)->delete();
    }

    public function insertLog($id_trx, $id_status, $nik_pic, $keterangan)
    {
        $data = new TrxBpdLog;
        $data->id_trx = $id_trx;
        $data->id_status = $id_status;
        $data->nik_pic = $nik_pic;
        $data->keterangan = $keterangan;
        $data->save();
    }

    public function kirimEmail($data)
    {
        // dd($data);
        // start
        // parameter yang dipassing ke sendemail
        $pengaju_property = json_decode($data['pengaju_property'], true);
        $atasan_property = json_decode($data['atasan_property'], true);
        $mailData['title'] = '';
        if($data['luar_dalam'] == 'ca_dalam'){
            $mailData['title'] = 'Cash Advance Dalam Negeri';
        }else if($data['luar_dalam'] == 'ca_luar'){
            $mailData['title'] = 'Cash Advance Luar Negeri';
        }else if($data['luar_dalam'] == 'reimburse'){
            $mailData['title'] = 'Reimbursement Dalam Negeri';
        }else if($data['luar_dalam'] == 'reimburse_luar'){
            $mailData['title'] = 'Reimbursement Luar Negeri';
        }else if($data['luar_dalam'] == 'realisasi_ca_dalam'){
            $mailData['title'] = 'Realisasi Cash Advance Dalam Negeri';
        }else if($data['luar_dalam'] == 'realisasi_ca_luar'){
            $mailData['title'] = 'Realisasi Cash Advance Luar Negeri';
        }
        $mailData['filename'] = 'emails.bpd0';
        if(in_array($data['view'], ['approval_done', 'realisasi_done', 'reimburse_done'])){
            $mailData['filename'] = 'emails.bpd_done';
        }
        // foreach ($data['email_to'] as $email){
        for ($x = 0; $x < count($data['email_to']); $x++){
            $mailData['data'] = ['id' => $data['id'], 'luar_dalam' => $data['luar_dalam'], 'view' => $data['view'], 'title' => $mailData['title'], 'nama_to' => $data['email_to'][$x]['nama_to'], 'nik_to' => $data['email_to'][$x]['nik_to'], 'nama' => $pengaju_property['nama'].'/'.$pengaju_property['nik'], 'jabatan' => $pengaju_property['jabatan'], 'unit_kerja' => $pengaju_property['divisi'] . '/' . $pengaju_property['dep'] . '/' . $pengaju_property['sub_dep'], 'atasan' => $atasan_property['nama'].'/'.$atasan_property['nik'], 'link' => route('bpd.show', [$data['id'], 'view' => $data['view']])];
            // $mailData['to'] = $email;
            $mailData['to'] = $data['email_to'][$x]['email_to'];
            $todolist = ['id_trx' => $data['email_to'][$x]['id_trx'], 'nik' => $data['email_to'][$x]['nik_to'], 'id_status' => $data['email_to'][$x]['id_status'], 'pic' => $data['email_to'][$x]['pic']];
            // jika sudah diapprove oleh hco maka tidak dibuatkan todolist
            if(in_array($data['luar_dalam'], ['ca_dalam', 'ca_luar'])){
                if($data['view']<>'approval_done'){
                    $this->insertToDoList($todolist, $mailData);
                }
            }else if(in_array($data['luar_dalam'], ['realisasi_ca_dalam', 'realisasi_ca_luar'])){
                if($data['view']<>'realisasi_done'){
                    $this->insertToDoList($todolist, $mailData);
                }
            }else if(in_array($data['luar_dalam'], ['reimburse', 'reimburse_luar'])){
                if($data['view']<>'reimburse_done'){
                    $this->insertToDoList($todolist, $mailData);
                }
            }
            $this->sendEmail($mailData);
        }
        // finish
    }

    public function sendEmail($mailData)
    {
        $ccEmails = explode(',', MtrUtility::find(11)->informasi);
        Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
    }

    public function get_project($id=0)
    {
        // ambil project_id dan name
        $where = ['admin' => auth()->user()->nik];
        $id_proyek = BpdProyekAdmin::select('id_proyek')->where($where)->get()->toArray();
        $where = ['is_active' => 1];
        $project = BpdProyek::where($where)->whereIn('id', $id_proyek)->whereIn('group', ['Corporate'])->orderBy('project_id', 'asc')->get();
        // jika project maka pilihannya adalah Project dan Project IOH
        if($id==1){
            $project = BpdProyek::where($where)->whereIn('id', $id_proyek)->whereNotIn('group', ['Corporate'])->orderBy('project_id', 'asc')->get();
        }
        return response()->json(['project_id' => $project]);
    }

    public function get_coa($id=0)
    {
        // 1 = 500240 = BIAYA UANG SAKU = Project
        // 2 = 500240 = BIAYA UANG SAKU = Support
        // ambil account dan name
        // $account = BpdCoa::whereIn('divisi', ['0'])->whereNotIn('id', [1,2])->orderBy('account', 'asc')->get();
        // sudah tidak digunakan lagi
        // $account = BpdCoa::whereIn('divisi', ['0'])->where(['is_active'=>1])->orderBy('account', 'asc')->get();
        // if($id==1){
        //     // $account = BpdCoa::whereIn('divisi', ['Project'])->whereNotIn('id', [1])->orderBy('account', 'asc')->get();
        //     $account = BpdCoa::whereIn('divisi', ['Project'])->where(['is_active'=>1])->orderBy('account', 'asc')->get();
        // }else if($id==0){
        //     // $account = BpdCoa::whereIn('divisi', ['Support'])->whereNotIn('id', [2])->orderBy('account', 'asc')->get();
        //     $account = BpdCoa::whereIn('divisi', ['Support'])->where(['is_active'=>1])->orderBy('account', 'asc')->get();
        // }
        // merujuk ke "C:\alita\matta\data_master\2026_PROJECT ID vs ADMIN_Rev1.xlsx" sheet DAFTAR PROJECT ID
        $account = BpdCoa::where('account', 'like', BpdProyek::find($id)->coa_prefix.'%')->where(['is_active'=>1])->orderBy('account', 'asc')->get();
        return response()->json(['account' => $account]);
    }
}
