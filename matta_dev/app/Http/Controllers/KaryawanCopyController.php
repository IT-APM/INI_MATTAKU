<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Karyawan;
use App\Models\KaryawanAnak;
use App\Models\KaryawanPelatihan;
use App\Models\KaryawanPendidikan;
use App\Models\KaryawanSertifikasi;
use App\Models\User;
use App\Models\UsersPwd;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class KaryawanCopyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        return view( 'master.karyawan.copy.insert', compact( 'karyawan' ) );
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
        // $data = DB::table('mtr_tbl_karyawan_nik_2_nkp')->select('nik', 'nik_nkp')->get();
        // foreach($data as $_data){
        // $nik_karyawan_baru = $_data->nik_nkp;
        // $nik_karyawan_lama = $_data->nik;
        // File::deleteDirectory(public_path('assets/images/matta/karyawan/'.$nik_karyawan_baru));
        // ambil data karyawan lama
        $nik_karyawan_baru = $request->nik_karyawan_baru;
        $nik_karyawan_lama = $request->nik_karyawan_lama;
        $_karyawan = Karyawan::where('nik', $nik_karyawan_lama)->first();
        $karyawan = New Karyawan;
        $karyawan->nik = $nik_karyawan_baru;
        $karyawan->password = $_karyawan->password;
        $karyawan->nama = $_karyawan->nama;
        $karyawan->tempat_lahir = $_karyawan->tempat_lahir;
        $karyawan->tanggal_lahir = $_karyawan->tanggal_lahir;
        $karyawan->no_ktp = $_karyawan->no_ktp;
        $karyawan->no_npwp = $_karyawan->no_npwp;
        $karyawan->telepon_darurat = $_karyawan->telepon_darurat;
        $karyawan->alamat_domisili = $_karyawan->alamat_domisili;
        $karyawan->alamat_ktp = $_karyawan->alamat_ktp;
        $karyawan->alamat_npwp = $_karyawan->alamat_npwp;
        $karyawan->telepon1 = $_karyawan->telepon1;
        $karyawan->telepon2 = $_karyawan->telepon2;
        $karyawan->telepon3 = $_karyawan->telepon3;
        $karyawan->nama_pasangan = $_karyawan->nama_pasangan;
        $karyawan->nama_anak = $_karyawan->nama_anak;
        $karyawan->email = $_karyawan->email;
        $karyawan->kode_perusahaan = $_karyawan->kode_perusahaan;
        $karyawan->alamat_kantor = $_karyawan->alamat_kantor;
        $karyawan->kode_divisi = $_karyawan->kode_divisi;
        $karyawan->kode_departemen = $_karyawan->kode_departemen;
        $karyawan->kode_sub_departemen = $_karyawan->kode_sub_departemen;
        $karyawan->layer1 = $_karyawan->layer1;
        $karyawan->layer2 = $_karyawan->layer2;
        $karyawan->layer3 = $_karyawan->layer3;
        $karyawan->kode_status = $_karyawan->kode_status;
        $karyawan->wg_id = $_karyawan->wg_id;
        $karyawan->is_aktif = $_karyawan->is_aktif;
        $karyawan->gaji_pokok = $_karyawan->gaji_pokok;
        $karyawan->kode_level = $_karyawan->kode_level;
        $karyawan->kode_jabatan = $_karyawan->kode_jabatan;
        $karyawan->tanggal_mulai = $_karyawan->tanggal_mulai;
        $karyawan->tanggal_bergabung = $_karyawan->tanggal_bergabung;
        $karyawan->tanggal_berakhir = $_karyawan->tanggal_berakhir;
        $karyawan->tanggal_karyawan_tetap = $_karyawan->tanggal_karyawan_tetap;
        $karyawan->tanggal_tidak_aktif = $_karyawan->tanggal_tidak_aktif;
        $karyawan->kode_agama = $_karyawan->kode_agama;
        $karyawan->kode_kelamin = $_karyawan->kode_kelamin;
        $karyawan->kode_warga = $_karyawan->kode_warga;
        $karyawan->kode_kawin = $_karyawan->kode_kawin;
        // 
        $karyawan->no_rek = $_karyawan->no_rek;
        $karyawan->nama_bank = $_karyawan->nama_bank;
        $karyawan->nama_rek = $_karyawan->nama_rek;
        $karyawan->findim_project = $_karyawan->findim_project;
        $karyawan->findim_divisi = $_karyawan->findim_divisi;
        $karyawan->findim_departemen = $_karyawan->findim_departemen;
        $karyawan->findim_subdepartemen = $_karyawan->findim_subdepartemen;
        $karyawan->id_grade = $_karyawan->id_grade;
        $karyawan->id_fp = $_karyawan->id_fp;
        // 
        $karyawan->created_by = auth()->user()->nik;
        $karyawan->updated_by = auth()->user()->nik;
        $karyawan->save();
        // 
        $user = New User;
        $user->name = $_karyawan->nama;
        $user->nik = $nik_karyawan_baru;
        $user->email = $_karyawan->email;
        $user->userrole = 'nhc';
        $user->password = $_karyawan->password;
        $user->save();
        // 
        $user = New UsersPwd;
        $user->nik = $nik_karyawan_baru;
        $user->password = $_karyawan->password;
        $user->save();
        // 
        $_karyawan = KaryawanAnak::where('nik', $nik_karyawan_lama)->get();
        foreach ($_karyawan as $_data) {
            $trx = New KaryawanAnak;
            $trx->nik = $nik_karyawan_baru;
            $trx->id_anakke = $_data->id_anakke;
            $trx->nama = $_data->nama;
            $trx->save();
        }
        // 
        $_karyawan = KaryawanPelatihan::where('nik', $nik_karyawan_lama)->get();
        foreach ($_karyawan as $_data) {
            $trx = New KaryawanPelatihan;
            $trx->nik = $nik_karyawan_baru;
            $trx->nama = $_data->nama;
            $trx->keterangan = $_data->keterangan;
            $trx->save();
        }
        // 
        $_karyawan = KaryawanPendidikan::where('nik', $nik_karyawan_lama)->get();
        foreach ($_karyawan as $_data) {
            $trx = New KaryawanPendidikan;
            $trx->nik = $nik_karyawan_baru;
            $trx->id_jenjang = $_data->id_jenjang;
            $trx->nama = $_data->nama;
            $trx->jurusan_prodi = $_data->jurusan_prodi;
            $trx->save();
        }
        // 
        $_karyawan = KaryawanSertifikasi::where('nik', $nik_karyawan_lama)->get();
        foreach ($_karyawan as $_data) {
            $trx = New KaryawanSertifikasi;
            $trx->nik = $nik_karyawan_baru;
            $trx->nama = $_data->nama;
            $trx->keterangan = $_data->keterangan;
            $trx->save();
        }
        // 
        if(File::exists(public_path('assets/images/matta/karyawan/'.$nik_karyawan_lama.'/'.$nik_karyawan_lama.'.jpg'))) {
            File::makeDirectory(public_path('assets/images/matta/karyawan/'.$nik_karyawan_baru, 0777, true, true));
            File::copy(public_path('assets/images/matta/karyawan/'.$nik_karyawan_lama.'/'.$nik_karyawan_lama.'.jpg'), public_path('assets/images/matta/karyawan/'.$nik_karyawan_baru.'/'.$nik_karyawan_baru.'.jpg'));
        }
        // 5 = PT NASIO KARYA PRATAMA
        // 38 = Operation
        // 101 = Operation
        // 349 = Internal Resource
        // DB::table('mtr_tbl_karyawan')->where('nik', $nik_karyawan_baru)->update(
        //     ['kode_perusahaan' => 5, 'kode_divisi' => 38, 'kode_departemen' => 101, 'kode_sub_departemen' => 349,
        // ]);
        // DB::table('mtr_tbl_karyawan')->where('nik', $nik_karyawan_lama)->update(
        //     ['is_aktif' => 0,
        // ]);
        // }
        return redirect( 'karyawan' )->with( 'msg', 'Data is copied successfully.' );
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
