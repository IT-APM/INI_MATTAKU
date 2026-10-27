<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\TrxPenilaian;
use App\Models\TrxPenilaianSkor;
use App\Models\TrxPenilaianLog;
use App\Models\TrxPenilaianLogHistory;
// 
use App\Models\ToDoList;
use App\Models\ToDoListLog;
use App\Models\MtrUtility;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;
// 
class TrxPenilaianSkorController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show','update']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // dd($request->all(), $id);
        $linkAction = route('penilaianskor.update', $id);
        $id_trx = $id;
        // 
        $id_trx = (new OpenDataController)->decryptWithKey($id_trx);
        $nik = (new OpenDataController)->decryptWithKey($request->nik);
        $id_status = (new OpenDataController)->decryptWithKey($request->id_status);
        $pic = (new OpenDataController)->decryptWithKey($request->pic);
        // dd($id_trx, $nik, $id_status, $pic);
        if(!ToDoList::where(['id_trx'=>$id_trx, 'nik'=>$nik, 'id_status'=>$id_status, 'pic'=>$pic])->exists()){
            return view( 'emails.penilaian_ok' );
        }
        $penilaian = TrxPenilaian::find( $id_trx );
        $is_kt = Karyawan::where(['kode_status'=>1, 'nik'=>$penilaian->nik_karyawan])->exists();
        // dd($is_kt);
        $id_tipe = $penilaian->id_tipe;
        // 
        $nik_pic = $penilaian['nik_karyawan'];
        $where = [ 'id_trx' => $id, 'nik_pic' => $nik_pic ];
        $nKaryawan = TrxPenilaianLog::where($where)->count();
        // 
        $nik_pic = $penilaian['nik_penilai_1'];
        $where = [ 'id_trx' => $id, 'nik_pic' => $nik_pic ];
        $nPenilai1 = TrxPenilaianLog::where($where)->count();
        // 
        $nik_pic = $penilaian['nik_penilai_2'];
        $where = [ 'id_trx' => $id, 'nik_pic' => $nik_pic ];
        $nPenilai2 = TrxPenilaianLog::where($where)->count();
        // 
        $nik_pic = $penilaian['nik_penilai_3'];
        $where = [ 'id_trx' => $id, 'nik_pic' => $nik_pic ];
        $nPenilai3 = TrxPenilaianLog::where($where)->count();
        // 
        $karyawan = json_decode($penilaian['karyawan_property'], true);
        $atasan = json_decode($penilaian['penilai1_property'], true);
        $penilai2 = json_decode($penilaian['penilai2_property'], true);
        $penilai3 = json_decode($penilaian['penilai3_property'], true);
        $data = ['nama'=>$karyawan['nama'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=>'', 'tanggal_bergabung'=>$karyawan['tanggal_bergabung'], 'tanggal_berakhir'=>$karyawan['tanggal_berakhir']];
        // 
        $where = ['id_trx' => $id_trx];
        $trxSkor1 = TrxPenilaianSkor::select('penilaian_1')->where($where)->distinct('penilaian_1')->orderBy('id_nilai3', 'asc')->get();
        // echo $trxSkor1;die;
        $ket_penilaian = '';
        $isian_penilaian = '';
        $isRequired = '';
        $jumlah = 0;
        $jumlah_soal_outsourcing = 0;
        $jumlah_nilai_outsourcing = 0;
        // 6 = Penilaian Outsourcing
        if($id_tipe == 6){
            $where = ['id_trx' => $id_trx];
            $trxSkor1 = TrxPenilaianSkor::where($where)->orderBy('id_nilai3', 'asc')->get();
            $jumlah_soal_outsourcing = count($trxSkor1);
            foreach ($trxSkor1 as $value) {
                $isian_penilaian .= '<br/>'.$value['id_nilai3'].'. '.$value['penilaian_1'].'<br/>';
                if($penilaian->id_status <> 16){
                    $isian_penilaian .= '<b>Penilaian oleh Atasan Langsung: '.$value['skor_penilai_1'].'</b><br/>';
                    $jumlah_nilai_outsourcing += $value['skor_penilai_1'];
                }
                if($penilaian->id_status == 16){
                    for($x=1; $x<=5; $x++){
                        $isian_penilaian .= '<div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="p'.$value['id_nilai3'].'" id="p'.$value['id_nilai3'].'" required="" value="'.$x.'"><span>'.$x.'</span></div>';
                    }
                }
            }
        }
        // selain Outsourcing
        else{
            foreach ($trxSkor1 as $value) {
                $strNilai1 = $value['penilaian_1'];
                $ket_penilaian .= '<h6>'.$strNilai1.'</h6>';
                $where = ['id_trx' => $id_trx];
                $trxSkor2 = TrxPenilaianSkor::select('penilaian_2','penilaian_2_ket')
                ->distinct('penilaian_2')->where('penilaian_1', '=', $strNilai1)->where($where)
                ->orderBy('id_nilai3', 'asc')->get();
                // echo $trxSkor2;die;
                foreach ($trxSkor2 as $value2) {
                    $strNilai2 = $value2['penilaian_2'];
                    $strNilai2Ket = $value2['penilaian_2_ket'];
                    $ket_penilaian .= '<h6>'.$strNilai2.'</h6>';
                    $ket_penilaian .= $strNilai2Ket.'<br/>';
                    // 
                    $where = ['id_trx' => $id_trx];
                    $trxSkor3 = TrxPenilaianSkor::select('id_nilai3','penilaian_3','skor_karyawan','skor_penilai_1','skor_penilai_2','skor_penilai_3')
                    ->where('penilaian_2', '=', $strNilai2)->where($where)->orderBy('id_nilai3', 'asc')->get();
                    $isian_penilaian .= '<h6>'.$strNilai2.'</h6>';
                    foreach ($trxSkor3 as $value3) {
                        $isian_penilaian .= '<br/>'.$value3['id_nilai3'].'. '.$value3['penilaian_3'].'<br/>';
                        if($penilaian->id_status > 1){
                            $isian_penilaian .= '<b>Penilaian oleh Karyawan: '.$value3['skor_karyawan'].'</b><br/>';
                        }
                        if($penilaian->id_status > 4){
                            $isian_penilaian .= '<b>Penilaian oleh Atasan Langsung: '.$value3['skor_penilai_1'].'</b><br/>';
                        }
                        if($penilaian->id_status < 3){
                            for($x=1; $x<=5; $x++){
                                $isian_penilaian .= '<div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="p'.$value3['id_nilai3'].'" id="p'.$value3['id_nilai3'].'" required="" value="'.$x.'"><span>'.$x.'</span></div>';
                                // $isian_penilaian .= '<input type="radio" name="p'.$value3['id_nilai3'].'" id="p'.$value3['id_nilai3'].'" required>';
                                // $isian_penilaian .= '&nbsp;&nbsp;<input type="radio" name="p'.$value3['id_nilai3'].'" id="p'.$value3['id_nilai3'].'" value="'.$x.'"><span>'.$x.'</span>';
                            //     $isian_penilaian .= '<div class="form-check radio radio-primary">
                            //     <input class="form-check-input" type="radio" name="p'.$value3['id_nilai3'].'" id="p'.$value3['id_nilai3'].'" value="'.$x.'" required="">
                            //     <label class="form-check-label" for="p'.$value3['id_nilai3'].'"><span>'.$x.'</span></label>
                            //   </div>';
                            }
                        }
                        // $isian_penilaian .= '&nbsp;&nbsp;';
                        $jumlah += 1;
                    }
                    $isian_penilaian .= '<hr/>';
                    // 
                }
                // $ket_penilaian .= '<hr/>';
            }
        }
        // for ($y=1; $y<=$jumlah; $y++) {
        //     $isRequired .= "p".$y.": { required: true },";
        // };
        $kes_rek = '';
        if($pic != 'nik_karyawan'){
            $kes_rek = '<tr>
            <td><hr/>Kesimpulan dan Rekomendasi<br/>
            <textarea class="form-control" name="kes_rek" id="kes_rek" rows="3" ></textarea>
            </td></tr>';
        }
        // 
        $isian_kes_rek = '';
        if($pic == 'nik_penilai_2' || $pic == 'nik_penilai_3'){
            $isian_kes_rek .= '<tr><td><b>Kesimpulan dan Rekomendasi oleh '.$atasan['nama'].':<br/>'.$penilaian->kes_rek_1.'</b></td></tr>';
            $isian_kes_rek .= '<tr><td><b>';
            $txt_perpanjang = '';
            $where = [ 'id_trx' => $id_trx, 'nik_pic'=> $atasan['nik'] ];
            $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
            // return response()->json(['trx' => $trx]);
            if(!empty($trx)){
                if($trx->is_perpanjang){
                    $isian_kes_rek .= 'Status Perpanjangan: Diperpanjang '.$trx->bulan.' Bulan<br/><br/>';
                }else{
                    $isian_kes_rek .= 'Status Perpanjangan: Tidak Diperpanjang<br/>';
                }
            }
            $isian_kes_rek .= '</b><hr/></td></tr>';
            if(!empty($penilaian->nik_penilai_2)){
                $isian_kes_rek .= '<tr><td><b>Kesimpulan dan Rekomendasi oleh '.$penilai2['nama'].':<br/>'.$penilaian->kes_rek_2.'</b></td></tr>';
                $isian_kes_rek .= '<tr><td><b>';
                $txt_perpanjang = '';
                $where = [ 'id_trx' => $id_trx, 'nik_pic'=> $penilai2['nik'] ];
                $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
                // return response()->json(['trx' => $trx]);
                if(!empty($trx)){
                    if($trx->is_perpanjang){
                        $isian_kes_rek .= 'Status Perpanjangan: Diperpanjang '.$trx->bulan.' Bulan<br/><br/>';
                    }else{
                        $isian_kes_rek .= 'Status Perpanjangan: Tidak Diperpanjang<br/>';
                    }
                }
                $isian_kes_rek .= '</b><hr/></td></tr>';
            }
            if(!empty($penilaian->nik_penilai_3)){
                $isian_kes_rek .= '<tr><td><b>Kesimpulan dan Rekomendasi oleh '.$penilai3['nama'].':<br/>'.$penilaian->kes_rek_3.'</b></td></tr>';
                $isian_kes_rek .= '<tr><td><b>';
                $txt_perpanjang = '';
                $where = [ 'id_trx' => $id_trx, 'nik_pic'=> $penilai3['nik'] ];
                $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
                // return response()->json(['trx' => $trx]);
                if(!empty($trx)){
                    if($trx->is_perpanjang){
                        $isian_kes_rek .= 'Status Perpanjangan: Diperpanjang '.$trx->bulan.' Bulan<br/><br/>';
                    }else{
                        $isian_kes_rek .= 'Status Perpanjangan: Tidak Diperpanjang<br/>';
                    }
                }
                $isian_kes_rek .= '</b><hr/></td></tr>';
            }
        }
        // $isian_kes_rek .= '<hr/>';
        // 
        // $pic = $request->pic;
        // 6 = Penilaian Outsourcing
        if($id_tipe == 6){
            if($nik==$penilaian[$pic]){
                if($id_status==$penilaian['id_status']){
                    return view( 'emails.penilaian_outsourcing', compact( 'jumlah_soal_outsourcing', 'jumlah_nilai_outsourcing', 'data', 'ket_penilaian', 'isian_penilaian', 'isRequired', 'linkAction', 'kes_rek', 'pic', 'isian_kes_rek' ) );
                }else{
                    if($pic == 'nik_penilai_2' && $nPenilai2==0){
                        return view( 'emails.penilaian_outsourcing', compact( 'jumlah_soal_outsourcing', 'jumlah_nilai_outsourcing', 'data', 'ket_penilaian', 'isian_penilaian', 'isRequired', 'linkAction', 'kes_rek', 'pic', 'isian_kes_rek' ) );
                    }elseif($pic=='nik_penilai_3' && $nPenilai3==0){
                        return view( 'emails.penilaian_outsourcing', compact( 'jumlah_soal_outsourcing', 'jumlah_nilai_outsourcing', 'data', 'ket_penilaian', 'isian_penilaian', 'isRequired', 'linkAction', 'kes_rek', 'pic', 'isian_kes_rek' ) );
                    }else{
                        return view( 'emails.penilaian_ok' );
                    }
                }
            }else{
                return view( 'emails.penilaian_ok' );
            }
        }
        // selain Penilaian Outsourcing
        else{
            if($nik==$penilaian[$pic]){
                if($id_status==$penilaian['id_status']){
                    return view( 'emails.penilaian', compact( 'is_kt', 'data', 'ket_penilaian', 'isian_penilaian', 'isRequired', 'linkAction', 'kes_rek', 'pic', 'isian_kes_rek' ) );
                }else{
                    if($pic == 'nik_penilai_2' && $nPenilai2==0){
                        return view( 'emails.penilaian', compact( 'is_kt', 'data', 'ket_penilaian', 'isian_penilaian', 'isRequired', 'linkAction', 'kes_rek', 'pic', 'isian_kes_rek' ) );
                    }elseif($pic=='nik_penilai_3' && $nPenilai3==0){
                        return view( 'emails.penilaian', compact( 'is_kt', 'data', 'ket_penilaian', 'isian_penilaian', 'isRequired', 'linkAction', 'kes_rek', 'pic', 'isian_kes_rek' ) );
                    }else{
                        return view( 'emails.penilaian_ok' );
                    }
                }
            }else{
                return view( 'emails.penilaian_ok' );
            }
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
        $id = (new OpenDataController)->decryptWithKey($id);
        // dd($request->all(), $id);
        $trx = TrxPenilaian::find( $id );
        $id_tipe = $trx->id_tipe;
        // 
        $nik_pic = $trx->nik_karyawan;
        $where = [ 'id_trx' => $id, 'nik_pic' => $nik_pic ];
        $nKaryawan = TrxPenilaianLog::where($where)->count();
        // pengecualian agar maker hco bisa melakukan isian dirinya sendiri
        if(TrxPenilaianLog::where(['id_trx' => $id])->count() == 1 && $nik_pic == $trx->nik_karyawan){
            $nKaryawan = 0;
        }
        // 
        $nik_pic = $trx->nik_penilai_1;
        $where = [ 'id_trx' => $id, 'nik_pic' => $nik_pic ];
        $nPenilai1 = TrxPenilaianLog::where($where)->count();
        // 
        $nik_pic = $trx->nik_penilai_2;
        $where = [ 'id_trx' => $id, 'nik_pic' => $nik_pic ];
        $nPenilai2 = TrxPenilaianLog::where($where)->count();
        // 
        $nik_pic = $trx->nik_penilai_3;
        $where = [ 'id_trx' => $id, 'nik_pic' => $nik_pic ];
        $nPenilai3 = TrxPenilaianLog::where($where)->count();
        // return response()->json(['id_status' => $trx->id_status, 'nKaryawan' => $nKaryawan]);die;
        // formulir diinput oleh karyawan
        if($trx->id_status==1 && $nKaryawan==0 && $request->pic=='nik_karyawan'){
            $data = $request->all();
            foreach ($data as $key => $value) {
                if(str_starts_with($key, 'p')){
                    $where = ['id_trx' => $id, 'id_nilai3' => substr($key, 1)];
                    $trxSkor = TrxPenilaianSkor::where($where)->update(array('skor_karyawan' => $value));
                }
            }
            $trx->id_status = 2;
            $trx->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$trx->nik_karyawan, 'pic'=>'nik_karyawan', 'filename'=>'emails.penilaian0']);
            // $id_trx, $id_status, $nik_pic, $keterangan, $is_perpanjang, $bulan
            $this->insertLog($id, 2, $trx->nik_karyawan, $request->kes_rek, $request->is_perpanjang, $request->bulan);
            // 
            $karyawan = json_decode($trx->karyawan_property, true);
            $atasan = json_decode($trx->penilai1_property, true);
            $mailData['title'] = 'Formulir Penilaian Karyawan';
            $mailData['data'] = ['nama'=>$karyawan['nama'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('penilaianskor.show', [(new OpenDataController)->encryptWithKey($trx->id), 'nik' => (new OpenDataController)->encryptWithKey($trx->nik_penilai_1), 'id_status' => (new OpenDataController)->encryptWithKey(2), 'pic' => (new OpenDataController)->encryptWithKey('nik_penilai_1')]), 'id_trx' => $trx->id ];
            // print_r($mailData['data']);die;
            if($trx->id_tipe == 2 || $trx->id_tipe == 4){
                $mailData['title'] .= '- Reguler';
            }elseif($trx->id_tipe == 3 || $trx->id_tipe == 5){
                $mailData['title'] .= '- Assesment';
            }
            // template email ada link agar bisa diklik dan diinput pic
            $mailData['filename'] = 'emails.penilaian0';
            $mailData['to'] = $atasan['email'];
            $todolist = ['id_trx' => $trx->id, 'nik' => $trx->nik_penilai_1, 'id_status' => 2, 'pic' => 'nik_penilai_1'];
            $this->insertToDoList($todolist, $mailData);
            $this->sendEmail($mailData);
            // 
        }
        // formulir diinput oleh penilai 1
        // 2 = Done by Employee
        // 16 = Send to Reviewer 1
        elseif(($trx->id_status==2 || $trx->id_status==16) && $nPenilai1==0 && $request->pic=='nik_penilai_1'){
            $data = $request->all();
            foreach ($data as $key => $value) {
                if(str_starts_with($key, 'p')){
                    $where = ['id_trx' => $id, 'id_nilai3' => substr($key, 1)];
                    $trxSkor = TrxPenilaianSkor::where($where)->update(array('skor_penilai_1' => $value));
                }
            }
            // jika bertipe 180 hanya sampai hco
            if($trx->id_tipe == 1 || $trx->id_tipe == 4 || $trx->id_tipe == 5){
                $trx->id_status = 3;
                $trx->kes_rek_1 = $request->kes_rek;
                $trx->update();
                $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$trx->nik_penilai_1, 'pic'=>'nik_penilai_1', 'filename'=>'emails.penilaian0']);
                // $id_trx, $id_status, $nik_pic, $keterangan, $is_perpanjang, $bulan
                $this->insertLog($id, 3, $trx->nik_penilai_1, $request->kes_rek, $request->is_perpanjang, $request->bulan);
                // 
                $karyawan = json_decode($trx->karyawan_property, true);
                $atasan = json_decode($trx->penilai1_property, true);
                $mailData['title'] = 'Formulir Penilaian Karyawan';
                // $mailData['data'] = ['nama'=>$karyawan['nama'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'id_trx' => $trx->id ];
                $mailData['data'] = ['nama'=>$karyawan['nama'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('penilaian.edit', [(new OpenDataController)->encryptWithKey($trx->id)]), 'id_trx' => $trx->id ];
                if($trx->id_tipe == 2 || $trx->id_tipe == 4){
                    $mailData['title'] .= '- Reguler';
                }elseif($trx->id_tipe == 3 || $trx->id_tipe == 5){
                    $mailData['title'] .= '- Assesment';
                }
                // template email tidak ada link done 180 to hco
                // $mailData['filename'] = 'emails.penilaian1';
                // $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
                // hco dapat notifikasi email bahwa penilaian sudah selesai
                $mailData['filename'] = 'emails.penilaian0';
                $mailData['to'] = explode('/', MtrUtility::find(38)->informasi)[1];
                $todolist = ['id_trx' => $trx->id, 'nik' => explode('/', MtrUtility::find(38)->informasi)[0], 'id_status' => $trx->id_status, 'pic' => 'hco'];
                $this->insertToDoList($todolist, $mailData);
                $this->sendEmail($mailData);
                // 
            }
            // jika bertipe 360 maka bisa berlanjut ke penilai 2 dan atau penilai 3
            elseif($trx->id_tipe == 2 || $trx->id_tipe == 3){
                // echo 'ini lagi ya';die;
                $trx->id_status = 5;
                $trx->kes_rek_1 = $request->kes_rek;
                $trx->update();
                $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$trx->nik_penilai_1, 'pic'=>'nik_penilai_1', 'filename'=>'emails.penilaian0']);
                // $id_trx, $id_status, $nik_pic, $keterangan, $is_perpanjang, $bulan
                $this->insertLog($id, 5, $trx->nik_penilai_1, $request->kes_rek, $request->is_perpanjang, $request->bulan);
                // 
                $karyawan = json_decode($trx->karyawan_property, true);
                $atasan = json_decode($trx->penilai1_property, true);
                $mailData['title'] = 'Formulir Penilaian Karyawan';
                if($trx->id_tipe == 2 || $trx->id_tipe == 4){
                    $mailData['title'] .= '- Reguler';
                }elseif($trx->id_tipe == 3 || $trx->id_tipe == 5){
                    $mailData['title'] .= '- Assesment';
                }
                // template email ada link agar bisa diklik dan diinput pic
                $mailData['filename'] = 'emails.penilaian0';
                // jika penilai 2 ada maka dilanjut ke penilai 2
                if(!empty($trx->nik_penilai_2)){
                    $penilai2 = json_decode($trx->penilai2_property, true);
                    $mailData['data'] = ['nama'=>$karyawan['nama'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('penilaianskor.show', [(new OpenDataController)->encryptWithKey($trx->id), 'nik' => (new OpenDataController)->encryptWithKey($trx->nik_penilai_2), 'id_status' => (new OpenDataController)->encryptWithKey(5), 'pic' => (new OpenDataController)->encryptWithKey('nik_penilai_2')]), 'id_trx' => $trx->id ];
                    $mailData['to'] = $penilai2['email'];
                    $todolist = ['id_trx' => $trx->id, 'nik' => $trx->nik_penilai_2, 'id_status' => 5, 'pic' => 'nik_penilai_2'];
                    $this->insertToDoList($todolist, $mailData);
                    $this->sendEmail($mailData);
                    // 
                }
                // jika penilai 3 ada maka dilanjut ke penilai 3
                // if(!empty($trx->nik_penilai_3)){
                //     $penilai3 = json_decode($trx->penilai3_property, true);
                //     $mailData['data'] = ['nama'=>$karyawan['nama'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('penilaianskor.show', [$trx->id, 'nik' => $trx->nik_penilai_3, 'id_status' => 5, 'pic' => 'nik_penilai_3']), 'id_trx' => $trx->id ];
                //     $mailData['to'] = $penilai3['email'];
                //     $todolist = ['id_trx' => $trx->id, 'nik' => $trx->nik_penilai_3, 'id_status' => 5, 'pic' => 'nik_penilai_3'];
                //     $this->insertToDoList($todolist, $mailData);
                //     $this->sendEmail($mailData);
                //     // 
                // }
            }
            // 6 = Penilaian Outsourcing
            elseif($trx->id_tipe == 6){
                // 3 = Done By Reviewer 1
                $id_status = 3;
                $trx->id_status = $id_status;
                $trx->kes_rek_1 = $request->kes_rek;
                $trx->update();
                $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$trx->nik_penilai_1, 'pic'=>'nik_penilai_1', 'filename'=>'emails.penilaian0_outsourcing']);
                // $id_trx, $id_status, $nik_pic, $keterangan, $is_perpanjang, $bulan
                $this->insertLog($id, $id_status, $trx->nik_penilai_1, $request->kes_rek, $request->is_perpanjang, $request->bulan);
                // 
                $karyawan = json_decode($trx->karyawan_property, true);
                $atasan = json_decode($trx->penilai1_property, true);
                $mailData['title'] = 'Formulir Penilaian Karyawan';
                $mailData['title'] .= ' - Outsourcing';
                // template email ada link agar bisa diklik dan diinput pic
                $mailData['filename'] = 'emails.penilaian0_outsourcing';
                // jika penilai 2 ada maka dilanjut ke penilai 2
                if(!empty($trx->nik_penilai_2)){
                    $penilai2 = json_decode($trx->penilai2_property, true);
                    $mailData['data'] = ['nama'=>$karyawan['nama'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('penilaianskor.show', [(new OpenDataController)->encryptWithKey($trx->id), 'nik' => (new OpenDataController)->encryptWithKey($trx->nik_penilai_2), 'id_status' => (new OpenDataController)->encryptWithKey(5), 'pic' => (new OpenDataController)->encryptWithKey('nik_penilai_2')]), 'id_trx' => $trx->id ];
                    $mailData['to'] = $penilai2['email'];
                    $todolist = ['id_trx' => $trx->id, 'nik' => $trx->nik_penilai_2, 'id_status' => 5, 'pic' => 'nik_penilai_2'];
                    $this->insertToDoList($todolist, $mailData);
                    $this->sendEmail($mailData);
                    // 
                }
            }
        }
        // formulir diinput oleh penilai 2 atau 3
        // karyawan reguler
        elseif(($trx->id_status==5 || $trx->id_status==6 || $trx->id_status==7) && $trx->id_tipe != 6){
            // 
            $karyawan = json_decode($trx->karyawan_property, true);
            $atasan = json_decode($trx->penilai1_property, true);
            $mailData['title'] = 'Formulir Penilaian Karyawan';
            // $mailData['data'] = ['nama'=>$karyawan['nama'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'id_trx' => $trx->id ];
            $mailData['data'] = ['nama'=>$karyawan['nama'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('penilaian.edit', [(new OpenDataController)->encryptWithKey($trx->id)]), 'id_trx' => $trx->id ];
            if($trx->id_tipe == 2 || $trx->id_tipe == 4){
                $mailData['title'] .= '- Reguler';
            }elseif($trx->id_tipe == 3 || $trx->id_tipe == 5){
                $mailData['title'] .= '- Assesment';
            }
            // template email tidak ada link done 360 to hco
            // $mailData['filename'] = 'emails.penilaian1';
            // $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
            // hco dapat notifikasi email bahwa penilaian sudah selesai
            $mailData['filename'] = 'emails.penilaian0';
            $mailData['to'] = explode('/', MtrUtility::find(38)->informasi)[1];
            $todolist = ['id_trx' => $trx->id, 'nik' => explode('/', MtrUtility::find(38)->informasi)[0], 'id_status' => $trx->id_status, 'pic' => 'hco'];
            // jika layer3 tidak ada maka email done to hco
            if(empty($trx->nik_penilai_3)){
                $this->insertToDoList($todolist, $mailData);
                $this->sendEmail($mailData);
            }
            // jika diinput penilai 2
            if($request->pic == 'nik_penilai_2' && $nPenilai2 == 0){
                $trx->id_status = 6;
                $trx->kes_rek_2 = $request->kes_rek;
                $trx->update();
                $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$trx->nik_penilai_2, 'pic'=>'nik_penilai_2', 'filename'=>'emails.penilaian0']);
                // $id_trx, $id_status, $nik_pic, $keterangan, $is_perpanjang, $bulan
                $this->insertLog($id, 6, $trx->nik_penilai_2, $request->kes_rek, $request->is_perpanjang, $request->bulan);
                // 
                if(!empty($trx->nik_penilai_3)){
                    $penilai3 = json_decode($trx->penilai3_property, true);
                    $mailData['data'] = ['nama'=>$karyawan['nama'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('penilaianskor.show', [(new OpenDataController)->encryptWithKey($trx->id), 'nik' => (new OpenDataController)->encryptWithKey($trx->nik_penilai_3), 'id_status' => (new OpenDataController)->encryptWithKey(6), 'pic' => (new OpenDataController)->encryptWithKey('nik_penilai_3')]), 'id_trx' => $trx->id ];
                    // template email ada link agar bisa diklik dan diinput pic
                    $mailData['filename'] = 'emails.penilaian0';
                    $mailData['to'] = $penilai3['email'];
                    $todolist = ['id_trx' => $trx->id, 'nik' => $trx->nik_penilai_3, 'id_status' => 6, 'pic' => 'nik_penilai_3'];
                    $this->insertToDoList($todolist, $mailData);
                    $this->sendEmail($mailData);
                    // 
                }
            }
            // jika diinput penilai 3
            elseif($request->pic == 'nik_penilai_3' && $nPenilai3 == 0){
                $trx->id_status = 7;
                $trx->kes_rek_3 = $request->kes_rek;
                $trx->update();
                $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$trx->nik_penilai_3, 'pic'=>'nik_penilai_3', 'filename'=>'emails.penilaian0']);
                // $id_trx, $id_status, $nik_pic, $keterangan, $is_perpanjang, $bulan
                $this->insertLog($id, 7, $trx->nik_penilai_3, $request->kes_rek, $request->is_perpanjang, $request->bulan);
                // template email tidak ada link done 360 to hco
                $this->insertToDoList($todolist, $mailData);
                $this->sendEmail($mailData);
            }
        }
        // formulir diinput oleh penilai 2 atau penilai 3
        // 6 = Penilaian Outsourcing
        elseif(($trx->id_status==3 || $trx->id_status==6 || $trx->id_status==15) && $trx->id_tipe == 6){
            // dd($request->all(), $id);
            $karyawan = json_decode($trx->karyawan_property, true);
            $atasan = json_decode($trx->penilai1_property, true);
            $mailData['title'] = 'Formulir Penilaian Karyawan';
            // $mailData['data'] = ['nama'=>$karyawan['nama'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'id_trx' => $trx->id ];
            $mailData['data'] = ['nama'=>$karyawan['nama'].'/'.$karyawan['nik'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('penilaian.edit', [(new OpenDataController)->encryptWithKey($trx->id)]), 'id_trx' => $trx->id ];
            $mailData['title'] .= ' - Outsourcing';
            // 
            $mailData['filename'] = 'emails.penilaian0_outsourcing';
            // tidak digunakan karena menjadi 2 pic
            // $mailData['to'] = explode('/', MtrUtility::find(28)->informasi)[1];
            // $todolist = ['id_trx' => $trx->id, 'nik' => explode('/', MtrUtility::find(28)->informasi)[0], 'id_status' => $trx->id_status, 'pic' => 'hco'];
            // 
            // $this->insertToDoList($todolist, $mailData);
            // $this->sendEmail($mailData);
            // jika layer3 tidak ada maka email done to hco
            if(empty($trx->nik_penilai_3)){
                $pic_hco = explode(',', MtrUtility::find(28)->informasi);
                // cek apakah karyawan 5=PT NASIO KARYA PRATAMA
                if(Karyawan::where(['nik'=>$trx->nik_karyawan,'is_aktif'=>1,'kode_perusahaan'=>5])->exists()){
                    // PC NKP
                    foreach(explode(',', MtrUtility::find(49)->informasi) as $nkp){
                        array_push($pic_hco, $nkp);
                    }
                }
                foreach($pic_hco as $_data){
                    $mailData['to'] = explode('/', $_data)[1];
                    $todolist = ['id_trx' => $trx->id, 'nik' => explode('/', $_data)[0], 'id_status' => $trx->id_status, 'pic' => 'hco'];
                    $this->insertToDoList($todolist, $mailData);
                    $this->sendEmail($mailData);
                }
            }
            // jika diinput penilai 2
            if($request->pic == 'nik_penilai_2' && $nPenilai2 == 0){
                $trx->id_status = 6;
                $trx->kes_rek_2 = $request->kes_rek;
                $trx->update();
                $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$trx->nik_penilai_2, 'pic'=>'nik_penilai_2', 'filename'=>'emails.penilaian0_outsourcing']);
                // $id_trx, $id_status, $nik_pic, $keterangan, $is_perpanjang, $bulan
                $this->insertLog($id, 6, $trx->nik_penilai_2, $request->kes_rek, $request->is_perpanjang, $request->bulan);
                // 
                if(!empty($trx->nik_penilai_3)){
                    $penilai3 = json_decode($trx->penilai3_property, true);
                    $mailData['data'] = ['nama'=>$karyawan['nama'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('penilaianskor.show', [(new OpenDataController)->encryptWithKey($trx->id), 'nik' => (new OpenDataController)->encryptWithKey($trx->nik_penilai_3), 'id_status' => (new OpenDataController)->encryptWithKey(15), 'pic' => (new OpenDataController)->encryptWithKey('nik_penilai_3')]), 'id_trx' => $trx->id ];
                    $mailData['to'] = $penilai3['email'];
                    $todolist = ['id_trx' => $trx->id, 'nik' => $trx->nik_penilai_3, 'id_status' => 15, 'pic' => 'nik_penilai_3'];
                    $this->insertToDoList($todolist, $mailData);
                    $this->sendEmail($mailData);
                    // 
                }
            }
            // jika diinput penilai 3
            elseif($request->pic == 'nik_penilai_3' && $nPenilai3 == 0){
                $trx->id_status = 7;
                $trx->kes_rek_3 = $request->kes_rek;
                $trx->update();
                $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$trx->nik_penilai_3, 'pic'=>'nik_penilai_3', 'filename'=>'emails.penilaian0_outsourcing']);
                // $id_trx, $id_status, $nik_pic, $keterangan, $is_perpanjang, $bulan
                $this->insertLog($id, 7, $trx->nik_penilai_3, $request->kes_rek, $request->is_perpanjang, $request->bulan);
                $pic_hco = explode(',', MtrUtility::find(28)->informasi);
                // cek apakah karyawan 5=PT NASIO KARYA PRATAMA
                if(Karyawan::where(['nik'=>$trx->nik_karyawan,'is_aktif'=>1,'kode_perusahaan'=>5])->exists()){
                    // PC NKP
                    foreach(explode(',', MtrUtility::find(49)->informasi) as $nkp){
                        array_push($pic_hco, $nkp);
                    }
                }
                foreach($pic_hco as $_data){
                    $mailData['to'] = explode('/', $_data)[1];
                    $todolist = ['id_trx' => $trx->id, 'nik' => explode('/', $_data)[0], 'id_status' => $trx->id_status, 'pic' => 'hco'];
                    $this->insertToDoList($todolist, $mailData);
                    $this->sendEmail($mailData);
                }
            }
        }
        return view( 'emails.penilaian_ok' );
    }

    public function sendEmail($mailData)
    {
        $ccEmails = explode(',', MtrUtility::find(11)->informasi);
        Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
    }

    public function insertToDoList($todolist, $mailData)
    {
        $data = New ToDoList;
        $data->id_trx = $todolist['id_trx'];
        $data->id_status = $todolist['id_status'];
        $data->nik = $todolist['nik'];
        $data->pic = $todolist['pic'];
        $data->title = $mailData['title'];
        $data->nama = $mailData['data']['nama'];
        $data->jabatan = $mailData['data']['jabatan'];
        $data->unit_kerja = $mailData['data']['unit_kerja'];
        $data->link = $mailData['data']['link'];
        $data->filename = $mailData['filename'];
        $data->email = $mailData['to'];
        $data->save();
        $this->insertToDoListLog($todolist, $mailData);
    }

    public function insertToDoListLog($todolist, $mailData)
    {
        $data = New ToDoListLog;
        $data->id_trx = $todolist['id_trx'];
        $data->id_status = $todolist['id_status'];
        $data->nik = $todolist['nik'];
        $data->pic = $todolist['pic'];
        $data->title = $mailData['title'];
        $data->nama = $mailData['data']['nama'];
        $data->jabatan = $mailData['data']['jabatan'];
        $data->unit_kerja = $mailData['data']['unit_kerja'];
        $data->link = $mailData['data']['link'];
        $data->filename = $mailData['filename'];
        $data->email = $mailData['to'];
        $data->save();
    }

    public function deleteToDoList($where)
    {
        // $trx = ToDoList::where($where)->first();
        // if (!empty($trx)) {
        //     $trx->delete();
        // }
        $trx = ToDoList::where($where)->delete();
    }

    public function insertLog($id_trx, $id_status, $nik_pic, $keterangan, $is_perpanjang, $bulan)
    {
        $data = New TrxPenilaianLog;
        $data->id_trx = $id_trx;
        $data->id_status = $id_status;
        $data->nik_pic = $nik_pic;
        $data->keterangan = $keterangan;
        $data->is_perpanjang = $is_perpanjang;
        $data->bulan = $bulan;
        $data->save();
        // simpan history perubahan
        $data = New TrxPenilaianLogHistory;
        $data->id_trx = $id_trx;
        $data->id_status = $id_status;
        $data->nik_pic = $nik_pic;
        $data->keterangan = $keterangan;
        $data->is_perpanjang = $is_perpanjang;
        $data->bulan = $bulan;
        $data->revised_by = $nik_pic;
        $data->save();
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
