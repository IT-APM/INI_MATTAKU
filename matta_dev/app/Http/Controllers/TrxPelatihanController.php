<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
// 
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Level;
// 
use App\Models\PelatihanJenis;
use App\Models\PelatihanAnggaran;
use App\Models\PelatihanBenefit;
use App\Models\PelatihanStatus;
use App\Models\MtrPelatihanPenilaian;
use App\Models\TrxPelatihan;
use App\Models\TrxPelatihanLog;
use App\Models\TrxPelatihanSertifikat;
use App\Models\TrxPelatihanPeserta;
use App\Models\TrxPelatihanDokumen;
use App\Models\TrxPelatihanPenilaian;
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
use Illuminate\Support\Facades\Gate;

class TrxPelatihanController extends Controller
{
    // public function __construct(Gate $gate)
    public function __construct()
    {
        $this->middleware('can:spv', ['except' => ['show','update']]);
    }

    public function downloadDriveFile(Request $request)
    {
        // $driveUrl = 'https://drive.google.com/file/d/1bdhv9OAr6jEWsClt5Me7mqeJ1K3CnNru/view?usp=sharing';

        // if (!preg_match('/\/d\/(.*?)\//', $driveUrl, $matches)) {
        //     return response('Invalid Google Drive URL.', 400);
        // }

        // $fileId = $matches[1];
        // $downloadUrl = "https://drive.google.com/uc?export=download&id={$fileId}";
        // $filename = $fileId . '.pdf';
        // $savePath = public_path("assets/pelatihan/{$filename}");

        // // Download file pakai cURL
        // $fp = fopen($savePath, 'w+');
        // $ch = curl_init($downloadUrl);

        // curl_setopt($ch, CURLOPT_FILE, $fp);
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_exec($ch);

        // if (curl_errno($ch)) {
        //     fclose($fp);
        //     unlink($savePath);
        //     return response('Gagal download file: ' . curl_error($ch), 500);
        // }

        // curl_close($ch);
        // fclose($fp);

        // // Setelah disimpan, kirim file ke user
        // return response()->download($savePath);
    }

