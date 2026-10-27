<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\SubDepartemen;
use App\Models\Perusahaan;
use App\Models\Divisi;
use App\Models\Departemen;
use Illuminate\Support\Facades\Log;

class SubDepartemenController extends Controller
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
        $subdepartemen = SubDepartemen::orderBy( 'nama', 'asc' )->get();
        return view( 'master.sub_departemen.index', compact( 'subdepartemen', 'no' ) );
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
        $perusahaan = Perusahaan::all();
        $divisi = Divisi::all();
        $departemen = Departemen::all();
        return view( 'master.sub_departemen.upsert', compact( 'status', 'perusahaan', 'divisi', 'departemen' ) );
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
        $subdepartemen = New SubDepartemen;
        $subdepartemen->nama = $request->nama;
        $subdepartemen->kode_departemen = $request->kode_departemen;
        if($request->is_active == 'on'){
            $subdepartemen->is_active = 1;
        }else{
            $subdepartemen->is_active = 0;
        }
        $subdepartemen->save();
        return redirect( 'subdepartemen' )->with( 'msg', 'Data is saved successfully.' );
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
        $subdepartemen = SubDepartemen::select('mtr_tbl_sub_departemen.*', 'mtr_tbl_departemen.kode_divisi', 'mtr_tbl_divisi.kode_perusahaan')
            ->leftJoin('mtr_tbl_departemen', 'mtr_tbl_departemen.id', '=', 'mtr_tbl_sub_departemen.kode_departemen')
            ->leftJoin('mtr_tbl_divisi', 'mtr_tbl_divisi.id', '=', 'mtr_tbl_departemen.kode_divisi')
            ->find( $id );
        $perusahaan = Perusahaan::all();
        $divisi = Divisi::where('kode_perusahaan', $subdepartemen->kode_perusahaan)->get();
        $departemen = Departemen::where('kode_divisi', $subdepartemen->kode_divisi)->get();
        return view( 'master.sub_departemen.upsert', compact( 'departemen', 'status', 'perusahaan', 'divisi', 'subdepartemen' ) );
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
        $subdepartemen = SubDepartemen::find( $id );
        $subdepartemen->nama = $request->nama;
        $subdepartemen->kode_departemen = $request->kode_departemen;
        if($request->is_active == 'on'){
            $subdepartemen->is_active = 1;
        }else{
            $subdepartemen->is_active = 0;
        }
        $subdepartemen->update();
        return redirect( 'subdepartemen' )->with( 'msg', 'Data is saved successfully.' );
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
        $subdepartemen = SubDepartemen::find( $id );
        try {
            $subdepartemen->delete();
            return redirect( 'subdepartemen' )->with( 'msg', 'Data is deleted successfully.' );
         } catch (\Exception $e) {
            //   var_dump( $e->errorInfo );
            Log::error($e->errorInfo);
            Log::error($subdepartemen);
            return redirect()->route('error_db');
         }
    }
}
