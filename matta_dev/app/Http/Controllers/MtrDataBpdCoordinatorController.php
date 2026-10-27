<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
// 
use App\Models\Propinsi;
use App\Models\PropinsiKota;
use App\Models\Negara;
use App\Models\Karyawan;
use App\Models\BpdBiaya;
use App\Models\BpdCoa;
use App\Models\BpdKelompok;
use App\Models\BpdNikPeran;
use App\Models\BpdNikPC;
use App\Models\BpdNonProyekBiayaDalamNegeri;
use App\Models\BpdNonProyekBiayaLuarNegeri;
use App\Models\BpdPeran;
use App\Models\BpdProyek;
use App\Models\BpdProyekAdmin;
use App\Models\BpdProyekUangSaku;
use App\Models\BpdProyekUangSakuKelompok;
use App\Models\BpdStatus;
// 
use App\Models\TrxBpdDetailBiaya;
use App\Models\TrxBpdDetailInfo;
use App\Models\TrxBpdLog;
use App\Models\TrxBpdMaster;
use App\Models\ToDoList;
use App\Models\ToDoListLog;
// 
use App\Models\MtrUtility;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
// 
use Carbon\Carbon;
use DB;
use Elibyy\TCPDF\Facades\TCPDF;
use Excel;
// 

class MtrDataBpdCoordinatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $no = 0;
        $trx = BpdNikPC::select('mtr_tbl_bpd_nik_pc.id','mtr_tbl_bpd_nik_pc.nik','mtr_tbl_bpd_nik_pc.is_active')->leftJoin('mtr_tbl_karyawan', 'mtr_tbl_karyawan.nik','=','mtr_tbl_bpd_nik_pc.nik')->orderBy( 'mtr_tbl_karyawan.nama', 'asc' )->get();
        return view( 'master.bpd.coordinator.index', compact( 'trx', 'no' ) );
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
        $status = 'create';
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::where($where)->orderBy( 'nama', 'asc' )->get();
        return view( 'master.bpd.coordinator.upsert', compact( 'status','karyawan' ) );
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
        $trx = New BpdNikPC;
        $trx->nik = $request->nik;
        if($request->is_active == 'on'){
            $trx->is_active = 1;
        }else{
            $trx->is_active = 0;
        }
        $trx->save();
        return redirect( 'mtrdatacoordinatorbpd' )->with( 'msg', 'Data is saved successfully.' );
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
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $status = 'edit';
        $trx = BpdNikPC::find( $id );
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::where($where)->orderBy( 'nama', 'asc' )->get();
        return view( 'master.bpd.coordinator.upsert', compact( 'trx', 'status', 'karyawan' ) );
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
        $trx = BpdNikPC::find( $id );
        $trx->nik = $request->nik;
        if($request->is_active == 'on'){
            $trx->is_active = 1;
        }else{
            $trx->is_active = 0;
        }
        $trx->update();
        return redirect( 'mtrdatacoordinatorbpd' )->with( 'msg', 'Data is saved successfully.' );
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
        $trx = BpdNikPC::find( $id );
        $trx->delete();
        return redirect( 'mtrdatacoordinatorbpd' )->with( 'msg', 'Data is deleted successfully.' );
    }
}
