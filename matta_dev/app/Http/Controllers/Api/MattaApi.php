<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Karyawan;
use App\Models\Feeds;
use App\Models\Banner;
use App\Models\Holiday;
use App\Models\Attendance;
use App\Models\WorkingDay;
use App\Models\ToDoList;
use App\Models\Overtime;
use App\Models\TrxCuti;
use App\Models\TrxSakit;
// 
use DB;
use Carbon\Carbon;

class MattaApi extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 
        //
        // $nik = '01030908046';
        // $profile = $this->getProfileGroup($nik);
        // return $profile;
        // $cek = $this->cekBookLatein($nik,1);
        // $today = $cek[]
        // return $cek;
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
        $tgl = date('Y-m-d');
        $jam = date('H:i:s');
        $waktu = date('Y-m-d H:i:s');
        // $jam = date('H:i:s', strtotime('21:30:00'));
        // $waktu = date('Y-m-d H:i:s', strtotime('2025-07-25 21:30:00'));
        $data = New Attendance;
        $data->att_date = $tgl;
        $data->att_timestamp = $waktu;
        $data->att_nik = $request->att_nik;
        $data->att_type = $request->att_type;
        $data->att_long = $request->att_long;
        $data->att_lat = $request->att_lat;
        $data->att_address = $request->att_address;
        $data->att_data_src = $request->att_data_src;
        $data->att_wd_ot_id = $request->att_wd_ot_id;
        $data->att_status = $request->att_status;
        if($data->att_type == 'Clock-out'){
            $data->att_status = 'Book-out';
        }
        $data->att_property = $this->getNikProperty($request->att_nik);
        $data->wg_id = Karyawan::where(['nik' => $request->att_nik, 'is_aktif' => 1])->first()->wg_id;
        $data->save();
        // 
		if($request->att_data_src == 'Overtime'){
			$ot = $this->updateOvertime($request);
		}
        // refer to cm 523
        // refer to "C:\alita\matta\data_master\kemungkinan cico v1.xlsx"
        $_data = Attendance::where(['id'=>$data->id, 'att_data_src'=>'Regular', 'att_type'=>'Clock-in', 'att_wd_ot_id'=>0, 'wg_id'=>1]);
        if($_data->exists()){
            if(Carbon::now()->greaterThan(Carbon::today()->setTime(9, 1, 0))){
                $_data->update(['att_status' => 'Late-in']);
            }else{
                $_data->update(['att_status' => 'Book-in']);
            }
        }
        // 
        return response()->json( [ 'success' => true, 'message' => 'Data is inserted successfully.', 'data' => $data ], 200 );
    }

    public function updateOvertime(Request $request)
    {
        if($request->att_type == 'Clock-in'){
            DB::update(DB::raw("UPDATE trx_tbl_overtime AS a INNER JOIN trx_tbl_attendance AS b ON a.id=b.att_wd_ot_id SET a.ot_start_at_real=b.att_timestamp WHERE a.ot_maker_by=b.att_nik AND b.att_data_src='Overtime' AND b.att_type='Clock-in' AND a.id=".$request->att_wd_ot_id));
            DB::update(DB::raw("UPDATE trx_tbl_overtime SET ot_start_at_rev=CASE WHEN ot_start_at<ot_start_at_real THEN ot_start_at_real ELSE ot_start_at END WHERE ot_start_at_real IS NOT NULL AND id=".$request->att_wd_ot_id));
        }
        else if($request->att_type == 'Clock-out'){
            DB::update(DB::raw("UPDATE trx_tbl_overtime AS a INNER JOIN trx_tbl_attendance AS b ON a.id=b.att_wd_ot_id SET a.ot_end_at_real=b.att_timestamp WHERE a.ot_maker_by=b.att_nik AND b.att_data_src='Overtime' AND b.att_type='Clock-out' AND a.id=".$request->att_wd_ot_id));
            DB::update(DB::raw("UPDATE trx_tbl_overtime SET ot_end_at_rev=CASE WHEN ot_end_at>ot_end_at_real THEN ot_end_at_real ELSE ot_end_at END WHERE ot_end_at_real IS NOT NULL AND id=".$request->att_wd_ot_id));
            DB::update(DB::raw("UPDATE trx_tbl_overtime SET ot_length_real=SEC_TO_TIME(TIMESTAMPDIFF(SECOND,ot_start_at_real,ot_end_at_real)) WHERE ot_start_at_real IS NOT NULL AND ot_end_at_real IS NOT NULL AND id=".$request->att_wd_ot_id));
            DB::update(DB::raw("UPDATE trx_tbl_overtime SET ot_length_rev=SEC_TO_TIME(TIMESTAMPDIFF(SECOND,ot_start_at_rev,ot_end_at_rev)) WHERE ot_start_at_rev IS NOT NULL AND ot_end_at_rev IS NOT NULL AND id=".$request->att_wd_ot_id));
            DB::update(DB::raw("UPDATE trx_tbl_overtime SET ot_length_adj=TIME_TO_SEC(ot_length_rev) WHERE id=".$request->att_wd_ot_id));
            // 
            $_ot = Overtime::find( $request->att_wd_ot_id );
            $arr = json_decode($_ot->ot_maker_property, true);
            $ot_salary = $this->getOTSalary($_ot->ot_start_at, $_ot->ot_maker_by, $arr['is_reguler'], $arr['wg_id'], $arr['gaji_pokok'], $_ot->ot_length_adj, $arr['status_karyawan'], $_ot->ot_freelance_property);
            $_ot->ot_length_rp = $ot_salary;
            $_ot->ot_length_rp_adj = $ot_salary;
            $_ot->update();
        }
    }
    
    public function getOTSalary($waktu,$nik,$is_reguler,$wg_id,$gaji_pokok,$ot_length,$status_karyawan,$ot_freelance_property)
    {
        $tanggal = Carbon::parse($waktu)->format('Y-m-d');
        $arr = json_decode($ot_freelance_property, true);
        // reguler
        if($is_reguler == 1){
            $isHoliday = $this->isHolidayOT($tanggal)['isHoliday'];
            $is_cuti = $this->isHolidayOT($tanggal)['is_cuti'];
            if($isHoliday){
                // cuti bersama dinyatakan sebagai OT Weekday
                if($is_cuti == 1){
                    // CM 390, Menambahkan Perhitungan OT untuk anak Magang (Perhitungan sama dengan perhitungan OT Freelance)
                    // Weekday
                    if($status_karyawan == 'Freelance' || $status_karyawan == 'Magang'){
                        return $arr[0]['informasi']*$ot_length/3600;
                    }else{
                        return $this->getOTSalaryWeekday($gaji_pokok,$ot_length);
                    }
                }
                // cuti bersama dinyatakan sebagai OT Weekend
                else if($is_cuti == 0){
                    // Weekend
                    if($status_karyawan == 'Freelance' || $status_karyawan == 'Magang'){
                        return $arr[1]['informasi']*$ot_length/3600;
                    }else{
                        return $this->getOTSalaryWeekend($gaji_pokok,$ot_length);
                    }
                }
            }else{
                $isWeekend = $this->isWeekendOT($tanggal)['isWeekend'];
                if($isWeekend){
                    // Weekend
                    if($status_karyawan == 'Freelance' || $status_karyawan == 'Magang'){
                        return $arr[1]['informasi']*$ot_length/3600;
                    }else{
                        return $this->getOTSalaryWeekend($gaji_pokok,$ot_length);
                    }
                }else{
                    // Weekday
                    if($status_karyawan == 'Freelance' || $status_karyawan == 'Magang'){
                        return $arr[0]['informasi']*$ot_length/3600;
                    }else{
                        return $this->getOTSalaryWeekday($gaji_pokok,$ot_length);
                    }
                }
            }
        }
        // non reguler
        else{
            $isWorkingDay = $this->isWorkingDayOT($nik, $tanggal, $wg_id)['isWorkingDay'];
            if($isWorkingDay){
                // Weekday
                return $this->getOTSalaryWeekday($gaji_pokok,$ot_length);
            }else{
                // Weekend
                return $this->getOTSalaryWeekend($gaji_pokok,$ot_length);
            }
        }
    }

    public static function getOTSalaryWeekday($gaji_pokok,$ot_length)
    {
        $koef = 173;
        $hours = floor($ot_length / 3600);
        $seconds = $ot_length - ($hours * 3600);
        // kurang atau sama dengan 1 jam
        if($ot_length <= 3600){
            // perhitungan 1 jam pertama 1 x 1/173 x 1.5 x gaji_pokok
            $amount = $ot_length / 3600 / $koef * 1.5 * $gaji_pokok;
            return round($amount, 2);
        }
        // lebih dari 1 jam
        else{
            // perhitungan 1 jam pertama 1 x 1/173 x 1.5 x gaji_pokok
            $amount = 3600 / 3600 / $koef * 1.5 * $gaji_pokok;
            // perhitungan sisa jam berikutnya sisa_jam x 1/173 x 2 x gaji_pokok
            $amount += ($ot_length - 3600) / 3600 / $koef * 2 * $gaji_pokok;
            return round($amount, 2);
        }
    }

    public static function getOTSalaryWeekend($gaji_pokok,$ot_length)
    {
        $koef = 173;
        $hours = floor($ot_length / 3600);
        $seconds = $ot_length - ($hours * 3600);
        // kurang atau sama dengan 8 jam
        if($ot_length <= 3600 * 8){
            // perhitungan 8 jam pertama 8 x 1/173 x 2 x gaji_pokok
            $amount = $ot_length / 3600 / $koef * 2 * $gaji_pokok;
            return round($amount, 2);
        }
        // lebih dari 8 jam dan kurang sama dengan 9 jam
        else if($ot_length <= 3600 * 9){
            // perhitungan 8 jam pertama 8 x 1/173 x 2 x gaji_pokok
            $amount = 3600 * 8 / 3600 / $koef * 2 * $gaji_pokok;
            // perhitungan 1 jam berikutnya 1 x 1/173 x 3 x gaji_pokok
            $amount += ($ot_length - (3600 * 8)) / 3600 / $koef * 3 * $gaji_pokok;
            return round($amount, 2);
        }
        // lebih dari 9 jam
        else{
            // perhitungan 8 jam pertama 8 x 1/173 x 2 x gaji_pokok
            $amount = 3600 * 8 / 3600 / $koef * 2 * $gaji_pokok;
            // perhitungan 1 jam berikutnya 1 x 1/173 x 3 x gaji_pokok
            $amount += 3600 * 1 / 3600 / $koef * 3 * $gaji_pokok;
            // perhitungan sisa jam berikutnya sisa_jam x 1/173 x 4 x gaji_pokok
            $amount += ($ot_length - (3600 * 9)) / 3600 / $koef * 4 * $gaji_pokok;
            return round($amount, 2);
        }
    }

    // cek apakah hari ini adalah hari libur nasional
    public function isHolidayOT($tanggal) 
    {
        $where = ['tanggal' => $tanggal];
        $data = Holiday::where($where)->first();
        if(!empty($data)){
            return ['isHoliday'=> true, 'is_cuti'=> $data->is_cuti, 'msg'=> '<span class="badge badge-danger">'.$data->libur.'</span>'];
            // return true;
        }else{
            return ['isHoliday'=> false, 'is_cuti'=> 0, 'msg'=> ''];
            // return false;
        }
    }

    // cek apakah hari ini sabtu minggu
    public function isWeekendOT($tanggal) 
    {
        $weekDay = date('w', strtotime($tanggal));
        if(($weekDay == 0 || $weekDay == 6)){
            return ['isWeekend'=> true, 'msg'=> '<span class="badge badge-danger">Weekend</span>'];
            // return true;
        }else{
            return ['isWeekend'=> false, 'msg'=> '<span class="badge badge-success">Weekday</span>'];
            // return false;
        }
        // return ($weekDay == 0 || $weekDay == 6);
    }

    // cek apakah hari ini adalah working day non reguler
    public function isWorkingDayOT($nik, $tanggal, $wg_id) 
    {
        $cek = false;
        $where = [ 'nik' => $nik, 'working_date' => $tanggal, 'wg_id' => $wg_id ];
        $check = WorkingDay::where($where)->exists();
        if($check){
            // khusus group security jika OT di hari libur nasional dan jatuh di jadwal kerjanya maka dihitung Weekend
            // if($wg_id == 2){
                $isHoliday = $this->isHolidayOT($tanggal)['isHoliday'];
                if($isHoliday){
                    // Weekend
                    return ['isWorkingDay'=> false, 'msg'=> '<span class="badge badge-danger">Weekend</span>'];
                }else{
                    // Weekday
                    return ['isWorkingDay'=> true, 'msg'=> '<span class="badge badge-success">Weekday</span>'];
                }
            // }else{
            //     return ['isWorkingDay'=> true, 'msg'=> '<span class="badge badge-success">Weekday</span>'];
            // }
        }else{
            return ['isWorkingDay'=> false, 'msg'=> '<span class="badge badge-danger">Weekend</span>'];
        }
    }

    public function login(Request $request)
    {
        // $id = $request->id;
        $nik = $request->nik;
        $pwd = $request->pwd;
        // 
        // $where = [ 'nik' => $nik, 'is_aktif' => 1, 'password' => MD5($pwd) ];
        // $where = [ 'nik' => $nik, 'is_aktif' => 1, 'password' => Hash::make($pwd) ];
        $where = [ 'nik' => $nik, 'is_aktif' => 1 ];
        $data = Karyawan::select('password')->where($where)->first();
		if(!empty($data)){
			if (Hash::check($pwd, $data->password)) {
				$data = Karyawan::select('mtr_tbl_karyawan.nik','mtr_tbl_karyawan.nama','mtr_tbl_karyawan.email','mtr_tbl_perusahaan.nama as perusahaan','mtr_tbl_divisi.nama as divisi','mtr_tbl_departemen.nama as dep','mtr_tbl_sub_departemen.nama as sub_dep','mtr_tbl_level.id as id_level','mtr_tbl_level.level as level','mtr_tbl_jabatan.jabatan as jabatan','mtr_tbl_working_group.is_reguler as is_reguler','mtr_tbl_working_group.ot_weekday as ot_weekday','mtr_tbl_working_group.ot_weekend as ot_weekend','mtr_tbl_working_group.wg_ot_monthly as ot_monthly','mtr_tbl_working_group.wg_ot_weekly as ot_weekly','mtr_tbl_working_group.wg_ot_daily as ot_daily')
				->leftJoin('mtr_tbl_perusahaan', 'mtr_tbl_perusahaan.id', '=', 'mtr_tbl_karyawan.kode_perusahaan')
				->leftJoin('mtr_tbl_divisi', 'mtr_tbl_divisi.id', '=', 'mtr_tbl_karyawan.kode_divisi')
				->leftJoin('mtr_tbl_departemen', 'mtr_tbl_departemen.id', '=', 'mtr_tbl_karyawan.kode_departemen')
				->leftJoin('mtr_tbl_sub_departemen', 'mtr_tbl_sub_departemen.id', '=', 'mtr_tbl_karyawan.kode_sub_departemen')
				->leftJoin('mtr_tbl_level', 'mtr_tbl_level.id', '=', 'mtr_tbl_karyawan.kode_level')
				->leftJoin('mtr_tbl_jabatan', 'mtr_tbl_jabatan.id', '=', 'mtr_tbl_karyawan.kode_jabatan')
				->leftJoin('mtr_tbl_working_group', 'mtr_tbl_working_group.id', '=', 'mtr_tbl_karyawan.wg_id')
				->where($where)->first();
				if($request->has('fcmToken')) {
					$karyawan = Karyawan::where($where)->first();
					$karyawan->mobile_id = $request->fcmToken;
					$karyawan->update();
				}
				return response()->json( [ 'success' => true, 'message' => 'Data is viewed successfully', 'data' => $data ], 200 );
			}else{
				return response()->json( [ 'success' => false, 'message' => 'Data is empty', 'data' => [] ], 200 );
			}
		}else{
            return response()->json( [ 'success' => false, 'message' => 'Data is empty', 'data' => [] ], 200 );
        }
		//$where = [ 'nik' => $nik, 'is_aktif' => 1, 'password' => Hash::make($pwd) ];
        // $data = Karyawan::where($where)->first();
        // $data = Karyawan::select('mtr_tbl_karyawan.nik','mtr_tbl_karyawan.nama','mtr_tbl_karyawan.email','mtr_tbl_perusahaan.nama as perusahaan','mtr_tbl_divisi.nama as divisi','mtr_tbl_departemen.nama as dep','mtr_tbl_sub_departemen.nama as sub_dep','mtr_tbl_level.id as id_level','mtr_tbl_level.level as level','mtr_tbl_jabatan.jabatan as jabatan','mtr_tbl_working_group.is_reguler as is_reguler','mtr_tbl_working_group.ot_weekday as ot_weekday','mtr_tbl_working_group.ot_weekend as ot_weekend','mtr_tbl_working_group.wg_ot_monthly as ot_monthly','mtr_tbl_working_group.wg_ot_weekly as ot_weekly','mtr_tbl_working_group.wg_ot_daily as ot_daily')
        // ->leftJoin('mtr_tbl_perusahaan', 'mtr_tbl_perusahaan.id', '=', 'mtr_tbl_karyawan.kode_perusahaan')
        // ->leftJoin('mtr_tbl_divisi', 'mtr_tbl_divisi.id', '=', 'mtr_tbl_karyawan.kode_divisi')
        // ->leftJoin('mtr_tbl_departemen', 'mtr_tbl_departemen.id', '=', 'mtr_tbl_karyawan.kode_departemen')
        // ->leftJoin('mtr_tbl_sub_departemen', 'mtr_tbl_sub_departemen.id', '=', 'mtr_tbl_karyawan.kode_sub_departemen')
        // ->leftJoin('mtr_tbl_level', 'mtr_tbl_level.id', '=', 'mtr_tbl_karyawan.kode_level')
        // ->leftJoin('mtr_tbl_jabatan', 'mtr_tbl_jabatan.id', '=', 'mtr_tbl_karyawan.kode_jabatan')
        // ->leftJoin('mtr_tbl_working_group', 'mtr_tbl_working_group.id', '=', 'mtr_tbl_karyawan.wg_id')
        // ->where($where)->first();
        // if(!empty($data)){
        //     // $isLogin = true;
        //     return response()->json( [ 'success' => true, 'message' => 'Data is viewed successfully', 'data' => $data ], 200 );
        // }else{
        //     return response()->json( [ 'success' => false, 'message' => 'Data is empty', 'data' => $data ], 200 );
        // }
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
        $id = $request->id;
        // $isLogin = false;
        $nik = $request->nik;
        $filename = $request->filename;
        // $isLogin = false;
        switch ($id) {
            // case 'login':
            //     $nik = $request->nik;
            //     $pwd = $request->pwd;
            //     $where = [ 'nik' => $nik, 'is_aktif' => 1, 'password' => MD5($pwd) ];
            //     // $data = Karyawan::where($where)->first();
            //     $data = Karyawan::select('mtr_tbl_karyawan.nik','mtr_tbl_karyawan.nama','mtr_tbl_karyawan.email','mtr_tbl_perusahaan.nama as perusahaan','mtr_tbl_divisi.nama as divisi','mtr_tbl_departemen.nama as dep','mtr_tbl_sub_departemen.nama as sub_dep','mtr_tbl_level.id as id_level','mtr_tbl_level.level as level','mtr_tbl_jabatan.jabatan as jabatan','mtr_tbl_working_group.is_reguler as is_reguler','mtr_tbl_working_group.ot_weekday as ot_weekday','mtr_tbl_working_group.ot_weekend as ot_weekend','mtr_tbl_working_group.wg_ot_monthly as ot_monthly','mtr_tbl_working_group.wg_ot_weekly as ot_weekly','mtr_tbl_working_group.wg_ot_daily as ot_daily')
            //     ->leftJoin('mtr_tbl_perusahaan', 'mtr_tbl_perusahaan.id', '=', 'mtr_tbl_karyawan.kode_perusahaan')
            //     ->leftJoin('mtr_tbl_divisi', 'mtr_tbl_divisi.id', '=', 'mtr_tbl_karyawan.kode_divisi')
            //     ->leftJoin('mtr_tbl_departemen', 'mtr_tbl_departemen.id', '=', 'mtr_tbl_karyawan.kode_departemen')
            //     ->leftJoin('mtr_tbl_sub_departemen', 'mtr_tbl_sub_departemen.id', '=', 'mtr_tbl_karyawan.kode_sub_departemen')
            //     ->leftJoin('mtr_tbl_level', 'mtr_tbl_level.id', '=', 'mtr_tbl_karyawan.kode_level')
            //     ->leftJoin('mtr_tbl_jabatan', 'mtr_tbl_jabatan.id', '=', 'mtr_tbl_karyawan.kode_jabatan')
            //     ->leftJoin('mtr_tbl_working_group', 'mtr_tbl_working_group.id', '=', 'mtr_tbl_karyawan.wg_id')
            //     ->where($where)->first();
            //     if(!empty($data)){
            //         $isLogin = true;
            //         return response()->json( [ 'success' => true, 'message' => 'Data is viewed successfully', 'data' => $data ], 200 );
            //     }else{
            //         return response()->json( [ 'success' => false, 'message' => 'Data is empty', 'data' => $data ], 200 );
            //     }
            //   break;
            case 'feeds':
                $where = ['is_active' => 1];
                $data = Feeds::where($where)->orderBy('created_at', 'desc')->get();
                if(!empty($data)){
                    return response()->json( [ 'success' => true, 'message' => 'Data is viewed successfully', 'data' => $data ], 200 );
                }else{
                    return response()->json( [ 'success' => false, 'message' => 'Data is empty', 'data' => $data ], 200 );
                }
              break;
            case 'banner':
                $data = Banner::orderBy('id', 'asc')->get();
                if(!empty($data)){
                    return response()->json( [ 'success' => true, 'message' => 'Data is viewed successfully', 'data' => $data ], 200 );
                }else{
                    return response()->json( [ 'success' => false, 'message' => 'Data is empty', 'data' => $data ], 200 );
                }
              break;
            case 'tdl':
                $where = ['nik' => $nik, 'filename' => $filename];
                $data = ToDoList::where($where)->orderBy('created_at', 'desc')->get();
                if($filename == 'emails.penilaian0'){
                    $where = ['nik' => $nik];
                    $data = ToDoList::where($where)->whereIn('filename', ['emails.penilaian0', 'emails.penilaian0_outsourcing'])->orderBy('created_at', 'desc')->get();
                }
                if(!empty($data)){
                    return response()->json( [ 'success' => true, 'message' => 'Data is viewed successfully', 'data' => $data ], 200 );
                }else{
                    return response()->json( [ 'success' => false, 'message' => 'Data is empty', 'data' => $data ], 200 );
                }
              break;
            case 'ntdl':
                $data = array();
                $where = ['nik' => $nik, 'filename' => 'emails.lembur0'];
                array_push($data, ToDoList::where($where)->count());
                $where = ['nik' => $nik];
                array_push($data, ToDoList::where($where)->whereIn('filename', ['emails.penilaian0', 'emails.penilaian0_outsourcing'])->count());
                $where = ['nik' => $nik, 'filename' => 'emails.rekomendasi0'];
                array_push($data, ToDoList::where($where)->count());
                $where = ['nik' => $nik, 'filename' => 'emails.cuti0'];
                array_push($data, ToDoList::where($where)->count());
                $where = ['nik' => $nik, 'filename' => 'emails.izin0'];
                array_push($data, ToDoList::where($where)->count());
                $where = ['nik' => $nik, 'filename' => 'emails.nonaktif0'];
                array_push($data, ToDoList::where($where)->count());
                $where = ['nik' => $nik, 'filename' => 'emails.reimburse0'];
                array_push($data, ToDoList::where($where)->count());
                $where = ['nik' => $nik, 'filename' => 'emails.kebutuhan0'];
                array_push($data, ToDoList::where($where)->count());
                $where = ['nik' => $nik, 'filename' => 'emails.pelatihan0'];
                array_push($data, ToDoList::where($where)->count());
                $where = ['nik' => $nik, 'filename' => 'emails.bpd0'];
                array_push($data, ToDoList::where($where)->count());
                $where = ['nik' => $nik, 'filename' => 'emails.sakit0'];
                array_push($data, ToDoList::where($where)->count());
                if(!empty($data)){
                    return response()->json( [ 'success' => true, 'message' => 'Data is viewed successfully', 'data' => $data ], 200 );
                }else{
                    return response()->json( [ 'success' => false, 'message' => 'Data is empty', 'data' => $data ], 200 );
                }
                break;
            case 'absensi':
                $data = $this->getProfile($nik);
                return response()->json( [ 'success' => true, 'message' => 'Data is viewed successfully', 'data' => $data ], 200 );
              break;
            case 'group':
                $data = $this->getProfileGroup($nik);
                return response()->json( [ 'success' => true, 'message' => 'Data is viewed successfully', 'data' => $data ], 200 );
              break;
            default:
              return response()->json( [ 'success' => false, 'message' => 'No Data is viewed', 'data' => [] ], 200 );
        }
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

    // cek apakah hari ini adalah hari cutinya
    public function isCuti($nik, $date) {
        // 4=Disetujui oleh Layer 1
        // 6=Selesai
		$where = [ 'nik_karyawan' => $nik ];
        $exists = TrxCuti::where($where)->whereIn('status_cuti', [4,6])->whereDate('tanggal_mulai','<=', $date)->whereDate('tanggal_akhir','>=', $date)->exists();
        return $exists;
    }

    // cek apakah hari ini adalah hari sakitnya
    public function isSakit($nik, $date) {
        // 4=Disetujui oleh Layer 1
        // 6=Selesai
		$where = [ 'nik_karyawan' => $nik ];
        $exists = TrxSakit::where($where)->whereIn('status_sakit', [4,6])->whereDate('tanggal_mulai','<=', $date)->whereDate('tanggal_akhir','>=', $date)->exists();
        return $exists;
    }

    // profile karyawan is_reguler = 0 -> non reguler (ex. security, noc, cls)
    // mereka punya aturan clockin clockout masing-masing
    public function getProfileGroup($nik) {
        $tgl = date('Y-m-d');
        $jam = date('H:i:s');
        $input = date('Y-m-d H:i:s');
        $isClock = array("isClock"=>true);
        $arr = array();
        // $where = [ 'nik' => $nik, 'working_date' => $tgl ];
        // $data = WorkingDay::where($where)->get();
        $where = [ 'nik' => $nik ];
        $data = WorkingDay::where($where)->whereIn('working_date', [date('Y-m-d', strtotime($tgl. ' - 1 days')), $tgl, date('Y-m-d', strtotime($tgl. ' + 1 days'))])->get();
        foreach ($data as $_data) {
            $_arr = array();
            array_push($_arr, $_data);
            array_push($_arr, $isClock);
            // $nGetClock = $this->getNClockGroup($nik, $tgl, $_data->id);
            $nGetClock = $this->getNClockGroup($nik, $_data->id);
            array_push($_arr, $nGetClock);
            array_push($_arr, array("tgl"=>$tgl, "jam"=>$jam));
            array_push($_arr, $this->getClockinoutGroup($_data->start_clockin,$_data->end_clockin,$_data->start_clockout,$_data->end_clockout,$nGetClock,$input,$isClock,$nik,$tgl));
            // array_push($_arr, $this->getDataClockGroup($nik, $tgl, $_data->id));
            array_push($_arr, $this->getDataClockGroup($nik, $_data->id));
            //
            array_push($arr, $_arr);
        }
        return $arr;
    }

    public function getClockinoutGroup($fromClockin,$toClockin,$fromClockout,$toClockout,$nGetClock,$input,$isClock,$nik,$tgl){
        // cek window clockin
        $isWindowClockin = false;
        if($this->checkIfExist($fromClockin,$toClockin,$input)){
            $isWindowClockin = true;
        }
        // cek window clockout
        $isWindowClockout = false;
        if($this->checkIfExist($fromClockout,$toClockout,$input)){
            $isWindowClockout = true;
        }
        $isClockinout = array("isClockin"=>false, "isClockout"=>false, "isCheckin"=>false);
        // apakah harus clockin di hari kerja dan between clock window dan belum clock in
        if($isClock["isClock"] && $isWindowClockin && $nGetClock["n_clockin"]==0){
            $isClockinout = array("isClockin"=>true, "isClockout"=>false, "isCheckin"=>false);
        }
        // apakah harus clockout di hari kerja dan between clock window dan belum clock out
        else if($isClock["isClock"] && $isWindowClockout && $nGetClock["n_clockin"]>0){
            if($nGetClock["n_clockout"]>0){
                $isClockinout = array("isClockin"=>false, "isClockout"=>false, "isCheckin"=>false);
            }else{
                $isClockinout = array("isClockin"=>false, "isClockout"=>true, "isCheckin"=>true);
            }
        }
        // check in
        else if($isClock["isClock"] && $nGetClock["n_clockout"]==0 && $nGetClock["n_clockin"]>0){
            $isClockinout = array("isClockin"=>false, "isClockout"=>false, "isCheckin"=>true);
        }
        // jika cuti tidak cico
		if($this->isCuti($nik, $tgl)){
			$isClockinout = array("isClockin"=>false, "isClockout"=>false, "isCheckin"=>false);
		}
        // jika sakit tidak cico
		if($this->isSakit($nik, $tgl)){
			$isClockinout = array("isClockin"=>false, "isClockout"=>false, "isCheckin"=>false);
		}
        return $isClockinout;
    }

    // profile karyawan wg_id = 1 -> reguler
    public function getProfile($nik) {
        $tgl = date('Y-m-d');
        $jam = date('H:i:s');
        $waktu = date('Y-m-d H:i:s');
        $isLibur1 = $this->isWeekend(date($tgl));
        $isLibur2 = $this->isHoliday(date($tgl));
        //
        $isClock = array("isClock"=>false);
        if($isLibur1){
            $isClock = array("isClock"=>false);
        }else{
            if($isLibur2){
                $isClock = array("isClock"=>false);
            }else{
                $isClock = array("isClock"=>true);
            }
        }
        // ambil data clock in = dtClockin
        $getDataClock = $this->getDataClock($nik, $tgl);
        $dtClockin = $this->getDataClock($nik, $tgl)['dtClockin'];
        // mendapatkan jumlah clock in atau clock out
        // jika sudah n_clockin maka 1 jika sudah n_clockout maka 1 jika belum maka 0
        $nGetClock = $this->getNClock($nik, $tgl);
        //
        $data = DB::select(DB::raw("SELECT a.nik,a.nama,a.wg_id,b.clockin,b.clockout,b.start_clockin,b.end_clockin,b.start_clockout,b.end_clockout,b.is_reguler FROM mtr_tbl_karyawan AS a LEFT JOIN mtr_tbl_working_group AS b ON b.id=a.wg_id WHERE a.nik=:nik"), array('nik'=>$nik,));
        $fromClockin = "0000-00-00 00:00:00";
        $toClockin = "0000-00-00 00:00:00";
        $fromClockout = "0000-00-00 00:00:00";
        $toClockout = "0000-00-00 00:00:00";
        // return response()->json($data);
        $input = $waktu;
        // jika nik adalah reguler maka waktu yang digunakan adalah today
        if($data[0]->is_reguler == 1){
            $fromClockin = $tgl." ".$data[0]->start_clockin;
            $toClockin = $tgl." ".$data[0]->end_clockin;
        }
        if($data[0]->is_reguler == 1){
            $fromClockout = $tgl." ".$data[0]->start_clockout;
            $toClockout = $tgl." ".$data[0]->end_clockout;
        }
        // cek window clockin
        $isWindowClockin = false;
        if($this->checkIfExist($fromClockin,$toClockin,$input)){
            $isWindowClockin = true;
        }
        // cek window clockout
        $isWindowClockout = false;
        if($this->checkIfExist($fromClockout,$toClockout,$input)){
            $isWindowClockout = true;
        }
        $isClockinout = array("isClockin"=>false, "isClockout"=>false, "isCheckin"=>false);
        // apakah harus clockin di hari kerja dan between clock window dan belum clock in
        if($isClock["isClock"] && $isWindowClockin && $nGetClock["n_clockin"]==0){
            $isClockinout = array("isClockin"=>true, "isClockout"=>false, "isCheckin"=>false);
        }
        // apakah harus clockout di hari kerja dan between clock window dan belum clock out
        else if($isClock["isClock"] && $isWindowClockout && $nGetClock["n_clockin"]>0){
            if($nGetClock["n_clockout"]>0){
                $isClockinout = array("isClockin"=>false, "isClockout"=>false, "isCheckin"=>false);
            }else{
                $isClockinout = array("isClockin"=>false, "isClockout"=>true, "isCheckin"=>true);
                // refer to cm 523
                // refer to "C:\alita\matta\data_master\kemungkinan cico v1.xlsx"
                if($data[0]->is_reguler == 1 && Carbon::parse($dtClockin)->diffInHours(Carbon::now()) < 9){
                    $isClockinout = array("isClockin"=>false, "isClockout"=>false, "isCheckin"=>true);
                }
            }
        }
        // check in
        else if($isClock["isClock"] && $nGetClock["n_clockout"]==0 && $nGetClock["n_clockin"]>0){
            $isClockinout = array("isClockin"=>false, "isClockout"=>false, "isCheckin"=>true);
        }
        // else{
        //     $isClockinout = array("isClockin"=>false, "isClockout"=>false, "isCheckin"=>true);
        // }
        // jika cuti tidak cico
		if($this->isCuti($nik, $tgl)){
			$isClockinout = array("isClockin"=>false, "isClockout"=>false, "isCheckin"=>false);
		}
        // jika sakit tidak cico
		if($this->isSakit($nik, $tgl)){
			$isClockinout = array("isClockin"=>false, "isClockout"=>false, "isCheckin"=>false);
		}
        array_push($data, $isClock);
        array_push($data, $nGetClock);
        array_push($data, array("tgl"=>$tgl, "jam"=>$jam));
        array_push($data, $isClockinout);
        array_push($data, $getDataClock);
        // echo $isClock["isClock"];
        return $data;
    }

    // fungsi untuk mendapatkan apakah sudah clock in dan clock out
    // jika sudah clock in maka n_clockin=1 dan jika sudah clock out maka n_clockout=1
    public function getNClock($nik, $tgl) {
        $whereClockin = [ 'att_nik' => $nik, 'att_date' => $tgl, 'att_type' => 'Clock-in', 'att_data_src' => 'Regular' ];
        $dataClockin = Attendance::where($whereClockin)->count();
        $whereClockout = [ 'att_nik' => $nik, 'att_date' => $tgl, 'att_type' => 'Clock-out', 'att_data_src' => 'Regular' ];
        $dataClockout = Attendance::where($whereClockout)->count();
        $whereCheckin = [ 'att_nik' => $nik, 'att_date' => $tgl, 'att_type' => 'Check-in', 'att_data_src' => 'Regular' ];
        $dataCheckin = Attendance::where($whereCheckin)->count();
        return array("n_clockin"=>$dataClockin, "n_clockout"=>$dataClockout, "n_checkin"=>$dataCheckin);
    }

    // khusus untuk non reguler
    public function getNClockGroup($nik, $wd_id) {
        $whereClockin = [ 'att_nik' => $nik, 'att_type' => 'Clock-in', 'att_data_src' => 'Regular', 'att_wd_ot_id' => $wd_id ];
        $dataClockin = Attendance::where($whereClockin)->count();
        $whereClockout = [ 'att_nik' => $nik, 'att_type' => 'Clock-out', 'att_data_src' => 'Regular', 'att_wd_ot_id' => $wd_id ];
        $dataClockout = Attendance::where($whereClockout)->count();
        $whereCheckin = [ 'att_nik' => $nik, 'att_type' => 'Check-in', 'att_data_src' => 'Regular', 'att_wd_ot_id' => $wd_id ];
        $dataCheckin = Attendance::where($whereCheckin)->count();
        return array("n_clockin"=>$dataClockin, "n_clockout"=>$dataClockout, "n_checkin"=>$dataCheckin);
    }

    // khusus untuk non reguler
    // public function getNClockGroup($nik, $tgl, $wd_id) {
    //     $whereClockin = [ 'att_nik' => $nik, 'att_date' => $tgl, 'att_type' => 'Clock-in', 'att_data_src' => 'Regular', 'att_wd_ot_id' => $wd_id ];
    //     $dataClockin = Attendance::where($whereClockin)->count();
    //     $whereClockout = [ 'att_nik' => $nik, 'att_date' => $tgl, 'att_type' => 'Clock-out', 'att_data_src' => 'Regular', 'att_wd_ot_id' => $wd_id ];
    //     $dataClockout = Attendance::where($whereClockout)->count();
    //     $whereCheckin = [ 'att_nik' => $nik, 'att_date' => $tgl, 'att_type' => 'Check-in', 'att_data_src' => 'Regular', 'att_wd_ot_id' => $wd_id ];
    //     $dataCheckin = Attendance::where($whereCheckin)->count();
    //     return array("n_clockin"=>$dataClockin, "n_clockout"=>$dataClockout, "n_checkin"=>$dataCheckin);
    // }

    // fungsi mendapatkan semua timestamp
    public function getDataClock($nik, $tgl) {
        $whereClockin = [ 'att_nik' => $nik, 'att_date' => $tgl, 'att_type' => 'Clock-in', 'att_data_src' => 'Regular' ];
        $dataClockin = Attendance::where($whereClockin)->value('att_timestamp');
        $whereClockout = [ 'att_nik' => $nik, 'att_date' => $tgl, 'att_type' => 'Clock-out', 'att_data_src' => 'Regular' ];
        $dataClockout = Attendance::where($whereClockout)->value('att_timestamp');
        $whereCheckin = [ 'att_nik' => $nik, 'att_date' => $tgl, 'att_type' => 'Check-in', 'att_data_src' => 'Regular' ];
        $dataCheckin = Attendance::where($whereCheckin)->get('att_timestamp');
        return array("dtClockin"=>$dataClockin, "dtClockout"=>$dataClockout, "dtCheckin"=>$dataCheckin);
    }

    // // khusus untuk non reguler
    // public function getDataClockGroup($nik, $tgl, $wd_id) {
    //     $whereClockin = [ 'att_nik' => $nik, 'att_date' => $tgl, 'att_type' => 'Clock-in', 'att_data_src' => 'Regular', 'att_wd_ot_id' => $wd_id ];
    //     $dataClockin = Attendance::where($whereClockin)->value('att_timestamp');
    //     $whereClockout = [ 'att_nik' => $nik, 'att_date' => $tgl, 'att_type' => 'Clock-out', 'att_data_src' => 'Regular', 'att_wd_ot_id' => $wd_id ];
    //     $dataClockout = Attendance::where($whereClockout)->value('att_timestamp');
    //     $whereCheckin = [ 'att_nik' => $nik, 'att_date' => $tgl, 'att_type' => 'Check-in', 'att_data_src' => 'Regular', 'att_wd_ot_id' => $wd_id ];
    //     $dataCheckin = Attendance::where($whereCheckin)->get('att_timestamp');
    //     return array("dtClockin"=>$dataClockin, "dtClockout"=>$dataClockout, "dtCheckin"=>$dataCheckin);
    // }

    // khusus untuk non reguler
    public function getDataClockGroup($nik, $wd_id) {
        $whereClockin = [ 'att_nik' => $nik, 'att_type' => 'Clock-in', 'att_data_src' => 'Regular', 'att_wd_ot_id' => $wd_id ];
        $dataClockin = Attendance::where($whereClockin)->value('att_timestamp');
        $whereClockout = [ 'att_nik' => $nik, 'att_type' => 'Clock-out', 'att_data_src' => 'Regular', 'att_wd_ot_id' => $wd_id ];
        $dataClockout = Attendance::where($whereClockout)->value('att_timestamp');
        $whereCheckin = [ 'att_nik' => $nik, 'att_type' => 'Check-in', 'att_data_src' => 'Regular', 'att_wd_ot_id' => $wd_id ];
        $dataCheckin = Attendance::where($whereCheckin)->get('att_timestamp');
        return array("dtClockin"=>$dataClockin, "dtClockout"=>$dataClockout, "dtCheckin"=>$dataCheckin);
    }

    // fungsi untuk cek apakah masuk window clockin clockout
    public function checkIfExist($from, $to, $input) {
        $fromDateTime = Carbon::createFromFormat('Y-m-d H:i:s', trim($from));
        $toDateTime = Carbon::createFromFormat('Y-m-d H:i:s', trim($to));
        $inputDateTime= Carbon::createFromFormat('Y-m-d H:i:s', trim($input));
        return ($fromDateTime <= $inputDateTime && $inputDateTime < $toDateTime);
    }

    // cek pengajuan OT
    // public function makerOT($from, $to, $input) {}
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
        // $where = ['nik' => $nik, 'is_aktif' => 1];
        $where = ['nik' => $nik];
        $data = Karyawan::select('mtr_tbl_karyawan.nik', 'mtr_tbl_karyawan.nama', 'mtr_tbl_karyawan.email', 'mtr_tbl_karyawan.telepon1', 
        'mtr_tbl_perusahaan.nama as perusahaan', 'mtr_tbl_divisi.nama as divisi', 'mtr_tbl_departemen.nama as dep', 'mtr_tbl_sub_departemen.nama as sub_dep', 
        'mtr_tbl_level.level as level', 'mtr_tbl_jabatan.jabatan as jabatan', 'mtr_tbl_level.id as id_level', 'mtr_tbl_working_group.wg_name as wg_name', 
        'mtr_tbl_working_group.id as wg_id', 'mtr_tbl_working_group.is_reguler as is_reguler',
        'mtr_tbl_status_karyawan.status_karyawan as status_karyawan')
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
}
