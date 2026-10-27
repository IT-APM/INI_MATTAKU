<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pekerjaan;
use App\Models\PekerjaanSub;
use DB;
use Illuminate\Support\Facades\Log;

class PekerjaanMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $no = 0;
        // $trx = PekerjaanSub::orderBy( 'pekerjaan', 'asc' )->get();
        $bidang = Pekerjaan::orderBy( 'bidang', 'asc' )->get();
        return view( 'master.pekerjaan.index', compact( 'bidang' ) );
    }

    public function pekerjaan($id)
    {
        // echo $id;die;
        // $pekerjaan = PekerjaanSub::select('id', 'id_bidang', 'pekerjaan')->orderBy( 'pekerjaan', 'asc' )->get();
        $pekerjaan = DB::table('mtr_tbl_pekerjaan_sub as a')->select('a.id as id', 'b.bidang as id_bidang', 'a.pekerjaan as pekerjaan')->leftJoin('mtr_tbl_pekerjaan as b', 'a.id_bidang', '=', 'b.id')->orderBy( 'a.pekerjaan', 'asc' )->get();
        if($id != 0 ){
            // $pekerjaan = PekerjaanSub::select('id', 'id_bidang', 'pekerjaan')->whereIn('id_bidang', explode(',', $id))->orderBy( 'pekerjaan', 'asc' )->get();
            $pekerjaan = DB::table('mtr_tbl_pekerjaan_sub as a')->select('a.id as id', 'b.bidang as id_bidang', 'a.pekerjaan as pekerjaan')->leftJoin('mtr_tbl_pekerjaan as b', 'a.id_bidang', '=', 'b.id')->whereIn('a.id_bidang', explode(',', $id))->orderBy( 'a.pekerjaan', 'asc' )->get();
        }
        return response(json_encode($pekerjaan));
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
        $bidang = Pekerjaan::orderBy( 'bidang', 'asc' )->get();
        return view( 'master.pekerjaan.upsert', compact( 'status', 'bidang' ) );
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
        $trx = New PekerjaanSub;
        $trx->id_bidang = $request->id_bidang;
        $trx->pekerjaan = $request->pekerjaan;
        $trx->tugas = $request->tugas;
        $trx->wewenang = $request->wewenang;
        $trx->tanggung_jawab = $request->tanggung_jawab;
        $trx->keterangan = $request->keterangan;
        $trx->save();
        return redirect( 'pekerjaan_master' )->with( 'msg', 'Data is saved successfully.' );
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
        $trx = PekerjaanSub::find( $id );
        return view('master.pekerjaan.view', compact('trx'));
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
        $trx = PekerjaanSub::find( $id );
        $bidang = Pekerjaan::orderBy( 'bidang', 'asc' )->get();
        return view( 'master.pekerjaan.upsert', compact( 'trx', 'status', 'bidang' ) );
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
        $trx = PekerjaanSub::find( $id );
        $trx->id_bidang = $request->id_bidang;
        $trx->pekerjaan = $request->pekerjaan;
        $trx->tugas = $request->tugas;
        $trx->wewenang = $request->wewenang;
        $trx->tanggung_jawab = $request->tanggung_jawab;
        $trx->keterangan = $request->keterangan;
        $trx->update();
        return redirect( 'pekerjaan_master' )->with( 'msg', 'Data is saved successfully.' );
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
        $trx = PekerjaanSub::find( $id );
        try {
            $trx->delete();
            return redirect( 'pekerjaan_master' )->with( 'msg', 'Data is deleted successfully.' );
         } catch (\Exception $e) {
            //   var_dump( $e->errorInfo );
            Log::error($e->errorInfo);
            Log::error($trx);
            return redirect()->route('error_db');
         }
    }
}
