<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\MtrStrukturGaji;
use App\Models\KaryawanGrade;

class MtrStrukturGajiController extends Controller
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
        $trx = MtrStrukturGaji::orderBy('id', 'asc')->get();
        return view('master.salary.index', compact('trx'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // dd(Route::getCurrentRoute()->getActionName());
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $status = 'create';
        $grade = KaryawanGrade::orderBy('id', 'asc')->get();
        $arr_tingkat = [];
        for ($x = 1; $x <= 22; $x++){
            array_push($arr_tingkat, $x);
        }
        return view( 'master.salary.upsert', compact( 'status', 'grade', 'arr_tingkat' ) );
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
        $gapok = str_replace(',', '', $request->gapok);
        $t_makan = str_replace(',', '', $request->t_makan);
        $t_transport = str_replace(',', '', $request->t_transport);
        $t_jabatan = str_replace(',', '', $request->t_jabatan);
        $t_telekomunikasi = str_replace(',', '', $request->t_telekomunikasi);
        $t_lain = str_replace(',', '', $request->t_lain);
        // 
        $where = ['id_grade'=>$request->id_grade, 'tingkat'=>$request->tingkat, 'tahun'=>$request->tahun, 'gapok'=>$gapok];
        $exists = MtrStrukturGaji::where($where)->exists();
        // dd($exists);
        if ($exists) {
            return redirect()->back()->withInput()->with('error', 'Data is already existing');
        }
        $trx = New MtrStrukturGaji;
        $trx->id_grade = $request->id_grade;
        $trx->tingkat = $request->tingkat;
        $trx->tahun = $request->tahun;
        $trx->gapok = $gapok;
        $trx->t_makan = $t_makan;
        $trx->t_transport = $t_transport;
        $trx->t_jabatan = $t_jabatan;
        $trx->t_telekomunikasi = $t_telekomunikasi;
        $trx->t_lain = $t_lain;
        $trx->save();
        return redirect( 'salary_str' )->with( 'msg', 'Data is saved successfully.' );
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
        // dd(Route::getCurrentRoute()->getActionName());
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $status = 'edit';
        $trx = MtrStrukturGaji::find( $id );
        $grade = KaryawanGrade::orderBy('id', 'asc')->get();
        $arr_tingkat = [];
        for ($x = 1; $x <= 22; $x++){
            array_push($arr_tingkat, $x);
        }
        return view( 'master.salary.upsert', compact( 'status', 'trx', 'grade', 'arr_tingkat' ) );
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
        $gapok = str_replace(',', '', $request->gapok);
        $t_makan = str_replace(',', '', $request->t_makan);
        $t_transport = str_replace(',', '', $request->t_transport);
        $t_jabatan = str_replace(',', '', $request->t_jabatan);
        $t_telekomunikasi = str_replace(',', '', $request->t_telekomunikasi);
        $t_lain = str_replace(',', '', $request->t_lain);
        // 
        $trx = MtrStrukturGaji::find( $id );
        $trx->id_grade = $request->id_grade;
        $trx->tingkat = $request->tingkat;
        $trx->tahun = $request->tahun;
        $trx->gapok = $gapok;
        $trx->t_makan = $t_makan;
        $trx->t_transport = $t_transport;
        $trx->t_jabatan = $t_jabatan;
        $trx->t_telekomunikasi = $t_telekomunikasi;
        $trx->t_lain = $t_lain;
        $trx->update();
        return redirect( 'salary_str' )->with( 'msg', 'Data is saved successfully.' );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd(Route::getCurrentRoute()->getActionName());
        $trx = MtrStrukturGaji::find( $id );
        $trx->delete();
        return redirect( 'salary_str' )->with( 'msg', 'Data is deleted successfully.' );
    }

    public function upload_template(Request $request)
    {
        if($request->hasFile('template')){
            $request->validate([
                'template'=>'required|file|mimes:xls,xlsx|max:2048',
            ]);
            $path = $request->file('template')->getPathname();
            $trx = Excel::toArray([], $path);
            $data0 = $trx[0];
            // dd($data0);
            // 1=ID GRADE
            // 2=TINGKAT
            // 3=Tahun
            // 4=GAJI POKOK
            // 5=TUNJANGAN MAKAN
            // 6=TUNJANGAN TRANSPORT
            // 7=TUNJANGAN JABATAN
            // 8=TUNJANGAN TELEKOMUNIKASI
            // 9=TUNJANGAN LAIN-LAIN
            $_1 = array_search(1, $data0[0]);
            $_2 = array_search(2, $data0[0]);
            $_3 = array_search(3, $data0[0]);
            $_4 = array_search(4, $data0[0]);
            $_5 = array_search(5, $data0[0]);
            $_6 = array_search(6, $data0[0]);
            $_7 = array_search(7, $data0[0]);
            $_8 = array_search(8, $data0[0]);
            $_9 = array_search(9, $data0[0]);
            // dimulai dari baris ke-3
            for ($x = 2; $x < count($data0); $x++) {
                $where = ['id_grade'=>$data0[$x][$_1], 'tingkat'=>$data0[$x][$_2], 'tahun'=>$data0[$x][$_3], 'gapok'=>$data0[$x][$_4]];
                MtrStrukturGaji::where($where)->delete();
                $trx = New MtrStrukturGaji;
                $trx->id_grade = $data0[$x][$_1];
                $trx->tingkat = $data0[$x][$_2];
                $trx->tahun = $data0[$x][$_3];
                $trx->gapok = $data0[$x][$_4];
                $trx->t_makan = $data0[$x][$_5];
                $trx->t_transport = $data0[$x][$_6];
                $trx->t_jabatan = $data0[$x][$_7];
                $trx->t_telekomunikasi = $data0[$x][$_8];
                $trx->t_lain = $data0[$x][$_9];
                $trx->save();
            }
            return redirect('/salary_str')->with('msg', 'Data is saved successfully.');
        }
    }
}
