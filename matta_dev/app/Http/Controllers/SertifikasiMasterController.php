<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sertifikasi;
use App\Models\SertifikasiSub;
use DB;
use Illuminate\Support\Facades\Log;

class SertifikasiMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $no = 0;
        $sertifikasi_sub = SertifikasiSub::orderBy( 'id_sertifikasi', 'asc' )->get();
        return view( 'master.sertifikasi.index', compact( 'sertifikasi_sub', 'no' ) );
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
        $sertifikasi = Sertifikasi::all();
        return view( 'master.sertifikasi.upsert', compact( 'status', 'sertifikasi' ) );
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
        $sertifikasi_sub = New SertifikasiSub;
        $sertifikasi_sub->id_sertifikasi = $request->id_sertifikasi;
        $sertifikasi_sub->sub_sertifikasi = $request->sub_sertifikasi;
        $sertifikasi_sub->save();
        return redirect( '_sertifikasi_master' )->with( 'msg', 'Data is saved successfully.' );
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
        $status = 'edit';
        $sertifikasi_sub = SertifikasiSub::find( $id );
        $sertifikasi = Sertifikasi::all();
        return view( 'master.sertifikasi.upsert', compact( 'status', 'sertifikasi', 'sertifikasi_sub' ) );
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
        $sertifikasi_sub = SertifikasiSub::find( $id );
        $sertifikasi_sub->id_sertifikasi = $request->id_sertifikasi;
        $sertifikasi_sub->sub_sertifikasi = $request->sub_sertifikasi;
        $sertifikasi_sub->update();
        return redirect( '_sertifikasi_master' )->with( 'msg', 'Data is saved successfully.' );
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
        $this->authorize('hc');
        $sertifikasi_sub = SertifikasiSub::find( $id );
        try {
            $sertifikasi_sub->delete();
            return redirect( '_sertifikasi_master' )->with( 'msg', 'Data is deleted successfully.' );
         } catch (\Exception $e) {
            //   var_dump( $e->errorInfo );
            Log::error($e->errorInfo);
            Log::error($sertifikasi_sub);
            return redirect()->route('error_db');
         }
    }
}
