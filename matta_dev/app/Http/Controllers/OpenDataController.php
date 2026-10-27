<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use App\Models\Divisi;
use App\Models\Departemen;
use App\Models\SubDepartemen;
use App\Models\Propinsi;
use App\Models\PropinsiKota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
// 
use App\Models\TrxBpdDetailBiaya;
use App\Models\TrxBpdDetailInfo;
use App\Models\TrxBpdMaster;
use App\Models\BpdProyekUangSaku;
use App\Models\BpdProyekUangSakuKelompok;
use App\Models\BpdNonProyekBiayaDalamNegeri;
use App\Models\BpdNonProyekBiayaLuarNegeri;
use DB;
use Illuminate\Support\Str;
use App\Models\ToDoList;
use App\Models\MtrUtility;
// 
use App\Models\PelatihanSub;
use App\Models\SertifikasiSub;
use App\Models\KeterampilanSub;
use App\Models\KepribadianSub;
use App\Models\PekerjaanSub;
// 
class OpenDataController extends Controller
{

    public function encrypt_link()
    {
        // dd($this->encryptWithKey(123456));
        dd($this->decryptWithKey('M3BMekpTcVBTUlFuVzg0TUQ0R2tQUT09OjoyTGZVUm82dG5zY0hQWmNU'));
        // $url = 'https://matta-reborn.alita.id/matta/nonaktif/TWVIZmNxajV5bHZuWElsSkdoNGpndz09OjpuRVgyUnJYZnJkYjUwTmtX?view=Rk1nT3hJemlHdzhlcXlMc1JvaUV1dz09OjpUWVJLdTBrMmlDcWhXV0JZ&nik=RnpINlczNmFvSXpJMWFiUDlZcm1WUT09Ojo5VWhpdFpaaU42RUoycllv&id_status=bFEwc1hXYlhma0liOC9FbUJCWlI0QT09OjpOaEgwOUt2d0JDWlc2SjVF&pic=OEMrd0o5OSt4MFZoSnJRbGpGMHJYUT09OjoyRVZVUE1kWUNucXNLalAy';
        // $trx = ToDoList::where('created_at','>','2025-01-08 05:00:00')->get();
        // // dd(count($trx));
        // $arr = [];
        // foreach($trx as $data){
        //     $_arr = [];
        //     $response = Http::get($data->link);
        //     if ($response->successful()) {
        //         // return "Success: The URL returned a 200 status code.";
        //     } else {
        //         array_push($_arr, ['error'=>$response->status(), 'id'=>$data->id, 'link'=>$data->link]);
        //         // return "Failed: The URL returned a " . $response->status() . " status code.";
        //     }
        //     array_push($arr, $_arr);
        // }
        // dd($arr);
        // $where = ['id' => 48115];
        // $trx = ToDoList::where($where)->get();
        // $trx = ToDoList::whereNotIn('filename', ['emails.bpd0', 'emails.lembur0'])->whereNotIn('id', [41937, 47514, 47891])->get();
        $trx = ToDoList::whereIn('id', [48376, 48377])->get();
        // $trx = ToDoList::whereNotIn('filename', ['emails.bpd0', 'emails.lembur0'])->get();
        $arr_trx = [];
        // dd($trx);
        foreach($trx as $data){
            $_arr_trx = [];
            // $url = 'http://localhost/matta/reimburse/11296?view=approval';
            // $url = 'https://matta-reborn.alita.id/matta/reimburse/11317?view=approval&nik=12345';
            $url = $data->link;
            // dd($url);
            $parsedUrl = parse_url($url);
            // reverse engineering
            // start
            if(!empty($parsedUrl['query'])){
                parse_str($parsedUrl['query'], $queryParams);
                $request = Request::create($parsedUrl['path'], 'GET', $queryParams);
                $scheme = $parsedUrl['scheme'];
                $host = $parsedUrl['host'];
                $path = $parsedUrl['path'];
                $query = $parsedUrl['query'];
                $_path = explode('/', $path);
                $_enc = $this->encryptWithKey($data->id_trx);
                $_path[count($_path)-1] = $_enc;
                $_path = implode('/', $_path);
                $query = $request->all();
                $arr_query = [];
                array_push($arr_query, 'view', '=', $this->encryptWithKey('approval'), '&');
                array_push($arr_query, 'nik', '=', $this->encryptWithKey($data->nik), '&');
                array_push($arr_query, 'id_status', '=', $this->encryptWithKey($data->id_status), '&');
                array_push($arr_query, 'pic', '=', $this->encryptWithKey($data->pic));
                array_push($_arr_trx, $scheme, '://', $host, $_path, '?', implode('', $arr_query));
                $new_link = implode('', $_arr_trx);
                array_push($arr_trx, $new_link);
                // dd($new_link);
                $_trx = ToDoList::find($data->id);
                $_trx->link = $new_link;
                $_trx->update();
            }
            // end
            // mengubah non menjadi encrypted
            // start
            // jika ada parameter
            // if(!empty($parsedUrl['query'])){
            //     parse_str($parsedUrl['query'], $queryParams);
            //     $request = Request::create($parsedUrl['path'], 'GET', $queryParams);
            //     $scheme = $parsedUrl['scheme'];
            //     $host = $parsedUrl['host'];
            //     $path = $parsedUrl['path'];
            //     $query = $parsedUrl['query'];
            //     $_path = explode('/', $path);
            //     $_enc = $this->encryptWithKey($_path[count($_path)-1]);
            //     $_path[count($_path)-1] = $_enc;
            //     $_path = implode('/', $_path);
            //     $query = $request->all();
            //     $arr_query = [];
            //     foreach($query as $key => $value){
            //         if ($value !== end($query)) {
            //             array_push($arr_query, $key, '=', $this->encryptWithKey($value), '&');
            //         }else{
            //             array_push($arr_query, $key, '=', $this->encryptWithKey($value));
            //         }
            //     }
            //     array_push($_arr_trx, $scheme, '://', $host, $_path, '?', implode('', $arr_query));
            //     $new_link = implode('', $_arr_trx);
            //     array_push($arr_trx, $new_link);
            //     $_trx = ToDoList::find($data->id);
            //     $_trx->link = $new_link;
            //     $_trx->update();
            // }
            // // jika tidak ada parameter
            // else{
            //     $request = Request::create($parsedUrl['path']);
            //     $scheme = $parsedUrl['scheme'];
            //     $host = $parsedUrl['host'];
            //     $path = $parsedUrl['path'];
            //     $_path = explode('/', $path);
            //     $_enc = $this->encryptWithKey($_path[count($_path)-2]);
            //     $_path[count($_path)-2] = $_enc;
            //     $_path = implode('/', $_path);
            //     array_push($_arr_trx, $scheme, '://', $host, $_path);
            //     $new_link = implode('', $_arr_trx);
            //     array_push($arr_trx, $new_link);
            //     $_trx = ToDoList::find($data->id);
            //     $_trx->link = $new_link;
            //     $_trx->update();
            // }
            // end
        }
        dd($arr_trx);
    }

