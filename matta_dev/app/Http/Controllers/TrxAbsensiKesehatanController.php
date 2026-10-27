<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\IphoneUser;
use App\Models\AbsensiKesehatan;
// 
use Carbon\Carbon;
use DB;
use Elibyy\TCPDF\Facades\TCPDF;

class TrxAbsensiKesehatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $this->authorize('iphone');
        $id = auth()->user()->nik;
        $tanggal = date('Y-m-d');
        $where = ['karyawan' => $id, 'tanggal_input' => $tanggal];
        $n = AbsensiKesehatan::where($where)->count();
        if($n > 0){
            return view('kesehatan.kesehatan_ok_web');
        }else{
            return view('kesehatan.insert_web', compact('id'));
        }
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
        $tanggal = date('Y-m-d');
        $where = ['karyawan' => $id, 'tanggal_input' => $tanggal];
        $n = AbsensiKesehatan::where($where)->count();
        if($n > 0){
            return view('kesehatan.kesehatan_ok');
        }else{
            return view('kesehatan.insert', compact('id'));
        }
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
        $tanggal = date('Y-m-d');
        $where = ['nik' => $id, 'is_aktif' => 1];
        $karyawan = Karyawan::where($where)->first();
        // ring1
        $trx = new AbsensiKesehatan;
        $trx->karyawan = $id;
        $trx->karyawan_property = $this->getNikProperty($id);
        $trx->tanggal_input = $tanggal;
        $trx->ring_ke = 'ring1';
        $trx->ring_isian = $request->ring1;
        if($request->has('sakit_ring1')){
            $trx->siapa = $karyawan->nama;
            $trx->sakit = $request->sakit_ring1;
            $trx->tanggal_sakit = $request->tanggal_ring1;
        }
        $trx->save();
        // ring2
        $siapa_ring2 = $request->input('siapa_ring2', []);
        $sakit_ring2 = $request->input('sakit_ring2', []);
        $tanggal_ring2 = $request->input('tanggal_ring2', []);
        if($request->has('sakit_ring2')){
            for ($x = 0; $x < count($sakit_ring2); $x++) {
                $trx = new AbsensiKesehatan;
                $trx->karyawan = $id;
                $trx->karyawan_property = $this->getNikProperty($id);
                $trx->tanggal_input = $tanggal;
                $trx->ring_ke = 'ring2';
                $trx->ring_isian = $request->ring2;
                $trx->siapa = $siapa_ring2[$x];
                $trx->sakit = $sakit_ring2[$x];
                $trx->tanggal_sakit = $tanggal_ring2[$x];
                $trx->save();
            }
        }else{
            $trx = new AbsensiKesehatan;
            $trx->karyawan = $id;
            $trx->karyawan_property = $this->getNikProperty($id);
            $trx->tanggal_input = $tanggal;
            $trx->ring_ke = 'ring2';
            $trx->ring_isian = $request->ring2;
            $trx->save();
        }
        // ring3
        $siapa_ring3 = $request->input('siapa_ring3', []);
        $sakit_ring3 = $request->input('sakit_ring3', []);
        $tanggal_ring3 = $request->input('tanggal_ring3', []);
        if($request->has('sakit_ring3')){
            for ($x = 0; $x < count($sakit_ring3); $x++) {
                $trx = new AbsensiKesehatan;
                $trx->karyawan = $id;
                $trx->karyawan_property = $this->getNikProperty($id);
                $trx->tanggal_input = $tanggal;
                $trx->ring_ke = 'ring3';
                $trx->ring_isian = $request->ring3;
                $trx->siapa = $siapa_ring3[$x];
                $trx->sakit = $sakit_ring3[$x];
                $trx->tanggal_sakit = $tanggal_ring3[$x];
                $trx->save();
            }
        }else{
            $trx = new AbsensiKesehatan;
            $trx->karyawan = $id;
            $trx->karyawan_property = $this->getNikProperty($id);
            $trx->tanggal_input = $tanggal;
            $trx->ring_ke = 'ring3';
            $trx->ring_isian = $request->ring3;
            $trx->save();
        }
        // 
        // return response()->json( [ 'data' => $request->all() ], 200 );
        // return view('kesehatan.insert', compact('id'));
        $where = ['nik' => $id];
        $check = IphoneUser::where($where)->exists();
        if($check){
            return view('kesehatan.kesehatan_ok_web');
        }else{
            return view('kesehatan.kesehatan_ok');
        }
    }

    public function getNikProperty($nik)
    {
        $where = ['nik' => $nik, 'is_aktif' => 1];
        $data = Karyawan::select('mtr_tbl_karyawan.nik', 'mtr_tbl_karyawan.nama', 'mtr_tbl_karyawan.email', 'mtr_tbl_karyawan.telepon1', 'mtr_tbl_perusahaan.nama as perusahaan', 'mtr_tbl_divisi.nama as divisi', 'mtr_tbl_departemen.nama as dep', 'mtr_tbl_sub_departemen.nama as sub_dep', 'mtr_tbl_level.level as level', 'mtr_tbl_jabatan.jabatan as jabatan', 'mtr_tbl_level.id as id_level')
            ->leftJoin('mtr_tbl_perusahaan', 'mtr_tbl_perusahaan.id', '=', 'mtr_tbl_karyawan.kode_perusahaan')
            ->leftJoin('mtr_tbl_divisi', 'mtr_tbl_divisi.id', '=', 'mtr_tbl_karyawan.kode_divisi')
            ->leftJoin('mtr_tbl_departemen', 'mtr_tbl_departemen.id', '=', 'mtr_tbl_karyawan.kode_departemen')
            ->leftJoin('mtr_tbl_sub_departemen', 'mtr_tbl_sub_departemen.id', '=', 'mtr_tbl_karyawan.kode_sub_departemen')
            ->leftJoin('mtr_tbl_level', 'mtr_tbl_level.id', '=', 'mtr_tbl_karyawan.kode_level')
            ->leftJoin('mtr_tbl_jabatan', 'mtr_tbl_jabatan.id', '=', 'mtr_tbl_karyawan.kode_jabatan')
            ->where($where)->first();
        return json_encode($data);
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
