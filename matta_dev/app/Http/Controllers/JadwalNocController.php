<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\WorkingDay;
use App\Models\Karyawan;
use App\Models\JadwalNocShift;
use App\Models\JadwalNoc;
use Carbon\Carbon;
use DB;

class JadwalNocController extends Controller
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
        // 10 = NOC Group
        $where = ['wg_id' => 10];
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
        return view( 'master.jadwal.noc.index', compact( 'trx', 'no', 'date_from', 'date_to', 'nik_karyawan', 'arr_nik_karyawan' ) );
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
        // 10 = NOC Group
        $status = 'create';
        $where = ['is_aktif' => 1, 'wg_id'=>10];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        $shift = JadwalNocShift::orderBy('id', 'asc')->get();
        return view( 'master.jadwal.noc.upsert', compact('karyawan', 'shift', 'status') );
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
        $trx = New JadwalNoc;
        $trx->nik = $request->nik;
        $trx->id_shift = $request->id_shift;
        // $trx->tanggal_awal = $request->tanggal_awal;
        // $trx->tanggal_akhir = $request->tanggal_akhir;
        $trx->save();
        return redirect( 'jadwalnoc/0' )->with( 'msg', 'Data is saved successfully.' );
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
        // 10 = NOC Group
        $trx = JadwalNoc::orderBy( 'id_shift', 'asc' )->get();
        return view( 'master.jadwal.noc._index', compact('trx', 'no') );
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
        $trx = JadwalNoc::find( $id );
        $where = ['is_aktif' => 1, 'wg_id'=>10];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        $shift = JadwalNocShift::orderBy('id', 'asc')->get();
        return view( 'master.jadwal.noc.upsert', compact( 'trx', 'karyawan', 'shift', 'status' ) );
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
        $trx = JadwalNoc::find( $id );
        $trx->nik = $request->nik;
        $trx->id_shift = $request->id_shift;
        // $trx->tanggal_awal = $request->tanggal_awal;
        // $trx->tanggal_akhir = $request->tanggal_akhir;
        $trx->update();
        return redirect( 'jadwalnoc/0' )->with( 'msg', 'Data is saved successfully.' );
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
        $trx = JadwalNoc::find( $id );
        $trx->delete();
        return redirect( 'jadwalnoc/0' )->with( 'msg', 'Data is deleted successfully.' );
    }
    
    public function generate(Request $request){
        DB::update(DB::raw("UPDATE mtr_tbl_jadwal_noc SET tanggal_awal='".$request->tanggal_awal."',tanggal_akhir='".$request->tanggal_akhir."'"));
        $arr = JadwalNoc::orderBy( 'id_shift', 'asc' )->get();
        // return response()->json( [ 'arr' => $arr ], 200 );
        $jumlah = 0;
        $wg_id = 10;
        $wn_id = 1;
        $startP = '08:00:00';
        $endP = '16:00:00';
        $startS = '16:00:00';
        $endS = '22:00:00';
        $startM = '22:00:00';
        $endM = '08:00:00';
        // echo $wg_id;
        // echo '<br/>';
        // echo $wn_id;
        // echo '<br/>';
        for ($x = 0; $x < sizeof($arr); $x++) {
        // 	"1"	"Shift 1 ke-1"	"08:00:00"	"16:00:00"
            if($arr[$x]['id_shift']==1){
                $loop_shift = array('1-1','1-2','2','3-1','3-2','L','L','L');
                $uk_nik = $arr[$x]['nik'];
                $this->deleteData($uk_nik, $request->tanggal_awal, $request->tanggal_akhir);
                // echo '#'.$uk_nik.'-'.getNama($arr[$x]['nik']);
                // echo '<br/>';
                $i = 0;
                while (strtotime($arr[$x]['tanggal_awal']) <= strtotime($arr[$x]['tanggal_akhir'])) {
                    $cal_date = $arr[$x]['tanggal_awal'];
                    $wd_start_datetime = '';
                    $wd_finish_datetime = '';
                    if($loop_shift[$i]=='1-1'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startP;
                        $wd_finish_datetime = $arr[$x]['tanggal_awal'].' '.$endP;
                    }elseif($loop_shift[$i]=='1-2'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startP;
                        $wd_finish_datetime = $arr[$x]['tanggal_awal'].' '.$endP;
                    }elseif($loop_shift[$i]=='2'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startS;
                        $wd_finish_datetime = $arr[$x]['tanggal_awal'].' '.$endS;
                    }elseif($loop_shift[$i]=='3-1'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startM;
                        $wd_finish_datetime = date ("Y-m-d", strtotime("+1 days", strtotime($arr[$x]['tanggal_awal']))).' '.$endM;
                    }elseif($loop_shift[$i]=='3-2'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startM;
                        $wd_finish_datetime = date ("Y-m-d", strtotime("+1 days", strtotime($arr[$x]['tanggal_awal']))).' '.$endM;
                    }
                    $wd_bookout_closing = date ("Y-m-d H:i:s", strtotime("+3 hours", strtotime($wd_finish_datetime)));
                    $wd_bookin_prepare = date ("Y-m-d H:i:s", strtotime("-1 hours", strtotime($wd_start_datetime)));
                    // if(in_array($loop_shift[$i], $loop_shift)){
                    if($loop_shift[$i]!='L'){
                        $jumlah++;
                        // echo '<br/>';
                        // echo $cal_date;
                        // echo '<br/>';
                        // echo $loop_shift[$i];
                        // echo '<br/>';
                        // echo $wd_start_datetime;
                        // echo '<br/>';
                        // echo $wd_finish_datetime;
                        // echo '<br/>';
                        // echo $wd_bookout_closing;
                        // echo '<br/>';
                        // echo $wd_bookin_prepare;
                        // echo '<br/>';
                        if($loop_shift[$i] != '2'){
                            // $this->deleteData($wg_id, $cal_date, $uk_nik);
                        }
                        $this->insertData($wg_id, $wn_id, $cal_date, $uk_nik, $wd_start_datetime, $wd_finish_datetime, $wd_bookout_closing, $wd_bookin_prepare);
                    }
                    if($loop_shift[$i] != '1-2'){
                        $arr[$x]['tanggal_awal'] = date ("Y-m-d", strtotime("+1 days", strtotime($arr[$x]['tanggal_awal'])));
                    }
                    if($i==sizeof($loop_shift)-1){
                        $i = 0;
                    }else{
                        $i++;
                    }
                }
                // echo '<br/>';
            }
        // 	"2"	"Shift 1 ke-2"	"08:00:00"	"16:00:00"
            elseif($arr[$x]['id_shift']==2){
                $loop_shift = array('1-2','2','3-1','3-2','L','L','L','1-1');
                $uk_nik = $arr[$x]['nik'];
                $this->deleteData($uk_nik, $request->tanggal_awal, $request->tanggal_akhir);
                // echo '#'.$uk_nik.'-'.getNama($arr[$x]['nik']);
                // echo '<br/>';
                $i = 0;
                while (strtotime($arr[$x]['tanggal_awal']) <= strtotime($arr[$x]['tanggal_akhir'])) {
                    $cal_date = $arr[$x]['tanggal_awal'];
                    $wd_start_datetime = '';
                    $wd_finish_datetime = '';
                    if($loop_shift[$i]=='1-1'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startP;
                        $wd_finish_datetime = $arr[$x]['tanggal_awal'].' '.$endP;
                    }elseif($loop_shift[$i]=='1-2'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startP;
                        $wd_finish_datetime = $arr[$x]['tanggal_awal'].' '.$endP;
                    }elseif($loop_shift[$i]=='2'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startS;
                        $wd_finish_datetime = $arr[$x]['tanggal_awal'].' '.$endS;
                    }elseif($loop_shift[$i]=='3-1'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startM;
                        $wd_finish_datetime = date ("Y-m-d", strtotime("+1 days", strtotime($arr[$x]['tanggal_awal']))).' '.$endM;
                    }elseif($loop_shift[$i]=='3-2'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startM;
                        $wd_finish_datetime = date ("Y-m-d", strtotime("+1 days", strtotime($arr[$x]['tanggal_awal']))).' '.$endM;
                    }
                    $wd_bookout_closing = date ("Y-m-d H:i:s", strtotime("+3 hours", strtotime($wd_finish_datetime)));
                    $wd_bookin_prepare = date ("Y-m-d H:i:s", strtotime("-1 hours", strtotime($wd_start_datetime)));
                    if($loop_shift[$i]!='L'){
                        $jumlah++;
                        if($loop_shift[$i] != '2'){
                            // $this->deleteData($wg_id, $cal_date, $uk_nik);
                        }
                        $this->insertData($wg_id, $wn_id, $cal_date, $uk_nik, $wd_start_datetime, $wd_finish_datetime, $wd_bookout_closing, $wd_bookin_prepare);
                    }
                    if($loop_shift[$i] != '1-2'){
                        $arr[$x]['tanggal_awal'] = date ("Y-m-d", strtotime("+1 days", strtotime($arr[$x]['tanggal_awal'])));
                    }
                    if($i==sizeof($loop_shift)-1){
                        $i = 0;
                    }else{
                        $i++;
                    }
                }
                // echo '<br/>';
            }
        // 	"4"	"Shift 3 ke-1"	"22:00:00"	"08:00:00"
            elseif($arr[$x]['id_shift']==4){
                $loop_shift = array('3-1','3-2','L','L','L','1-1','1-2','2');
                $uk_nik = $arr[$x]['nik'];
                $this->deleteData($uk_nik, $request->tanggal_awal, $request->tanggal_akhir);
                // echo '#'.$uk_nik.'-'.getNama($arr[$x]['nik']);
                // echo '<br/>';
                $i = 0;
                while (strtotime($arr[$x]['tanggal_awal']) <= strtotime($arr[$x]['tanggal_akhir'])) {
                    $cal_date = $arr[$x]['tanggal_awal'];
                    $wd_start_datetime = '';
                    $wd_finish_datetime = '';
                    if($loop_shift[$i]=='1-1'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startP;
                        $wd_finish_datetime = $arr[$x]['tanggal_awal'].' '.$endP;
                    }elseif($loop_shift[$i]=='1-2'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startP;
                        $wd_finish_datetime = $arr[$x]['tanggal_awal'].' '.$endP;
                    }elseif($loop_shift[$i]=='2'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startS;
                        $wd_finish_datetime = $arr[$x]['tanggal_awal'].' '.$endS;
                    }elseif($loop_shift[$i]=='3-1'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startM;
                        $wd_finish_datetime = date ("Y-m-d", strtotime("+1 days", strtotime($arr[$x]['tanggal_awal']))).' '.$endM;
                    }elseif($loop_shift[$i]=='3-2'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startM;
                        $wd_finish_datetime = date ("Y-m-d", strtotime("+1 days", strtotime($arr[$x]['tanggal_awal']))).' '.$endM;
                    }
                    $wd_bookout_closing = date ("Y-m-d H:i:s", strtotime("+3 hours", strtotime($wd_finish_datetime)));
                    $wd_bookin_prepare = date ("Y-m-d H:i:s", strtotime("-1 hours", strtotime($wd_start_datetime)));
                    if($loop_shift[$i]!='L'){
                        $jumlah++;
                        if($loop_shift[$i] != '2'){
                            // $this->deleteData($wg_id, $cal_date, $uk_nik);
                        }
                        $this->insertData($wg_id, $wn_id, $cal_date, $uk_nik, $wd_start_datetime, $wd_finish_datetime, $wd_bookout_closing, $wd_bookin_prepare);
                    }
                    if($loop_shift[$i] != '1-2'){
                        $arr[$x]['tanggal_awal'] = date ("Y-m-d", strtotime("+1 days", strtotime($arr[$x]['tanggal_awal'])));
                    }
                    if($i==sizeof($loop_shift)-1){
                        $i = 0;
                    }else{
                        $i++;
                    }
                }
                // echo '<br/>';
            }
        // 	"5"	"Shift 3 ke-2"	"22:00:00"	"08:00:00"
            elseif($arr[$x]['id_shift']==5){
                $loop_shift = array('3-2','L','L','L','1-1','1-2','2','3-1');
                $uk_nik = $arr[$x]['nik'];
                $this->deleteData($uk_nik, $request->tanggal_awal, $request->tanggal_akhir);
                // echo '#'.$uk_nik.'-'.getNama($arr[$x]['nik']);
                // echo '<br/>';
                $i = 0;
                while (strtotime($arr[$x]['tanggal_awal']) <= strtotime($arr[$x]['tanggal_akhir'])) {
                    $cal_date = $arr[$x]['tanggal_awal'];
                    $wd_start_datetime = '';
                    $wd_finish_datetime = '';
                    if($loop_shift[$i]=='1-1'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startP;
                        $wd_finish_datetime = $arr[$x]['tanggal_awal'].' '.$endP;
                    }elseif($loop_shift[$i]=='1-2'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startP;
                        $wd_finish_datetime = $arr[$x]['tanggal_awal'].' '.$endP;
                    }elseif($loop_shift[$i]=='2'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startS;
                        $wd_finish_datetime = $arr[$x]['tanggal_awal'].' '.$endS;
                    }elseif($loop_shift[$i]=='3-1'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startM;
                        $wd_finish_datetime = date ("Y-m-d", strtotime("+1 days", strtotime($arr[$x]['tanggal_awal']))).' '.$endM;
                    }elseif($loop_shift[$i]=='3-2'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startM;
                        $wd_finish_datetime = date ("Y-m-d", strtotime("+1 days", strtotime($arr[$x]['tanggal_awal']))).' '.$endM;
                    }
                    $wd_bookout_closing = date ("Y-m-d H:i:s", strtotime("+3 hours", strtotime($wd_finish_datetime)));
                    $wd_bookin_prepare = date ("Y-m-d H:i:s", strtotime("-1 hours", strtotime($wd_start_datetime)));
                    if($loop_shift[$i]!='L'){
                        $jumlah++;
                        if($loop_shift[$i] != '2'){
                            // $this->deleteData($wg_id, $cal_date, $uk_nik);
                        }
                        $this->insertData($wg_id, $wn_id, $cal_date, $uk_nik, $wd_start_datetime, $wd_finish_datetime, $wd_bookout_closing, $wd_bookin_prepare);
                    }
                    if($loop_shift[$i] != '1-2'){
                        $arr[$x]['tanggal_awal'] = date ("Y-m-d", strtotime("+1 days", strtotime($arr[$x]['tanggal_awal'])));
                    }
                    if($i==sizeof($loop_shift)-1){
                        $i = 0;
                    }else{
                        $i++;
                    }
                }
                // echo '<br/>';
            }
        // 	"6"	"Libur ke-1"	"00:00:00"	"00:00:00"
            elseif($arr[$x]['id_shift']==6){
                $loop_shift = array('L','L','L','1-1','1-2','2','3-1','3-2');
                $uk_nik = $arr[$x]['nik'];
                $this->deleteData($uk_nik, $request->tanggal_awal, $request->tanggal_akhir);
                // echo '#'.$uk_nik.'-'.getNama($arr[$x]['nik']);
                // echo '<br/>';
                $i = 0;
                while (strtotime($arr[$x]['tanggal_awal']) <= strtotime($arr[$x]['tanggal_akhir'])) {
                    $cal_date = $arr[$x]['tanggal_awal'];
                    $wd_start_datetime = '';
                    $wd_finish_datetime = '';
                    if($loop_shift[$i]=='1-1'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startP;
                        $wd_finish_datetime = $arr[$x]['tanggal_awal'].' '.$endP;
                    }elseif($loop_shift[$i]=='1-2'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startP;
                        $wd_finish_datetime = $arr[$x]['tanggal_awal'].' '.$endP;
                    }elseif($loop_shift[$i]=='2'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startS;
                        $wd_finish_datetime = $arr[$x]['tanggal_awal'].' '.$endS;
                    }elseif($loop_shift[$i]=='3-1'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startM;
                        $wd_finish_datetime = date ("Y-m-d", strtotime("+1 days", strtotime($arr[$x]['tanggal_awal']))).' '.$endM;
                    }elseif($loop_shift[$i]=='3-2'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startM;
                        $wd_finish_datetime = date ("Y-m-d", strtotime("+1 days", strtotime($arr[$x]['tanggal_awal']))).' '.$endM;
                    }
                    $wd_bookout_closing = date ("Y-m-d H:i:s", strtotime("+3 hours", strtotime($wd_finish_datetime)));
                    $wd_bookin_prepare = date ("Y-m-d H:i:s", strtotime("-1 hours", strtotime($wd_start_datetime)));
                    if($loop_shift[$i]!='L'){
                        $jumlah++;
                        if($loop_shift[$i] != '2'){
                            // $this->deleteData($wg_id, $cal_date, $uk_nik);
                        }
                        $this->insertData($wg_id, $wn_id, $cal_date, $uk_nik, $wd_start_datetime, $wd_finish_datetime, $wd_bookout_closing, $wd_bookin_prepare);
                    }
                    if($loop_shift[$i] != '1-2'){
                        $arr[$x]['tanggal_awal'] = date ("Y-m-d", strtotime("+1 days", strtotime($arr[$x]['tanggal_awal'])));
                    }
                    if($i==sizeof($loop_shift)-1){
                        $i = 0;
                    }else{
                        $i++;
                    }
                }
                // echo '<br/>';
            }
        // 	"7"	"Libur ke-2"	"00:00:00"	"00:00:00"
            elseif($arr[$x]['id_shift']==7){
                $loop_shift = array('L','L','1-1','1-2','2','3-1','3-2','L');
                $uk_nik = $arr[$x]['nik'];
                $this->deleteData($uk_nik, $request->tanggal_awal, $request->tanggal_akhir);
                // echo '#'.$uk_nik.'-'.getNama($arr[$x]['nik']);
                // echo '<br/>';
                $i = 0;
                while (strtotime($arr[$x]['tanggal_awal']) <= strtotime($arr[$x]['tanggal_akhir'])) {
                    $cal_date = $arr[$x]['tanggal_awal'];
                    $wd_start_datetime = '';
                    $wd_finish_datetime = '';
                    if($loop_shift[$i]=='1-1'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startP;
                        $wd_finish_datetime = $arr[$x]['tanggal_awal'].' '.$endP;
                    }elseif($loop_shift[$i]=='1-2'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startP;
                        $wd_finish_datetime = $arr[$x]['tanggal_awal'].' '.$endP;
                    }elseif($loop_shift[$i]=='2'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startS;
                        $wd_finish_datetime = $arr[$x]['tanggal_awal'].' '.$endS;
                    }elseif($loop_shift[$i]=='3-1'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startM;
                        $wd_finish_datetime = date ("Y-m-d", strtotime("+1 days", strtotime($arr[$x]['tanggal_awal']))).' '.$endM;
                    }elseif($loop_shift[$i]=='3-2'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startM;
                        $wd_finish_datetime = date ("Y-m-d", strtotime("+1 days", strtotime($arr[$x]['tanggal_awal']))).' '.$endM;
                    }
                    $wd_bookout_closing = date ("Y-m-d H:i:s", strtotime("+3 hours", strtotime($wd_finish_datetime)));
                    $wd_bookin_prepare = date ("Y-m-d H:i:s", strtotime("-1 hours", strtotime($wd_start_datetime)));
                    if($loop_shift[$i]!='L'){
                        $jumlah++;
                        if($loop_shift[$i] != '2'){
                            // $this->deleteData($wg_id, $cal_date, $uk_nik);
                        }
                        $this->insertData($wg_id, $wn_id, $cal_date, $uk_nik, $wd_start_datetime, $wd_finish_datetime, $wd_bookout_closing, $wd_bookin_prepare);
                    }
                    if($loop_shift[$i] != '1-2'){
                        $arr[$x]['tanggal_awal'] = date ("Y-m-d", strtotime("+1 days", strtotime($arr[$x]['tanggal_awal'])));
                    }
                    if($i==sizeof($loop_shift)-1){
                        $i = 0;
                    }else{
                        $i++;
                    }
                }
                // echo '<br/>';
            }
        // 	"8"	"Libur ke-3"	"00:00:00"	"00:00:00"
            elseif($arr[$x]['id_shift']==8){
                $loop_shift = array('L','1-1','1-2','2','3-1','3-2','L','L');
                $uk_nik = $arr[$x]['nik'];
                $this->deleteData($uk_nik, $request->tanggal_awal, $request->tanggal_akhir);
                // echo '#'.$uk_nik.'-'.getNama($arr[$x]['nik']);
                // echo '<br/>';
                $i = 0;
                while (strtotime($arr[$x]['tanggal_awal']) <= strtotime($arr[$x]['tanggal_akhir'])) {
                    $cal_date = $arr[$x]['tanggal_awal'];
                    $wd_start_datetime = '';
                    $wd_finish_datetime = '';
                    if($loop_shift[$i]=='1-1'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startP;
                        $wd_finish_datetime = $arr[$x]['tanggal_awal'].' '.$endP;
                    }elseif($loop_shift[$i]=='1-2'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startP;
                        $wd_finish_datetime = $arr[$x]['tanggal_awal'].' '.$endP;
                    }elseif($loop_shift[$i]=='2'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startS;
                        $wd_finish_datetime = $arr[$x]['tanggal_awal'].' '.$endS;
                    }elseif($loop_shift[$i]=='3-1'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startM;
                        $wd_finish_datetime = date ("Y-m-d", strtotime("+1 days", strtotime($arr[$x]['tanggal_awal']))).' '.$endM;
                    }elseif($loop_shift[$i]=='3-2'){
                        $wd_start_datetime = $arr[$x]['tanggal_awal'].' '.$startM;
                        $wd_finish_datetime = date ("Y-m-d", strtotime("+1 days", strtotime($arr[$x]['tanggal_awal']))).' '.$endM;
                    }
                    $wd_bookout_closing = date ("Y-m-d H:i:s", strtotime("+3 hours", strtotime($wd_finish_datetime)));
                    $wd_bookin_prepare = date ("Y-m-d H:i:s", strtotime("-1 hours", strtotime($wd_start_datetime)));
                    if($loop_shift[$i]!='L'){
                        $jumlah++;
                        if($loop_shift[$i] != '2'){
                            // $this->deleteData($wg_id, $cal_date, $uk_nik);
                        }
                        $this->insertData($wg_id, $wn_id, $cal_date, $uk_nik, $wd_start_datetime, $wd_finish_datetime, $wd_bookout_closing, $wd_bookin_prepare);
                    }
                    if($loop_shift[$i] != '1-2'){
                        $arr[$x]['tanggal_awal'] = date ("Y-m-d", strtotime("+1 days", strtotime($arr[$x]['tanggal_awal'])));
                    }
                    if($i==sizeof($loop_shift)-1){
                        $i = 0;
                    }else{
                        $i++;
                    }
                }
                // echo '<br/>';
            }
        }
        // $trx = $this->deleteData(2, 'CV0016203', '2020-09-07');
        // return response()->json( [ 'arr' => $arr ], 200 );
        return redirect( 'jadwalnoc' )->with( 'msg', 'Data is saved successfully.' );
    }
    
    // public function deleteData($wg_id, $working_date, $nik){
    //     return DB::delete(DB::raw("DELETE FROM trx_tbl_working_day WHERE wg_id=".$wg_id." AND nik='".$nik."' AND working_date='".$working_date."'"));
    // }
    
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
