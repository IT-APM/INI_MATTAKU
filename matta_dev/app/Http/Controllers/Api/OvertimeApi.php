<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perusahaan;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Karyawan;
use App\Models\Overtime;
use App\Models\OvertimeLog;
use App\Models\OvertimeType;
use App\Models\WorkingDay;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// 
use App\Models\ToDoList;
use App\Models\ToDoListLog;
use App\Models\MtrUtility;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;

class OvertimeApi extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $nik = '01021003077';
        // $from = '2025-09-08 19:00:00';
        // $to = '2025-09-09 09:00:00';
        // dd($this->cekDatetimeKerja($nik, $from, $to));
        // Maulid Nabi Muhammad S.A.W
        // 2025-09-05
        // dd($this->isHoliday('2025-09-05'));
        // dd($this->isWeekend('2025-09-29'));
        // Carbon::now()->dayOfWeek;
        // echo Carbon::createFromFormat('Y-m-d', '2023-03-18')->weekOfYear;die;
        // $mulai = Carbon::createFromFormat('Y-m-d', '2023-03-14')->startOfWeek()->format('Y-m-d');
        // echo $mulai;die;
        // non reguler
        // $nik = '01031604597';
        // $nik = 'CV0018572';
        // reguler
        // $nik = '01030908046';
        // $from = '2023-03-19 20:00:00';
        // $to = '2023-03-20 08:00:00';
        // $waktu = date('Y-m-d H:i:s');
        // $lengthOT = Carbon::createFromFormat('Y-m-d H:i:s', $from)->diffInSeconds(Carbon::createFromFormat('Y-m-d H:i:s', $to));
        // $lengthOT = 64800;
        // $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $from)->startOfWeek()->format('Y-m-d');
        // $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $from)->endOfWeek()->format('Y-m-d');
        // return response()->json( [ 'start_date' => $start_date, 'end_date' => $end_date ] );
        // die;
        // $data5 = $this->calOTLength($nik, $from, $lengthOT);
        // return response()->json( [ 'data' => $data5 ] );
        // die;
        // $arr = array();
        // $data1 = $this->isOTExisting($nik, $from, $to);
        // $data2 = $this->checkIfExist($from, $waktu);
        // $data3 = $this->checkStartEnd($from, $to);
        // $data4 = $this->cekDatetimeKerja($nik, $from, $to);
        // $data5 = $this->calOTLength($nik, $from, $lengthOT);
        // array_push($arr, $data1, $data2, $data3, $data4, $data5);
        // $cek = $data1['status'] && $data2['status'] && $data3['status'] && $data4['status'] && $data5['status'];
        // return response()->json( [ 'data' => $arr ] );
        // $nik = '01031510389';
        // return $this->getLayer1($nik);
        // $data = Attendance::orderBy( 'id', 'desc' )->get();
        // return response()->json( [ 'success' => true, 'message' => 'Data is viewed successfully ', 'data' => $data ], 200 );
    }

    // fungsi menghitung jumlah OT dalam weekday dan weekend
    public function calOTLength($nik, $from, $length){
        // ambil default jumlah OT yang dibolehkan
        $where = [ 'nik' => $nik ];
        $data = Karyawan::select('mtr_tbl_working_group.ot_weekday','mtr_tbl_working_group.ot_weekend','mtr_tbl_working_group.is_reguler','mtr_tbl_working_group.wg_ot_monthly','mtr_tbl_working_group.wg_ot_weekly','mtr_tbl_working_group.wg_ot_daily')
        ->leftJoin('mtr_tbl_working_group', 'mtr_tbl_working_group.id', '=', 'mtr_tbl_karyawan.wg_id')
        ->where($where)->first();
        // return [ 'data' => $data ];die;
        // ambil tanggal awal dan akhir di minggu pengajuan (1 minggu adalah Hari Senin sd Minggu di tanggal tersebut)
        $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $from)->startOfWeek()->format('Y-m-d');
        $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $from)->endOfWeek()->format('Y-m-d');
        // masukkan ke dalam kumpulan array
        $arrWeekDay = array();
        $arrWeekEnd = array();
        // weekday reguler adalah senin sd jumat dan weekend adalah sabtu & minggu,
        // jika ada hari libur nasional/cuti bersama di weekday maka akan masuk ke weekend
        if($data['is_reguler'] == 1){
            while (strtotime($start_date) <= strtotime($end_date)) {
                $_dayOfWeek = Carbon::createFromFormat('Y-m-d', $start_date)->dayOfWeek;
                if($_dayOfWeek == 0 || $_dayOfWeek == 6){
                    array_push($arrWeekEnd, $start_date);
                }else{
                    // hari libur nasional/cuti bersama masuk kategori weekend
                    if($this->isHoliday($start_date)){
                        array_push($arrWeekEnd, $start_date);
                    }else{
                        array_push($arrWeekDay, $start_date);
                    }
                }
                $start_date = date ("Y-m-d", strtotime("+1 days", strtotime($start_date)));
            }
        }
        // weekday dan weekend non reguler diambil dari WorkingDay
        // jika ada hari libur nasional/cuti bersama di WorkingDay maka akan masuk ke weekday
        else{
            while (strtotime($start_date) <= strtotime($end_date)) {
                $cek = false;
                $where = [ 'nik' => $nik, 'working_date' => $start_date ];
                $dataWD = WorkingDay::where($where)->count();
                if($dataWD>0){
                    $cek = true;
                }
                if($cek){
                    array_push($arrWeekDay, $start_date);
                }else{
                    array_push($arrWeekEnd, $start_date);
                }
                $start_date = date ("Y-m-d", strtotime("+1 days", strtotime($start_date)));
            }
        }
        $calWeekDay = 0;
        $calWeekEnd = 0;
        // dihitung semua OT di weekday dan weekend
        foreach ($arrWeekDay as $_arrWeekDay) {
            $where = [ 'ot_maker_by' => $nik ];
            $calWeekDay += Overtime::where($where)->whereDate('ot_start_at','=',$_arrWeekDay)->whereIn('ot_approver_status', ['Approve','Waiting'])->sum('ot_length');
        }
        foreach ($arrWeekEnd as $_arrWeekEnd) {
            $where = [ 'ot_maker_by' => $nik ];
            $calWeekEnd += Overtime::where($where)->whereDate('ot_start_at','=',$_arrWeekEnd)->whereIn('ot_approver_status', ['Approve','Waiting'])->sum('ot_length');
        }
        // sisa weekday dan weekend yang masih bisa diambil
        $calWeekDay = $data['ot_weekday']*3600 - $calWeekDay;
        $calWeekEnd = $data['ot_weekend']*3600 - $calWeekEnd;
        // cek reguler
        if($data['is_reguler'] == 1){
            // cek apakah pengajuan di weekday atau weekend
            $cekFrom = Carbon::createFromFormat('Y-m-d H:i:s', $from)->dayOfWeek;
            if($cekFrom==0 || $cekFrom==6 || $this->isHoliday(Carbon::createFromFormat('Y-m-d H:i:s', $from)->format('Y-m-d'))){
                if($length>$calWeekEnd){
                    return [ 'status' => false, 'message' => 'Reguler, Jumlah OT weekend yang bisa diambil tidak melebihi quota', 'quota'=>$this->secondsToTime($calWeekEnd) ];
                }else{
                    return [ 'status' => true, 'message' => 'Reguler, Jumlah OT weekend yang bisa diambil tidak melebihi quota', 'quota'=>$this->secondsToTime($calWeekEnd) ];
                }
            }else{
                if($length>$calWeekDay){
                    return [ 'status' => false, 'message' => 'Reguler, Jumlah OT weekday yang bisa diambil tidak melebihi quota', 'quota'=>$this->secondsToTime($calWeekDay) ];
                }else{
                    return [ 'status' => true, 'message' => 'Reguler, Jumlah OT weekday yang bisa diambil tidak melebihi quota', 'quota'=>$this->secondsToTime($calWeekDay) ];
                }
            }
        }
        // cek non reguler
        else{
            // cek apakah pengajuan di weekday atau weekend
            $cekFrom = Carbon::createFromFormat('Y-m-d H:i:s', $from)->format('Y-m-d');
            $cek = false;
            $where = [ 'nik' => $nik, 'working_date' => $cekFrom ];
            $dataWD = WorkingDay::where($where)->count();
            if($dataWD>0){
                $cek = true;
            }
            if($cek){
                if($length>$calWeekDay){
                    return [ 'status' => false, 'message' => 'Non Reguler, Jumlah OT weekday yang bisa diambil tidak melebihi quota', 'quota'=>$this->secondsToTime($calWeekDay) ];
                }else{
                    return [ 'status' => true, 'message' => 'Non Reguler, Jumlah OT weekday yang bisa diambil tidak melebihi quota', 'quota'=>$this->secondsToTime($calWeekDay) ];
                }
            }else{
                if($length>$calWeekEnd){
                    return [ 'status' => false, 'message' => 'Non Reguler, Jumlah OT weekend yang bisa diambil tidak melebihi quota', 'quota'=>$this->secondsToTime($calWeekEnd) ];
                }else{
                    return [ 'status' => true, 'message' => 'Non Reguler, Jumlah OT weekend yang bisa diambil tidak melebihi quota', 'quota'=>$this->secondsToTime($calWeekEnd) ];
                }
            }
        }
        // return [ 'status' => true, 'message' => 'Jumlah OT yang bisa diambil (detik)', 'calWeekDay'=>$calWeekDay, 'calWeekEnd'=>$calWeekEnd ];
    }

    // fungsi merubah detik menjadi jam
    public function secondsToTime($seconds_time)
    {
        // if ($seconds_time < 24 * 60 * 60) {
        //     return gmdate('H:i:s', $seconds_time);
        // } else {
            $hours = floor($seconds_time / 3600);
            $hours = str_pad($hours, 2, '0', STR_PAD_LEFT);
            $minutes = floor(($seconds_time - $hours * 3600) / 60);
            $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
            $seconds = floor($seconds_time - ($hours * 3600) - ($minutes * 60));
            $seconds = str_pad($seconds, 2, '0', STR_PAD_LEFT);
            return "$hours jam, $minutes menit, $seconds detik";
        // }
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
        $waktu = date('Y-m-d H:i:s');
        $nik = $request->ot_maker_by;
        $from = $request->ot_start_at;
        $to = $request->ot_end_at;
        $ot_approver_by = $this->getLayer1($nik)['layer1'];
        $data = New Overtime;
        $data->ot_maker_at = $waktu;
        $data->ot_maker_by = $nik;
        $data->ot_maker_property = $this->getNikProperty($nik);
        $data->ot_freelance_property = $this->getFreelanceProperty($nik);
        $data->ot_approver_by = $ot_approver_by;
        $data->ot_approver_property = $this->getNikProperty($ot_approver_by);
        $data->ot_maker_status = 'Waiting';
        $data->ot_approver_status = 'Waiting';
        $data->ot_start_at = $from;
        $data->ot_end_at = $to;
        $diff = Carbon::createFromFormat('Y-m-d H:i:s', $from)->diffInSeconds(Carbon::createFromFormat('Y-m-d H:i:s', $to));
        $data->ot_length = $diff;
        $data->ot_start_clockin = Carbon::createFromFormat('Y-m-d H:i:s', $from)->subHour();
        $data->ot_end_clockin = $to;
        // bisa CO saat waktu OT mulai
        // permintaan Mbak Puput karena disamakan dengan matta lama
        $data->ot_start_clockout = $from;
        // bisa CO saat waktu OT berakhir
        // $data->ot_start_clockout = $to;
        $data->ot_end_clockout = Carbon::createFromFormat('Y-m-d H:i:s', $to)->addHours(3);
        $data->ot_title = $request->ot_title;
        $data->ot_desc = $request->ot_desc;
        $data->ot_type_code = $this->getOTCode($request->ot_type)['id'];
        // cek semua kemungkinan pengajuan OT
        $arr = array();
        // Pengajuan OT tidak beririsan dengan OT lainnya
        $data1 = $this->isOTExisting($nik, $from, $to);
        // Pengajuan OT lebih besar dari saat ini
        $data2 = $this->checkIfExist($from, $waktu);
        // Datetime akhir OT lebih besar dari Datetime mulai OT
        $data3 = $this->checkStartEnd($from, $to);
        // Datetime OT bukan waktu kerja
        $data4 = $this->cekDatetimeKerja($nik, $from, $to);
        // return response()->json( [ 'success' => true, 'data4' => $data4 ], 200 );
        // Jumlah OT weekday yang bisa diambil tidak melebihi quota
        $data5 = $this->calOTLength($nik, $from, $diff);
        array_push($arr, $data1, $data2, $data3, $data4, $data5);
        $cek = $data1['status'] && $data2['status'] && $data3['status'] && $data4['status'] && $data5['status'];
        if($cek){
            $data->save();
            // Overtime::updateOrCreate(
            //     ['ot_maker_at'=>$waktu],
            //     ['ot_maker_by'=>$nik],
            //     ['ot_maker_property'=>$this->getNikProperty($nik)],
            //     ['ot_freelance_property'=>$this->getFreelanceProperty($nik)],
            //     ['ot_approver_by'=>$ot_approver_by],
            //     ['ot_approver_property'=>$this->getNikProperty($ot_approver_by)],
            //     ['ot_maker_status'=>'Waiting'],
            //     ['ot_approver_status'=>'Waiting'],
            //     ['ot_start_at'=>$from],
            //     ['ot_end_at'=>$to],
            //     ['ot_length'=>$diff],
            //     ['ot_start_clockin'=>Carbon::createFromFormat('Y-m-d H:i:s', $from)->subHour()],
            //     ['ot_end_clockin'=>$to],
            //     ['ot_start_clockout'=>$from],
            //     ['ot_end_clockout'=>Carbon::createFromFormat('Y-m-d H:i:s', $to)->addHours(3)],
            //     ['ot_title'=>$request->ot_title],
            //     ['ot_desc'=>$request->ot_desc],
            //     ['ot_type_code'=>$this->getOTCode($request->ot_type)['id']]
            // );
            // parameter yang dipassing ke sendemail
            // start
            $pengaju = json_decode($this->getNikProperty($nik), true);
            $atasan = json_decode($this->getNikProperty($ot_approver_by), true);
            $overtime = ['ot_start_at'=>$from, 'ot_end_at'=>$to, 'ot_title'=>$request->ot_title, 'ot_desc'=>$request->ot_desc, 'ot_type'=>$request->ot_type];
            // $mailData['title'] = 'Notifikasi Lembur - '.$pengaju['nama'].' => '.$from.' sd '.$to;
            $mailData['title'] = 'Notifikasi Lembur - '.$pengaju['nama'];
            $mailData['data'] = ['nama'=>$pengaju['nama'].'/'.$pengaju['nik'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'atasan'=>$atasan['nama'], 'overtime'=>$overtime, 'link'=> route('approval-review', [$data->id])];
            $mailData['filename'] = 'emails.lembur0';
            $mailData['to'] = $atasan['email'];
            $this->sendEmail($mailData, $pengaju['email']);
            // 
            $mailData['data'] = ['nama'=>$pengaju['nama'].'/'.$pengaju['nik'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'atasan'=>$atasan['nama'], 'overtime'=>$overtime, 'link'=> route('approval-review-todolist', [$data->id])];
            $todolist = ['id_trx' => $data->id, 'nik' => $atasan['nik'], 'id_status' => 1, 'pic' => 'atasan'];
            $this->insertToDoList($todolist, $mailData);
            // end
            // 
            $_log = new OvertimeLog;
            $_log->id_ot = $data->id;
            $_log->ot_approver_status = 'Waiting';
            $_log->save();
            // 
            return response()->json( [ 'success' => true, 'message' => 'Data is inserted successfully.', 'data' => $mailData ], 200 );
        }else{
            return response()->json( [ 'success' => false, 'message' => $arr ], 200 );
        }
        //
    }

    public function getNikProperty($nik)
    {
        // $where = ['nik' => $nik, 'is_aktif' => 1];
        $where = ['nik' => $nik];
        $data = Karyawan::select('mtr_tbl_karyawan.nik', 'mtr_tbl_karyawan.nama', 'mtr_tbl_karyawan.email', 'mtr_tbl_karyawan.telepon1', 
        'mtr_tbl_perusahaan.nama as perusahaan', 'mtr_tbl_divisi.nama as divisi', 'mtr_tbl_departemen.nama as dep', 'mtr_tbl_sub_departemen.nama as sub_dep', 
        'mtr_tbl_level.level as level', 'mtr_tbl_jabatan.jabatan as jabatan', 'mtr_tbl_level.id as id_level', 'mtr_tbl_working_group.wg_name as wg_name', 
        'mtr_tbl_working_group.id as wg_id', 'mtr_tbl_working_group.is_reguler as is_reguler',
        'mtr_tbl_status_karyawan.status_karyawan as status_karyawan', 'mtr_tbl_karyawan.gaji_pokok as gaji_pokok')
            ->leftJoin('mtr_tbl_perusahaan', 'mtr_tbl_perusahaan.id', '=', 'mtr_tbl_karyawan.kode_perusahaan')
            ->leftJoin('mtr_tbl_divisi', 'mtr_tbl_divisi.id', '=', 'mtr_tbl_karyawan.kode_divisi')
            ->leftJoin('mtr_tbl_departemen', 'mtr_tbl_departemen.id', '=', 'mtr_tbl_karyawan.kode_departemen')
            ->leftJoin('mtr_tbl_sub_departemen', 'mtr_tbl_sub_departemen.id', '=', 'mtr_tbl_karyawan.kode_sub_departemen')
            ->leftJoin('mtr_tbl_level', 'mtr_tbl_level.id', '=', 'mtr_tbl_karyawan.kode_level')
            ->leftJoin('mtr_tbl_jabatan', 'mtr_tbl_jabatan.id', '=', 'mtr_tbl_karyawan.kode_jabatan')
            ->leftJoin('mtr_tbl_working_group', 'mtr_tbl_working_group.id', '=', 'mtr_tbl_karyawan.wg_id')
            ->leftJoin('mtr_tbl_status_karyawan', 'mtr_tbl_status_karyawan.id', '=', 'mtr_tbl_karyawan.kode_status')
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
        $trx = ToDoList::where($where)->first();
        if (!empty($trx)) {
            $trx->delete();
        }
    }

    public function getFreelanceProperty()
    {
        $data = MtrUtility::select('informasi')->whereIn('id', [21,22])->get();
        return json_encode($data);
    }


    // public function getNikProperty($nik)
    // {
    //     // $where = ['nik' => $nik, 'is_aktif' => 1];
    //     $where = ['nik' => $nik];
    //     $data = Karyawan::select('mtr_tbl_karyawan.nik', 'mtr_tbl_karyawan.nama', 'mtr_tbl_karyawan.email', 'mtr_tbl_karyawan.telepon1', 'mtr_tbl_perusahaan.nama as perusahaan', 'mtr_tbl_divisi.nama as divisi', 'mtr_tbl_departemen.nama as dep', 'mtr_tbl_sub_departemen.nama as sub_dep', 'mtr_tbl_level.level as level', 'mtr_tbl_jabatan.jabatan as jabatan', 'mtr_tbl_level.id as id_level', 'mtr_tbl_working_group.wg_name as wg_name', 'mtr_tbl_status_karyawan.status_karyawan as status_karyawan', 'mtr_tbl_karyawan.gaji_pokok as gaji_pokok')
    //         ->leftJoin('mtr_tbl_perusahaan', 'mtr_tbl_perusahaan.id', '=', 'mtr_tbl_karyawan.kode_perusahaan')
    //         ->leftJoin('mtr_tbl_divisi', 'mtr_tbl_divisi.id', '=', 'mtr_tbl_karyawan.kode_divisi')
    //         ->leftJoin('mtr_tbl_departemen', 'mtr_tbl_departemen.id', '=', 'mtr_tbl_karyawan.kode_departemen')
    //         ->leftJoin('mtr_tbl_sub_departemen', 'mtr_tbl_sub_departemen.id', '=', 'mtr_tbl_karyawan.kode_sub_departemen')
    //         ->leftJoin('mtr_tbl_level', 'mtr_tbl_level.id', '=', 'mtr_tbl_karyawan.kode_level')
    //         ->leftJoin('mtr_tbl_jabatan', 'mtr_tbl_jabatan.id', '=', 'mtr_tbl_karyawan.kode_jabatan')
    //         ->leftJoin('mtr_tbl_working_group', 'mtr_tbl_working_group.id', '=', 'mtr_tbl_karyawan.wg_id')
    //         ->leftJoin('mtr_tbl_status_karyawan', 'mtr_tbl_status_karyawan.id', '=', 'mtr_tbl_karyawan.kode_status')
    //         ->where($where)->first();
    //     return json_encode($data);
    // }

    // public function getNikProperty($nik)
    // {
    //     $where = ['nik' => $nik, 'is_aktif' => 1];
    //     $data = Karyawan::select('mtr_tbl_karyawan.nik', 'mtr_tbl_karyawan.nama', 'mtr_tbl_karyawan.email', 'mtr_tbl_karyawan.telepon1', 'mtr_tbl_perusahaan.nama as perusahaan', 'mtr_tbl_divisi.nama as divisi', 'mtr_tbl_departemen.nama as dep', 'mtr_tbl_sub_departemen.nama as sub_dep', 'mtr_tbl_level.level as level', 'mtr_tbl_jabatan.jabatan as jabatan', 'mtr_tbl_level.id as id_level')
    //         ->leftJoin('mtr_tbl_perusahaan', 'mtr_tbl_perusahaan.id', '=', 'mtr_tbl_karyawan.kode_perusahaan')
    //         ->leftJoin('mtr_tbl_divisi', 'mtr_tbl_divisi.id', '=', 'mtr_tbl_karyawan.kode_divisi')
    //         ->leftJoin('mtr_tbl_departemen', 'mtr_tbl_departemen.id', '=', 'mtr_tbl_karyawan.kode_departemen')
    //         ->leftJoin('mtr_tbl_sub_departemen', 'mtr_tbl_sub_departemen.id', '=', 'mtr_tbl_karyawan.kode_sub_departemen')
    //         ->leftJoin('mtr_tbl_level', 'mtr_tbl_level.id', '=', 'mtr_tbl_karyawan.kode_level')
    //         ->leftJoin('mtr_tbl_jabatan', 'mtr_tbl_jabatan.id', '=', 'mtr_tbl_karyawan.kode_jabatan')
    //         ->where($where)->first();
    //     return json_encode($data);
    // }

    public function sendEmail($mailData, $ke_cc)
    {
        $ccEmails = explode(',', MtrUtility::find(11)->informasi);
        array_push($ccEmails, $ke_cc);
        Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
        $tgl = date('Y-m-d');
        $jam = date('H:i:s');
        $waktu = date('Y-m-d H:i:s');
		$tahun = Carbon::parse(now())->format('Y');
		$bulan = Carbon::parse(now())->format('m');
        $id = $request->id;
        $nik = $request->nik;
        $src = $request->src;
        $wd_id = $request->wd_id;
        switch ($id) {
            case 'maker':
                $where = [ $src => $nik ];
				// where('ot_end_clockout','>=',$waktu)
                // whereMonth('ot_start_at', $bulan)->whereYear('ot_start_at', $tahun)
                $data = Overtime::select('trx_tbl_overtime.*','mtr_tbl_overtime_type.ot_type','mtr_tbl_karyawan.nama')
                ->leftJoin('mtr_tbl_overtime_type', 'mtr_tbl_overtime_type.id', '=', 'trx_tbl_overtime.ot_type_code')
                ->leftJoin('mtr_tbl_karyawan', 'mtr_tbl_karyawan.nik', '=', 'trx_tbl_overtime.ot_maker_by')
                ->where($where)->whereBetween('ot_start_at', [date('Y-m-d', strtotime('-14 days')), date('Y-m-d', strtotime('14 days'))])->whereIn('ot_approver_status', ['Waiting', 'Approve', 'Reject'])->orderBy( 'ot_start_at', 'desc' )->get();
                if(!empty($data)){
                    return response()->json( [ 'success' => true, 'message' => 'Data is viewed successfully', 'data' => $data ], 200 );
                }else{
                    return response()->json( [ 'success' => false, 'message' => 'Data is empty', 'data' => $data ], 200 );
                }
              break;
            case 'absensi':
                $data = $this->getProfileOT($nik, $wd_id);
                return response()->json( [ 'success' => true, 'message' => 'Data is viewed successfully', 'data' => $data ], 200 );
              break;
            case 'absensi_notif':
                  $data = $this->getProfileOTNotif($nik, $wd_id);
                  return response()->json( [ 'success' => true, 'message' => 'Data is viewed successfully', 'data' => $data ], 200 );
                break;
            default:
              return response()->json( [ 'success' => false, 'message' => 'No Data is viewed', 'data' => [] ], 200 );
        }
        return $data;
    }

    public function getProfileOT($nik, $wd_id) {
        $input = date('Y-m-d H:i:s');
        $tgl = date('Y-m-d');
        $jam = date('H:i:s');
        // jika sudah n_clockin maka 1 jika sudah n_clockout maka 1 jika belum maka 0
        $data = array();
        $nGetClock = $this->getNClockOT($nik, $wd_id);
        $where = [ 'nik' => $nik ];
        $dataOT = Overtime::select('ot_start_clockin','ot_end_clockin','ot_start_clockout','ot_end_clockout','ot_start_at','ot_end_at')->find( $wd_id );
        //
        $fromClockin = $dataOT['ot_start_clockin'];
        $toClockin = $dataOT['ot_end_clockin'];
        $fromClockout = $dataOT['ot_start_clockout'];
        $toClockout = $dataOT['ot_end_clockout'];
        // cek window clockin
        $isWindowClockin = false;
        if($this->checkIfExistOT($fromClockin,$toClockin,$input)){
            $isWindowClockin = true;
        }
        // cek window clockout
        $isWindowClockout = false;
        if($this->checkIfExistOT($fromClockout,$toClockout,$input)){
            $isWindowClockout = true;
        }
        $isClockinout =array("isClockin"=>false, "isClockout"=>false);
        // apakah harus clockin di hari kerja dan between clock window dan belum clock in
        if($isWindowClockin && $nGetClock["n_clockin"]==0){
            $isClockinout =array("isClockin"=>true, "isClockout"=>false);
        }
        // apakah harus clockout di hari kerja dan between clock window dan belum clock out
        else if($isWindowClockout && $nGetClock["n_clockin"]>0){
            if($nGetClock["n_clockout"]>0){
                $isClockinout =array("isClockin"=>false, "isClockout"=>false);
            }else{
                $isClockinout =array("isClockin"=>false, "isClockout"=>true);
            }
        }
        // karyawan reguler bisa clock in OT setelah clock out absensi reguler
        if(Karyawan::where([ 'nik'=>$nik, 'wg_id'=>1 ])->exists()){
            $att_date = Carbon::createFromFormat('Y-m-d H:i:s', $dataOT['ot_start_at'])->format('Y-m-d');
            if(!($this->isWeekend($att_date) || $this->isHoliday($att_date))){
                // if(!Attendance::where([ 'att_nik'=>$nik, 'att_type'=>'Clock-out', 'att_data_src'=>'Regular', 'att_date'=>$att_date ])->exists()){
                //     $isClockinout =array("isClockin"=>false, "isClockout"=>false);
                // }
                // refer to cm 511
                // start jika OT after office hour
                $clockout = DB::table('mtr_tbl_working_group')->where('id', 1)->value('clockout');
                if(Carbon::parse($dataOT['ot_start_at'])->gt(Carbon::parse($att_date.' '.$clockout))){
                    if(!Attendance::where([ 'att_nik'=>$nik, 'att_type'=>'Clock-out', 'att_data_src'=>'Regular', 'att_date'=>$att_date ])->exists()){
                        $isClockinout =array("isClockin"=>false, "isClockout"=>false);
                    }
                }
                // end
            }
        }
        // 
        array_push($data, $nGetClock);
        array_push($data, array("tgl"=>$tgl, "jam"=>$jam));
        array_push($data, $isClockinout);
        array_push($data, $this->getDataClockOT($nik, $tgl, $wd_id));
        return $data;
    }

    public function getProfileOTNotif($nik, $wd_id) {
        $input = date('Y-m-d H:i:s');
        $tgl = date('Y-m-d');
        $jam = date('H:i:s');
        // jika sudah n_clockin maka 1 jika sudah n_clockout maka 1 jika belum maka 0
        $data = array();
        $nGetClock = $this->getNClockOT($nik, $wd_id);
        $where = [ 'nik' => $nik ];
        $dataOT = Overtime::select('ot_start_clockin','ot_end_clockin','ot_start_clockout','ot_end_clockout')->find( $wd_id );
        //
        $fromClockin = $dataOT['ot_start_clockin'];
        $toClockin = $dataOT['ot_end_clockin'];
        $fromClockout = $dataOT['ot_end_clockin'];
        $toClockout = $dataOT['ot_end_clockout'];
        // cek window clockin
        $isWindowClockin = false;
        if($this->checkIfExistOT($fromClockin,$toClockin,$input)){
            $isWindowClockin = true;
        }
        // cek window clockout
        $isWindowClockout = false;
        if($this->checkIfExistOT($fromClockout,$toClockout,$input)){
            $isWindowClockout = true;
        }
        $isClockinout =array("isClockin"=>false, "isClockout"=>false);
        // apakah harus clockin di hari kerja dan between clock window dan belum clock in
        if($isWindowClockin && $nGetClock["n_clockin"]==0){
            $isClockinout =array("isClockin"=>true, "isClockout"=>false);
        }
        // apakah harus clockout di hari kerja dan between clock window dan belum clock out
        else if($isWindowClockout && $nGetClock["n_clockin"]>0){
            if($nGetClock["n_clockout"]>0){
                $isClockinout =array("isClockin"=>false, "isClockout"=>false);
            }else{
                $isClockinout =array("isClockin"=>false, "isClockout"=>true);
            }
        }
        array_push($data, $nGetClock);
        array_push($data, array("tgl"=>$tgl, "jam"=>$jam));
        array_push($data, $isClockinout);
        array_push($data, $this->getDataClockOT($nik, $tgl, $wd_id));
        return $data;
    }

    // fungsi untuk mendapatkan data clockin clockout OT
    public function getDataClockOT($nik, $tgl, $wd_id) {
		//'att_date' => $tgl, 
        $whereClockin = [ 'att_nik' => $nik, 'att_type' => 'Clock-in', 'att_data_src' => 'Overtime', 'att_wd_ot_id' => $wd_id ];
        $dataClockin = Attendance::where($whereClockin)->value('att_timestamp');
        $whereClockout = [ 'att_nik' => $nik, 'att_type' => 'Clock-out', 'att_data_src' => 'Overtime', 'att_wd_ot_id' => $wd_id ];
        $dataClockout = Attendance::where($whereClockout)->value('att_timestamp');
        return array("dtClockin"=>$dataClockin, "dtClockout"=>$dataClockout);
    }

    // fungsi untuk mendapatkan berapa jumlah clockin clockout OT
    public function getNClockOT($nik, $wd_id) {
        //$tgl = date('Y-m-d');
		//'att_date' => $tgl, 
        $whereClockin = [ 'att_nik' => $nik, 'att_type' => 'Clock-in', 'att_data_src' => 'Overtime', 'att_wd_ot_id' => $wd_id ];
        $dataClockin = Attendance::where($whereClockin)->count();
        $whereClockout = [ 'att_nik' => $nik, 'att_type' => 'Clock-out', 'att_data_src' => 'Overtime', 'att_wd_ot_id' => $wd_id ];
        $dataClockout = Attendance::where($whereClockout)->count();
        return array("n_clockin"=>$dataClockin, "n_clockout"=>$dataClockout);
    }

    // fungsi untuk cek apakah masuk window clockin clockout OT
    public function checkIfExistOT($from, $to, $input) {
        $fromDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $from);
        $toDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $to);
        $inputDateTime= Carbon::createFromFormat('Y-m-d H:i:s', $input);
        return ($fromDateTime <= $inputDateTime && $inputDateTime < $toDateTime);
    }

    // fungsi untuk cek apakah masuk window
    public function checkIfExist($from, $input) {
        $fromDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $from);
        $inputDateTime= Carbon::createFromFormat('Y-m-d H:i:s', $input);
        $cek = $fromDateTime > $inputDateTime;
        return [ 'status' => $cek, 'message' => 'Pengajuan OT lebih besar dari saat ini' ];
    }

    // fungsi untuk cek apakah masuk window
    public function checkStartEnd($from, $to) {
        $fromDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $from);
        $toDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $to);
        $cek = $fromDateTime < $toDateTime;
        // return ($fromDateTime <= $inputDateTime && $inputDateTime <= $toDateTime);
        return [ 'status' => $cek, 'message' => 'Datetime akhir OT lebih besar dari Datetime mulai OT' ];
    }

    // fungsi ambil OT yang ada sesuai NIK dan waktu pengajuan
    public function isOTExisting($nik, $from, $to) {
        $_from = $from;
        $_to = $to;
        $cek = true;
        $where = [ 'ot_maker_by' => $nik ];
        // $data = Overtime::where($where)->whereBetween('ot_start_at', [$from, $to])->whereIn('ot_approver_status', ['Waiting', 'Approve'])->count();
        // $data += Overtime::where($where)->whereBetween('ot_end_at', [$from, $to])->whereIn('ot_approver_status', ['Waiting', 'Approve'])->count();
        // $data = Overtime::where($where)->where('ot_start_at','<',$from)->where('ot_end_at','>=',$from)->whereIn('ot_approver_status', ['Waiting', 'Approve'])->count();
        // $data += Overtime::where($where)->where('ot_start_at','<',$to)->where('ot_end_at','>=',$to)->whereIn('ot_approver_status', ['Waiting', 'Approve'])->count();
        $data = 0;
        while (strtotime($from) <= strtotime($to)){
            $data += Overtime::where($where)->where('ot_start_at','<',$from)->where('ot_end_at','>=',$from)->whereIn('ot_approver_status', ['Waiting', 'Approve'])->count();
            $from = date ('Y-m-d H:i:s', strtotime('+1 minutes', strtotime($from)));
        }
        if($data>0){
            $cek = false;
        }
        // return [ 'status' => $cek, 'message' => 'Pengajuan OT tidak beririsan dengan OT lainnya' ];
        return [ 'status' => $cek, 'message' => $nik.'#'.$_from.' sd '.$_to.'. Pengajuan OT tidak beririsan dengan OT lainnya' ];
    }

    // fungsi get layer 1
    public function getLayer1($nik) {
        $where = [ 'nik' => $nik ];
        $data = Karyawan::select('layer1')->where($where)->first();
        return $data;
    }

    // fungsi untuk mendapatkan apakah OT ada di waktu kerja
    public function cekDatetimeKerja($nik, $from, $to){
        $tglFrom = Carbon::parse($from)->format('Y-m-d');
        $tglTo = Carbon::parse($to)->format('Y-m-d');
        $where = [ 'nik' => $nik ];
        $data = Karyawan::select('mtr_tbl_working_group.start_clockin','mtr_tbl_working_group.clockin','mtr_tbl_working_group.clockout','mtr_tbl_working_group.is_reguler','mtr_tbl_karyawan.wg_id')->leftJoin('mtr_tbl_working_group', 'mtr_tbl_working_group.id', '=', 'mtr_tbl_karyawan.wg_id')->where($where)->first();
        // hanya untuk reguler, 1 jam setelah window-out
        $clockinFrom = Carbon::parse($tglFrom.' '.$data['start_clockin']);
        $clockoutFrom = Carbon::parse($tglFrom.' '.$data['clockout'])->addHour();
        $clockinTo = Carbon::parse($tglTo.' '.$data['start_clockin']);
        $clockoutTo = Carbon::parse($tglTo.' '.$data['clockout'])->addHour();
        // return response()->json( [ 'success' => true, 
        //     ['clockinFrom' => Carbon::parse($clockinFrom)->format('Y-m-d H:i:s'), 
        //     'clockoutFrom' => Carbon::parse($clockoutFrom)->format('Y-m-d H:i:s'), 
        //     'clockinTo' => Carbon::parse($clockinTo)->format('Y-m-d H:i:s'), 
        //     'clockoutTo' => Carbon::parse($clockoutTo)->format('Y-m-d H:i:s')] 
        // ], 200 );
        // start
        // khusus jam kerja karyawan reguler Bulan Ramadhan 08-16
        // tetapi jam overtime tetap dimulai dari jam 19 jadi ditambah 1+2 jam
        // if($data['is_reguler']==1){
        //     $clockoutFrom = $clockoutFrom->addHour(2);
        //     $clockoutTo = $clockoutTo->addHour(2);
        // }
        // end
        // from
        $isClockFrom = false;
        // jika weekend
        if($this->isWeekend($tglFrom)){
            $isClockFrom = true;
        }
        // jika bukan weekend maka weekday
        else{
            // jika libur nasional/cuti bersama
            if($this->isHoliday($tglFrom)){
                $isClockFrom = true;
            }
            // jika bukan libur nasional/cuti bersama
            else{
                if(!Carbon::parse($from)->between($clockinFrom, $clockoutFrom, false)){
                    $isClockFrom = true;
                }
            }
        }
        // to
        $isClockTo = false;
        // jika weekend
        if($this->isWeekend($tglTo)){
            $isClockTo = true;
        }
        // jika bukan weekend maka weekday
        else{
            // jika libur nasional/cuti bersama
            if($this->isHoliday($tglTo)){
                $isClockTo = true;
            }
            // jika bukan libur nasional/cuti bersama
            else{
                if(!Carbon::parse($to)->between($clockinTo, $clockoutTo, false)){
                    $isClockTo = true;
                }
            }
        }
        // untuk non reguler
        if($data['is_reguler']==0){
            // jika di jadwal kerja
            if($this->isWorkingDay($nik, $from, $to)){
                // khusus grup security bisa OT di hari libur nasional/cuti bersama walaupun di jadwal kerjanya
                // ada perubahan tidak hanya untuk grup security melainkan untuk semua non reguler
				// if($data['wg_id']==2){
					if($this->isHoliday($tglFrom)){
						return [ 'status' => true, 'message' => 'Non Reguler, Datetime OT bukan waktu kerja' ];
					}else{
						return [ 'status' => false, 'message' => 'Non Reguler, Datetime OT bukan waktu kerja' ];
					}
				// }else{
				// 	return [ 'status' => false, 'message' => 'Non Reguler, Datetime OT bukan waktu kerja' ];
				// }
            }
            // jika bukan di jadwal kerja
            else{
                return [ 'status' => true, 'message' => 'Non Reguler, Datetime OT bukan waktu kerja' ];
            }
        }
        // untuk reguler
        else{
            if($isClockFrom && $isClockTo){
                return [ 'status' => true, 'message' => 'Reguler, Datetime OT bukan waktu kerja' ];
            }else{
                return [ 'status' => false, 'message' => 'Reguler, Datetime OT bukan waktu kerja' ];
            }
            // if($isClock){
            //     return [ 'status' => $cek, 'message' => 'Reguler, Datetime OT diajukan setelah 1 jam Datetime akhir kerja atau sebelum Datetime mulai kerja' ];
            // }else{
            //     return [ 'status' => true, 'message' => 'Reguler, Pengajuan OT di akhir pekan atau hari libur' ];
            // }
        }
        // start tidak digunakan
        // $tgl = Carbon::createFromFormat('Y-m-d H:i:s', $from)->format('Y-m-d');
        // $where = [ 'nik' => $nik ];
        // $data = Karyawan::select('mtr_tbl_working_group.clockin','mtr_tbl_working_group.clockout','mtr_tbl_working_group.is_reguler','mtr_tbl_karyawan.wg_id')->leftJoin('mtr_tbl_working_group', 'mtr_tbl_working_group.id', '=', 'mtr_tbl_karyawan.wg_id')->where($where)->first();
        // $waktuClockin = $tgl.' '.$data['clockin'];
        // $waktuClockinDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $waktuClockin);
        // $waktuClockout = $tgl.' '.$data['clockout'];
        // $waktuClockoutDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $waktuClockout);
        // // hanya untuk reguler, 1 jam setelah window-out
        // $startWaktu = $waktuClockoutDateTime->addHour();
        // // start
        // // khusus jam kerja karyawan reguler Bulan Ramadhan 08-16
        // // tetapi jam overtime tetap dimulai dari jam 19 jadi ditambah 1+2 jam
        // // if($data['is_reguler']==1){
        // //     $startWaktu = $waktuClockoutDateTime->addHour(2);
        // // }
        // // end
        // $fromDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $from);
        // $toDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $to);
        // $cek = $fromDateTime >= $startWaktu || $toDateTime < $waktuClockinDateTime;
        // //
        // $isLibur1 = $this->isWeekend(date($tgl));
        // $isLibur2 = $this->isHoliday(date($tgl));
        // //
        // $isClock = false;
        // // jika weekend
        // if($isLibur1){
        //     $isClock = false;
        // }
        // // jika bukan weekend maka weekday
        // else{
        //     // jika libur nasional/cuti bersama
        //     if($isLibur2){
        //         $isClock = false;
        //     }
        //     // jika bukan libur nasional/cuti bersama
        //     else{
        //         $isClock = true;
        //     }
        // }
        // // untuk non reguler
        // if($data['is_reguler']==0){
        //     // jika di jadwal kerja
        //     if($this->isWorkingDay($nik, $from, $to)){
        //         // khusus grup security bisa OT di hari libur nasional/cuti bersama walaupun di jadwal kerjanya
        //         // ada perubahan tidak hanya untuk grup security melainkan untuk semua non reguler
		// 		// if($data['wg_id']==2){
		// 			if($isLibur2){
		// 				return [ 'status' => true, 'message' => 'Non Reguler, Datetime OT bukan waktu kerja' ];
		// 			}else{
		// 				return [ 'status' => false, 'message' => 'Non Reguler, Datetime OT bukan waktu kerja' ];
		// 			}
		// 		// }else{
		// 		// 	return [ 'status' => false, 'message' => 'Non Reguler, Datetime OT bukan waktu kerja' ];
		// 		// }
        //     }
        //     // jika bukan di jadwal kerja
        //     else{
        //         return [ 'status' => true, 'message' => 'Non Reguler, Datetime OT bukan waktu kerja' ];
        //     }
        // }
        // // untuk reguler
        // else{
        //     if($isClock){
        //         return [ 'status' => $cek, 'message' => 'Reguler, Datetime OT diajukan setelah 1 jam Datetime akhir kerja atau sebelum Datetime mulai kerja' ];
        //     }else{
        //         return [ 'status' => true, 'message' => 'Reguler, Pengajuan OT di akhir pekan atau hari libur' ];
        //     }
        // }
        // end
    }

    // fungsi ambil Working Day yang ada sesuai NIK dan waktu pengajuan
    public function isWorkingDay($nik, $from, $to) {
        $cek = false;
        $where = [ 'nik' => $nik ];
        // // $data = WorkingDay::where($where)->whereBetween('clockin', [$from, $to])->count();
        // // $data += WorkingDay::where($where)->whereBetween('clockout', [$from, $to])->count();
        // $data = WorkingDay::where($where)->where('clockin','<',$from)->where('clockout','>',$from)->count();
        // $data += WorkingDay::where($where)->where('clockin','<',$to)->where('clockout','>',$to)->count();
        $data = 0;
        while (strtotime($from) <= strtotime($to)){
            $data += WorkingDay::where($where)->where('clockin','<',$from)->where('clockout','>=',$from)->count();
            $from = date ('Y-m-d H:i:s', strtotime('+1 minutes', strtotime($from)));
        }
        if($data>0){
            $cek = true;
        }
        return $cek;
    }

    // fungsi get OT type code
    public function getOTCode($str) {
        $where = [ 'ot_type' => $str ];
        $data = OvertimeType::select('id')->where($where)->first();
        return $data;
    }

    // cek apakah hari ini sabtu minggu
    public function isWeekend($date) {
        $weekDay = date('w', strtotime($date));
        return ($weekDay == 0 || $weekDay == 6);
    }

    // cek apakah hari ini adalah hari libur nasional
    public function isHoliday($date) {
        $where = ['tanggal' => $date];
        $data = Holiday::where($where)->first();
        if(!empty($data)){
            return true;
        }else{
            return false;
        }
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
        $waktu = date('Y-m-d H:i:s');
        $ot = Overtime::find( $id );
        $ot->ot_approver_at = $waktu;
        $ot->ot_approver_status = $request->ot_approver_status;
        $ot->update();
        $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$ot->ot_approver_by, 'pic'=>'atasan', 'filename'=>'emails.lembur0']);
        // 
        $_log = new OvertimeLog;
        $_log->id_ot = $id;
        $_log->ot_approver_status = $request->ot_approver_status;
        $_log->save();
        // 
        if($request->ot_approver_status == 'Approve'){
            // parameter yang dipassing ke sendemail
            // start
            $pengaju = json_decode($this->getNikProperty($ot->ot_maker_by), true);
            $atasan = json_decode($this->getNikProperty($ot->ot_approver_by), true);
            $overtime = ['ot_start_at'=>$ot->ot_start_at, 'ot_end_at'=>$ot->ot_end_at, 'ot_title'=>$ot->ot_title, 'ot_desc'=>$ot->ot_desc];
            // $mailData['title'] = 'Notifikasi Lembur - '.$pengaju['nama'].' => '.$ot->ot_start_at.' sd '.$ot->ot_end_at;
            $mailData['title'] = 'Notifikasi Lembur - '.$pengaju['nama'];
            $mailData['data'] = ['nama'=>$pengaju['nama'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'atasan'=>$atasan['nama'], 'overtime'=>$overtime];
            $mailData['filename'] = 'emails.lembur1';
            $mailData['to'] = $pengaju['email'];
            $this->sendEmail($mailData, $atasan['email']);
            // end
        }
        return response()->json( [ 'success' => true, 'message' => 'Data is updated successfully.', 'data' => $ot ], 200 );
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
