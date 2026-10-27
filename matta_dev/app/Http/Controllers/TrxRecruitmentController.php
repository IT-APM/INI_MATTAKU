<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\MtrRecruitmentResume;
use App\Models\TrxRecruitmentResume;
use App\Models\TrxKebutuhanMaster;
use App\Models\TrxKebutuhanDetail;
use App\Models\TrxKebutuhanLog;
use App\Models\TrxRecruitment;
use App\Models\MtrRecruitmentStatus;
use App\Models\MtrRecruitmentSoal;
use App\Models\MtrRecruitmentSoalTiu5;
use App\Models\MtrRecruitmentSoalTiu5Norma;
use App\Models\TrxRecruitmentIdentitas;
use App\Models\TrxRecruitmentAnak;
use App\Models\TrxRecruitmentSaudara;
use App\Models\TrxRecruitmentFormal;
use App\Models\TrxRecruitmentInformal;
use App\Models\TrxRecruitmentOrganisasi;
use App\Models\TrxRecruitmentPekerjaan;
use App\Models\TrxRecruitmentPsychotest;
use App\Models\TrxRecruitmentPsychotestLog;
use App\Models\TrxRecruitmentTiu5;
use App\Models\TrxRecruitmentInterview;
use App\Models\TrxRecruitmentLog;
use App\Models\TrxRecruitmentAtributeLog;
use App\Models\MtrRecruitmentBenefit;
use App\Models\MtrRecruitmentCatatPegawai;
use App\Models\MtrRecruitmentStatusPegawai;
use App\Models\TrxRecruitmentNegosiasi;
use App\Models\TrxRecruitmentNegosiasiLog;
use App\Models\MtrUtility;
use App\Models\Karyawan;
use App\Models\MtrJoin;
use Spatie\Browsershot\Browsershot;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Encryption\DecryptException;
use Carbon\Carbon;
use App\Exports\TrxRecruitmentPsychotestExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Elibyy\TCPDF\Facades\TCPDF;
use App\Models\TrxRecruitmentPedoman;
use App\Models\StatusKerja;
use App\Models\PekerjaanSub;
use App\Models\Level;
use App\Models\Propinsi;
use App\Models\PropinsiKota;
use App\Models\Perusahaan;
use App\Models\Divisi;
use App\Models\Departemen;
use App\Models\SubDepartemen;
use App\Models\ToDoList;
use App\Models\ToDoListLog;
use ZipArchive;

class TrxRecruitmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // // start
        // // backdoor mapping trx_tbl_recruitment_identitas ke trx_tbl_kebutuhan_detail
        // $trx = TrxRecruitmentIdentitas::whereIn('id_status', [24,26])->orderBy('id', 'desc')->get();
        // foreach($trx as $__trx){
        //     $id = $__trx->id;
        //     $_trx = TrxRecruitmentIdentitas::find($id);
        //     $interview = TrxRecruitmentInterview::where(['id_identitas' => $id])->orderBy('id', 'asc')->first();
        //     $nego = TrxRecruitmentNegosiasi::where(['id_identitas' => $id])->orderBy('id', 'desc')->first();
        //     $trxM = TrxKebutuhanMaster::find($_trx->id_kebutuhan);
        //     $trxD = TrxKebutuhanDetail::where(['id_kebutuhan' => $_trx->id_kebutuhan])->whereNull('id_identitas')->orderBy('id', 'desc')->first();
        //     if(!empty($trxD)){
        //         $trxD->nama_karyawan = $_trx->nama;
        //         $trxD->alamat = $_trx->alamat_ktp;
        //         $trxD->tanggal_lahir = $_trx->tanggal_lahir;
        //         $arr_pendidikan = ['SD', 'SMP', 'SMA/SMK', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3', 'Lainnya'];
        //         $trxD->id_pendidikan = array_search($_trx->pendidikan, $arr_pendidikan) + 1;
        //         $trxD->tanggal_interview = Carbon::parse($interview->check_in)->format('Y-m-d');
        //         if(!empty($interview->file_interview)){
        //             $trxD->file_interview = $interview->file_interview;
        //             File::copy(public_path('assets/recruitment/upload/'.$interview->file_interview), public_path('assets/kebutuhan/'.$interview->file_interview));
        //         }
        //         $trxD->tanggal_psikotes = Carbon::parse($_trx->check_in)->format('Y-m-d');
        //         $file_psikotes = $id.'_'.now()->getTimestampMs().'.pdf';
        //         $this->pdf_interpretation($id, $file_psikotes);
        //         $trxD->file_psikotes = $file_psikotes;
        //         // 17=Negotiation Deal by Candidate
        //         $tanggal_negosiasi = TrxRecruitmentLog::where(['id_identitas'=>$id, 'id_status'=>17])->orderBy('id', 'asc')->first();
        //         if(!empty($tanggal_negosiasi->created_at)){
        //             $trxD->tanggal_negosiasi = Carbon::parse($tanggal_negosiasi->created_at)->format('Y-m-d');
        //         }
        //         // 20=COC and File Upload Done
        //         $tanggal_coc = TrxRecruitmentLog::where(['id_identitas'=>$id, 'id_status'=>20])->orderBy('id', 'asc')->first();
        //         $trxD->tanggal_peraturan_perusahaan = Carbon::parse($tanggal_coc->created_at)->format('Y-m-d');
        //         $trxD->tanggal_pedoman_prilaku = Carbon::parse($tanggal_coc->created_at)->format('Y-m-d');
        //         $trxD->tanggal_struktur_organisasi = Carbon::parse($tanggal_coc->created_at)->format('Y-m-d');
        //         if(!empty($_trx->file_dokumen_ttd)){
        //             $trxD->file_dokumen_ttd = $_trx->file_dokumen_ttd;
        //             File::copy(public_path('assets/recruitment/upload/'.$_trx->file_dokumen_ttd), public_path('assets/kebutuhan/'.$_trx->file_dokumen_ttd));
        //         }
        //         if(!empty($_trx->cv)){
        //             $trxD->file_cv = $_trx->cv;
        //             File::copy(public_path('assets/recruitment/upload/'.$_trx->cv), public_path('assets/kebutuhan/'.$_trx->cv));
        //         }
        //         if(!empty($_trx->file_sertifikat_keahlian)){
        //             $trxD->file_sertifikat_keahlian = $_trx->file_sertifikat_keahlian;
        //             File::copy(public_path('assets/recruitment/upload/'.$_trx->file_sertifikat_keahlian), public_path('assets/kebutuhan/'.$_trx->file_sertifikat_keahlian));
        //         }
        //         if(!empty($_trx->file_ktp)){
        //             $trxD->file_ktp = $_trx->file_ktp;
        //             File::copy(public_path('assets/recruitment/upload/'.$_trx->file_ktp), public_path('assets/kebutuhan/'.$_trx->file_ktp));
        //         }
        //         if(!empty($_trx->file_kk)){
        //             $trxD->file_kk = $_trx->file_kk;
        //             File::copy(public_path('assets/recruitment/upload/'.$_trx->file_kk), public_path('assets/kebutuhan/'.$_trx->file_kk));
        //         }
        //         if(!empty($_trx->file_npwp)){
        //             $trxD->file_npwp = $_trx->file_npwp;
        //             File::copy(public_path('assets/recruitment/upload/'.$_trx->file_npwp), public_path('assets/kebutuhan/'.$_trx->file_npwp));
        //         }
        //         if(!empty($_trx->file_bpjs_tk)){
        //             $trxD->file_bpjs_tk = $_trx->file_bpjs_tk;
        //             File::copy(public_path('assets/recruitment/upload/'.$_trx->file_bpjs_tk), public_path('assets/kebutuhan/'.$_trx->file_bpjs_tk));
        //         }
        //         if(!empty($_trx->file_bpjs_kes)){
        //             $trxD->file_bpjs_kes = $_trx->file_bpjs_kes;
        //             File::copy(public_path('assets/recruitment/upload/'.$_trx->file_bpjs_kes), public_path('assets/kebutuhan/'.$_trx->file_bpjs_kes));
        //         }
        //         if(!empty($_trx->file_buku_tabungan)){
        //             $trxD->file_buku_tabungan = $_trx->file_buku_tabungan;
        //             File::copy(public_path('assets/recruitment/upload/'.$_trx->file_buku_tabungan), public_path('assets/kebutuhan/'.$_trx->file_buku_tabungan));
        //         }
        //         if(!empty($_trx->file_ket_sehat)){
        //             $trxD->file_ket_sehat = $_trx->file_ket_sehat;
        //             File::copy(public_path('assets/recruitment/upload/'.$_trx->file_ket_sehat), public_path('assets/kebutuhan/'.$_trx->file_ket_sehat));
        //         }
        //         if(!empty($_trx->file_skck)){
        //             $trxD->file_skck = $_trx->file_skck;
        //             File::copy(public_path('assets/recruitment/upload/'.$_trx->file_skck), public_path('assets/kebutuhan/'.$_trx->file_skck));
        //         }
        //         if(!empty($_trx->file_ijazah)){
        //             $trxD->file_ijazah = $_trx->file_ijazah;
        //             File::copy(public_path('assets/recruitment/upload/'.$_trx->file_ijazah), public_path('assets/kebutuhan/'.$_trx->file_ijazah));
        //         }
        //         $file_offering = $id.'_'.now()->getTimestampMs().'.pdf';
        //         $this->pdf_offering($id, $file_offering);
        //         $trxD->file_offering = $file_offering;
        //         $trxD->take_home_pay = $nego->thp;
        //         // mapping ke benefit_utama dan benefit_lainnya
        //         $benefit_utama = explode(',', $nego->benefit_utama);
        //         $id_benefit = array();
        //         foreach($benefit_utama as $data){
        //             // mapping mtr_tbl_recruitment_benefit vs mtr_tbl_benefit
        //             // refer to "C:\alita\matta\data_master\mapping kebutuhan vs recruitment v1.xlsx"
        //             // 1=Upah Lembur
        //             if($data == 1){
        //                 // 1=Overtime
        //                 array_push($id_benefit, 1);
        //             }
        //             // Medical Reimbursement
        //             elseif($data == 2){
        //                 // 2=Medical Reimbursement
        //                 array_push($id_benefit, 2);
        //             }
        //             // Asuransi
        //             elseif($data == 10){
        //                 // 3=Asuransi
        //                 array_push($id_benefit, 3);
        //             }
        //             // 5=BPJS Ketenagakerjaan, ditanggung perusahaan
        //             // 6=BPJS Ketenagakerjaan, ditanggung  perusahaan & karyawan
        //             elseif($data == 5 || $data == 6){
        //                 // 4=BPJS Ketenagakerjaan
        //                 array_push($id_benefit, 4);
        //             }
        //             // 7=BPJS Kesehatan, ditanggung perusahaan
        //             // 8=BPJS Kesehatan, ditanggung  perusahaan & karyawan
        //             elseif($data == 7 || $data == 8){
        //                 // 5=BPJS Kesehatan
        //                 array_push($id_benefit, 5);
        //             }
        //             // 9=Cuti Setelah 1 tahun
        //             elseif($data == 9){
        //                 // 6=Cuti
        //                 array_push($id_benefit, 6);
        //             }
        //             // 3=PPh 21, ditanggung perusahaan
        //             // 4=PPh 21, ditanggung perusahaan & karyawan
        //             elseif($data == 3 || $data == 4){
        //                 // 7=PPh21
        //                 array_push($id_benefit, 7);
        //             }
        //             // 11=Tunjangan Corporate
        //             elseif($data == 11){
        //                 // 8=Tunjangan Corporate
        //                 array_push($id_benefit, 8);
        //             }
        //             // 12=Tunjangan Project / Operasional
        //             elseif($data == 12){
        //                 // 9=Tunjangan Project / Operasional
        //                 array_push($id_benefit, 9);
        //             }
        //             // 13=Alat kerja (laptop / notebook)
        //             elseif($data == 13){
        //                 // 10=Alat kerja (laptop / notebook)
        //                 array_push($id_benefit, 10);
        //             }
        //         }
        //         $trxD->id_benefit = implode(',',$id_benefit);
        //         $trxD->keterangan3 = $nego->benefit_lainnya;
        //         $trxD->tanggal_bergabung = $nego->tgl_bergabung;
        //         // mapping mtr_tbl_recruitment_catat_pegawai vs mtr_tbl_rencana_penambahan
        //         // refer to "C:\alita\matta\data_master\mapping kebutuhan vs recruitment v1.xlsx"
        //         $id_catat_pegawai = $nego->id_catat_pegawai;
        //         // 1=ALITA
        //         if($id_catat_pegawai == 1){
        //             // 4=Internal Alita
        //             $trxD->id_pencatatan_kepegawaian = 4;
        //         }
        //         // 2=Outsource
        //         elseif($id_catat_pegawai == 2){
        //             // 3=Outsource
        //             $trxD->id_pencatatan_kepegawaian = 3;
        //         }
        //         // mapping mtr_tbl_recruitment_status_pegawai vs mtr_tbl_status_kerja
        //         // refer to "C:\alita\matta\data_master\mapping kebutuhan vs recruitment v1.xlsx"
        //         $id_status_pegawai = $nego->id_status_pegawai;
        //         // 1=Kontrak dan 1=ALITA
        //         if($id_status_pegawai == 1 && $id_catat_pegawai == 1){
        //             // 1=Karyawan Kontrak Alita
        //             $trxD->id_status_kepegawaian = 1;
        //         }
        //         // 2=Tetap
        //         elseif($id_status_pegawai == 2){
        //             // 2=Karyawan Tetap
        //             $trxD->id_status_kepegawaian = 2;
        //         }
        //         // 3=Tenaga Lepas Harian (TLH)
        //         elseif($id_status_pegawai == 3){
        //             // 3=Freelance
        //             $trxD->id_status_kepegawaian = 3;
        //         }
        //         // 4=Konsultan
        //         elseif($id_status_pegawai == 4){
        //             // 8=Konsultan
        //             $trxD->id_status_kepegawaian = 8;
        //         }
        //         // 1=Kontrak dan 2=Outsource
        //         elseif($id_status_pegawai == 1 && $id_catat_pegawai == 2){
        //             // 9=Karyawan Kontrak Outsource
        //             $trxD->id_status_kepegawaian = 9;
        //         }
        //         $trxD->tanggal_mulai = $nego->tgl_bergabung;
        //         $trxD->tanggal_akhir = Carbon::parse($nego->tgl_bergabung)->addMonths($nego->masa_kerja)->format('Y-m-d');
        //         $trxD->tanggal_ttd_kontrak = $nego->tgl_bergabung;
        //         $trxD->atasan = $trxM->atasan;
        //         $trxD->id_identitas = $id;
        //         $trxD->update();
        //     }

        // }
        // // end
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $id_trx = [];
        $trx = TrxKebutuhanMaster::orderBy('id', 'desc')->get();
        if(Gate::allows('hc_nkp')){
            $trx = TrxKebutuhanMaster::whereIn('pengaju',$arr_nik_nkp)->orderBy('id', 'desc')->get();
        }
        foreach($trx as $data){
            // 9=Approved by L2 to HC
            $where = ['id_trx' => $data->id, 'id_status' => 9];
            if(TrxKebutuhanLog::where($where)->exists()){
                array_push($id_trx, $data->id);
            }
        }
        // $trx = TrxKebutuhanMaster::whereIn('id', $id_trx)->orderBy('id', 'desc')->get();
        // terposting adalah pengajuan yang statusnya 9=Approved by L2 to HC dan bukan 7=Magang
        $trx = TrxKebutuhanMaster::whereIn('id', $id_trx)->whereNotIn('id_status_kerja', [7])->orderBy('id', 'desc')->get();
        // kecuali, refer to "C:\alita\matta\data_master\Backdoor Add Employee_280726.xlsx"
        $arr_id_trx = explode(',', MtrUtility::find(53)->informasi);
        if(!empty(array_intersect($id_trx, $arr_id_trx))){
            $trx = TrxKebutuhanMaster::whereIn('id', $id_trx)->orderBy('id', 'desc')->get();
        }
        $pengaju = array();
        $id_pekerjaan = array();
        $id_status_kerja = array();
        foreach($trx as $data){
            array_push($pengaju, $data->pengaju);
            array_push($id_pekerjaan, $data->id_pekerjaan);
            array_push($id_status_kerja, $data->id_status_kerja);
        }
        $arr_pengaju = $pengaju;
        $pengaju = Karyawan::select('nik', 'nama')->whereIn('nik', $pengaju)->orderBy('nama', 'asc')->get();
        $arr_id_pekerjaan = array();
        $arr_id_status_kerja = array();
        $pekerjaan = PekerjaanSub::whereIn('id', $id_pekerjaan)->orderBy('pekerjaan', 'asc')->get();
        $status_kerja = StatusKerja::whereIn('id', $id_status_kerja)->orderBy('status_kerja', 'asc')->get();
        // start filter
        if($request->filter == true){
            // $_pengaju = $request->input('pengaju', []);
            $_pengaju = [$request->pengaju];
            // $_id_pekerjaan = $request->input('id_pekerjaan', []);
            $_id_pekerjaan = [$request->id_pekerjaan];
            $_id_status_kerja = $request->input('id_status_kerja', []);
            if(count($_pengaju) > 0){
                $arr_pengaju = $_pengaju;
            }
            else{
                $arr_pengaju = array();
            }
            if(count($_id_pekerjaan) > 0){
                $arr_id_pekerjaan = $_id_pekerjaan;
            }
            else{
                $arr_id_pekerjaan = array();
            }
            if(count($_id_status_kerja) > 0){
                $arr_id_status_kerja = $_id_status_kerja;
            }
            else{
                $arr_id_status_kerja = array();
            }
            // dd($arr_pengaju, $arr_id_pekerjaan);
            $trx = TrxKebutuhanMaster::whereIn('pengaju', $arr_pengaju)->whereIn('id_pekerjaan', $arr_id_pekerjaan)->whereIn('id_status_kerja', $arr_id_status_kerja)->whereIn('id', $id_trx)->whereNotIn('id_status_kerja', [7])->orderBy('id', 'desc')->get();
            if(in_array('0', $arr_pengaju) && in_array('0', $arr_id_pekerjaan)){
                $trx = TrxKebutuhanMaster::whereIn('id_status_kerja', $arr_id_status_kerja)->whereIn('id', $id_trx)->whereNotIn('id_status_kerja', [7])->orderBy('id', 'desc')->get();
            }elseif(in_array('0', $arr_pengaju)){
                $trx = TrxKebutuhanMaster::whereIn('id_pekerjaan', $arr_id_pekerjaan)->whereIn('id_status_kerja', $arr_id_status_kerja)->whereIn('id', $id_trx)->whereNotIn('id_status_kerja', [7])->orderBy('id', 'desc')->get();
            }elseif(in_array('0', $arr_id_pekerjaan)){
                $trx = TrxKebutuhanMaster::whereIn('pengaju', $arr_pengaju)->whereIn('id_status_kerja', $arr_id_status_kerja)->whereIn('id', $id_trx)->whereNotIn('id_status_kerja', [7])->orderBy('id', 'desc')->get();
            }
        }else{
            $arr_pengaju = array();
            $arr_id_pekerjaan = array();
            $arr_id_status_kerja = array();
        }
        // end filter
        return view('recruitment.index', compact('trx', 'pengaju', 'pekerjaan', 'status_kerja', 'arr_id_pekerjaan', 'arr_id_status_kerja', 'arr_pengaju'));
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

    public function post_atribute(Request $request)
    {
        // dd($request->all());
        $id_identitas = Crypt::decrypt($request->id);
        $_data = json_encode([
            'id_pekerjaan'=>$request->id_pekerjaan,
            'id_level'=>$request->id_level,
            'id_area'=>$request->id_area,
            'id_kota'=>$request->id_kota,
            'atasan'=>$request->atasan,
            'atasan_property'=>$this->getNikProperty($request->atasan),
            'id_perusahaan_kandidat'=>$request->id_perusahaan_kandidat,
            'id_divisi_kandidat'=>$request->id_divisi_kandidat,
            'id_dep_kandidat'=>$request->id_dep_kandidat,
            'id_sub_dep_kandidat'=>$request->id_sub_dep_kandidat,
        ]);
        TrxRecruitmentIdentitas::where('id', $id_identitas)->update(['kebutuhan_atribute' => $_data]);
        // $trx = TrxRecruitmentIdentitas::find($id_identitas);
        // $trx->kebutuhan_atribute = $_data;
        // $trx->save();
        // if($trx->wasChanged('kebutuhan_atribute')) {
        if(!empty($request->catatan)) {
            $trx = new TrxRecruitmentAtributeLog;
            $trx->id_identitas = $id_identitas;
            $trx->inserted_by = auth()->user()->nik;
            $trx->catatan = $request->catatan;
            $trx->save();
        }
        return redirect()->to($request->back_url)->with('msg_post_atribute', 'Data is saved successfully.');
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
        // dd($request->all());
        if(empty($request->job_description)){
            return redirect()->back()->with('error', 'Content is empty.');
        }
        // 
        $id_kebutuhan = Crypt::decrypt($request->id_kebutuhan);
        $where = ['id_kebutuhan' => $id_kebutuhan];
        $trx = TrxRecruitment::where($where)->orderBy('id', 'desc')->first();
        if(!empty($trx)){
            $trx->job_description = $request->job_description;
            $trx->update();
        }else{
            $trx = new TrxRecruitment;
            $trx->id_kebutuhan = Crypt::decrypt($request->id_kebutuhan);
            $trx->id_status = 2;
            $trx->job_description = $request->job_description;
            $trx->save();
        }
        // $link = 'http://localhost/matta/recruitment/eyJpdiI6IlFEMEo0eFBZampzRGg3dURuYml6UkE9PSIsInZhbHVlIjoiVnlOUi9ZV2NVbnJWMVk1UXM2anVhQT09IiwibWFjIjoiZjdmYTI0NzZmMmZlN2NlZmI0YzQ5OGNlOTYwNjk1MGVlMjk4ZGQ4NjQ4NmIyYTQxM2FiZTVlYWRiZDNiOWJkMyIsInRhZyI6IiJ9?view=posting';
        // Browsershot::url($link)->setScreenshotType('jpeg', 100)->windowSize(1920, 1080)->save('example.jpg');
        return redirect('recruitment')->with('msg', 'Data is saved successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // dd(Crypt::decrypt($id));
        $id = Crypt::decrypt($id);
        $id_kebutuhan = $id[0];
        $trx = TrxKebutuhanMaster::find($id_kebutuhan);
        $where = ['id_kebutuhan' => $id_kebutuhan];
        $_trx = TrxRecruitment::where($where)->orderBy('id', 'desc')->first();
        $default = '<div class="col-12 text-center"><span style="font-size:30px;"><strong>Urgently Needed!</strong></span></div>';
        $default .= '<div class="container">
                        <div class="row">
                            <div class="col-12 text-center text-uppercase"><span style="font-size:20px;color:orange;"><strong>'.$trx->_pekerjaan->pekerjaan.'</strong></span></div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-1">&nbsp;</div>
                            <div class="col-5">
                                <h5 class="text-start">Qualification</h5>
                                <table class="table table-borderless text-start" style="font-size:16px">
                                    <tbody>
                                        <tr>
                                            <td>'.$trx->_kelamin->kelamin.'<br/>'
                                            .$trx->_jenjang_pendidikan->jenjang.'<br/>Usia minimum '
                                            .$trx->umur_minimum.' tahun<br/>Usia maksimum '
                                            .$trx->umur_maksimum.' tahun<br/>'
                                            .$trx->_pengalaman->pengalaman.
                                            '</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-5">
                                <h5 class="text-start">Job Description</h5>
                                <table class="table table-borderless text-start" style="font-size:16px">
                                    <tbody>
                                        <tr>
                                            <td>'.$trx->tugas.'<br/>'
                                            .$trx->wewenang.'<br/>'
                                            .$trx->tanggung_jawab.
                                            '</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-1">&nbsp;</div>
                        </div>
                    </div>';
        if($id[1] == 2){
            return view('recruitment.posting', compact('trx', '_trx', 'default'));
        }elseif($id[1] == 1){
            return view('recruitment.lowongan', compact('trx', '_trx', 'default'));
        }
    }

    public function applicant_process(Request $request, $id)
    {
        // psikotest_reschedule
        $process = $request->process;
        $id = Crypt::decrypt($id);
        // dd($request->all(), $id);
        $id_interview = $request->id_interview;
        if(!empty($id_interview)){
            $id_interview = Crypt::decrypt($request->id_interview);
        }
        // dd($id_interview);
        // dd($request->all(), $id);
        $where = ['id_identitas' => $id];
        $history = TrxRecruitmentLog::where($where)->orderBy('id', 'desc')->get();
        $resume = TrxRecruitmentResume::where($where)->orderBy('id', 'asc')->get();
        $trx = TrxRecruitmentIdentitas::find($id);
        $trxM = TrxKebutuhanMaster::find($trx->id_kebutuhan);
        $interview = array();
        if(!empty($id_interview)){
            $interview = TrxRecruitmentInterview::find($id_interview);
        }
        $nego = TrxRecruitmentNegosiasi::where($where)->orderBy('id', 'desc')->first();
        $benefit = MtrRecruitmentBenefit::orderBy('benefit', 'asc')->get();
        $catat_pegawai = MtrRecruitmentCatatPegawai::orderBy('pencatatan', 'asc')->get();
        $status_pegawai = MtrRecruitmentStatusPegawai::orderBy('status', 'asc')->get();
        $where = ['is_join' => 1];
        $status_join = MtrJoin::where($where)->orderBy('nama', 'asc')->get();
        $where = ['is_join' => 0];
        $status_not_join = MtrJoin::where($where)->orderBy('nama', 'asc')->get();
        // 4=Psychotest Send
        // 6=Interview User and PC Send
        // 8=Interview User Send
        // 10=Interview PC Send
        // 25=Not Join
        $id_status = [4,6,8,10,25];
        $log_status = TrxRecruitmentLog::where(['id_identitas'=>$id])->pluck('id_status')->toArray();
        // 
        $pekerjaan = PekerjaanSub::orderBy('pekerjaan', 'asc')->get();
        $level = Level::orderBy('id', 'asc')->get();
        $area = Propinsi::orderBy('order_by', 'asc')->get();
        $kota = PropinsiKota::orderBy('kota', 'asc')->get();
        $perusahaan = Perusahaan::whereNotIn('id', [0,3])->orderBy('nama', 'asc')->get();
        $divisi = Divisi::orderBy('nama', 'asc')->get();
        $dep = Departemen::orderBy('nama', 'asc')->get();
        $sub_dep = SubDepartemen::orderBy('nama', 'asc')->get();
        // atasan langsung dengan level minimal 3 = Supervisor (SPV)
        $atasan = Karyawan::select('nik', 'nama')->where(['is_aktif' => 1])->where('kode_level','>=',3)->orderBy('nama', 'asc')->get();
        // 5=Psychotest Done
        if(in_array(5, $log_status)){
            // jika kandidat sudah diberikan TIU 5 dan (EPPS, PAPI Kostick, DISC, BAUM, DAP) maka Psychotest Send menjadi tidak ada
            if(TrxRecruitmentPsychotest::where(['id_identitas'=>$id])->exists() && TrxRecruitmentTiu5::where(['id_identitas'=>$id])->exists()){
                // do nothing
            }else{
                // $id_status = array_values(array_diff($id_status, [4]));
                $log_status = array_values(array_diff($log_status, [4]));
            }
            // 16=Interpretation
            // 25=Not Join
            array_push($id_status, 16, 25);
        }
        // dd($log_status);
        // negosiasi dilakukan setelah interpretasi dan done any interview
        // 16=Interpretation
        // 7=Interview User and PC Done
        // 9=Interview User Done
        // 11=Interview PC Done
        if(in_array(16, $log_status) && (in_array(7, $log_status)||in_array(9, $log_status)||in_array(11, $log_status))){
            // 12=Negotiation Process
            // array_push($id_status, 12);
            // 22=Psychotest and Interview Pass
            // 23=Psychotest and Interview Not Pass
            // 25=Not Join
            array_push($id_status, 22, 23, 25);
        }
        // 22=Psychotest and Interview Pass
        if(in_array(22, $log_status)){
            // 6=Interview User and PC Send
            // 8=Interview User Send
            // 10=Interview PC Send
            // 23=Psychotest and Interview Not Pass
            $id_status = array_values(array_diff($id_status, [6,8,10,23]));
            // 7=Magang
            // 8=Konsultan
            if(in_array($trxM->id_status_kerja, [7, 8])){
                // 13=Negotiation Deal by PC
                array_push($id_status, 13);
            }else{
                // 12=Negotiation Process
                // 25=Not Join
                array_push($id_status, 12, 25);
            }
        }
        // 12=Negotiation Process
        if(in_array(12, $log_status)){
            $id_status = [];
            // $id_status = array_values(array_diff($id_status, [6,8,10,16]));
            // 13=Negotiation Deal by PC
            // 14=Negotiation No Deal by PC
            // $id_status = [13,14];
        }
        $id_status = MtrRecruitmentStatus::whereIn('id', $id_status)->whereNotIn('id', $log_status)->orderBy('id', 'asc')->get();
        // 17=Negotiation Deal by Candidate
        // 18=Negotiation No Deal by Candidate
        if(in_array($trx->id_status, [17,18])){
            // 13=Negotiation Deal by PC
            // 14=Negotiation No Deal by PC
            // 21=Re-Negotiation Process
            // 25=Not Join
            $id_status = [13,14,21, 25];
            $id_status = MtrRecruitmentStatus::whereIn('id', $id_status)->orderBy('id', 'asc')->get();
        }
        // 14=Negotiation No Deal by PC
        // 20=COC and File Upload Done
        // 23=Psychotest and Interview Not Pass
        if(in_array($trx->id_status, [14,20,23])){
            // 15=Recruitment Done
            // $id_status = [15];
            // 24=Join
            // 25=Not Join
            $id_status = [24,25];
            $id_status = MtrRecruitmentStatus::whereIn('id', $id_status)->orderBy('id', 'asc')->get();
        }
        // 15=Recruitment Done
        // 24=Join
        // 25=Not Join
        if(in_array($trx->id_status, [15,24,25])){
            $id_status = [];
        }
        // kode_level=3=Supervisor (SPV)
        // dd($id_status);
        // kode_sub_departemen=56 adalah hco
        // MtrUtility::find(56)->informasi
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where('kode_level', '>=', 3)->where($where)->whereNotIn('kode_sub_departemen', [MtrUtility::find(56)->informasi])->orderBy('nama', 'asc')->get();
        $karyawan_user_hco = Karyawan::select('nik', 'nama')->where('kode_level', '>=', 3)->where($where)->orderBy('nama', 'asc')->get();
        $where = ['is_aktif' => 1, 'kode_sub_departemen' => MtrUtility::find(56)->informasi];
        $karyawan_hco = Karyawan::select('nik', 'nama')->where('kode_level', '>=', 3)->where($where)->orderBy('nama', 'asc')->get();
        return view('recruitment.applicant_process', compact('trx', 'id_status', 'karyawan', 'karyawan_hco', 'karyawan_user_hco', 'history', 'trxM', 'benefit', 'catat_pegawai', 'status_pegawai', 'nego', 'resume', 'status_join', 'status_not_join', 'process', 'interview', 'pekerjaan', 'level', 'area', 'kota', 'atasan', 'perusahaan', 'divisi', 'dep', 'sub_dep'));
        
    }

    // public function generateCustomQRCode()
    // {
    //     $image = QrCode::format('png')
    //                     ->size(300)
    //                     ->color(255, 0, 0)
    //                     ->backgroundColor(255, 255, 255)
    //                     ->margin(1)
    //                     ->merge('/public/assets/images/matta/alita_50.png', .3)
    //                     ->generate('Customize your QR code content here');
    //     return view('customQrCode', ['image' => $image]);
    // }

    public function apply($id)
    {
        try {
            $id_recruitment = Crypt::decrypt($id)[0];
            // dd(Crypt::decrypt($id));
            $trx = TrxKebutuhanMaster::find(TrxRecruitment::find($id_recruitment)->id_kebutuhan);
            // $posisi = $trx->_pekerjaan->pekerjaan;
            // 1=Process
            // 2=Posting
            // 3=Apply
            $where = ['id'=>$id_recruitment, 'id_status'=>2];
            // 3=Reject Checked by HC
            // 10=Done by HC
            // 28=Terminate Cancel by HC
            if(TrxRecruitment::where($where)->exists() && !in_array($trx->status_pengajuan, [3,10,28])){
                return view('recruitment.application', compact('id'));
            }else{
                return view('recruitment.no_vacancy', compact('id'));
            }
        } catch (DecryptException $e) {
            return view('errors.403');
        }
    }

    public function applicant_list(Request $request, $id)
    {
        $allowed_nik = ['P0826165'];
        if (!auth()->user()->can('hc') && !in_array(auth()->user()->nik, $allowed_nik)) {
            return redirect()->route('error-403');
        }
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $id_kebutuhan = TrxKebutuhanMaster::pluck('id')->toArray();
        if(Gate::allows('hc_nkp')){
            $id_kebutuhan = TrxKebutuhanMaster::whereIn('pengaju',$arr_nik_nkp)->pluck('id')->toArray();
        }
        $trx = TrxRecruitmentIdentitas::whereIn('id_kebutuhan',$id_kebutuhan)->orderBy('updated_at', 'desc')->get();
        $id_identitas = $trx->pluck('id')->toArray();
        $nama = TrxRecruitmentIdentitas::distinct()->orderBy('nama', 'asc')->get(['nama']);
        $arr_nama = array();
        $id_kebutuhan = array();
        $id_status = array();
        foreach($trx as $data){
            array_push($id_kebutuhan, $data->id_kebutuhan);
            array_push($id_status, $data->id_status);
        }
        $trx0 = TrxKebutuhanMaster::whereIn('id', $id_kebutuhan)->orderBy('id', 'desc')->get();
        $id_pekerjaan = array();
        foreach($trx0 as $data){
            array_push($id_pekerjaan, $data->id_pekerjaan);
        }
        $pekerjaan = PekerjaanSub::whereIn('id', $id_pekerjaan)->orderBy('pekerjaan', 'asc')->get();
        $status = MtrRecruitmentStatus::whereIn('id', $id_status)->orderBy('id', 'asc')->get();
        // start dibatasi 30 hari terakhir dari created_at
        $trx = $trx->filter(function ($item) {
            return Carbon::parse($item->created_at)->gte(Carbon::now()->subDays(30));
        });
        $id_identitas = $trx->pluck('id')->toArray();
        $trx1 = TrxRecruitmentInterview::whereIn('id_identitas',$id_identitas)->orderBy('updated_at', 'desc')->get();
        $trx2 = TrxRecruitmentNegosiasiLog::whereIn('id_identitas',$id_identitas)->orderBy('updated_at', 'desc')->get();
        $tanggal_mulai = '';
        $tanggal_akhir = '';
        // end
        $arr_id_pekerjaan = array();
        $arr_id_status = array();
        // start filter
        if($request->filter == true){
            $tanggal_mulai = $request->tanggal_mulai;
            $tanggal_akhir = $request->tanggal_akhir;
            // $_nama = $request->input('nama', []);
            $_nama = [$request->nama];
            $_id_pekerjaan = $request->input('id_pekerjaan', []);
            $_id_status = $request->input('id_status', []);
            if(count($_nama) > 0){
                $arr_nama = $_nama;
            }
            else{
                $arr_nama = array();
            }
            if(count($_id_pekerjaan) > 0){
                $arr_id_pekerjaan = $_id_pekerjaan;
            }
            else{
                $arr_id_pekerjaan = array();
            }
            if(count($_id_status) > 0){
                $arr_id_status = $_id_status;
            }
            else{
                $arr_id_status = array();
            }
            $trx0 = TrxKebutuhanMaster::whereIn('id_pekerjaan', $arr_id_pekerjaan)->orderBy('id', 'desc')->get();
            if(Gate::allows('hc_nkp')){
                $trx0 = TrxKebutuhanMaster::whereIn('pengaju',$arr_nik_nkp)->whereIn('id_pekerjaan', $arr_id_pekerjaan)->orderBy('id', 'desc')->get();
            }
            $id_kebutuhan = array();
            foreach($trx0 as $data){
                array_push($id_kebutuhan, $data->id);
            }
            $trx = TrxRecruitmentIdentitas::whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->whereIn('nama', $arr_nama)->whereIn('id_kebutuhan', $id_kebutuhan)->whereIn('id_status', $arr_id_status)->orderBy('updated_at', 'desc')->get();
            if(in_array('0', $arr_nama)){
                $trx = TrxRecruitmentIdentitas::whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->whereIn('id_kebutuhan', $id_kebutuhan)->whereIn('id_status', $arr_id_status)->orderBy('updated_at', 'desc')->get();
            }
            $id_identitas = array();
            foreach($trx as $data){
                array_push($id_identitas, $data->id);
            }
            $trx1 = TrxRecruitmentInterview::whereIn('id_identitas', $id_identitas)->orderBy('updated_at', 'desc')->get();
            $trx2 = TrxRecruitmentNegosiasiLog::whereIn('id_identitas', $id_identitas)->orderBy('updated_at', 'desc')->get();
        }else{
            $arr_nama = array();
            $arr_id_pekerjaan = array();
            $arr_id_status = array();
        }
        // end filter
        return view('recruitment.applicant_list', compact('trx', 'trx1', 'trx2', 'nama', 'pekerjaan', 'status', 'arr_nama', 'arr_id_pekerjaan', 'arr_id_status', 'tanggal_mulai', 'tanggal_akhir'));
    }

    public function applicant_item($id)
    {
        $id = Crypt::decrypt($id);
        // dd($id);
        $trx = TrxRecruitmentIdentitas::find($id);
        $_trx = TrxKebutuhanMaster::find($trx->id_kebutuhan);
        // $posisi = $_trx->_pekerjaan->pekerjaan;
        return view('recruitment.applicant_item', compact('trx'));
    }

    public function download_cv(Request $request, $id)
    {
        // dd($request->all(), $id);
        $download_cv = $request->download_cv;
        $download_cv = explode(',', $download_cv);
        $zipFileName = 'download_cv_' . Carbon::parse(now())->format('YmdHis') . '.zip';
        $zipPath = public_path('assets/recruitment/upload/' . $zipFileName);
        $zip = new ZipArchive;
        foreach($download_cv as $_id){
            $trx = TrxRecruitmentIdentitas::find($_id);
            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                $filePath = public_path('assets/recruitment/upload/' . $trx->cv);
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $trx->id_kebutuhan.'_'.$trx->id.'_'.$trx->nama.'.'.$extension); // path di dalam zip
                }
            } else {
                return response()->json(['error' => 'Gagal membuat ZIP.'], 500);
            }
        }
        $zip->close();
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function download_applicant($id)
    {
        $id = Crypt::decrypt($id);
        $trx = TrxRecruitmentIdentitas::find($id);
        $filename = $id . '_applicant.pdf';
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle('Formulir Identitas');
        $pdf::SetFont('times', '', 11, '', true);
        $pdf::AddPage();
        $html = \View::make('recruitment.pdf_persetujuan', compact('trx'))->render();
        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::AddPage();
        $html = \View::make('recruitment.pdf_applicant', compact('trx'))->render();
        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output(public_path($filename), 'I');
        return response()->download(public_path($filename));
    }

    public function interview_item($id)
    {
        $allowed_nik = ['P0826165'];
        if (!auth()->user()->can('hc') && !in_array(auth()->user()->nik, $allowed_nik)) {
            return redirect()->route('error-403');
        }
        $id = Crypt::decrypt($id);
        // dd($id);
        $trx = TrxRecruitmentInterview::find($id);
        $_trx = TrxRecruitmentIdentitas::find($trx->id_identitas);
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        return view('recruitment.interview_item', compact('trx', '_trx', 'karyawan'));
    }

    public function atasan_save(Request $request)
    {
        $id_identitas = Crypt::decrypt($request->id_identitas);
        // dd($id_identitas, $request->all());
        $id_status = $request->id_status;
        $_trx = TrxRecruitmentIdentitas::find($id_identitas);
        $_trx->id_status = $id_status;
        $_trx->update();
        $trx = TrxKebutuhanMaster::find($_trx->id_kebutuhan);
        // $posisi = $trx->_pekerjaan->pekerjaan;
        $kebutuhan_atribute = json_decode($_trx->kebutuhan_atribute, true);
        $posisi = PekerjaanSub::find($kebutuhan_atribute['id_pekerjaan'])->pekerjaan;
        $nego = TrxRecruitmentNegosiasi::where(['id_identitas' => $id_identitas])->orderBy('id', 'desc')->first();
        $atasan = json_decode($trx->atasan_property, true);
        $nama = $atasan['nama'].'/'.$atasan['nik'];
        // 26=Approval By Leader
        $arr = [$id_identitas,$id_status,$nama,'','Approval By Leader',''];
        $this->insertLog($arr);
        // kirim email
        // 26=Approval By Leader
        if(in_array($id_status, [26])){
            $msg = '<p>Yth. '.$atasan['nama'].',<br/>Dengan hormat,</p>
            <p>Kami informasikan bahwa Proses Seleksi Penerimaan Karyawan untuk Formulir Kebutuhan Karyawan nomor '.$_trx->id_kebutuhan.' telah disetujui oleh atasan untuk calon karyawan atas nama berikut:</p>
            <p><table border="0" cellpadding="1" cellspacing="1">	
                <tr>
                    <td width="150">Nama</td>
                    <td width="5">:</td>
                    <td>'.$_trx->nama.'</td>
                </tr>
                <tr>
                    <td>Posisi</td>
                    <td>:</td>
                    <td>'.$posisi.'</td>
                </tr>
                <tr>
                    <td>Tanggal Bergabung</td>
                    <td>:</td>
                    <td>'.Carbon::parse($nego->tgl_bergabung)->format('d-m-Y').'</td>
                </tr>
            </table></p>
            <p>Demikian informasi ini kami sampaikan untuk diketahui. Jika terdapat perkembangan lebih lanjut, kami akan memberikan pembaruan.<br/>Terima kasih.</p>'.MtrUtility::find(47)->informasi;
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Atasan Menyetujui Proses Seleksi Penerimaan Karyawan';
            $mailData['data'] = ['msg'=>$msg];
            $mailData['filename'] = 'emails.recruitment0';
            $mailData['to'] = $atasan['email'];
            $this->deleteToDoList(['id_trx'=>$id_identitas, 'nik'=>$atasan['nik'], 'pic'=>'atasan', 'filename'=>'emails.recruitment0']);
            $this->sendEmail($mailData);
            return view('recruitment.thank', compact('msg'));
        }
    }

    public function negotiation_save(Request $request)
    {
        // $id = Crypt::decrypt($request->id);
        $id = $request->has('id') ? Crypt::decrypt($request->id) : null;
        // dd($id, $request->all());
        $id_identitas = Crypt::decrypt($request->id_identitas);
        $namafile='';
        if($request->hasFile('lampiran')){
            $lampiran = $request->file('lampiran');
            $request->validate([
                'lampiran' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);
            $extension = $lampiran->getClientOriginalExtension();
            $namafile = $id_identitas.'_'.now()->getTimestampMs().'.'.$extension;
            $lampiran->move('public/assets/recruitment/upload/', $namafile);
        }
        $id_status = $request->id_status;
        // dd($id_status);
        $_trx = TrxRecruitmentIdentitas::find($id_identitas);
        $_trx->id_status = $id_status;
        $_trx->update();
        $nama = $_trx->nama;
        if(Auth::check()){
            $nik = auth()->user()->nik;
            $nama = Karyawan::where(['nik'=>$nik])->first()->nama.'/'.$nik;
        }
        // 7=Magang dan 8=Konsultan
        if(!in_array($_trx->_kebutuhan->id_status_kerja, [7, 8])){
            $trx = TrxRecruitmentNegosiasi::find($id);
            $trx->id_status = $id_status;
            // 21=Re-Negotiation Process
            if($id_status == 21){
                $trx->thp = str_replace(',', '', $request->thp);
                $trx->benefit_utama = implode(',', $request->input('benefit_utama', []));
                $trx->benefit_lainnya = $request->benefit_lainnya;
                $trx->tgl_bergabung = $request->tgl_bergabung;
                $trx->id_catat_pegawai = $request->id_catat_pegawai;
                $trx->id_status_pegawai = $request->id_status_pegawai;
                $trx->masa_kerja = $request->masa_kerja;
            }
            $trx->update();
        }
        // start
        // id_identitas,id_status,pic,pic_property,aktifitas,catatan
        $arr = [$id_identitas,$id_status,$nama,'','negosiasi',''];
        // pic,is_hco,catatan,lampiran
        $data = [$nama, 0, $request->catatan, $namafile];
        if(Auth::check()){
            $data = [$nama, 1, $request->catatan, $namafile];
        }
        // jika diinput oleh kandidat maka pic menjadi nama kandidat dan is_hco=0
        // if(in_array($id_status, [17,18])){
        //     $data = array_replace($data, array(0=>$_trx->nama, 1=>0));
        //     $arr = array_replace($arr, array(2=>$_trx->nama));
        // }
        // 15=Recruitment Done
        // 7=Magang dan 8=Konsultan
        if($id_status != 15 && !in_array($_trx->_kebutuhan->id_status_kerja, [7, 8])){
            $this->insertNegotiationLog($trx, $data);
        }
        $this->insertLog($arr);
        // end
        // 13=Negotiation Deal by PC
        // 19=COC and File Upload Send
        // jika 13 maka otomatis berlanjut ke 19
        if($id_status == 13){
            $id_status = 19;
            $_trx->id_status = $id_status;
            $_trx->file_dokumen_ttd = $request->file_dokumen_ttd;
            $_trx->update();
            // 7=Magang dan 8=Konsultan
            if(!in_array($_trx->_kebutuhan->id_status_kerja, [7, 8])){
                $trx->id_status = $id_status;
                $trx->update();
            }
            // insert to TrxRecruitmentPedoman
            $generik = ['matta_page-0001','matta_page-0002','matta_page-0003','matta_page-0004'];
            // matta_page-0001 = 5eaeebe46db8e8134009d0e8ece5640f
            // matta_page-0002 = 84abe3e07dd0708cf2d8a3a9befcbda2
            // matta_page-0003 = 736ee712f8d25b71dcdbf71ffe54fb0e
            // matta_page-0004 = 29e863789d082ab31839c101f40a028a
            for($x = 0; $x < 4; $x++){
                $pedoman = new TrxRecruitmentPedoman;
                $pedoman->id_identitas = $id_identitas;
                $pedoman->jenis = 'generik';
                $pedoman->nomor = $x+1;
                $pedoman->filename = md5($generik[$x]);
                $pedoman->save();
            }
            // tidak digunakan ref to 488
            // $general = ['general_page-0001','general_page-0002','general_page-0003','general_page-0004','general_page-0005','general_page-0006'];
            // $leader = ['leader_page-0001','leader_page-0002','leader_page-0003','leader_page-0004','leader_page-0005','leader_page-0006'];
            // for($x = 0; $x < 6; $x++){
            //     $pedoman = new TrxRecruitmentPedoman;
            //     $pedoman->id_identitas = $id_identitas;
            //     $pedoman->jenis = 'general';
            //     $pedoman->nomor = $x+1;
            //     $pedoman->filename = md5($general[$x]);
            //     if($request->file_dokumen_ttd == 'leader.pdf'){
            //         $pedoman->jenis = 'leader';
            //         $pedoman->nomor = $x+1;
            //         $pedoman->filename = md5($leader[$x]);
            //     }
            //     $pedoman->save();
            // }
            // start
            // id_identitas,id_status,pic,pic_property,aktifitas,catatan
            // $arr = [$id_identitas,$id_status,$nama.'/'.$nik,'','negosiasi',''];
            $arr = [$id_identitas,$id_status,$nama,'','negosiasi',''];
            $this->insertLog($arr);
            // end
            
        }
        // kirim email
        $info = '';
        // 17=Negotiation Deal by Candidate
        if($id_status == 17){
            $info = 'Proses Negosiasi Disetujui Kandidat';
        }
        // 18=Negotiation No Deal by Candidate
        elseif($id_status == 18){
            $info = 'Proses Negosiasi Tidak Disetujui Kandidat';
        }
        // 13=Negotiation Deal by PC
        elseif($id_status == 13){
            $info = 'Proses Negosiasi Disetujui '.MtrUtility::find(51)->informasi;
        }
        // 14=Negotiation No Deal by PC
        elseif($id_status == 14){
            $info = 'Proses Negosiasi Tidak Disetujui '.MtrUtility::find(51)->informasi;
        }
        // 19=COC and File Upload Send
        elseif($id_status == 19){
            $info = 'Kelengkapan Dokumen Karyawan Baru';
        }
        // 21=Re-Negotiation Process
        elseif($id_status == 21){
            $info = 'Proses Negosiasi Kembali';
        }
        // 17=Negotiation Deal by Candidate
        // 18=Negotiation No Deal by Candidate
        if(in_array($id_status, [17,18])){
            $msg = '<p>Yth. '.$_trx->nama.',<br/>Dengan Hormat,</p>
            <p>Kami ingin menginformasikan bahwa proses negosiasi dengan Bapak/Ibu/Saudara/i '.$_trx->nama.' telah disetujui. Kandidat menyatakan kesediaannya untuk menerima ketentuan yang telah disepakati.</p>
            <p>Kami akan melanjutkan ke tahap berikutnya sesuai dengan prosedur yang berlaku. Apabila ada hal lain yang perlu ditanyakan, mohon disampaikan kepada kami.</p>
            <p>Terima kasih,</p>'.MtrUtility::find(47)->informasi;
            // 18=Negotiation No Deal by Candidate
            if($id_status == 18){
                $msg = '<p>Yth. '.$_trx->nama.',<br/>Dengan Hormat,</p>
                <p>Kami ingin menginformasikan bahwa proses negosiasi dengan Bapak/Ibu/Saudara/i '.$_trx->nama.' belum mencapai kesepakatan. Kandidat menyatakan ketidaksediaannya untuk menerima ketentuan yang telah diajukan.</p>
                <p>Kami akan meninjau kembali langkah selanjutnya terkait proses ini dan segera memberikan pembaharuan apabila diperlukan. Jika ada saran atau hal lain yang perlu dipertimbangkan, kami siap untuk mendiskusikannya lebih lanjut.</p>
                <p>Terima kasih,</p>'.MtrUtility::find(47)->informasi;
            }
            // parameter yang dipassing ke sendemail
            $mailData['title'] = $info;
            $mailData['data'] = ['msg'=>$msg];
            $mailData['filename'] = 'emails.recruitment0';
            $mailData['to'] = $_trx->email;
            $this->sendEmail($mailData);
            return view('recruitment.thank', compact('msg'));
        }
        // 14=Negotiation No Deal by PC
        elseif($id_status == 14){
            // $msg = '<p>Hai '.$_trx->nama.',<br/>Mohon maaf, '.$info.'.
            // <br/>Terima kasih,</p>'.MtrUtility::find(47)->informasi;
            // // parameter yang dipassing ke sendemail
            // $mailData['title'] = $info;
            // $mailData['data'] = ['msg'=>$msg];
            // $mailData['filename'] = 'emails.recruitment0';
            // $mailData['to'] = $_trx->email;
            // $this->sendEmail($mailData);
            return redirect('recruitment/applicant_list/0')->with('msg', 'Data is saved successfully.');
        }
        // 19=COC and File Upload Send
        elseif($id_status == 19){
            // parameter yang dipassing ke sendemail
            $mailData['title'] = $info;
            $mailData['filename'] = 'emails.recruitment4';
            $mailData['data'] = ['trx' => $_trx];
            $mailData['to'] = $_trx->email;
            $this->sendEmail($mailData);
            return redirect('recruitment/applicant_list/0')->with('msg', 'Data is saved successfully.');
        }
        // 21=Re-Negotiation Process
        elseif($id_status == 21){
            $_trx_ = TrxKebutuhanMaster::find($_trx->id_kebutuhan);
            // $posisi = $_trx_->_pekerjaan->pekerjaan;
            $kebutuhan_atribute = json_decode($_trx->kebutuhan_atribute, true);
            $posisi = PekerjaanSub::find($kebutuhan_atribute['id_pekerjaan'])->pekerjaan;
            // parameter yang dipassing ke sendemail
            $mailData['title'] = $info;
            $mailData['filename'] = 'emails.recruitment3';
            $mailData['data'] = ['trx' => $_trx, 'posisi' => $posisi];
            $mailData['to'] = $_trx->email;
            $this->sendEmail($mailData);
            return redirect('recruitment/applicant_list/0')->with('msg', 'Data is saved successfully.');
        }
        // 15=Recruitment Done
        // elseif($id_status == 15){
        //     return redirect('recruitment/applicant_list/0')->with('msg', 'Data is saved successfully.');
        // }
    }

    public function negotiation_process($id)
    {
        $id = Crypt::decrypt($id)[0];
        // dd($id);
        $_trx = TrxRecruitmentIdentitas::find($id);
        $trxM = TrxKebutuhanMaster::find($_trx->id_kebutuhan);
        // 12=Negotiation Process
        // 21=Re-Negotiation Process
        if(in_array($_trx->id_status, [12,21])){
            $trx = TrxRecruitmentNegosiasi::where(['id_identitas'=>$id])->first();
            $log = TrxRecruitmentNegosiasiLog::where(['id_identitas'=>$id])->whereIn('id_status', [12,21])->orderBy('id', 'desc')->first();
            return view('recruitment.negotiation_process', compact('trx', 'log', '_trx', 'trxM'));
        }else{
            $msg = '<p>Yth. '.$_trx->nama.',<br/>Dengan Hormat,</p>
            <p>Proses saat ini tidak/belum tersedia.</p>
            <p>Terima kasih,</p>'.MtrUtility::find(47)->informasi;
            return view('recruitment.thank', compact('msg'));
        }
    }

    public function pedoman_post(Request $request)
    {
        $id = Crypt::decrypt($request->id);
        $_trx = TrxRecruitmentIdentitas::find($id);
        $nomor = $request->nomor;
        $url = $request->url;
        // dd($id, $request->all());
        $where = ['id_identitas' => $id, 'nomor' => $nomor];
        $cek_satu = TrxRecruitmentPedoman::where($where)->whereNull('check_in')->exists();
        if($cek_satu){
            $trx = TrxRecruitmentPedoman::where($where)->first();
            $trx->check_in = Carbon::now();
            $trx->update();
        }else{
            $trx = TrxRecruitmentPedoman::where($where)->first();
            $trx->check_out = Carbon::now();
            $trx->update();
            $where = ['id_identitas' => $id, 'nomor' => $nomor+1];
            // update nomor berikutnya
            $trx = TrxRecruitmentPedoman::where($where)->first();
            if(!empty($trx)){
                $trx->check_in = Carbon::now();
                $trx->update();
            }
        }
        return redirect()->to($url);
    }

    public function upload_dokumen_post(Request $request)
    {
        $id = Crypt::decrypt($request->id);
        $id_status = $request->id_status;
        // dd($id, $request->all());
        $_trx = TrxRecruitmentIdentitas::find($id);
        $namafile='';
        if($request->hasFile('file_sertifikat_keahlian')){
            $file_sertifikat_keahlian = $request->file('file_sertifikat_keahlian');
            $request->validate([
                'file_sertifikat_keahlian' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);
            $extension = $file_sertifikat_keahlian->getClientOriginalExtension();
            $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
            $file_sertifikat_keahlian->move('public/assets/recruitment/upload/', $namafile);
            $_trx->file_sertifikat_keahlian = $namafile;
            if($request->upload_by == 'hco'){
                $_trx->update();
                $filename = $request->filename;
                // 20=COC and File Upload Done
                $arr = [$_trx->id,20,auth()->user()->name.'/'.auth()->user()->nik,'',$filename,''];
                $this->insertLog($arr);
                return redirect()->to($request->back_url)->with('msg', 'Data is saved successfully.');
            }
        }
        if($request->hasFile('file_ktp')){
            $file_ktp = $request->file('file_ktp');
            $request->validate([
                'file_ktp' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);
            $extension = $file_ktp->getClientOriginalExtension();
            $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
            $file_ktp->move('public/assets/recruitment/upload/', $namafile);
            $_trx->file_ktp = $namafile;
            if($request->upload_by == 'hco'){
                $_trx->update();
                $filename = $request->filename;
                // 20=COC and File Upload Done
                $arr = [$_trx->id,20,auth()->user()->name.'/'.auth()->user()->nik,'',$filename,''];
                $this->insertLog($arr);
                return redirect()->to($request->back_url)->with('msg', 'Data is saved successfully.');
            }
        }
        if($request->hasFile('file_kk')){
            $file_kk = $request->file('file_kk');
            $request->validate([
                'file_kk' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);
            $extension = $file_kk->getClientOriginalExtension();
            $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
            $file_kk->move('public/assets/recruitment/upload/', $namafile);
            $_trx->file_kk = $namafile;
            if($request->upload_by == 'hco'){
                $_trx->update();
                $filename = $request->filename;
                // 20=COC and File Upload Done
                $arr = [$_trx->id,20,auth()->user()->name.'/'.auth()->user()->nik,'',$filename,''];
                $this->insertLog($arr);
                return redirect()->to($request->back_url)->with('msg', 'Data is saved successfully.');
            }
        }
        if($request->hasFile('file_npwp')){
            $file_npwp = $request->file('file_npwp');
            $request->validate([
                'file_npwp' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);
            $extension = $file_npwp->getClientOriginalExtension();
            $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
            $file_npwp->move('public/assets/recruitment/upload/', $namafile);
            $_trx->file_npwp = $namafile;
            if($request->upload_by == 'hco'){
                $_trx->update();
                $filename = $request->filename;
                // 20=COC and File Upload Done
                $arr = [$_trx->id,20,auth()->user()->name.'/'.auth()->user()->nik,'',$filename,''];
                $this->insertLog($arr);
                return redirect()->to($request->back_url)->with('msg', 'Data is saved successfully.');
            }
        }
        if($request->hasFile('file_bpjs_tk')){
            $file_bpjs_tk = $request->file('file_bpjs_tk');
            $request->validate([
                'file_bpjs_tk' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);
            $extension = $file_bpjs_tk->getClientOriginalExtension();
            $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
            $file_bpjs_tk->move('public/assets/recruitment/upload/', $namafile);
            $_trx->file_bpjs_tk = $namafile;
            if($request->upload_by == 'hco'){
                $_trx->update();
                $filename = $request->filename;
                // 20=COC and File Upload Done
                $arr = [$_trx->id,20,auth()->user()->name.'/'.auth()->user()->nik,'',$filename,''];
                $this->insertLog($arr);
                return redirect()->to($request->back_url)->with('msg', 'Data is saved successfully.');
            }
        }
        if($request->hasFile('file_bpjs_kes')){
            $file_bpjs_kes = $request->file('file_bpjs_kes');
            $request->validate([
                'file_bpjs_kes' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);
            $extension = $file_bpjs_kes->getClientOriginalExtension();
            $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
            $file_bpjs_kes->move('public/assets/recruitment/upload/', $namafile);
            $_trx->file_bpjs_kes = $namafile;
            if($request->upload_by == 'hco'){
                $_trx->update();
                $filename = $request->filename;
                // 20=COC and File Upload Done
                $arr = [$_trx->id,20,auth()->user()->name.'/'.auth()->user()->nik,'',$filename,''];
                $this->insertLog($arr);
                return redirect()->to($request->back_url)->with('msg', 'Data is saved successfully.');
            }
        }
        if($request->hasFile('file_buku_tabungan')){
            $file_buku_tabungan = $request->file('file_buku_tabungan');
            $request->validate([
                'file_buku_tabungan' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);
            $extension = $file_buku_tabungan->getClientOriginalExtension();
            $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
            $file_buku_tabungan->move('public/assets/recruitment/upload/', $namafile);
            $_trx->file_buku_tabungan = $namafile;
            if($request->upload_by == 'hco'){
                $_trx->update();
                $filename = $request->filename;
                // 20=COC and File Upload Done
                $arr = [$_trx->id,20,auth()->user()->name.'/'.auth()->user()->nik,'',$filename,''];
                $this->insertLog($arr);
                return redirect()->to($request->back_url)->with('msg', 'Data is saved successfully.');
            }
        }
        if($request->hasFile('file_ket_sehat')){
            $file_ket_sehat = $request->file('file_ket_sehat');
            $request->validate([
                'file_ket_sehat' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);
            $extension = $file_ket_sehat->getClientOriginalExtension();
            $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
            $file_ket_sehat->move('public/assets/recruitment/upload/', $namafile);
            $_trx->file_ket_sehat = $namafile;
            if($request->upload_by == 'hco'){
                $_trx->update();
                $filename = $request->filename;
                // 20=COC and File Upload Done
                $arr = [$_trx->id,20,auth()->user()->name.'/'.auth()->user()->nik,'',$filename,''];
                $this->insertLog($arr);
                return redirect()->to($request->back_url)->with('msg', 'Data is saved successfully.');
            }
        }
        if($request->hasFile('file_skck')){
            $file_skck = $request->file('file_skck');
            $request->validate([
                'file_skck' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);
            $extension = $file_skck->getClientOriginalExtension();
            $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
            $file_skck->move('public/assets/recruitment/upload/', $namafile);
            $_trx->file_skck = $namafile;
            if($request->upload_by == 'hco'){
                $_trx->update();
                $filename = $request->filename;
                // 20=COC and File Upload Done
                $arr = [$_trx->id,20,auth()->user()->name.'/'.auth()->user()->nik,'',$filename,''];
                $this->insertLog($arr);
                return redirect()->to($request->back_url)->with('msg', 'Data is saved successfully.');
            }
        }
        if($request->hasFile('file_ijazah')){
            $file_ijazah = $request->file('file_ijazah');
            $request->validate([
                'file_ijazah' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);
            $extension = $file_ijazah->getClientOriginalExtension();
            $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
            $file_ijazah->move('public/assets/recruitment/upload/', $namafile);
            $_trx->file_ijazah = $namafile;
            if($request->upload_by == 'hco'){
                $_trx->update();
                $filename = $request->filename;
                // 20=COC and File Upload Done
                $arr = [$_trx->id,20,auth()->user()->name.'/'.auth()->user()->nik,'',$filename,''];
                $this->insertLog($arr);
                return redirect()->to($request->back_url)->with('msg', 'Data is saved successfully.');
            }
        }
        if($request->hasFile('file_covid')){
            $file_covid = $request->file('file_covid');
            $request->validate([
                'file_covid' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);
            $extension = $file_covid->getClientOriginalExtension();
            $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
            $file_covid->move('public/assets/recruitment/upload/', $namafile);
            $_trx->file_covid = $namafile;
            if($request->upload_by == 'hco'){
                $_trx->update();
                $filename = $request->filename;
                // 20=COC and File Upload Done
                $arr = [$_trx->id,20,auth()->user()->name.'/'.auth()->user()->nik,'',$filename,''];
                $this->insertLog($arr);
                return redirect()->to($request->back_url)->with('msg', 'Data is saved successfully.');
            }
        }
        // tidak digunakan, diganti dengan dibuat persetujuan dalam setiap halaman setelah kandidat membaca
        // if($request->hasFile('file_dokumen_ttd')){
        //     $file_dokumen_ttd = $request->file('file_dokumen_ttd');
        //     $request->validate([
        //         'file_dokumen_ttd' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
        //     ]);
        //     $extension = $file_dokumen_ttd->getClientOriginalExtension();
        //     $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
        //     $file_dokumen_ttd->move('public/assets/recruitment/upload/', $namafile);
        //     $_trx->file_dokumen_ttd = $namafile;
        // }
        // start generate pdf then save to public_path
        $where = ['id_identitas' => $id];
        $pedoman = TrxRecruitmentPedoman::where($where)->orderBy('nomor', 'asc')->get();
        $filename = $id.'_'.now()->getTimestampMs().'.pdf';
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle('Pedoman Perilaku');
        $pdf::SetFont('times', '', 10, '', true);
        foreach($pedoman as $data){
            $pdf::AddPage();
            $pdf::SetAutoPageBreak(false, 0);
            $bg = public_path('assets/recruitment/upload/'.$data->jenis.'/'.$data->filename.'.jpg');
            // $pdf::Image($bg, 0, 0, 210, 300, '', '', '', false, 0, '', false, false, 0);
            $pdf::Image($bg, 0, 0, 210, 0, '', '', '', false, 300, '', false, false, 0);
            $bg = public_path('assets/images/matta/approved_new.png');
            $html = '<div style="text-align:left;">
                <table>
                    <tr>
                        <td width="5%">&nbsp;</td>
                        <td width="90%"><img src="'.$bg.'" alt="image" height="15"><br/><span style="font-size: 6px;">'.$_trx->nama.'<br/>'.Carbon::parse($data->check_out)->format('d-m-Y H:i:s').'</span></td>
                        <td width="5%">&nbsp;</td>
                    </tr>
            </table></div>';
            $x = 5;
            $y = 250;
            $pdf::writeHTMLCell(0, 0, $x, $y, $html, 0, 1, 0, true, '', true);
            // $pdf::Image($bg, $x, $y, 25, 5, '', '', '', false, 0, '', false, false, 0);
            // $pdf::Text($x, $y+6, $_trx->nama);
            // $pdf::Text($x, $y+10, Carbon::parse($data->check_out)->format('d-m-Y H:i:s'));
        }
        $pdf::Output(public_path('assets/recruitment/upload/'.$filename), 'F');
        // 
        // $pdf::Output(public_path('assets/recruitment/upload/'.$filename), 'I');
        // return response()->download(public_path($filename));
        // 
        $_trx->file_dokumen_ttd = $filename;
        // end
        $_trx->id_status = $id_status;
        $_trx->update();
        // start
        // id_identitas,id_status,pic,pic_property,aktifitas,catatan
        $arr = [$_trx->id,$_trx->id_status,$_trx->nama,'','upload',''];
        $this->insertLog($arr);
        // end
        // kirim email
        $msg = '<p>Yth. '.$_trx->nama.',<br/>Dengan Hormat,</p>
        <p>Kami informasikan bahwa proses upload dokumen telah selesai dilakukan. Seluruh dokumen Bapak/Ibu/Saudara/i '.$_trx->nama.' telah berhasil kami terima sesuai kebutuhan.</p>
        <p>Apabila terdapat hal lain yang perlu dikonfirmasikan kembali, mohon menghubungi kami kembali.</p>
        <p>Terima kasih,</p>'.MtrUtility::find(47)->informasi;
        // parameter yang dipassing ke sendemail
        $mailData['title'] = 'Upload Dokumen Selesai';
        $mailData['data'] = ['msg'=>$msg];
        $mailData['filename'] = 'emails.recruitment0';
        $mailData['to'] = $_trx->email;
        $this->sendEmail($mailData);
        return view('recruitment.thank', compact('msg'));
    }

    public function get_interpretation($id)
    {
        $id = Crypt::decrypt($id);
        // dd($id);
        $_id = $id[0];
        $_trx = TrxRecruitmentIdentitas::find($_id);
        $where = ['id_identitas' => $_id];
        $resume = TrxRecruitmentResume::where($where)->orderBy('id', 'asc')->get();
        // dd($_trx);
        return view('recruitment.get_interpretation', compact('_trx', 'resume'));
    }

    public function download_interpretation($id)
    {
        $id = Crypt::decrypt($id);
        // dd($id);
        $trx = TrxRecruitmentIdentitas::find($id);
        $where = ['id_identitas' => $id];
        $resume = TrxRecruitmentResume::where($where)->orderBy('id', 'asc')->get();
        $interview = TrxRecruitmentInterview::where($where)->orderBy('id', 'desc')->get();
        $filename = $id . '_resume.pdf';
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle('Resume Hasil Tes');
        $pdf::SetFont('times', '', 11, '', true);
        // page-1
        $pdf::AddPage();
        $html = \View::make('recruitment.pdf_resume', compact('trx', 'resume', 'interview'))->render();
        $pdf::writeHTML($html, true, false, true, false, '');
        // page-2
        $pdf::AddPage();
        $html = \View::make('recruitment.pdf_resume2', compact('trx', 'resume', 'interview'))->render();
        $pdf::writeHTML($html, true, false, true, false, '');
        // 
        $pdf::Output(public_path($filename), 'I');
        return response()->download(public_path($filename));
    }

    public function pdf_interpretation($id, $filename)
    {
        // dd($id);
        $trx = TrxRecruitmentIdentitas::find($id);
        $where = ['id_identitas' => $id];
        $resume = TrxRecruitmentResume::where($where)->orderBy('id', 'asc')->get();
        $interview = TrxRecruitmentInterview::where($where)->orderBy('id', 'desc')->get();
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle('Resume Hasil Tes');
        $pdf::SetFont('times', '', 11, '', true);
        // page-1
        $pdf::AddPage();
        $html = \View::make('recruitment.pdf_resume', compact('trx', 'resume', 'interview'))->render();
        $pdf::writeHTML($html, true, false, true, false, '');
        // page-2
        $pdf::AddPage();
        $html = \View::make('recruitment.pdf_resume2', compact('trx', 'resume', 'interview'))->render();
        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output(public_path('assets/kebutuhan/'.$filename), 'F');
        $pdf::reset();
    }

    public function get_offering($id)
    {
        $_id = Crypt::decrypt($id);
        // dd($_id);
        $id = $_id[0];
        $_trx = TrxRecruitmentIdentitas::find($id);
        $trxM = TrxKebutuhanMaster::find($_trx->id_kebutuhan);
        $trx = TrxRecruitmentNegosiasi::where(['id_identitas'=>$id])->orderBy('id', 'desc')->first();
        if($_trx->id_status == 26 && !empty($_id[2])){
            // $posisi = $trxM->_pekerjaan->pekerjaan;
            $kebutuhan_atribute = json_decode($_trx->kebutuhan_atribute, true);
            $posisi = PekerjaanSub::find($kebutuhan_atribute['id_pekerjaan'])->pekerjaan;
            $atasan = json_decode($trxM->atasan_property, true);
            $msg = '<p>Yth. '.$atasan['nama'].',<br/>Dengan hormat,</p>
            <p>Kami informasikan bahwa Proses Seleksi Penerimaan Karyawan untuk Formulir Kebutuhan Karyawan nomor '.$_trx->id_kebutuhan.' telah disetujui oleh atasan untuk calon karyawan atas nama berikut:</p>
            <p><table border="0" cellpadding="1" cellspacing="1">	
                <tr>
                    <td width="150">Nama</td>
                    <td width="5">:</td>
                    <td>'.$_trx->nama.'</td>
                </tr>
                <tr>
                    <td>Posisi</td>
                    <td>:</td>
                    <td>'.$posisi.'</td>
                </tr>
                <tr>
                    <td>Tanggal Bergabung</td>
                    <td>:</td>
                    <td>'.Carbon::parse($trx->tgl_bergabung)->format('d-m-Y').'</td>
                </tr>
            </table></p>
            <p>Demikian informasi ini kami sampaikan untuk diketahui. Jika terdapat perkembangan lebih lanjut, kami akan memberikan pembaruan.<br/>Terima kasih.</p>'.MtrUtility::find(47)->informasi;
            return view('recruitment.thank', compact('msg'));
        }
        return view('recruitment.get_offering', compact('trx', '_trx', 'trxM', '_id'));
    }

    public function download_offering($id)
    {
        $id = Crypt::decrypt($id);
        // dd($id);
        $_trx = TrxRecruitmentIdentitas::find($id);
        $trxM = TrxKebutuhanMaster::find($_trx->id_kebutuhan);
        $trx = TrxRecruitmentNegosiasi::where(['id_identitas'=>$id])->first();
        $filename = $id . '_offering.pdf';
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle('Offering Letter');
        $pdf::SetFont('times', '', 10, '', true);
        $pdf::AddPage();
        $html = \View::make('recruitment.pdf_offering', compact('trx', '_trx', 'trxM'))->render();
        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output(public_path($filename), 'I');
        return response()->download(public_path($filename));
    }

    public function pdf_offering($id, $filename)
    {
        // dd($id);
        $_trx = TrxRecruitmentIdentitas::find($id);
        $trxM = TrxKebutuhanMaster::find($_trx->id_kebutuhan);
        $trx = TrxRecruitmentNegosiasi::where(['id_identitas'=>$id])->first();
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle('Offering Letter');
        $pdf::SetFont('times', '', 10, '', true);
        $pdf::AddPage();
        $html = \View::make('recruitment.pdf_offering', compact('trx', '_trx', 'trxM'))->render();
        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output(public_path('assets/kebutuhan/'.$filename), 'F');
        $pdf::reset();
    }

    public function upload_dokumen_get($id)
    {
        $id = Crypt::decrypt($id);
        // 
        // // start generate pdf then save to public_path
        // $id = $id[0];
        // $_trx = TrxRecruitmentIdentitas::find($id);
        // $where = ['id_identitas' => $id];
        // $pedoman = TrxRecruitmentPedoman::where($where)->orderBy('nomor', 'asc')->get();
        // $filename = $id.'_'.now()->getTimestampMs().'.pdf';
        // $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        // $pdf::SetTitle('Pedoman Perilaku');
        // $pdf::SetFont('times', '', 10, '', true);
        // foreach($pedoman as $data){
        //     $pdf::AddPage();
        //     $pdf::SetAutoPageBreak(false, 0);
        //     $bg = public_path('assets/recruitment/upload/'.$data->jenis.'/'.$data->filename.'.jpg');
        //     $pdf::Image($bg, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        //     // $pdf::Image($bg, 10, 1, 210, 297, '', '', '', false, 0, '', false, false, 0);
        //     if($data->nomor == 4){
        //         $bg = public_path('assets/images/matta/approved_new.png');
        //         $x = 90;
        //         $y = 120;
        //         $pdf::Image($bg, $x, $y, 30, 6, '', '', '', false, 0, '', false, false, 0);
        //         $pdf::Text($x, $y+6, $_trx->nama);
        //         $pdf::Text($x, $y+10, Carbon::parse($data->check_out)->format('d-m-Y H:i:s'));
        //     }
        // }
        // $pdf::Output(public_path('assets/recruitment/upload/'.$filename), 'F');
        // // $pdf::Output($filename, 'I');
        // // end
        // dd($id);
        $_id = $id[0];
        // ada 2 kondisi yang bisa didapat pada array index-1
        // yaitu negotiation untuk kandidiat upload file
        // dan view untuk lihat file yang diupload
        $kondisi = $id[1];
        $_trx = TrxRecruitmentIdentitas::find($_id);
        // start
        // $where = ['id_identitas' => $_id];
        // $pedoman = TrxRecruitmentPedoman::where($where)->orderBy('nomor', 'asc')->get();
        // $filename = $_id.'_'.now()->getTimestampMs().'.pdf';
        // $pdf = new TCPDF('P', 'mm', 'A4', false, 'UTF-8', true);
        // $pdf::SetTitle('Pedoman Perilaku');
        // $pdf::SetFont('times', '', 10, '', true);
        // // $pdf::setImageScale(100);
        // $pdf::SetMargins(0, 0, 0);
        // $pdf::SetHeaderMargin(0);
        // $pdf::SetFooterMargin(0);
        // foreach($pedoman as $data){
        //     $pdf::AddPage();
        //     $bg = public_path('assets/recruitment/upload/'.$data->jenis.'/'.$data->filename.'.jpg');
        //     // Image( filename, left, top, width, height, type, link, align, resize, dpi, align, ismask, imgmask, border, fitbox, hidden, fitonpage)
        //     // $pdf::Image($bg, 0, 0, 210, 297, '', '', '', false, 0, '', false, false, 0);
        //     $pdf::Image($bg, $x = 0, $y = 0, $w = 210, $h = 297, $type = '', $link = '', $align = '', $resize = false, $dpi = 300, $palign = '', $ismask = false, $imgmask = false, $border = 0, $fitbox = false, $hidden = false, $fitonpage = false, $alt = false, $altimgs = array());
        //     $bg = public_path('assets/images/matta/approved_new.png');
        //     $x = 90;
        //     $y = 262;
        //     $pdf::Image($bg, $x, $y, 30, 6, '', '', '', false, 0, '', false, false, 0);
        //     $pdf::Text($x, $y+6, $_trx->nama);
        //     $pdf::Text($x, $y+10, Carbon::parse($data->check_out)->format('d-m-Y H:i:s'));
        // }
        // $pdf::Output(public_path($filename), 'I');
        // return response()->download(public_path($filename));
        // end
        // negotiation
        if($kondisi == 'negotiation'){
            // 19=COC and File Upload Send
            if($_trx->id_status == 19){
                return view('recruitment.upload_dokumen', compact('_trx'));
            }else{
                $msg = '<p>Yth. '.$_trx->nama.',<br/>Dengan Hormat,</p>
                <p>Proses saat ini tidak/belum tersedia.</p>
                <p>Terima kasih,</p>'.MtrUtility::find(47)->informasi;
                return view('recruitment.thank', compact('msg'));
            }
        }
        // view
        elseif($kondisi == 'view'){
            return view('recruitment.upload_dokumen', compact('_trx'));
        }
    }

    public function upload_psikotest($id)
    {
        $allowed_nik = ['P0826165'];
        if (!auth()->user()->can('hc') && !in_array(auth()->user()->nik, $allowed_nik)) {
            return redirect()->route('error-403');
        }
        $id = Crypt::decrypt($id);
        $_id = $id[0];
        $kondisi = $id[1];
        $_trx = TrxRecruitmentIdentitas::find($_id);
        // upload
        if($kondisi == 'upload'){
            return view('recruitment.upload_psikotest', compact('_trx'));
        }
    }

    public function upload_psikotest_post(Request $request)
    {
        $allowed_nik = ['P0826165'];
        if (!auth()->user()->can('hc') && !in_array(auth()->user()->nik, $allowed_nik)) {
            return redirect()->route('error-403');
        }
        $id = Crypt::decrypt($request->id);
        $_trx = TrxRecruitmentIdentitas::find($id);
        if($request->hasFile('file_epps')){
            $file_epps = $request->file('file_epps');
            $request->validate([
                'file_epps' => 'file|mimes:xls,xlsx|max:5120',
            ]);
            $extension = $file_epps->getClientOriginalExtension();
            $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
            $file_epps->move('public/assets/recruitment/upload/', $namafile);
            $_trx->file_epps = $namafile;
            $_trx->update();
        }
        if($request->hasFile('file_papi')){
            $file_papi = $request->file('file_papi');
            $request->validate([
                'file_papi' => 'file|mimes:xls,xlsx|max:5120',
            ]);
            $extension = $file_papi->getClientOriginalExtension();
            $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
            $file_papi->move('public/assets/recruitment/upload/', $namafile);
            $_trx->file_papi = $namafile;
            $_trx->update();
        }
        if($request->hasFile('file_disc')){
            $file_disc = $request->file('file_disc');
            $request->validate([
                'file_disc' => 'file|mimes:xls,xlsx|max:5120',
            ]);
            $extension = $file_disc->getClientOriginalExtension();
            $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
            $file_disc->move('public/assets/recruitment/upload/', $namafile);
            $_trx->file_disc = $namafile;
            $_trx->update();
        }
        if($request->hasFile('file_baum')){
            $file_baum = $request->file('file_baum');
            $request->validate([
                'file_baum' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);
            $extension = $file_baum->getClientOriginalExtension();
            $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
            $file_baum->move('public/assets/recruitment/upload/', $namafile);
            $_trx->file_baum = $namafile;
            $_trx->update();
        }
        if($request->hasFile('file_dap')){
            $file_dap = $request->file('file_dap');
            $request->validate([
                'file_dap' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);
            $extension = $file_dap->getClientOriginalExtension();
            $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
            $file_dap->move('public/assets/recruitment/upload/', $namafile);
            $_trx->file_dap = $namafile;
            $_trx->update();
        }
        return redirect()->to($request->back_url)->with('msg', 'Data is saved successfully.');
    }

    public function insertNegotiationLog($_trx, $data)
    {
        $trx = new TrxRecruitmentNegosiasiLog;
        $trx->id_identitas = $_trx->id_identitas;
        $trx->id_status = $_trx->id_status;
        $trx->posisi = $_trx->posisi;
        $trx->area_kerja = $_trx->area_kerja;
        $trx->divisi = $_trx->divisi;
        $trx->thp = $_trx->thp;
        $trx->benefit_utama = $_trx->benefit_utama;
        $trx->benefit_lainnya = $_trx->benefit_lainnya;
        $trx->tgl_bergabung = $_trx->tgl_bergabung;
        $trx->id_catat_pegawai = $_trx->id_catat_pegawai;
        $trx->id_status_pegawai = $_trx->id_status_pegawai;
        $trx->masa_kerja = $_trx->masa_kerja;
        $trx->pic = $data[0];
        $trx->is_hco = $data[1];
        $trx->catatan = $data[2];
        $trx->lampiran = $data[3];
        $trx->save();
    }

    public function negotiation_send(Request $request)
    {
        $nik = auth()->user()->nik;
        $nama = Karyawan::where(['nik'=>$nik])->first()->nama;
        // dd($request->all());
        $id = Crypt::decrypt($request->id);
        // dd($id);
        $id_status = $request->id_status;
        $namafile='';
        if($request->hasFile('lampiran')){
            $lampiran = $request->file('lampiran');
            $request->validate([
                'lampiran' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);
            $extension = $lampiran->getClientOriginalExtension();
            $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
            $lampiran->move('public/assets/recruitment/upload/', $namafile);
            // array_push($arr, $namafile);
        }
        $trx = new TrxRecruitmentNegosiasi;
        $trx->id_identitas = $id;
        $trx->id_status = $id_status;
        $trx->posisi = $request->posisi;
        $trx->area_kerja = $request->area_kerja;
        $trx->divisi = $request->divisi;
        $trx->thp = str_replace(',', '', $request->thp);
        $trx->benefit_utama = implode(',', $request->input('benefit_utama', []));
        $trx->benefit_lainnya = $request->benefit_lainnya;
        $trx->tgl_bergabung = $request->tgl_bergabung;
        $trx->id_catat_pegawai = $request->id_catat_pegawai;
        $trx->id_status_pegawai = $request->id_status_pegawai;
        $trx->masa_kerja = $request->masa_kerja;
        $trx->save();
        // 
        // pic,is_hco,catatan,lampiran
        $data = [$nama.'/'.$nik, 1, $request->catatan, $namafile];
        $this->insertNegotiationLog($trx, $data);
        // 
        $trx = TrxRecruitmentIdentitas::find($id);
        $trx->id_status = $id_status;
        $trx->update();
        // 
        $_trx = TrxKebutuhanMaster::find($trx->id_kebutuhan);
        // $posisi = $_trx->_pekerjaan->pekerjaan;
        $kebutuhan_atribute = json_decode($trx->kebutuhan_atribute, true);
        $posisi = PekerjaanSub::find($kebutuhan_atribute['id_pekerjaan'])->pekerjaan;
        // parameter yang dipassing ke sendemail
        $mailData['title'] = 'Proses Negosiasi';
        $mailData['filename'] = 'emails.recruitment3';
        $mailData['data'] = ['trx' => $trx, 'posisi' => $posisi];
        $mailData['to'] = $trx->email;
        $this->sendEmail($mailData);
        // start
        // id_identitas,id_status,pic,pic_property,aktifitas,catatan
        $arr = [$trx->id,$id_status,$nama.'/'.$nik,'','negosiasi',''];
        $this->insertLog($arr);
        return redirect('recruitment/applicant_list/0')->with('msg', 'Data is saved successfully.');
    }

    public function interview_save(Request $request)
    {
        // dd($request->all());
        $allowed_nik = ['P0826165'];
        if (!auth()->user()->can('hc') && !in_array(auth()->user()->nik, $allowed_nik)) {
            return redirect()->route('error-403');
        }
        $nik = auth()->user()->nik;
        $nama = Karyawan::where(['nik'=>$nik])->first()->nama;
        $id = Crypt::decrypt($request->id);
        // dd($id, $request->all());
        $id_status = $request->id_status;
        $process = $request->process;
        $id_interview = $request->id_interview;
        if(!empty($id_interview)){
            $id_interview = Crypt::decrypt($request->id_interview);
        }
        // 6=Interview User and PC Send
        // 8=Interview User Send
        // 10=Interview PC Send
        $catatan = '';
        if($id_status == 6 || $id_status == 8 || $id_status == 10){
            $_trx = TrxRecruitmentIdentitas::find($id);
            $_trx_ = TrxKebutuhanMaster::find($_trx->id_kebutuhan);
            // $posisi = $_trx_->_pekerjaan->pekerjaan;
            $kebutuhan_atribute = json_decode($_trx->kebutuhan_atribute, true);
            $posisi = PekerjaanSub::find($kebutuhan_atribute['id_pekerjaan'])->pekerjaan;
            $_trx->id_status = $id_status;
            $_trx->update();
            $metode = $request->metode;
            $trx = new TrxRecruitmentInterview;
            if(!empty($process)){
                $trx = TrxRecruitmentInterview::find($id_interview);
            }
            $trx->id_identitas = $id;
            $trx->id_status = $id_status;
            if($id_status == 10){
                $trx->jenis = 'hco';
            }elseif($id_status == 8){
                $trx->jenis = 'user';
            }elseif($id_status == 6){
                $trx->jenis = 'user_hco';
            }
            $trx->interviewer = implode(';', $request->interviewer);
            $trx->metode = $metode;
            $trx->check_in = $request->check_in;
            $trx->check_out = $request->check_out;
            if(!empty($process)){
                $trx->update();
            }else{
                $trx->save();
            }
            // 
            $where = ['id_identitas' => $trx->id_identitas, 'id_status' => $trx->id_status];
            $count = TrxRecruitmentLog::where($where)->count();
            $catatan = json_encode(['no'=>$count+1, 'jenis'=>$trx->jenis, 'metode'=>$trx->metode, 'interviewer'=>$trx->interviewer, 'check_in'=>$trx->check_in, 'check_out'=>$trx->check_out]);
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Undangan Interview '.$metode;
            $mailData['filename'] = 'emails.recruitment2';
            $mailData['data'] = ['trx' => $_trx, 'check_in' => $request->check_in, 'check_out' => $request->check_out, 'metode' => $metode, 'href' => MtrUtility::find(31)->informasi, 'posisi' => $posisi];
            $mailData['to'] = $_trx->email;
            // $mailData['cc'] = Karyawan::whereIn('nik', $request->interviewer)->pluck('email')->toArray();
            $this->sendEmail($mailData);
            $interviewer = Karyawan::whereIn('nik', $request->interviewer)->get();
            foreach($interviewer as $_interviewer){
                $mailData['filename'] = 'emails.recruitment2a';
                $mailData['to'] = $_interviewer->email;
                $mailData['data'] = ['trx' => $_trx, 'check_in' => $request->check_in, 'check_out' => $request->check_out, 'metode' => $metode, 'href' => MtrUtility::find(31)->informasi, 'posisi' => $posisi, 'interviewer' => $_interviewer];
                $this->sendEmail($mailData);
            }
            // return redirect()->to($request->back_url)->with('msg', 'Data is saved successfully.');
        }
        // 16=Interpretation
        // upload file Interpretation
        elseif($id_status == 16){
            $_trx = TrxRecruitmentIdentitas::find($id);
            $_trx->id_status = $id_status;
            $_trx->update();
            $data = $request->all();
            foreach($data as $_id => $value){
                if(str_starts_with($_id, 'jawaban_')){
                    $_trx_ = TrxRecruitmentResume::find(explode('_', $_id)[1]);
                    $_trx_->jawaban = $value;
                    $_trx_->update();
                }elseif(str_starts_with($_id, 'keterangan_')){
                    $_trx_ = TrxRecruitmentResume::find(explode('_', $_id)[1]);
                    $_trx_->keterangan = $value;
                    $_trx_->update();
                }
            }
            // if($request->hasFile('interpretasi')){
            //     $interpretasi = $request->file('interpretasi');
            //     $request->validate([
            //         'interpretasi' => 'file|max:1024',
            //     ]);
            //     $extension = $interpretasi->getClientOriginalExtension();
            //     $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
            //     $interpretasi->move('public/assets/recruitment/upload/', $namafile);
            //     $_trx->interpretasi = $namafile;
            // }
        }
        // 22=Psychotest and Interview Pass
        // 23=Psychotest and Interview Not Pass
        elseif(in_array($id_status, [22,23])){
            $_trx = TrxRecruitmentIdentitas::find($id);
            $_trx->id_status = $id_status;
            $_trx->update();
        }
        // 24=Join
        // 25=Not Join
        elseif(in_array($id_status, [24,25])){
            $_trx = TrxRecruitmentIdentitas::find($id);
            $interview = TrxRecruitmentInterview::where(['id_identitas' => $id])->orderBy('id', 'asc')->first();
            $nego = TrxRecruitmentNegosiasi::where(['id_identitas' => $id])->orderBy('id', 'desc')->first();
            $trxM = TrxKebutuhanMaster::find($_trx->id_kebutuhan);
            $trxD = TrxKebutuhanDetail::where(['id_kebutuhan' => $_trx->id_kebutuhan])->whereNull('id_identitas')->orderBy('id', 'desc')->first();
            // 
            $namafile='';
            if($request->hasFile('lampiran')){
                $lampiran = $request->file('lampiran');
                $request->validate([
                    'lampiran' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
                ]);
                $extension = $lampiran->getClientOriginalExtension();
                $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
                $lampiran->move('public/assets/recruitment/upload/', $namafile);
            }
            if(!empty($nego)){
                $nego->id_status = $id_status;
                $nego->update();
                $data = [$nama.'/'.$nik, 1, $request->catatan, $namafile];
                $this->insertNegotiationLog($nego, $data);
            }
            // 
            $_trx_ = TrxKebutuhanMaster::find($_trx->id_kebutuhan);
            $atasan = json_decode($_trx_->atasan_property, true);
            // $posisi = $_trx_->_pekerjaan->pekerjaan;
            $kebutuhan_atribute = json_decode($_trx->kebutuhan_atribute, true);
            $posisi = PekerjaanSub::find($kebutuhan_atribute['id_pekerjaan'])->pekerjaan;
            $bagian = json_decode($_trx_->pengaju_property, true);
            $bagian = $bagian['divisi'].'/'.$bagian['dep'].'/'.$bagian['sub_dep'];
            // 24=Join
            if($id_status == 24){
                // sync data Recruitment ke Employee Addition
                if(!empty($trxD)){
                    // status dinyatakan join not join
                    $_trx->id_join = implode(',', $request->input('id_join', []));
                    $_trx->id_status = $id_status;
                    if(empty($request->param)){
                        $_trx->update();
                    }
                    // 
                    $trxD->nama_karyawan = $_trx->nama;
                    $trxD->alamat = $_trx->alamat_ktp;
                    $trxD->tanggal_lahir = $_trx->tanggal_lahir;
                    $arr_pendidikan = ['SD', 'SMP', 'SMA/SMK', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3', 'Lainnya'];
                    $trxD->id_pendidikan = array_search($_trx->pendidikan, $arr_pendidikan) + 1;
                    $trxD->tanggal_interview = Carbon::parse($interview->check_in)->format('Y-m-d');
                    if(!empty($interview->file_interview) && File::exists(public_path('assets/recruitment/upload/'.$interview->file_interview))){
                        $trxD->file_interview = $interview->file_interview;
                        File::copy(public_path('assets/recruitment/upload/'.$interview->file_interview), public_path('assets/kebutuhan/'.$interview->file_interview));
                    }
                    $trxD->tanggal_psikotes = Carbon::parse($_trx->check_in)->format('Y-m-d');
                    $file_psikotes = $id.'_'.now()->getTimestampMs().'.pdf';
                    $this->pdf_interpretation($id, $file_psikotes);
                    $trxD->file_psikotes = $file_psikotes;
                    // 17=Negotiation Deal by Candidate
                    $tanggal_negosiasi = TrxRecruitmentLog::where(['id_identitas'=>$id, 'id_status'=>17])->orderBy('id', 'asc')->first();
                    if(!empty($tanggal_negosiasi->created_at)){
                        $trxD->tanggal_negosiasi = Carbon::parse($tanggal_negosiasi->created_at)->format('Y-m-d');
                    }
                    // 20=COC and File Upload Done
                    $tanggal_coc = TrxRecruitmentLog::where(['id_identitas'=>$id, 'id_status'=>20])->orderBy('id', 'asc')->first();
                    $trxD->tanggal_peraturan_perusahaan = Carbon::parse($tanggal_coc->created_at)->format('Y-m-d');
                    $trxD->tanggal_pedoman_prilaku = Carbon::parse($tanggal_coc->created_at)->format('Y-m-d');
                    $trxD->tanggal_struktur_organisasi = Carbon::parse($tanggal_coc->created_at)->format('Y-m-d');
                    if(!empty($_trx->file_dokumen_ttd) && File::exists(public_path('assets/recruitment/upload/'.$_trx->file_dokumen_ttd))){
                        $trxD->file_dokumen_ttd = $_trx->file_dokumen_ttd;
                        File::copy(public_path('assets/recruitment/upload/'.$_trx->file_dokumen_ttd), public_path('assets/kebutuhan/'.$_trx->file_dokumen_ttd));
                    }
                    if(!empty($_trx->cv) && File::exists(public_path('assets/recruitment/upload/'.$_trx->cv))){
                        $trxD->file_cv = $_trx->cv;
                        File::copy(public_path('assets/recruitment/upload/'.$_trx->cv), public_path('assets/kebutuhan/'.$_trx->cv));
                    }
                    if(!empty($_trx->file_sertifikat_keahlian) && File::exists(public_path('assets/recruitment/upload/'.$_trx->file_sertifikat_keahlian))){
                        $trxD->file_sertifikat_keahlian = $_trx->file_sertifikat_keahlian;
                        File::copy(public_path('assets/recruitment/upload/'.$_trx->file_sertifikat_keahlian), public_path('assets/kebutuhan/'.$_trx->file_sertifikat_keahlian));
                    }
                    if(!empty($_trx->file_ktp) && File::exists(public_path('assets/recruitment/upload/'.$_trx->file_ktp))){
                        $trxD->file_ktp = $_trx->file_ktp;
                        File::copy(public_path('assets/recruitment/upload/'.$_trx->file_ktp), public_path('assets/kebutuhan/'.$_trx->file_ktp));
                    }
                    if(!empty($_trx->file_kk) && File::exists(public_path('assets/recruitment/upload/'.$_trx->file_kk))){
                        $trxD->file_kk = $_trx->file_kk;
                        File::copy(public_path('assets/recruitment/upload/'.$_trx->file_kk), public_path('assets/kebutuhan/'.$_trx->file_kk));
                    }
                    if(!empty($_trx->file_npwp) && File::exists(public_path('assets/recruitment/upload/'.$_trx->file_npwp))){
                        $trxD->file_npwp = $_trx->file_npwp;
                        File::copy(public_path('assets/recruitment/upload/'.$_trx->file_npwp), public_path('assets/kebutuhan/'.$_trx->file_npwp));
                    }
                    if(!empty($_trx->file_bpjs_tk) && File::exists(public_path('assets/recruitment/upload/'.$_trx->file_bpjs_tk))){
                        $trxD->file_bpjs_tk = $_trx->file_bpjs_tk;
                        File::copy(public_path('assets/recruitment/upload/'.$_trx->file_bpjs_tk), public_path('assets/kebutuhan/'.$_trx->file_bpjs_tk));
                    }
                    if(!empty($_trx->file_bpjs_kes) && File::exists(public_path('assets/recruitment/upload/'.$_trx->file_bpjs_kes))){
                        $trxD->file_bpjs_kes = $_trx->file_bpjs_kes;
                        File::copy(public_path('assets/recruitment/upload/'.$_trx->file_bpjs_kes), public_path('assets/kebutuhan/'.$_trx->file_bpjs_kes));
                    }
                    if(!empty($_trx->file_buku_tabungan) && File::exists(public_path('assets/recruitment/upload/'.$_trx->file_buku_tabungan))){
                        $trxD->file_buku_tabungan = $_trx->file_buku_tabungan;
                        File::copy(public_path('assets/recruitment/upload/'.$_trx->file_buku_tabungan), public_path('assets/kebutuhan/'.$_trx->file_buku_tabungan));
                    }
                    if(!empty($_trx->file_ket_sehat) && File::exists(public_path('assets/recruitment/upload/'.$_trx->file_ket_sehat))){
                        $trxD->file_ket_sehat = $_trx->file_ket_sehat;
                        File::copy(public_path('assets/recruitment/upload/'.$_trx->file_ket_sehat), public_path('assets/kebutuhan/'.$_trx->file_ket_sehat));
                    }
                    if(!empty($_trx->file_skck) && File::exists(public_path('assets/recruitment/upload/'.$_trx->file_skck))){
                        $trxD->file_skck = $_trx->file_skck;
                        File::copy(public_path('assets/recruitment/upload/'.$_trx->file_skck), public_path('assets/kebutuhan/'.$_trx->file_skck));
                    }
                    if(!empty($_trx->file_ijazah) && File::exists(public_path('assets/recruitment/upload/'.$_trx->file_ijazah))){
                        $trxD->file_ijazah = $_trx->file_ijazah;
                        File::copy(public_path('assets/recruitment/upload/'.$_trx->file_ijazah), public_path('assets/kebutuhan/'.$_trx->file_ijazah));
                    }
                    $file_offering = $id.'_'.now()->getTimestampMs().'.pdf';
                    // 7=Magang 8=Konsultan
                    if(!in_array($trxM->id_status_kerja, [7, 8])){
                        $this->pdf_offering($id, $file_offering);
                        $trxD->file_offering = $file_offering;
                        $trxD->take_home_pay = $nego->thp;
                        // mapping ke benefit_utama dan benefit_lainnya
                        $benefit_utama = explode(',', $nego->benefit_utama);
                        $id_benefit = array();
                        foreach($benefit_utama as $data){
                            // mapping mtr_tbl_recruitment_benefit vs mtr_tbl_benefit
                            // refer to "C:\alita\matta\data_master\mapping kebutuhan vs recruitment v1.xlsx"
                            // 1=Upah Lembur
                            if($data == 1){
                                // 1=Overtime
                                array_push($id_benefit, 1);
                            }
                            // Medical Reimbursement
                            elseif($data == 2){
                                // 2=Medical Reimbursement
                                array_push($id_benefit, 2);
                            }
                            // Asuransi
                            elseif($data == 10){
                                // 3=Asuransi
                                array_push($id_benefit, 3);
                            }
                            // 5=BPJS Ketenagakerjaan, ditanggung perusahaan
                            // 6=BPJS Ketenagakerjaan, ditanggung  perusahaan & karyawan
                            elseif($data == 5 || $data == 6){
                                // 4=BPJS Ketenagakerjaan
                                array_push($id_benefit, 4);
                            }
                            // 7=BPJS Kesehatan, ditanggung perusahaan
                            // 8=BPJS Kesehatan, ditanggung  perusahaan & karyawan
                            elseif($data == 7 || $data == 8){
                                // 5=BPJS Kesehatan
                                array_push($id_benefit, 5);
                            }
                            // 9=Cuti Setelah 1 tahun
                            elseif($data == 9){
                                // 6=Cuti
                                array_push($id_benefit, 6);
                            }
                            // 3=PPh 21, ditanggung perusahaan
                            // 4=PPh 21, ditanggung perusahaan & karyawan
                            elseif($data == 3 || $data == 4){
                                // 7=PPh21
                                array_push($id_benefit, 7);
                            }
                            // 11=Tunjangan Corporate
                            elseif($data == 11){
                                // 8=Tunjangan Corporate
                                array_push($id_benefit, 8);
                            }
                            // 12=Tunjangan Project / Operasional
                            elseif($data == 12){
                                // 9=Tunjangan Project / Operasional
                                array_push($id_benefit, 9);
                            }
                            // 13=Alat kerja (laptop / notebook)
                            elseif($data == 13){
                                // 10=Alat kerja (laptop / notebook)
                                array_push($id_benefit, 10);
                            }
                        }
                        $trxD->id_benefit = implode(',',$id_benefit);
                        $trxD->keterangan3 = $nego->benefit_lainnya;
                        $trxD->tanggal_bergabung = $nego->tgl_bergabung;
                        // mapping mtr_tbl_recruitment_catat_pegawai vs mtr_tbl_rencana_penambahan
                        // refer to "C:\alita\matta\data_master\mapping kebutuhan vs recruitment v1.xlsx"
                        $id_catat_pegawai = $nego->id_catat_pegawai;
                        // 1=ALITA
                        if($id_catat_pegawai == 1){
                            // 4=Internal Alita
                            $trxD->id_pencatatan_kepegawaian = 4;
                        }
                        // 2=Outsource
                        elseif($id_catat_pegawai == 2){
                            // 3=Outsource
                            $trxD->id_pencatatan_kepegawaian = 3;
                        }
                        // mapping mtr_tbl_recruitment_status_pegawai vs mtr_tbl_status_kerja
                        // refer to "C:\alita\matta\data_master\mapping kebutuhan vs recruitment v1.xlsx"
                        $id_status_pegawai = $nego->id_status_pegawai;
                        // 1=Kontrak dan 1=ALITA
                        if($id_status_pegawai == 1 && $id_catat_pegawai == 1){
                            // 1=Karyawan Kontrak Alita
                            $trxD->id_status_kepegawaian = 1;
                        }
                        // 2=Tetap
                        elseif($id_status_pegawai == 2){
                            // 2=Karyawan Tetap
                            $trxD->id_status_kepegawaian = 2;
                        }
                        // 3=Tenaga Lepas Harian (TLH)
                        elseif($id_status_pegawai == 3){
                            // 3=Freelance
                            $trxD->id_status_kepegawaian = 3;
                        }
                        // 4=Konsultan
                        elseif($id_status_pegawai == 4){
                            // 8=Konsultan
                            $trxD->id_status_kepegawaian = 8;
                        }
                        // 1=Kontrak dan 2=Outsource
                        elseif($id_status_pegawai == 1 && $id_catat_pegawai == 2){
                            // 9=Karyawan Kontrak Outsource
                            $trxD->id_status_kepegawaian = 9;
                        }
                        $trxD->tanggal_mulai = $nego->tgl_bergabung;
                        $trxD->tanggal_akhir = Carbon::parse($nego->tgl_bergabung)->addMonths($nego->masa_kerja)->format('Y-m-d');
                        $trxD->tanggal_ttd_kontrak = $nego->tgl_bergabung;
                    }
                    $trxD->atasan = $trxM->atasan;
                    $trxD->id_identitas = $id;
                    $trxD->update();
                    // 
                    $check = TrxKebutuhanDetail::where(['id_kebutuhan' => $_trx->id_kebutuhan])->whereNull('id_identitas')->exists();
                    // check apakah masih ada tempat kosong jika tidak ada maka dinyatakan 10=Done by HC
                    if(!$check){
                        // 10=Done by HC
                        $_trx_->status_pengajuan = 10;
                        $_trx_->update();
                        // 
                        $data = new TrxKebutuhanLog;
                        $data->id_trx = $_trx->id_kebutuhan;
                        $data->id_status = 10;
                        $data->nik_pic = auth()->user()->nik;
                        $data->save();
                    }
                    // 
                    // email ke kandidat dinyatakan diterima
                    // 7=Magang 8=Konsultan
                    if(empty($request->param) && !in_array($trxM->id_status_kerja, [7, 8])){
                        // kirim email
                        $link = route('recruitment.download_offering', Crypt::encrypt($_trx->id));
                        $msg = '<p>Yth. '.$_trx->nama.',<br/>Dengan hormat,</p>
                        <p>Sehubungan dengan proses seleksi yang telah Bapak/Ibu/Saudara/i '.$_trx->nama.' ikuti untuk Posisi '.$posisi.', dengan ini kami sampaikan bahwa Bapak/Ibu/Saudara/i diterima di PT. Alita Praya Mitra sebagai '.$posisi.' di Direktorat / Divisi / Departemen '.$trxM->_divisi_kandidat->nama.' / '.$trxM->_dep_kandidat->nama.' / '.$trxM->_sub_dep_kandidat->nama.'.</p>
                        <p>Klik link berikut untuk mendownload Offering Letter yang telah disepakati.<br/><a target="_blank" href="'.$link.'" data-toggle="tooltip" title="LINK">LINK</a></p>
                        <p>Kami akan menghubungi Bapak/Ibu/Saudara/i  terkait proses selanjutnya. Mohon untuk memastikan bahwa  email anda '.$_trx->email.' dan nomor telepon yang dicantumkan dalam keadaan aktif untuk korespondensi.</p>
                        <p>Terima kasih atas kesediaannya mengikuti proses seleksi di PT. Alita Praya Mitra.</p>
                        <p>Terima kasih atas perhatiannya.<br/>Salam sehat dan sukses selalu.</p>'.MtrUtility::find(47)->informasi;
                        // parameter yang dipassing ke sendemail
                        $mailData['title'] = 'Pemberitahuan Proses Seleksi Diterima';
                        $mailData['data'] = ['msg'=>$msg];
                        $mailData['filename'] = 'emails.recruitment0';
                        $mailData['to'] = $_trx->email;
                        $this->sendEmail($mailData);
                        // kirim email ke atasan langsung jika ada
                        // <p>Mohon mempersiapkan alat kerja dan kebutuhan kerja lainnya untuk calon karyawan tersebut dan klik link berikut untuk melakukan persetujuan<br/><a href="'.$link.'" data-toggle="tooltip" title="Link Persetujuan">Link Persetujuan</a>.</p>
                        if(!empty($atasan)){
                            $link = route('recruitment.get_offering', [Crypt::encrypt([$_trx->id, 'view', 'atasan'])]);
                            $msg = '<p>Yth. '.$atasan['nama'].',<br/>Dengan hormat,</p>
                            <p>Kami informasikan bahwa proses negosiasi atas Formulir Kebutuhan Karyawan nomor '.$_trx->id_kebutuhan.' telah dilakukan dan disetujui oleh calon karyawan atas nama berikut:</p>
                            <p><table border="0" cellpadding="1" cellspacing="1">	
                                <tr>
                                    <td width="150">Nama</td>
                                    <td width="5">:</td>
                                    <td>'.$_trx->nama.'</td>
                                </tr>
                                <tr>
                                    <td>Posisi</td>
                                    <td>:</td>
                                    <td>'.$posisi.'</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Bergabung</td>
                                    <td>:</td>
                                    <td>'.Carbon::parse($nego->tgl_bergabung)->format('d-m-Y').'</td>
                                </tr>
                            </table></p>
                            <p>Calon karyawan tersebut telah melalui seluruh tahapan seleksi, termasuk seleksi CV, wawancara HC, wawancara user, Psikotest, dll., dan dinyatakan memenuhi kualifikasi yang dibutuhkan.</p>
                            <p>Mohon mempersiapkan alat kerja dan kebutuhan kerja lainnya untuk calon karyawan tersebut. Klik link dibawah ini untuk melakukan persetujuan terhadap Formulir Kebutuhan Karyawan tersebut.<br/><a href="'.$link.'" data-toggle="tooltip" title="LINK">LINK</a></p>
                            <p>Demikian informasi ini kami sampaikan untuk diketahui. Jika terdapat perkembangan lebih lanjut, kami akan memberikan pembaruan.<br/>Terima kasih.</p>'.MtrUtility::find(47)->informasi;
                            // parameter yang dipassing ke sendemail
                            $mailData['title'] = 'Pemberitahuan Proses Seleksi Penerimaan Karyawan';
                            // $mailData['data'] = ['msg'=>$msg];
                            $mailData['filename'] = 'emails.recruitment0';
                            $mailData['to'] = $atasan['email'];
                            $todolist = ['id_trx' => $_trx->id, 'nik' => $atasan['nik'], 'id_status' => $id_status, 'pic' => 'atasan'];
                            $mailData['data'] = ['msg'=>$msg, 'nama' => $atasan['nama'].'/'.$atasan['nik'], 'jabatan' => $atasan['jabatan'], 'unit_kerja' => $atasan['divisi'] . '/' . $atasan['dep'] . '/' . $atasan['sub_dep'], 'link' => $link];
                            $this->insertToDoList($todolist, $mailData);
                            $this->sendEmail($mailData);
                        }
                    }
                }
                // jika sudah terpenuhi
                else
                {
                    return redirect('recruitment/applicant_list/0')->with('error', 'No data is saved successfully. Add Employess are not comply.');
                }
            }
            // 25=Not Join
            elseif($id_status == 25){
                // status dinyatakan join not join
                $_trx->id_join = implode(',', $request->input('id_join', []));
                $_trx->id_status = $id_status;
                if(empty($request->param)){
                    $_trx->update();
                }
                // email ke kandidat dinyatakan tidak diterima
                // kirim email
                $msg = '<p>Yth. '.$_trx->nama.',<br/>Dengan hormat,</p>
                <p>Sehubungan dengan proses seleksi yang telah Bapak/Ibu/Saudara/i '.$_trx->nama.' ikuti untuk Posisi '.$posisi.', dengan ini kami sampaikan bahwa Bapak/Ibu/Saudara/i, dengan berat hati kami sampaikan bahwa Bapak/Ibu/Saudara/i belum dapat bergabung di PT. Alita Praya Mitra dikarenakan belum memenuhi kualifikasi yang dibutuhkan untuk posisi tersebut.</p>
                <p>Terimakasih atas kesediaannya mengikuti proses seleksi di PT. Alita Praya Mitra.<br/>Kami harap Bapak/Ibu/Saudara/i dapat bekerjasama dengan kami di lain kesempatan.</p>
                <p>Terima kasih atas perhatiannya.<br/>Salam sehat dan sukses selalu.</p>'.MtrUtility::find(47)->informasi;
                // parameter yang dipassing ke sendemail
                $mailData['title'] = 'Pemberitahuan Proses Seleksi Ditolak';
                $mailData['data'] = ['msg'=>$msg];
                $mailData['filename'] = 'emails.recruitment0';
                $mailData['to'] = $_trx->email;
                $this->sendEmail($mailData);
            }
        }
        // 12=Negotiation Process
        // elseif($id_status == 12){
        //     $_trx = TrxRecruitmentIdentitas::find($id);
        //     $_trx->id_status = $id_status;
        //     $_trx->update();
        // }
        // 13=Negotiation Deal by PC
        // 14=Negotiation No Deal by PC
        // upload file Negotiation
        // elseif($id_status == 13 || $id_status == 14){
        //     $_trx = TrxRecruitmentIdentitas::find($id);
        //     $_trx->id_status = $id_status;
        //     if($request->hasFile('negosiasi')){
        //         $negosiasi = $request->file('negosiasi');
        //         $request->validate([
        //             'negosiasi' => 'file|max:1024',
        //         ]);
        //         $extension = $negosiasi->getClientOriginalExtension();
        //         $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
        //         $negosiasi->move('public/assets/recruitment/upload/', $namafile);
        //         $_trx->negosiasi = $namafile;
        //     }
        //     $_trx->update();
        // }
        // 15=Recruitment Done
        // elseif($id_status == 15){
        //     $_trx = TrxRecruitmentIdentitas::find($id);
        //     $_trx->id_status = $id_status;
        //     $_trx->update();
        // }
        // $_trx = TrxRecruitmentIdentitas::find($trx->id_identitas);
        // $posisi = $_trx->_kebutuhan->_pekerjaan->pekerjaan;
        // $where = ['is_aktif' => 1];
        // $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        // $trx->interviewer = $request->interviewer;
        // $trx->check_in = $request->check_in;
        // $trx->check_out = $request->check_out;
        // 7=Interview Send
        // $id_status = 7;
        // upload file_interview
        if($request->hasFile('file_interview')){
            $trx = TrxRecruitmentInterview::find($id);
            $file_interview = $request->file('file_interview');
            $request->validate([
                'file_interview' => 'file|max:20480',
            ]);
            $extension = $file_interview->getClientOriginalExtension();
            $namafile = $trx->id_identitas.'_'.now()->getTimestampMs().'.'.$extension;
            $file_interview->move('public/assets/recruitment/upload/', $namafile);
            $trx->file_interview = $namafile;
            // 6=Interview User and PC Send
            // 7=Interview User and PC Done
            // 8=Interview User Send
            // 9=Interview User Done
            // 10=Interview PC Send
            // 11=Interview PC Done
            $id_status = $trx->id_status;
            if($id_status == 6){
                $trx->id_status = 7;
                $id_status = 7;
            }elseif($id_status == 8){
                $trx->id_status = 9;
                $id_status = 9;
            }elseif($id_status == 10){
                $trx->id_status = 11;
                $id_status = 11;
            }
            // 
            $trx->update();
            // 
            $_trx = TrxRecruitmentIdentitas::find($trx->id_identitas);
            $_trx->id_status = $id_status;
            $_trx->update();
            // end
            $_trx_ = TrxKebutuhanMaster::find($_trx->id_kebutuhan);
            // $posisi = $_trx_->_pekerjaan->pekerjaan;
            $kebutuhan_atribute = json_decode($_trx->kebutuhan_atribute, true);
            $posisi = PekerjaanSub::find($kebutuhan_atribute['id_pekerjaan'])->pekerjaan;
            // kirim email
            $msg = '<p>Yth. '.$_trx->nama.',<br/>Dengan hormat,</p>
            <p>Kami ucapkan terima kasih atas partisipasi Bapak/Ibu/Saudara/i dalam proses Interview untuk posisi '.$posisi.' di PT. Alita Praya Mitra. Kami telah menerima hasil interview Bapak/Ibu/Saudara/i dan saat ini kami sedang melakukan proses evaluasi lebih lanjut.</p>
            <p>Kami akan segera menghubungi Bapak/Ibu/Saudara/i apabila terdapat informasi tambahan atau informasi jika ada tahapan seleksi selanjutnya.</p>
            <p>Demikian kami sampaikan, terima kasih atas perhatian dan kerja samanya.</p>'.MtrUtility::find(47)->informasi;
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Interview Selesai';
            $mailData['data'] = ['msg'=>$msg];
            $mailData['filename'] = 'emails.recruitment0';
            $mailData['to'] = $_trx->email;
            $this->sendEmail($mailData);
            // return redirect()->to($request->back_url)->with('msg', 'Data is saved successfully.');
        }
        if(empty($request->param)){
            // start
            // id_identitas,id_status,pic,pic_property,aktifitas,catatan
            $arr = [$_trx->id,$id_status,$nama.'/'.$nik,'','internal',''];
            // jika ada $catatan
            if(!empty($catatan)){
                // $_trx_ = TrxRecruitmentInterview::find($id_interview);
                // // count
                // $where = ['id_identitas' => $_trx_->id_identitas, 'id_status' => $_trx_->id_status];
                // $count = TrxRecruitmentLog::where($where)->count();
                // $catatan = json_encode(['no'=>$count+1, 'jenis'=>$_trx_->jenis, 'metode'=>$_trx_->metode, 'interviewer'=>$_trx_->interviewer, 'check_in'=>$_trx_->check_in, 'check_out'=>$_trx_->check_out]);
                $arr = [$_trx->id,$id_status,$nama.'/'.$nik,'','internal',$catatan];
            }
            // 
            $this->insertLog($arr);
        }
        return redirect('recruitment/applicant_list/0')->with('msg', 'Data is saved successfully.');
    }

    public function applicant_save(Request $request)
    {
        // dd($request->all());
        // psychotest_bulk
        if($request->submit == 'psychotest_bulk'){
            $id_identitas = $request->input('id_identitas');
            if(!empty($id_identitas)){
                foreach($id_identitas as $data){
                    // dd($data);
                    $trx = TrxRecruitmentIdentitas::find($data);
                    $_trx = TrxKebutuhanMaster::find($trx->id_kebutuhan);
                    // $posisi = $_trx->_pekerjaan->pekerjaan;
                    // dd($trx);
                    $trx->id_status = $request->id_status;
                    $trx->check_in = $request->check_in;
                    // default durasi pengerjaan adalah 150 menit (2.5 jam)
                    $trx->check_out = Carbon::parse($request->check_in)->addMinutes(150);
                    // dan jika belum terisi karena sudah dikerjakan
                    if(empty($trx->remain)){
                        $trx->remain = Carbon::parse($trx->check_out)->diffInSeconds(Carbon::parse($trx->check_in));
                    }
                    $trx->update();
                    // start
                    // id_identitas,id_status,pic,pic_property,aktifitas,catatan
                    $nik = auth()->user()->nik;
                    $nama = Karyawan::where(['nik'=>$nik])->first()->nama;
                    $arr = [$trx->id,$trx->id_status,$nama.'/'.$nik,'','internal',''];
                    $this->insertLog($arr);
                    // end
                    // jika belum psychotest
                    $where = ['id_identitas'=>$trx->id];
                    if(!TrxRecruitmentPsychotest::where($where)->exists()){
                        $this->insertPsychotest($trx);
                    }
                    // source: https://ziixon93.blogspot.com/2020/07/cara-memasang-imagemagick-di-xampp.html
                    $qr = QrCode::format('png')->size(500)->generate(route('recruitment.psychotest', [Crypt::encrypt(array($trx->id, 'psychotest'))]));
                    $qrImageName = base64_encode($trx->id) . '.png';
                    // dd($qrImageName);
                    // simpan ke local storage
                    Storage::put('public/qr/' . $qrImageName, $qr);
                    // Storage::disk('public')->put('qr/'.$qrImageName, File::get(public_path($qrImageName)));
                    // 
                    // parameter yang dipassing ke sendemail
                    $mailData['title'] = 'Undangan Psikotes';
                    $mailData['data'] = ['trx' => $trx];
                    $mailData['filename'] = 'emails.recruitment1';
                    $mailData['to'] = $trx->email;
                    $this->sendEmail($mailData);
                }
                return redirect()->to($request->back_url)->with('msg', 'Data is saved successfully.');
            }
            // jika tidak ada id_identitas yang dipilih
            else{
                return redirect()->to($request->back_url)->with('msg', 'No Data is executed.' );
            }
            $trx = TrxRecruitmentIdentitas::find(Crypt::decrypt($request->id));
            $_trx = TrxKebutuhanMaster::find($trx->id_kebutuhan);
            // $posisi = $_trx->_pekerjaan->pekerjaan;
            // dd($trx);
            $trx->id_status = $request->id_status;
            $trx->check_in = $request->check_in;
            // default durasi pengerjaan adalah 150 menit (2.5 jam)
            $trx->check_out = Carbon::parse($request->check_in)->addMinutes(150);
            // dan jika belum terisi karena sudah dikerjakan
            if(empty($trx->remain)){
                $trx->remain = Carbon::parse($trx->check_out)->diffInSeconds(Carbon::parse($trx->check_in));
            }
            $trx->update();
            // start
            // id_identitas,id_status,pic,pic_property,aktifitas,catatan
            $nik = auth()->user()->nik;
            $nama = Karyawan::where(['nik'=>$nik])->first()->nama;
            $arr = [$trx->id,$trx->id_status,$nama.'/'.$nik,'','internal',''];
            $this->insertLog($arr);
            // end
            // jika belum psychotest
            $where = ['id_identitas'=>$trx->id];
            if(!TrxRecruitmentPsychotest::where($where)->exists()){
                $this->insertPsychotest($trx);
            }
            // source: https://ziixon93.blogspot.com/2020/07/cara-memasang-imagemagick-di-xampp.html
            $qr = QrCode::format('png')->size(500)->generate(route('recruitment.psychotest', [Crypt::encrypt(array($trx->id, 'psychotest'))]));
            $qrImageName = base64_encode($trx->id) . '.png';
            // dd($qrImageName);
            // simpan ke local storage
            Storage::put('public/qr/' . $qrImageName, $qr);
            // Storage::disk('public')->put('qr/'.$qrImageName, File::get(public_path($qrImageName)));
            // 
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Undangan Psikotes';
            $mailData['data'] = ['trx' => $trx];
            $mailData['filename'] = 'emails.recruitment1';
            $mailData['to'] = $trx->email;
            $this->sendEmail($mailData);
            // return redirect()->to($request->back_url)->with('msg', 'Data is saved successfully.');
            return redirect('recruitment/applicant_list/0')->with('msg', 'Data is saved successfully.');
        }
        $jenis_psikotes = $request->jenis_psikotes;
        $type = $request->type;
        $trx = TrxRecruitmentIdentitas::find(Crypt::decrypt($request->id));
        $_trx = TrxKebutuhanMaster::find($trx->id_kebutuhan);
        // $posisi = $_trx->_pekerjaan->pekerjaan;
        // dd($trx);
        $trx->id_status = $request->id_status;
        $trx->check_in = $request->check_in;
        // default
        $trx->check_out = Carbon::parse($request->check_in)->addMinutes(150);
        // <option value="1">TIU 5</option>
        // jika diberikan tiu5 maka ditambah 30 menit
        if($jenis_psikotes == 1){
            $trx->check_out = Carbon::parse($request->check_in)->addMinutes(25);
        }
        // <option value="2">EPPS, PAPI Kostick, DISC, BAUM, DAP</option>
        // jika diberikan existing maka ditambah 150 menit
        elseif($jenis_psikotes == 2){
            $trx->check_out = Carbon::parse($request->check_in)->addMinutes(150);
        }
        // <option value="3">TIU 5 & (EPPS, PAPI Kostick, DISC, BAUM, DAP)</option>
        // jika diberikan tiu5 & existing maka ditambah 25 + 150 menit
        elseif($jenis_psikotes == 3){
            $trx->check_out = Carbon::parse($request->check_in)->addMinutes(175);
        }
        // dan jika belum terisi karena sudah dikerjakan
        if(empty($trx->remain)){
            $trx->remain = Carbon::parse($trx->check_out)->diffInSeconds(Carbon::parse($trx->check_in));
        }
        // jika extend
        if($type == 'extend'){
            $trx->check_out = Carbon::parse($request->check_in)->addMinutes(60);
        }
        // jika revisi psikotes
        if($request->process == 'psikotest_revisi'){
            $jenis = $request->input('jenis');
            // 0 = Pengantar
            // 1 = EPPS
            // 2 = PAPI Kostick
            // 3 = DISC
            // 4 = BAUM
            // 5 = DAP
            // satuan menit
            $durasi = [0, 60, 30, 30, 15, 15];
            $total_durasi = 0;
            foreach($jenis as $_jenis){
                $total_durasi += $durasi[$_jenis];
            }
            $trx->check_out = Carbon::parse($request->check_in)->addMinutes($total_durasi);
            $trx->remain = Carbon::parse($trx->check_out)->diffInSeconds(Carbon::parse($trx->check_in));
            // backup soal dan jawaban sebelumnya yang akan direvisi
            $soal = TrxRecruitmentPsychotest::where(['id_identitas'=>$trx->id])->whereIn('jenis', $jenis)->orderBy('jenis', 'asc')->orderBy('nomor', 'asc')->get();
            $n_log = TrxRecruitmentPsychotestLog::selectRaw('COUNT(*) as total')->where(['id_identitas'=>$trx->id])->groupBy('urutan')->get()->count();
            foreach($soal as $_soal){
                $log = new TrxRecruitmentPsychotestLog;
                $log->urutan = $n_log + 1;
                $log->id_identitas = $_soal->id_identitas;
                $log->jenis = $_soal->jenis;
                $log->nomor = $_soal->nomor;
                $log->soal1 = $_soal->soal1;
                $log->soal2 = $_soal->soal2;
                $log->soal3 = $_soal->soal3;
                $log->soal4 = $_soal->soal4;
                $log->jawaban1 = $_soal->jawaban1;
                $log->jawaban2 = $_soal->jawaban2;
                $log->check_in = $_soal->check_in;
                $log->check_out = $_soal->check_out;
                $log->remain = $_soal->remain;
                $log->_created_at = Carbon::parse($_soal->_created_at)->format('Y-m-d H:i:s');
                $log->_updated_at = Carbon::parse($_soal->_updated_at)->format('Y-m-d H:i:s');
                $log->save();
            }
        }
        $trx->update();
        // start
        // id_identitas,id_status,pic,pic_property,aktifitas,catatan
        $nik = auth()->user()->nik;
        $nama = Karyawan::where(['nik'=>$nik])->first()->nama;
        $arr = [$trx->id,$trx->id_status,$nama.'/'.$nik,'','internal',''];
        // jika revisi psikotest maka id_status di trx_tbl_recruitment_log adalah 4
        if($request->process == 'psikotest_revisi'){
            $arr = [$trx->id, 4, $nama.'/'.$nik, '', 'internal', ''];
            // menjadikan jawaban1, jawaban2, check_in, check_out, remain == NULL
            // sesuai dengan jenis psikotest yang direvisi
            $jenis = $request->input('jenis');
            // 0 = Pengantar
            // 1 = EPPS
            // 2 = PAPI Kostick
            // 3 = DISC
            // 4 = BAUM
            // 5 = DAP
            TrxRecruitmentPsychotest::where(['id_identitas'=>$trx->id])->whereIn('jenis', $jenis)->update(['jawaban1' => NULL, 'jawaban2' => NULL,'check_in' => NULL,'check_out' => NULL,'remain' => NULL]);
        }
        $this->insertLog($arr);
        // end
        // jika belum psychotest
        // <option value="2">EPPS, PAPI Kostick, DISC, BAUM, DAP</option>
        // <option value="3">TIU 5 & (EPPS, PAPI Kostick, DISC, BAUM, DAP)</option>
        $where = ['id_identitas'=>$trx->id];
        if(!TrxRecruitmentPsychotest::where($where)->exists() && in_array($jenis_psikotes, [2,3])){
            $this->insertPsychotest($trx);
        }
        // jika diberikan tiu5 dan belum ada maka insert into TrxRecruitmentTiu5
        // <option value="1">TIU 5</option>
        // <option value="3">TIU 5 & (EPPS, PAPI Kostick, DISC, BAUM, DAP)</option>
        if(!TrxRecruitmentTiu5::where($where)->exists() && in_array($jenis_psikotes, [1,3])){
            $this->insertTiu5($trx);
        }
        // source: https://ziixon93.blogspot.com/2020/07/cara-memasang-imagemagick-di-xampp.html
        $qr = QrCode::format('png')->size(500)->generate(route('recruitment.psychotest', [Crypt::encrypt(array($trx->id, 'psychotest'))]));
        $qrImageName = base64_encode($trx->id) . '.png';
        // dd($qrImageName);
        // simpan ke local storage
        Storage::put('public/qr/' . $qrImageName, $qr);
        // Storage::disk('public')->put('qr/'.$qrImageName, File::get(public_path($qrImageName)));
        // 
        // parameter yang dipassing ke sendemail
        $mailData['title'] = 'Undangan Psikotes';
        $mailData['data'] = ['trx' => $trx];
        $mailData['filename'] = 'emails.recruitment1';
        $mailData['to'] = $trx->email;
        $this->sendEmail($mailData);
        // return redirect()->to($request->back_url)->with('msg', 'Data is saved successfully.');
        return redirect('recruitment/applicant_list/0')->with('msg', 'Data is saved successfully.');
    }

    public function insertLog($id)
    {
        // $arr = [$trx->id, $trx->id_status, $nama.'/'.$nik, '', 'internal', ''];
        $trx = new TrxRecruitmentLog;
        $trx->id_identitas = $id[0];
        $trx->id_status = $id[1];
        $trx->pic = $id[2];
        $trx->pic_property = $id[3];
        $trx->aktifitas = $id[4];
        $trx->catatan = $id[5];
        // jika 4=Psychotest Send
        if($id[1] == 4){
            $_trx = TrxRecruitmentIdentitas::find($id[0]);
            // count
            $where = ['id_identitas' => $id[0], 'id_status' => $id[1]];
            $count = TrxRecruitmentLog::where($where)->count();
            $psikotest = 'Send';
            // jika sudah pernah dikirimkan psikotest
            if($count > 0){
                // jika psikotest yang dikirimkan belum pernah diisi
                if($id[1] == $_trx->id_status){
                    $psikotest = 'Reschedule';
                }
                // jika psikotest sudah selesai dan direvisi
                else{
                    $psikotest = 'Revision';
                }
            }
            $catatan = json_encode(['no'=>$count+1, 'check_in'=>$_trx->check_in, 'check_out'=>$_trx->check_out, 'psikotest'=>$psikotest]);
            $trx->catatan = $catatan;
        }
        if(!empty($id[6])){
            $trx->lampiran = $id[6];
        }
        $trx->save();
    }

    public function insertInterpretation($trx)
    {
        $data = MtrRecruitmentResume::orderBy('id', 'asc')->get();
        foreach($data as $_data){
            $_trx = new TrxRecruitmentResume;
            $_trx->id_identitas = $trx->id;
            $_trx->nomor = $_data->nomor;
            $_trx->urutan = $_data->urutan;
            $_trx->pernyataan1 = $_data->pernyataan1;
            $_trx->pernyataan2 = $_data->pernyataan2;
            $_trx->save();
        }
    }

    public function insertPsychotest($id)
    {
        // dd($id);
        // insert jenis=0
        // $where = ['jenis'=>0];
        $jenis = 0;
        // Pengantar awal
        // $trx = MtrRecruitmentSoal::find(1);
        $trx = MtrRecruitmentSoal::where(['jenis'=>$jenis, 'nomor'=>0, 'urutan'=>0])->first();
        $_trx = new TrxRecruitmentPsychotest;
        $_trx->id_identitas = $id->id;
        $_trx->jenis = $trx->jenis;
        $_trx->nomor = $trx->nomor;
        $_trx->soal1 = $trx->soal;
        $_trx->save();
        $jenis = 1;
        // Pengantar
        // $trx = MtrRecruitmentSoal::find(734);
        $trx = MtrRecruitmentSoal::where(['jenis'=>$jenis, 'nomor'=>0, 'urutan'=>0])->first();
        $_trx = new TrxRecruitmentPsychotest;
        $_trx->id_identitas = $id->id;
        $_trx->jenis = $trx->jenis;
        $_trx->nomor = $trx->nomor;
        $_trx->soal1 = $trx->soal;
        $_trx->save();
        // insert jenis=1
        $where = ['jenis'=>$jenis, 'urutan'=>1];
        $soal1 = MtrRecruitmentSoal::where($where)->orderBy('id', 'asc')->pluck('soal')->toArray();
        $where = ['jenis'=>$jenis, 'urutan'=>2];
        $soal2 = MtrRecruitmentSoal::where($where)->orderBy('id', 'asc')->pluck('soal')->toArray();
        for($x = 0; $x < count($soal1); $x++){
            $_trx = new TrxRecruitmentPsychotest;
            $_trx->id_identitas = $id->id;
            $_trx->jenis = $jenis;
            $_trx->nomor = $x+1;
            $_trx->soal1 = $soal1[$x];
            $_trx->soal2 = $soal2[$x];
            $_trx->save();
        }
        $jenis = 2;
        // Pengantar
        // $trx = MtrRecruitmentSoal::find(452);
        $trx = MtrRecruitmentSoal::where(['jenis'=>$jenis, 'nomor'=>0, 'urutan'=>0])->first();
        $_trx = new TrxRecruitmentPsychotest;
        $_trx->id_identitas = $id->id;
        $_trx->jenis = $trx->jenis;
        $_trx->nomor = $trx->nomor;
        $_trx->soal1 = $trx->soal;
        $_trx->save();
        // insert jenis=2
        $where = ['jenis'=>$jenis, 'urutan'=>1];
        $soal1 = MtrRecruitmentSoal::where($where)->orderBy('id', 'asc')->pluck('soal')->toArray();
        $where = ['jenis'=>$jenis, 'urutan'=>2];
        $soal2 = MtrRecruitmentSoal::where($where)->orderBy('id', 'asc')->pluck('soal')->toArray();
        for($x = 0; $x < count($soal1); $x++){
            $_trx = new TrxRecruitmentPsychotest;
            $_trx->id_identitas = $id->id;
            $_trx->jenis = $jenis;
            $_trx->nomor = $x+1;
            $_trx->soal1 = $soal1[$x];
            $_trx->soal2 = $soal2[$x];
            $_trx->save();
        }
        $jenis = 3;
        // Pengantar
        // $trx = MtrRecruitmentSoal::find(633);
        $trx = MtrRecruitmentSoal::where(['jenis'=>$jenis, 'nomor'=>0, 'urutan'=>0])->first();
        $_trx = new TrxRecruitmentPsychotest;
        $_trx->id_identitas = $id->id;
        $_trx->jenis = $trx->jenis;
        $_trx->nomor = $trx->nomor;
        $_trx->soal1 = $trx->soal;
        $_trx->save();
        // insert jenis=3
        $where = ['jenis'=>$jenis, 'urutan'=>1];
        $soal1 = MtrRecruitmentSoal::where($where)->orderBy('id', 'asc')->pluck('soal')->toArray();
        $where = ['jenis'=>$jenis, 'urutan'=>2];
        $soal2 = MtrRecruitmentSoal::where($where)->orderBy('id', 'asc')->pluck('soal')->toArray();
        $where = ['jenis'=>$jenis, 'urutan'=>3];
        $soal3 = MtrRecruitmentSoal::where($where)->orderBy('id', 'asc')->pluck('soal')->toArray();
        $where = ['jenis'=>$jenis, 'urutan'=>4];
        $soal4 = MtrRecruitmentSoal::where($where)->orderBy('id', 'asc')->pluck('soal')->toArray();
        for($x = 0; $x < count($soal1); $x++){
            $_trx = new TrxRecruitmentPsychotest;
            $_trx->id_identitas = $id->id;
            $_trx->jenis = $jenis;
            $_trx->nomor = $x+1;
            $_trx->soal1 = $soal1[$x];
            $_trx->soal2 = $soal2[$x];
            $_trx->soal3 = $soal3[$x];
            $_trx->soal4 = $soal4[$x];
            $_trx->save();
        }
        $jenis = 4;
        // Pengantar
        // $trx = MtrRecruitmentSoal::find(730);
        $trx = MtrRecruitmentSoal::where(['jenis'=>$jenis, 'nomor'=>0, 'urutan'=>0])->first();
        $_trx = new TrxRecruitmentPsychotest;
        $_trx->id_identitas = $id->id;
        $_trx->jenis = $trx->jenis;
        $_trx->nomor = $trx->nomor;
        $_trx->soal1 = $trx->soal;
        $_trx->save();
        // insert jenis=4
        $where = ['jenis'=>$jenis, 'urutan'=>1];
        $soal1 = MtrRecruitmentSoal::where($where)->orderBy('id', 'asc')->pluck('soal')->toArray();
        for($x = 0; $x < count($soal1); $x++){
            $_trx = new TrxRecruitmentPsychotest;
            $_trx->id_identitas = $id->id;
            $_trx->jenis = $jenis;
            $_trx->nomor = $x+1;
            $_trx->soal1 = $soal1[$x];
            $_trx->save();
        }
        $jenis = 5;
        // Pengantar
        // $trx = MtrRecruitmentSoal::find(732);
        $trx = MtrRecruitmentSoal::where(['jenis'=>$jenis, 'nomor'=>0, 'urutan'=>0])->first();
        $_trx = new TrxRecruitmentPsychotest;
        $_trx->id_identitas = $id->id;
        $_trx->jenis = $trx->jenis;
        $_trx->nomor = $trx->nomor;
        $_trx->soal1 = $trx->soal;
        $_trx->save();
        // insert jenis=5
        $where = ['jenis'=>$jenis, 'urutan'=>1];
        $soal1 = MtrRecruitmentSoal::where($where)->orderBy('id', 'asc')->pluck('soal')->toArray();
        for($x = 0; $x < count($soal1); $x++){
            $_trx = new TrxRecruitmentPsychotest;
            $_trx->id_identitas = $id->id;
            $_trx->jenis = $jenis;
            $_trx->nomor = $x+1;
            $_trx->soal1 = $soal1[$x];
            $_trx->save();
        }
    }

    public function insertTiu5($id)
    {
        $trx = MtrRecruitmentSoalTiu5::orderBy('id', 'asc')->get();
        foreach($trx as $_trx){
            $data = new TrxRecruitmentTiu5;
            $data->id_identitas = $id->id;
            $data->jenis = $_trx->jenis;
            $data->nomor = $_trx->nomor;
            $data->urutan = $_trx->urutan;
            $data->soal = $_trx->soal;
            $data->kunci = $_trx->kunci;
            $data->save();
        }
    }

    public function psychotest(Request $request, $id)
    {
        // contoh param yang dipassing
        // array:4 [
        //     "_token" => "P4iAcbVv66NMtwARC44IeoyDKNzoVpxPIOIwcOBf"
        //     "je" => "1"
        //     "no" => "2"
        //     "a" => "1"
        // ]
        // array:3 [
        //     0 => 11150
        //     1 => "psychotest"
        //     2 => 160715
        // ]
        try {
            // dd($request->all(), Crypt::decrypt($id));
            $remain = 0;
            $_now = Carbon::now();
            $_id = Crypt::decrypt($id);
            // $_id[0] = id_identitas
            $id = $_id[0];
            // $kode = $_id[1];
            $trx = TrxRecruitmentIdentitas::find($id);
            $kebutuhan_atribute = json_decode($trx->kebutuhan_atribute, true);
            $kebutuhan_atribute_atasan_property = json_decode($kebutuhan_atribute['atasan_property'], true);
            $posisi = PekerjaanSub::find($kebutuhan_atribute['id_pekerjaan'])->pekerjaan;
            $_trx_ = TrxKebutuhanMaster::find($trx->id_kebutuhan);
            // ambil sisa waktu yang tersisa
            $remain = $_now->diffInSeconds(Carbon::parse($trx->check_out));
            // check apakah ada tiu5 atau check_out null
            $tiu5 = TrxRecruitmentTiu5::where(['id_identitas' => $id])->whereNull('check_out')->orderBy('jenis', 'asc')->orderBy('nomor', 'asc')->first();
            if(!empty($tiu5)){
                // dd($request->all());
                // $_id[2] = id-nya trx_tbl_recruitment_tiu5
                if(!empty($_id[2])){
                    $soal = TrxRecruitmentTiu5::find($_id[2]);
                    $soal->check_out = $_now;
                    $soal->jawaban = $request->a;
                    // jika jawaban benar sama dengan kunci maka dibarikan 1
                    if($soal->nomor > 0){
                        $soal->cek = 0;
                        if($request->a == $soal->kunci){
                            $soal->cek = 1;
                        }
                    }
                    $soal->update();
                }
                // $posisi = $_trx_->_pekerjaan->pekerjaan;
                $where = ['id_identitas' => $id];
                $soal = TrxRecruitmentTiu5::where($where)->whereNull('check_out')->orderBy('jenis', 'asc')->orderBy('nomor', 'asc')->first();
                if(!empty($soal)){
                    if((Carbon::now() > Carbon::parse($trx->check_in)) && (Carbon::now() < Carbon::parse($trx->check_out))){
                        $where = ['id_identitas' => $soal->id_identitas, 'jenis' => $soal->jenis];
                        $jumlah = TrxRecruitmentTiu5::where($where)->where('nomor', '>', 0)->count();
                        $soal->check_in = $_now;
                        $soal->remain = $remain;
                        $soal->update();
                        // 
                        $trx->remain = $remain;
                        $trx->update();
                        if($soal->jenis == 6){
                            if($soal->nomor == 0 & $soal->urutan == 0){
                                return view('recruitment.tiu5_pre', compact('trx','soal', 'jumlah'));
                            }else{
                                return view('recruitment.psychotest_exe', compact('trx','soal', 'jumlah'));
                            }
                        }
                    }else{
                        $msg = '<p>Yth. '.$trx->nama.',<br/>Dengan Hormat,</p>
                        <p>Kami ingin menginformasikan bahwa psikotest belum dapat dimulai karena belum sesuai dengan jadwal dan waktu yang telah diinformasikan.</p>
                        <p>Mohon kesediaannya untuk menunggu sesuai jadwal dan waktu yang telah diinformasikan. Apabila ada pertanyaan atau kebutuhan lainnya, jangan ragu untuk menghubungi kami melalui [nomor telepon/email].</p>
                        <p>Terima kasih,</p>'.MtrUtility::find(47)->informasi;
                        return view('recruitment.thank', compact('msg'));
                    }
                }else{
                    if(empty($trx->check_in)){
                        $msg = '<p>Yth. '.$trx->nama.',<br/>Dengan Hormat,</p>
                        <p>Kami ingin menginformasikan bahwa psikotest belum dapat dimulai karena belum sesuai dengan jadwal dan waktu yang telah diinformasikan.</p>
                        <p>Mohon kesediaannya untuk menunggu sesuai jadwal dan waktu yang telah diinformasikan. Apabila ada pertanyaan atau kebutuhan lainnya, jangan ragu untuk menghubungi kami melalui [nomor telepon/email].</p>
                        <p>Terima kasih,</p>'.MtrUtility::find(47)->informasi;
                        return view('recruitment.thank', compact('msg'));
                    }
                }
            }
            // $_id[2] = id-nya trx_tbl_recruitment_psychotest
            // if(!empty($_id[2])){
            if(!empty($_id[2]) && TrxRecruitmentPsychotest::where(['id_identitas' => $id, 'id' => $_id[2]])->exists()){
                $soal = TrxRecruitmentPsychotest::find($_id[2]);
                $soal->check_out = $_now;
                // $remain = $soal->remain - $_now->diffInSeconds(Carbon::parse($soal->check_in));
                // jika jenis=0 masih di pengantar belum dihitung mundur
                // if($soal->jenis == 0){
                //     $remain = $soal->remain;
                // }
                $soal->jawaban1 = $request->a;
                if($soal->jenis == 3){
                    $soal->jawaban1 = $request->p;
                    $soal->jawaban2 = $request->k;
                }
                $soal->update();
            }
            // check apakah ada tiu5 dan semua sudah dikerjakan
            if(TrxRecruitmentTiu5::where(['id_identitas' => $id])->whereNotNull('check_in')->whereNotNull('check_out')->whereNotNull('remain')->exists()){
                // jika sudah dikerjakan maka pengantar tidak diberikan
                if(TrxRecruitmentPsychotest::where(['id_identitas' => $id, 'jenis' => 0, 'nomor' => 0])->whereNull('check_in')->whereNull('check_out')->whereNull('remain')->exists()){
                    $soal = TrxRecruitmentPsychotest::where(['id_identitas' => $id])->whereNull('check_out')->orderBy('jenis', 'asc')->orderBy('nomor', 'asc')->first();
                    $soal->check_in = $_now;
                    $soal->check_out = $_now;
                    $soal->remain = $remain;
                    $soal->update();
                }
            }
            // $posisi = $_trx_->_pekerjaan->pekerjaan;
            $where = ['id_identitas' => $id];
            $soal = TrxRecruitmentPsychotest::where($where)->whereNull('check_out')->orderBy('jenis', 'asc')->orderBy('nomor', 'asc')->first();
            if(!empty($soal)){
                if((Carbon::now() > Carbon::parse($trx->check_in)) && (Carbon::now() < Carbon::parse($trx->check_out))){
                    $where = ['id_identitas' => $soal->id_identitas, 'jenis' => $soal->jenis];
                    $jumlah = TrxRecruitmentPsychotest::where($where)->where('nomor', '>', 0)->count();
                    $soal->check_in = $_now;
                    // if(empty($_id[2])){
                    //     $remain = $trx->remain;
                    // }
                    $soal->remain = $remain;
                    $soal->update();
                    // 
                    $trx->remain = $remain;
                    $trx->update();
                    if($soal->jenis == 0){
                        return view('recruitment.psychotest_pre', compact('trx','soal', 'jumlah'));
                    }else{
                        return view('recruitment.psychotest_exe', compact('trx','soal', 'jumlah'));
                    }
                }else{
                    $msg = '<p>Yth. '.$trx->nama.',<br/>Dengan Hormat,</p>
                    <p>Kami ingin menginformasikan bahwa psikotest belum dapat dimulai karena belum sesuai dengan jadwal dan waktu yang telah diinformasikan.</p>
                    <p>Mohon kesediaannya untuk menunggu sesuai jadwal dan waktu yang telah diinformasikan. Apabila ada pertanyaan atau kebutuhan lainnya, jangan ragu untuk menghubungi kami melalui [nomor telepon/email].</p>
                    <p>Terima kasih,</p>'.MtrUtility::find(47)->informasi;
                    return view('recruitment.thank', compact('msg'));
                }
            }else{
                if(empty($trx->check_in)){
                    $msg = '<p>Yth. '.$trx->nama.',<br/>Dengan Hormat,</p>
                    <p>Kami ingin menginformasikan bahwa psikotest belum dapat dimulai karena belum sesuai dengan jadwal dan waktu yang telah diinformasikan.</p>
                    <p>Mohon kesediaannya untuk menunggu sesuai jadwal dan waktu yang telah diinformasikan. Apabila ada pertanyaan atau kebutuhan lainnya, jangan ragu untuk menghubungi kami melalui [nomor telepon/email].</p>
                    <p>Terima kasih,</p>'.MtrUtility::find(47)->informasi;
                    return view('recruitment.thank', compact('msg'));
                }else{
                    // $posisi
                    $msg = '<p>Yth. '.$trx->nama.',<br/>Dengan hormat,</p>
                    <p>Kami ucapkan terima kasih atas partisipasi Bapak/Ibu/Saudara/i dalam proses psikotes untuk posisi '.$posisi.' di PT. Alita Praya Mitra. Kami telah menerima hasil pengerjaan psikotes Bapak/Ibu/Saudara/i dan saat ini kami sedang melakukan proses evaluasi lebih lanjut.</p>
                    <p>Kami akan segera menghubungi Bapak/Ibu/Saudara/i apabila terdapat informasi tambahan atau informasi jika ada tahapan seleksi selanjutnya.</p>
                    <p>Demikian kami sampaikan, terima kasih atas perhatian dan kerja samanya.</p>'.MtrUtility::find(47)->informasi;
                    // jika sudah tidak ada lagi soal setelah soal terakhir maka dikirimkan email ke kandidat
                    if(!empty($_id[2]) && !empty($request->je) && !empty($request->no) && !empty($request->a)){
                        $headers = [];
                        foreach ($request->headers->all() as $k => $v) {
                            $headers[$k] = $v;
                        };
                        $catatan = ['url'=>request()->fullUrl(),'method' => $request->method(),'ip' => $request->ip(),'user_agent' => $request->userAgent(),'headers' => $headers];
                        $arr = [$trx->id,5,$trx->nama,'','external',response()->json($catatan)];
                        $this->insertLog($arr);
                        // 
                        $trx->id_status = 5;
                        $trx->update();
                        // parameter yang dipassing ke sendemail
                        $mailData['title'] = 'Psikotes Selesai';
                        $mailData['data'] = ['msg'=>$msg];
                        $mailData['filename'] = 'emails.recruitment0';
                        $mailData['to'] = $trx->email;
                        $this->sendEmail($mailData);
                    }
                    return view('recruitment.thank', compact('msg'));
                }
            }
        } catch (DecryptException $e) {
            return view('errors.403');
        }
    }
    
    // digunakan jika gambar pohon dan manusia menjadi 2 halaman
    public function psychotest_file(Request $request)
    {
        try {
            // dd($request->all());
            $remain = 0;
            $_now = Carbon::now();
            $_id = Crypt::decrypt($request->id);
            $id = $_id[0];
            // $kode = $_id[1];
            if(!empty($_id[2])){
                $soal = TrxRecruitmentPsychotest::find($_id[2]);
                $soal->check_out = $_now;
                $remain = $soal->remain - $_now->diffInSeconds(Carbon::parse($soal->check_in));
                if(in_array($soal->jenis, [4,5])){
                    if($soal->jenis == 4){
                        $gambar_pohon = $request->file('gambar_pohon');
                        $request->validate([
                            'gambar_pohon' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                        ]);
                        $extension = $gambar_pohon->getClientOriginalExtension();
                        $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
                        $gambar_pohon->move('public/assets/recruitment/upload/', $namafile);
                        $soal->jawaban1 = $namafile;
                        $soal->update();
                    }
                    elseif($soal->jenis == 5){
                        $gambar_manusia = $request->file('gambar_manusia');
                        $request->validate([
                            'gambar_manusia' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                        ]);
                        $extension = $gambar_manusia->getClientOriginalExtension();
                        $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
                        $gambar_manusia->move('public/assets/recruitment/upload/', $namafile);
                        $soal->jawaban1 = $namafile;
                        $soal->update();
                    }
                }
            }
            $trx = TrxRecruitmentIdentitas::find($id);
            $where = ['id_identitas' => $id];
            $soal = TrxRecruitmentPsychotest::where($where)->whereNull('check_out')->orderBy('id', 'asc')->first();
            if(!empty($soal)){
                $where = ['id_identitas' => $soal->id_identitas, 'jenis' => $soal->jenis];
                $jumlah = TrxRecruitmentPsychotest::where($where)->where('nomor', '>', 0)->count();
                $soal->check_in = $_now;
                if(empty($_id[2])){
                    $remain = $trx->remain;
                }
                $soal->remain = $remain;
                $soal->update();
                // 
                $trx->remain = $remain;
                $trx->update();
                return view('recruitment.psychotest_exe', compact('trx','soal', 'jumlah'));
            }else{
                // update status 5=Psychotest Done
                $trx = TrxRecruitmentIdentitas::find($id);
                $trx->id_status = 5;
                $trx->update();
                // start
                // id_identitas,id_status,pic,pic_property,aktifitas,catatan
                $arr = [$trx->id,$trx->id_status,$trx->nama,'','external',''];
                $this->insertLog($arr);
                // 
                $_trx = TrxKebutuhanMaster::find($trx->id_kebutuhan);
                // $posisi = $_trx->_pekerjaan->pekerjaan;
                $kebutuhan_atribute = json_decode($trx->kebutuhan_atribute, true);
                $posisi = PekerjaanSub::find($kebutuhan_atribute['id_pekerjaan'])->pekerjaan;
                // end
                // kirim email
                // $posisi
                $msg = '<p>Yth. '.$trx->nama.',<br/>Dengan hormat,</p>
                <p>Kami ucapkan terima kasih atas partisipasi Bapak/Ibu/Saudara/i dalam proses psikotes untuk posisi '.$posisi.' di PT. Alita Praya Mitra. Kami telah menerima hasil pengerjaan psikotes Bapak/Ibu/Saudara/i dan saat ini kami sedang melakukan proses evaluasi lebih lanjut.</p>
                <p>Kami akan segera menghubungi Bapak/Ibu/Saudara/i apabila terdapat informasi tambahan atau informasi jika ada tahapan seleksi selanjutnya.</p>
                <p>Demikian kami sampaikan, terima kasih atas perhatian dan kerja samanya.</p>'.MtrUtility::find(47)->informasi;
                // parameter yang dipassing ke sendemail
                $mailData['title'] = 'Psikotes Selesai';
                $mailData['data'] = ['msg'=>$msg];
                $mailData['filename'] = 'emails.recruitment0';
                $mailData['to'] = $trx->email;
                $this->sendEmail($mailData);
                return view('recruitment.thank', compact('msg'));
            }
        } catch (DecryptException $e) {
            return view('errors.403');
        }
    }
    
    // digunakan jika gambar pohon dan manusia menjadi 1 halaman
    // public function psychotest_file(Request $request)
    // {
    //     try {
    //         // dd($request->all());
    //         $_now = Carbon::now();
    //         $_id = Crypt::decrypt($request->id);
    //         $id = $_id[0];
    //         // $kode = $_id[1];
    //         if(!empty($_id[2])){
    //             $soal = TrxRecruitmentPsychotest::find($_id[2]);
    //             $soal->check_out = $_now;
    //             if($soal->jenis == 4){
    //                 $gambar_pohon = $request->file('gambar_pohon');
    //                 $request->validate([
    //                     'gambar_pohon' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
    //                 ]);
    //                 $gambar_manusia = $request->file('gambar_manusia');
    //                 $request->validate([
    //                     'gambar_manusia' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
    //                 ]);
    //                 $extension = $gambar_pohon->getClientOriginalExtension();
    //                 $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
    //                 $gambar_pohon->move('public/assets/recruitment/upload/', $namafile);
    //                 $soal->jawaban1 = $namafile;
    //                 // 
    //                 $extension = $gambar_manusia->getClientOriginalExtension();
    //                 $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
    //                 $gambar_manusia->move('public/assets/recruitment/upload/', $namafile);
    //                 $soal->jawaban2 = $namafile;
    //             }
    //             $soal->update();
    //             // update status 5=Psychotest Done
    //             $trx = TrxRecruitmentIdentitas::find($id);
    //             $trx->id_status = 5;
    //             $trx->update();
    //             // insert interview
    //             // $this->insertInterview($trx);
    //             // kirim email
    //             // $posisi = $trx->_kebutuhan->_pekerjaan->pekerjaan;
    //             $msg = '<p>Hai '.$trx->nama.',<br/>Psikotes anda sudah selesai.
    //             <br/>Kami akan menggunakan email anda ('.$trx->email.') sebagai alamat email untuk korespondensi.<br/>Terima kasih,</p>'.MtrUtility::find(47)->informasi;
    //             // parameter yang dipassing ke sendemail
    //             $mailData['title'] = 'Psikotes Selesai';
    //             $mailData['data'] = ['msg'=>$msg];
    //             $mailData['filename'] = 'emails.recruitment0';
    //             $mailData['to'] = $trx->email;
    //             $this->sendEmail($mailData);
    //         }
    //         return view('recruitment.thank', compact('msg'));
    //     } catch (DecryptException $e) {
    //         return view('errors.403');
    //     }
    // }

    public function insertInterview($id)
    {
        // jenis=hco
        $trx = new TrxRecruitmentInterview;
        $trx->id_identitas = $id->id;
        // 6=Interview Next
        $trx->id_status = 6;
        $trx->jenis = 'hco';
        $trx->save();
        // jenis=user
        $trx = new TrxRecruitmentInterview;
        $trx->id_identitas = $id->id;
        // 6=Interview Next
        $trx->id_status = 6;
        $trx->jenis = 'user';
        $trx->save();
    }

    public function apply_save(Request $request)
    {
        // dd($request->all());
        $id = Crypt::decrypt($request->id);
        $id_recruitment = $id[0];
        // cek apakah sudah pernah melamar untuk info loker yang sama dengan email yang sama
        $where = ['id_recruitment'=>$id_recruitment, 'email'=>$request->email];
        if(TrxRecruitmentIdentitas::where($where)->exists()){
            $error = '<p>Yth. '.$request->nama.',<br/>Dengan hormat,</p>
            <p>Lamaran kerja Bapak/Ibu/Saudara/i sebelumnya telah kami terima dan tidak dapat mengajukan kembali untuk info lowongan yang sama.</p>
            <p>Terima kasih,</p>'.MtrUtility::find(47)->informasi;
            return redirect()->back()->with('error', $error);
        }
        $trx = TrxRecruitment::find($id_recruitment);
        $id_kebutuhan = $trx->id_kebutuhan;
        $trx = TrxKebutuhanMaster::find($id_kebutuhan);
        $posisi = $trx->_pekerjaan->pekerjaan;
        $id_status_kerja = $trx->id_status_kerja;
        $_data = json_encode([
            'id_pekerjaan'=>$trx->id_pekerjaan,
            'id_level'=>$trx->id_level,
            'id_area'=>$trx->id_area,
            'id_kota'=>$trx->id_kota,
            'atasan'=>$trx->atasan,
            'atasan_property'=>$trx->atasan_property,
            'id_perusahaan_kandidat'=>Divisi::find($trx->id_divisi_kandidat)->kode_perusahaan,
            'id_divisi_kandidat'=>$trx->id_divisi_kandidat,
            'id_dep_kandidat'=>$trx->id_dep_kandidat,
            'id_sub_dep_kandidat'=>$trx->id_sub_dep_kandidat,
        ]);
        // dd($id);
        $trx = new TrxRecruitmentIdentitas;
        $trx->id_recruitment = $id_recruitment;
        $trx->id_kebutuhan = $id_kebutuhan;
        // 3=Apply
        $trx->id_status = 3;
        // 
        $cv = $request->file('cv');
        $request->validate([
            'cv' => 'required|file|mimes:pdf|max:5120',
        ]);
        $extension = $cv->getClientOriginalExtension();
        $namafile = 'cv_'.now()->getTimestampMs().'.'.$extension;
        $cv->move('public/assets/recruitment/upload/', $namafile);
        $trx->cv = $namafile;
        // 
        $trx->nama = $request->nama;
        $trx->tempat_lahir = $request->tempat_lahir;
        $trx->tanggal_lahir = $request->tanggal_lahir;
        $trx->kelamin = $request->kelamin;
        $trx->ktp = $request->ktp;
        $trx->alamat_ktp = $request->alamat_ktp;
        $trx->alamat_domisili = $request->alamat_domisili;
        $trx->agama = $request->agama;
        $trx->npwp = $request->npwp;
        $trx->telepon = $request->telepon;
        // <option value="1">Ayah</option>
        // <option value="2">Ibu</option>
        // <option value="3">Suami</option>
        // <option value="4">Istri</option>
        // <option value="5">Saudara Kandung (Kakak/Adik)</option>
        // <option value="6">Lainnya</option>
        $trx->telepon_darurat = implode(',', [$request->id_darurat, $request->nama_darurat, $request->telepon_darurat]);
        if(!empty($request->darurat_lain)){
            $trx->telepon_darurat = implode(',', [$request->id_darurat, $request->nama_darurat, $request->darurat_lain, $request->telepon_darurat]);
        }
        $trx->email = $request->email;
        $trx->status_hidup = $request->status_hidup;
        $trx->tanggal_status = $request->tanggal_status;
        $trx->nama_pasangan = $request->nama_pasangan;
        $trx->usia_pasangan = $request->usia_pasangan;
        $trx->kelamin_pasangan = $request->kelamin_pasangan;
        $trx->pendidikan_pasangan = $request->pendidikan_pasangan;
        $trx->pekerjaan_pasangan = $request->pekerjaan_pasangan;
        $trx->nama_bapak = $request->nama_bapak;
        $trx->usia_bapak = $request->usia_bapak;
        $trx->pendidikan_bapak = $request->pendidikan_bapak;
        $trx->pekerjaan_bapak = $request->pekerjaan_bapak;
        $trx->nama_ibu = $request->nama_ibu;
        $trx->usia_ibu = $request->usia_ibu;
        $trx->pendidikan_ibu = $request->pendidikan_ibu;
        $trx->pekerjaan_ibu = $request->pekerjaan_ibu;
        $trx->hobi = $request->hobi;
        $trx->kelebihan = $request->kelebihan;
        $trx->peningkatan = $request->peningkatan;
        $trx->is_sakit = $request->is_sakit;
        $trx->sakit_apa = $request->sakit_apa;
        $trx->sakit_tahun = $request->sakit_tahun;
        $trx->sakit_kondisi = $request->sakit_kondisi;
        $trx->alasan = $request->alasan;
        $trx->gaji = str_replace(',', '', $request->gaji);
        $trx->pendidikan = $request->pendidikan;
        $trx->pekerjaan = $request->pekerjaan;
        $trx->usia = Carbon::parse($request->tanggal_lahir)->age;
        $trx->kebutuhan_atribute = $_data;
        $trx->save();
        $this->insertInterpretation($trx);
        // start
        // id_identitas,id_status,pic,pic_property,aktifitas,catatan
        $arr = [$trx->id,$trx->id_status,$trx->nama,'','external',''];
        $this->insertLog($arr);
        // end
        // TrxRecruitmentAnak
        $nama_anak = $request->input('nama_anak', []);
        $usia_anak = $request->input('usia_anak', []);
        $kelamin_anak = $request->input('kelamin_anak', []);
        $pendidikan_anak = $request->input('pendidikan_anak', []);
        $pekerjaan_anak = $request->input('pekerjaan_anak', []);
        for($x = 0; $x < count($nama_anak); $x++){
            $_trx = new TrxRecruitmentAnak;
            $_trx->id_identitas = $trx->id;
            $_trx->nama = $nama_anak[$x];
            $_trx->usia = $usia_anak[$x];
            $_trx->kelamin = $kelamin_anak[$x];
            $_trx->pendidikan = $pendidikan_anak[$x];
            $_trx->pekerjaan = $pekerjaan_anak[$x];
            $_trx->save();
        }
        // TrxRecruitmentSaudara
        // $kakak_adik = $request->input('kakak_adik', []);
        $nama_saudara = $request->input('nama_saudara', []);
        $usia_saudara = $request->input('usia_saudara', []);
        $kelamin_saudara = $request->input('kelamin_saudara', []);
        $pendidikan_saudara = $request->input('pendidikan_saudara', []);
        $pekerjaan_saudara = $request->input('pekerjaan_saudara', []);
        for($x = 0; $x < count($nama_saudara); $x++){
            $_trx = new TrxRecruitmentSaudara;
            $_trx->id_identitas = $trx->id;
            // $_trx->kakak_adik = $kakak_adik[$x];
            $_trx->nama = $nama_saudara[$x];
            $_trx->usia = $usia_saudara[$x];
            $_trx->kelamin = $kelamin_saudara[$x];
            $_trx->pendidikan = $pendidikan_saudara[$x];
            $_trx->pekerjaan = $pekerjaan_saudara[$x];
            $_trx->save();
        }
        // TrxRecruitmentFormal
        $jenjang_sekolah = $request->input('jenjang_sekolah', []);
        $nama_formal = $request->input('nama_formal', []);
        $kota_sekolah = $request->input('kota_sekolah', []);
        $jurusan = $request->input('jurusan', []);
        $tahun_masuk_sekolah = $request->input('tahun_masuk_sekolah', []);
        $tahun_lulus_sekolah = $request->input('tahun_lulus_sekolah', []);
        $nilai = $request->input('nilai', []);
        for($x = 0; $x < count($jenjang_sekolah); $x++){
            $_trx = new TrxRecruitmentFormal;
            $_trx->id_identitas = $trx->id;
            $_trx->jenjang = $jenjang_sekolah[$x];
            $_trx->nama = $nama_formal[$x];
            $_trx->kota = $kota_sekolah[$x];
            $_trx->jurusan = $jurusan[$x];
            $_trx->tahun_masuk = $tahun_masuk_sekolah[$x];
            $_trx->tahun_lulus = $tahun_lulus_sekolah[$x];
            $_trx->nilai = $nilai[$x];
            $_trx->save();
        }
        // TrxRecruitmentInformal
        $bidang_informal = $request->input('bidang_informal', []);
        $tahun_informal = $request->input('tahun_informal', []);
        $lembaga = $request->input('lembaga', []);
        $sertifikat = $request->input('sertifikat', []);
        for($x = 0; $x < count($bidang_informal); $x++){
            $_trx = new TrxRecruitmentInformal;
            $_trx->id_identitas = $trx->id;
            $_trx->bidang = $bidang_informal[$x];
            $_trx->tahun = $tahun_informal[$x];
            $_trx->lembaga = $lembaga[$x];
            $_trx->sertifikat = $sertifikat[$x];
            $_trx->save();
        }
        // TrxRecruitmentOrganisasi
        $nama_organisasi = $request->input('nama_organisasi', []);
        $periode = $request->input('periode', []);
        $jabatan_organisasi = $request->input('jabatan_organisasi', []);
        $keterangan = $request->input('keterangan', []);
        for($x = 0; $x < count($nama_organisasi); $x++){
            $_trx = new TrxRecruitmentOrganisasi;
            $_trx->id_identitas = $trx->id;
            $_trx->nama = $nama_organisasi[$x];
            $_trx->periode = $periode[$x];
            $_trx->jabatan = $jabatan_organisasi[$x];
            $_trx->keterangan = $keterangan[$x];
            $_trx->save();
        }
        // TrxRecruitmentPekerjaan
        $nama_perusahaan = $request->input('nama_perusahaan', []);
        $kota_perusahaan = $request->input('kota_perusahaan', []);
        $tahun_masuk_perusahaan = $request->input('tahun_masuk_perusahaan', []);
        $tahun_keluar_perusahaan = $request->input('tahun_keluar_perusahaan', []);
        $jabatan_perusahaan = $request->input('jabatan_perusahaan', []);
        $upah = $request->input('upah', []);
        $alasan_keluar = $request->input('alasan_keluar', []);
        for($x = 0; $x < count($nama_perusahaan); $x++){
            $_trx = new TrxRecruitmentPekerjaan;
            $_trx->id_identitas = $trx->id;
            $_trx->nama = $nama_perusahaan[$x];
            $_trx->kota = $kota_perusahaan[$x];
            $_trx->tahun_masuk = $tahun_masuk_perusahaan[$x];
            $_trx->tahun_keluar = $tahun_keluar_perusahaan[$x];
            $_trx->jabatan = $jabatan_perusahaan[$x];
            $_trx->gaji = str_replace(',', '', $upah[$x]);
            $_trx->alasan = $alasan_keluar[$x];
            $_trx->save();
        }
        $nomor = $trx->id_kebutuhan.'/'.$trx->id;
        $msg = '<p>Yth. '.$trx->nama.',<br/>Dengan hormat,</p>
        <p>Kami sampaikan terima kasih atas minat Bapak/Ibu/Saudara/i dengan melamar pada posisi '.$posisi.' di PT. Alita Praya Mitra. Lamaran kerja Bapak/Ibu/Saudara/i telah kami terima dan akan kami pelajari lebih lanjut sesuai kualifikasi yang dibutuhkan.</p>
        <p>Proses seleksi akan memerlukan waktu. Jika dalam kurun waktu 14 (empat belas) hari kerja kami belum menghubungi Bapak/Ibu/Saudara/i, kami sampaikan bahwa lamaran Bapak/Ibu/Saudara/i belum memenuhi kualifikasi yang kami butuhkan. Mohon untuk memastikan bahwa  email anda  '.$trx->email.' dan nomor telepon yang di cantumkan dalam keadaan aktif untuk korespondensi.</p>
        <p>Terima kasih,</p>'.MtrUtility::find(47)->informasi;
        // parameter yang dipassing ke sendemail
        $mailData['title'] = 'Lamaran Pekerjaan';
        $mailData['data'] = ['msg'=>$msg];
        $mailData['filename'] = 'emails.recruitment0';
        $mailData['to'] = $trx->email;
        $this->sendEmail($mailData);
        // start
        // CM 320
        // 8=Konsultan
        // jika id_status_kerja=8 maka Menghilangkan tahapan psikotest dan wawancara pada list of Applicant untuk kebutuhan karyawan dengan status kerja Konsultan
        // langsung ke isian 16=Interpretation
        if($id_status_kerja == 8){
            // 37=PIC Recruitment
            $nik = MtrUtility::find(37)->informasi;
            $pic_hco = Karyawan::where(['nik'=>$nik])->first()->nama.'/'.$nik;
            // 11=Interview PC Done
            // 22=Psychotest and Interview Pass
            $trx->id_status = 22;
            $trx->save();
            // id_identitas,id_status,pic,pic_property,aktifitas,catatan
            // 4=Psychotest Send
            $arr = [$trx->id,4,$pic_hco,'','internal','NA'];
            $this->insertLog($arr);
            // 5=Psychotest Done
            $arr = [$trx->id,5,$trx->nama,'','external','NA'];
            $this->insertLog($arr);
            // 10=Interview PC Send
            $arr = [$trx->id,10,$pic_hco,'','internal','NA'];
            $this->insertLog($arr);
            // 11=Interview PC Done
            $arr = [$trx->id,11,$pic_hco,'','internal','NA'];
            $this->insertLog($arr);
            // 22=Psychotest and Interview Pass
            $arr = [$trx->id,22,$pic_hco,'','internal','NA'];
            $this->insertLog($arr);
        }
        // end
        // return redirect()->view('recruitment.thank', compact('msg'))->withInput();
        // return back()->with(['message' => $msg]);
        return view('recruitment.thank', compact('msg'));
    }

    public function sendEmail($mailData)
    {
        $ccEmails = explode(',', MtrUtility::find(11)->informasi);
        if(!empty($mailData['cc'])){
            foreach($mailData['cc'] as $cc){
                array_push($ccEmails, $cc);
            }
        }
        // dd($ccEmails);
        Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
    }

    public static function getTanggalIndonesia($tgl)
    {
        $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $strTgl = $hari[Carbon::createFromFormat('Y-m-d', $tgl)->dayOfWeek] . ', ';
        $strTgl .= Carbon::createFromFormat('Y-m-d', $tgl)->format('d-m-Y');
        return $strTgl;
    }

    public function psychotestexcel(Request $request, $id)
    {
        $allowed_nik = ['P0826165'];
        if (!auth()->user()->can('hc') && !in_array(auth()->user()->nik, $allowed_nik)) {
            return redirect()->route('error-403');
        }
        $id = Crypt::decrypt($id);
        // dd($request->all(), $id);
        $filename = '';
        if($id[1] == 1){
            $filename = 'EPPS_';
        }elseif($id[1] == 2){
            $filename = 'PAPI KOSTIC_';
        }elseif($id[1] == 3){
            $filename = 'DISC_';
        }elseif($id[1] == 6){
            $filename = 'TIU5_';
        }
        return Excel::download(new TrxRecruitmentPsychotestExport($request,$id), $filename.$id[0].'_'.now()->getTimestampMs().'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        // if($id[1] == 1){
        //     return Excel::download(new TrxRecruitmentPsychotestExport($request,$id), 'psychotest_'.$id[0].'_'.now()->getTimestampMs().'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        // }elseif($id == 2){
        //     return Excel::download(new TrxRecruitmentPsychotestExport($request,$id), 'psychotest_'.$id[0].'_'.now()->getTimestampMs().'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        // }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $type = $request->type;
        $back_url = $request->back_url;
        $trx = TrxRecruitmentIdentitas::find($id);
        // dd($id, $type, $back_url, $trx);
        return view('recruitment.edit_page', compact('trx', 'type', 'back_url'));
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
        $id = Crypt::decrypt($id);
        $type = $request->type;
        $back_url = $request->back_url;
        $trx = TrxRecruitmentIdentitas::find($id);
        $trx->nama = $request->nama;
        $trx->email = $request->email;
        $trx->telepon = $request->telepon;
        $trx->update();
        // dd($id, $type, $back_url, $trx);
        // return redirect('recruitment/applicant_list/0')->with('msg', 'Data is saved successfully.');
        return redirect()->to($back_url)->with('msg', 'Data is saved successfully.');
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
}