    public function encryptWithKey($data)
    {
        try {
            // $key = env('LINK_KEY');
            $key = MtrUtility::find(34)->informasi;
            $method = 'AES-256-CBC';
            // Generate an IV (Initialization Vector) that matches the block size for AES
            $iv = Str::random(16); // AES-256-CBC uses a 16-byte IV
            // Encrypt the data with the key
            $encryptedData = openssl_encrypt($data, $method, $key, 0, $iv);
            $str = base64_encode($encryptedData . '::' . $iv);
            // check jika belum benar maka ulangi kembali sampai benar
            if($this->decryptWithKey($str) == $data){
                return $str;
            }else{
                return $this->encryptWithKey($data);
            }
            // while (!$this->decryptWithKey(base64_encode($encryptedData . '::' . $iv))) {
            //     sleep(1);
            // }
            // // Combine the encrypted data with the IV and base64 encode the result
            // return base64_encode($encryptedData . '::' . $iv);
        } catch (\Exception $e) {
            return redirect()->route('error-500');
            // Handle the exception, for example log it or display a message
            dd('Caught exception: ' . $e->getMessage());
        }
    }

    public function decryptWithKey($encodedData)
    {
        try {
            // $key = env('LINK_KEY');
            $key = MtrUtility::find(34)->informasi;
            $method = 'AES-256-CBC';
            // Decode the base64 encoded data
            $decodedData = base64_decode($encodedData);
            // Split the data into the encrypted message and the IV
            list($encryptedData, $iv) = explode('::', $decodedData);
            // Decrypt the data with the key
            return openssl_decrypt($encryptedData, $method, $key, 0, $iv);
        } catch (\Exception $e) {
            return redirect()->route('error-500');
            // Handle the exception, for example log it or display a message
            dd('Caught exception: ' . $e->getMessage());
        }
    }

