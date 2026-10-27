<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use App\Models\Karyawan;
use App\Models\WorkingDay;
use App\Models\WorkingGroup;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class WorkingDayController extends Controller
{

    public function upload_jadwal(Request $request, $id)
    {
        // 22 = NOC 3 Group
        if($request->hasFile('template') && $id == 22){
            $request->validate([
                'template'=>'required|file|mimes:xls,xlsx|max:2048',
            ]);
            $path = $request->file('template')->getPathname();
            $data = Excel::toArray([], $path);
            $data0 = $data[0];
            $n_kolom = count($data[0][0]);
            $wn_id = 1;
            $startP = '08:00:00';
            $endP = '20:00:00';
            $startM = '20:00:00';
            $endM = '08:00:00';
            for ($x = 1; $x < count($data0); $x++) {
                // nik
                $nik = $data0[$x][2];
                // wg_id
                $wg_id = $data0[$x][3];
                // $data0[0][4] => tanggal
                for ($y = 4; $y < $n_kolom; $y++) {
                    $tanggal = Date::excelToDateTimeObject($data0[0][$y])->format('Y-m-d');
                    $this->deleteData($nik, $tanggal, $tanggal);
                    $wd_start_datetime = '';
                    $wd_finish_datetime = '';
                    if($data0[$x][$y]=='P'){
                        $wd_start_datetime = $tanggal.' '.$startP;
                        $wd_finish_datetime = $tanggal.' '.$endP;
                    }elseif($data0[$x][$y]=='M'){
                        $wd_start_datetime = $tanggal.' '.$startM;
                        $wd_finish_datetime = date ("Y-m-d", strtotime("+1 days", strtotime($tanggal))).' '.$endM;
                    }
                    $wd_bookout_closing = date ("Y-m-d H:i:s", strtotime("+3 hours", strtotime($wd_finish_datetime)));
                    $wd_bookin_prepare = date ("Y-m-d H:i:s", strtotime("-1 hours", strtotime($wd_start_datetime)));
                    if($data0[$x][$y]!='L'){
                        $this->insertData($wg_id, $wn_id, $tanggal, $nik, $wd_start_datetime, $wd_finish_datetime, $wd_bookout_closing, $wd_bookin_prepare);
                    }
                }
            }
            return redirect()->route('working.show', [$id, 'req'=>'view'])->with('msg', 'Data is saved successfully.');
        }
        // 15 = QA_TS
        elseif($request->hasFile('template') && $id == 15){
            $request->validate([
                'template'=>'required|file|mimes:xls,xlsx|max:2048',
            ]);
            $path = $request->file('template')->getPathname();
            $data = Excel::toArray([], $path);
            $data0 = $data[0];
            $n_kolom = count($data[0][0]);
            $wn_id = 1;
            $startP = '08:30:00';
            $endP = '18:00:00';
            $startS = '13:00:00';
            $endS = '22:00:00';
            $startM = '19:00:00';
            $endM = '03:00:00';
            for ($x = 1; $x < count($data0); $x++) {
                // nik
                $nik = $data0[$x][2];
                // wg_id
                $wg_id = $data0[$x][3];
                // $data0[0][4] => tanggal
                for ($y = 4; $y < $n_kolom; $y++) {
                    $tanggal = Date::excelToDateTimeObject($data0[0][$y])->format('Y-m-d');
                    $this->deleteData($nik, $tanggal, $tanggal);
                    $wd_start_datetime = '';
                    $wd_finish_datetime = '';
                    if($data0[$x][$y]=='P'){
                        $wd_start_datetime = $tanggal.' '.$startP;
                        $wd_finish_datetime = $tanggal.' '.$endP;
                    }elseif($data0[$x][$y]=='S'){
                        $wd_start_datetime = $tanggal.' '.$startS;
                        $wd_finish_datetime = $tanggal.' '.$endS;
                    }elseif($data0[$x][$y]=='M'){
                        $wd_start_datetime = $tanggal.' '.$startM;
                        $wd_finish_datetime = date ("Y-m-d", strtotime("+1 days", strtotime($tanggal))).' '.$endM;
                    }
                    $wd_bookout_closing = date ("Y-m-d H:i:s", strtotime("+3 hours", strtotime($wd_finish_datetime)));
                    $wd_bookin_prepare = date ("Y-m-d H:i:s", strtotime("-1 hours", strtotime($wd_start_datetime)));
                    if($data0[$x][$y]!='L'){
                        $this->insertData($wg_id, $wn_id, $tanggal, $nik, $wd_start_datetime, $wd_finish_datetime, $wd_bookout_closing, $wd_bookin_prepare);
                    }
                }
            }
            return redirect()->route('working.show', [$id, 'req'=>'view'])->with('msg', 'Data is saved successfully.');
        }
        // dd($request->all());
    }
    
    public function deleteData($nik, $tanggal_awal, $tanggal_akhir){
        WorkingDay::where('nik', $nik)->whereBetween('working_date', [$tanggal_awal, $tanggal_akhir])->delete();
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
        return $trx;
    }

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
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $req = $request->req;
        $id = $request->id;
        if($id == 21 && $req == 'add'){
            $wg = WorkingGroup::find($id);
            $where = ['wg_id' => $id, 'is_aktif' => 1];
            $nik = Karyawan::where($where)->pluck('nik')->toArray();
            if(count($nik) == 0){
                return redirect()->route('working.show', [$id, 'req'=>'view'])->with('error', 'No data is saved successfully.');
            }
            foreach($nik as $_nik){
                $start = Carbon::parse($request->tanggal_awal);
                $end = Carbon::parse($request->tanggal_akhir);
                $this->deleteData($_nik, $start, $end);
                while ($start->lte($end)) {
                    $tgl = $start->format('Y-m-d');
                    $this->insertData($wg->id, 1, $tgl, $_nik, $tgl.' '.$wg->clockin, $tgl.' '.$wg->clockout, $tgl.' '.$wg->end_clockout, $tgl.' '.$wg->start_clockin);
                    $start->addDay();
                }
            }
            return redirect()->route('working.show', [$id, 'req'=>'view'])->with('msg', 'Data is saved successfully.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $req = $request->req;
        // 21 = Reguler Freelance
        if($id == 21 && $req == 'view'){
            $tahun = Carbon::parse(now())->format('Y');
            $where = ['wg_id' => $id];
            $trx = WorkingDay::where($where)->whereYear('working_date', $tahun)->orderBy( 'working_date', 'desc' )->orderBy( 'clockin', 'asc' )->get();
            // filter parameter
            $_arr_nik_karyawan = WorkingDay::where($where)->groupBy('nik')->pluck('nik')->toArray();
            $arr_nik_karyawan = array();
            $date_from = '';
            $date_to = '';
            foreach ($_arr_nik_karyawan as $data){
                array_push($arr_nik_karyawan, $data);
            }
            $nik_karyawan = Karyawan::select('nik','nama')->whereIn('nik', $arr_nik_karyawan)->orderBy('nama', 'asc')->get();
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
            return view('master.jadwal.freelance.index', compact('trx', 'date_from', 'date_to', 'nik_karyawan', 'arr_nik_karyawan'));
        }
        // 22 = NOC 3 Group
        elseif($id == 22 && $req == 'view'){
            $tahun = Carbon::parse(now())->format('Y');
            $where = ['wg_id' => $id];
            $trx = WorkingDay::where($where)->whereYear('working_date', $tahun)->orderBy( 'working_date', 'desc' )->orderBy( 'clockin', 'asc' )->get();
            // filter parameter
            $_arr_nik_karyawan = WorkingDay::where($where)->groupBy('nik')->pluck('nik')->toArray();
            $arr_nik_karyawan = array();
            $date_from = '';
            $date_to = '';
            foreach ($_arr_nik_karyawan as $data){
                array_push($arr_nik_karyawan, $data);
            }
            $nik_karyawan = Karyawan::select('nik','nama')->whereIn('nik', $arr_nik_karyawan)->orderBy('nama', 'asc')->get();
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
            return view('master.jadwal.noc3.index', compact('trx', 'date_from', 'date_to', 'nik_karyawan', 'arr_nik_karyawan'));
        }
        // 15 = QA_TS
        elseif($id == 15 && $req == 'view'){
            $tahun = Carbon::parse(now())->format('Y');
            $where = ['wg_id' => $id];
            $trx = WorkingDay::where($where)->whereYear('working_date', $tahun)->orderBy( 'working_date', 'desc' )->orderBy( 'clockin', 'asc' )->get();
            // filter parameter
            $_arr_nik_karyawan = WorkingDay::where($where)->groupBy('nik')->pluck('nik')->toArray();
            $arr_nik_karyawan = array();
            $date_from = '';
            $date_to = '';
            foreach ($_arr_nik_karyawan as $data){
                array_push($arr_nik_karyawan, $data);
            }
            $nik_karyawan = Karyawan::select('nik','nama')->whereIn('nik', $arr_nik_karyawan)->orderBy('nama', 'asc')->get();
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
            return view('master.jadwal.qats.index_upload', compact('trx', 'date_from', 'date_to', 'nik_karyawan', 'arr_nik_karyawan'));
        }else{
            return redirect()->route('error-403');
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
