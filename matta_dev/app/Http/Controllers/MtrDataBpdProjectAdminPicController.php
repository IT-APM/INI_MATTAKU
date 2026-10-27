<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// 
use App\Models\Propinsi;
use App\Models\PropinsiKota;
use App\Models\Negara;
use App\Models\Karyawan;
use App\Models\BpdBiaya;
use App\Models\BpdCoa;
use App\Models\BpdKelompok;
use App\Models\BpdNikPeran;
use App\Models\BpdNikPC;
use App\Models\BpdNonProyekBiayaDalamNegeri;
use App\Models\BpdNonProyekBiayaLuarNegeri;
use App\Models\BpdPeran;
use App\Models\BpdProyek;
use App\Models\BpdProyekAdmin;
use App\Models\BpdProyekUangSaku;
use App\Models\BpdProyekUangSakuKelompok;
use App\Models\BpdStatus;
// 
use App\Models\TrxBpdDetailBiaya;
use App\Models\TrxBpdDetailInfo;
use App\Models\TrxBpdLog;
use App\Models\TrxBpdMaster;
use App\Models\ToDoList;
use App\Models\ToDoListLog;
// 
use App\Models\MtrUtility;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
// 
use Carbon\Carbon;
use DB;
use Elibyy\TCPDF\Facades\TCPDF;
use Excel;
// 

class MtrDataBpdProjectAdminPicController extends Controller
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
        // $trx = BpdProyek::orderBy( 'project_id', 'asc' )->get();
        // return view( 'master.bpd.project_admin.index', compact( 'trx', 'no' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // dd($request->all());
        $status = 'create';
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::where($where)->orderBy( 'nama', 'asc' )->get();
        $project_id  = Crypt::decrypt($request->project_id);
        return view( 'master.bpd.project_admin.upsert_pic', compact( 'status','karyawan', 'project_id' ) );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $project_id = Crypt::decrypt($request->project_id);
        $trx = New BpdProyekAdmin;
        $trx->id_proyek = $project_id;
        $trx->admin = $request->admin;
        $trx->save();
        return redirect()->route('mtrdataprojectadmin.show', Crypt::encrypt($project_id))->with( 'msg', 'Data is saved successfully.' );
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
    public function edit(Request $request, $id)
    {
        //
        $status = 'edit';
        $trx = BpdProyekAdmin::find($id);
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::where($where)->orderBy( 'nama', 'asc' )->get();
        $project_id  = Crypt::decrypt($request->project_id);
        return view( 'master.bpd.project_admin.upsert_pic', compact( 'trx', 'status', 'karyawan', 'project_id' ) );
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
        $project_id = Crypt::decrypt($request->project_id);
        $trx = BpdProyekAdmin::find($id);
        $trx->id_proyek = $project_id;
        $trx->admin = $request->admin;
        $trx->update();
        return redirect()->route('mtrdataprojectadmin.show', Crypt::encrypt($project_id))->with( 'msg', 'Data is saved successfully.' );
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
        $trx = BpdProyekAdmin::find($id);
        $trx->delete();
        return redirect()->route('mtrdataprojectadmin.show', $request->project_id)->with( 'msg', 'Data is deleted successfully.' );
    }
}
