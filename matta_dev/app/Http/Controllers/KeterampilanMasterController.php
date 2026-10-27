<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keterampilan;
use App\Models\KeterampilanSub;
use DB;
use Illuminate\Support\Facades\Log;

class KeterampilanMasterController extends Controller
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
        $keterampilan_sub = KeterampilanSub::orderBy( 'id_keterampilan', 'asc' )->get();
        return view( 'master.keterampilan.index', compact( 'keterampilan_sub', 'no' ) );
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
        $keterampilan = Keterampilan::all();
        return view( 'master.keterampilan.upsert', compact( 'status', 'keterampilan' ) );
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
        $keterampilan_sub = New KeterampilanSub;
        $keterampilan_sub->id_keterampilan = $request->id_keterampilan;
        $keterampilan_sub->sub_keterampilan = $request->sub_keterampilan;
        $keterampilan_sub->save();
        return redirect( '_keterampilan_master' )->with( 'msg', 'Data is saved successfully.' );
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
        $keterampilan_sub = KeterampilanSub::find( $id );
        $keterampilan = Keterampilan::all();
        return view( 'master.keterampilan.upsert', compact( 'status', 'keterampilan', 'keterampilan_sub' ) );
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
        $keterampilan_sub = KeterampilanSub::find( $id );
        $keterampilan_sub->id_keterampilan = $request->id_keterampilan;
        $keterampilan_sub->sub_keterampilan = $request->sub_keterampilan;
        $keterampilan_sub->update();
        return redirect( '_keterampilan_master' )->with( 'msg', 'Data is saved successfully.' );
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
        $keterampilan_sub = KeterampilanSub::find( $id );
        try {
            $keterampilan_sub->delete();
            return redirect( '_keterampilan_master' )->with( 'msg', 'Data is deleted successfully.' );
         } catch (\Exception $e) {
            //   var_dump( $e->errorInfo );
            Log::error($e->errorInfo);
            Log::error($keterampilan_sub);
            return redirect()->route('error_db');
         }
    }
}
