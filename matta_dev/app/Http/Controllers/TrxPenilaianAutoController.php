<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Karyawan;
use App\Models\TrxPenilaian;
use App\Models\MtrPenilaianTipe;
use App\Models\TrxPenilaianAuto;
use App\Http\Controllers\TrxPenilaianController;
use URL;

class TrxPenilaianAutoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // karyawan tetap
        $kt = Karyawan::select('nik')->where(['kode_status'=>1])->get();
        // 
        $no_non_outsource = 0;
        $no_outsource = 0;
        $no_kt = 0;
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $trx_kt = TrxPenilaianAuto::whereIn('nik_karyawan', $kt)->whereNotIn('id_tipe', [6])->whereNull('id_trx')->orderBy( 'id', 'asc' )->get();
        $trx_non_outsource = TrxPenilaianAuto::whereNotIn('nik_karyawan', $kt)->whereNotIn('id_tipe', [6])->whereNull('id_trx')->orderBy( 'end_date', 'asc' )->get();
        $trx_outsource = TrxPenilaianAuto::whereNotIn('nik_karyawan', $kt)->whereIn('id_tipe', [6])->whereNull('id_trx')->orderBy( 'end_date', 'asc' )->get();
        if(Gate::allows('hc_nkp')){
            $trx_kt = TrxPenilaianAuto::whereIn('nik_karyawan',$arr_nik_nkp)->whereIn('nik_karyawan', $kt)->whereNotIn('id_tipe', [6])->whereNull('id_trx')->orderBy( 'id', 'asc' )->get();
            $trx_non_outsource = TrxPenilaianAuto::whereIn('nik_karyawan',$arr_nik_nkp)->whereNotIn('nik_karyawan', $kt)->whereNotIn('id_tipe', [6])->whereNull('id_trx')->orderBy( 'end_date', 'asc' )->get();
            $trx_outsource = TrxPenilaianAuto::whereIn('nik_karyawan',$arr_nik_nkp)->whereNotIn('nik_karyawan', $kt)->whereIn('id_tipe', [6])->whereNull('id_trx')->orderBy( 'end_date', 'asc' )->get();
        }
        // dd($trx_outsource);
        return view( 'formulir.penilaian.auto.index', compact( 'trx_non_outsource', 'trx_outsource', 'no_non_outsource', 'no_outsource', 'trx_kt', 'no_kt'));
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
        // dd($request->all());
        $data = array();
        $kt = Karyawan::select('nik')->where(['kode_status'=>1])->get();
        if($request->submit == 'execute'){
            // karyawan outsource
            if($request->is_outsource == 1){
                // $data = TrxPenilaianAuto::whereIn('id_tipe', [6])->whereNull('id_trx')->orderBy( 'end_date', 'asc' )->get();
                $id = $request->input('id_outsource', []);
                if(count($id)==0){
                    return redirect( 'penilaian_auto' )->with( 'msg', 'No Outsourcing Data is executed.' );
                }
                $data = TrxPenilaianAuto::whereIn('id', $id)->whereNotIn('nik_karyawan', $kt)->whereIn('id_tipe', [6])->whereNull('id_trx')->orderBy( 'end_date', 'asc' )->get();
            }
            // karyawan non outsource dan non kt
            else if($request->is_outsource == 0){
                // $data = TrxPenilaianAuto::whereNotIn('id_tipe', [6])->whereNull('id_trx')->orderBy( 'end_date', 'asc' )->get();
                $id = $request->input('id_non_outsource', []);
                if(count($id)==0){
                    return redirect( 'penilaian_auto' )->with( 'msg', 'No Reguler Data is executed.' );
                }
                $data = TrxPenilaianAuto::whereIn('id', $id)->whereNotIn('nik_karyawan', $kt)->whereNotIn('id_tipe', [6])->whereNull('id_trx')->orderBy( 'end_date', 'asc' )->get();
            }
            // karyawan non outsource dan kt
            else if($request->is_outsource == 2){
                $id = $request->input('id_kt', []);
                if(count($id)==0){
                    return redirect( 'penilaian_auto' )->with( 'msg', 'No Karyawan Tetap Data is executed.' );
                }
                $data = TrxPenilaianAuto::whereIn('id', $id)->whereIn('nik_karyawan', $kt)->whereNotIn('id_tipe', [6])->whereNull('id_trx')->orderBy( 'id', 'asc' )->get();
            }
        }
        elseif($request->submit == 'delete'){
            // karyawan outsource
            if($request->is_outsource == 1){
                $id = $request->input('id_outsource', []);
                if(count($id)==0){
                    return redirect( 'penilaian_auto' )->with( 'msg', 'No Outsourcing Data is deleted.' );
                }
                else{
                    foreach($id as $_id){
                        $this->destroy($_id);
                    }
                    return redirect( 'penilaian_auto' )->with( 'msg', 'Outsourcing Data is deleted successfully.' );
                }
            }
            // karyawan non outsource dan non kt
            else if($request->is_outsource == 0){
                $id = $request->input('id_non_outsource', []);
                if(count($id)==0){
                    return redirect( 'penilaian_auto' )->with( 'msg', 'No Reguler Data is deleted.' );
                }
                else{
                    foreach($id as $_id){
                        $this->destroy($_id);
                    }
                    return redirect( 'penilaian_auto' )->with( 'msg', 'Reguler Data is deleted successfully.' );
                }
            }
            // karyawan non outsource dan kt
            else if($request->is_outsource == 2){
                $id = $request->input('id_kt', []);
                if(count($id)==0){
                    return redirect( 'penilaian_auto' )->with( 'msg', 'No Karyawan Tetap Data is deleted.' );
                }
                else{
                    foreach($id as $_id){
                        $this->destroy($_id);
                    }
                    return redirect( 'penilaian_auto' )->with( 'msg', 'Karyawan Tetap Data is deleted successfully.' );
                }
            }
        }
        // dd($data);
        foreach ($data as $_data){
            $trx = New TrxPenilaian;
            $trx->nik_karyawan = $_data->nik_karyawan;
            $trx->nik_penilai_1 = $_data->nik_penilai_1;
            $trx->karyawan_property = (new TrxPenilaianController)->getNikProperty($_data->nik_karyawan);
            $trx->penilai1_property = (new TrxPenilaianController)->getNikProperty($_data->nik_penilai_1);
            if(!empty($_data->nik_penilai_2)){
                $trx->nik_penilai_2 = $_data->nik_penilai_2;
                $trx->penilai2_property = (new TrxPenilaianController)->getNikProperty($_data->nik_penilai_2);
            }
            if(!empty($_data->nik_penilai_3)){
                $trx->nik_penilai_3 = $_data->nik_penilai_3;
                $trx->penilai3_property = (new TrxPenilaianController)->getNikProperty($_data->nik_penilai_3);
            }
            $trx->id_tipe = $_data->id_tipe;
            $trx->id_status = $_data->id_status;
            $trx->save();
            // $this->destroy((new OpenDataController)->encryptWithKey($_data->id));
            $this->destroy($_data->id);
            // maker masih di hardcode 01031908799 => khatrin hco
            (new TrxPenilaianController)->insertLog($trx->id, $_data->id_status, auth()->user()->nik);
            // 
            $array = json_decode((new TrxPenilaianController)->getPertanyaanPenilaian(), true);
            $id_level = json_decode($trx->karyawan_property, true)['id_level'];
            // 6 = Penilaian Outsourcing
            if($_data->id_tipe == 6){
                $array = json_decode((new TrxPenilaianController)->getPertanyaanPenilaianOutsourcing(0), true);
                // 3 = Supervisor (SPV)
                // untuk level spv keatas
                if($id_level > 2){
                    $array = json_decode((new TrxPenilaianController)->getPertanyaanPenilaianOutsourcing(1), true);
                }
                foreach($array as $values) {
                    (new TrxPenilaianController)->insertPenilaianSkorOutsourcing($trx->id, $values);
                }
            }
            // selain Penilaian Outsourcing
            else{
                foreach($array as $values) {
                    // hanya level spv ke atas (>2) yang diberikan pertanyaan ini
                    if($values['is_spv']==1){
                        if($id_level > 2){
                            (new TrxPenilaianController)->insertPenilaianSkor($trx->id,$values['id_nilai3'],$values['penilaian_1'],$values['penilaian_2'],$values['penilaian_2_ket'],$values['penilaian_3'],$values['is_spv']);}
                    }
                    // semua level mendapatkan pertanyaan ini
                    else{
                        (new TrxPenilaianController)->insertPenilaianSkor($trx->id,$values['id_nilai3'],$values['penilaian_1'],$values['penilaian_2'],$values['penilaian_2_ket'],$values['penilaian_3'],$values['is_spv']);
                    }
                }
            }
            // parameter yang dipassing ke sendemail
            $karyawan = json_decode($trx->karyawan_property, true);
            $atasan = json_decode($trx->penilai1_property, true);
            // 
            $nik = $karyawan['nik'];
            $pic = 'nik_karyawan';
            $mailData['title'] = 'Formulir Penilaian Karyawan';
            // 
            $mailData['filename'] = 'emails.penilaian0';
            $mailData['to'] = $karyawan['email'];
            if($trx->id_tipe == 2 || $trx->id_tipe == 4){
                $mailData['title'] .= '- Reguler';
            }else if($trx->id_tipe == 3 || $trx->id_tipe == 5){
                $mailData['title'] .= '- Assesment';
            }
            // 6 = Penilaian Outsourcing
            else if($trx->id_tipe == 6){
                $mailData['title'] .= ' - Outsourcing';
                $mailData['filename'] = 'emails.penilaian0_outsourcing';
                $mailData['to'] = $atasan['email'];
                $nik = $atasan['nik'];
                $pic = 'nik_penilai_1';
            }
            $mailData['data'] = ['nama'=>$karyawan['nama'].'/'.$karyawan['nik'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('penilaianskor.show', [(new OpenDataController)->encryptWithKey($trx->id), 'nik' => (new OpenDataController)->encryptWithKey($nik), 'id_status' => (new OpenDataController)->encryptWithKey($_data->id_status), 'pic' => (new OpenDataController)->encryptWithKey($pic)]), 'id_trx' => $trx->id ];
            $todolist = ['id_trx' => $trx->id, 'nik' => $nik, 'id_status' => $_data->id_status, 'pic' => $pic];
            (new TrxPenilaianController)->insertToDoList($todolist, $mailData);
            (new TrxPenilaianController)->sendEmail($mailData);
        }
        return redirect( 'penilaian_auto' )->with( 'msg', 'Data is executed successfully.' );
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
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $id = (new OpenDataController)->decryptWithKey($id);
        $trx = TrxPenilaianAuto::find( $id );
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        $tipe = MtrPenilaianTipe::orderBy('tipe_penilaian', 'asc')->get();
        if(Gate::allows('hc_nkp')){
            $karyawan = Karyawan::select('nik', 'nama')->whereIn('nik',$arr_nik_nkp)->where($where)->orderBy('nama', 'asc')->get();
            $tipe = MtrPenilaianTipe::whereIn('id',[6])->orderBy('tipe_penilaian', 'asc')->get();
        }
        // return response()->json(['trx' => $trx]);
        return view( 'formulir.penilaian.auto.insert', compact( 'trx', 'karyawan', 'tipe' ) );
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
        $id = (new OpenDataController)->decryptWithKey($id);
        $trx = TrxPenilaianAuto::find( $id );
        $trx->nik_karyawan = $request->nik_karyawan;
        $trx->nik_penilai_1 = $request->penilai_1;
        $trx->nik_penilai_2 = $request->penilai_2;
        $trx->nik_penilai_3 = $request->penilai_3;
        $trx->id_tipe = $request->id_tipe;
        $trx->update();
        return redirect( 'penilaian_auto' )->with( 'msg', 'Data is saved successfully.' );
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
        // $id = (new OpenDataController)->decryptWithKey($id);
        $trx = TrxPenilaianAuto::find( $id );
        $trx->delete();
        // return redirect( 'penilaian_auto' )->with( 'msg', 'Data is deleted successfully.' );
    }
}
