<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelatihan;
use App\Models\PelatihanSub;
use DB;
use Illuminate\Support\Facades\Log;

class PelatihanMasterController extends Controller
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
        $pelatihan_sub = PelatihanSub::orderBy( 'id_pelatihan', 'asc' )->get();
        return view( 'master.pelatihan.index', compact( 'pelatihan_sub', 'no' ) );
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
        $pelatihan = Pelatihan::all();
        return view( 'master.pelatihan.upsert', compact( 'status', 'pelatihan' ) );
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
        $pelatihan_sub = New PelatihanSub;
        $pelatihan_sub->id_pelatihan = $request->id_pelatihan;
        $pelatihan_sub->sub_pelatihan = $request->sub_pelatihan;
        $pelatihan_sub->save();
        return redirect( '_pelatihan_master' )->with( 'msg', 'Data is saved successfully.' );
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
        $pelatihan_sub = PelatihanSub::find( $id );
        $pelatihan = Pelatihan::all();
        return view( 'master.pelatihan.upsert', compact( 'status', 'pelatihan', 'pelatihan_sub' ) );
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
        $pelatihan_sub = PelatihanSub::find( $id );
        $pelatihan_sub->id_pelatihan = $request->id_pelatihan;
        $pelatihan_sub->sub_pelatihan = $request->sub_pelatihan;
        $pelatihan_sub->update();
        return redirect( '_pelatihan_master' )->with( 'msg', 'Data is saved successfully.' );
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
        $pelatihan_sub = PelatihanSub::find( $id );
        try {
            $pelatihan_sub->delete();
            return redirect( '_pelatihan_master' )->with( 'msg', 'Data is deleted successfully.' );
         } catch (\Exception $e) {
            //   var_dump( $e->errorInfo );
            Log::error($e->errorInfo);
            Log::error($pelatihan_sub);
            return redirect()->route('error_db');
         }
    }
}