    public function get_pekerjaan($id) {
        // $data = PekerjaanSub::select('id','pekerjaan','tugas','wewenang','tanggung_jawab')->orderBy('pekerjaan', 'asc')->get();
        $data = DB::table('mtr_tbl_pekerjaan_sub as a')->leftJoin('mtr_tbl_pekerjaan as b', 'a.id_bidang', '=', 'b.id')->select('a.id', 'b.bidang', 'a.pekerjaan', 'a.tugas', 'a.wewenang', 'a.tanggung_jawab')->orderBy('a.pekerjaan', 'asc')->get();
        return json_decode($data, true);
    }

    public function get_pelatihan($id) {
        $data = PelatihanSub::orderBy('sub_pelatihan', 'asc')->get();
        return json_decode($data, true);
    }

    public function get_sertifikasi($id) {
        $data = SertifikasiSub::orderBy('sub_sertifikasi', 'asc')->get();
        return json_decode($data, true);
    }

    public function get_keterampilan($id) {
        $data = KeterampilanSub::orderBy('sub_keterampilan', 'asc')->get();
        return json_decode($data, true);
    }

    public function get_kepribadian($id) {
        $data = KepribadianSub::orderBy('sub_kepribadian', 'asc')->get();
        return json_decode($data, true);
    }

    public function getKaryawanPerusahaan($id){
        $data = DB::select("SELECT COUNT(a.nik) AS jumlah, b.nama_singkat AS nama FROM mtr_tbl_karyawan AS a LEFT JOIN mtr_tbl_perusahaan AS b ON a.kode_perusahaan=b.id WHERE a.is_aktif=1 AND a.nik NOT IN ('IT.BM','Hco.all') GROUP BY a.kode_perusahaan");
        $perusahaan = array();
        $karyawan = array();
        $total = 0;
        foreach($data as $_data){
            array_push($perusahaan, $_data->nama);
            array_push($karyawan, $_data->jumlah);
            $total += $_data->jumlah;
        }
        return response()->json(['perusahaan'=>$perusahaan, 'karyawan'=>$karyawan, 'total'=>$total]);
    }

    public function getDivisi($id) {
        $data = Divisi::whereIn('kode_perusahaan', explode(',', $id))->orderBy('nama', 'asc')->get();
        return json_decode($data, true);
    }

    public function getDepartemen($id) {
        $data = Departemen::whereIn('kode_divisi', explode(',', $id))->orderBy('nama', 'asc')->get();
        return json_decode($data, true);
    }

    public function getSubDepartemen($id) {
        $data = SubDepartemen::whereIn('kode_departemen', explode(',', $id))->orderBy('nama', 'asc')->get();
        return json_decode($data, true);
    }

    public function get_propinsi_kota($id) {
        $data = PropinsiKota::whereIn('id_propinsi', explode(',', $id))->orderBy('kota', 'asc')->get();
        return json_decode($data, true);
    }

