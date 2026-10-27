<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// 
use App\Models\AnakKe;
use App\Models\KaryawanAnak;
// 
class KaryawanAnakController extends Controller
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
        $anakke = AnakKe::all();
        return view( 'master.karyawan.anak.upsert', compact( 'status', 'anakke' ) );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $nik = (new OpenDataController)->decryptWithKey($request->nik);
        $trx = New KaryawanAnak;
        $trx->nik = $nik;
        $trx->id_anakke = $request->id_anakke;
        $trx->nama = $request->nama;
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
        $id = (new OpenDataController)->decryptWithKey($id);
        $status = 'edit';
        $trx = KaryawanAnak::find( $id );
        $anakke = AnakKe::all();
        return view( 'master.karyawan.anak.upsert', compact( 'status', 'anakke', 'trx' ) );
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
        // dd($request->all(), $id);
        $trx = KaryawanAnak::find( $id );
        $trx->id_anakke = $request->id_anakke;
        $trx->nama = $request->nama;
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
        $trx = KaryawanAnak::find( $id );
        $trx->delete();
        if($request->src=='open'){
            return redirect()->route('karyawan.show', $request->nik)->with('success','Data is deleted successfully.');
        }else{
            return redirect()->route('karyawan.edit', $request->karyawan_id)->with('success','Data is deleted successfully.');
        }
    }
}
