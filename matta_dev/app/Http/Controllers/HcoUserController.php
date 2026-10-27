<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Log;
use DB;

class HcoUserController extends Controller
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
        $where = ['users.userrole' => 'hc'];
        $hcouser = User::select('users.*','mtr_tbl_karyawan.is_aktif')->leftJoin('mtr_tbl_karyawan', 'users.nik', '=', 'mtr_tbl_karyawan.nik')
        ->where($where)->orderBy('users.name', 'asc')->get();
        return view( 'master.hcouser.index', compact( 'hcouser', 'no' ) );
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
        $karyawan = DB::select(DB::raw("SELECT nik,nama FROM mtr_tbl_karyawan WHERE is_aktif=1 AND nik NOT IN (SELECT nik FROM users WHERE userrole='hc') ORDER BY nama"));
        return view( 'master.hcouser.upsert', compact( 'karyawan' ) );
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
        $where = ['nik' => $request->nik];
        $hcouser = User::where($where)->first();
        $hcouser->userrole = 'hc';
        $hcouser->update();
        return redirect( 'hcouser' )->with( 'msg', 'Data is saved successfully.' );
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
        $where = ['nik' => $id];
        $hcouser = User::where($where)->first();
        $hcouser->userrole = 'nhc';
        $hcouser->update();
        return redirect( 'hcouser' )->with( 'msg', 'Data is saved successfully.' );
    }
}
