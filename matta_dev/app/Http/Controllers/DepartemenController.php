<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Perusahaan;
use App\Models\Divisi;
use App\Models\Departemen;
use Illuminate\Support\Facades\Log;

class DepartemenController extends Controller
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
        $departemen = Departemen::orderBy( 'nama', 'asc' )->get();
        return view( 'master.departemen.index', compact( 'departemen', 'no' ) );
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
        return view( 'master.departemen.upsert', compact( 'status', 'perusahaan', 'divisi' ) );
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
        $departemen = New Departemen;
        $departemen->nama = $request->nama;
        $departemen->kode_divisi = $request->kode_divisi;
        if($request->is_active == 'on'){
            $departemen->is_active = 1;
        }else{
            $departemen->is_active = 0;
        }
        $departemen->save();
        return redirect( 'departemen' )->with( 'msg', 'Data is saved successfully.' );
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
        // $where = ['mtr_tbl_departemen.id' => $id];
        $departemen = Departemen::select('mtr_tbl_departemen.*', 'mtr_tbl_divisi.kode_perusahaan')
            ->leftJoin('mtr_tbl_divisi', 'mtr_tbl_divisi.id', '=', 'mtr_tbl_departemen.kode_divisi')
            ->find( $id );
        $perusahaan = Perusahaan::all();
        $divisi = Divisi::where('kode_perusahaan', $departemen->kode_perusahaan)->get();
        return view( 'master.departemen.upsert', compact( 'departemen', 'status', 'perusahaan', 'divisi' ) );
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
        $departemen = Departemen::find( $id );
        $departemen->nama = $request->nama;
        $departemen->kode_divisi = $request->kode_divisi;
        if($request->is_active == 'on'){
            $departemen->is_active = 1;
        }else{
            $departemen->is_active = 0;
        }
        $departemen->update();
        return redirect( 'departemen' )->with( 'msg', 'Data is saved successfully.' );
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
        $departemen = Departemen::find( $id );
        try {
            $departemen->delete();
            return redirect( 'departemen' )->with( 'msg', 'Data is deleted successfully.' );
         } catch (\Exception $e) {
            //   var_dump( $e->errorInfo );
            Log::error($e->errorInfo);
            Log::error($departemen);
            return redirect()->route('error_db');
         }
    }
}