    public function autoSave(Request $request)
    {
        // // Contoh file Google Drive
        // $fileId = '1bdhv9OAr6jEWsClt5Me7mqeJ1K3CnNru';
        // $downloadUrl = "https://drive.google.com/uc?export=download&id={$fileId}";

        // // Lokasi penyimpanan
        // $fileName = $fileId . '.pdf';
        // $savePath = public_path("assets/pelatihan/{$fileName}");

        // // Buat folder jika belum ada
        // // if (!file_exists(dirname($savePath))) {
        // //     mkdir(dirname($savePath), 0755, true);
        // // }

        // // Download & simpan menggunakan cURL
        // $fp = fopen($savePath, 'w+');
        // $ch = curl_init($downloadUrl);

        // curl_setopt($ch, CURLOPT_FILE, $fp);
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // curl_exec($ch);

        // // Error handling
        // if (curl_errno($ch)) {
        //     fclose($fp);
        //     unlink($savePath);
        //     return response()->json(['error' => curl_error($ch)], 500);
        // }

        // curl_close($ch);
        // fclose($fp);

        // // Optional: Auto kirim file ke user
        // // return response()->download($savePath);

        // // Atau hanya info sukses
        // return response()->json([
        //     'message' => 'File berhasil disimpan.',
        //     'saved_to' => "assets/pelatihan/{$fileName}"
        // ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $trx = TrxPelatihanSertifikat::orderBy('id', 'asc')->get();
        // $this->autoSave($request);
        // $_response = [];
        // foreach($trx as $_trx){
        //     $url = $_trx->formulir_link;
        //     $fileName = $_trx->id;
        //     if(!empty($url) && str_starts_with($url, 'https')){
        //         $parts = explode('/d/', $url);
        //         $idPart = explode('/', $parts[1]);
        //         $fileId = $idPart[0];
        //         $filePath = public_path('assets/pelatihan/formulir_' . $fileName . '.pdf');
        //         // Mulai cURL
        //         $fp = fopen($filePath, 'w+');
        //         $ch = curl_init('https://drive.google.com/uc?export=download&id='.$fileId);
        //         // $downloadUrl = 'https://drive.google.com/file/d/1bdhv9OAr6jEWsClt5Me7mqeJ1K3CnNru/view?usp=sharing';
        //         $downloadUrl = 'https://drive.google.com/uc?export=download&id=1bdhv9OAr6jEWsClt5Me7mqeJ1K3CnNru';
        //         return view('formulir.pelatihan.opentab', compact('downloadUrl'));
        //         curl_setopt($ch, CURLOPT_FILE, $fp);
        //         curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        //         curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        //         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //         curl_exec($ch);

        //         // Cek error
        //         if (curl_errno($ch)) {
        //             fclose($fp);
        //             unlink($filePath); // Hapus file yang gagal didownload
        //             return response()->json(['error' => curl_error($ch)], 500);
        //         }

        //         curl_close($ch);
        //         fclose($fp);

        //         return response()->json([
        //             'message' => 'File berhasil didownload.',
        //             'path' => "assets/pelatihan/{$fileId}.pdf"
        //         ]);
        //         // dd($fileId);
        //         // $savePath = public_path('assets/pelatihan/formulir_' . $fileName . '.pdf');
        //         // $downloadUrl = Http::get('https://drive.google.com/uc?export=download&id='.$fileId);
        //         // try {
        //         //     $response = Http::get('https://drive.google.com/uc?export=download&id='.$fileId);
        //         //     if ($response->successful()) {
        //         //         $contentType = $response->header('Content-Type');
        //         //         if (!Str::startsWith($contentType, 'application/pdf')) {
        //         //             return response()->json([
        //         //                 'error' => 'File bukan PDF atau tidak bisa langsung didownload. Kemungkinan Google Drive mengembalikan HTML.',
        //         //                 'content_type' => $contentType
        //         //             ], 400);
        //         //         }else{
        //         //             // Optional: ambil nama file dari header jika ada
        //         //             $contentDisposition = $response->header('Content-Disposition');
        //         //             preg_match('/filename="(.*?)"/', $contentDisposition ?? '', $filenameMatch);
        //         //             $fileName = $filenameMatch[1] ?? $fileId . '.file'; // fallback
        //         //             // Simpan file di public/assets/pelatihan
        //         //             $savePath = public_path('assets/pelatihan/' . $fileName);
        //         //             file_put_contents($savePath, $response->body());
        //         //             array_push($_response, [
        //         //                 'message' => 'File berhasil didownload dan disimpan.',
        //         //                 'path' => $savePath
        //         //             ], 200);
        //         //         }
        //         //     } else {
        //         //         array_push($_response, [
        //         //             'message' => 'Gagal mendownload file.',
        //         //             'status' => $response->status()
        //         //         ], 400);
        //         //     }
        //         // } catch (\Exception $e) {
        //         //     return response()->json([
        //         //         'error' => 'Terjadi kesalahan: ' . $e->getMessage()
        //         //     ], 500);
        //         // }
        //         // if ($response->successful()) {
        //         //     file_put_contents($savePath, $response->body());
        //             // array_push($_response, [
        //             //     'message' => 'File berhasil didownload dan disimpan.',
        //             //     'path' => $savePath
        //             // ], 200);
        //         // } else {
        //             // array_push($_response, [
        //             //     'message' => 'Gagal mendownload file.',
        //             //     'status' => $response->status()
        //             // ], 400);
        //         // }
        //         return response()->json($_response);
        //     }
        // }
        // return response()->json($_response);
        // echo($this->downloadDriveFile($request));
        // dd($trx);
        $no = 0;
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $trx = TrxPelatihan::orderBy('id', 'desc')->get();
        if(Gate::allows('hc_nkp')){
            $trx = TrxPelatihan::whereIn('pengaju',$arr_nik_nkp)->orderBy('id', 'desc')->get();
        }
        // foreach ($trx as $value) {
        //     $trxM = TrxPelatihan::find( $value->id );
        //     $trxM->pengaju_property = $this->getNikProperty($value->pengaju);
        //     $trxM->atasan_pengaju_property = $this->getNikProperty($value->atasan_pengaju);
        //     $trxM->reviewer1_property = $this->getNikProperty($value->reviewer1);
        //     $trxM->reviewer2_property = $this->getNikProperty($value->reviewer2);
        //     // 
        //     $peserta_property = array();
        //     $_peserta = explode(';', $value->peserta);
        //     foreach ($_peserta as $_value) {
        //         array_push($peserta_property, $this->getNikProperty($_value));
        //     }
        //     $trxM->peserta_property = implode(';', $peserta_property);
        //     // 
        //     $atasan_peserta_property = array();
        //     $_atasan_peserta = explode(';', $value->atasan_peserta);
        //     foreach ($_atasan_peserta as $_value) {
        //         array_push($atasan_peserta_property, $this->getNikProperty($_value));
        //     }
        //     $trxM->atasan_peserta_property = implode(';', $atasan_peserta_property);
        //     // 
        //     $trxM->update();
        // }
        if(auth()->user()->userrole == 'nhc'){
            $where = ['pengaju' => auth()->user()->nik];
            $trx = TrxPelatihan::where($where)->orderBy('id', 'desc')->get();
            // 
            $arr_id = [];
            foreach($trx as $_trx){
                array_push($arr_id, $_trx->id);
            }
            $where = ['nik_pic' => auth()->user()->nik];
            $trx = TrxPelatihanLog::where($where)->orderBy('id_trx', 'desc')->get();
            foreach($trx as $_trx){
                array_push($arr_id, $_trx->id_trx);
            }
            // 
            $trx = TrxPelatihan::whereIn('id', $arr_id)->orderBy('id', 'desc')->get();
        }
        // filter parameter
        $arr_status_pengajuan = array();
        $arr_pengaju = array();
        $arr_atasan_pengaju = array();
        foreach ($trx as $data){
            array_push($arr_status_pengajuan, $data->status_pengajuan);
            array_push($arr_pengaju, $data->pengaju);
            array_push($arr_atasan_pengaju, $data->atasan_pengaju);
        }
        $status_pengajuan = PelatihanStatus::whereIn('id', $arr_status_pengajuan)->orderBy('id', 'asc')->get();
        $pengaju = Karyawan::whereIn('nik', $arr_pengaju)->orderBy('nama', 'asc')->get();
        $atasan_pengaju = Karyawan::whereIn('nik', $arr_atasan_pengaju)->orderBy('nama', 'asc')->get();
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
            $_status_pengajuan = $request->input('status_pengajuan', []);
            $_pengaju = $request->input('pengaju', []);
            $_atasan_pengaju = $request->input('atasan_pengaju', []);
            if(count($_status_pengajuan) > 0){
                $arr_status_pengajuan = $_status_pengajuan;
            }
            else{
                $arr_status_pengajuan = array();
            }
            if(count($_pengaju) > 0){
                $arr_pengaju = $_pengaju;
            }
            // else{
            //     $arr_pengaju = array();
            // }
            if(count($_atasan_pengaju) > 0){
                $arr_atasan_pengaju = $_atasan_pengaju;
            }
            // else{
            //     $arr_atasan_pengaju = array();
            // }
            $trx = TrxPelatihan::whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->whereIn('status_pengajuan', $arr_status_pengajuan)->whereIn('pengaju', $arr_pengaju)->whereIn('atasan_pengaju', $arr_atasan_pengaju)->orderBy('id', 'desc')->get();
            if(Gate::allows('hc_nkp')){
                $trx = TrxPelatihan::whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->whereIn('pengaju',$arr_nik_nkp)->whereIn('status_pengajuan', $arr_status_pengajuan)->whereIn('pengaju', $arr_pengaju)->whereIn('atasan_pengaju', $arr_atasan_pengaju)->orderBy('id', 'desc')->get();
            }
            if(auth()->user()->userrole == 'nhc'){
				$where = ['pengaju' => auth()->user()->nik];
                $trx = TrxPelatihan::whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->whereIn('status_pengajuan', $arr_status_pengajuan)->whereIn('pengaju', $arr_pengaju)->whereIn('atasan_pengaju', $arr_atasan_pengaju)->where($where)->orderBy('id', 'desc')->get();
                // 
                $arr_id = [];
                foreach($trx as $_trx){
                    array_push($arr_id, $_trx->id);
                }
                $where = ['nik_pic' => auth()->user()->nik];
                $trx = TrxPelatihanLog::where($where)->orderBy('id_trx', 'desc')->get();
                foreach($trx as $_trx){
                    array_push($arr_id, $_trx->id_trx);
                }
                // 
                $trx = TrxPelatihan::whereIn('id', $arr_id)->whereIn('status_pengajuan', $arr_status_pengajuan)->whereIn('pengaju', $arr_pengaju)->whereIn('atasan_pengaju', $arr_atasan_pengaju)->orderBy('id', 'desc')->get();
            }
        }else{
            $arr_status_pengajuan = array();
            $arr_pengaju = array();
            $arr_atasan_pengaju = array();
        }
        // end filter
        return view('formulir.pelatihan.index', compact('trx', 'no', 'status_pengajuan', 'pengaju', 'atasan_pengaju','arr_status_pengajuan', 'arr_pengaju', 'arr_atasan_pengaju', 'tanggal_mulai', 'tanggal_akhir'));
        // return view('formulir.pelatihan.index', compact('trx', 'no'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // start
        // $trx = TrxPelatihan::orderBy('id', 'desc')->get();
        // foreach($trx as $_trx){
        //     $id = $_trx->id;
        //     $check = TrxPelatihanPeserta::where(['id_trx'=>$id])->exists();
        //     if(!$check){
        //         $peserta = explode(';', $_trx->peserta);
        //         foreach($peserta as $_peserta){
        //             $_data = new TrxPelatihanPeserta;
        //             $_data->id_trx = $id;
        //             $_data->nik = $_peserta;
        //             $items = array_filter(array_map('trim', explode(';', $_trx->peserta_property)));
        //             $_items = [];
        //             foreach ($items as $item) {
        //                 $_items[] = json_decode($item, true);
        //             }
        //             $_data->nik_property = json_encode(collect($_items)->firstWhere('nik', $_peserta));
        //             $_data->save();
        //             // 
        //             $materi = MtrPelatihanPenilaian::orderBy('id', 'asc')->get();
        //             foreach($materi as $_materi){
        //                 $data = new TrxPelatihanPenilaian;
        //                 $data->id_trx = $id;
        //                 $data->id_peserta = $_data->id;
        //                 $data->nik = $_peserta;
        //                 $data->urutan = $_materi->id;
        //                 $data->label = $_materi->label;
        //                 $data->nomor = $_materi->nomor;
        //                 $data->materi1 = $_materi->materi1;
        //                 $data->materi2 = $_materi->materi2;
        //                 $data->tipe = $_materi->tipe;
        //                 $data->save();
        //             }
        //         }
        //     }
        // }
        // end
        $status = 'create';
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        $jenis = PelatihanJenis::orderBy('jenis', 'asc')->get();
        $int_ext = collect([
            'ext' => 'Eksternal',
            'int' => 'Internal',
        ]);
        $anggaran = PelatihanAnggaran::orderBy('anggaran', 'asc')->get();
        // tidak digunakan
        // 2=Sertifikat
        $benefit = PelatihanBenefit::whereNotIn('id', [2])->orderBy('benefit', 'asc')->get();
        // reviewer level minimal 3 = spv
        $where = [ 'is_aktif' => 1];
        $reviewer = Karyawan::where($where)->where('kode_level','>=',3)->orderBy('nama', 'asc')->get();
        // dd($int_ext);
        return view('formulir.pelatihan.insert', compact('karyawan', 'jenis', 'anggaran', 'benefit', 'reviewer', 'status', 'int_ext'));
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
        // nik pengaju di hardcode 01031504369
        // Abi Ibadallah
        // Minimal level pengaju adalah spv
        // $pengaju = '01031504369';
        $pengaju = auth()->user()->nik;
        $where = ['nik' => $pengaju, 'is_aktif' => 1];
        $karyawan = Karyawan::where($where)->first();
        $atasan_pengaju = $karyawan->layer1;
        // 
        $trx = new TrxPelatihan;
        $status_pengajuan = 1;
        $trx->pengaju = $pengaju;
        $trx->atasan_pengaju = $atasan_pengaju;
        $trx->peserta = implode(';', $request->peserta);
        $atasan_peserta = Karyawan::select('layer1')->whereIn('nik', $request->peserta)->get();
        $nik_atasan_peserta = array();
        foreach ($atasan_peserta as $value) {
            array_push($nik_atasan_peserta, $value->layer1);
        }
        $trx->atasan_peserta = implode(';', $nik_atasan_peserta);
        if($request->has('reviewer1')){
            $trx->reviewer1 = $request->reviewer1;
            $trx->reviewer1_property = $this->getNikProperty($request->reviewer1);
        }
        if($request->has('reviewer2')){
            $trx->reviewer2 = $request->reviewer2;
            $trx->reviewer2_property = $this->getNikProperty($request->reviewer2);
        }
        $_pengaju = $this->getNikProperty($pengaju);
        $trx->pengaju_property = $_pengaju;
        $_atasan_pengaju = $this->getNikProperty($atasan_pengaju);
        $trx->atasan_pengaju_property = $_atasan_pengaju;
        $peserta_property = array();
        foreach ($request->peserta as $value) {
            array_push($peserta_property, $this->getNikProperty($value));
        }
        $trx->peserta_property = implode(';', $peserta_property);
        $atasan_peserta_property = array();
        foreach ($atasan_peserta as $value) {
            array_push($atasan_peserta_property, $this->getNikProperty($value->layer1));
        }
        $trx->atasan_peserta_property = implode(';', $atasan_peserta_property);
        // return response()->json(['response' => $trx->atasan_peserta_property]);die;
        $where = ['pengaju' => $pengaju, 'tahun' => $request->tahun];
        $ke = TrxPelatihan::where($where)->count() + 1;
        $trx->ke = $ke;
        $trx->tahun = $request->tahun;
        $trx->jenis = implode(';', $request->jenis);
        $trx->int_ext = $request->int_ext;
        $trx->judul = $request->judul;
        $trx->penyelenggara = $request->penyelenggara;
        // tidak digunakan diganti dengan tanggal_range
        // $trx->tanggal_mulai = $request->tanggal_mulai;
        // $trx->tanggal_akhir = $request->tanggal_akhir;
        $trx->tanggal_range = json_encode($request->input('tanggal_range', []), JSON_UNESCAPED_SLASHES);
        $trx->tempat = $request->tempat;
        $trx->biaya = str_replace(',', '', $request->biaya);
        $trx->benefit = implode(';', $request->benefit);
        $trx->anggaran = $request->anggaran;
        $trx->keterangan = $request->keterangan;
        $trx->status_pengajuan = $status_pengajuan;
        $trx->catatan = $request->catatan;
        if($request->has('lampiran')){
            $lampiran = $request->file('lampiran');
            $request->validate([
                'lampiran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);
            $extension = $lampiran->getClientOriginalExtension();
            $namafile = $pengaju.'_'.now()->getTimestampMs().'.'.$extension;
            $lampiran->move('public/assets/pelatihan/', $namafile);
            $trx->lampiran = $namafile;
        }
        $trx->save();
        $this->insertLog($trx->id, $status_pengajuan, $pengaju, $request->keterangan);
        // 
        $_pengaju_ = json_decode($_pengaju, true);
        $_atasan_pengaju_ = json_decode($_atasan_pengaju, true);
        // parameter yang dipassing ke sendemail
        $mailData['title'] = 'Formulir Pengajuan Pelatihan';
        $mailData['data'] = ['nama'=>$_pengaju_['nama'].'/'.$_pengaju_['nik'], 'jabatan'=>$_pengaju_['jabatan'], 'unit_kerja'=>$_pengaju_['divisi'].'/'.$_pengaju_['dep'].'/'.$_pengaju_['sub_dep'], 'link'=> route('pelatihan.show', [(new OpenDataController)->encryptWithKey($trx->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($_atasan_pengaju_['nik']), 'id_status' => (new OpenDataController)->encryptWithKey($status_pengajuan), 'pic' => (new OpenDataController)->encryptWithKey('atasan_pengaju')]), 'trx'=>$trx ];
        $mailData['filename'] = 'emails.pelatihan0';
        $mailData['to'] = $_atasan_pengaju_['email'];
        $todolist = ['id_trx' => $trx->id, 'nik' => $_atasan_pengaju_['nik'], 'id_status' => $status_pengajuan, 'pic' => 'atasan_pengaju'];
        $this->insertToDoList($todolist, $mailData);
        $this->sendEmail($mailData);
        // 
        return redirect('pelatihan')->with('msg', 'Data is saved successfully.');
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

    public function insertLog($id_trx, $id_status, $nik_pic, $keterangan)
    {
        $data = new TrxPelatihanLog;
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
        // disable ketika uji coba
        $ccEmails = explode(',', MtrUtility::find(11)->informasi);
        Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
    }

    public function checkApprovalLine($id)
    {
        // check para approver, jika sudah semua kirimkan email ke pengaju
        // start
        $trx = TrxPelatihan::find($id);
        $check = true;
        $approver = array();
        $approver['atasan_pengaju'] = $trx->atasan_pengaju;
        $approver['atasan_peserta'] = $trx->atasan_peserta;
        if(!empty($trx->reviewer1)){
            $approver['reviewer1'] = $trx->reviewer1;
        }
        if(!empty($trx->reviewer2)){
            $approver['reviewer2'] = $trx->reviewer2;
        }
        // atasan_pengaju
        // 2 = Approve by L1 Requestor
        $where = ['id_trx' => $id, 'nik_pic' => $approver['atasan_pengaju'], 'id_status' => 2];
        if(TrxPelatihanLog::where($where)->exists()){
            $check = $check && true;
        }else{
            $check = $check && false;
        }
        // atasan_peserta
        // 5 = Approve by L1 Participants
        foreach(explode(';', $approver['atasan_peserta']) as $value){
            $where = ['id_trx' => $id, 'nik_pic' => $value, 'id_status' => 5];
            if(TrxPelatihanLog::where($where)->exists()){
                $check = $check && true;
            }else{
                $check = $check && false;
            }
        }
        // reviewer1
        // 10 = Approve by Reviewer 2
        if(!empty($trx->reviewer1)){
            $where = ['id_trx' => $id, 'nik_pic' => $approver['reviewer1'], 'id_status' => 10];
            if(TrxPelatihanLog::where($where)->exists()){
                $check = $check && true;
            }else{
                $check = $check && false;
            }
        }
        // reviewer2
        // 14 = Approve by Reviewer 3
        if(!empty($trx->reviewer2)){
            $where = ['id_trx' => $id, 'nik_pic' => $approver['reviewer2'], 'id_status' => 14];
            if(TrxPelatihanLog::where($where)->exists()){
                $check = $check && true;
            }else{
                $check = $check && false;
            }
        }
        if($check){
            // parameter yang dipassing ke sendemail
            $pengaju = json_decode($trx->pengaju_property, true);
            $mailData['title'] = 'Formulir Pengajuan Pelatihan';
            $mailData['filename'] = 'emails.pelatihan0';
            // tidak digunakan
            // $mailData['data'] = ['nama'=>$pengaju['nama'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'link'=> route('pelatihan.edit', [(new OpenDataController)->encryptWithKey($id)]), 'trx'=>$trx];
            // diganti dengan ini
            $mailData['data'] = ['nama'=>$pengaju['nama'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'link'=> route('pelatihan.show', [(new OpenDataController)->encryptWithKey($id), 'view' => (new OpenDataController)->encryptWithKey('detail')]), 'trx'=>$trx];
            $hco = explode(',', MtrUtility::find(29)->informasi);
            foreach($hco as $data){
                $_hco = explode('/', $data);
                $mailData['to'] = $_hco[1];
                $todolist = ['id_trx' => $trx->id, 'nik' => $_hco[0], 'id_status' => $trx->status_pengajuan, 'pic' => 'hco'];
                $this->insertToDoList($todolist, $mailData);
                $this->sendEmail($mailData);
            }
        }
        // finish
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
        // dd($request->all(), (new OpenDataController)->decryptWithKey($request->view), (new OpenDataController)->decryptWithKey($request->nik), (new OpenDataController)->decryptWithKey($request->id_status), (new OpenDataController)->decryptWithKey($request->pic), (new OpenDataController)->decryptWithKey($id));
        $linkAction = route('pelatihan.update', $id);
        $id = (new OpenDataController)->decryptWithKey($id);
        $back_url = $request->back_url;
        // 
        $where = ['id_trx' => $id];
        $history = TrxPelatihanLog::where($where)->orderBy('created_at', 'desc')->get();
        $trx = TrxPelatihan::find($id);
        // ambil property para pihak
        $pengaju = json_decode($trx->pengaju_property, true);
        $atasan_pengaju = json_decode($trx->atasan_pengaju_property, true);
        // return response()->json(['atasan_pengaju' => $atasan_pengaju]);die;
        // 
        $arr_peserta = explode(';', $trx->peserta_property);
        $arrPeserta = array();
        foreach ($arr_peserta as $value) {
            array_push($arrPeserta, json_decode($value, true));
        }
        // 
        $arr_atasan_peserta = explode(';', $trx->atasan_peserta_property);
        $arrAtasanPeserta = array();
        foreach ($arr_atasan_peserta as $value) {
            array_push($arrAtasanPeserta, json_decode($value, true));
        }
        // 
        $arr_jenis = explode(';', $trx->jenis);
        $arrJenis = array();
        foreach ($arr_jenis as $value) {
            array_push($arrJenis, PelatihanJenis::select('jenis')->find( $value ));
        }
        // 
        $arr_benefit = explode(';', $trx->benefit);
        $arrBenefit = array();
        foreach ($arr_benefit as $value) {
            array_push($arrBenefit, PelatihanBenefit::select('benefit')->find( $value ));
        }
        // 
        $int_ext = collect([
            'ext' => 'Eksternal',
            'int' => 'Internal',
        ]);
        // 
        $reviewer1 = '';
        $reviewer2 = '';
        if(!empty($trx->reviewer1)){
            $reviewer1 = json_decode($trx->reviewer1_property, true);
        }
        if(!empty($trx->reviewer2)){
            $reviewer2 = json_decode($trx->reviewer2_property, true);
        }
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
        $where = ['id_trx' => $id, 'nik_pic' => $nik];
        // 2 = Approve by L1 Requestor
        // 3 = Reject by L1 Requestor
        $nAtasanPengaju = TrxPelatihanLog::where($where)->whereIn('id_status', [2, 3])->count();
        // 5 = Approve by L1 Participants
        // 6 = Reject by L1 Participants
        $nAtasanPeserta = TrxPelatihanLog::where($where)->whereIn('id_status', [5, 6])->count();
        // Reviewer1 & Reviewer2 adalah paralel
        // 10 = Approve by Reviewer 2
        // 8 = Reject by Reviewer 2
        $nReviewer1 = TrxPelatihanLog::where($where)->whereIn('id_status', [10, 8])->count();
        // 14 = Approve by Reviewer 3
        // 12 = Reject by Reviewer 3
        $nReviewer2 = TrxPelatihanLog::where($where)->whereIn('id_status', [14, 12])->count();
        // 
        switch ($view) {
            case 'approval':
                // atasan_pengaju - 1 = Submit by Requestor
                if ($pic == 'atasan_pengaju' && $nik == $trx->atasan_pengaju && $nAtasanPengaju == 0 && $id_status == 1) {
                    // 2 = Approve by L1 Requestor
                    // 3 = Reject by L1 Requestor
                    $approve = 2;
                    $reject = 3;
                    return view('emails.pelatihan', compact('trx', 'nik', 'pic', 'approve', 'reject', 'pengaju', 'arrPeserta', 'arrJenis', 'arrBenefit', 'linkAction', 'history', 'int_ext'));
                } 
                // atasan_peserta - 2 = Approve by L1 Requestor
                else if ($pic == 'atasan_peserta' && in_array($nik, explode(';', $trx->atasan_peserta)) && $nAtasanPeserta == 0 && $id_status == 2) {
                    // 5 = Approve by L1 Participants
                    // 6 = Reject by L1 Participants
                    $approve = 5;
                    $reject = 6;
                    return view('emails.pelatihan', compact('trx', 'nik', 'pic', 'approve', 'reject', 'pengaju', 'arrPeserta', 'arrJenis', 'arrBenefit', 'linkAction', 'history', 'int_ext'));
                } 
                // reviewer1 - 5 = Approve by L1 Participants
                else if ($pic == 'reviewer1' && $nik == $trx->reviewer1 && $nReviewer1 == 0 && $id_status == 5) {
                    // 10 = Approve by Reviewer 2
                    // 8 = Reject by Reviewer 2
                    $approve = 10;
                    $reject = 8;
                    return view('emails.pelatihan', compact('trx', 'nik', 'pic', 'approve', 'reject', 'pengaju', 'arrPeserta', 'arrJenis', 'arrBenefit', 'linkAction', 'history', 'int_ext'));
                } 
                // reviewer2 - 5 = Approve by L1 Participants
                else if ($pic == 'reviewer2' && $nik == $trx->reviewer2 && $nReviewer2 == 0 && $id_status == 5) {
                    // 14 = Approve by Reviewer 3
                    // 12 = Reject by Reviewer 3
                    $approve = 14;
                    $reject = 12;
                    return view('emails.pelatihan', compact('trx', 'nik', 'pic', 'approve', 'reject', 'pengaju', 'arrPeserta', 'arrJenis', 'arrBenefit', 'linkAction', 'history', 'int_ext'));
                } 
                // selainnya
                else {
                    return view('emails.pelatihan_ok');
                }
                break;
            // case 'hco':
            //     $approve = 14;
            //     $reject = 12;
            //     return view('emails.pelatihan', compact('trx', 'nik', 'pic', 'approve', 'reject', 'pengaju', 'arrPeserta', 'arrJenis', 'arrBenefit', 'linkAction', 'history'));
            //     break;
            case 'detail':
                // $this->authorize('auth');
                if (!auth()->check()) {
                    session(['url.intended' => url()->full()]);
                    return redirect()->route('login-one');
                }
                // $karyawan = Karyawan::select('nik', 'nama')->orderBy('nama', 'asc')->get();
                $where = ['id_trx' => $id];
                $history = TrxPelatihanLog::where($where)->orderBy('created_at', 'desc')->get();
                // $jenis = PelatihanJenis::orderBy('jenis', 'asc')->get();
                // $anggaran = PelatihanAnggaran::orderBy('anggaran', 'asc')->get();
                // $benefit = PelatihanBenefit::orderBy('benefit', 'asc')->get();
                $status_pengajuan = PelatihanStatus::whereIn('id', [11,$trx->status_pengajuan])->orderBy('id', 'asc')->get();
                // return view('formulir.pelatihan.view', compact('trx', 'pengaju', 'arrPeserta', 'arrAtasanPeserta', 'history', 'arrJenis', 'arrBenefit', 'jenis', 'anggaran', 'benefit', 'status_pengajuan', 'karyawan'));
                return view('formulir.pelatihan.view', compact('trx', 'pengaju', 'arrPeserta', 'arrAtasanPeserta', 'history', 'arrJenis', 'arrBenefit', 'status_pengajuan', 'int_ext'));
                break;
            case 'edit':
                // $this->authorize('auth');
                $status = $view;
                if (!auth()->check()) {
                    session(['url.intended' => url()->full()]);
                    return redirect()->route('login-one');
                }
                $karyawan = Karyawan::select('nik', 'nama')->orderBy('nama', 'asc')->get();
                $jenis = PelatihanJenis::orderBy('jenis', 'asc')->get();
                $anggaran = PelatihanAnggaran::orderBy('anggaran', 'asc')->get();
                $benefit = PelatihanBenefit::orderBy('benefit', 'asc')->get();
                return view('formulir.pelatihan.insert', compact('trx', 'jenis', 'anggaran', 'benefit', 'karyawan', 'status', 'back_url', 'int_ext'));
                break;
            case 'peserta':
                if (!auth()->check()) {
                    session(['url.intended' => url()->full()]);
                    return redirect()->route('login-one');
                }
                $trx = TrxPelatihanPeserta::where(['id_trx'=>$id])->orderBy('id', 'asc')->get();
                $no = 0;
                // backdoor id_rtx = 10101
                // $arr_nik = ['01031112191','01031609480','01031710668','01029903006','01021504105','01021206084','01020701025','01031611512','01031709654','01021405096','01032509884','01032603891'];
                // foreach($arr_nik as $_peserta){
                //     $_data = new TrxPelatihanPeserta;
                //     $_data->id_trx = $id;
                //     $_data->nik = $_peserta;
                //     $_data->nik_property = $this->getNikProperty($_peserta);
                //     $_data->save();
                // }
                return view('formulir.pelatihan.peserta_index', compact('trx', 'no', 'arrJenis', 'back_url', 'int_ext'));
                break;
            case 'sertifikat':
                if (!auth()->check()) {
                    session(['url.intended' => url()->full()]);
                    return redirect()->route('login-one');
                }
                $id_peserta = (new OpenDataController)->decryptWithKey($request->id_peserta);
                $trx = TrxPelatihanPeserta::find($id_peserta);
                $_trx = TrxPelatihanDokumen::where(['id_peserta'=>$id_peserta])->orderBy('id', 'asc')->get();
                $no = 0;
                return view('formulir.pelatihan.doc_index', compact('trx', '_trx', 'no', 'arrJenis', 'back_url', 'int_ext'));
                break;
//             case 'list_certificate':
//                 $arr_nik_nkp = new Karyawan();
//                 $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
//                 if (!auth()->check()) {
//                     session(['url.intended' => url()->full()]);
//                     return redirect()->route('login-one');
//                 }
//                 $no = 0;
//                 $trx = TrxPelatihanSertifikat::orderBy('nama', 'asc')->get();
//                 if(Gate::allows('hc_nkp')){
//                     $trx = TrxPelatihanSertifikat::whereIn('nik',$arr_nik_nkp)->orderBy('nama', 'asc')->get();
//                 }
//                 // start cleansing data
//                 // $return_data = [];
//                 // $bulan_en = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
//                 // $bulan_id = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
//                 // $bulan_en3 = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
//                 // // tanggal_mulai => 954
//                 // $trx = TrxPelatihanSertifikat::where('tanggal_mulai', '!=', '')->orderBy('id', 'asc')->get();
//                 // foreach($trx as $data){
//                 //     $tanggal_mulai = $data->tanggal_mulai;
//                 //     $parts = explode('-', $tanggal_mulai);
//                 //     if (count($parts) == 3) {
//                 //         list($d, $m, $y) = explode('-', $tanggal_mulai);
//                 //         $isValid = false;
//                 //         try {
//                 //             $isValid = true;
//                 //             if(in_array($m, $bulan_en)){
//                 //                 $index = array_search($m, $bulan_en);
//                 //             }
//                 //             else if(in_array($m, $bulan_id)){
//                 //                 $index = array_search($m, $bulan_id);
//                 //             }
//                 //             else if(in_array($m, $bulan_en3)){
//                 //                 $index = array_search($m, $bulan_en3);
//                 //             }
//                 //             $eng = $index + 1;
//                 //             if(strlen($y) == 2){
//                 //                 $y = "20$y";
//                 //             }
//                 //             $parsed = Carbon::createFromFormat('d-m-Y', "$d-$eng-$y");
//                 //             if($isValid){
//                 //                 $hasil = $parsed->format('Y-m-d');
//                 //                 $_trx = TrxPelatihanSertifikat::findOrFail($data->id);
//                 //                 $_trx->tanggal_mulai = $hasil;
//                 //                 $_trx->update();
//                 //                 $return_data[] = ['id'=>$data->id, 'tanggal_mulai_before'=>$tanggal_mulai, 'tanggal_mulai_after'=>$hasil];
//                 //             }
//                 //         } catch (\Exception $e) {
//                 //             $isValid = false;
//                 //         }
//                 //     }
// // manual => 4
// // UPDATE `trx_tbl_pelatihan_sertifikat` SET `tanggal_mulai`='2022-08-02,2022-08-04' WHERE `id`=593;
// // UPDATE `trx_tbl_pelatihan_sertifikat` SET `tanggal_mulai`='2022-08-06,2022-08-13' WHERE `id`=594;
// // UPDATE `trx_tbl_pelatihan_sertifikat` SET `tanggal_mulai`='2025-11-04,2025-11-05,2025-11-06,2025-11-07' WHERE `id`=1205;
// // UPDATE `trx_tbl_pelatihan_sertifikat` SET `tanggal_mulai`='2022-08-30,2022-08-31,2022-09-01,2022-09-02,2022-09-06,2022-09-07,2022-09-08,2022-09-09' WHERE `id`=602;
//                 // }
//                 // // tanggal_akhir => 1050
//                 // $trx = TrxPelatihanSertifikat::where('tanggal_akhir', '!=', '')->orderBy('id', 'asc')->get();
//                 // foreach($trx as $data){
//                 //     $tanggal_akhir = $data->tanggal_akhir;
//                 //     $parts = explode('-', $tanggal_akhir);
//                 //     if (count($parts) == 3) {
//                 //         list($d, $m, $y) = explode('-', $tanggal_akhir);
//                 //         $isValid = false;
//                 //         try {
//                 //             $isValid = true;
//                 //             if(in_array($m, $bulan_en)){
//                 //                 $index = array_search($m, $bulan_en);
//                 //             }
//                 //             else if(in_array($m, $bulan_id)){
//                 //                 $index = array_search($m, $bulan_id);
//                 //             }
//                 //             else if(in_array($m, $bulan_en3)){
//                 //                 $index = array_search($m, $bulan_en3);
//                 //             }
//                 //             $eng = $index + 1;
//                 //             if(strlen($y) == 2){
//                 //                 $y = "20$y";
//                 //             }
//                 //             $parsed = Carbon::createFromFormat('d-m-Y', "$d-$eng-$y");
//                 //             if($isValid){
//                 //                 $hasil = $parsed->format('Y-m-d');
//                 //                 $_trx = TrxPelatihanSertifikat::findOrFail($data->id);
//                 //                 $_trx->tanggal_akhir = $hasil;
//                 //                 $_trx->update();
//                 //                 $return_data[] = ['id'=>$data->id, 'tanggal_akhir_before'=>$tanggal_akhir, 'tanggal_akhir_after'=>$hasil];
//                 //             }
//                 //         } catch (\Exception $e) {
//                 //             $isValid = false;
//                 //         }
//                 //     }
// // manual => 4
// // UPDATE `trx_tbl_pelatihan_sertifikat` SET `tanggal_akhir`='2022-08-09,2022-08-11' WHERE `id`=593;
// // UPDATE `trx_tbl_pelatihan_sertifikat` SET `tanggal_akhir`='2022-08-06,2022-08-13' WHERE `id`=594;
// // UPDATE `trx_tbl_pelatihan_sertifikat` SET `tanggal_akhir`='2022-09-13,2022-09-14,2022-09-15' WHERE `id`=602;
// // UPDATE `trx_tbl_pelatihan_sertifikat` SET `tanggal_akhir`='2025-11-10,2025-11-11,2025-11-12' WHERE `id`=1205;
//                 // }
//                 // // tanggal_sertifikat => 780
//                 // $trx = TrxPelatihanSertifikat::where('tanggal_sertifikat', '!=', '')->orderBy('id', 'asc')->get();
//                 // foreach($trx as $data){
//                 //     $tanggal_sertifikat = $data->tanggal_sertifikat;
//                 //     $parts = explode('-', $tanggal_sertifikat);
//                 //     if (count($parts) == 3) {
//                 //         list($d, $m, $y) = explode('-', $tanggal_sertifikat);
//                 //         $isValid = false;
//                 //         try {
//                 //             $isValid = true;
//                 //             if(in_array($m, $bulan_en)){
//                 //                 $index = array_search($m, $bulan_en);
//                 //             }
//                 //             else if(in_array($m, $bulan_id)){
//                 //                 $index = array_search($m, $bulan_id);
//                 //             }
//                 //             else if(in_array($m, $bulan_en3)){
//                 //                 $index = array_search($m, $bulan_en3);
//                 //             }
//                 //             $eng = $index + 1;
//                 //             if(strlen($y) == 2){
//                 //                 $y = "20$y";
//                 //             }
//                 //             $parsed = Carbon::createFromFormat('d-m-Y', "$d-$eng-$y");
//                 //             if($isValid){
//                 //                 $hasil = $parsed->format('Y-m-d');
//                 //                 $_trx = TrxPelatihanSertifikat::findOrFail($data->id);
//                 //                 $_trx->tanggal_sertifikat = $hasil;
//                 //                 $_trx->update();
//                 //                 $return_data[] = ['id'=>$data->id, 'tanggal_sertifikat_before'=>$tanggal_sertifikat, 'tanggal_sertifikat_after'=>$hasil];
//                 //             }
//                 //         } catch (\Exception $e) {
//                 //             $isValid = false;
//                 //         }
//                 //     }
// // manual => 1
// // UPDATE `trx_tbl_pelatihan_sertifikat` SET `tanggal_sertifikat`='2018-07-01' WHERE `id`=188;
//                 // }
//                 // // tanggal_kadaluarsa => 196 '-' + 14 ' '
//                 // $trx = TrxPelatihanSertifikat::where('tanggal_kadaluarsa', '!=', '')->orderBy('id', 'asc')->get();
//                 // foreach($trx as $data){
//                 //     $tanggal_kadaluarsa = $data->tanggal_kadaluarsa;
//                 //     $parts = explode('-', $tanggal_kadaluarsa);
//                 //     if (count($parts) == 3) {
//                 //         list($d, $m, $y) = explode('-', $tanggal_kadaluarsa);
//                 //         $isValid = false;
//                 //         try {
//                 //             $isValid = true;
//                 //             if(in_array($m, $bulan_en)){
//                 //                 $index = array_search($m, $bulan_en);
//                 //             }
//                 //             else if(in_array($m, $bulan_id)){
//                 //                 $index = array_search($m, $bulan_id);
//                 //             }
//                 //             else if(in_array($m, $bulan_en3)){
//                 //                 $index = array_search($m, $bulan_en3);
//                 //             }
//                 //             $eng = $index + 1;
//                 //             if(strlen($y) == 2){
//                 //                 $y = "20$y";
//                 //             }
//                 //             $parsed = Carbon::createFromFormat('d-m-Y', "$d-$eng-$y");
//                 //             if($isValid){
//                 //                 $hasil = $parsed->format('Y-m-d');
//                 //                 $_trx = TrxPelatihanSertifikat::findOrFail($data->id);
//                 //                 $_trx->tanggal_kadaluarsa = $hasil;
//                 //                 $_trx->update();
//                 //                 $return_data[] = ['id'=>$data->id, 'tanggal_kadaluarsa_before'=>$tanggal_kadaluarsa, 'tanggal_kadaluarsa_after'=>$hasil];
//                 //             }
//                 //         } catch (\Exception $e) {
//                 //             $isValid = false;
//                 //         }
//                 //     }
// // manual => 29
// // UPDATE `trx_tbl_pelatihan_sertifikat` SET `tanggal_kadaluarsa`='2027-07-31' WHERE `tanggal_kadaluarsa`='Juli 2027';
//                 // }
//                 // $json = json_encode($return_data, JSON_PRETTY_PRINT);
//                 // $timestamp = now()->format('YmdHis');
//                 // $filePath = storage_path("app/{$timestamp}.json");
//                 // file_put_contents($filePath, $json);
//                 // return $json;
//                 // end cleansing data
//                 $jenis = PelatihanJenis::orderBy('jenis', 'asc')->get();
//                 $aktif_non = collect([
//                     '1' => 'Aktif',
//                     '0' => 'Non Aktif',
//                 ]);
//                 $int_ext = collect([
//                     'Eksternal' => 'Eksternal',
//                     'Internal' => 'Internal',
//                 ]);
//                 $kta = collect([
//                     'Ada' => 'Ada',
//                     'Tidak Ada' => 'Tidak Ada',
//                 ]);
//                 $arr_jenis = [];
//                 $arr_aktif_non = [];
//                 $arr_int_ext = [];
//                 $arr_kta = [];
//                 // start filter
//                 if($request->filter == true){
//                     $_aktif_non = $request->input('aktif_non', []);
//                     $_jenis = $request->input('jenis', []);
//                     $_int_ext = $request->input('int_ext', []);
//                     $_kta = $request->input('kta', []);
//                     // dd($_kta);
//                     if(count($_aktif_non) > 0){
//                         $arr_aktif_non = $_aktif_non;
//                     }
//                     // else{
//                     //     $arr_aktif_non = array();
//                     // }
//                     if(count($_jenis) > 0){
//                         $arr_jenis = $_jenis;
//                     }
//                     // else{
//                     //     $arr_jenis = array();
//                     // }
//                     if(count($_int_ext) > 0){
//                         $arr_int_ext = $_int_ext;
//                     }
//                     // else{
//                     //     $arr_int_ext = array();
//                     // }
//                     if(count($_kta) > 0){
//                         $arr_kta = $_kta;
//                     }
//                     // else{
//                     //     $arr_kta = array();
//                     // }
//                 }else{
//                     $arr_aktif_non = array();
//                     $arr_int_ext = array();
//                     $arr_jenis = array();
//                     $arr_kta = array();
//                 }
//                 // end filter
//                 $back_url = route('pelatihan.show', [(new OpenDataController)->encryptWithKey(4), 'view' => (new OpenDataController)->encryptWithKey('list_certificate')]);
//                 return view('formulir.pelatihan.index_sertifikat', compact('trx', 'no', 'back_url', 'jenis', 'aktif_non', 'int_ext', 'kta', 'arr_jenis', 'arr_aktif_non', 'arr_int_ext', 'arr_kta'));
//                 break;
            case 'evaluasi':
                $id_peserta = (new OpenDataController)->decryptWithKey($request->id_peserta);
                $_trx = TrxPelatihanPeserta::find($id_peserta);
                // $_trx = TrxPelatihanDokumen::where(['id_peserta'=>$id_peserta])->orderBy('id', 'asc')->get();
                $materi = TrxPelatihanPenilaian::where(['id_peserta'=>$id_peserta])->orderBy('urutan', 'asc')->get();
                // check apakah sudah melakukan evaluasi jika sudah maka diarahkan ke terima kasih
                $check = TrxPelatihanPenilaian::where(['id_peserta'=>$id_peserta])->whereNotIn('tipe', ['readonly'])->whereNotNull('jawaban')->exists();
                if($check){
                    return view('emails.pelatihan_ok');
                }
                return view('formulir.pelatihan.evaluasi', compact('trx', '_trx', 'arrJenis', 'int_ext', 'materi'));
                break;
            case 'evaluasi_pdf':
                if (!auth()->check()) {
                    session(['url.intended' => url()->full()]);
                    return redirect()->route('login-one');
                }
                // 
                $_int_ext = '';
                foreach($int_ext as $key => $value){
                    if($key == $trx->int_ext){
                        $_int_ext = $value;
                    }
                }
                $id_peserta = (new OpenDataController)->decryptWithKey($request->id_peserta);
                $_trx = TrxPelatihanPeserta::find($id_peserta);
                // $_trx = TrxPelatihanDokumen::where(['id_peserta'=>$id_peserta])->orderBy('id', 'asc')->get();
                $materi = TrxPelatihanPenilaian::where(['id_peserta'=>$id_peserta])->orderBy('urutan', 'asc')->get();
                // print to pdf
                $waktu = date('YmdHis');
                $filename = $waktu . '_formulir_evaluasi_pelatihan.pdf';
                $pdf = new TCPDF;
                $pdf::SetTitle('Formulir Evaluasi Pelatihan');
                $pdf::SetFont('times', '', 10, '', true);
                $pdf::AddPage();
                $html = \View::make('formulir.pelatihan.evaluasi_pdf', compact('trx', '_trx', 'arrJenis', '_int_ext', 'materi'))->render();
                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::AddPage();
                $html = \View::make('formulir.pelatihan.evaluasi_pdf_next', compact('trx', '_trx', 'arrJenis', '_int_ext', 'materi'))->render();
                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output(public_path($filename), 'I');
                return response()->download(public_path($filename));
                break;
            case 'sertifikat_detail':
                $status = 'create';
                if (!auth()->check()) {
                    session(['url.intended' => url()->full()]);
                    return redirect()->route('login-one');
                }
                $id_peserta = $request->id_peserta;
                $id_peserta = (new OpenDataController)->decryptWithKey($id_peserta);
                $trx = TrxPelatihanPeserta::find($id_peserta);
                return view('formulir.pelatihan.doc_update', compact('trx', 'back_url', 'arrJenis', 'status', 'int_ext'));
                break;
            case 'sertifikat_update':
                $status = 'edit';
                if (!auth()->check()) {
                    session(['url.intended' => url()->full()]);
                    return redirect()->route('login-one');
                }
                $id_peserta = $request->id_peserta;
                $id_peserta = (new OpenDataController)->decryptWithKey($id_peserta);
                $trx = TrxPelatihanPeserta::find($id_peserta);
                $id_sertifikat = $request->id_sertifikat;
                $id_sertifikat = (new OpenDataController)->decryptWithKey($id_sertifikat);
                $_trx = TrxPelatihanDokumen::find($id_sertifikat);
                return view('formulir.pelatihan.doc_update', compact('trx', '_trx', 'back_url', 'arrJenis', 'status', 'int_ext'));
                break;
            case 'pdf':
                // $this->authorize('auth');
                if (!auth()->check()) {
                    session(['url.intended' => url()->full()]);
                    return redirect()->route('login-one');
                }
                // 
                $_int_ext = '';
                foreach($int_ext as $key => $value){
                    if($key == $trx->int_ext){
                        $_int_ext = $value;
                    }
                }
                // print to pdf
                $waktu = date('YmdHis');
                $no = 0;
                // start
                $arr_htmlLog = array();
                $htmlLog = '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">';
                // pengaju
                $where = ['id_trx' => $id, 'nik_pic' => $trx->pengaju, 'id_status' => 1];
                $pelatihanLog = TrxPelatihanLog::where($where)->orderBy('id', 'desc')->first();
                $_htmlLog = '<td>Mengajukan<br/><img src="' . public_path('assets/images/matta/requested_new.png') . '" height="30" /><br/>' . $pengaju['nama'] . '<br/>' . $pengaju['jabatan'] . '<br/>' . $pelatihanLog->created_at . '</td>';
                array_push($arr_htmlLog, $_htmlLog);
                // atasan_pengaju
                $where = ['id_trx' => $id, 'nik_pic' => $trx->atasan_pengaju];
                $pelatihanLog = TrxPelatihanLog::where($where)->whereIn('id_status', [2, 3])->orderBy('id', 'desc')->first();
                // 2 = Menyetujui
                // 3 = Menolak
                if (!empty($pelatihanLog)) {
                    if($pelatihanLog->id_status == 2){
                        $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $atasan_pengaju['nama'] . '<br/>' . $atasan_pengaju['jabatan'] . '<br/>' . $pelatihanLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }else{
                        $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $atasan_pengaju['nama'] . '<br/>' . $atasan_pengaju['jabatan'] . '<br/>' . $pelatihanLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }
                }
                // atasan_peserta
                $atasan_peserta = array();
                foreach (explode(';', $trx->atasan_peserta_property) as $value){
                    if(!in_array($value, $atasan_peserta)){
                        array_push($atasan_peserta, $value);
                    }
                }
                foreach ($atasan_peserta as $value){
                    $_atasan_peserta = json_decode($value, true);
                    $where = ['id_trx' => $id, 'nik_pic' => $_atasan_peserta['nik']];
                    $pelatihanLog = TrxPelatihanLog::where($where)->whereIn('id_status', [5,6])->orderBy('id', 'desc')->first();
                    // 5 = Menyetujui
                    // 6 = Menolak
                    if (!empty($pelatihanLog)) {
                        if($pelatihanLog->id_status == 5){
                            $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $_atasan_peserta['nama'] . '<br/>' . $_atasan_peserta['jabatan'] . '<br/>' . $pelatihanLog->created_at . '</td>';
                            array_push($arr_htmlLog, $_htmlLog);
                        }else{
                            $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $_atasan_peserta['nama'] . '<br/>' . $_atasan_peserta['jabatan'] . '<br/>' . $pelatihanLog->created_at . '</td>';
                            array_push($arr_htmlLog, $_htmlLog);
                        }
                    }
                }
                // reviewer1
                $where = ['id_trx' => $id, 'nik_pic' => $trx->reviewer1];
                $pelatihanLog = TrxPelatihanLog::where($where)->whereIn('id_status', [10, 8])->orderBy('id', 'desc')->first();
                // 10 = Menyetujui
                // 8 = Menolak
                if (!empty($pelatihanLog)) {
                    if($pelatihanLog->id_status == 10){
                        $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $reviewer1['nama'] . '<br/>' . $reviewer1['jabatan'] . '<br/>' . $pelatihanLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }else{
                        $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $reviewer1['nama'] . '<br/>' . $reviewer1['jabatan'] . '<br/>' . $pelatihanLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }
                }
                // reviewer2
                $where = ['id_trx' => $id, 'nik_pic' => $trx->reviewer2];
                $pelatihanLog = TrxPelatihanLog::where($where)->whereIn('id_status', [14, 12])->orderBy('id', 'desc')->first();
                // 14 = Menyetujui
                // 12 = Menolak
                if (!empty($pelatihanLog)) {
                    if($pelatihanLog->id_status == 14){
                        $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $reviewer2['nama'] . '<br/>' . $reviewer2['jabatan'] . '<br/>' . $pelatihanLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }else{
                        $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $reviewer2['nama'] . '<br/>' . $reviewer2['jabatan'] . '<br/>' . $pelatihanLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }
                }
                // hco
                $where = ['id_trx' => $id];
                $pelatihanLog = TrxPelatihanLog::where($where)->whereIn('id_status', [11])->orderBy('id', 'desc')->first();
                if (!empty($pelatihanLog)) {
                    $_htmlLog = '<td>Mengetahui<br/><img src="' . public_path('assets/images/matta/informed_new.png') . '" height="30" /><br/>' . $pelatihanLog->_pic->nama . '<br/>' . $pelatihanLog->_pic->_jabatan->jabatan . '<br/>' . $pelatihanLog->created_at . '</td>';
                    array_push($arr_htmlLog, $_htmlLog);
                }
                // finish
                // start looping satu baris terdiri dari $pembagi
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
                $filename = $waktu . '_formulir_pelatihan_karyawan.pdf';
                $pdf = new TCPDF;
                $pdf::SetTitle('Formulir Pengajuan Pelatihan');
                $pdf::SetFont('times', '', 10, '', true);
                $pdf::AddPage();
                $html = \View::make('formulir.pelatihan.pdf', compact('trx', 'pengaju', 'arrPeserta', 'arrJenis', 'arrBenefit', 'htmlLog', '_int_ext'))->render();
                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output(public_path($filename), 'I');
                return response()->download(public_path($filename));
                break;
            default:
                return view('emails.pelatihan_ok');
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
        $where = ['id_trx' => $id];
        $history = TrxPelatihanLog::where($where)->orderBy('created_at', 'desc')->get();
        $trx = TrxPelatihan::find($id);
        // 
        $status_pengajuan = PelatihanStatus::whereIn('id', [11,$trx->status_pengajuan])->orderBy('id', 'asc')->get();
        // return response()->json(['status_pengajuan' => $status_pengajuan]);die;
        // 
        // ambil property para pihak
        $pengaju = json_decode($trx->pengaju_property, true);
        $atasan_pengaju = json_decode($trx->atasan_pengaju_property, true);
        // 
        $arr_peserta = explode(';', $trx->peserta_property);
        $arrPeserta = array();
        foreach ($arr_peserta as $value) {
            array_push($arrPeserta, json_decode($value, true));
        }
        // 
        $arr_atasan_peserta = explode(';', $trx->atasan_peserta_property);
        $arrAtasanPeserta = array();
        foreach ($arr_atasan_peserta as $value) {
            array_push($arrAtasanPeserta, json_decode($value, true));
        }
        // 
        $reviewer1 = '';
        $reviewer2 = '';
        if(!empty($trx->reviewer1)){
            $reviewer1 = json_decode($trx->reviewer1_property, true);
        }
        if(!empty($trx->reviewer2)){
            $reviewer2 = json_decode($trx->reviewer2_property, true);
        }
        return view( 'formulir.pelatihan.update', compact( 'trx', 'pengaju', 'atasan_pengaju', 'arrPeserta', 'arrAtasanPeserta', 'reviewer1', 'reviewer2', 'status_pengajuan', 'history' ) );
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
        if($request->type == 'doc_insert'){
            $id = (new OpenDataController)->decryptWithKey($id);
            $trx = TrxPelatihanPeserta::find($id);
            $data = new TrxPelatihanDokumen;
            $data->id_trx = $trx->id_trx;
            $data->id_peserta = $id;
            $data->nik = $trx->nik;
            $data->nik_property = $trx->nik_property;
            $tanggal_ujian = $request->input('tanggal_ujian', []);
            if(!empty($tanggal_ujian) && !in_array(null, $tanggal_ujian, true)){
                $data->tanggal_ujian = json_encode($tanggal_ujian, JSON_UNESCAPED_SLASHES);
            }
            $is_lulus = $request->is_lulus;
            $data->is_lulus = $is_lulus;
            $tanggal_berlaku = $request->input('tanggal_berlaku', []);
            if(!empty($tanggal_berlaku) && !in_array(null, $tanggal_berlaku, true)){
                $data->tanggal_berlaku = json_encode($tanggal_berlaku, JSON_UNESCAPED_SLASHES);
            }
            $nama_sertifikat = $request->nama_sertifikat;
            $data->nama_sertifikat = $nama_sertifikat;
            $no_sertifikat = $request->no_sertifikat;
            $data->no_sertifikat = $no_sertifikat;
            $penerbit = $request->penerbit;
            $data->penerbit = $penerbit;
            if($request->has('file_anggota')){
                $file_anggota = $request->file('file_anggota');
                $namafile = 'file_anggota_'.$id . '_' . time() . '.' . $file_anggota->getClientOriginalExtension();
                $file_anggota->move('public/assets/pelatihan/', $namafile);
                $data->file_anggota = $namafile;
            }
            if($request->has('file_lisensi')){
                $file_lisensi = $request->file('file_lisensi');
                $namafile = 'file_lisensi_'.$id . '_' . time() . '.' . $file_lisensi->getClientOriginalExtension();
                $file_lisensi->move('public/assets/pelatihan/', $namafile);
                $data->file_lisensi = $namafile;
            }
            if($request->has('file_modul')){
                $file_modul = $request->file('file_modul');
                $namafile = 'file_modul_'.$id . '_' . time() . '.' . $file_modul->getClientOriginalExtension();
                $file_modul->move('public/assets/pelatihan/', $namafile);
                $data->file_modul = $namafile;
            }
            if($request->has('file_pelatihan')){
                $file_pelatihan = $request->file('file_pelatihan');
                $namafile = 'file_pelatihan_'.$id . '_' . time() . '.' . $file_pelatihan->getClientOriginalExtension();
                $file_pelatihan->move('public/assets/pelatihan/', $namafile);
                $data->file_pelatihan = $namafile;
            }
            if($request->has('file_keahlian')){
                $file_keahlian = $request->file('file_keahlian');
                $namafile = 'file_keahlian_'.$id . '_' . time() . '.' . $file_keahlian->getClientOriginalExtension();
                $file_keahlian->move('public/assets/pelatihan/', $namafile);
                $data->file_keahlian = $namafile;
            }
            $data->save();
            return redirect()->to($request->back_url)->with( 'msg', 'Data is saved successfully.' );
        }
        else if($request->type == 'doc_update'){
            $id = (new OpenDataController)->decryptWithKey($id);
            $data = TrxPelatihanDokumen::find($id);
            $tanggal_ujian = $request->input('tanggal_ujian', []);
            if(!empty($tanggal_ujian) && !in_array(null, $tanggal_ujian, true)){
                $data->tanggal_ujian = json_encode($tanggal_ujian, JSON_UNESCAPED_SLASHES);
            }
            $is_lulus = $request->is_lulus;
            $data->is_lulus = $is_lulus;
            $tanggal_berlaku = $request->input('tanggal_berlaku', []);
            if(!empty($tanggal_berlaku) && !in_array(null, $tanggal_berlaku, true)){
                $data->tanggal_berlaku = json_encode($tanggal_berlaku, JSON_UNESCAPED_SLASHES);
            }
            $nama_sertifikat = $request->nama_sertifikat;
            $data->nama_sertifikat = $nama_sertifikat;
            $no_sertifikat = $request->no_sertifikat;
            $data->no_sertifikat = $no_sertifikat;
            $penerbit = $request->penerbit;
            $data->penerbit = $penerbit;
            if($request->has('file_anggota')){
                $file_anggota = $request->file('file_anggota');
                $namafile = 'file_anggota_'.$id . '_' . time() . '.' . $file_anggota->getClientOriginalExtension();
                $file_anggota->move('public/assets/pelatihan/', $namafile);
                $data->file_anggota = $namafile;
            }
            if($request->has('file_lisensi')){
                $file_lisensi = $request->file('file_lisensi');
                $namafile = 'file_lisensi_'.$id . '_' . time() . '.' . $file_lisensi->getClientOriginalExtension();
                $file_lisensi->move('public/assets/pelatihan/', $namafile);
                $data->file_lisensi = $namafile;
            }
            if($request->has('file_modul')){
                $file_modul = $request->file('file_modul');
                $namafile = 'file_modul_'.$id . '_' . time() . '.' . $file_modul->getClientOriginalExtension();
                $file_modul->move('public/assets/pelatihan/', $namafile);
                $data->file_modul = $namafile;
            }
            if($request->has('file_pelatihan')){
                $file_pelatihan = $request->file('file_pelatihan');
                $namafile = 'file_pelatihan_'.$id . '_' . time() . '.' . $file_pelatihan->getClientOriginalExtension();
                $file_pelatihan->move('public/assets/pelatihan/', $namafile);
                $data->file_pelatihan = $namafile;
            }
            if($request->has('file_keahlian')){
                $file_keahlian = $request->file('file_keahlian');
                $namafile = 'file_keahlian_'.$id . '_' . time() . '.' . $file_keahlian->getClientOriginalExtension();
                $file_keahlian->move('public/assets/pelatihan/', $namafile);
                $data->file_keahlian = $namafile;
            }
            $data->update();
            return redirect()->to($request->back_url)->with( 'msg', 'Data is saved successfully.' );
        }
        else if($request->type == 'evaluasi'){
            $id = (new OpenDataController)->decryptWithKey($id);
            $jawaban = collect($request->all())->filter(fn($value, $key) => str_starts_with($key, 'jawaban'));
            foreach($jawaban as $key => $value){
                $id = explode('_', $key)[1];
                $data = TrxPelatihanPenilaian::find($id);
                $data->jawaban = $value;
                $data->update();
            }
            return view('emails.pelatihan_ok');
            // return redirect()->to($request->back_url)->with( 'msg', 'Data is saved successfully.' );
        }
        // masih di hardcode pic hco -> Khatrin Rismasany
        // $hco = '01031908799';
        // $hco = auth()->user()->nik;
        $id = (new OpenDataController)->decryptWithKey($id);
        // dd($request->all(), $id);
		$hco = explode(',', MtrUtility::find(29)->informasi);
        $trx = TrxPelatihan::find($id);
        $pengaju = json_decode($trx->pengaju_property, true);
        // 
        $pic = $request->pic;
        $nik = $request->nik;
        $status_pengajuan = $request->status_pengajuan;
        // 2 = Approve by L1 Requestor
        // next to atasan_peserta
        if ($status_pengajuan == 2 && $pic == 'atasan_pengaju' && $nik == $trx->atasan_pengaju) {
            $trx->status_pengajuan = $status_pengajuan;
            $trx->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.pelatihan0']);
            $this->insertLog($id, $status_pengajuan, $nik, $request->keterangan);
            $atasan_peserta = array();
            foreach (explode(';', $trx->atasan_peserta_property) as $value){
                if(!in_array($value, $atasan_peserta)){
                    array_push($atasan_peserta, $value);
                }
            }
            // start
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Formulir Pengajuan Pelatihan';
            $mailData['filename'] = 'emails.pelatihan0';
            foreach ($atasan_peserta as $value) {
                $_atasan_peserta = json_decode($value, true);
                $mailData['data'] = ['nama'=>$pengaju['nama'].'/'.$pengaju['nik'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'link'=> route('pelatihan.show', [(new OpenDataController)->encryptWithKey($trx->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($_atasan_peserta['nik']), 'id_status' => (new OpenDataController)->encryptWithKey($status_pengajuan), 'pic' => (new OpenDataController)->encryptWithKey('atasan_peserta')]), 'trx'=>$trx ];
                $mailData['to'] = $_atasan_peserta['email'];
                $todolist = ['id_trx' => $trx->id, 'nik' => $_atasan_peserta['nik'], 'id_status' => $status_pengajuan, 'pic' => 'atasan_peserta'];
                $this->insertToDoList($todolist, $mailData);
                $this->sendEmail($mailData);
            }
            // check para approver, jika sudah semua kirimkan email ke pengaju
            $this->checkApprovalLine($id);
            // finish
            return view('emails.pelatihan_ok');
        }
        // 3 = Reject by L1 Requestor
        // next to hco
        else if ($status_pengajuan == 3 && $pic == 'atasan_pengaju' && $nik == $trx->atasan_pengaju) {
            $trx->status_pengajuan = $status_pengajuan;
            $trx->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.pelatihan0']);
            $this->insertLog($id, $status_pengajuan, $nik, $request->keterangan);
            // start
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Formulir Pengajuan Pelatihan';
            $mailData['filename'] = 'emails.pelatihan1';
            // $mailData['data'] = ['nama'=>$pengaju['nama'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'trx'=>$trx];
            $mailData['data'] = ['nama'=>$pengaju['nama'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'link'=> route('pelatihan.show', [(new OpenDataController)->encryptWithKey($trx->id), 'view' => (new OpenDataController)->encryptWithKey('detail')]), 'trx'=>$trx];
            $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
            $this->sendEmail($mailData);
            // check para approver, jika sudah semua kirimkan email ke pengaju
            $this->checkApprovalLine($id);
            // finish
            return view('emails.pelatihan_ok');
        }
        // 6 = Reject by L1 Participants
        // next to hco
        else if ($status_pengajuan == 6 && $pic == 'atasan_peserta' && in_array($nik, explode(';', $trx->atasan_peserta))) {
            $trx->status_pengajuan = $status_pengajuan;
            $trx->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.pelatihan0']);
            $this->insertLog($id, $status_pengajuan, $nik, $request->keterangan);
            // start
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Formulir Pengajuan Pelatihan';
            $mailData['filename'] = 'emails.pelatihan1';
            // $mailData['data'] = ['nama'=>$pengaju['nama'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'trx'=>$trx];
            $mailData['data'] = ['nama'=>$pengaju['nama'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'link'=> route('pelatihan.show', [(new OpenDataController)->encryptWithKey($trx->id), 'view' => (new OpenDataController)->encryptWithKey('detail')]), 'trx'=>$trx];
            $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
            $this->sendEmail($mailData);
            // check para approver, jika sudah semua kirimkan email ke pengaju
            $this->checkApprovalLine($id);
            // finish
            return view('emails.pelatihan_ok');
        }
        // 8 = Reject by Reviewer 2
        // 10 = Approve by Reviewer 2
        // matta lama masih berlabel Reviewer 2 di matta reborn menjadi reviewer1
        // next to hco
        else if (($status_pengajuan == 8 || $status_pengajuan == 10) && $pic == 'reviewer1' && $nik == $trx->reviewer1) {
            $trx->status_pengajuan = $status_pengajuan;
            $trx->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.pelatihan0']);
            $this->insertLog($id, $status_pengajuan, $nik, $request->keterangan);
            // start
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Formulir Pengajuan Pelatihan';
            $mailData['filename'] = 'emails.pelatihan1';
            // $mailData['data'] = ['nama'=>$pengaju['nama'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'trx'=>$trx];
            $mailData['data'] = ['nama'=>$pengaju['nama'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'link'=> route('pelatihan.show', [(new OpenDataController)->encryptWithKey($trx->id), 'view' => (new OpenDataController)->encryptWithKey('detail')]), 'trx'=>$trx];
            $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
            $this->sendEmail($mailData);
            // check para approver, jika sudah semua kirimkan email ke pengaju
            $this->checkApprovalLine($id);
            // finish
            return view('emails.pelatihan_ok');
        }
        // 12 = Reject by Reviewer 3
        // 14 = Approve by Reviewer 3
        // matta lama masih berlabel Reviewer 3 di matta reborn menjadi reviewer2
        // next to hco
        else if (($status_pengajuan == 12 || $status_pengajuan == 14) && $pic == 'reviewer2' && $nik == $trx->reviewer2) {
            $trx->status_pengajuan = $status_pengajuan;
            $trx->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.pelatihan0']);
            $this->insertLog($id, $status_pengajuan, $nik, $request->keterangan);
            // start
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Formulir Pengajuan Pelatihan';
            $mailData['filename'] = 'emails.pelatihan1';
            // $mailData['data'] = ['nama'=>$pengaju['nama'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'trx'=>$trx];
            $mailData['data'] = ['nama'=>$pengaju['nama'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'link'=> route('pelatihan.show', [(new OpenDataController)->encryptWithKey($trx->id), 'view' => (new OpenDataController)->encryptWithKey('detail')]), 'trx'=>$trx];
            $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
            $this->sendEmail($mailData);
            // check para approver, jika sudah semua kirimkan email ke pengaju
            $this->checkApprovalLine($id);
            // finish
            return view('emails.pelatihan_ok');
        }
        // 5 = Approve by L1 Participants
        else if ($status_pengajuan == 5 && $pic == 'atasan_peserta' && in_array($nik, explode(';', $trx->atasan_peserta))) {
            $trx->status_pengajuan = $status_pengajuan;
            $trx->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.pelatihan0']);
            $this->insertLog($id, $status_pengajuan, $nik, $request->keterangan);
            $where = ['id_trx' => $id];
            $nAtasanPeserta = TrxPelatihanLog::where($where)->whereIn('id_status', [5])->count();
            if($nAtasanPeserta == 1){
                // reviewer1 || reviewer2
                if(!empty($trx->reviewer1) || !empty($trx->reviewer2)){
                    if(!empty($trx->reviewer1)){
                        $reviewer1 = json_decode($trx->reviewer1_property, true);
                        // start
                        // parameter yang dipassing ke sendemail
                        $mailData['title'] = 'Formulir Pengajuan Pelatihan';
                        $mailData['filename'] = 'emails.pelatihan0';
                        $mailData['data'] = ['nama'=>$pengaju['nama'].'/'.$pengaju['nik'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'link'=> route('pelatihan.show', [(new OpenDataController)->encryptWithKey($trx->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($reviewer1['nik']), 'id_status' => (new OpenDataController)->encryptWithKey($status_pengajuan), 'pic' => (new OpenDataController)->encryptWithKey('reviewer1')]), 'trx'=>$trx ];
                        $mailData['to'] = $reviewer1['email'];
                        $todolist = ['id_trx' => $trx->id, 'nik' => $reviewer1['nik'], 'id_status' => $status_pengajuan, 'pic' => 'reviewer1'];
                        $this->insertToDoList($todolist, $mailData);
                        $this->sendEmail($mailData);
                        // check para approver, jika sudah semua kirimkan email ke pengaju
                        $this->checkApprovalLine($id);
                        // finish
                    }
                    if(!empty($trx->reviewer2)){
                        $reviewer2 = json_decode($trx->reviewer2_property, true);
                        // start
                        // parameter yang dipassing ke sendemail
                        $mailData['title'] = 'Formulir Pengajuan Pelatihan';
                        $mailData['filename'] = 'emails.pelatihan0';
                        $mailData['data'] = ['nama'=>$pengaju['nama'].'/'.$pengaju['nik'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'link'=> route('pelatihan.show', [(new OpenDataController)->encryptWithKey($trx->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($reviewer2['nik']), 'id_status' => (new OpenDataController)->encryptWithKey($status_pengajuan), 'pic' => (new OpenDataController)->encryptWithKey('reviewer2')]), 'trx'=>$trx ];
                        $mailData['to'] = $reviewer2['email'];
                        $todolist = ['id_trx' => $trx->id, 'nik' => $reviewer2['nik'], 'id_status' => $status_pengajuan, 'pic' => 'reviewer2'];
                        $this->insertToDoList($todolist, $mailData);
                        $this->sendEmail($mailData);
                        // check para approver, jika sudah semua kirimkan email ke pengaju
                        $this->checkApprovalLine($id);
                        // finish
                    }
                }else{
                    // start
                    // parameter yang dipassing ke sendemail
                    $mailData['title'] = 'Formulir Pengajuan Pelatihan';
                    $mailData['filename'] = 'emails.pelatihan1';
                    // $mailData['data'] = ['nama'=>$pengaju['nama'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'trx'=>$trx];
                    $mailData['data'] = ['nama'=>$pengaju['nama'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'link'=> route('pelatihan.show', [(new OpenDataController)->encryptWithKey($trx->id), 'view' => (new OpenDataController)->encryptWithKey('detail')]), 'trx'=>$trx];
                    $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
                    $this->sendEmail($mailData);
                    // check para approver, jika sudah semua kirimkan email ke pengaju
                    $this->checkApprovalLine($id);
                    // finish
                }
            }else{
                // check para approver, jika sudah semua kirimkan email ke pengaju
                $this->checkApprovalLine($id);
                // finish
            }
        }
        // 11 = Done by HC
        else if ($status_pengajuan == 11) {
            $trx->status_pengajuan = $status_pengajuan;
            // 
            $trx->update();
            // $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$hco[0], 'pic'=>'hco', 'filename'=>'emails.pelatihan0']);
            $this->deleteToDoList(['id_trx'=>$id, 'pic'=>'hco', 'filename'=>'emails.pelatihan0']);
            // $this->insertLog($id, $status_pengajuan, $hco[0], 'Done by HC');
            $this->insertLog($id, $status_pengajuan, auth()->user()->nik, $request->keterangan);
            // jika belum ada maka dimasukkan
            $check = TrxPelatihanPeserta::where(['id_trx'=>$id])->exists();
            if(!$check){
                $peserta = explode(';', $trx->peserta);
                foreach($peserta as $_peserta){
                    $_data = new TrxPelatihanPeserta;
                    // $_data->file_formulir = NULL;
                    $_data->id_trx = $id;
                    $_data->nik = $_peserta;
                    // $_data->nik_property = $this->getNikProperty($_peserta);
                    $items = array_filter(array_map('trim', explode(';', $trx->peserta_property)));
                    $_items = [];
                    foreach ($items as $item) {
                        $_items[] = json_decode($item, true);
                    }
                    $_data->nik_property = json_encode(collect($_items)->firstWhere('nik', $_peserta));
                    $_data->save();
                    // 
                    $materi = MtrPelatihanPenilaian::orderBy('id', 'asc')->get();
                    foreach($materi as $_materi){
                        $data = new TrxPelatihanPenilaian;
                        $data->id_trx = $id;
                        $data->id_peserta = $_data->id;
                        $data->nik = $_peserta;
                        $data->urutan = $_materi->id;
                        $data->label = $_materi->label;
                        $data->nomor = $_materi->nomor;
                        $data->materi1 = $_materi->materi1;
                        $data->materi2 = $_materi->materi2;
                        $data->tipe = $_materi->tipe;
                        $data->save();
                    }
                }
            }
            return redirect('pelatihan')->with('msg', 'Data is saved successfully.');
        }
        // 
        return view('emails.pelatihan_ok');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // dd($request->all(), $id);
        $type = $request->type;
        if(!empty($type) && $type == 'doc_delete'){
            $back_url = $request->back_url;
            $id = (new OpenDataController)->decryptWithKey($id);
            TrxPelatihanDokumen::find($id)->delete();
            return redirect()->to($back_url)->with( 'msg', 'Data is deleted successfully.' );
        }
    }

    public function save_update(Request $request, $id)
    {
        // dd($request->all(), $id);
        $this->authorize('hc');
        $back_url = $request->back_url;
        $trx = TrxPelatihan::find($id);
        $trx->tahun = $request->tahun;
        $trx->jenis = implode(';', $request->jenis);
        $trx->int_ext = $request->int_ext;
        $trx->judul = $request->judul;
        $trx->penyelenggara = $request->penyelenggara;
        // tidak digunakan diganti dengan tanggal_range
        // $trx->tanggal_mulai = $request->tanggal_mulai;
        // $trx->tanggal_akhir = $request->tanggal_akhir;
        $trx->tanggal_range = json_encode($request->input('tanggal_range', []), JSON_UNESCAPED_SLASHES);
        $trx->anggaran = $request->anggaran;
        $trx->biaya = str_replace(',', '', $request->biaya);
        $trx->benefit = implode(';', $request->benefit);
        $trx->tempat = $request->tempat;
        $trx->keterangan = $request->keterangan;
        $trx->catatan = $request->catatan;
        if($request->has('lampiran')){
            $lampiran = $request->file('lampiran');
            $request->validate([
                'lampiran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);
            $extension = $lampiran->getClientOriginalExtension();
            $namafile = $trx->pengaju.'_'.now()->getTimestampMs().'.'.$extension;
            $lampiran->move('public/assets/pelatihan/', $namafile);
            $trx->lampiran = $namafile;
        }
        $trx->update();
        return redirect()->to($back_url)->with( 'msg', 'Data is saved successfully.' );
    }
}
