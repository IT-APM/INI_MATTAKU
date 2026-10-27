<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Models\TrxPelatihanSertifikat;
use App\Models\TrxPelatihanSertifikatKadaluarsa;
use App\Models\PelatihanJenis;
use App\Models\Karyawan;

use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class TrxPelatihanSertifikatController extends Controller
{

    public function kadaluarsa(Request $request, $id)
    {
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        if (!auth()->check()) {
            session(['url.intended' => url()->full()]);
            return redirect()->route('login-one');
        }
        $no = 0;
        $trx = TrxPelatihanSertifikatKadaluarsa::orderBy('nama', 'asc')->get();
        if(Gate::allows('hc_nkp')){
            $trx = TrxPelatihanSertifikatKadaluarsa::whereIn('nik',$arr_nik_nkp)->orderBy('nama', 'asc')->get();
        }
        $jenis = PelatihanJenis::orderBy('jenis', 'asc')->get();
        $aktif_non = collect([
            '1' => 'Aktif',
            '0' => 'Non Aktif',
        ]);
        $int_ext = collect([
            'Eksternal' => 'Eksternal',
            'Internal' => 'Internal',
        ]);
        $kta = collect([
            'Ada' => 'Ada',
            'Tidak Ada' => 'Tidak Ada',
        ]);
        $arr_jenis = [];
        $arr_aktif_non = [];
        $arr_int_ext = [];
        $arr_kta = [];
        // start filter
        if($request->filter == true){
            $_aktif_non = $request->input('aktif_non', []);
            $_jenis = $request->input('jenis', []);
            $_int_ext = $request->input('int_ext', []);
            $_kta = $request->input('kta', []);
            // dd($_kta);
            if(count($_aktif_non) > 0){
                $arr_aktif_non = $_aktif_non;
            }
            // else{
            //     $arr_aktif_non = array();
            // }
            if(count($_jenis) > 0){
                $arr_jenis = $_jenis;
            }
            // else{
            //     $arr_jenis = array();
            // }
            if(count($_int_ext) > 0){
                $arr_int_ext = $_int_ext;
            }
            // else{
            //     $arr_int_ext = array();
            // }
            if(count($_kta) > 0){
                $arr_kta = $_kta;
            }
            // else{
            //     $arr_kta = array();
            // }
            $arr_nik = Karyawan::where(['is_aktif'=>1])->pluck('nik')->toArray();
            $trx = TrxPelatihanSertifikatKadaluarsa::whereIn('nik', [])->whereIn('jenis', $arr_jenis)->whereIn('int_ext', $arr_int_ext)->whereIn('kta', $arr_kta)->orderBy('nama', 'asc')->get();
            if(in_array(1, $_aktif_non) && in_array(0, $_aktif_non)){
                $trx = TrxPelatihanSertifikatKadaluarsa::whereIn('jenis', $arr_jenis)->whereIn('int_ext', $arr_int_ext)->whereIn('kta', $arr_kta)->orderBy('nama', 'asc')->get();
            }else if(in_array(1, $_aktif_non)){
                $trx = TrxPelatihanSertifikatKadaluarsa::whereIn('nik', $arr_nik)->whereIn('jenis', $arr_jenis)->whereIn('int_ext', $arr_int_ext)->whereIn('kta', $arr_kta)->orderBy('nama', 'asc')->get();
            }else if(in_array(0, $_aktif_non)){
                $trx = TrxPelatihanSertifikatKadaluarsa::whereNotIn('nik', $arr_nik)->whereIn('jenis', $arr_jenis)->whereIn('int_ext', $arr_int_ext)->whereIn('kta', $arr_kta)->orderBy('nama', 'asc')->get();
            }
            if(Gate::allows('hc_nkp')){
                $trx = TrxPelatihanSertifikatKadaluarsa::whereIn('nik', [])->whereIn('nik',$arr_nik_nkp)->whereIn('jenis', $arr_jenis)->whereIn('int_ext', $arr_int_ext)->whereIn('kta', $arr_kta)->orderBy('nama', 'asc')->get();
                if(in_array(1, $_aktif_non) && in_array(0, $_aktif_non)){
                    $trx = TrxPelatihanSertifikatKadaluarsa::whereIn('nik',$arr_nik_nkp)->whereIn('jenis', $arr_jenis)->whereIn('int_ext', $arr_int_ext)->whereIn('kta', $arr_kta)->orderBy('nama', 'asc')->get();
                }else if(in_array(1, $_aktif_non)){
                    $trx = TrxPelatihanSertifikatKadaluarsa::whereIn('nik', $arr_nik)->whereIn('nik',$arr_nik_nkp)->whereIn('jenis', $arr_jenis)->whereIn('int_ext', $arr_int_ext)->whereIn('kta', $arr_kta)->orderBy('nama', 'asc')->get();
                }else if(in_array(0, $_aktif_non)){
                    $trx = TrxPelatihanSertifikatKadaluarsa::whereNotIn('nik', $arr_nik)->whereIn('nik',$arr_nik_nkp)->whereIn('jenis', $arr_jenis)->whereIn('int_ext', $arr_int_ext)->whereIn('kta', $arr_kta)->orderBy('nama', 'asc')->get();
                }
            }
        }else{
            $arr_aktif_non = array();
            $arr_int_ext = array();
            $arr_jenis = array();
            $arr_kta = array();
        }
        // end filter
        return view('formulir.pelatihan.index_sertifikat_kadaluarsa', compact('trx', 'no', 'jenis', 'aktif_non', 'int_ext', 'kta', 'arr_jenis', 'arr_aktif_non', 'arr_int_ext', 'arr_kta'));
    }

    public function scheduler(Request $request, $id)
    {
        // dd($request->all(), $id);
        // 2 bulan = 60
        // 1 bulan = 30
        // 2 minggu = 14
        $data = [];
        $check = Carbon::today()->addDays($id);
        $trx = TrxPelatihanSertifikat::whereBetween('tanggal_kadaluarsa', [Carbon::today(), $check])->orderBy('tanggal_kadaluarsa', 'desc')->get();
        foreach($trx as $_trx){
            $check = Karyawan::where(['nik'=>$_trx->nik, 'is_aktif'=>1])->exists();
            if($check){
                array_push($data, TrxPelatihanSertifikat::find($_trx->id));
                if(empty(TrxPelatihanSertifikatKadaluarsa::find($_trx->id))){
                    TrxPelatihanSertifikatKadaluarsa::create($_trx->toArray());
                }
            }
        }
        // dd($data);
    }

    public function upload_file(Request $request)
    {
        if($request->hasFile('template')){
            $request->validate([
                'template'=>'required|file|mimes:xls,xlsx|max:2048',
            ]);
            $path = $request->file('template')->getPathname();
            $data = Excel::toArray([], $path);
            $data0 = $data[0];
            // Formulir (url)
            $_1 = array_search(1, $data0[0]);
            // Nama
            $_2 = array_search(2, $data0[0]);
            // NIP
            $_3 = array_search(3, $data0[0]);
            // Jenis Pelatihan
            // Pelatihan
            // Sertifikasi Keahlian
            // Seminar
            $_4 = array_search(4, $data0[0]);
            // Mode Pelatihan
            // Eksternal
            // Internal
            $_5 = array_search(5, $data0[0]);
            // Penerbit
            // LEMBAGA
            // KEMENAKER
            // BNSP
            // LEMBAGA & BNSP
            // LAINNYA
            $_6 = array_search(6, $data0[0]);
            // Kartu Tanda Anggota
            // Ada
            // Tidak Ada
            $_7 = array_search(7, $data0[0]);
            // Judul Pelatihan
            $_8 = array_search(8, $data0[0]);
            // Lembaga
            $_9 = array_search(9, $data0[0]);
            // Biaya (double)
            $_10 = array_search(10, $data0[0]);
            // Tanggal Mulai (dd-mm-yyyy)
            $_11 = array_search(11, $data0[0]);
            // Tanggal Akhir (dd-mm-yyyy)
            $_12 = array_search(12, $data0[0]);
            // No Sertifikat
            $_13 = array_search(13, $data0[0]);
            // Tanggal Sertifikat (dd-mm-yyyy)
            $_14 = array_search(14, $data0[0]);
            // Tanggal Kedaluwarsa (dd-mm-yyyy)
            $_15 = array_search(15, $data0[0]);
            // Sertifikat (url)
            $_16 = array_search(16, $data0[0]);
            // No Kartu Lisensi
            $_17 = array_search(17, $data0[0]);
            // No Kartu Anggota
            $_18 = array_search(18, $data0[0]);
            for ($x = 2; $x < count($data0); $x++) {
                $nik = $data0[$x][$_3];
                $trx = New TrxPelatihanSertifikat;
                $trx->formulir_link = $data0[$x][$_1];
                $trx->nama = $data0[$x][$_2];
                $trx->nik = $nik;
                if(!empty($nik) && Karyawan::where(['nik'=>$nik])->exists()){
                    $trx->nik_property = $this->getNikProperty($nik);
                }
                $trx->jenis = $data0[$x][$_4];
                $trx->int_ext = $data0[$x][$_5];
                $trx->penerbit = $data0[$x][$_6];
                $trx->kta = $data0[$x][$_7];
                $trx->judul = $data0[$x][$_8];
                $trx->lembaga = $data0[$x][$_9];
                $trx->biaya = $data0[$x][$_10];
                $trx->tanggal_mulai = Carbon::createFromFormat('d-m-Y', $data0[$x][$_11])->format('Y-m-d');
                $trx->tanggal_akhir = Carbon::createFromFormat('d-m-Y', $data0[$x][$_12])->format('Y-m-d');
                $trx->nomor_sertifikat = $data0[$x][$_13];
                $trx->tanggal_sertifikat = Carbon::createFromFormat('d-m-Y', $data0[$x][$_14])->format('Y-m-d');
                $trx->tanggal_kadaluarsa = Carbon::createFromFormat('d-m-Y', $data0[$x][$_15])->format('Y-m-d');
                $trx->sertifikat_link = $data0[$x][$_16];
                $trx->no_kartu_lisensi = $data0[$x][$_17];
                $trx->no_kartu_anggota = $data0[$x][$_18];
                $trx->save();
            }
            return redirect('pelatihan_sertifikat')->with('msg', 'Data is saved successfully.');
        }
        // dd($request->all());
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
        if (!auth()->check()) {
            session(['url.intended' => url()->full()]);
            return redirect()->route('login-one');
        }
        $no = 0;
        $trx = TrxPelatihanSertifikat::orderBy('nama', 'asc')->get();
        if(Gate::allows('hc_nkp')){
            $trx = TrxPelatihanSertifikat::whereIn('nik',$arr_nik_nkp)->orderBy('nama', 'asc')->get();
        }
        $jenis = PelatihanJenis::orderBy('jenis', 'asc')->get();
        $aktif_non = collect([
            '1' => 'Aktif',
            '0' => 'Non Aktif',
        ]);
        $int_ext = collect([
            'Eksternal' => 'Eksternal',
            'Internal' => 'Internal',
        ]);
        $kta = collect([
            'Ada' => 'Ada',
            'Tidak Ada' => 'Tidak Ada',
        ]);
        $arr_jenis = [];
        $arr_aktif_non = [];
        $arr_int_ext = [];
        $arr_kta = [];
        // start filter
        if($request->filter == true){
            $_aktif_non = $request->input('aktif_non', []);
            $_jenis = $request->input('jenis', []);
            $_int_ext = $request->input('int_ext', []);
            $_kta = $request->input('kta', []);
            // dd($_kta);
            if(count($_aktif_non) > 0){
                $arr_aktif_non = $_aktif_non;
            }
            // else{
            //     $arr_aktif_non = array();
            // }
            if(count($_jenis) > 0){
                $arr_jenis = $_jenis;
            }
            // else{
            //     $arr_jenis = array();
            // }
            if(count($_int_ext) > 0){
                $arr_int_ext = $_int_ext;
            }
            // else{
            //     $arr_int_ext = array();
            // }
            if(count($_kta) > 0){
                $arr_kta = $_kta;
            }
            // else{
            //     $arr_kta = array();
            // }
            $arr_nik = Karyawan::where(['is_aktif'=>1])->pluck('nik')->toArray();
            $trx = TrxPelatihanSertifikat::whereIn('nik', [])->whereIn('jenis', $arr_jenis)->whereIn('int_ext', $arr_int_ext)->whereIn('kta', $arr_kta)->orderBy('nama', 'asc')->get();
            if(in_array(1, $_aktif_non) && in_array(0, $_aktif_non)){
                $trx = TrxPelatihanSertifikat::whereIn('jenis', $arr_jenis)->whereIn('int_ext', $arr_int_ext)->whereIn('kta', $arr_kta)->orderBy('nama', 'asc')->get();
            }else if(in_array(1, $_aktif_non)){
                $trx = TrxPelatihanSertifikat::whereIn('nik', $arr_nik)->whereIn('jenis', $arr_jenis)->whereIn('int_ext', $arr_int_ext)->whereIn('kta', $arr_kta)->orderBy('nama', 'asc')->get();
            }else if(in_array(0, $_aktif_non)){
                $trx = TrxPelatihanSertifikat::whereNotIn('nik', $arr_nik)->whereIn('jenis', $arr_jenis)->whereIn('int_ext', $arr_int_ext)->whereIn('kta', $arr_kta)->orderBy('nama', 'asc')->get();
            }
            if(Gate::allows('hc_nkp')){
                $trx = TrxPelatihanSertifikat::whereIn('nik', [])->whereIn('nik',$arr_nik_nkp)->whereIn('jenis', $arr_jenis)->whereIn('int_ext', $arr_int_ext)->whereIn('kta', $arr_kta)->orderBy('nama', 'asc')->get();
                if(in_array(1, $_aktif_non) && in_array(0, $_aktif_non)){
                    $trx = TrxPelatihanSertifikat::whereIn('nik',$arr_nik_nkp)->whereIn('jenis', $arr_jenis)->whereIn('int_ext', $arr_int_ext)->whereIn('kta', $arr_kta)->orderBy('nama', 'asc')->get();
                }else if(in_array(1, $_aktif_non)){
                    $trx = TrxPelatihanSertifikat::whereIn('nik', $arr_nik)->whereIn('nik',$arr_nik_nkp)->whereIn('jenis', $arr_jenis)->whereIn('int_ext', $arr_int_ext)->whereIn('kta', $arr_kta)->orderBy('nama', 'asc')->get();
                }else if(in_array(0, $_aktif_non)){
                    $trx = TrxPelatihanSertifikat::whereNotIn('nik', $arr_nik)->whereIn('nik',$arr_nik_nkp)->whereIn('jenis', $arr_jenis)->whereIn('int_ext', $arr_int_ext)->whereIn('kta', $arr_kta)->orderBy('nama', 'asc')->get();
                }
            }
        }else{
            $arr_aktif_non = array();
            $arr_int_ext = array();
            $arr_jenis = array();
            $arr_kta = array();
        }
        // end filter
        return view('formulir.pelatihan.index_sertifikat', compact('trx', 'no', 'jenis', 'aktif_non', 'int_ext', 'kta', 'arr_jenis', 'arr_aktif_non', 'arr_int_ext', 'arr_kta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $status = 'create';
        $jenis = PelatihanJenis::orderBy('jenis', 'asc')->get();
        $int_ext = collect([
            'Eksternal' => 'Eksternal',
            'Internal' => 'Internal',
        ]);
        $kta = collect([
            'Ada' => 'Ada',
            'Tidak Ada' => 'Tidak Ada',
        ]);
        $penerbit = TrxPelatihanSertifikat::select('penerbit')->distinct()->orderBy('penerbit', 'asc')->get();
        return view('formulir.pelatihan.upsert_sertifikat', compact('status', 'jenis', 'int_ext', 'kta', 'penerbit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $nik = $request->nik;
        $trx = New TrxPelatihanSertifikat;
        $trx->formulir_link = $request->formulir_link;
        $trx->sertifikat_link = $request->sertifikat_link;
        $trx->nama = $request->nama;
        $trx->nik = $nik;
        if(!empty($nik) && Karyawan::where(['nik'=>$nik])->exists()){
            $trx->nik_property = $this->getNikProperty($nik);
        }
        $trx->jenis = $request->jenis;
        $trx->int_ext = $request->int_ext;
        $trx->penerbit = $request->penerbit;
        $trx->kta = $request->kta;
        $trx->judul = $request->judul;
        $trx->lembaga = $request->lembaga;
        $trx->biaya = str_replace(',', '', $request->biaya);
        $trx->tanggal_mulai = $request->tanggal_mulai;
        $trx->tanggal_akhir = $request->tanggal_akhir;
        $trx->nomor_sertifikat = $request->nomor_sertifikat;
        $trx->no_kartu_lisensi = $request->no_kartu_lisensi;
        $trx->no_kartu_anggota = $request->no_kartu_anggota;
        $trx->tanggal_sertifikat = $request->tanggal_sertifikat;
        $trx->tanggal_kadaluarsa = $request->tanggal_kadaluarsa;
        $trx->save();
        return redirect('pelatihan_sertifikat')->with('msg', 'Data is saved successfully.');
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
        $status = 'edit';
        $id = (new OpenDataController)->decryptWithKey($id);
        $trx = TrxPelatihanSertifikat::find($id);
        $jenis = PelatihanJenis::orderBy('jenis', 'asc')->get();
        $int_ext = collect([
            'Eksternal' => 'Eksternal',
            'Internal' => 'Internal',
        ]);
        $kta = collect([
            'Ada' => 'Ada',
            'Tidak Ada' => 'Tidak Ada',
        ]);
        $penerbit = TrxPelatihanSertifikat::select('penerbit')->distinct()->orderBy('penerbit', 'asc')->get();
        return view('formulir.pelatihan.upsert_sertifikat', compact('trx', 'status', 'jenis', 'int_ext', 'kta', 'penerbit'));
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
        $trx = TrxPelatihanSertifikat::find($id);
        $trx->nama = $request->nama;
        // $trx->nik = $request->nik;
        $trx->jenis = $request->jenis;
        $trx->int_ext = $request->int_ext;
        $trx->penerbit = $request->penerbit;
        $trx->kta = $request->kta;
        $trx->judul = $request->judul;
        $trx->lembaga = $request->lembaga;
        $trx->nomor_sertifikat = $request->nomor_sertifikat;
        $trx->no_kartu_lisensi = $request->no_kartu_lisensi;
        $trx->no_kartu_anggota = $request->no_kartu_anggota;
        $trx->biaya = str_replace(',', '', $request->biaya);
        $trx->tanggal_mulai = $request->tanggal_mulai;
        $trx->tanggal_akhir = $request->tanggal_akhir;
        $trx->tanggal_sertifikat = $request->tanggal_sertifikat;
        $trx->tanggal_kadaluarsa = $request->tanggal_kadaluarsa;
        $trx->sertifikat_link = $request->sertifikat_link;
        $trx->formulir_link = $request->formulir_link;
        $trx->update();
        return redirect('pelatihan_sertifikat')->with('msg', 'Data is saved successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // dd($id);
        $trx = TrxPelatihanSertifikat::find($id);
        $trx->delete();
        return redirect('pelatihan_sertifikat')->with('msg', 'Data is deleted successfully.');
    }

    public function getNikProperty($nik)
    {
        $where = ['nik' => $nik];
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
