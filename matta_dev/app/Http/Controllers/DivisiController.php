<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Perusahaan;
use App\Models\Divisi;
use Illuminate\Support\Facades\Log;

class DivisiController extends Controller
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
        $divisi = Divisi::orderBy( 'nama', 'asc' )->get();
        return view( 'master.divisi.index', compact( 'divisi', 'no' ) );
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
        return view( 'master.divisi.upsert', compact( 'status', 'perusahaan' ) );
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
        $divisi = New Divisi;
        $divisi->nama = $request->nama;
        $divisi->kode_perusahaan = $request->kode_perusahaan;
        if($request->is_active == 'on'){
            $divisi->is_active = 1;
        }else{
            $divisi->is_active = 0;
        }
        $divisi->save();
        return redirect( 'divisi' )->with( 'msg', 'Data is saved successfully.' );
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
        $divisi = Divisi::find( $id );
        $perusahaan = Perusahaan::all();
        return view( 'master.divisi.upsert', compact( 'divisi', 'status', 'perusahaan' ) );
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
        $divisi = Divisi::find( $id );
        $divisi->nama = $request->nama;
        $divisi->kode_perusahaan = $request->kode_perusahaan;
        if($request->is_active == 'on'){
            $divisi->is_active = 1;
        }else{
            $divisi->is_active = 0;
        }
        $divisi->update();
        return redirect( 'divisi' )->with( 'msg', 'Data is saved successfully.' );
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
        $divisi = Divisi::find( $id );
        try {
            $divisi->delete();
            return redirect( 'divisi' )->with( 'msg', 'Data is deleted successfully.' );
         } catch (\Exception $e) {
            //   var_dump( $e->errorInfo );
            Log::error($e->errorInfo);
            Log::error($divisi);
            return redirect()->route('error_db');
         }
    }
}
