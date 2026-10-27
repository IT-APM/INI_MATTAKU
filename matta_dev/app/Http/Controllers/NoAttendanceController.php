<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Karyawan;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Gate;

class NoAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // filter parameter
        $no = 0;
        $limit = 25000;
        $trx = array();
        $arr_is_aktif = [ ['id'=>1,'is_aktif'=>'Aktif'], ['id'=>0,'is_aktif'=>'Non Aktif'] ];
        $is_aktif = $arr_is_aktif;
        $date_from = '';
        $date_to = '';
        // start filter
        if($request->filter == true){
            $arr_nik_nkp = new Karyawan();
            $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
            $date_from = $request->date_from;
            $date_to = $request->date_to;
            $_is_aktif = $request->input('is_aktif', []);
            if(count($_is_aktif) > 0){
                $arr_is_aktif = $_is_aktif;
            }
            else{
                $arr_is_aktif = array();
            }
            $nik = Karyawan::whereNotIn('nik', ['Hco.all', 'IT.BM'])->whereIn('is_aktif', $arr_is_aktif)->get();
            $att_nik = Attendance::whereIn('att_data_src', ['Regular', 'Leave'])->whereBetween('att_date', [$date_from, $date_to])->orderBy( 'att_timestamp', 'desc' )->limit($limit)->get();
            if(Gate::allows('hc_nkp')){
                $nik = Karyawan::whereIn('nik',$arr_nik_nkp)->whereNotIn('nik', ['Hco.all', 'IT.BM'])->whereIn('is_aktif', $arr_is_aktif)->get();
                $att_nik = Attendance::whereIn('att_nik',$arr_nik_nkp)->whereIn('att_data_src', ['Regular', 'Leave'])->whereBetween('att_date', [$date_from, $date_to])->orderBy( 'att_timestamp', 'desc' )->limit($limit)->get();
            }
            // 
            $arr_att_nik = array();
            foreach($att_nik as $data){
                array_push($arr_att_nik, $data->att_nik);
            }
            // cek nik yang tidak melakukan cico
            foreach($nik as $data){
                if (!in_array($data->nik, $arr_att_nik)){
                    array_push($trx, $data);
                }
            }
            // return response()->json( [ 'no_nik' => $no_nik ], 200 );
            // if(auth()->user()->userrole == 'nhc'){
            //     // $where = ['att_nik' => auth()->user()->nik];
            //     $where = ['layer1' => auth()->user()->nik];
            //     $bawahan = Karyawan::select('nik')->where($where)->get();
            //     $att_nik = array();
            //     array_push($att_nik, auth()->user()->nik);
            //     foreach($bawahan as $data){
            //         array_push($att_nik, $data->nik);
            //     }
            //     $att = Attendance::whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereBetween('att_date', [$date_from, $date_to])->orderBy( 'att_timestamp', 'desc' )->limit($limit)->get();
            // }
        }
        // else{
        //     $arr_att_type = array();
        //     $arr_att_status = array();
        //     $arr_att_data_src = array();
        //     $arr_perusahaan = array();
        // }
        // end filter
        return view( 'attendance.no.index', compact( 'trx', 'no', 'date_from', 'date_to', 'arr_is_aktif', 'is_aktif' ) );
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
