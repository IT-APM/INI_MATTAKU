<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Karyawan;
use App\Models\AbsensiKesehatan;
// 
use Carbon\Carbon;
use DB;
use Elibyy\TCPDF\Facades\TCPDF;

class TrxKesehatanReportingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $no = 0;
        $limit = 25000;
        $tahun = Carbon::parse(now())->format('Y');
        $bulan = Carbon::parse(now())->format('m');
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $trx = array();
        $trx_m = AbsensiKesehatan::whereMonth('tanggal_input', $bulan)->whereYear('tanggal_input', $tahun)->groupBy('karyawan','tanggal_input')->orderBy( 'tanggal_input', 'desc' )->limit($limit)->get();
        if(Gate::allows('hc_nkp')){
            $trx_m = AbsensiKesehatan::whereIn('karyawan',$arr_nik_nkp)->whereMonth('tanggal_input', $bulan)->whereYear('tanggal_input', $tahun)->groupBy('karyawan','tanggal_input')->orderBy( 'tanggal_input', 'desc' )->limit($limit)->get();
        }
        foreach ($trx_m as $data){
            $where = ['karyawan'=>$data->karyawan, 'tanggal_input'=>$data->tanggal_input];
            $trx_d = AbsensiKesehatan::where($where)->orderBy( 'ring_ke', 'asc' )->get();
            array_push($trx, ['trx_m'=>$data, 'trx_d'=>$trx_d]);
        }
        // return response()->json( [ 'trx' => $trx ], 200 );
        // filter parameter
        $date_from = '';
        $date_to = '';
        // start filter
        if($request->filter == true){
            $trx = array();
            $date_from = $request->date_from;
            $date_to = $request->date_to;
            $trx_m = AbsensiKesehatan::whereBetween('tanggal_input', [$date_from, $date_to])->groupBy('karyawan','tanggal_input')->orderBy( 'tanggal_input', 'desc' )->limit($limit)->get();
            if(Gate::allows('hc_nkp')){
                $trx_m = AbsensiKesehatan::whereIn('karyawan',$arr_nik_nkp)->whereBetween('tanggal_input', [$date_from, $date_to])->groupBy('karyawan','tanggal_input')->orderBy( 'tanggal_input', 'desc' )->limit($limit)->get();
            }
            foreach ($trx_m as $data){
                $where = ['karyawan'=>$data->karyawan, 'tanggal_input'=>$data->tanggal_input];
                $trx_d = AbsensiKesehatan::where($where)->orderBy( 'ring_ke', 'asc' )->get();
                array_push($trx, ['trx_m'=>$data, 'trx_d'=>$trx_d]);
            }
            // $trx = AbsensiKesehatan::whereBetween('tanggal_input', [$date_from, $date_to])->groupBy('karyawan','tanggal_input')->orderBy( 'tanggal_input', 'desc' )->limit($limit)->get();
        }
        return view('kesehatan.reporting.index', compact('no', 'trx', 'date_from', 'date_to'));
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
