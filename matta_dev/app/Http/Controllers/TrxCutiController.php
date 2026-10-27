<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
// 
use App\Models\Karyawan;
use App\Models\TrxCuti;
use App\Models\TrxCutiLog;
use App\Models\CutiStatus;
use App\Models\CutiTipe;
use App\Models\Holiday;
use App\Models\WorkingDay;
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

class TrxCutiController extends Controller
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
        set_time_limit(3600);
        // dd($request->all());
        // $tipe_cuti = 0;
        // $waktu = date('Y-m-d');
        // $tanggal_mulai = '2023-03-30';
        // $jumlah_hari = 13;
        // $periode_cuti = 2023;
        // $nik_karyawan = '';
        // // cek semua kemungkinan pengajuan cuti
        // $arr = array();
        // $data1 = $this->isWeekend($tanggal_mulai);
        // $data2 = $this->checkIfExist($tanggal_mulai, $waktu);
        // $data3 = $this->isHoliday($tanggal_mulai);
        // $data4 = $this->isCutiTahunanOver($periode_cuti, $nik_karyawan, $jumlah_hari);
        // // 
        // $arr1 = array();
        // $str = $tanggal_mulai;
        // for ($x = 0; $x < $jumlah_hari; $x++) {
        //     $str = $this->getTanggalCuti($str);
        //     array_push($arr1, $str);
        //     $str = date("Y-m-d", strtotime($str ."+1 day"));
        // }
        // $tanggal_akhir = $arr1[$jumlah_hari-1];
        // $tanggal_cuti = implode(";", $arr1);
        // // echo $tanggal_cuti;die;
        // // 
        // $data5 = $this->isCutiExisting($nik_karyawan, $tanggal_mulai, $tanggal_akhir, $periode_cuti);
        // array_push($arr, $data1, $data2, $data3, $data4, $data5);
        // $cek = $data1['status'] && $data2['status'] && $data3['status'] && $data4['status'] && $data5['status'];
        // if($cek){
        //     return response()->json( [ 'success' => true, 'message' => $arr ], 200 );
        // }else{
        //     return response()->json( [ 'success' => false, 'message' => $arr ], 200 );
        // }
        // die;
        // 
        // parameter yang dipassing ke sendemail
        // $trx = TrxCuti::find( 4 );
        // $karyawan = json_decode($trx->karyawan_property, true);
        // $atasan = json_decode($trx->atasan_property, true);
        // $mailData['title'] = 'Formulir Permohonan Cuti';
        // $mailData['data'] = ['nama'=>$karyawan['nama'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('cuti.index') ];
        // $mailData['filename'] = 'emails.cuti0';
        // // print_r($karyawan);die;
        // foreach (explode(';', $trx->pic_property) as $value) {
        //     $pic_property = json_decode($value, true);
        //     $mailData['to'] = $pic_property['email'];
        //     $this->sendEmail($mailData);
        // }
        // die;
        // halaman index kali pertama dibuka
        $no = 0;
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->whereNotIn('nik', ['Hco.all','IT.BM','IT Infras'])->orderBy('nama', 'asc')->get();
        $trx = TrxCuti::orderBy('id', 'desc')->get();
        if(Gate::allows('hc_nkp')){
            $karyawan = Karyawan::select('nik', 'nama')->whereIn('nik',$arr_nik_nkp)->where($where)->whereNotIn('nik', ['Hco.all','IT.BM','IT Infras'])->orderBy('nama', 'asc')->get();
            $trx = TrxCuti::whereIn('nik_karyawan',$arr_nik_nkp)->orderBy('id', 'desc')->get();
        }
        // foreach ($trx as $value) {
        //     $trxM = TrxCuti::find( $value->id );
        //     $trxM->karyawan_property = $this->getNikProperty($value->nik_karyawan);
        //     $trxM->atasan_property = $this->getNikProperty($value->nik_atasan);
        //     $pic_property = explode(";", $value->pic_pengganti);
        //     $_pic_property = array();
        //     foreach ($pic_property as $_value) {
        //         array_push($_pic_property, $this->getNikProperty($_value));
        //     }
        //     $trxM->pic_property = implode(';', $_pic_property);
        //     $trxM->update();
        // }
        if(auth()->user()->userrole == 'nhc'){
            // $layer1 = auth()->user()->nik;
            // $where = ['layer1' => $layer1];
            // $_nik = Karyawan::select('nik')->where($where)->orderBy('nama', 'asc')->get();
            // $arr_nik = [$layer1];
            // foreach ($_nik as $data){
            //     array_push($arr_nik, $data->nik);
            // }
            // $trx = TrxCuti::whereIn('nik_karyawan', $arr_nik)->orderBy('id', 'desc')->get();
            $trx = TrxCuti::where('nik_karyawan', [auth()->user()->nik])->orWhere('nik_atasan', [auth()->user()->nik])->orderBy('id', 'desc')->get();
            // 
            $arr_id = [];
            foreach($trx as $_trx){
                array_push($arr_id, $_trx->id);
            }
            $where = ['nik_pic' => auth()->user()->nik];
            $trx = TrxCutiLog::where($where)->orderBy('id_trx', 'desc')->get();
            foreach($trx as $_trx){
                array_push($arr_id, $_trx->id_trx);
            }
            // 
            $trx = TrxCuti::whereIn('id', $arr_id)->orderBy('id', 'desc')->get();
        }
        // filter parameter
        function forgetById($collection,$id){
            foreach($collection as $key => $item){
                if($item->id == $id){
                    $collection->forget($key);
                    break;
                }
            }
            return $collection;
        }
        // 
        $arr_status_cuti = array();
        $arr_tipe_cuti = array();
        $arr_nik_karyawan = array();
        $arr_nik_atasan = array();
        // 
        $_nik_karyawan = '';
        $_nik_atasan = '';
        $date_from = '';
        $date_to = '';
        foreach ($trx as $data){
            array_push($arr_status_cuti, $data->status_cuti);
            array_push($arr_tipe_cuti, $data->tipe_cuti);
            array_push($arr_nik_karyawan, $data->nik_karyawan);
            array_push($arr_nik_atasan, $data->nik_atasan);
        }
        $status_cuti = CutiStatus::whereIn('id', $arr_status_cuti)->orderBy('id', 'asc')->get();
        $tipe_cuti = CutiTipe::whereIn('id', $arr_tipe_cuti)->orderBy('order_by', 'asc')->get();
        $nik_karyawan = Karyawan::whereIn('nik', $arr_nik_karyawan)->orderBy('nama', 'asc')->get();
        $nik_atasan = Karyawan::whereIn('nik', $arr_nik_atasan)->orderBy('nama', 'asc')->get();
        // start dibatasi 30 hari terakhir dari created_at
        $trx = $trx->filter(function ($item) {
            return Carbon::parse($item->created_at)->gte(Carbon::now()->subDays(30));
        });
        // end
        // start filter
        if($request->filter == true){
            $_status_cuti = $request->input('status_cuti', []);
            $_tipe_cuti = $request->input('tipe_cuti', []);
            if(!empty($request->nik_karyawan)){
                $_nik_karyawan = $request->nik_karyawan;
            }
            if(!empty($request->nik_atasan)){
                $_nik_atasan = $request->nik_atasan;
            }
            $date_from = $request->date_from;
            $date_to = $request->date_to;
            // 
            $arr_tanggal = array($date_from);
            $start = $date_from;
            $i=1;
            if (strtotime($date_from) < strtotime($date_to))
            {
            while (strtotime($start) < strtotime($date_to))
                {
                    $start = date('Y-m-d', strtotime($date_from.'+'.$i.' days'));
                    $arr_tanggal[] = $start;
                    $i++;
                }
            }
            // 
            if(count($_status_cuti) > 0){
                $arr_status_cuti = $_status_cuti;
            }
            else{
                $arr_status_cuti = array();
            }
            if(count($_tipe_cuti) > 0){
                $arr_tipe_cuti = $_tipe_cuti;
            }
            else{
                $arr_tipe_cuti = array();
            }
            $trx = TrxCuti::where('nik_karyawan','LIKE','%'.$_nik_karyawan.'%')->where('nik_atasan','LIKE','%'.$_nik_atasan.'%')->whereIn('status_cuti', $arr_status_cuti)->whereIn('tipe_cuti', $arr_tipe_cuti)->orderBy('id', 'desc')->get();
            if(Gate::allows('hc_nkp')){
                $trx = TrxCuti::whereIn('nik_karyawan',$arr_nik_nkp)->where('nik_karyawan','LIKE','%'.$_nik_karyawan.'%')->where('nik_atasan','LIKE','%'.$_nik_atasan.'%')->whereIn('status_cuti', $arr_status_cuti)->whereIn('tipe_cuti', $arr_tipe_cuti)->orderBy('id', 'desc')->get();
            }
            if(auth()->user()->userrole == 'nhc'){
                // $layer1 = auth()->user()->nik;
                // $where = ['layer1' => $layer1];
                // $_nik = Karyawan::select('nik')->where($where)->orderBy('nama', 'asc')->get();
                // $arr_nik = [$layer1];
                // foreach ($_nik as $data){
                //     array_push($arr_nik, $data->nik);
                // }
                $trx = TrxCuti::where('nik_karyawan','LIKE','%'.$_nik_karyawan.'%')->where('nik_atasan', [auth()->user()->nik])->whereIn('status_cuti', $arr_status_cuti)->whereIn('tipe_cuti', $arr_tipe_cuti)->orderBy('id', 'desc')->get();
                // dd($trx);
                // 
                $arr_id = [];
                foreach($trx as $_trx){
                    array_push($arr_id, $_trx->id);
                }
                $where = ['nik_pic' => auth()->user()->nik];
                $trx = TrxCutiLog::where($where)->orderBy('id_trx', 'desc')->get();
                foreach($trx as $_trx){
                    array_push($arr_id, $_trx->id_trx);
                }
                // 
                $trx = TrxCuti::whereIn('id', $arr_id)->where('nik_karyawan','LIKE','%'.$_nik_karyawan.'%')->whereIn('status_cuti', $arr_status_cuti)->whereIn('tipe_cuti', $arr_tipe_cuti)->orderBy('id', 'desc')->get();
            }
            foreach ($trx as $data){
                $_arr_tanggal = explode(';', $data->tanggal_cuti);
                if(count(array_intersect($_arr_tanggal, $arr_tanggal)) == 0){
                    $trx = forgetById($trx, $data->id);
                }
            }
        }else{
            $arr_status_cuti = array();
            $arr_tipe_cuti = array();
            $arr_nik_karyawan = array();
            $arr_nik_atasan = array();
        }
        // end filter
        return view( 'formulir.cuti.index', compact( 'trx', 'no', 'status_cuti', 'tipe_cuti', 'arr_status_cuti', 'arr_tipe_cuti', 'date_from', 'date_to', 'nik_karyawan', '_nik_karyawan', 'nik_atasan', '_nik_atasan', 'karyawan'));
        // return view('formulir.cuti.index', compact('trx', 'no'));
    }

    // ambil sisa cuti
    public static function getSisaCuti($nik, $periode, $id)
    {
        $where = ['nik_karyawan' => $nik, 'periode_cuti' => $periode, 'tipe_cuti' => 0];
        $trx = TrxCuti::where($where)->whereNotIn('status_cuti', [5,7,8])->where('id', '<=', $id)->sum('jumlah_hari');
        // cek karyawan jika ada perubahan nik tetapi emailnya sama maka cutinya dihitung juga
        $email = Karyawan::where(['nik' => $nik])->first()->email;
        $arr_nik = array_values(array_diff(Karyawan::where(['email' => $email])->get()->pluck('nik')->toArray(), [$nik]));
        foreach($arr_nik as $_nik){
            $where = ['nik_karyawan' => $_nik, 'periode_cuti' => $periode, 'tipe_cuti' => 0];
            $trx += TrxCuti::where($where)->whereNotIn('status_cuti', [5,7,8])->where('id', '<=', $id)->sum('jumlah_hari');
        }
        // cek apakah karyawan reguler atau tidak, jika iya maka dihitung cuti bersama
        $where = [ 'nik' => $nik, 'wg_id' => 1 ];
        $check = Karyawan::where($where)->exists();
        $cuti_bersama = 0;
        if($check){
            $where = ['is_cuti' => 1];
            $cuti_bersama = Holiday::whereYear('tanggal', $periode)->where($where)->count();
        }
        return 12 - $trx - $cuti_bersama;
    }

    // ambil sisa cuti ketika input formulir
    public static function getSisaFormCuti($nik, $periode)
    {
        // $periode = Carbon::now()->format('Y');
        // $nik = auth()->user()->nik;
        $where = ['nik_karyawan' => $nik, 'periode_cuti' => $periode, 'tipe_cuti' => 0];
        $trx = TrxCuti::where($where)->whereNotIn('status_cuti', [5,7,8])->sum('jumlah_hari');
        // cek karyawan jika ada perubahan nik tetapi emailnya sama maka cutinya dihitung juga
        $email = Karyawan::where(['nik' => $nik])->first()->email;
        $arr_nik = array_values(array_diff(Karyawan::where(['email' => $email])->get()->pluck('nik')->toArray(), [$nik]));
        foreach($arr_nik as $_nik){
            $where = ['nik_karyawan' => $_nik, 'periode_cuti' => $periode, 'tipe_cuti' => 0];
            $trx += TrxCuti::where($where)->whereNotIn('status_cuti', [5,7,8])->sum('jumlah_hari');
        }
        // cek apakah karyawan reguler atau tidak, jika iya maka dihitung cuti bersama
        $where = [ 'nik' => $nik, 'is_aktif' => 1, 'wg_id' => 1 ];
        $check = Karyawan::where($where)->exists();
        $cuti_bersama = 0;
        if($check){
            $where = ['is_cuti' => 1];
            $cuti_bersama = Holiday::whereYear('tanggal', $periode)->where($where)->count();
        }
        return 12 - $trx - $cuti_bersama;
    }

    // fungsi untuk cek apakah masuk window
    public function checkIfExist($from, $input)
    {
        $fromDateTime = Carbon::createFromFormat('Y-m-d', $from);
        $inputDateTime = Carbon::createFromFormat('Y-m-d', $input);
        $cek = $fromDateTime > $inputDateTime;
        return ['status' => $cek, 'message' => 'Tanggal Mulai tidak CURRENTDATE atau BACKDATE'];
    }

    // cek apakah hari ini sabtu minggu
    public function isWeekend($date)
    {
        $weekDay = date('w', strtotime($date));
        $cek = $weekDay == 0 || $weekDay == 6;
        return ['status' => !$cek, 'message' => 'Tanggal Mulai tidak di HARI SABTU atau MINGGU'];
    }

    // cek apakah hari ini adalah hari libur nasional
    public function isHoliday($date)
    {
        $cek = false;
        $where = ['tanggal' => $date];
        $data = Holiday::where($where)->first();
        if (empty($data)) {
            $cek = true;
        }
        return ['status' => $cek, 'message' => 'Tanggal Mulai tidak di HARI LIBUR NASIONAL'];
    }

    // cek apakah jumlah cuti tanggungan perusahaan sudah pernah diambil
    public function isPerusahaanOver($periode_cuti, $nik_karyawan, $tipe_cuti)
    {
        $cek = true;
        $where = ['periode_cuti' => $periode_cuti, 'nik_karyawan' => $nik_karyawan, 'tipe_cuti' => $tipe_cuti];
        $jumlah = TrxCuti::where($where)->whereNotIn('status_cuti', [5,7,8])->count();
        // cek karyawan jika ada perubahan nik tetapi emailnya sama maka cutinya dihitung juga
        $email = Karyawan::where(['nik' => $nik_karyawan])->first()->email;
        $arr_nik = array_values(array_diff(Karyawan::where(['email' => $email])->get()->pluck('nik')->toArray(), [$nik_karyawan]));
        foreach($arr_nik as $_nik){
            $where = ['periode_cuti' => $periode_cuti, 'nik_karyawan' => $_nik, 'tipe_cuti' => $tipe_cuti];
            $jumlah += TrxCuti::where($where)->whereNotIn('status_cuti', [5,7,8])->count();
        }
        if ($jumlah > 0) {
            $cek = false;
        }
        // 2 = Pernikahan-Anak Karyawan (2 hari)
        // 3 = Pernikahan-Sdr Kandung/Ipar (1 hari)
        // 4 = Duka Cita-Istri/Suami/Anak (3 hari)
        // 5 = Duka Cita-Orang Tua Kandung/Mertua (3 hari)
        // 6 = Duka Cita-Sdr Kandung/Ipar (2 hari)
        // 7 = Khitan/Baptis/Upacara Keagamaan (3 hari)	
        // 10 = Menjaga-Anak/Istri/Suami Yang Rawat Inap (3 hari)
        // 11 = Kebakaran/Bencana Alam (2 hari)
        // 12 = Ibadah Umroh (7 hari)
        // 13 = Ibadah Haji Plus (30 hari)
        // 14 = Ibadah Umroh (10 hari)
        // 15 = Ibadah Haji Reguler (40 hari)
        // 16 = Cuti Haid (2 hari)
        // 17 = Cuti Haid (1 hari)
        $arr_tipe_cuti = array(2,3,4,5,6,7,10,11,12,13,14,15,16,17);
        if(in_array($tipe_cuti, $arr_tipe_cuti)){
            $cek = true;
        }
        return ['status' => $cek, 'message' => 'Cuti Tanggungan Perusahaan'];
    }

    // cek apakah jumlah cuti sudah lebih dari 12
    public function isCutiTahunanOver($tanggal_mulai, $nik_karyawan, $jumlah_hari)
    {
        // date: 2025-01-08 00:00:00.0 Asia/Jakarta (+07:00)
        // dd(Carbon::today());
        // date: 2025-01-08 15:00:57.714397 Asia/Jakarta (+07:00)
        // dd(Carbon::now());
        // 2025-01-01
        // dd(Carbon::now()->year.'-01-01');
        // date: 2025-01-31 00:00:00.0 Asia/Jakarta (+07:00)
        // dd(Carbon::parse($tanggal_mulai));
        $cek = false;
        // jika di Bulan Januari maka gunakan sisa cuti tahun lalu jika sudah habis gunakan tahun ini
        // jika menggunakan ini maka tanggal 31 Januari tidak termasuk disebabkan adanya satuan jam menit dan detik
        // contoh: date: 2025-01-31 14:58:27.0 Asia/Jakarta (+07:00)
        // if(Carbon::createFromFormat('Y-m-d', $tanggal_mulai)->between(Carbon::now()->year.'-01-01', Carbon::now()->year.'-01-31')){
        // jika menggunakan ini maka tanggal 31 Januari termasuk karena waktunya adalah 00
        // contoh: date: 2025-01-31 00:00:00.0 Asia/Jakarta (+07:00)
        // if(Carbon::parse($tanggal_mulai)->between(Carbon::now()->year.'-01-01', Carbon::now()->year.'-01-31')){
        if(Carbon::parse($tanggal_mulai)->month == 1){
            // gunakan sisa cuti tahun lalu
            $periode_cuti = Carbon::createFromFormat('Y-m-d', $tanggal_mulai)->subYear()->year;
            $where = ['periode_cuti' => $periode_cuti, 'nik_karyawan' => $nik_karyawan, 'tipe_cuti' => 0];
            $jumlah = TrxCuti::where($where)->whereNotIn('status_cuti', [5,7,8])->sum('jumlah_hari');
            // cek apakah karyawan reguler atau tidak, jika iya maka dihitung cuti bersama
            $where = [ 'nik' => $nik_karyawan, 'is_aktif' => 1, 'wg_id' => 1 ];
            $check = Karyawan::where($where)->exists();
            $cuti_bersama = 0;
            if($check){
                $where = ['is_cuti' => 1];
                $cuti_bersama = Holiday::whereYear('tanggal', $periode_cuti)->where($where)->count();
            }
            // 
            $jumlah += $cuti_bersama;
            if ($jumlah + $jumlah_hari < 13) {
                $cek = true;
            }
            // jika sudah habis gunakan tahun ini
            if($cek == false){
                $periode_cuti = Carbon::createFromFormat('Y-m-d', $tanggal_mulai)->year;
                $where = ['periode_cuti' => $periode_cuti, 'nik_karyawan' => $nik_karyawan, 'tipe_cuti' => 0];
                $jumlah = TrxCuti::where($where)->whereNotIn('status_cuti', [5,7,8])->sum('jumlah_hari');
                $where = ['is_cuti' => 1];
                $cuti_bersama = Holiday::whereYear('tanggal', $periode_cuti)->where($where)->count();
                $jumlah += $cuti_bersama;
                if ($jumlah + $jumlah_hari < 13) {
                    $cek = true;
                }
            }
        }
        // jika selain Bulan Januari maka gunakan tahun ini
        else{
            $periode_cuti = Carbon::createFromFormat('Y-m-d', $tanggal_mulai)->year;
            // $where = ['periode_cuti' => $periode_cuti, 'nik_karyawan' => $nik_karyawan, 'tipe_cuti' => 0];
            // $jumlah = TrxCuti::where($where)->whereNotIn('status_cuti', [5,7,8])->sum('jumlah_hari');
            // $where = ['is_cuti' => 1];
            // $cuti_bersama = Holiday::whereYear('tanggal', $periode_cuti)->where($where)->count();
            // $jumlah += $cuti_bersama;
            // if ($jumlah + $jumlah_hari < 13) {
            //     $cek = true;
            // }
            $sisa_cuti = $this->getSisaFormCuti($nik_karyawan, $periode_cuti);
            if($sisa_cuti >= $jumlah_hari){
                $cek = true;
            }
        // dd($cek);
        }
        return ['status' => $cek, 'message' => 'Cuti Tahunan tidak melebihi BATAS', 'periode_cuti' => $periode_cuti];
    }

    // ambil tanggal tanggal cuti reguler
    public function getTanggalCuti($str)
    {
        if (!$this->isWeekend($str)['status'] || !$this->isHoliday($str)['status']) {
            $str = $this->getTanggalCuti(date("Y-m-d", strtotime($str . "+1 day")));
        } 
        // else {
        //     $str = $this->getTanggalCuti(date("Y-m-d", strtotime($str ."+1 day")));
        // }
        return $str;
    }

    // ambil tanggal tanggal cuti non reguler
    public function getTanggalCutiNonReguler($str, $nik)
    {
        $where = [ 'nik' => $nik, 'working_date' => $str ];
        $check = WorkingDay::where($where)->exists();
        if (!$check) {
            $str = $this->getTanggalCutiNonReguler(date("Y-m-d", strtotime($str . "+1 day")), $nik);
        } 
        // else {
        //     $str = $this->getTanggalCuti(date("Y-m-d", strtotime($str ."+1 day")));
        // }
        return $str;
    }

    // ambil tanggal tanggal cuti non reguler sebelum
    public function getTanggalCutiNonRegulerSebelum($str, $nik)
    {
        $where = [ 'nik' => $nik, 'working_date' => $str ];
        $check = WorkingDay::where($where)->exists();
        if (!$check) {
            $str = $this->getTanggalCutiNonRegulerSebelum(date("Y-m-d", strtotime($str . "-1 day")), $nik);
        } 
        // else {
        //     $str = $this->getTanggalCuti(date("Y-m-d", strtotime($str ."+1 day")));
        // }
        return $str;
    }

    // fungsi ambil cuti yang ada sesuai NIK dan waktu pengajuan
    public function isCutiExisting($nik_karyawan, $from, $to, $periode_cuti)
    {
        $cek = true;
        $where = ['periode_cuti' => $periode_cuti, 'nik_karyawan' => $nik_karyawan];
        $data = TrxCuti::where($where)->where('tanggal_mulai', '<=', $from)->where('tanggal_akhir', '>=', $from)->whereNotIn('status_cuti', [5,7,8])->count();
        $data += TrxCuti::where($where)->where('tanggal_mulai', '<=', $to)->where('tanggal_akhir', '>=', $to)->whereNotIn('status_cuti', [5,7,8])->count();
        if ($data > 0) {
            $cek = false;
        }
        return ['status' => $cek, 'message' => 'Pengajuan Cuti tidak beririsan dengan Cuti lainnya'];
    }

    // fungsi cek boleh cuti yang sudah setahun dari tanggal_mulai untuk kode_status KT=1 dan KKWT=2
    public function isSetahun($nik_karyawan, $waktu, $tipe_cuti)
    {
        $cek = true;
        $message = 'Mulai bekerja lebih dari setahun';
        // jika NIK berawalan F, CS dan P tidak bisa cuti
        if((Str::startsWith($nik_karyawan, 'F') || Str::startsWith($nik_karyawan, 'CS') || Str::startsWith($nik_karyawan, 'P'))){
            $cek = false;
            $message = 'Pengajuan Cuti Tahunan';
        }
        // 
        else{
            $where = ['nik' => $nik_karyawan];
            $tanggal = Karyawan::where($where)->first();
            if (!empty($tanggal)) {
                $tanggal_mulai = $tanggal->tanggal_mulai;
                $fromDate = Carbon::createFromFormat('Y-m-d', date("Y-m-d", strtotime("+1 years", strtotime($tanggal_mulai))));
                $inputDate = Carbon::createFromFormat('Y-m-d', $waktu);
                $cek1 = $fromDate <= $inputDate;
                // 
                // $tanggal_bergabung = $tanggal->tanggal_bergabung;
                // $fromDate = Carbon::createFromFormat('Y-m-d', date("Y-m-d", strtotime("+1 years", strtotime($tanggal_bergabung))));
                // $inputDate = Carbon::createFromFormat('Y-m-d', $waktu);
                // $cek2 = $fromDate <= $inputDate;
                $cek2 = true;
                $cek = $cek1 && $cek2;
            }
        }
        // semua bisa cuti tanggungan perusahaan
        if($tipe_cuti > 0){
            $cek = true;
        }
        return ['status' => $cek, 'message' => $message];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        //passing parameter ke formulir page
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->whereNotIn('nik', [auth()->user()->nik])->orderBy('nama', 'asc')->get();
        // $tipe = CutiTipe::orderBy('id', 'asc')->get();
        // 12 = Ibadah Umroh (7 hari)
        $tipe = CutiTipe::whereNotIn('id', [12])->orderBy('order_by', 'asc')->get();
        $status = 'create';
        // $jumlah_hari = [1,2,3,4,5,6,7,8,9,10,11,12];
        $nik_karyawan = Crypt::decrypt($request->nik_karyawan);
        $where = ['nik' => $nik_karyawan];
        $pengaju = Karyawan::select('nik', 'nama')->where($where)->first();
        $pengaju = $pengaju->nama.' - '.$pengaju->nik;
        return view('formulir.cuti.insert', compact('karyawan', 'tipe', 'status', 'pengaju', 'nik_karyawan'));
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
        $preview = $request->preview;
        // start
        $nik_karyawan = $request->nik_karyawan;
        // $nik_karyawan = auth()->user()->nik;
        $tipe_cuti = $request->tipe_cuti;
        $waktu = date('Y-m-d');
        $tanggal_mulai = $request->tanggal_mulai;
        $jumlah_hari = $request->jumlah_hari;
        if ($tipe_cuti > 0) {
            $jumlah_hari = CutiTipe::find($tipe_cuti)->jumlah_hari;
        }
        // $periode_cuti = date('Y', strtotime($tanggal_mulai));
        $periode_cuti = Carbon::createFromFormat('Y-m-d', $tanggal_mulai)->year;
        $is_reguler = Karyawan::where('nik', $nik_karyawan)->first()->_wg->is_reguler;
        // cek semua kemungkinan pengajuan cuti
        $arr = array();
        $data1 = $this->isWeekend($tanggal_mulai);
        // jika karyawan non reguler maka bisa cuti di hari sabtu minggu
        if ($is_reguler == 0) {
            $data1 = ['status' => true, 'message' => 'Tanggal Mulai boleh di HARI SABTU atau MINGGU'];
        }
        // 12=Ibadah Umroh (7 hari)
        // 13=Ibadah Haji Plus (30 hari)
        // 14=Ibadah Umroh (10 hari)
        // 15=Ibadah Haji Reguler (40 hari)
        if(in_array($tipe_cuti, [12,13,14,15])){
            $data1 = ['status' => true, 'message' => 'Tanggal Mulai boleh di HARI SABTU atau MINGGU'];
        }
        $data2 = $this->checkIfExist($tanggal_mulai, $waktu);
        // cuti tanggungan perusahaan boleh current date / backdate
        if ($tipe_cuti > 0) {
            $data2 = ['status' => true, 'message' => 'Tanggal Mulai boleh CURRENTDATE atau BACKDATE'];
        }
        $data3 = $this->isHoliday($tanggal_mulai);
        // jika karyawan non reguler maka bisa cuti di hari libur
        if ($is_reguler == 0) {
            $data3 = ['status' => true, 'message' => 'Tanggal Mulai boleh di HARI LIBUR NASIONAL'];
        }
        $data4 = $this->isCutiTahunanOver($tanggal_mulai, $nik_karyawan, $jumlah_hari);
        if($data4['status']){
            $periode_cuti = $data4['periode_cuti'];
        }
        // cek apakah cuti tanggungan perusahaan sudah pernah diambil atau belum
        if ($tipe_cuti > 0) {
            $periode_cuti = Carbon::createFromFormat('Y-m-d', $tanggal_mulai)->year;
            $data4 = $this->isPerusahaanOver($periode_cuti, $nik_karyawan, $tipe_cuti);
        }
        // 
        $arr1 = array();
        $str = $tanggal_mulai;
        for ($x = 0; $x < $jumlah_hari; $x++) {
            // 8 = Melahirkan-Karyawati (90 hari)
            // 12 = Ibadah Umroh (7 hari)
            // 13 = Ibadah Haji Plus (30 hari)
            // 14 = Ibadah Umroh (10 hari)
            // 15 = Ibadah Haji Reguler (40 hari)
            // untuk cuti tersebut Hari Sabtu Minggu dan libur nasional tetap dihitung
            if($tipe_cuti == 8 || $tipe_cuti == 12 || $tipe_cuti == 13 || $tipe_cuti == 14 || $tipe_cuti == 15){
                array_push($arr1, $str);
                $str = date("Y-m-d", strtotime($str . "+1 day"));
            }
            else{
                // jika karyawan reguler maka Hari Sabtu dan Minggu dan hari libur tidak dihitung
                if ($is_reguler == 1) {
                    $str = $this->getTanggalCuti($str);
                }else{
                    $str = $this->getTanggalCutiNonReguler($str, $nik_karyawan);
                }
                array_push($arr1, $str);
                $str = date("Y-m-d", strtotime($str . "+1 day"));
            }
        }
        $tanggal_akhir = $arr1[$jumlah_hari - 1];
        // refer to CM 343
        // ini tidak lagi digunakan
        // Menambahkan cuti tambahan untuk ibadah umroh / 12 = Ibadah Umroh (7 hari)
        // 5 hari kerja sebelum ibadah & 3 hari kerja sesudah ibadah
        // tanggal diambil berdasarkan pengajuan cuti ibadah yang diajukan karyawan
        // 12 = Ibadah Umroh (7 hari)
        // ini yang digunakan
        // Menambahkan cuti tambahan untuk:
        // 13 = Ibadah Haji Plus (30 hari)
        // 14 = Ibadah Umroh (10 hari)
        // 15 = Ibadah Haji Reguler (40 hari)
        // 2 hari kerja sebelum ibadah & 2 hari kerja sesudah ibadah
        // tanggal diambil berdasarkan pengajuan cuti ibadah yang diajukan karyawan
        if($tipe_cuti == 13 || $tipe_cuti == 14 || $tipe_cuti == 15){
            // 2 hari kerja sebelum ibadah
            $hari = 2;
            $jumlah_hari += $hari;
            $arr1_sebelum = array();
            for ($x = 0; $x < $hari; $x++) {
                $tanggal_mulai = $this->getTanggalSebelum($tanggal_mulai, $is_reguler, $nik_karyawan);
                array_push($arr1, $tanggal_mulai);
            }
            // 2 hari kerja sesudah ibadah
            $jumlah_hari += $hari;
            // $arr1_sesudah = array();
            $str = $tanggal_akhir;
            for ($x = 0; $x < $hari; $x++) {
                $tanggal_akhir = $this->getTanggalSesudah($tanggal_akhir, $is_reguler, $nik_karyawan);
                array_push($arr1, $tanggal_akhir);
            }
            sort($arr1);
        }
        // 
        $tanggal_cuti = implode(";", $arr1);
        // 
        $data5 = $this->isCutiExisting($nik_karyawan, $tanggal_mulai, $tanggal_akhir, $periode_cuti);
        $data6 = $this->isSetahun($nik_karyawan, $tanggal_mulai, $tipe_cuti);
        array_push($arr, $data1, $data2, $data3, $data4, $data5, $data6);
        // dd($arr);
        $cek = $data1['status'] && $data2['status'] && $data3['status'] && $data4['status'] && $data5['status'] && $data6['status'];
        // dd($cek, $periode_cuti);
        // finish
        // 
        $trx = new TrxCuti;
        $trx->tipe_cuti = $tipe_cuti;
        $trx->periode_cuti = $periode_cuti;
        $trx->nik_karyawan = $nik_karyawan;
        $where = ['nik' => $nik_karyawan, 'is_aktif' => 1];
        $dataKaryawan = Karyawan::where($where)->first();
        $nik_atasan = $dataKaryawan->layer1;
        $trx->nik_atasan = $nik_atasan;
        $trx->pic_pengganti = implode(';', $request->pic_pengganti);
        $trx->karyawan_property = $this->getNikProperty($nik_karyawan);
        $trx->atasan_property = $this->getNikProperty($nik_atasan);
        $pic_property = array();
        foreach ($request->pic_pengganti as $value) {
            array_push($pic_property, $this->getNikProperty($value));
        }
        $trx->pic_property = implode(';', $pic_property);
        $trx->tanggal_mulai = $tanggal_mulai;
        $trx->tanggal_akhir = $tanggal_akhir;
        $trx->jumlah_hari = $jumlah_hari;
        $trx->tanggal_cuti = $tanggal_cuti;
        $trx->alasan = $request->alasan;
        $trx->alamat = $request->alamat;
        $trx->telepon = $request->telepon;
        // $trx->telepon = $dataKaryawan->telepon1;
        $trx->keterangan = $request->keterangan;
        $trx->status_cuti = 1;
        // 
        if($preview == 'preview'){
            $tanggal_mulai = $this->getTanggalIndonesia($trx->tanggal_mulai);
            $tanggal_akhir = $this->getTanggalIndonesia($trx->tanggal_akhir);
            $tanggal_cuti = explode(';', $trx->tanggal_cuti);
            $arrTanggalCuti = array();
            foreach ($tanggal_cuti as $value) {
                array_push($arrTanggalCuti, $this->getTanggalIndonesia($value));
            }
            $is_reguler = Karyawan::where('nik', $trx->nik_karyawan)->first()->_wg->is_reguler;
            $tanggal_masuk = $this->getTanggalMasuk($trx->tanggal_akhir, $is_reguler, $trx->nik_karyawan);
            $arrTanggal = ['tanggal_mulai' => $tanggal_mulai, 'tanggal_akhir' => $tanggal_akhir, 'arrTanggalCuti' => $arrTanggalCuti, 'tanggal_masuk' => $tanggal_masuk];
            $pic_property = explode(';', $trx->pic_property);
            $arrPicProperty = array();
            foreach ($pic_property as $value) {
                array_push($arrPicProperty, json_decode($value, true));
            }
            if ($cek) {
                return view('formulir.cuti.preview', compact('trx', 'arrTanggal', 'arrPicProperty', 'cek', 'arr'));
            } else {
                return view('formulir.cuti.preview', compact('trx', 'arrTanggal', 'arrPicProperty', 'cek', 'arr'));
            }
        }
        // 
        if ($cek) {
            $trx->save();
            $this->insertLog($trx->id, $trx->status_cuti, $nik_karyawan, 'INSERT');
            // start
            // parameter yang dipassing ke sendemail
            $karyawan = json_decode($trx->karyawan_property, true);
            $atasan = json_decode($trx->atasan_property, true);
            $mailData['title'] = 'Formulir Permohonan Cuti';
            $mailData['filename'] = 'emails.cuti0';
            foreach (explode(';', $trx->pic_property) as $value) {
                $pic_property = json_decode($value, true);
                $mailData['data'] = ['nama' => $karyawan['nama'].'/'.$karyawan['nik'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'], 'atasan' => $atasan['nama'], 'link' => route('cuti.show', [(new OpenDataController)->encryptWithKey($trx->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($pic_property['nik']), 'pic' => (new OpenDataController)->encryptWithKey('pic_pengganti')]), 'id_trx' => $trx->id];
                $mailData['to'] = $pic_property['email'];
                $todolist = ['id_trx' => $trx->id, 'nik' => $pic_property['nik'], 'id_status' => $trx->status_cuti, 'pic' => 'pic_pengganti'];
                $this->insertToDoList($todolist, $mailData);
                $this->sendEmail($mailData);
            }
            // finish
            // 
            return redirect('cuti')->with('msg', 'Data is saved successfully.');
        } else {
            // return view('formulir.cuti.error', compact(['arr']));
            return redirect()->back()->with('error', $arr);
        }
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
        $data = new TrxCutiLog;
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
        $linkAction = route('cuti.update', $id);
        $id = (new OpenDataController)->decryptWithKey($id);
        // dd($request->all(), $id);
        $where = ['id_trx' => $id];
        $history = TrxCutiLog::where($where)->orderBy('created_at', 'desc')->get();
        // 
        $trxCuti = TrxCuti::find($id);
        $karyawan = json_decode($trxCuti->karyawan_property, true);
        $atasan = json_decode($trxCuti->atasan_property, true);
        // print_r($atasan);die;
        $tanggal_mulai = $this->getTanggalIndonesia($trxCuti->tanggal_mulai);
        $tanggal_akhir = $this->getTanggalIndonesia($trxCuti->tanggal_akhir);
        $tanggal_cuti = explode(';', $trxCuti->tanggal_cuti);
        $arrTanggalCuti = array();
        foreach ($tanggal_cuti as $value) {
            array_push($arrTanggalCuti, $this->getTanggalIndonesia($value));
        }
        $is_reguler = Karyawan::where('nik', $trxCuti->nik_karyawan)->first()->_wg->is_reguler;
        $tanggal_masuk = $this->getTanggalMasuk($trxCuti->tanggal_akhir, $is_reguler, $trxCuti->nik_karyawan);
        $arrTanggal = ['tanggal_mulai' => $tanggal_mulai, 'tanggal_akhir' => $tanggal_akhir, 'arrTanggalCuti' => $arrTanggalCuti, 'tanggal_masuk' => $tanggal_masuk];
        $pic_property = explode(';', $trxCuti->pic_property);
        $arrPicProperty = array();
        foreach ($pic_property as $value) {
            array_push($arrPicProperty, json_decode($value, true));
        }
        $data = ['nik' => $karyawan['nik'], 'nama' => $karyawan['nama'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep']];
        $view = $request->view;
        $pic = $request->pic;
        $nik = $request->nik;
        $view = (new OpenDataController)->decryptWithKey($view);
        if(!empty($pic)){
            $pic = (new OpenDataController)->decryptWithKey($pic);
        }
        if(!empty($nik)){
            $nik = (new OpenDataController)->decryptWithKey($nik);
        }
        // 
        $where = ['id_trx' => $id, 'nik_pic' => $nik, 'id_status' => 2];
        if($pic == 'nik_atasan'){
            $where = ['id_trx' => $id, 'nik_pic' => $nik, 'id_status' => 4];
        }
        $nPic = TrxCutiLog::where($where)->count();
        $pic_pengganti = explode(';', $trxCuti->pic_pengganti);
        // 4 Disetujui oleh Layer 1
        // 5 Ditolak oleh Layer 1
        // 7 Ditolak oleh PIC Pengganti
        $where = ['id_trx' => $id];
        $isTolak = TrxCutiLog::where($where)->whereIn('id_status', [4,5,7,8])->exists();
        // 
        switch ($view) {
            // PIC pengganti dapat mereject 
            // dan salah satu pic pengganti reject berlaku untuk pic pengganti yg lain otomatis reject.
            // Ini berlaku jika atasan pengaju belum melakukan approve/reject.
            // Jika atasan pengaju sudah melakukan approve/reject maka pic pengganti yang belum melakukan approve/reject tidak bisa melakukan approve/reject.
            case 'approval':
                if ($pic == 'pic_pengganti' && in_array($nik, $pic_pengganti) && $nPic == 0) {
                    if($isTolak){
                        return view('emails.cuti_ok');
                    }
                    return view('emails.cuti', compact('trxCuti', 'data', 'arrTanggal', 'arrPicProperty', 'linkAction', 'pic', 'nik', 'history'));
                } else if ($pic == 'nik_atasan' && $nik == $trxCuti->nik_atasan && $nPic == 0) {
                    if($isTolak){
                        return view('emails.cuti_ok');
                    }
                    return view('emails.cuti', compact('trxCuti', 'data', 'arrTanggal', 'arrPicProperty', 'linkAction', 'pic', 'nik', 'history'));
                } else if ($pic == 'nik_hco' && $nik == MtrUtility::find(23)->informasi) {
                    $where = ['id_trx' => $id, 'nik_pic' => MtrUtility::find(23)->informasi, 'id_status' => 6];
                    $nPic = TrxCutiLog::where($where)->exists();
                    if($nPic){
                        return view('emails.cuti_ok');
                    }
                    return view('emails.cuti', compact('trxCuti', 'data', 'arrTanggal', 'arrPicProperty', 'linkAction', 'pic', 'nik', 'history'));
                } else {
                    return view('emails.cuti_ok');
                }
                break;
            case 'detail':
                $this->authorize('auth');
                // 
                $where = ['id_trx' => $id];
                $history = TrxCutiLog::where($where)->orderBy('created_at', 'desc')->get();
                // 
                return view('formulir.cuti.view', compact('history', 'trxCuti', 'karyawan', 'atasan', 'arrTanggal', 'arrPicProperty'));
                break;
            case 'pdf':
                $this->authorize('auth');
                // print to pdf
                $waktu = date('YmdHis');
                // start
                $htmlCutiLog = '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0"><tr>';
                // karyawan
                $where = ['id_trx' => $id, 'nik_pic' => $trxCuti->nik_karyawan, 'id_status' => 1];
                $cutiLog = TrxCutiLog::where($where)->orderBy('id', 'desc')->first();
                if (!empty($cutiLog)) {
                    $htmlCutiLog .= '<td>Mengajukan<br/><img src="' . public_path('assets/images/matta/requested_new.png') . '" height="30" /><br/>' . $karyawan['nama'] . '<br/>' . $karyawan['jabatan'] . '<br/>' . $cutiLog->created_at . '</td>';
                }
                // pic pengganti
                // 2 Disetujui oleh PIC Pengganti
                $where = ['id_trx' => $id, 'id_status' => 2];
                $cutiLog = TrxCutiLog::where($where)->groupBy('nik_pic')->orderBy('id', 'desc')->get();
                if (!empty($cutiLog) && count($cutiLog) > 0) {
                    $htmlCutiLog .= '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>';
                    foreach ($cutiLog as $value) {
                        $_pic = $arrPicProperty[array_search($value->nik_pic, $pic_pengganti)];
                        $htmlCutiLog .= $_pic['nama'] . '<br/>' . $_pic['jabatan'] . '<br/>' . $value->created_at . '<br/>';
                    }
                    $htmlCutiLog .= '</td>';
                }
                // 7 Ditolak oleh PIC Pengganti
                $where = ['id_trx' => $id, 'id_status' => 7];
                $cutiLog = TrxCutiLog::where($where)->groupBy('nik_pic')->orderBy('id', 'desc')->get();
                if (!empty($cutiLog) && count($cutiLog) > 0) {
                    $htmlCutiLog .= '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>';
                    foreach ($cutiLog as $value) {
                        $_pic = $arrPicProperty[array_search($value->nik_pic, $pic_pengganti)];
                        $htmlCutiLog .= $_pic['nama'] . '<br/>' . $_pic['jabatan'] . '<br/>' . $value->created_at . '<br/>';
                    }
                    $htmlCutiLog .= '</td>';
                }
                // atasan
                // 4 Disetujui oleh Layer 1
                $where = ['id_trx' => $id, 'nik_pic' => $trxCuti->nik_atasan, 'id_status' => 4];
                $cutiLog = TrxCutiLog::where($where)->orderBy('id', 'desc')->first();
                if (!empty($cutiLog)) {
                    $htmlCutiLog .= '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $atasan['nama'] . '<br/>' . $atasan['jabatan'] . '<br/>' . $cutiLog->created_at . '</td>';
                }
                // 5 Ditolak oleh Layer 1
                $where = ['id_trx' => $id, 'nik_pic' => $trxCuti->nik_atasan, 'id_status' => 5];
                $cutiLog = TrxCutiLog::where($where)->orderBy('id', 'desc')->first();
                if (!empty($cutiLog)) {
                    $htmlCutiLog .= '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $atasan['nama'] . '<br/>' . $atasan['jabatan'] . '<br/>' . $cutiLog->created_at . '</td>';
                }
                // hco
                $where = ['id_trx' => $id, 'id_status' => 6];
                $cutiLog = TrxCutiLog::where($where)->orderBy('id', 'desc')->first();
                if (!empty($cutiLog)) {
                    $htmlCutiLog .= '<td>Mengetahui<br/><img src="' . public_path('assets/images/matta/informed_new.png') . '" height="30" /><br/>' . $cutiLog->_pic->nama . '<br/>' . $cutiLog->_pic->_jabatan->jabatan . '<br/>' . $cutiLog->created_at . '</td>';
                }
                // 8 Ditolak oleh hco
                $where = ['id_trx' => $id, 'id_status' => 8];
                $cutiLog = TrxCutiLog::where($where)->orderBy('id', 'desc')->first();
                if (!empty($cutiLog)) {
                    $htmlCutiLog .= '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $cutiLog->_pic->nama . '<br/>' . $cutiLog->_pic->_jabatan->jabatan . '<br/>' . $cutiLog->created_at . '</td>';
                }
                // finish
                $htmlCutiLog .= '</tr></table>';
                // die;
                // 
                $filename = $waktu . '_formulir_permohonan_cuti.pdf';
                $view = \View::make('formulir.cuti.pdf', compact('trxCuti', 'karyawan', 'arrTanggal', 'arrPicProperty', 'htmlCutiLog'));
                $html = $view->render();
                $pdf = new TCPDF;
                $pdf::SetTitle('Formulir Permohonan Cuti');
                $pdf::SetFont('times', '', 10, '', true);
                $pdf::AddPage();
                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output(public_path($filename), 'I');
                return response()->download(public_path($filename));
                break;
            default:
                return view('emails.cuti_ok');
        }
    }

    public function getTanggalIndonesia($tgl)
    {
        $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $strTgl = $hari[Carbon::createFromFormat('Y-m-d', $tgl)->dayOfWeek] . ', ';
        $strTgl .= Carbon::createFromFormat('Y-m-d', $tgl)->format('d-m-Y');
        return $strTgl;
    }

    public function getTanggalSebelum($tgl, $is_reguler, $nik)
    {
        if ($is_reguler == 1) {
            $tgl = date("Y-m-d", strtotime($tgl . "-1 day"));
            while (!$this->isWeekend($tgl)['status'] || !$this->isHoliday($tgl)['status']) {
                $tgl = date("Y-m-d", strtotime($tgl . "-1 day"));
            }
        } else {
            $tgl = date("Y-m-d", strtotime($tgl . "-1 day"));
            $tgl = $this->getTanggalCutiNonRegulerSebelum($tgl, $nik);
        }
        return $tgl;
    }

    public function getTanggalSesudah($tgl, $is_reguler, $nik)
    {
        if ($is_reguler == 1) {
            $tgl = date("Y-m-d", strtotime($tgl . "+1 day"));
            while (!$this->isWeekend($tgl)['status'] || !$this->isHoliday($tgl)['status']) {
                $tgl = date("Y-m-d", strtotime($tgl . "+1 day"));
            }
        } else {
            $tgl = date("Y-m-d", strtotime($tgl . "+1 day"));
            $tgl = $this->getTanggalCutiNonReguler($tgl, $nik);
        }
        return $tgl;
    }

    public function getTanggalMasuk($tgl, $is_reguler, $nik)
    {
        if ($is_reguler == 1) {
            $tgl = date("Y-m-d", strtotime($tgl . "+1 day"));
            while (!$this->isWeekend($tgl)['status'] || !$this->isHoliday($tgl)['status']) {
                $tgl = date("Y-m-d", strtotime($tgl . "+1 day"));
            }
        } else {
            $tgl = date("Y-m-d", strtotime($tgl . "+1 day"));
            $tgl = $this->getTanggalCutiNonReguler($tgl, $nik);
        }
        return $this->getTanggalIndonesia($tgl);
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
        // dd($request->all(), $id);
        $id = (new OpenDataController)->decryptWithKey($id);
        $trx = TrxCuti::find($id);
        $pic = $request->pic;
        $nik = $request->nik;
        $status_cuti = $request->status_cuti;
        $catatan = $request->catatan;
        // $status_cuti = 0;
        // if($pic == 'pic_pengganti'){
        //     $status_cuti = 2;
        //     $trx->status_cuti = $status_cuti;
        // }
        $trx->status_cuti = $status_cuti;
        $trx->update();
        $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.cuti0']);
        $this->insertLog($id, $status_cuti, $nik, $catatan);
        // jika direject maka todolist didelete
        // 5=Ditolak oleh Layer 1
        // 7=Ditolak oleh PIC Pengganti
        if($status_cuti == 5 || $status_cuti == 7){
            ToDoList::where(['id_trx'=>$id, 'filename'=>'emails.cuti0'])->delete();
        }
        // jika sudah ada 1 pic pengganti yang approve
        $where = ['id_trx' => $id, 'id_status' => 2];
        $nPic = TrxCutiLog::where($where)->count();
        if ($nPic == 1 && $pic == 'pic_pengganti') {
            // 7 Ditolak oleh PIC Pengganti
            $where = ['id_trx' => $id, 'id_status' => 7];
            $isTolak = TrxCutiLog::where($where)->exists();
            if($isTolak){
                return view('emails.cuti_ok');
            }
            // start
            // parameter yang dipassing ke sendemail
            $karyawan = json_decode($trx->karyawan_property, true);
            $atasan = json_decode($trx->atasan_property, true);
            $mailData['title'] = 'Formulir Permohonan Cuti';
            $mailData['filename'] = 'emails.cuti0';
            $mailData['data'] = ['nama' => $karyawan['nama'].'/'.$karyawan['nik'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'], 'atasan' => $atasan['nama'], 'link' => route('cuti.show', [(new OpenDataController)->encryptWithKey($id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($atasan['nik']), 'pic' => (new OpenDataController)->encryptWithKey('nik_atasan')]), 'id_trx' => $id];
            $mailData['to'] = $atasan['email'];
            $todolist = ['id_trx' => $id, 'nik' => $atasan['nik'], 'id_status' => $status_cuti, 'pic' => 'nik_atasan'];
            $this->insertToDoList($todolist, $mailData);
            $this->sendEmail($mailData);
            // finish
        }
        else if ($status_cuti == 4 && $pic == 'nik_atasan') {
            ToDoList::where(['id_trx'=>$id, 'filename'=>'emails.cuti0'])->delete();
            // hco
            // 23 = PIC PC Formulir Cuti (Novia Rahmayanti Ikmal)
            $nik_hco = MtrUtility::find(23)->informasi;
            // start
            $karyawan = json_decode($trx->karyawan_property, true);
            $atasan = json_decode($trx->atasan_property, true);
            $mailData['title'] = 'Formulir Permohonan Cuti';
            $mailData['filename'] = 'emails.cuti0';
            $mailData['data'] = ['nama' => $karyawan['nama'].'/'.$karyawan['nik'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'], 'atasan' => $atasan['nama'], 'link' => route('cuti.show', [(new OpenDataController)->encryptWithKey($id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($nik_hco), 'pic' => (new OpenDataController)->encryptWithKey('nik_hco')]), 'id_trx' => $id];
            $mailData['to'] = Karyawan::where('nik', $nik_hco)->first()->email;
            $todolist = ['id_trx' => $id, 'nik' => $nik_hco, 'id_status' => $status_cuti, 'pic' => 'nik_hco'];
            $this->insertToDoList($todolist, $mailData);
            $this->sendEmail($mailData);
            // finish
            // tidak digunakan karena hco kembali dilibatkan
            // start
            // $karyawan = json_decode($trx->karyawan_property, true);
            // $atasan = json_decode($trx->atasan_property, true);
            // $mailData['title'] = 'Formulir Permohonan Cuti';
            // $mailData['filename'] = 'emails.cuti1';
            // $mailData['data'] = ['nama' => $karyawan['nama'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'], 'atasan' => $atasan['nama'], 'id_trx' => $id];
            // $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
            // $this->sendEmail($mailData);
            // finish
            // karyawan
            // start
            $karyawan = json_decode($trx->karyawan_property, true);
            $atasan = json_decode($trx->atasan_property, true);
            $mailData['title'] = 'Formulir Permohonan Cuti';
            $mailData['filename'] = 'emails.cuti_done';
            $mailData['data'] = ['pesan' => '<strong>Formulir Permohonan Cuti anda telah disetujui para pihak</strong>', 'nama' => $karyawan['nama'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'], 'atasan' => $atasan['nama'], 'link' => route('cuti.show', [(new OpenDataController)->encryptWithKey($id), 'view' => (new OpenDataController)->encryptWithKey('pdf')]), 'id_trx' => $id];
            $mailData['to'] = $karyawan['email'];
            $this->sendEmail($mailData);
            // finish
        }
        else if ($status_cuti == 6 && $pic == 'nik_hco') {
            ToDoList::where(['id_trx'=>$id, 'filename'=>'emails.cuti0'])->delete();
            // tidak perlu diemail ke karyawan jika sudah diketahui oleh hco
            // karyawan
            // start
            // $karyawan = json_decode($trx->karyawan_property, true);
            // $atasan = json_decode($trx->atasan_property, true);
            // $mailData['title'] = 'Formulir Permohonan Cuti';
            // $mailData['filename'] = 'emails.cuti_done';
            // $mailData['data'] = ['nama' => $karyawan['nama'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'], 'atasan' => $atasan['nama'], 'link' => route('cuti.show', [(new OpenDataController)->encryptWithKey($id), 'view' => (new OpenDataController)->encryptWithKey('pdf')]), 'id_trx' => $id];
            // $mailData['to'] = $karyawan['email'];
            // $this->sendEmail($mailData);
            // finish
        }
        else if ($status_cuti == 8 && $pic == 'reject_hco') {
            // dd($request->all(), $id);
            // start
            $karyawan = json_decode($trx->karyawan_property, true);
            $atasan = json_decode($trx->atasan_property, true);
            $mailData['title'] = 'Formulir Permohonan Cuti';
            $mailData['filename'] = 'emails.cuti_done';
            $mailData['data'] = ['pesan' => '<strong>Formulir Permohonan Cuti anda telah ditolak '.MtrUtility::find(51)->informasi.'</strong>', 'nama' => $karyawan['nama'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'], 'atasan' => $atasan['nama'], 'link' => route('cuti.show', [(new OpenDataController)->encryptWithKey($id), 'view' => (new OpenDataController)->encryptWithKey('pdf')]), 'id_trx' => $trx->id];
            $mailData['to'] = $karyawan['email'];
            $ccEmails = explode(',', MtrUtility::find(11)->informasi);
            array_push($ccEmails, $atasan['email']);
            foreach (explode(';', $trx->pic_property) as $value) {
                $pic_property = json_decode($value, true);
                array_push($ccEmails, $pic_property['email']);
            }
            // dd($ccEmails);
            Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
            // finish
            return redirect('cuti')->with('msg', 'Data is saved successfully.');
        }
        // 
        return view('emails.cuti_ok');
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