<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// 
use App\Models\ReimburseHubungan;
use App\Models\ReimburseLayanan;
use App\Models\TrxReimburseDetail;

class TrxReimburseDetailController extends Controller
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
        // return redirect( 'reimburse.edit', $request->trxMId )->with( 'msg', 'Data is saved successfully.' );
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
        // $status = 'create';
        // $hubungan = ReimburseHubungan::orderBy('id', 'asc')->get();
        // $layanan = ReimburseLayanan::orderBy('id', 'asc')->get();
        // return view('formulir.reimburse.detail.upsert', compact('id', 'status', 'hubungan', 'layanan'));
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
        // $status = 'edit';
        // $trxD = TrxReimburseDetail::find( $id );
        // $hubungan = ReimburseHubungan::orderBy('id', 'asc')->get();
        // $layanan = ReimburseLayanan::orderBy('id', 'asc')->get();
        // return view('formulir.reimburse.detail.upsert', compact('trxD', 'status', 'hubungan', 'layanan'));
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
        // return response()->json( [ 'request' => $request->all() ], 200 );
        // return redirect()->route('reimburse.edit', $request->trxMId)->with( 'msg', 'Data is saved successfully.' );
        // return redirect( '/reimburse/10110/edit', $request->trxMId )->with( 'msg', 'Data is saved successfully.' );
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
    }
}
