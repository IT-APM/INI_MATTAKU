<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Perusahaan;
use App\Models\Divisi;
use App\Models\Departemen;
use App\Models\SubDepartemen;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\BpdNikPeran;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PerusahaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // company_new
        // $data = DB::select("SELECT `company_new` FROM `mtr_tbl_karyawan_reorganisasi` GROUP BY `company_new`");
        // $arr_data1 = [];
        // foreach($data as $_data){
        //     $data1 = DB::select("SELECT `id`,`nama`,`is_active` FROM `mtr_tbl_perusahaan` WHERE `nama`='$_data->company_new'");
        //     // dd($data1);
        //     // check apakah ada company_new
        //     if(empty($data1)){
        //         array_push($arr_data1, $_data);
        //     }
        // }
        // dd($arr_data1);
        // directorate_new
        // $data = DB::select("SELECT `company_new`,`directorate_new` FROM `mtr_tbl_karyawan_reorganisasi` GROUP BY `company_new`,`directorate_new`");
        // $arr_data1 = [];
        // foreach($data as $_data){
        //     $data1 = DB::select("SELECT a.`id` FROM `mtr_tbl_divisi` AS b LEFT JOIN `mtr_tbl_perusahaan` AS a ON b.`kode_perusahaan`=a.`id` WHERE b.`nama`='$_data->directorate_new' AND a.`nama`='$_data->company_new'");
        //     // check apakah ada directorate_new di company_new
        //     if(empty($data1)){
        //         array_push($arr_data1, ['id'=>DB::select("SELECT `id` FROM `mtr_tbl_perusahaan` WHERE `nama`='$_data->company_new'")[0]->id, 'nama'=>$_data->directorate_new]);
        //     }
        // }
        // foreach($arr_data1 as $_data){
        //     $trx = New Divisi;
        //     $trx->nama = $_data['nama'];
        //     $trx->kode_perusahaan = $_data['id'];
        //     $trx->is_active = 1;
        //     $trx->save();
        // }
        // dd($arr_data1);
        // division_new
        // $data = DB::select("SELECT `company_new`,`directorate_new`,`division_new` FROM `mtr_tbl_karyawan_reorganisasi` GROUP BY `company_new`,`directorate_new`,`division_new`");
        // $arr_data1 = [];
        // foreach($data as $_data){
        //     $data1 = DB::select("SELECT b.`id` FROM `mtr_tbl_departemen` AS c LEFT JOIN `mtr_tbl_divisi` AS b ON c.`kode_divisi`=b.`id` LEFT JOIN `mtr_tbl_perusahaan` AS a ON b.`kode_perusahaan`=a.`id` WHERE c.`nama`='$_data->division_new' AND b.`nama`='$_data->directorate_new' AND a.`nama`='$_data->company_new'");
        //     // check apakah ada division_new di directorate_new di company_new
        //     if(empty($data1)){
        //         array_push($arr_data1, ['id'=>DB::select("SELECT b.id FROM `mtr_tbl_divisi` AS b LEFT JOIN `mtr_tbl_perusahaan` AS a ON b.`kode_perusahaan`=a.`id` WHERE b.`nama`='$_data->directorate_new' AND a.`nama`='$_data->company_new'")[0]->id, 'nama'=>$_data->division_new]);
        //     }
        // }
        // foreach($arr_data1 as $_data){
        //     $trx = New Departemen;
        //     $trx->nama = $_data['nama'];
        //     $trx->kode_divisi = $_data['id'];
        //     $trx->is_active = 1;
        //     $trx->save();
        // }
        // dd($arr_data1);
        // department_new
        // $data = DB::select("SELECT `company_new`,`directorate_new`,`division_new`,`department_new` FROM `mtr_tbl_karyawan_reorganisasi` GROUP BY `company_new`,`directorate_new`,`division_new`,`department_new`");
        // $arr_data1 = [];
        // foreach($data as $_data){
        //     $data1 = DB::select("SELECT c.id FROM `mtr_tbl_sub_departemen` AS d LEFT JOIN `mtr_tbl_departemen` AS c ON d.`kode_departemen`=c.`id` LEFT JOIN `mtr_tbl_divisi` AS b ON c.`kode_divisi`=b.`id` LEFT JOIN `mtr_tbl_perusahaan` AS a ON b.`kode_perusahaan`=a.`id` WHERE d.`nama`='$_data->department_new' AND c.`nama`='$_data->division_new' AND b.`nama`='$_data->directorate_new' AND a.`nama`='$_data->company_new'");
        //     // check apakah ada department_new di division_new di directorate_new di company_new
        //     if(empty($data1)){
        //         array_push($arr_data1, ['id'=>DB::select("SELECT c.`id` FROM `mtr_tbl_departemen` AS c LEFT JOIN `mtr_tbl_divisi` AS b ON c.`kode_divisi`=b.`id` LEFT JOIN `mtr_tbl_perusahaan` AS a ON b.`kode_perusahaan`=a.`id` WHERE c.`nama`='$_data->division_new' AND b.`nama`='$_data->directorate_new' AND a.`nama`='$_data->company_new'")[0]->id, 'nama'=>$_data->department_new]);
        //     }
        // }
        // foreach($arr_data1 as $_data){
        //     $trx = New SubDepartemen;
        //     $trx->nama = $_data['nama'];
        //     $trx->kode_departemen = $_data['id'];
        //     $trx->is_active = 1;
        //     $trx->save();
        // }
        // dd($arr_data1);
        // occupation_new
        // $data = DB::select("SELECT `occupation_new` FROM `mtr_tbl_karyawan_reorganisasi` GROUP BY `occupation_new`");
        // $arr_data1 = [];
        // foreach($data as $_data){
        //     $data1 = DB::select("SELECT `id`,`jabatan` FROM `mtr_tbl_jabatan` WHERE `jabatan`='$_data->occupation_new'");
        //     // check apakah ada occupation_new
        //     if(empty($data1)){
        //         array_push($arr_data1, ['jabatan'=>$_data->occupation_new]);
        //     }
        // }
        // foreach($arr_data1 as $_data){
        //     $trx = New Jabatan;
        //     $trx->jabatan = $_data['jabatan'];
        //     $trx->save();
        // }
        // dd($arr_data1);
        // update mtr_tbl_karyawan
        // $data = DB::select("SELECT `nip_new`,`company_new`,`directorate_new`,`division_new`,`department_new`,`occupation_new`,`nip_layer1_new`,`nip_layer2_new`,`nip_layer3_new` FROM `mtr_tbl_karyawan_reorganisasi` WHERE `nip`=`nip_new`");
        // $arr_data1 = [];
        // foreach($data as $_data){
        //     $data1 = DB::select("SELECT a.id AS `kode_perusahaan`,b.id AS `kode_divisi`,c.id AS `kode_departemen`,d.id AS `kode_sub_departemen` FROM `mtr_tbl_sub_departemen` AS d LEFT JOIN `mtr_tbl_departemen` AS c ON d.`kode_departemen`=c.`id` LEFT JOIN `mtr_tbl_divisi` AS b ON c.`kode_divisi`=b.`id` LEFT JOIN `mtr_tbl_perusahaan` AS a ON b.`kode_perusahaan`=a.`id` WHERE d.`nama`='$_data->department_new' AND c.`nama`='$_data->division_new' AND b.`nama`='$_data->directorate_new' AND a.`nama`='$_data->company_new'")[0];
        //     $jabatan = DB::select("SELECT `id` FROM `mtr_tbl_jabatan` WHERE `jabatan`='$_data->occupation_new'")[0];
        //     array_push($arr_data1, ['kode_jabatan'=>$jabatan->id, 'layer1'=>$_data->nip_layer1_new, 'layer2'=>$_data->nip_layer2_new, 'layer3'=>$_data->nip_layer3_new, 'nik'=>$_data->nip_new, 'kode_perusahaan'=>$data1->kode_perusahaan, 'kode_divisi'=>$data1->kode_divisi, 'kode_departemen'=>$data1->kode_departemen, 'kode_sub_departemen'=>$data1->kode_sub_departemen]);
        // }
        // foreach($arr_data1 as $_data){
        //     Karyawan::where('nik', $_data['nik'])->update([
        //         'kode_jabatan' => $_data['kode_jabatan'],
        //         'layer1' => $_data['layer1'],
        //         'layer2' => $_data['layer2'],
        //         'layer3' => $_data['layer3'],
        //         'kode_perusahaan' => $_data['kode_perusahaan'],
        //         'kode_divisi' => $_data['kode_divisi'],
        //         'kode_departemen' => $_data['kode_departemen'],
        //         'kode_sub_departemen' => $_data['kode_sub_departemen'],
        //     ]);
        // }
        // dd($arr_data1);
        // mtr_tbl_bpd_nik_peran
        // $data = DB::select("SELECT a.`nik`,a.`admin`,a.`id_peran`,a.`is_active`,b.`nik_nkp` FROM `mtr_tbl_bpd_nik_peran` AS a INNER JOIN `mtr_tbl_karyawan_nik_2_nkp` AS b ON a.`nik`=b.`nik`");
        // $arr_data1 = [];
        // foreach($data as $_data){
        //     $trx = New BpdNikPeran;
        //     $trx->nik = $_data->nik_nkp;
        //     $trx->admin = $_data->admin;
        //     $trx->id_peran = $_data->id_peran;
        //     $trx->is_active = $_data->is_active;
        //     $trx->save();
        // }
        // dd($arr_data1);
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $no = 0;
        $perusahaan = Perusahaan::orderBy( 'nama', 'asc' )->get();
        return view( 'master.perusahaan.index', compact( 'perusahaan', 'no' ) );
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
        return view( 'master.perusahaan.upsert', compact( 'status' ) );
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
        $perusahaan = New Perusahaan;
        $perusahaan->nama = $request->nama;
        $perusahaan->nama_singkat = '';
        if($request->is_active == 'on'){
            $perusahaan->is_active = 1;
        }else{
            $perusahaan->is_active = 0;
        }
        $perusahaan->save();
        return redirect( 'perusahaan' )->with( 'msg', 'Data is saved successfully.' );
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
        $perusahaan = Perusahaan::find( $id );
        return view( 'master.perusahaan.upsert', compact( 'perusahaan', 'status' ) );
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
        $perusahaan = Perusahaan::find( $id );
        $perusahaan->nama = $request->nama;
        if($request->is_active == 'on'){
            $perusahaan->is_active = 1;
        }else{
            $perusahaan->is_active = 0;
        }
        $perusahaan->update();
        return redirect( 'perusahaan' )->with( 'msg', 'Data is saved successfully.' );
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
        $perusahaan = Perusahaan::find( $id );
        try {
            $perusahaan->delete();
            return redirect( 'perusahaan' )->with( 'msg', 'Data is deleted successfully.' );
         } catch (\Exception $e) {
            //   var_dump( $e->errorInfo );
            Log::error($e->errorInfo);
            // Log::error($perusahaan);
            return redirect()->route('error_db');
         }
    }
}
