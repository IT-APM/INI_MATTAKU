<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Karyawan;
use App\Models\TrxPenilaian;
use App\Models\MtrPenilaianTipe;
use App\Models\TrxPenilaianAuto;
use App\Models\ToDoList;
// 
use URL;

class TrxPenilaianReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $no_non_outsource = 0;
        $no_outsource = 0;
        $trx_non_outsource = TrxPenilaianAuto::whereNotIn('id_tipe', [6])->whereNotNull('id_trx')->orderBy( 'end_date', 'asc' )->get();
        $trx_outsource = TrxPenilaianAuto::whereIn('id_tipe', [6])->whereNotNull('id_trx')->orderBy( 'end_date', 'asc' )->get();
        if(Gate::allows('hc_nkp')){
            $trx_non_outsource = TrxPenilaianAuto::whereIn('nik_karyawan',$arr_nik_nkp)->whereNotIn('id_tipe', [6])->whereNotNull('id_trx')->orderBy( 'end_date', 'asc' )->get();
            $trx_outsource = TrxPenilaianAuto::whereIn('nik_karyawan',$arr_nik_nkp)->whereIn('id_tipe', [6])->whereNotNull('id_trx')->orderBy( 'end_date', 'asc' )->get();
        }
        return view( 'formulir.penilaian.reminder.index', compact( 'trx_non_outsource', 'trx_outsource', 'no_non_outsource', 'no_outsource'));
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
        $submit = $request->all();
        // non outsource
        if($request->is_outsource == 0){
            $id = $request->input('id_non_outsource', []);
            if(count($id)>0){
                if($request->submit == 'execute'){
                    foreach($id as $_id){
                        $data = ToDoList::find( $_id );
                        $where = ['id_trx' => $_id];
                        $nik_karyawan = TrxPenilaianAuto::where($where)->first()->nik_karyawan;
                        $where = ['nik' => $nik_karyawan];
                        $layer1 = Karyawan::where($where)->first();
                        // dd($nik_karyawan, $layer1->_layer1->nama, $_id);
                        $mailData['title'] = $data->title;
                        $mailData['filename'] = $data->filename;
                        $mailData['to'] = $data->email;
                        $mailData['data'] = ['nama'=>$data->nama.'/'.$nik_karyawan, 'jabatan'=>$data->jabatan, 'unit_kerja'=>$data->unit_kerja, 'atasan'=>$layer1->_layer1->nama, 'link'=> $data->link, 'id_trx' => $_id ];
                        (new TrxPenilaianController)->sendEmail($mailData);
                        $this->destroy($_id);
                    }
                    return redirect( 'penilaian_reminder' )->with( 'msg', 'Reguler Data is executed successfully.' );
                }else if($request->submit == 'delete'){
                    foreach($id as $_id){
                        $this->destroy($_id);
                    }
                    return redirect( 'penilaian_reminder' )->with( 'msg', 'Reguler Data is deleted successfully.' );
                }
            }else{
                if($request->submit == 'execute'){
                    return redirect( 'penilaian_reminder' )->with( 'msg', 'No Reguler Data is executed.' );
                }else if($request->submit == 'delete'){
                    return redirect( 'penilaian_reminder' )->with( 'msg', 'No Reguler Data is deleted.' );
                }
            }
        }
        // outsource
        else if($request->is_outsource == 1){
            $id = $request->input('id_outsource', []);
            if(count($id)>0){
                if($request->submit == 'execute'){
                    foreach($id as $_id){
                        $data = ToDoList::find( $_id );
                        $where = ['id_trx' => $_id];
                        $nik_karyawan = TrxPenilaianAuto::where($where)->first()->nik_karyawan;
                        $where = ['nik' => $nik_karyawan];
                        $layer1 = Karyawan::where($where)->first();
                        // dd($nik_karyawan, $layer1->_layer1->nama, $_id);
                        $mailData['title'] = $data->title;
                        $mailData['filename'] = $data->filename;
                        $mailData['to'] = $data->email;
                        $mailData['data'] = ['nama'=>$data->nama.'/'.$nik_karyawan, 'jabatan'=>$data->jabatan, 'unit_kerja'=>$data->unit_kerja, 'atasan'=>$layer1->_layer1->nama, 'link'=> $data->link, 'id_trx' => $_id ];
                        (new TrxPenilaianController)->sendEmail($mailData);
                        $this->destroy($_id);
                    }
                    return redirect( 'penilaian_reminder' )->with( 'msg', 'Outsourcing Data is executed successfully.' );
                }else if($request->submit == 'delete'){
                    foreach($id as $_id){
                        $this->destroy($_id);
                    }
                    return redirect( 'penilaian_reminder' )->with( 'msg', 'Outsourcing Data is deleted successfully.' );
                }
            }else{
                if($request->submit == 'execute'){
                    return redirect( 'penilaian_reminder' )->with( 'msg', 'No Outsourcing Data is executed.' );
                }else if($request->submit == 'delete'){
                    return redirect( 'penilaian_reminder' )->with( 'msg', 'No Outsourcing Data is deleted.' );
                }
            }
        }
        // dd($submit);
        // foreach($submit as $_id => $value){
        //     if(str_starts_with($_id, 'submit')){
        //         if(empty($request->nonaktif)){
        //             if($value == 'execute'){
        //                 return redirect( 'penilaian_reminder' )->with( 'msg', 'No Data is executed.' );
        //             }else if($value == 'delete'){
        //                 return redirect( 'penilaian_reminder' )->with( 'msg', 'No Data is deleted.' );
        //             }
        //             else if($value == 'store'){
        //                 $where = ['nik' => $request->nik_karyawan];
        //                 $data = Karyawan::where($where)->first();
        //                 // parameter yang dipassing ke sendemail
        //                 $karyawan = json_decode($this->getNikProperty($data->nik), true);
        //                 $atasan = json_decode($this->getNikProperty($data->layer1), true);
        //                 // 
        //                 $mailData['title'] = 'Formulir Karyawan Non Aktif';
        //                 $mailData['filename'] = 'emails.nonaktif0_auto';
        //                 $mailData['to'] = $karyawan['email'];
        //                 $mailData['data'] = ['nama'=>$karyawan['nama'].'/'.$karyawan['nik'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('nonaktif.create') ];
        //                 (new TrxNonaktifController)->sendEmail($mailData);
        //                 return redirect( 'penilaian_reminder' )->with( 'msg', 'Data is executed successfully.' );
        //             }
        //         }
        //         else{
        //             if($value == 'execute'){
        //                 $nonaktif = $request->nonaktif;
        //                 foreach($nonaktif as $_nonaktif){
        //                     $data = TrxNonaktifAuto::find( $_nonaktif );
        //                     $this->destroy($_nonaktif);
        //                     // parameter yang dipassing ke sendemail
        //                     $karyawan = json_decode($this->getNikProperty($data['nik_karyawan']), true);
        //                     $atasan = json_decode($this->getNikProperty($data['nik_atasan']), true);
        //                     // 
        //                     $mailData['title'] = 'Formulir Karyawan Non Aktif';
        //                     $mailData['filename'] = 'emails.nonaktif0_auto';
        //                     $mailData['to'] = $karyawan['email'];
        //                     $mailData['data'] = ['nama'=>$karyawan['nama'].'/'.$karyawan['nik'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('nonaktif.create') ];
        //                     (new TrxNonaktifController)->sendEmail($mailData);
        //                 }
        //                 return redirect( 'penilaian_reminder' )->with( 'msg', 'Data is executed successfully.' );
        //             }else if($value == 'delete'){
        //                 $nonaktif = $request->nonaktif;
        //                 foreach($nonaktif as $_nonaktif){
        //                     $this->destroy($_nonaktif);
        //                 }
        //                 return redirect( 'penilaian_reminder' )->with( 'msg', 'Data is deleted successfully.' );
        //             }
        //         }
        //     }
        // }
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
        $where = ['id_trx' => $id];
        $trx = TrxPenilaianAuto::where($where);
        $trx->delete();
    }
}
