<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// 
use App\Models\KaryawanPelatihan;
// 
class KaryawanPelatihanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        return view( 'master.karyawan.pelatihan.upsert', compact( 'status' ) );
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
        $nik = (new OpenDataController)->decryptWithKey($request->nik);
        $trx = New KaryawanPelatihan;
        $trx->nik = $nik;
        $trx->nama = $request->nama;
        $trx->keterangan = $request->keterangan;
        $trx->save();
        if($request->src=='open'){
            return redirect()->route('karyawan.show', $request->nik)->with('success','Data is saved successfully.');
        }else{
            return redirect()->route('karyawan.edit', $request->karyawan_id)->with('success','Data is saved successfully.');
        }
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
        $id = (new OpenDataController)->decryptWithKey($id);
        $status = 'edit';
        $trx = KaryawanPelatihan::find( $id );
        return view( 'master.karyawan.pelatihan.upsert', compact( 'status', 'trx' ) );
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
        $trx = KaryawanPelatihan::find( $id );
        $trx->nama = $request->nama;
        $trx->keterangan = $request->keterangan;
        $trx->update();
        if($request->src=='open'){
            return redirect()->route('karyawan.show', $request->nik)->with('success','Data is saved successfully.');
        }else{
            return redirect()->route('karyawan.edit', $request->karyawan_id)->with('success','Data is saved successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
        $trx = KaryawanPelatihan::find( $id );
        $trx->delete();
        if($request->src=='open'){
            return redirect()->route('karyawan.show', $request->nik)->with('success','Data is deleted successfully.');
        }else{
            return redirect()->route('karyawan.edit', $request->karyawan_id)->with('success','Data is deleted successfully.');
        }
    }
}