    public static function getUangSaku($id) {
        $biaya = TrxBpdDetailBiaya::find($id);
        $trx = TrxBpdMaster::find($biaya->id_trx);
        $info = TrxBpdDetailInfo::find($biaya->id_info);
        $nominal = array();
        $tujuan1 = 0;
        if(!empty($info->tujuan1)){
            $tujuan1 = $info->tujuan1;
        }else{
            array_push($nominal, $biaya->nominal);
        }
        $is_project = $trx->is_project;
        $luar_dalam = $trx->luar_dalam;
        $project_id = $trx->project_id;
        $kelompok_id = $trx->kelompok_id;
        // project
        if($is_project == 1){
            // ca_dalam
            if($luar_dalam == 'ca_dalam' || $luar_dalam == 'reimburse'){
                // foreach ($trxDetailInfo as $data){
                    // $hari = Carbon::parse($data->tanggal_mulai)->diffInDays(Carbon::parse($data->tanggal_akhir))+1;
                    // $hari = $data->hari;
                    // dd($hari);
                    // jika Project IOH dan ada id_kelompok nya
                    $_where = ['id_proyek'=>$project_id, 'id_kelompok'=>$kelompok_id];
                    $uang_saku = BpdProyekUangSakuKelompok::where($_where)->get();
                    if(count($uang_saku)>0){
                        // $_uang_saku = $uang_saku->uangsaku;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->uangsaku);
                        }
                        // 1 = 500240 = BIAYA UANG SAKU = Project
                        // 2 = 500240 = BIAYA UANG SAKU = Support
                        // $biaya = 1;
                        // $satuan = 'IDR';
                        // $bpd_biaya = ['id_info'=>$data->id, 'jenis'=>$jenis, 'id'=>$id, 'biaya'=>array($biaya), 'satuan'=>array($satuan), 'nominal'=>array($_uang_saku), 'jumlah'=>array($hari), 'is_approve'=>$is_approve];
                        // $this->insertBpdBiaya($bpd_biaya);
                    }
                    // jika bukan Project IOH atau tidak ada id_kelompok nya
                    else{
                        // pecah-pecah jumlah hari sesuai dengan batasan jumlah hari berdasarkan kelompok
                        // $arr_hari['hari'] = array();
                        // $arr_hari['kelompok'] = array();
                        // // jumlah hari dari 1 sd 7 hari
                        // if($hari < 8){
                        //     array_push($arr_hari['hari'], $hari);
                        //     array_push($arr_hari['kelompok'], 7);
                        // }
                        // // jumlah hari dari 8 sd 30 hari
                        // else if($hari > 7 && $hari < 31){
                        //     array_push($arr_hari['hari'], 7);
                        //     array_push($arr_hari['kelompok'], 7);
                        //     array_push($arr_hari['hari'], $hari-7);
                        //     array_push($arr_hari['kelompok'], 30);
                        // }
                        // // jumlah hari lebih dari 30 hari
                        // else if($hari > 30){
                        //     array_push($arr_hari['hari'], 7);
                        //     array_push($arr_hari['kelompok'], 7);
                        //     array_push($arr_hari['hari'], 23);
                        //     array_push($arr_hari['kelompok'], 30);
                        //     array_push($arr_hari['hari'], $hari-30);
                        //     array_push($arr_hari['kelompok'], 365);
                        // }
                        // 
                        // for ($x = 0; $x < count($arr_hari['hari']); $x++){
                            $_where = ['id_propinsi'=>$tujuan1];
                            $uang_saku = BpdProyekUangSaku::where($_where)->get();
                            // dd($uang_saku);
                            // 1 = Kelompok I
                            if($kelompok_id == 1){
                                // $_uang_saku = $uang_saku->uangsaku_1;
                                foreach ($uang_saku as $data){
                                    array_push($nominal, $data->uangsaku_1);
                                }
                            }
                            // 2 = Kelompok II
                            else if($kelompok_id == 2){
                                // $_uang_saku = $uang_saku->uangsaku_2;
                                foreach ($uang_saku as $data){
                                    array_push($nominal, $data->uangsaku_2);
                                }
                            }
                            // 3 = Kelompok III
                            else if($kelompok_id == 3){
                                // $_uang_saku = $uang_saku->uangsaku_3;
                                foreach ($uang_saku as $data){
                                    array_push($nominal, $data->uangsaku_3);
                                }
                            }
                            // 4 = Kelompok IV
                            else if($kelompok_id == 4){
                                // $_uang_saku = $uang_saku->uangsaku_4;
                                foreach ($uang_saku as $data){
                                    array_push($nominal, $data->uangsaku_4);
                                }
                            }
                            // 5 = Kelompok V
                            else if($kelompok_id == 5){
                                // $_uang_saku = $uang_saku->uangsaku_5;
                                foreach ($uang_saku as $data){
                                    array_push($nominal, $data->uangsaku_5);
                                }
                            }
                            // 1 = 500240 = BIAYA UANG SAKU = Project
                            // 2 = 500240 = BIAYA UANG SAKU = Support
                            // $biaya = 1;
                            // $satuan = 'IDR';
                            // $bpd_biaya = ['id_info'=>$data->id, 'jenis'=>$jenis, 'id'=>$id, 'biaya'=>array($biaya), 'satuan'=>array($satuan), 'nominal'=>array($_uang_saku), 'jumlah'=>array($arr_hari['hari'][$x]), 'is_approve'=>$is_approve];
                            // $this->insertBpdBiaya($bpd_biaya);
                        // }
                    }
                // }
            }
            // ca_luar
            else if($luar_dalam == 'ca_luar'){
                // NA
            }
        }
        // non project
        else if($is_project == 0){
            // 
            if($luar_dalam == 'ca_dalam' || $luar_dalam == 'reimburse'){
                // foreach ($trxDetailInfo as $data){
                    // $hari = Carbon::parse($data->tanggal_mulai)->diffInDays(Carbon::parse($data->tanggal_akhir))+1;
                    // $uang_saku = BpdNonProyekBiayaDalamNegeri::find(1);
                    $_where = ['id_biaya'=>1];
                    $uang_saku = BpdNonProyekBiayaDalamNegeri::where($_where)->get();
                    // 6 = Kelompok I
                    if($kelompok_id == 6){
                        // $_uang_saku = $uang_saku->kelompok_1;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_1);
                        }
                    }
                    // 7 = Kelompok II
                    else if($kelompok_id == 7){
                        // $_uang_saku = $uang_saku->kelompok_2;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_2);
                        }
                    }
                    // 8 = Kelompok III
                    else if($kelompok_id == 8){
                        // $_uang_saku = $uang_saku->kelompok_3;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_3);
                        }
                    }
                    // 9 = Kelompok IV
                    else if($kelompok_id == 9){
                        // $_uang_saku = $uang_saku->kelompok_4;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_4);
                        }
                    }
                    // 10 = Kelompok V
                    else if($kelompok_id == 10){
                        // $_uang_saku = $uang_saku->kelompok_5;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_5);
                        }
                    }
                    // 11 = Kelompok VI
                    else if($kelompok_id == 11){
                        // $_uang_saku = $uang_saku->kelompok_6;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_6);
                        }
                    }
                    // 1 = 500240 = BIAYA UANG SAKU = Project
                    // 2 = 500240 = BIAYA UANG SAKU = Support
                    // $biaya = 2;
                    // $satuan = 'IDR';
                    // $bpd_biaya = ['id_info'=>$data->id, 'jenis'=>$jenis, 'id'=>$id, 'biaya'=>array($biaya), 'satuan'=>array($satuan), 'nominal'=>array($_uang_saku), 'jumlah'=>array($hari), 'is_approve'=>$is_approve];
                    // $this->insertBpdBiaya($bpd_biaya);
                // }
            }
            // ca_luar
            else if($luar_dalam == 'ca_luar'){
                // foreach ($trxDetailInfo as $data){
                    // $hari = Carbon::parse($data->tanggal_mulai)->diffInDays(Carbon::parse($data->tanggal_akhir))+1;
                    $where = ['id_negara'=>$tujuan1, 'id_biaya'=>1];
                    $uang_saku = BpdNonProyekBiayaLuarNegeri::where($where)->get();
                    // 6 = Kelompok I
                    if($kelompok_id == 6){
                        // $_uang_saku = $uang_saku->kelompok_1;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_1);
                        }
                    }
                    // 7 = Kelompok II
                    else if($kelompok_id == 7){
                        // $_uang_saku = $uang_saku->kelompok_2;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_2);
                        }
                    }
                    // 8 = Kelompok III
                    else if($kelompok_id == 8){
                        // $_uang_saku = $uang_saku->kelompok_3;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_3);
                        }
                    }
                    // 9 = Kelompok IV
                    else if($kelompok_id == 9){
                        // $_uang_saku = $uang_saku->kelompok_4;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_4);
                        }
                    }
                    // 10 = Kelompok V
                    else if($kelompok_id == 10){
                        // $_uang_saku = $uang_saku->kelompok_5;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_5);
                        }
                    }
                    // 11 = Kelompok VI
                    else if($kelompok_id == 11){
                        // $_uang_saku = $uang_saku->kelompok_6;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_6);
                        }
                    }
                    // 1 = 500240 = BIAYA UANG SAKU = Project
                    // 2 = 500240 = BIAYA UANG SAKU = Support
                    // $biaya = 2;
                    // $satuan = 'USD';
                    // $bpd_biaya = ['id_info'=>$data->id, 'jenis'=>$jenis, 'id'=>$id, 'biaya'=>array($biaya), 'satuan'=>array($satuan), 'nominal'=>array($_uang_saku), 'jumlah'=>array($hari), 'is_approve'=>$is_approve];
                    // $this->insertBpdBiaya($bpd_biaya);
                // }
            }
        }
        // $data = SubDepartemen::whereIn('kode_departemen', explode(',', $id))->get();
        return $nominal;
    }
}
