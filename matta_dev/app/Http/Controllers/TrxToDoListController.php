<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ToDoList;
use App\Models\Karyawan;
use App\Models\Perusahaan;
use App\Models\Divisi;
use App\Models\Departemen;
use App\Models\SubDepartemen;
use DB;
use App\Models\TrxPdp;
use App\Models\MtrUtility;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use App\Models\TrxSurveyPora;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TrxToDoListController extends Controller
{
    public function survey_mobile_post(Request $request)
    {
        // dd($request->all());
        // Your NIP has already been used for a pora survey
        $nik = $request->nik;
        $password = $request->password;
        $check = Karyawan::where(['nik'=>$nik])->orderBy('id', 'desc')->first();
        if(Hash::check($password, $check->password)){
            if(TrxSurveyPora::where(['nik'=>$nik])->exists()){
                return back()->with('msg_error', 'NIP anda sudah pernah digunakan untuk input survey pora')->withInput();
            }else{
                $trx = new TrxSurveyPora;
                $trx->nik = $nik;
                $trx->nik_property = $this->getNikProperty($nik);
                $trx->opsi1 = json_encode($request->input('opsi1'));;
                $trx->opsi2 = json_encode($request->input('opsi2'));;
                $trx->opsi3 = json_encode($request->input('opsi3'));;
                $trx->save();
                return back()->with('msg_success', 'Data berhasil disimpan.');
            }
            
        }else{
            return back()->with('msg_error', 'NIP dan atau password anda salah.<br/>Agar dapat dicek kembali!')->withInput();
        }
        // $no = 0;
        // return view( 'todolist.survey_pora_mobile', compact('id'));
    }

    public function survey_mobile(Request $request, $id)
    {
        return view( 'todolist.survey_pora_mobile', compact('id'));
    }

    public function survey_result($id)
    {
        // http://localhost/matta/survey_pora/result/0
        // $trx = TrxSurveyPora::all();
        // $n_futsal = 0;
        // $n_voli = 0;
        // $n_basket = 0;
        // $n_badminton = 0;
        // $n_tenis = 0;
        // $n_renang = 0;
        // $n_ml = 0;
        // $n_fifa = 0;
        // foreach($trx as $data){
        //     $opsi1 = json_decode($data['opsi1'], true) ?? [];
        //     $opsi2 = json_decode($data['opsi2'], true) ?? [];
        //     $opsi3 = json_decode($data['opsi3'], true) ?? [];
        //     $n_futsal += count(array_filter($opsi1, fn($item) => $item === 'futsal'));
        //     $n_voli += count(array_filter($opsi1, fn($item) => $item === 'voli'));
        //     $n_basket += count(array_filter($opsi1, fn($item) => $item === 'basket'));
        //     $n_badminton += count(array_filter($opsi2, fn($item) => $item === 'badminton'));
        //     $n_tenis += count(array_filter($opsi2, fn($item) => $item === 'tenis'));
        //     $n_renang += count(array_filter($opsi2, fn($item) => $item === 'renang'));
        //     $n_ml += count(array_filter($opsi3, fn($item) => $item === 'ml'));
        //     $n_fifa += count(array_filter($opsi3, fn($item) => $item === 'fifa'));
        // }
        // return response()->json([
        //     'success'=>true,
        //     'Cabor GROUP'=>['n_futsal'=>$n_futsal,'n_voli'=>$n_voli,'n_basket'=>$n_basket],
        //     'Cabor INDIVIDU / DUO'=>['n_badminton'=>$n_badminton,'n_tenis'=>$n_tenis,'n_renang'=>$n_renang],
        //     'Cabor E-SPORT'=>['n_ml'=>$n_ml,'n_fifa'=>$n_fifa],
        // ]);
    }
    
    public function survey_pora(Request $request)
    {
        // dd($request->all());
        $nik = auth()->user()->nik;
        $trx = new TrxSurveyPora;
        $trx->nik = $nik;
        $trx->nik_property = $this->getNikProperty($nik);
        $trx->opsi1 = json_encode($request->input('opsi1'));;
        $trx->opsi2 = json_encode($request->input('opsi2'));;
        $trx->opsi3 = json_encode($request->input('opsi3'));;
        $trx->save();
        return redirect()->to($request->back_url)->with('msg', 'Data is saved successfully.');
    }

    public function detail(Request $request)
    {
        $nik = (new OpenDataController)->decryptWithKey($request->nik);
        $title = (new OpenDataController)->decryptWithKey($request->title);
        $where = ['nik' => $nik, 'title' => $title];
        $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
        return view('todolist.recap_detail', compact('trx'));
    }

    public function recap(Request $request)
    {
        // dd($request->all());
        $title = ToDoList::groupBy('title')->orderBy('title', 'asc')->get();
        // dd($title);
        // 0=NA
        // 3=SMS Project
        $perusahaan = Perusahaan::whereNotIn('id', [0,3])->orderBy('nama', 'asc')->get();
        $divisi = [];
        $departemen = [];
        $sub_departemen = [];
        //
        $arr_title = [];
        $arr_perusahaan = [];
        $arr_divisi = [];
        $arr_departemen = [];
        $arr_sub_departemen = [];
        // 
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $trx = ToDoList::select('*', DB::raw('COUNT(nik) AS jumlah'))->groupBy('nik', 'title')->orderBy('jumlah', 'desc')->get();
        if(Gate::allows('hc_nkp')){
            $trx = ToDoList::select('*', DB::raw('COUNT(nik) AS jumlah'))->whereIn('nik',$arr_nik_nkp)->groupBy('nik', 'title')->orderBy('jumlah', 'desc')->get();
        }
        // start filter
        if ($request->filter == true) {
            $_title = $request->input('title', []);
            $_perusahaan = $request->input('kode_perusahaan', []);
            $_divisi = $request->input('kode_divisi', []);
            $_departemen = $request->input('kode_departemen', []);
            $_sub_departemen = $request->input('kode_sub_departemen', []);
            if (count($_title) > 0) {
                $arr_title = $_title;
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
            if (count($_sub_departemen) > 0) {
                $arr_sub_departemen = $_sub_departemen;
            }
            $divisi = Divisi::whereIn('kode_perusahaan', $arr_perusahaan)->get();
            $departemen = Departemen::whereIn('kode_divisi', $arr_divisi)->get();
            $sub_departemen = SubDepartemen::whereIn('kode_departemen', $arr_departemen)->get();
            $karyawan = Karyawan::whereIn('kode_sub_departemen', $arr_sub_departemen)->whereNotIn('nik', ['Hco.all', 'IT.BM'])->get();
            $_karyawan = [];
            foreach($karyawan as $data){
                array_push($_karyawan, $data->nik);
            }
            $trx = ToDoList::select('*', DB::raw('COUNT(nik) AS jumlah'))->whereIn('nik', $_karyawan)->whereIn('title', $arr_title)->groupBy('nik', 'title')->orderBy('jumlah', 'desc')->get();
            if(Gate::allows('hc_nkp')){
                $trx = ToDoList::select('*', DB::raw('COUNT(nik) AS jumlah'))->whereIn('nik',$arr_nik_nkp)->whereIn('nik', $_karyawan)->whereIn('title', $arr_title)->groupBy('nik', 'title')->orderBy('jumlah', 'desc')->get();
            }
        }
        return view( 'todolist.recap', compact('trx', 'perusahaan', 'divisi', 'departemen', 'sub_departemen', 'title', 'arr_perusahaan', 'arr_divisi', 'arr_departemen', 'arr_sub_departemen', 'arr_title'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $where = ['nik' => auth()->user()->nik];
        $is_setuju = TrxPdp::where($where)->orderBy('id', 'desc')->first();
        // $pdp_next = Carbon::parse($is_setuju->created_at)->addYear(MtrUtility::find(41)->informasi);
        // dd($is_setuju->created_at, $pdp_next, Carbon::now()->gt(Carbon::parse($is_setuju->created_at)->copy()->addYear(MtrUtility::find(41)->informasi)));
        $check_is_pdp = true;
        if(!empty($is_setuju)){
            $check_is_pdp = Carbon::now()->gt(Carbon::parse($is_setuju->created_at)->copy()->addYear(MtrUtility::find(41)->informasi));
        }
        // 40=PDP di dalam
        $is_pdp = ['is_pdp'=>!$check_is_pdp, 'is_aktif'=> (MtrUtility::find(40)->informasi==1)];
        $is_survey_pora = TrxSurveyPora::where(['nik'=>auth()->user()->nik])->exists();
        // dd($is_pdp);
        // $arr = [];
        // $trx = DB::select("SELECT COUNT(kode_sub_departemen) AS jumlah,kode_sub_departemen FROM mtr_tbl_karyawan WHERE is_aktif=1 AND wg_id=1 AND nik NOT IN ('Hco.all', 'IT.BM') GROUP BY kode_sub_departemen ORDER BY jumlah");
        // foreach ($trx as $data) {
        //     // $_arr = [];
        //     $where = ['kode_sub_departemen'=>$data->kode_sub_departemen, 'is_aktif'=>1, 'wg_id'=>1];
        //     $karyawan = Karyawan::where($where)->whereNotIn('nik', ['Hco.all', 'IT.BM'])->orderBy('nama', 'asc')->get();
        //     array_push($arr, ['jumlah'=>$data->jumlah, 'karyawan'=>$karyawan]);
        //     // array_push($arr, $_arr);
        // }
        // // dd($arr);
        // return view('todolist.karyawan_detail', compact('arr'));
        //
        // kebutuhan0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.kebutuhan0'];
        $trxKebutuhan = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // cuti0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.cuti0'];
        $trxCuti = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // izin0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.izin0'];
        $trxIzin = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // nonaktif0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.nonaktif0'];
        $trxNonAktif = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // pelatihan0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.pelatihan0'];
        $trxPelatihan = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // penilaian0 dan penilaian0_outsourcing
        // $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.penilaian0'];
        $where = ['nik' => auth()->user()->nik];
        $trxPenilaian = ToDoList::where($where)->whereIn('filename', ['emails.penilaian0', 'emails.penilaian0_outsourcing'])->orderBy('id', 'desc')->get();
        // reimburse0
        $where = ['nik' => auth()->user()->nik];
        $trxReimburse = ToDoList::where($where)->whereIn('filename', ['emails.reimburse0', 'emails.reimburse_done'])->orderBy('id', 'desc')->get();
        // rekomendasi0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.rekomendasi0'];
        $trxRekomendasi = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // lembur0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.lembur0'];
        $trxOvertime = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // bpd0
        // $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.bpd0'];
        $where = ['nik' => auth()->user()->nik];
        $trxBpd = ToDoList::where($where)->whereIn('filename', ['emails.bpd0', 'emails.bpd_done'])->orderBy('id', 'desc')->get();
        // sakit0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.sakit0'];
        $trxSakit = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // recruitment0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.recruitment0'];
        $recruitment = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // penyesuaian_upah0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.penyesuaian_upah0'];
        $notif_letter = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // karyawan_bulanan0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.karyawan_bulanan0'];
        $karyawan_bulanan = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // 
        return view('todolist.index', compact('trxKebutuhan','trxCuti','trxIzin','trxNonAktif','trxPelatihan','trxPenilaian','trxReimburse','trxRekomendasi','trxOvertime','trxBpd','trxSakit','recruitment','is_pdp','notif_letter','is_survey_pora', 'karyawan_bulanan'));
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
    public function show(Request $request, $id)
    {
        // start
        // local http://localhost/matta/todolist/nasio
        // live https://matta-reborn.alita.id/matta/todolist/nasio
        // if($id == 'nasio'){
        //     // Estu Wiharto
        //     $nik = '03032512019';
        //     $karyawan = Karyawan::where(['nik'=>$nik])->first();
        //     $trx = DB::select("SELECT * FROM `trx_tbl_todolist` WHERE `pic`='hco' AND `title`='Formulir Penilaian Karyawan - Outsourcing' AND `filename`='emails.penilaian0_outsourcing' AND SUBSTRING_INDEX(`nama`, '/', -1) IN (SELECT `nik` FROM `mtr_tbl_karyawan` WHERE `is_aktif`=1 AND `kode_perusahaan`=5) GROUP BY `nama`");
        //     foreach($trx as $_trx){
        //         $data = New ToDoList;
        //         $data->id_trx = $_trx->id_trx;
        //         $data->id_status = $_trx->id_status;
        //         $data->nik = $nik;
        //         $data->pic = $_trx->pic;
        //         $data->title = $_trx->title;
        //         $data->nama = $_trx->nama;
        //         $data->jabatan = $_trx->jabatan;
        //         $data->unit_kerja = $_trx->unit_kerja;
        //         $data->link = $_trx->link;
        //         $data->filename = $_trx->filename;
        //         $data->email = $karyawan->email;
        //         $data->save();
        //     }
        // }
        // end
        $no= 0;
        $filename = $request->filename;
        // kebutuhan0
        if($filename == 'emails.kebutuhan0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.kebutuhan0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // penilaian0
        else if($filename == 'emails.penilaian0'){
            // $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.penilaian0'];
            $where = ['nik' => auth()->user()->nik];
            $trx = ToDoList::where($where)->whereIn('filename', ['emails.penilaian0', 'emails.penilaian0_outsourcing'])->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // rekomendasi0
        else if($filename == 'emails.rekomendasi0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.rekomendasi0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // nonaktif0
        else if($filename == 'emails.nonaktif0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.nonaktif0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // reimburse0
        else if($filename == 'emails.reimburse0'){
            $where = ['nik' => auth()->user()->nik];
            $trx = ToDoList::where($where)->whereIn('filename', ['emails.reimburse0', 'emails.reimburse_done'])->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // cuti0
        else if($filename == 'emails.cuti0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.cuti0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // izin0
        else if($filename == 'emails.izin0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.izin0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // pelatihan0
        else if($filename == 'emails.pelatihan0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.pelatihan0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // lembur0
        else if($filename == 'emails.lembur0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.lembur0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // bpd0
        else if($filename == 'emails.bpd0'){
            // $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.bpd0'];
            $where = ['nik' => auth()->user()->nik];
            $trx = ToDoList::where($where)->whereIn('filename', ['emails.bpd0', 'emails.bpd_done'])->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // sakit0
        else if($filename == 'emails.sakit0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.sakit0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // recruitment0
        else if($filename == 'emails.recruitment0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.recruitment0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // penyesuaian_upah0
        else if($filename == 'emails.penyesuaian_upah0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.penyesuaian_upah0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // karyawan_bulanan0
        else if($filename == 'emails.karyawan_bulanan0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.karyawan_bulanan0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
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

    public function getNikProperty($nik)
    {
        $where = ['nik' => $nik, 'is_aktif' => 1];
        $data = Karyawan::select('mtr_tbl_karyawan.nik', 'mtr_tbl_karyawan.nama', 'mtr_tbl_karyawan.email', 'mtr_tbl_karyawan.telepon1', 'mtr_tbl_perusahaan.nama as perusahaan', 'mtr_tbl_divisi.nama as divisi', 'mtr_tbl_departemen.nama as dep', 'mtr_tbl_sub_departemen.nama as sub_dep', 'mtr_tbl_level.level as level', 'mtr_tbl_jabatan.jabatan as jabatan', 'mtr_tbl_level.id as id_level', 'mtr_tbl_sub_departemen.id as id_sub_departemen')
            ->leftJoin('mtr_tbl_perusahaan', 'mtr_tbl_perusahaan.id', '=', 'mtr_tbl_karyawan.kode_perusahaan')
            ->leftJoin('mtr_tbl_divisi', 'mtr_tbl_divisi.id', '=', 'mtr_tbl_karyawan.kode_divisi')
            ->leftJoin('mtr_tbl_departemen', 'mtr_tbl_departemen.id', '=', 'mtr_tbl_karyawan.kode_departemen')
            ->leftJoin('mtr_tbl_sub_departemen', 'mtr_tbl_sub_departemen.id', '=', 'mtr_tbl_karyawan.kode_sub_departemen')
            ->leftJoin('mtr_tbl_level', 'mtr_tbl_level.id', '=', 'mtr_tbl_karyawan.kode_level')
            ->leftJoin('mtr_tbl_jabatan', 'mtr_tbl_jabatan.id', '=', 'mtr_tbl_karyawan.kode_jabatan')
            ->where($where)->first();
        return json_encode($data);
    }
}
