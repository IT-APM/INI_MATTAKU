<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\Attendance;
use App\Models\TrxCuti;
use App\Models\TrxSakit;
use App\Models\Karyawan;
use App\Models\Perusahaan;
use App\Models\Divisi;
use App\Models\Departemen;
use App\Models\SubDepartemen;
use Carbon\Carbon;
use App\Models\WorkingDay;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RecapAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->all());
        set_time_limit(3600);
        // filter parameter
        $no = 0;
        // $perusahaan = Perusahaan::all();
        // 0=NA
        // 3=SMS Project
        $perusahaan = Perusahaan::whereNotIn('id', [0,3])->orderBy( 'nama', 'asc' )->get();
        $divisi = [];
        $departemen = [];
        $sub_departemen = [];
        //
        $arr_perusahaan = [];
        $arr_divisi = [];
        $arr_departemen = [];
        $arr_sub_departemen = [];
        // $limit = 25000;
        $trx = [];
        $arr_is_aktif = [['id' => 1, 'is_aktif' => 'Aktif'], ['id' => 0, 'is_aktif' => 'Non Aktif']];
        $is_aktif = $arr_is_aktif;
        $date_from = '';
        $date_to = '';
        // start filter
        if ($request->filter == true) {
            $arr_nik_nkp = new Karyawan();
            $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
            $date_from = $request->date_from;
            $date_to = $request->date_to;
            $_perusahaan = $request->input('kode_perusahaan', []);
            $_divisi = $request->input('kode_divisi', []);
            $_departemen = $request->input('kode_departemen', []);
            $_sub_departemen = $request->input('kode_sub_departemen', []);
            $_is_aktif = $request->input('is_aktif', []);
            if (count($_perusahaan) > 0) {
                $arr_perusahaan = $_perusahaan;
            }
            if (count($_divisi) > 0) {
                $arr_divisi = $_divisi;
            }
            if (count($_departemen) > 0) {
                $arr_departemen = $_departemen;
            }
            if (count($_sub_departemen) > 0) {
                $arr_sub_departemen = $_sub_departemen;
            }
            if (count($_is_aktif) > 0) {
                $arr_is_aktif = $_is_aktif;
            } else {
                $arr_is_aktif = [];
            }
            $divisi = Divisi::whereIn('kode_perusahaan', $arr_perusahaan)->get();
            $departemen = Departemen::whereIn('kode_divisi', $arr_divisi)->get();
            $sub_departemen = SubDepartemen::whereIn('kode_departemen', $arr_departemen)->get();
            $karyawan = Karyawan::whereIn('kode_sub_departemen', $arr_sub_departemen)->whereNotIn('nik', ['Hco.all', 'IT.BM'])->whereIn('is_aktif', $arr_is_aktif)->get();
            if(Gate::allows('hc_nkp')){
                $karyawan = Karyawan::whereIn('nik',$arr_nik_nkp)->whereIn('kode_sub_departemen', $arr_sub_departemen)->whereNotIn('nik', ['Hco.all', 'IT.BM'])->whereIn('is_aktif', $arr_is_aktif)->get();
            }
            if(auth()->user()->userrole == 'nhc'){
                // hanya karyawan saja
                // $where = ['nik' => auth()->user()->nik];
                // $karyawan = Karyawan::where($where)->get();
                // karyawan beserta bawahan1 dan bawahan2
                $_karyawan = array();
                array_push($_karyawan, auth()->user()->nik);
                $where = ['layer1' => auth()->user()->nik];
                $bawahan1 = Karyawan::select('nik')->where($where)->get();
                foreach($bawahan1 as $_bawahan1){
                    array_push($_karyawan, $_bawahan1->nik);
                    // bawahannya lagi
                    $where = ['layer1' => $_bawahan1->nik];
                    $bawahan2 = Karyawan::select('nik')->where($where)->get();
                    foreach($bawahan2 as $_bawahan2){
                        array_push($_karyawan, $_bawahan2->nik);
                    }
                }
                // $where = ['is_aktif' => 1];
                // $karyawan = Karyawan::where($where)->whereIn('nik', $_karyawan)->get();
                $karyawan = Karyawan::whereIn('nik', $_karyawan)->whereIn('is_aktif', $arr_is_aktif)->get();
            }
            // dd($karyawan);
            // $nik = '01032106835';
            // $date_from = '2023-12-01';
            // $date_to = '2023-12-31';
            $arr_date = $this->dateRange($date_from, $date_to);
            // pengecualian karena pada tanggal ini terjadi suspend domain alita.id
            $arr_date = array_values(array_diff($arr_date, ['2024-09-23']));
            // dd($arr_date);
            // $where = ['is_aktif'=>1, 'wg_id'=>1, 'kode_sub_departemen'=>122];
            // $karyawan = Karyawan::where($where)->get();
            foreach ($karyawan as $_karyawan) {
                $nik = $_karyawan->nik;
                $arr_base = [];
                $arr_clock = [];
                $tanggal_bergabung = $_karyawan->tanggal_bergabung;
                // cek apakah karyawan berjenis kerja reguler
                if ($_karyawan->_wg->is_reguler == 1) {
                    foreach ($arr_date as $data) {
                        if (!$this->isWeekend($data)) {
                            if (!$this->isHoliday($data)) {
                                if (!$this->isCuti($nik, $data)) {
                                    array_push($arr_base, $data);
                                }
                                if ($this->isSakit($nik, $data)) {
                                    if (($key = array_search($data, $arr_base)) !== false) {
                                        unset($arr_base[$key]);
                                    }
                                }
                                if ($tanggal_bergabung > $data) {
                                    if (($key = array_search($data, $arr_base)) !== false) {
                                        unset($arr_base[$key]);
                                    }
                                }
                            }
                        }
                    }
                }
                // karyawan non reguler dengan jadwal kerja sesuai working group nya
                else {
                    $where = ['nik' => $nik];
                    $data = WorkingDay::where($where)->whereIn('working_date', $arr_date)->get();
                    foreach ($data as $_data) {
                        if (!$this->isCuti($nik, $_data->working_date)) {
                            array_push($arr_base, $_data->working_date);
                        }
                        if ($this->isSakit($nik, $_data->working_date)) {
                            if (($key = array_search($_data->working_date, $arr_base)) !== false) {
                                unset($arr_base[$key]);
                            }
                        }
                        if ($tanggal_bergabung > $data) {
                            if (($key = array_search($data, $arr_base)) !== false) {
                                unset($arr_base[$key]);
                            }
                        }
                    }
                }
                foreach ($arr_base as $data) {
                    if ($this->isClockin($nik, $data)) {
                        array_push($arr_clock, $data);
                    }
                }
                $persentase = 0;
                if (count($arr_base) > 0) {
                    $persentase = count($arr_clock) / count($arr_base);
                    array_push($trx, ['nik' => $nik, 'arr_base' => count($arr_base), 'arr_clock' => count($arr_clock), 'persentase' => $persentase * 100]);
                }
            }
        } else {
            // NA
        }
        // end filter
        return view('attendance.recap.index', compact('trx', 'no', 'date_from', 'date_to', 'arr_is_aktif', 'is_aktif', 'perusahaan', 'divisi', 'departemen', 'sub_departemen', 'arr_perusahaan', 'arr_divisi', 'arr_departemen', 'arr_sub_departemen'));
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
}
