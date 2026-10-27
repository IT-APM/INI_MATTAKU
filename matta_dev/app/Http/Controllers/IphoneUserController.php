<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\IphoneUser;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Log;
use DB;

class IphoneUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $no = 0;
        $iphoneuser = IphoneUser::leftJoin('mtr_tbl_karyawan', 'mtr_tbl_iphone_users.nik', '=', 'mtr_tbl_karyawan.nik')->orderBy( 'nama', 'asc' )->get();
        if(Gate::allows('hc_nkp')){
            $iphoneuser = IphoneUser::leftJoin('mtr_tbl_karyawan', 'mtr_tbl_iphone_users.nik', '=', 'mtr_tbl_karyawan.nik')->whereIn('mtr_tbl_iphone_users.nik',$arr_nik_nkp)->orderBy( 'nama', 'asc' )->get();
        }
        return view( 'master.iphoneuser.index', compact( 'iphoneuser', 'no' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $status = 'create';
        // $where = ['is_aktif' => 1];
        // $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        $karyawan = DB::select(DB::raw("SELECT nik,nama FROM mtr_tbl_karyawan WHERE is_aktif=1 AND nik NOT IN (SELECT nik FROM mtr_tbl_iphone_users) ORDER BY nama"));
        if(Gate::allows('hc_nkp')){
            $karyawan = DB::select(DB::raw("SELECT nik,nama FROM mtr_tbl_karyawan WHERE is_aktif=1 AND nik NOT IN (SELECT nik FROM mtr_tbl_iphone_users) AND kode_perusahaan IN (5) ORDER BY nama"));
        }
        return view( 'master.iphoneuser.upsert', compact( 'status', 'karyawan' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $iphoneuser = New IphoneUser;
        $iphoneuser->nik = $request->nik;
        $iphoneuser->save();
        return redirect( 'iphoneuser' )->with( 'msg', 'Data is saved successfully.' );
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
        // $iphoneuser = IphoneUser::find( $id );
        $where = ['nik' => $id];
        $iphoneuser = IphoneUser::where($where)->first();
        try {
            $iphoneuser->delete();
            return redirect( 'iphoneuser' )->with( 'msg', 'Data is deleted successfully.' );
         } catch (\Exception $e) {
            Log::error($e->errorInfo);
            return redirect()->route('error_db');
         }
    }
}
