<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\MtrFindimProject;
use Illuminate\Support\Facades\Log;

class TrxFindimProjectController extends Controller
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
        $findim_project = MtrFindimProject::orderBy( 'project_id', 'asc' )->get();
        return view( 'master.findim.project.index', compact( 'findim_project', 'no' ) );
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
        return view( 'master.findim.project.upsert', compact( 'status' ) );
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
        $trx = New MtrFindimProject;
        $trx->project_id = $request->project_id;
        $trx->project_desc = $request->project_desc;
        $trx->save();
        return redirect( 'findim_project' )->with( 'msg', 'Data is saved successfully.' );
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
        $findim_project = MtrFindimProject::find( $id );
        return view( 'master.findim.project.upsert', compact( 'findim_project', 'status' ) );
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
        $trx = MtrFindimProject::find( $id );
        $trx->project_id = $request->project_id;
        $trx->project_desc = $request->project_desc;
        $trx->update();
        return redirect( 'findim_project' )->with( 'msg', 'Data is saved successfully.' );
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
        $trx = MtrFindimProject::find( $id );
        try {
            $trx->delete();
            return redirect( 'findim_project' )->with( 'msg', 'Data is deleted successfully.' );
         } catch (\Exception $e) {
            //   var_dump( $e->errorInfo );
            Log::error($e->errorInfo);
            Log::error($trx);
            return redirect()->route('error_db');
         }
    }
}
