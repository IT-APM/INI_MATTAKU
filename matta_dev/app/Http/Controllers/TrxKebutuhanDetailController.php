<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
// 
use App\Models\PendidikanJenjang;
use App\Models\Benefit;
use App\Models\RekomendasiRencana;
use App\Models\StatusKerja;
// use App\Models\TrxKebutuhanMaster;
use App\Models\TrxKebutuhanDetail;
// 

class TrxKebutuhanDetailController extends Controller
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
        // dd($id);
        $linkAction = route('kebutuhan_detail.update', $id);
        // $trxM = TrxKebutuhanMaster::find( $id );
        $id = (new OpenDataController)->decryptWithKey($id);
        $trxD = TrxKebutuhanDetail::find( $id );
        // level minimal 4 = Assistant Manager
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->where('kode_level','>=',4)->orderBy('nama', 'asc')->get();
        // 
        $jenjang_pendidikan = PendidikanJenjang::orderBy('id', 'asc')->get();
        $benefit = Benefit::orderBy('benefit', 'asc')->get();
        $pencatatan_kepegawaian = RekomendasiRencana::orderBy('rencana', 'asc')->get();
        $status_kepegawaian = StatusKerja::orderBy('status_kerja', 'asc')->get();
        return view('formulir.kebutuhan.doc_update', compact('trxD', 'karyawan', 'jenjang_pendidikan', 'benefit', 'pencatatan_kepegawaian', 'status_kepegawaian'));
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
        // $this->validate( $request, [
        //     'nama_karyawan'=>'required',
        //     'file_cv'=>'required'
        // ] );
        //
        // return response()->json(['request' => $request->all()]);
        // 
        $id_kebutuhan = $request->id_kebutuhan;
        // 
        $trxD = TrxKebutuhanDetail::find( $id );
        // PERSONAL
        $trxD->nama_karyawan = $request->nama_karyawan;
        $trxD->alamat = $request->alamat;
        $trxD->tanggal_lahir = $request->tanggal_lahir;
        $trxD->id_pendidikan = $request->id_pendidikan;
        // INTERVIEW/PSIKOTES
        $trxD->tanggal_interview = $request->tanggal_interview;
        if($request->has('file_interview')){
            $file_interview = $request->file('file_interview');
            $namafile = 'file_interview_'.$id . '_' . time() . '.' . $file_interview->getClientOriginalExtension();
            $file_interview->move('public/assets/kebutuhan/', $namafile);
            $trxD->file_interview = $namafile;
        }
        $trxD->tanggal_psikotes = $request->tanggal_psikotes;
        if($request->has('file_psikotes')){
            $file_psikotes = $request->file('file_psikotes');
            $namafile = 'file_psikotes_'.$id . '_' . time() . '.' . $file_psikotes->getClientOriginalExtension();
            $file_psikotes->move('public/assets/kebutuhan/', $namafile);
            $trxD->file_psikotes = $namafile;
        }
        $trxD->tanggal_negosiasi = $request->tanggal_negosiasi;
        // ORGANISASI
        $trxD->atasan = $request->atasan;
        $trxD->tanggal_peraturan_perusahaan = $request->tanggal_peraturan_perusahaan;
        $trxD->tanggal_pedoman_prilaku = $request->tanggal_pedoman_prilaku;
        if($request->has('file_dokumen_ttd')){
            $file_dokumen_ttd = $request->file('file_dokumen_ttd');
            $namafile = 'file_dokumen_ttd_'.$id . '_' . time() . '.' . $file_dokumen_ttd->getClientOriginalExtension();
            $file_dokumen_ttd->move('public/assets/kebutuhan/', $namafile);
            $trxD->file_dokumen_ttd = $namafile;
        }
        $trxD->tanggal_struktur_organisasi = $request->tanggal_struktur_organisasi;
        $trxD->keterangan2 = $request->keterangan2;
        // KELENGKAPAN DOKUMEN
        if($request->has('file_cv')){
            $file_cv = $request->file('file_cv');
            $namafile = 'file_cv_'.$id . '_' . time() . '.' . $file_cv->getClientOriginalExtension();
            $file_cv->move('public/assets/kebutuhan/', $namafile);
            $trxD->file_cv = $namafile;
        }
        if($request->has('file_sertifikat_keahlian')){
            $file_sertifikat_keahlian = $request->file('file_sertifikat_keahlian');
            $namafile = 'file_sertifikat_keahlian_'.$id . '_' . time() . '.' . $file_sertifikat_keahlian->getClientOriginalExtension();
            $file_sertifikat_keahlian->move('public/assets/kebutuhan/', $namafile);
            $trxD->file_sertifikat_keahlian = $namafile;
        }
        if($request->has('file_ktp')){
            $file_ktp = $request->file('file_ktp');
            $namafile = 'file_ktp_'.$id . '_' . time() . '.' . $file_ktp->getClientOriginalExtension();
            $file_ktp->move('public/assets/kebutuhan/', $namafile);
            $trxD->file_ktp = $namafile;
        }
        if($request->has('file_kk')){
            $file_kk = $request->file('file_kk');
            $namafile = 'file_kk_'.$id . '_' . time() . '.' . $file_kk->getClientOriginalExtension();
            $file_kk->move('public/assets/kebutuhan/', $namafile);
            $trxD->file_kk = $namafile;
        }
        if($request->has('file_npwp')){
            $file_npwp = $request->file('file_npwp');
            $namafile = 'file_npwp_'.$id . '_' . time() . '.' . $file_npwp->getClientOriginalExtension();
            $file_npwp->move('public/assets/kebutuhan/', $namafile);
            $trxD->file_npwp = $namafile;
        }
        if($request->has('file_bpjs_tk')){
            $file_bpjs_tk = $request->file('file_bpjs_tk');
            $namafile = 'file_bpjs_tk_'.$id . '_' . time() . '.' . $file_bpjs_tk->getClientOriginalExtension();
            $file_bpjs_tk->move('public/assets/kebutuhan/', $namafile);
            $trxD->file_bpjs_tk = $namafile;
        }
        if($request->has('file_bpjs_kes')){
            $file_bpjs_kes = $request->file('file_bpjs_kes');
            $namafile = 'file_bpjs_kes_'.$id . '_' . time() . '.' . $file_bpjs_kes->getClientOriginalExtension();
            $file_bpjs_kes->move('public/assets/kebutuhan/', $namafile);
            $trxD->file_bpjs_kes = $namafile;
        }
        if($request->has('file_buku_tabungan')){
            $file_buku_tabungan = $request->file('file_buku_tabungan');
            $namafile = 'file_buku_tabungan_'.$id . '_' . time() . '.' . $file_buku_tabungan->getClientOriginalExtension();
            $file_buku_tabungan->move('public/assets/kebutuhan/', $namafile);
            $trxD->file_buku_tabungan = $namafile;
        }
        if($request->has('file_ket_sehat')){
            $file_ket_sehat = $request->file('file_ket_sehat');
            $namafile = 'file_ket_sehat_'.$id . '_' . time() . '.' . $file_ket_sehat->getClientOriginalExtension();
            $file_ket_sehat->move('public/assets/kebutuhan/', $namafile);
            $trxD->file_ket_sehat = $namafile;
        }
        if($request->has('file_skck')){
            $file_skck = $request->file('file_skck');
            $namafile = 'file_skck_'.$id . '_' . time() . '.' . $file_skck->getClientOriginalExtension();
            $file_skck->move('public/assets/kebutuhan/', $namafile);
            $trxD->file_skck = $namafile;
        }
        if($request->has('file_ijazah')){
            $file_ijazah = $request->file('file_ijazah');
            $namafile = 'file_ijazah_'.$id . '_' . time() . '.' . $file_ijazah->getClientOriginalExtension();
            $file_ijazah->move('public/assets/kebutuhan/', $namafile);
            $trxD->file_ijazah = $namafile;
        }
        if($request->has('file_offering')){
            $file_offering = $request->file('file_offering');
            $namafile = 'file_offering_'.$id . '_' . time() . '.' . $file_offering->getClientOriginalExtension();
            $file_offering->move('public/assets/kebutuhan/', $namafile);
            $trxD->file_offering = $namafile;
        }
        $trxD->keterangan1 = $request->keterangan1;
        // BENEFIT
        $trxD->take_home_pay = $request->take_home_pay;
        if($request->has('id_benefit')){
            $trxD->id_benefit = implode(',', $request->id_benefit);
        }
        $trxD->keterangan3 = $request->keterangan3;
        // KEPEGAWAIAN
        $trxD->tanggal_bergabung = $request->tanggal_bergabung;
        $trxD->id_pencatatan_kepegawaian = $request->id_pencatatan_kepegawaian;
        $trxD->id_status_kepegawaian = $request->id_status_kepegawaian;
        $trxD->tanggal_mulai = $request->tanggal_mulai;
        $trxD->tanggal_akhir = $request->tanggal_akhir;
        $trxD->tanggal_ttd_kontrak = $request->tanggal_ttd_kontrak;
        $trxD->update();
        return redirect()->route('kebutuhan.show', [(new OpenDataController)->encryptWithKey($id_kebutuhan), 'view' => (new OpenDataController)->encryptWithKey('doc')])->with('msg', 'Data is saved successfully.');
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
