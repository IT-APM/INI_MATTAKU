<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Holiday;
use Illuminate\Support\Facades\Log;

class HolidayController extends Controller
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
        $holiday = Holiday::orderBy( 'tanggal', 'desc' )->get();
        return view( 'master.holiday.index', compact( 'holiday', 'no' ) );
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
        return view( 'master.holiday.upsert', compact( 'status' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $holiday = New Holiday;
        $holiday->tanggal = $request->tanggal;
        $holiday->libur = $request->libur;
        if($request->is_cuti == 'on'){
            $holiday->is_cuti = 1;
        }else{
            $holiday->is_cuti = 0;
        }
        $holiday->save();
        return redirect( 'holiday' )->with( 'msg', 'Data is saved successfully.' );
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
        $holiday = Holiday::find( $id );
        return view( 'master.holiday.upsert', compact( 'holiday', 'status' ) );
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
        $holiday = Holiday::find( $id );
        $holiday->tanggal = $request->tanggal;
        $holiday->libur = $request->libur;
        if($request->is_cuti == 'on'){
            $holiday->is_cuti = 1;
        }else{
            $holiday->is_cuti = 0;
        }
        $holiday->update();
        return redirect( 'holiday' )->with( 'msg', 'Data is saved successfully.' );
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
        $holiday = Holiday::find( $id );
        try {
            $holiday->delete();
            return redirect( 'holiday' )->with( 'msg', 'Data is deleted successfully.' );
         } catch (\Exception $e) {
            Log::error($e->errorInfo);
            return redirect()->route('error_db');
         }
    }
}
