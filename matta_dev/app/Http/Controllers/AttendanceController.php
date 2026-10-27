<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\TrxCuti;
use App\Models\TrxSakit;
use App\Models\Attendance;
use App\Models\TrxFingePrint;
use App\Models\TrxFingePrintMatta;
use App\Models\Karyawan;
use App\Models\Perusahaan;
use App\Models\WorkingGroup;
use App\Models\WorkingDay;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use App\Exports\AttendanceMultiSheetExport;
use App\Models\TrxDebug;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;

class AttendanceController extends Controller
{

    public function attendance_source(Request $request, $id)
    {
        $fp = DB::table('trx_tbl_fp_data as a')->select('b.nik', 'b.nama')->join('mtr_tbl_karyawan as b', 'a.id_nip', '=', 'b.id_fp')->groupBy('a.id_nip');
        $matta = DB::table('trx_tbl_fp_data_matta as a')->select('b.nik', 'b.nama')->join('mtr_tbl_karyawan as b', 'a.att_nik', '=', 'b.nik')->groupBy('a.att_nik');
        $att_nik_nama = $fp->union($matta)->orderBy('nama')->get()->unique('nik')->values();
        $trx_fp = [];
        $trx_matta = [];
        $date_from = '';
        $date_to = '';
        $str_att_nik_nama = '';
        if($request->filter == true){
            $date_from = $request->date_from;
            $date_to = $request->date_to;
            $str_att_nik_nama = $request->str_att_nik_nama;
            // $date_from = '2026-07-06';
            // $date_to = '2026-07-06';
            // $str_att_nik_nama = '01032510885';
            // $trx_fp = TrxFingePrint::select(
            //     DB::raw('MIN(trx_tbl_fp_data.id) as id_clock_in'),
            //     DB::raw('CAST(CASE WHEN COUNT(*) > 1 THEN MAX(trx_tbl_fp_data.id) ELSE NULL END AS UNSIGNED) as id_clock_out'),
            //     'trx_tbl_fp_data.id_nip',
            //     'mtr_tbl_karyawan.nik',
            //     'mtr_tbl_karyawan.nama',
            //     'mtr_tbl_karyawan.wg_id',
            //     DB::raw('DATE(trx_tbl_fp_data.waktu) as tanggal'),
            //     DB::raw('MIN(trx_tbl_fp_data.waktu) as clock_in'),
            //     DB::raw('CASE WHEN COUNT(*) > 1 THEN MAX(trx_tbl_fp_data.waktu) ELSE NULL END as clock_out')
            // )
            // ->leftJoin('mtr_tbl_karyawan', 'mtr_tbl_karyawan.id_fp', '=', 'trx_tbl_fp_data.id_nip')
            // ->whereBetween(DB::raw('DATE(trx_tbl_fp_data.waktu)'), [$date_from, $date_to])
            // // ->where(['mtr_tbl_karyawan.wg_id' => 1, 'mtr_tbl_karyawan.nik' => $str_att_nik_nama])
            // ->where('mtr_tbl_karyawan.wg_id', 1)
            // ->when(!empty($str_att_nik_nama), fn($q) => $q->where('mtr_tbl_karyawan.nik', $str_att_nik_nama))
            // ->groupBy('trx_tbl_fp_data.id_nip', DB::raw('DATE(trx_tbl_fp_data.waktu)'))
            // ->orderBy('tanggal', 'desc')
            // ->orderBy('mtr_tbl_karyawan.nama')
            // ->get();
            // 
            $trx_fp = TrxFingePrint::select(
                // DB::raw('MIN(trx_tbl_fp_data.id) as id_clock_in'),
                // DB::raw('CAST(CASE WHEN COUNT(*) > 1 THEN MAX(trx_tbl_fp_data.id) ELSE NULL END AS UNSIGNED) as id_clock_out'),
                'trx_tbl_fp_data.id_nip',
                'mtr_tbl_karyawan.nik',
                'mtr_tbl_karyawan.nama',
                // 'mtr_tbl_karyawan.wg_id',
                'trx_tbl_fp_data.waktu as waktu',
                'trx_tbl_fp_data.id_mesin as id_mesin',
                // DB::raw('DATE(trx_tbl_fp_data.waktu) as tanggal'),
                // DB::raw('MIN(trx_tbl_fp_data.waktu) as clock_in'),
                // DB::raw('CASE WHEN COUNT(*) > 1 THEN MAX(trx_tbl_fp_data.waktu) ELSE NULL END as clock_out')
            )
            ->leftJoin('mtr_tbl_karyawan', 'mtr_tbl_karyawan.id_fp', '=', 'trx_tbl_fp_data.id_nip')
            ->whereBetween(DB::raw('DATE(trx_tbl_fp_data.waktu)'), [$date_from, $date_to])
            // ->where(['mtr_tbl_karyawan.wg_id' => 1, 'mtr_tbl_karyawan.nik' => $str_att_nik_nama])
            ->where('mtr_tbl_karyawan.wg_id', 1)
            ->when(!empty($str_att_nik_nama), fn($q) => $q->where('mtr_tbl_karyawan.nik', $str_att_nik_nama))
            // ->groupBy('trx_tbl_fp_data.id_nip', DB::raw('DATE(trx_tbl_fp_data.waktu)'))
            ->orderBy('mtr_tbl_karyawan.nama')
            ->orderBy('waktu', 'desc')
            ->get();
            // 
            $trx_matta = TrxFingePrintMatta::select(
                DB::raw("MIN(CASE WHEN att_type = 'Clock-in'  THEN trx_tbl_fp_data_matta.id END) as id_clock_in"),
                DB::raw("MIN(CASE WHEN att_type = 'Clock-out' THEN trx_tbl_fp_data_matta.id END) as id_clock_out"),
                'mtr_tbl_karyawan.id_fp as id_nip',
                'mtr_tbl_karyawan.nik',
                'mtr_tbl_karyawan.nama',
                'mtr_tbl_karyawan.wg_id',
                'trx_tbl_fp_data_matta.att_date as tanggal',
                DB::raw("MIN(CASE WHEN att_type = 'Clock-in'  THEN att_timestamp END) as clock_in"),
                DB::raw("MIN(CASE WHEN att_type = 'Clock-out' THEN att_timestamp END) as clock_out"),
            )
            ->leftJoin('mtr_tbl_karyawan', 'mtr_tbl_karyawan.nik', '=', 'trx_tbl_fp_data_matta.att_nik')
            ->whereIn('att_type', ['Clock-in', 'Clock-out'])
            ->whereBetween(DB::raw('trx_tbl_fp_data_matta.att_date'), [$date_from, $date_to])
            // ->where(['mtr_tbl_karyawan.nik' => $str_att_nik_nama])
            ->when(!empty($str_att_nik_nama), fn($q) => $q->where('mtr_tbl_karyawan.nik', $str_att_nik_nama))
            ->groupBy('trx_tbl_fp_data_matta.att_nik', 'trx_tbl_fp_data_matta.att_date')
            ->orderBy('tanggal', 'desc')
            ->orderBy('mtr_tbl_karyawan.nama')
            ->get();
        }
        // return response()->json( [ 'trx_fp' => $trx_fp, 'trx_matta' => $trx_matta ], 200 );
        return view('attendance.source', compact( 'str_att_nik_nama', 'att_nik_nama', 'trx_fp', 'trx_matta', 'date_from', 'date_to'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(Request $request)
    // {
    //     set_time_limit(3600);
    //     $limit = 25000;
    //     $tahun = Carbon::parse(now())->format('Y');
    //     $bulan = Carbon::parse(now())->format('m');
    //     $arr_nik_nkp = new Karyawan();
    //     $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
    //     $att_nik_nama = DB::table('trx_tbl_attendance as a')->select('a.att_nik', 'b.nama')->distinct()->leftJoin('mtr_tbl_karyawan as b', 'a.att_nik', '=', 'b.nik')->orderBy('b.nama')->get();
    //     if(Gate::allows('hc_nkp')){
    //         $att_nik_nama = DB::table('trx_tbl_attendance as a')->select('a.att_nik', 'b.nama')->distinct()->leftJoin('mtr_tbl_karyawan as b', 'a.att_nik', '=', 'b.nik')->whereIn('a.att_nik',$arr_nik_nkp)->orderBy('b.nama')->get();
    //     }
    //     $duration = array();
    //     // start default hc
    //     $att = array();
    //     // $att = Attendance::select('*')->whereMonth('att_timestamp', $bulan)->whereYear('att_timestamp', $tahun)->orderBy( 'att_timestamp', 'desc' )->limit($limit)->get();
    //     // end default hc
    //     if(auth()->user()->userrole == 'nhc'){
    //         // $where = ['att_nik' => auth()->user()->nik];
    //         $where = ['layer1' => auth()->user()->nik];
    //         $bawahan = Karyawan::select('nik')->where($where)->get();
    //         $att_nik = array();
    //         array_push($att_nik, auth()->user()->nik);
    //         foreach($bawahan as $data){
    //             array_push($att_nik, $data->nik);
    //         }
    //         $att_nik_nama = $att_nik_nama->whereIn('att_nik', $att_nik)->values();
    //         $att = Attendance::select('*')->whereMonth('att_timestamp', $bulan)->whereYear('att_timestamp', $tahun)->whereIn('att_nik', $att_nik)->orderBy( 'att_timestamp', 'desc' )->limit($limit)->get();
    //         // start hitung durasi
    //         $duration = array();
    //         $_att_data_src = 'Regular';
    //         $raw = Attendance::select('*')->whereMonth('att_timestamp', $bulan)->whereYear('att_timestamp', $tahun)->whereIn('att_nik', $att_nik)->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'asc' )->limit($limit)->get();
    //         foreach($raw as $_raw){
    //             $result = Attendance::select('*')->where('att_nik', '=', $_raw->att_nik)->where('att_date', '=', $_raw->att_date)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
    //             // jika non reguler
    //             $where = ['nik' => $_raw->att_nik, 'wg_id' => 1];
    //             if(!Karyawan::where($where)->exists()){
    //                 $result = Attendance::select('*')->where('att_nik', '=', $_raw->att_nik)->where('att_wd_ot_id', '=', $_raw->att_wd_ot_id)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
    //             }
    //             // 
    //             if(!empty($result)){
    //                 array_push($duration, ['att_property' => $_raw->att_property, 'att_date' => $_raw->att_date, 'att_nik' => $_raw->att_nik, 'att_data_src' => $_att_data_src, 'Clock-in' => $_raw->att_timestamp, 'Clock-out' => $result->att_timestamp]);
    //             }
    //         }
    //         $_att_data_src = 'Overtime';
    //         $raw = Attendance::select('*')->whereMonth('att_timestamp', $bulan)->whereYear('att_timestamp', $tahun)->whereIn('att_nik', $att_nik)->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'asc' )->limit($limit)->get();
    //         foreach($raw as $_raw){
    //             $result = Attendance::select('*')->where('att_nik', '=', $_raw->att_nik)->where('att_date', '=', $_raw->att_date)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
    //             // jika non reguler
    //             $where = ['nik' => $_raw->att_nik, 'wg_id' => 1];
    //             if(!Karyawan::where($where)->exists()){
    //                 $result = Attendance::select('*')->where('att_nik', '=', $_raw->att_nik)->where('att_wd_ot_id', '=', $_raw->att_wd_ot_id)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
    //             }
    //             // 
    //             if(!empty($result)){
    //                 array_push($duration, ['att_property' => $_raw->att_property, 'att_date' => $_raw->att_date, 'att_nik' => $_raw->att_nik, 'att_data_src' => $_att_data_src, 'Clock-in' => $_raw->att_timestamp, 'Clock-out' => $result->att_timestamp]);
    //             }
    //         }
    //         // end hitung durasi
    //     }
    //     // filter parameter
    //     $_arr_att_type = Attendance::distinct()->orderBy( 'att_type', 'asc' )->get(['att_type']);
    //     $_arr_att_status = Attendance::distinct()->orderBy( 'att_status', 'asc' )->get(['att_status']);
    //     $_arr_att_data_src = Attendance::distinct()->orderBy( 'att_data_src', 'asc' )->get(['att_data_src']);
    //     // 0=NA
    //     // 3=SMS Project
    //     $perusahaan = Perusahaan::whereNotIn('id', [0,3])->orderBy( 'nama', 'asc' )->get();
    //     $wg_id = WorkingGroup::orderBy( 'id', 'asc' )->get();
    //     $arr_att_type = array();
    //     $arr_att_status = array();
    //     $arr_att_data_src = array();
    //     $arr_perusahaan = array();
    //     $arr_wg_id = array();
    //     foreach ($_arr_att_type as $data){
    //         if(!in_array($data->att_type, $arr_att_type)){
    //             array_push($arr_att_type, $data->att_type);
    //         }
    //     }
    //     foreach ($_arr_att_status as $data){
    //         if(!in_array($data->att_status, $arr_att_status)){
    //             array_push($arr_att_status, $data->att_status);
    //         }
    //     }
    //     foreach ($_arr_att_data_src as $data){
    //         if(!in_array($data->att_data_src, $arr_att_data_src)){
    //             array_push($arr_att_data_src, $data->att_data_src);
    //         }
    //     }
    //     foreach ($perusahaan as $data){
    //         array_push($arr_perusahaan, $data->id);
    //     }
    //     foreach ($wg_id as $data){
    //         array_push($arr_wg_id, $data->id);
    //     }
    //     $att_type = $arr_att_type;
    //     $att_status = $arr_att_status;
    //     $att_data_src = $arr_att_data_src;
    //     $date_from = '';
    //     $date_to = '';
    //     $str_att_nik_nama = '';
    //     // start filter
    //     // if($request->filter == true || $request->download == true){
    //     if($request->filter == true){
    //         // $this->into_debug(['nomor'=>1, 'modul'=>Route::getCurrentRoute()->getActionName(), 'url'=>$request->fullUrl()]);
    //         $date_from = $request->date_from;
    //         $date_to = $request->date_to;
    //         $str_att_nik_nama = $request->str_att_nik_nama;
    //         $_att_type = $request->input('att_type', []);
    //         $_att_status = $request->input('att_status', []);
    //         $_att_data_src = $request->input('att_data_src', []);
    //         $_perusahaan = $request->input('perusahaan', []);
    //         $_wg_id = $request->input('wg_id', []);
    //         if(count($_att_type) > 0){
    //             $arr_att_type = $_att_type;
    //         }
    //         else{
    //             $arr_att_type = array();
    //         }
    //         if(count($_att_status) > 0){
    //             $arr_att_status = $_att_status;
    //         }
    //         else{
    //             $arr_att_status = array();
    //         }
    //         if(count($_att_data_src) > 0){
    //             $arr_att_data_src = $_att_data_src;
    //         }
    //         else{
    //             $arr_att_data_src = array();
    //         }
    //         if(count($_perusahaan) > 0){
    //             $arr_perusahaan = $_perusahaan;
    //         }
    //         else{
    //             $arr_perusahaan = array();
    //         }
    //         if(count($_wg_id) > 0){
    //             $arr_wg_id = $_wg_id;
    //         }
    //         else{
    //             $arr_wg_id = array();
    //         }
    //         $att_nik = Karyawan::select('nik')->whereIn('kode_perusahaan', $arr_perusahaan)->get();
    //         $nik_wg_id = Karyawan::select('nik')->whereIn('wg_id', $arr_wg_id)->get();
    //         $att = Attendance::select('*')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->orderBy( 'att_timestamp', 'desc' )->limit($limit)->get();
    //         if(!empty($str_att_nik_nama)){
    //             $att = Attendance::select('*')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->where(['att_nik'=>$str_att_nik_nama])->orderBy( 'att_timestamp', 'desc' )->limit($limit)->get();
    //         }
    //         if(Gate::allows('hc_nkp')){
    //             $att = Attendance::select('*')->whereIn('att_nik',$arr_nik_nkp)->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->orderBy( 'att_timestamp', 'desc' )->limit($limit)->get();
    //             if(!empty($str_att_nik_nama)){
    //                 $att = Attendance::select('*')->whereIn('att_nik',$arr_nik_nkp)->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->where(['att_nik'=>$str_att_nik_nama])->orderBy( 'att_timestamp', 'desc' )->limit($limit)->get();
    //             }
    //         }
    //         // $att = Attendance::select('*')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->orderBy( 'att_timestamp', 'desc' )->limit($limit)->get();
    //         // start hitung durasi
    //         $duration = array();
    //         $_att_data_src = 'Regular';
    //         $raw = Attendance::select('*')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'asc' )->limit($limit)->get();
    //         if(!empty($str_att_nik_nama)){
    //             $raw = Attendance::select('*')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->where(['att_nik'=>$str_att_nik_nama])->orderBy( 'id', 'asc' )->limit($limit)->get();
    //         }
    //         if(Gate::allows('hc_nkp')){
    //             $raw = Attendance::select('*')->whereIn('att_nik',$arr_nik_nkp)->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'asc' )->limit($limit)->get();
    //             if(!empty($str_att_nik_nama)){
    //                 $raw = Attendance::select('*')->whereIn('att_nik',$arr_nik_nkp)->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->where(['att_nik'=>$str_att_nik_nama])->orderBy( 'id', 'asc' )->limit($limit)->get();
    //             }
    //         }
    //         // $raw = Attendance::select('*')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'asc' )->limit($limit)->get();
    //         foreach($raw as $_raw){
    //             // $this->into_debug(['nomor'=>2, 'modul'=>Route::getCurrentRoute()->getActionName(), 'url'=>$request->fullUrl()]);
    //             $result = Attendance::select('*')->where('att_nik', '=', $_raw->att_nik)->where('att_date', '=', $_raw->att_date)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
    //             // jika non reguler
    //             $where = ['nik' => $_raw->att_nik, 'wg_id' => 1];
    //             if(!Karyawan::where($where)->exists()){
    //                 $result = Attendance::select('*')->where('att_nik', '=', $_raw->att_nik)->where('att_wd_ot_id', '=', $_raw->att_wd_ot_id)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
    //             }
    //             // 
    //             if(!empty($result)){
    //                 array_push($duration, ['att_property' => $_raw->att_property, 'att_date' => $_raw->att_date, 'att_nik' => $_raw->att_nik, 'att_data_src' => $_att_data_src, 'Clock-in' => $_raw->att_timestamp, 'Clock-out' => $result->att_timestamp]);
    //             }
    //         }
    //         $_att_data_src = 'Overtime';
    //         $raw = Attendance::select('*')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'asc' )->limit($limit)->get();
    //         if(!empty($str_att_nik_nama)){
    //             $raw = Attendance::select('*')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->where(['att_nik'=>$str_att_nik_nama])->orderBy( 'id', 'asc' )->limit($limit)->get();
    //         }
    //         if(Gate::allows('hc_nkp')){
    //             $raw = Attendance::select('*')->whereIn('att_nik',$arr_nik_nkp)->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'asc' )->limit($limit)->get();
    //             if(!empty($str_att_nik_nama)){
    //                 $raw = Attendance::select('*')->whereIn('att_nik',$arr_nik_nkp)->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->where(['att_nik'=>$str_att_nik_nama])->orderBy( 'id', 'asc' )->limit($limit)->get();
    //             }
    //         }
    //         // $raw = Attendance::select('*')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'asc' )->limit($limit)->get();
    //         foreach($raw as $_raw){
    //             // $this->into_debug(['nomor'=>3, 'modul'=>Route::getCurrentRoute()->getActionName(), 'url'=>$request->fullUrl()]);
    //             $result = Attendance::select('*')->where('att_nik', '=', $_raw->att_nik)->where('att_date', '=', $_raw->att_date)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
    //             // jika non reguler
    //             $where = ['nik' => $_raw->att_nik, 'wg_id' => 1];
    //             if(!Karyawan::where($where)->exists()){
    //                 $result = Attendance::select('*')->where('att_nik', '=', $_raw->att_nik)->where('att_wd_ot_id', '=', $_raw->att_wd_ot_id)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
    //             }
    //             // 
    //             if(!empty($result)){
    //                 array_push($duration, ['att_property' => $_raw->att_property, 'att_date' => $_raw->att_date, 'att_nik' => $_raw->att_nik, 'att_data_src' => $_att_data_src, 'Clock-in' => $_raw->att_timestamp, 'Clock-out' => $result->att_timestamp]);
    //             }
    //         }
    //         // end hitung durasi
    //         if(auth()->user()->userrole == 'nhc'){
    //             // $this->into_debug(['nomor'=>4, 'modul'=>Route::getCurrentRoute()->getActionName(), 'url'=>$request->fullUrl()]);
    //             // $where = ['att_nik' => auth()->user()->nik];
    //             $where = ['layer1' => auth()->user()->nik];
    //             $bawahan = Karyawan::select('nik')->where($where)->get();
    //             $att_nik = array();
    //             array_push($att_nik, auth()->user()->nik);
    //             foreach($bawahan as $data){
    //                 array_push($att_nik, $data->nik);
    //             }
    //             $att = Attendance::select('*')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->orderBy( 'att_timestamp', 'desc' )->limit($limit)->get();
    //             if(!empty($str_att_nik_nama)){
    //                 $att = Attendance::select('*')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->where(['att_nik'=>$str_att_nik_nama])->orderBy( 'att_timestamp', 'desc' )->limit($limit)->get();
    //             }
    //             // start hitung durasi
    //             $duration = array();
    //             $_att_data_src = 'Regular';
    //             $raw = Attendance::select('*')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'asc' )->limit($limit)->get();
    //             if(!empty($str_att_nik_nama)){
    //                 $raw = Attendance::select('*')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->where(['att_nik'=>$str_att_nik_nama])->orderBy( 'id', 'asc' )->limit($limit)->get();
    //             }
    //             foreach($raw as $_raw){
    //                 // $this->into_debug(['nomor'=>5, 'modul'=>Route::getCurrentRoute()->getActionName(), 'url'=>$request->fullUrl()]);
    //                 $result = Attendance::select('*')->where('att_nik', '=', $_raw->att_nik)->where('att_date', '=', $_raw->att_date)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
    //                 // jika non reguler
    //                 $where = ['nik' => $_raw->att_nik, 'wg_id' => 1];
    //                 if(!Karyawan::where($where)->exists()){
    //                     $result = Attendance::select('*')->where('att_nik', '=', $_raw->att_nik)->where('att_wd_ot_id', '=', $_raw->att_wd_ot_id)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
    //                 }
    //             if(!empty($result)){
    //                     array_push($duration, ['att_property' => $_raw->att_property, 'att_date' => $_raw->att_date, 'att_nik' => $_raw->att_nik, 'att_data_src' => $_att_data_src, 'Clock-in' => $_raw->att_timestamp, 'Clock-out' => $result->att_timestamp]);
    //                 }
    //             }
    //             $_att_data_src = 'Overtime';
    //             $raw = Attendance::select('*')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'asc' )->limit($limit)->get();
    //             if(!empty($str_att_nik_nama)){
    //                 $raw = Attendance::select('*')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->where(['att_nik'=>$str_att_nik_nama])->orderBy( 'id', 'asc' )->limit($limit)->get();
    //             }
    //             foreach($raw as $_raw){
    //                 // $this->into_debug(['nomor'=>6, 'modul'=>Route::getCurrentRoute()->getActionName(), 'url'=>$request->fullUrl()]);
    //                 $result = Attendance::select('*')->where('att_nik', '=', $_raw->att_nik)->where('att_date', '=', $_raw->att_date)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
    //                 // jika non reguler
    //                 $where = ['nik' => $_raw->att_nik, 'wg_id' => 1];
    //                 if(!Karyawan::where($where)->exists()){
    //                     $result = Attendance::select('*')->where('att_nik', '=', $_raw->att_nik)->where('att_wd_ot_id', '=', $_raw->att_wd_ot_id)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();    
    //                 }
    //                 if(!empty($result)){
    //                     array_push($duration, ['att_property' => $_raw->att_property, 'att_date' => $_raw->att_date, 'att_nik' => $_raw->att_nik, 'att_data_src' => $_att_data_src, 'Clock-in' => $_raw->att_timestamp, 'Clock-out' => $result->att_timestamp]);
    //                 }
    //             }
    //             // end hitung durasi
    //         }
    //         // dd($att);
    //     }else{
    //         $arr_att_type = array();
    //         $arr_att_status = array();
    //         $arr_att_data_src = array();
    //         $arr_perusahaan = array();
    //         $arr_wg_id = array();
    //     }
    //     // start
    //     if($request->download == true){
    //         $date_from = $request->date_from;
    //         $date_to = $request->date_to;
    //         $att = Attendance::select('att_wd_ot_id','id_fp_trx','att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','att_property','wg_id')->whereBetween('att_date', [$date_from, $date_to])->orderBy( 'att_timestamp', 'desc' )->limit($limit)->get();
    //         // start hitung durasi
    //         $duration = array();
    //         $_att_data_src = 'Regular';
    //         $raw = Attendance::select('att_wd_ot_id','id_fp_trx','att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','att_property','wg_id')->whereBetween('att_date', [$date_from, $date_to])->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'asc' )->limit($limit)->get();
    //         foreach($raw as $_raw){
    //             $result = Attendance::select('att_wd_ot_id','id_fp_trx','att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','att_property','wg_id')->where('att_nik', '=', $_raw->att_nik)->where('att_date', '=', $_raw->att_date)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
    //             // jika non reguler
    //             $where = ['nik' => $_raw->att_nik, 'wg_id' => 1];
    //             if(!Karyawan::where($where)->exists()){
    //                 $result = Attendance::select('att_wd_ot_id','id_fp_trx','att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','att_property','wg_id')->where('att_nik', '=', $_raw->att_nik)->where('att_wd_ot_id', '=', $_raw->att_wd_ot_id)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
    //             }
    //             // 
    //             if(!empty($result)){
    //                 array_push($duration, ['att_property' => $_raw->att_property, 'att_date' => $_raw->att_date, 'att_nik' => $_raw->att_nik, 'att_data_src' => $_att_data_src, 'Clock-in' => $_raw->att_timestamp, 'Clock-out' => $result->att_timestamp, 'att_property' => $result->att_property]);
    //             }
    //         }
    //         $_att_data_src = 'Overtime';
    //         $raw = Attendance::select('att_wd_ot_id','id_fp_trx','att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','att_property','wg_id')->whereBetween('att_date', [$date_from, $date_to])->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'asc' )->limit($limit)->get();
    //         foreach($raw as $_raw){
    //             $result = Attendance::select('att_wd_ot_id','id_fp_trx','att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','att_property','wg_id')->where('att_nik', '=', $_raw->att_nik)->where('att_date', '=', $_raw->att_date)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
    //             // jika non reguler
    //             $where = ['nik' => $_raw->att_nik, 'wg_id' => 1];
    //             if(!Karyawan::where($where)->exists()){
    //                 $result = Attendance::select('att_wd_ot_id','id_fp_trx','att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','att_property','wg_id')->where('att_nik', '=', $_raw->att_nik)->where('att_wd_ot_id', '=', $_raw->att_wd_ot_id)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
    //             }
    //             // 
    //             if(!empty($result)){
    //                 array_push($duration, ['att_property' => $_raw->att_property, 'att_date' => $_raw->att_date, 'att_nik' => $_raw->att_nik, 'att_data_src' => $_att_data_src, 'Clock-in' => $_raw->att_timestamp, 'Clock-out' => $result->att_timestamp, 'att_property' => $result->att_property]);
    //             }
    //         }
    //         // end hitung durasi
    //         // start recap
    //         $trx = [];
    //         $arr_date = $this->dateRange($date_from, $date_to);
    //         // pengecualian karena pada tanggal ini terjadi suspend domain alita.id
    //         $arr_date = array_values(array_diff($arr_date, ['2024-09-23']));
    //         $karyawan = Karyawan::whereNotIn('nik', ['Hco.all', 'IT.BM'])->get();
    //         foreach ($karyawan as $_karyawan) {
    //             $nik = $_karyawan->nik;
    //             $arr_base = [];
    //             $arr_clock = [];
    //             $tanggal_bergabung = $_karyawan->tanggal_bergabung;
    //             // cek apakah karyawan berjenis kerja reguler
    //             if ($_karyawan->_wg->is_reguler == 1) {
    //                 foreach ($arr_date as $data) {
    //                     if (!$this->isWeekend($data)) {
    //                         if (!$this->isHoliday($data)) {
    //                             if (!$this->isCuti($nik, $data)) {
    //                                 array_push($arr_base, $data);
    //                             }
    //                             if ($this->isSakit($nik, $data)) {
    //                                 if (($key = array_search($data, $arr_base)) !== false) {
    //                                     unset($arr_base[$key]);
    //                                 }
    //                             }
    //                             if ($tanggal_bergabung > $data) {
    //                                 if (($key = array_search($data, $arr_base)) !== false) {
    //                                     unset($arr_base[$key]);
    //                                 }
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //             // karyawan non reguler dengan jadwal kerja sesuai working group nya
    //             else {
    //                 $where = ['nik' => $nik];
    //                 $data = WorkingDay::where($where)->whereIn('working_date', $arr_date)->get();
    //                 foreach ($data as $_data) {
    //                     if (!$this->isCuti($nik, $_data->working_date)) {
    //                         array_push($arr_base, $_data->working_date);
    //                     }
    //                     if ($this->isSakit($nik, $_data->working_date)) {
    //                         if (($key = array_search($_data->working_date, $arr_base)) !== false) {
    //                             unset($arr_base[$key]);
    //                         }
    //                     }
    //                     if ($tanggal_bergabung > $data) {
    //                         if (($key = array_search($data, $arr_base)) !== false) {
    //                             unset($arr_base[$key]);
    //                         }
    //                     }
    //                 }
    //             }
    //             foreach ($arr_base as $data) {
    //                 if ($this->isClockin($nik, $data)) {
    //                     array_push($arr_clock, $data);
    //                 }
    //             }
    //             $persentase = 0;
    //             if (count($arr_base) > 0) {
    //                 $persentase = count($arr_clock) / count($arr_base);
    //                 array_push($trx, ['tanggal' => [$date_from, $date_to], 'att_nik' => $nik, 'arr_base' => count($arr_base), 'arr_clock' => count($arr_clock), 'persentase' => $persentase * 100]);
    //             }
    //         }
    //         // end recap
    //         // return response()->json( [ 'duration' => $duration ], 200 );
    //         return Excel::download(new AttendanceMultiSheetExport($att, $duration, $trx), 'Raw Data dan Duration dan Recap '.Carbon::parse($date_from)->format('d-m-Y').' sd '.Carbon::parse($date_to)->format('d-m-Y').'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    //         // return response()->json( [ 'att' => $att ], 200 );
    //     }
    //     // end
    //     // return response()->json( [ 'att' => $att ], 200 );
    //     // $this->into_debug(['nomor'=>7, 'modul'=>Route::getCurrentRoute()->getActionName(), 'url'=>$request->fullUrl()]);
    //     // end filter
    //     return view( 'attendance.index', compact( 'str_att_nik_nama', 'att_nik_nama', 'att', 'duration', 'att_type', 'att_status', 'att_data_src', 'perusahaan', 'wg_id', 'arr_att_type', 'arr_att_status', 'arr_att_data_src', 'date_from', 'date_to', 'arr_perusahaan', 'arr_wg_id' ) );
    //     // return view( 'attendance.index', compact( 'att', 'duration', 'att_type', 'att_status', 'att_data_src', 'wg_id', 'arr_att_type', 'arr_att_status', 'arr_att_data_src', 'date_from', 'date_to', 'arr_wg_id' ) );
    // }

    public function index(Request $request)
    {
        $user  = auth()->user();
        $limit = 25000;
    
        // ---------- dropdown karyawan ----------
        $att_nik_nama = DB::table('trx_tbl_attendance as a')->select('a.att_nik', 'b.nama')->distinct()
            ->leftJoin('mtr_tbl_karyawan as b', 'a.att_nik', '=', 'b.nik')
            ->when(Gate::allows('hc_nkp'), fn($q) => $q->whereIn('a.att_nik', (new Karyawan)->getNikNkp()))
            ->when($user->userrole == 'nhc', fn($q) => $q->whereIn('a.att_nik', $this->nikTim($user)))
            ->orderBy('b.nama')->get();
    
        // ---------- opsi filter (distinct di tabel besar = full scan, jadi di-cache 1 jam) ----------
        $att_type     = Cache::remember('att_type_opts', 3600, fn() => Attendance::distinct()->orderBy('att_type')->pluck('att_type')->all());
        $att_status   = Cache::remember('att_status_opts', 3600, fn() => Attendance::distinct()->orderBy('att_status')->pluck('att_status')->all());
        $att_data_src = Cache::remember('att_data_src_opts', 3600, fn() => Attendance::distinct()->orderBy('att_data_src')->pluck('att_data_src')->all());
        $perusahaan   = Perusahaan::whereNotIn('id', [0, 3])->orderBy('nama')->get(); // 0=NA, 3=SMS Project
        $wg_id        = WorkingGroup::orderBy('id')->get();
    
        // ---------- state form filter ----------
        $isFilter         = $request->boolean('filter');
        $date_from        = $isFilter ? $request->date_from : '';
        $date_to          = $isFilter ? $request->date_to : '';
        $str_att_nik_nama = $isFilter ? (string) $request->str_att_nik_nama : '';
        $arr_att_type     = $isFilter ? $request->input('att_type', []) : [];
        $arr_att_status   = $isFilter ? $request->input('att_status', []) : [];
        $arr_att_data_src = $isFilter ? $request->input('att_data_src', []) : [];
        $arr_perusahaan   = $isFilter ? $request->input('perusahaan', []) : [];
        $arr_wg_id        = $isFilter ? $request->input('wg_id', []) : [];
    
        // ---------- data ----------
        $att = collect();
        $duration = [];
        $wdClockin = collect();
        $selisihEO = collect();
    
        if ($isFilter || $user->userrole == 'nhc') {
            // 1 query + 2 query eager load (pengganti Karyawan::where()->first() & relasi lazy per baris di Blade)
            $att = $this->filteredQuery($request)
                ->with(['_att_nik', '_fp_trx'])
                ->orderByDesc('att_timestamp')->limit($limit)->get();
    
            [$wdClockin, $selisihEO] = $this->delayLookup($att);
            $duration = $this->hitungDurasi($request, $limit);
        }
    
        return view('attendance.index', compact(
            'str_att_nik_nama', 'att_nik_nama', 'att', 'duration', 'att_type', 'att_status', 'att_data_src',
            'perusahaan', 'wg_id', 'arr_att_type', 'arr_att_status', 'arr_att_data_src', 'date_from', 'date_to',
            'arr_perusahaan', 'arr_wg_id', 'wdClockin', 'selisihEO'
        ));
    }
    
    // Semua logika filter + role dalam satu tempat (pengganti if/else yang berulang)
    private function filteredQuery(Request $request)
    {
        $user = auth()->user();
        $q = Attendance::query();
    
        // tanpa filter: nhc lihat bulan berjalan milik sendiri + bawahan, HC kosong (sama dengan kode lama)
        if (!$request->boolean('filter')) {
            if ($user->userrole !== 'nhc') return $q->whereRaw('1 = 0');
            return $q->whereIn('att_nik', $this->nikTim($user))
                    ->whereBetween('att_timestamp', [now()->startOfMonth(), now()->endOfMonth()]);
        }
    
        $q->whereIn('att_type', $request->input('att_type', []))
        ->whereIn('att_status', $request->input('att_status', []))
        ->whereIn('att_data_src', $request->input('att_data_src', []))
        ->whereIn('att_nik', Karyawan::select('nik')->whereIn('wg_id', $request->input('wg_id', []))) // subquery
        ->whereBetween('att_date', [$request->date_from, $request->date_to]);
    
        if ($user->userrole == 'nhc') {
            $q->whereIn('att_nik', $this->nikTim($user));
        } else {
            $q->whereIn('att_nik', Karyawan::select('nik')->whereIn('kode_perusahaan', $request->input('perusahaan', [])));
            if (Gate::allows('hc_nkp')) $q->whereIn('att_nik', (new Karyawan)->getNikNkp());
        }
    
        return $q->when($request->filled('str_att_nik_nama'), fn($w) => $w->where('att_nik', $request->str_att_nik_nama));
    }
    
    private function nikTim($user): array
    {
        return Karyawan::where('layer1', $user->nik)->pluck('nik')->push($user->nik)->all();
    }
    
    // Pengganti WorkingDay::find() dan query TIMEDIFF Early-out per baris di Blade (2 query total)
    private function delayLookup($att): array
    {
        $wdClockin = WorkingDay::whereIn('id', $att->where('wg_id', '>', 1)->pluck('att_wd_ot_id')->filter()->unique()->values())
            ->pluck('clockin', 'id');
    
        $eo = $att->filter(fn($r) => $r->att_type == 'Clock-out' && $r->att_status == 'Early-out' && $r->att_data_src == 'Regular' && $r->wg_id == 1);
    
        $selisihEO = $eo->isEmpty() ? collect() : Attendance::selectRaw("att_nik, att_date, TIMEDIFF('09:00:00', TIMEDIFF(
                    MAX(CASE WHEN att_type = 'Clock-out' THEN att_timestamp END),
                    MAX(CASE WHEN att_type = 'Clock-in'  THEN att_timestamp END))) as selisih_waktu")
            ->whereIn('att_nik', $eo->pluck('att_nik')->unique()->values())
            ->whereIn('att_date', $eo->pluck('att_date')->unique()->values())
            ->whereIn('att_type', ['Clock-in', 'Clock-out'])
            ->groupBy('att_nik', 'att_date')->get()
            ->mapWithKeys(fn($x) => ["{$x->att_nik}|{$x->att_date}" => $x->selisih_waktu]);
    
        return [$wdClockin, $selisihEO];
    }
    
    // Pengganti 2 loop foreach (Regular & Overtime) yang menjalankan 2-3 query per baris: sekarang 3 query total
    private function hitungDurasi(Request $request, int $limit): array
    {
        $ins = $this->filteredQuery($request)
            ->where('att_type', 'Clock-in')->whereIn('att_data_src', ['Regular', 'Overtime'])
            ->orderByDesc('att_data_src')->orderBy('id') // Regular dulu, lalu Overtime (urutan sama dengan kode lama)
            ->limit($limit)
            ->get(['id', 'att_nik', 'att_date', 'att_wd_ot_id', 'att_data_src', 'att_property', 'att_timestamp']);
    
        if ($ins->isEmpty()) return [];
    
        $niks = $ins->pluck('att_nik')->unique()->values();
    
        // query 2: karyawan (pengganti Karyawan::where(['nik'=>..., 'wg_id'=>1])->exists() per baris + lookup nama di Blade)
        $kary = Karyawan::whereIn('nik', $niks)->get(['nik', 'nama', 'alamat_kantor', 'wg_id'])->keyBy('nik');
    
        // query 3: semua Clock-out yang mungkin jadi pasangan (pengganti ->first() per baris)
        $outs = Attendance::where('att_type', 'Clock-out')->whereIn('att_data_src', ['Regular', 'Overtime'])
            ->whereIn('att_nik', $niks)
            ->where(fn($w) => $w->whereBetween('att_date', [$ins->min('att_date'), $ins->max('att_date')])
                                ->orWhereIn('att_wd_ot_id', $ins->pluck('att_wd_ot_id')->filter()->unique()->values()))
            ->orderBy('id') // keyBy menyimpan yang terakhir = id terbesar (sama dengan orderBy id desc ->first())
            ->get(['att_nik', 'att_date', 'att_wd_ot_id', 'att_data_src', 'att_timestamp']);
    
        $byDate = $outs->keyBy(fn($o) => "{$o->att_data_src}|{$o->att_nik}|{$o->att_date}");
        $byWd   = $outs->whereNotNull('att_wd_ot_id')->keyBy(fn($o) => "{$o->att_data_src}|{$o->att_nik}|{$o->att_wd_ot_id}");
    
        $duration = [];
        foreach ($ins as $in) {
            $k = $kary->get($in->att_nik);
    
            // reguler (wg_id = 1): pasangan berdasarkan tanggal; non reguler: berdasarkan att_wd_ot_id
            $out = ($k?->wg_id == 1)
                ? $byDate->get("{$in->att_data_src}|{$in->att_nik}|{$in->att_date}")
                : ($in->att_wd_ot_id ? $byWd->get("{$in->att_data_src}|{$in->att_nik}|{$in->att_wd_ot_id}") : null);
    
            if ($out) {
                $duration[] = [
                    'att_property'  => $in->att_property,
                    'att_date'      => $in->att_date,
                    'att_nik'       => $in->att_nik,
                    'att_data_src'  => $in->att_data_src,
                    'Clock-in'      => $in->att_timestamp,
                    'Clock-out'     => $out->att_timestamp,
                    'durasi'        => gmdate('H:i:s', abs(strtotime($out->att_timestamp) - strtotime($in->att_timestamp))),
                    'nama'          => $k?->nama,
                    'alamat_kantor' => $k?->alamat_kantor,
                ];
            }
        }
    
        return $duration;
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
    
    public function into_debug(array $arr)
    {
        $data = New TrxDebug;
        $data->nomor = $arr['nomor'];
        $data->modul = $arr['modul'];
        $data->url = $arr['url'];
        $data->save();
    }

    // get tanggal tanggal
    public function dateRange($first, $last, $step = '+1 day', $format = 'Y-m-d')
    {
        $dates = [];
        $current = strtotime($first);
        $last = strtotime($last);
        while ($current <= $last) {
            $dates[] = date($format, $current);
            $current = strtotime($step, $current);
        }
        return $dates;
    }

    // cek apakah hari ini sabtu minggu
    public function isWeekend($date)
    {
        $weekDay = date('w', strtotime($date));
        return $weekDay == 0 || $weekDay == 6;
    }

    // cek apakah hari ini adalah hari libur nasional
    public function isHoliday($date)
    {
        $where = ['tanggal' => $date];
        return Holiday::where($where)->exists();
    }

    // cek apakah hari ini adalah hari cutinya
    public function isCuti($nik, $date)
    {
        $where = ['nik_karyawan' => $nik];
        return TrxCuti::where($where)
            ->whereIn('status_cuti', [4, 6])
            ->whereDate('tanggal_mulai', '<=', $date)
            ->whereDate('tanggal_akhir', '>=', $date)
            ->exists();
    }

    // cek apakah hari ini adalah hari izin sakitnya
    public function isSakit($nik, $date)
    {
        $where = ['nik_karyawan' => $nik];
        return TrxSakit::where($where)
            ->whereIn('status_sakit', [4, 6])
            ->whereDate('tanggal_mulai', '<=', $date)
            ->whereDate('tanggal_akhir', '>=', $date)
            ->exists();
    }

    // cek apakah hari ini Clock-in
    public function isClockin($nik, $date)
    {
        $where = ['att_nik' => $nik, 'att_date' => $date, 'att_type' => 'Clock-in', 'att_data_src' => 'Regular'];
        return Attendance::where($where)->exists();
    }
}
