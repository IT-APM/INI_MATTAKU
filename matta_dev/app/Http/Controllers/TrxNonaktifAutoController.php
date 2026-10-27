<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Karyawan;
use App\Models\TrxNonaktifAuto;
use App\Models\NonaktifStatus;
use App\Models\Perusahaan;
use App\Models\MtrPenilaianPerpanjang;
use App\Models\NonaktifAlasan;
use App\Models\TrxPenilaian;

class TrxNonaktifAutoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        function forgetById($collection,$id){
            foreach($collection as $key => $item){
                if($item->id == $id){
                    $collection->forget($key);
                    break;
                }
            }
            return $collection;
        }
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $no = 0;
        $trx = TrxNonaktifAuto::orderBy( 'end_date', 'asc' )->get();
        if(Gate::allows('hc_nkp')){
            $trx = TrxNonaktifAuto::whereIn('nik_karyawan',$arr_nik_nkp)->orderBy( 'end_date', 'asc' )->get();
        }
        // filter parameter
        $nama = '';
        $atasan = '';
        $arr_perusahaan = array();
        $arr_perpanjangan = array();
        $perusahaan = Perusahaan::select('id','nama')->whereNotIn('id', [0,3])->orderBy('nama', 'asc')->get();
        $perpanjangan = MtrPenilaianPerpanjang::select('id','informasi')->orderBy('informasi', 'asc')->get();
        // start filter
        if($request->filter == true){
            $nama = $request->nama;
            $atasan = $request->atasan;
            $_perusahaan = $request->input('perusahaan', []);
            $_perpanjangan = $request->input('perpanjangan', []);
            if(count($_perusahaan) > 0){
                $arr_perusahaan = $_perusahaan;
            }
            else{
                $arr_perusahaan = array();
            }
            if(count($_perpanjangan) > 0){
                $arr_perpanjangan = $_perpanjangan;
            }
            else{
                $arr_perpanjangan = array();
            }
            $_nama = Karyawan::select('nik')->where('nama','LIKE','%'.$nama.'%')->get();
            $_atasan = Karyawan::select('nik')->where('nama','LIKE','%'.$atasan.'%')->get();
            $nik_karyawan = Karyawan::select('nik')->whereIn('kode_perusahaan', $arr_perusahaan)->get()->toArray();
            $trx = TrxNonaktifAuto::whereIn('nik_karyawan', $nik_karyawan)->whereIn('nik_karyawan', $_nama)->whereIn('nik_atasan', $_atasan)->orderBy('id', 'desc')->get();
            if(Gate::allows('hc_nkp')){
                $trx = TrxNonaktifAuto::whereIn('nik_karyawan',$arr_nik_nkp)->whereIn('nik_karyawan', $nik_karyawan)->whereIn('nik_karyawan', $_nama)->whereIn('nik_atasan', $_atasan)->orderBy('id', 'desc')->get();
            }
            foreach ($trx as $_trx) {
                $where = ['nik_karyawan' => $_trx->nik_karyawan];
                $check = TrxPenilaian::where($where)->whereIn('id_status', [4,8])->whereIn('is_perpanjang', $arr_perpanjangan)->get();
                if(count($check)==0){
                    $trx = forgetById($trx, $_trx->id);
                }
            }
        }else{
            $arr_perusahaan = array();
            $arr_perpanjangan = array();
        }
        // end filter
        return view( 'formulir.nonaktif.auto.index', compact( 'trx', 'no', 'nama', 'atasan', 'perusahaan', 'arr_perusahaan', 'perpanjangan', 'arr_perpanjangan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->whereIn('kode_perusahaan', [1,2,6])->whereIn('kode_status', [2])->orderBy('nama', 'asc')->get();
        if(Gate::allows('hc_nkp')){
            $karyawan = Karyawan::select('nik', 'nama')->whereIn('nik',$arr_nik_nkp)->where($where)->orderBy('nama', 'asc')->get();
        }
        return view( 'formulir.nonaktif.auto.insert', compact( 'karyawan' ) );
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
        // dd($submit);
        foreach($submit as $_id => $value){
            if(str_starts_with($_id, 'submit')){
                if(empty($request->nonaktif)){
                    if($value == 'execute'){
                        return redirect( 'nonaktif_auto' )->with( 'msg', 'No Data is executed.' );
                    }else if($value == 'delete'){
                        return redirect( 'nonaktif_auto' )->with( 'msg', 'No Data is deleted.' );
                    }else if($value == 'store'){
                        $where = ['nik' => $request->nik_karyawan];
                        $data = Karyawan::where($where)->first();
                        // parameter yang dipassing ke sendemail
                        $karyawan = json_decode($this->getNikProperty($data->nik), true);
                        $atasan = json_decode($this->getNikProperty($data->layer1), true);
                        // 
                        $mailData['title'] = 'Formulir Karyawan Non Aktif';
                        $mailData['filename'] = 'emails.nonaktif0_auto';
                        $mailData['to'] = $karyawan['email'];
                        $mailData['data'] = ['nama'=>$karyawan['nama'].'/'.$karyawan['nik'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('nonaktif.create') ];
                        (new TrxNonaktifController)->sendEmail($mailData);
                        return redirect( 'nonaktif_auto' )->with( 'msg', 'Data is executed successfully.' );
                    }
                }else{
                    if($value == 'execute'){
                        $nonaktif = $request->nonaktif;
                        foreach($nonaktif as $_nonaktif){
                            $data = TrxNonaktifAuto::find( $_nonaktif );
                            $this->destroy($_nonaktif);
                            // parameter yang dipassing ke sendemail
                            $karyawan = json_decode($this->getNikProperty($data['nik_karyawan']), true);
                            $atasan = json_decode($this->getNikProperty($data['nik_atasan']), true);
                            // 
                            $mailData['title'] = 'Formulir Karyawan Non Aktif';
                            $mailData['filename'] = 'emails.nonaktif0_auto';
                            $mailData['to'] = $karyawan['email'];
                            $mailData['data'] = ['nama'=>$karyawan['nama'].'/'.$karyawan['nik'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('nonaktif.create') ];
                            (new TrxNonaktifController)->sendEmail($mailData);
                        }
                        return redirect( 'nonaktif_auto' )->with( 'msg', 'Data is executed successfully.' );
                    }else if($value == 'delete'){
                        $nonaktif = $request->nonaktif;
                        foreach($nonaktif as $_nonaktif){
                            $this->destroy($_nonaktif);
                        }
                        return redirect( 'nonaktif_auto' )->with( 'msg', 'Data is deleted successfully.' );
                    }
                }
            }
        }
    }

    public function getNikProperty($nik)
    {
        $where = ['nik' => $nik];
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
        $trx = TrxNonaktifAuto::find( $id );
        $trx->delete();
        // return redirect( 'nonaktif_auto' )->with( 'msg', 'Data is executed successfully.' );
    }
}
