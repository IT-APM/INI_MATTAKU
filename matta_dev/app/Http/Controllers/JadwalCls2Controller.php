<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\WorkingDay;
use App\Models\Karyawan;
use App\Models\JadwalCls2Shift;
use App\Models\JadwalCls2;
use Carbon\Carbon;
use DB;

class JadwalCls2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $no = 0;
        $tahun = Carbon::parse(now())->format('Y');
        // 14 = CLS 2 Group
        $where = ['wg_id' => 14];
        $trx = WorkingDay::where($where)->whereYear('working_date', $tahun)->orderBy( 'working_date', 'desc' )->orderBy( 'clockin', 'asc' )->get();
        // filter parameter
        $_arr_nik_karyawan = WorkingDay::select('nik')->where($where)->distinct()->get();
        $arr_nik_karyawan = array();
        $date_from = '';
        $date_to = '';
        foreach ($_arr_nik_karyawan as $data){
            array_push($arr_nik_karyawan, $data->nik);
        }
        $nik_karyawan = Karyawan::select('nik','nama')->whereIn('nik', $arr_nik_karyawan)->orderBy('nama', 'asc')->get();
        // return response()->json( [ 'nik_karyawan' => $nik_karyawan ], 200 );
        // start filter
        if($request->filter == true){
            $_nik_karyawan = $request->input('nik_karyawan', []);
            $date_from = $request->date_from;
            $date_to = $request->date_to;
            // 
            if(count($_nik_karyawan) > 0){
                $arr_nik_karyawan = $_nik_karyawan;
            }
            else{
                $arr_nik_karyawan = array();
            }
            $trx = WorkingDay::where($where)->whereIn('nik', $arr_nik_karyawan)->whereBetween('working_date', [$date_from, $date_to])->orderBy( 'working_date', 'desc' )->orderBy( 'clockin', 'asc' )->get();
        }else{
            $arr_nik_karyawan = array();
        }
        // end filter
        return view( 'master.jadwal.cls2.index', compact( 'trx', 'no', 'date_from', 'date_to', 'nik_karyawan', 'arr_nik_karyawan' ) );
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
        // 14 = CLS 2 Group
        $status = 'create';
        $where = ['is_aktif' => 1, 'wg_id'=>14];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        $shift = JadwalCls2Shift::orderBy('id', 'asc')->get();
        return view( 'master.jadwal.cls2.upsert', compact('karyawan', 'shift', 'status') );
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
        // return response()->json( [ 'request' => $request->all() ], 200 );
        $trx = New JadwalCls2;
        $trx->nik = $request->nik;
        $trx->id_shift = $request->id_shift;
        // $trx->tanggal_awal = $request->tanggal_awal;
        // $trx->tanggal_akhir = $request->tanggal_akhir;
        $trx->save();
        return redirect( 'jadwalcls2/0' )->with( 'msg', 'Data is saved successfully.' );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $no = 0;
        // 14 = CLS 2 Group
        $trx = JadwalCls2::orderBy( 'id_shift', 'asc' )->get();
        return view( 'master.jadwal.cls2._index', compact('trx', 'no') );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $status = 'edit';
        $trx = JadwalCls2::find( $id );
        $where = ['is_aktif' => 1, 'wg_id'=>14];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        $shift = JadwalCls2Shift::orderBy('id', 'asc')->get();
        return view( 'master.jadwal.cls2.upsert', compact( 'trx', 'karyawan', 'shift', 'status' ) );
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
        $trx = JadwalCls2::find( $id );
        $trx->nik = $request->nik;
        $trx->id_shift = $request->id_shift;
        // $trx->tanggal_awal = $request->tanggal_awal;
        // $trx->tanggal_akhir = $request->tanggal_akhir;
        $trx->update();
        return redirect( 'jadwalcls2/0' )->with( 'msg', 'Data is saved successfully.' );
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
        $trx = JadwalCls2::find( $id );
        $trx->delete();
        return redirect( 'jadwalcls2/0' )->with( 'msg', 'Data is deleted successfully.' );
    }
    
    public function generate(Request $request){
        DB::update(DB::raw("UPDATE mtr_tbl_jadwal_cls2 SET tanggal_awal='".$request->tanggal_awal."',tanggal_akhir='".$request->tanggal_akhir."'"));
        $arr = JadwalCls2::orderBy( 'id_shift', 'asc' )->get();
        $jumlah = 0;
        $wg_id = 14;
        $wn_id = 1;
        $startP = '00:00:00';
        $endP = '08:00:00';
        $startS = '08:00:00';
        $endS = '16:00:00';
        $startM = '16:00:00';
        $endM = '00:00:00';
        for ($x = 0; $x < sizeof($arr); $x++) {
            $loop_shift = array();
            // 7 = Libur ke-1
            if($arr[$x]['id_shift']==7){
                $loop_shift = array('L','L','M','M','S','S','S','P');
            }
            // 8 = Libur ke-2
            elseif($arr[$x]['id_shift']==8){
                $loop_shift = array('L','M','M','S','S','S','P','L');
            }
            // 5 = Shift Malam ke-1
            elseif($arr[$x]['id_shift']==5){
                $loop_shift = array('M','M','S','S','S','P','L','L');
            }
            // 6 = Shift Malam ke-2
            elseif($arr[$x]['id_shift']==6){
                $loop_shift = array('M','S','S','S','P','L','L','M');
            }
            // 2 = Shift Siang ke-1
            elseif($arr[$x]['id_shift']==2){
                $loop_shift = array('S','S','S','P','L','L','M','M');
            }
            // 3 = Shift Siang ke-2
            elseif($arr[$x]['id_shift']==3){
                $loop_shift = array('S','S','P','L','L','M','M','S');
            }
            // 4 = Shift Siang ke-3
            elseif($arr[$x]['id_shift']==4){
                $loop_shift = array('S','P','L','L','M','M','S','S');
            }
            // 1 = Shift Pagi ke-1
            elseif($arr[$x]['id_shift']==1){
                $loop_shift = array('P','L','L','M','M','S','S','S');
            }
            $uk_nik = $arr[$x]['nik'];
            $this->deleteData($uk_nik, $request->tanggal_awal, $request->tanggal_akhir);
            $i = 0;
            while (strtotime($arr[$x]['tanggal_awal']) <= strtotime($arr[$x]['tanggal_akhir'])) {
                $cal_date = $arr[$x]['tanggal_awal'];
                $wd_start_datetime = '';
                $wd_finish_datetime = '';
                if($loop_shift[$i]=='P'){
                    $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startP;
                    $wd_finish_datetime = $arr[$x]['tanggal_awal'].' '.$endP;
                }elseif($loop_shift[$i]=='S'){
                    $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startS;
                    $wd_finish_datetime = $arr[$x]['tanggal_awal'].' '.$endS;
                }elseif($loop_shift[$i]=='M'){
                    $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startM;
                    $wd_finish_datetime = date ("Y-m-d", strtotime("+1 days", strtotime($arr[$x]['tanggal_awal']))).' '.$endM;
                }
                $wd_bookout_closing = date ("Y-m-d H:i:s", strtotime("+3 hours", strtotime($wd_finish_datetime)));
                $wd_bookin_prepare = date ("Y-m-d H:i:s", strtotime("-1 hours", strtotime($wd_start_datetime)));
                if($loop_shift[$i]!='L'){
                    $jumlah++;
                    $this->insertData($wg_id, $wn_id, $cal_date, $uk_nik, $wd_start_datetime, $wd_finish_datetime, $wd_bookout_closing, $wd_bookin_prepare);
                }
                $arr[$x]['tanggal_awal'] = date ("Y-m-d", strtotime("+1 days", strtotime($arr[$x]['tanggal_awal'])));
                if($i==sizeof($loop_shift)-1){
                    $i = 0;
                }else{
                    $i++;
                }
            }
        }
        return redirect( 'jadwalcls2' )->with( 'msg', 'Data is saved successfully.' );
    }
    
    public function deleteData($nik, $tanggal_awal, $tanggal_akhir){
        return DB::delete(DB::raw("DELETE FROM trx_tbl_working_day WHERE nik='".$nik."' AND working_date BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'"));
    }
    
    public function insertData($wg_id, $shift, $working_date, $nik, $clockin, $clockout, $end_clockout, $start_clockin){
        $trx = New WorkingDay;
        $trx->wg_id = $wg_id;
        $trx->shift = $shift;
        $trx->nik = $nik;
        $trx->working_date = $working_date;
        $trx->clockin = $clockin;
        $trx->clockout = $clockout;
        $trx->start_clockin = $start_clockin;
        $trx->end_clockin = $clockout;
        $trx->start_clockout = $clockout;
        $trx->end_clockout = $end_clockout;
        $trx->save();
    }
}
