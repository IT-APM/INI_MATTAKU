<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\MtrFindimDivisi;
use Illuminate\Support\Facades\Log;

class TrxFindimDivisiController extends Controller
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
        $findim_divisi = MtrFindimDivisi::orderBy( 'divisi_id', 'asc' )->get();
        return view( 'master.findim.divisi.index', compact( 'findim_divisi', 'no' ) );
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
        return view( 'master.findim.divisi.upsert', compact( 'status' ) );
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
        $trx = New MtrFindimDivisi;
        $trx->divisi_id = $request->divisi_id;
        $trx->divisi_desc = $request->divisi_desc;
        $trx->save();
        return redirect( 'findim_divisi' )->with( 'msg', 'Data is saved successfully.' );
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
        $findim_divisi = MtrFindimDivisi::find( $id );
        return view( 'master.findim.divisi.upsert', compact( 'findim_divisi', 'status' ) );
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
        $trx = MtrFindimDivisi::find( $id );
        $trx->divisi_id = $request->divisi_id;
        $trx->divisi_desc = $request->divisi_desc;
        $trx->update();
        return redirect( 'findim_divisi' )->with( 'msg', 'Data is saved successfully.' );
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
        $trx = MtrFindimDivisi::find( $id );
        try {
            $trx->delete();
            return redirect( 'findim_divisi' )->with( 'msg', 'Data is deleted successfully.' );
         } catch (\Exception $e) {
            //   var_dump( $e->errorInfo );
            Log::error($e->errorInfo);
            Log::error($trx);
            return redirect()->route('error_db');
         }
    }
}
