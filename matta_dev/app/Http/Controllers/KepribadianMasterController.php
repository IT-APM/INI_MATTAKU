<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kepribadian;
use App\Models\KepribadianSub;
use DB;
use Illuminate\Support\Facades\Log;

class KepribadianMasterController extends Controller
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
        $kepribadian_sub = KepribadianSub::orderBy( 'id_kepribadian', 'asc' )->get();
        return view( 'master.kepribadian.index', compact( 'kepribadian_sub', 'no' ) );
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
        $kepribadian = Kepribadian::all();
        return view( 'master.kepribadian.upsert', compact( 'status', 'kepribadian' ) );
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
        $kepribadian_sub = New KepribadianSub;
        $kepribadian_sub->id_kepribadian = $request->id_kepribadian;
        $kepribadian_sub->sub_kepribadian = $request->sub_kepribadian;
        $kepribadian_sub->save();
        return redirect( '_kepribadian_master' )->with( 'msg', 'Data is saved successfully.' );
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
        $kepribadian_sub = KepribadianSub::find( $id );
        $kepribadian = Kepribadian::all();
        return view( 'master.kepribadian.upsert', compact( 'status', 'kepribadian', 'kepribadian_sub' ) );
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
        $kepribadian_sub = KepribadianSub::find( $id );
        $kepribadian_sub->id_kepribadian = $request->id_kepribadian;
        $kepribadian_sub->sub_kepribadian = $request->sub_kepribadian;
        $kepribadian_sub->update();
        return redirect( '_kepribadian_master' )->with( 'msg', 'Data is saved successfully.' );
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
        $kepribadian_sub = KepribadianSub::find( $id );
        try {
            $kepribadian_sub->delete();
            return redirect( '_kepribadian_master' )->with( 'msg', 'Data is deleted successfully.' );
         } catch (\Exception $e) {
            //   var_dump( $e->errorInfo );
            Log::error($e->errorInfo);
            Log::error($kepribadian_sub);
            return redirect()->route('error_db');
         }
    }
}
