<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Perusahaan;
use App\Models\Divisi;
use App\Models\Departemen;
use App\Models\SubDepartemen;
use App\Models\Level;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\KaryawanLog;
use App\Models\KaryawanPendidikan;
use App\Models\TrxPenilaian;
use App\Models\TrxPenilaianLog;
use App\Models\TrxPenilaianLogHistory;
use App\Models\TrxPenilaianSkor;
use App\Models\MtrPenilaianTipe;
use App\Models\MtrPenilaianStatus;
use App\Models\MtrPenilaianPerpanjang;
use App\Models\MtrPenilaian1;
use App\Models\MtrPenilaian2;
use App\Models\MtrPenilaian3;
use App\Models\MtrPenilaianOutsource;
use Illuminate\Support\Facades\Log;
// 
use App\Models\ToDoList;
use App\Models\ToDoListLog;
use App\Models\MtrUtility;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;
// 
use DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Elibyy\TCPDF\Facades\TCPDF;
// 
use App\Models\TrxPayroll;
use App\Models\TrxPayrollLog;
use App\Models\TrxPayrollAddsub;
use App\Models\TrxPayrollAddsubLog;
// 
class TrxPenilaianController extends Controller
{
    public function kontrak_update(Request $request)
    {
        // dd($request->all());
        $trx = TrxPenilaian::find($request->id);
        $trx->no_kontrak = $request->no_kontrak;
        $trx->tanggal_kontrak = $request->tanggal_kontrak;
        $trx->tanggal_terhitung = $request->tanggal_terhitung;
        $trx->id_perusahaan = $request->id_perusahaan;
        $trx->id_divisi = $request->id_divisi;
        $trx->id_departemen = $request->id_departemen;
        $trx->id_sub_departemen = $request->id_sub_departemen;
        $trx->id_level = $request->id_level;
        $trx->id_jabatan = $request->id_jabatan;
        $trx->gapok = str_replace(',', '', $request->gapok);
        $trx->t_makan = str_replace(',', '', $request->t_makan);
        $trx->t_transportasi = str_replace(',', '', $request->t_transportasi);
        $trx->t_jabatan = str_replace(',', '', $request->t_jabatan);
        $trx->t_telekomunikasi = str_replace(',', '', $request->t_telekomunikasi);
        $trx->t_lain = str_replace(',', '', $request->t_lain);
        $getOriginal = $trx->getOriginal();
        $trx->update();
        // simpan jika ada perubahan
        $getChanges = $trx->getChanges();
        // dd($getOriginal, $getChanges);
        // start
        $arr = array();
        if ($trx->wasChanged('id_perusahaan')){
            $arr['kode_perusahaan'] = ['before'=>Perusahaan::find($getOriginal['id_perusahaan'])->nama, 'after'=>Perusahaan::find($getChanges['id_perusahaan'])->nama];
            $update['kode_perusahaan'] = $request->id_perusahaan;
        }
        if ($trx->wasChanged('id_divisi')){
            $arr['kode_divisi'] = ['before'=>Divisi::find($getOriginal['id_divisi'])->nama, 'after'=>Divisi::find($getChanges['id_divisi'])->nama];
            $update['kode_divisi'] = $request->id_divisi;
        }
        if ($trx->wasChanged('id_departemen')){
            $arr['kode_departemen'] = ['before'=>Departemen::find($getOriginal['id_departemen'])->nama, 'after'=>Departemen::find($getChanges['id_departemen'])->nama];
            $update['kode_departemen'] = $request->id_departemen;
        }
        if ($trx->wasChanged('id_sub_departemen')){
            $arr['kode_sub_departemen'] = ['before'=>SubDepartemen::find($getOriginal['id_sub_departemen'])->nama, 'after'=>SubDepartemen::find($getChanges['id_sub_departemen'])->nama];
            $update['kode_sub_departemen'] = $request->id_sub_departemen;
        }
        if ($trx->wasChanged('id_level')){
            $arr['kode_level'] = ['before'=>Level::find($getOriginal['id_level'])->level, 'after'=>Level::find($getChanges['id_level'])->level];
            $update['kode_level'] = $request->id_level;
        }
        if ($trx->wasChanged('id_jabatan')){
            $arr['kode_jabatan'] = ['before'=>Jabatan::find($getOriginal['id_jabatan'])->jabatan, 'after'=>Jabatan::find($getChanges['id_jabatan'])->jabatan];
            $update['kode_jabatan'] = $request->id_jabatan;
        }
        if(!empty($arr)){
            Karyawan::where('nik', $trx->nik_karyawan)->update($update);
            $log = New KaryawanLog;
            $log->nik = $trx->nik_karyawan;
            $log->perubahan = json_encode($arr);
            $log->insert_by = auth()->user()->nik;
            $catatan = json_encode(['sumber'=>'Data PKWT Karyawan']);
            $log->catatan = $catatan;
            $log->save();
            // start sendEmail
            $mailData['title'] = 'Perubahan Data Karyawan';
            $mailData['filename'] = 'emails.data0';
            $lbl_history = ["tanggal_lahir"=>"Tanggal Lahir","tempat_lahir"=>"Tempat Lahir","alamat_domisili"=>"Alamat Domisili","alamat_ktp"=>"Alamat KTP","alamat_npwp"=>"Alamat NPWP","telepon1"=>"Telepon 1","telepon2"=>"Telepon 2","telepon3"=>"Telepon 3","no_ktp"=>"NIK KTP","no_npwp"=>"NPWP","no_bpjs"=>"No BPJS Kesehatan","no_bpjstk"=>"No BPJS Ketenagakerjaan","nama_pasangan"=>"Nama Pasangan","kode_kelamin"=>"Jenis Kelamin","kode_agama"=>"Agama","kode_kawin"=>"Status Kawin","kode_warga"=>"Kewarganegaraan","nama"=>"Nama","telepon_darurat"=>"Telepon Darurat","nama_anak"=>"Nama Anak","email"=>"Email","kode_perusahaan"=>"Perusahaan","alamat_kantor"=>"Alamat Kantor","kode_divisi"=>"Direktorat","kode_departemen"=>"Divisi","kode_sub_departemen"=>"Departemen","layer1"=>"Layer 1","layer2"=>"Layer 2","layer3"=>"Layer 3","kode_status"=>"Status Karyawan","wg_id"=>"Grup Kerja","is_aktif"=>"Aktif","gaji_pokok"=>"Gaji Pokok","kode_level"=>"Level","kode_jabatan"=>"Jabatan","tanggal_mulai"=>"Tanggal Mulai Bekerja","tanggal_bergabung"=>"Tanggal Bergabung","tanggal_berakhir"=>"Tanggal Berakhir","tanggal_karyawan_tetap"=>"Tanggal Karyawan Tetap","tanggal_tidak_aktif"=>"Tanggal Tidak Aktif","no_rek"=>"Nomor Rekening","nama_bank"=>"Nama Bank","nama_rek"=>"Rekening Atas Nama","findim_project"=>"Findim Project","findim_divisi"=>"Findim Divisi","findim_departemen"=>"Findim Departemen","findim_subdepartemen"=>"Findim Sub Departemen","id_grade"=>"Grade","id_fp"=>"ID Finger Print"];
            $mailData['data'] = ['insert_by' => $log->_insert_by->nama.' / '.$log->insert_by, 'nik' => $log->nik, 'nama' => $log->_nik->nama, 'perubahan' => $log->perubahan, 'lbl_history' => $lbl_history, 'created_at' => $log->created_at, 'catatan' => $catatan];
            $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
            $this->sendEmail($mailData);
            // end sendEmail
        }
        // end
        return redirect()->to($request->back_url)->with( 'msg', 'Data is saved successfully.' );
    }
    
    public function kontrak_dok(Request $request, $id)
    {
        $trx = TrxPenilaian::from('trx_tbl_penilaian as a')->select('a.*', 'b.nama')
        ->leftJoin('mtr_tbl_karyawan as b', 'b.nik', '=', 'a.nik_karyawan')
        ->whereNotNull('a.no_kontrak')
        ->orderBy('b.nama', 'asc')
        ->get();
        return view( 'formulir.penilaian.kontrak_dok', compact( 'trx'));
    }

    // http://localhost/matta/penilaian/kontrak/0
    public function kontrak(Request $request, $id)
    {
        // dd($request->all(), $id);
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        Carbon::setLocale('id');
        $angka_romawi = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        $formatter = new \NumberFormatter('id', \NumberFormatter::SPELLOUT);
        $id = (new OpenDataController)->decryptWithKey($id);
        $trx = TrxPenilaian::find($id);
        $karyawan = Karyawan::where(['nik'=>$trx->nik_karyawan])->first();
        // 041-PKWT/ADM-APM/PC/III/2026
        $no_kontrak = $trx->no_kontrak;
        if(!empty($no_kontrak)){
            $no_kontrak = str_pad($no_kontrak, 3, '0', STR_PAD_LEFT).'-PKWT/ADM-APM/PC/';
        }
        $tanggal_kontrak = $trx->tanggal_kontrak;
        if(!empty($tanggal_kontrak)){
            $tanggal_kontrak = $this->tanggalKeTeks($tanggal_kontrak);
            $tanggal = Carbon::parse($trx->tanggal_kontrak);
            $no_kontrak .= $angka_romawi[$tanggal->month].'/'.$tanggal->year;
        }
        $tanggal_terhitung = $trx->tanggal_terhitung;
        if(!empty($tanggal_terhitung)){
            $tanggal_terhitung = $this->tanggalKeTeks($tanggal_terhitung);
        }
        $tanggal_lahir = $karyawan->tanggal_lahir;
        if(!empty($tanggal_lahir)){
            $tanggal_lahir = Carbon::parse($karyawan->tanggal_lahir)->translatedFormat('j F Y');
        }
        // $kelamin = $karyawan->kode_kelamin;
        // if(!empty($kelamin)){
        //     $kelamin = $karyawan->_kelamin->kelamin;
        // }
        // $agama = $karyawan->kode_agama;
        // if(!empty($agama)){
        //     $agama = $karyawan->_agama->agama;
        // }
        // $kawin = $karyawan->kode_kawin;
        // if(!empty($kawin)){
        //     $kawin = $karyawan->_kawin->status_kawin;
        // }
        $pendidikan = '';
        $jenjang = KaryawanPendidikan::where(['nik'=>$trx->nik_karyawan])->orderBy('id_jenjang', 'desc')->first();
        if(!empty($jenjang)){
            $pendidikan .= $jenjang->_jenjang->jenjang;
            if(!empty($jenjang->jurusan_prodi)){
                $pendidikan .= ' ('.$jenjang->jurusan_prodi.')';
            }
        }
        $bulan = $trx->bulan;
        if(!empty($bulan)){
            $bulan .= ' ('.$formatter->format($bulan).') bulan';
        }
        // 2 Maret 2026 sampai dengan 31 Desember 2026
        $durasi = $trx->tanggal_terhitung;
        if(!empty($durasi)){
            $durasi = Carbon::parse($durasi)->translatedFormat('j F Y').' sampai dengan '.Carbon::parse($durasi)->addMonths(($trx->bulan)-1)->endOfMonth()->translatedFormat('j F Y');
        }
        // payrol
        $payroll = $trx->gapok;
        $payroll += $trx->t_makan;
        $payroll += $trx->t_transportasi;
        $payroll += $trx->t_jabatan;
        $payroll += $trx->t_telekomunikasi;
        $payroll += $trx->t_lain;
        $nominal = Str::title($formatter->format($payroll));
        // 
        // 0=NA
        // 1=Non Staff
        // 2=Staff
        // 3=Supervisor (SPV)
        // 01031908799=Khatrin Rismasany
        $pic_hco = explode(',', MtrUtility::find(50)->informasi);
        $hco = Karyawan::where(['is_aktif' => 1, 'nik'=>$pic_hco[0]])->first();
        // 4=Assistant Manager
        // 5=Junior Manager (JM)
        // 6=Manager
        // 7=Senior Manager (SM)
        // 8=Junior General Manager (JGM)
        // 9=General Manager (GM)
        // 10=Senior General Manager (SGM / Senior GM)
        // 11=Executive General Manager (EGM)
        // 12=Vice President (VP)
        // 13=Senior Vice President (SVP)
        // 14=Direktur
        if(in_array($trx->id_level, range(4, 14))){
            // 01029803003=Bambang Andjar Prasetyono
            $hco = Karyawan::where(['is_aktif' => 1, 'nik'=>$pic_hco[1]])->first();
        }
        $waktu = date('YmdHis');
        $filename = 'Kontrak PKWT '.$waktu.'.pdf';
        $header = $request->header;
        $pdf = new TCPDF;
        $pdf::SetTitle('Kontrak PKWT');
        $pdf::SetFont('times', '', 10, '', true);
        // Page 1
        $pdf::AddPage();
        // $param = 10.00125;
        $left = 25.4;
        $top = 20.6;
        $right = 25.4;
        $pdf::SetMargins(0, 0, $right);
        $pdf::SetAutoPageBreak(true, 0);
        if($header){
            $bgImagePath = public_path('assets/images/matta/bg_alita_a4_header.png');
            $pdf::Image($bgImagePath, 0, 0, 210, 0, '', '', '', false, 300, '', false, false, 0);
            $bgImagePath = public_path('assets/images/matta/bg_alita_a4_footer.png');
            $pdf::Image($bgImagePath, 0, 275, 210, 0, '', '', '', false, 300, '', false, false, 0);
        }
        // $top = 20.6;
        // $x = 7;
        // $y = 23;
        // $x = 25.4;
        $x = $left;
        $y = $top;
        // No.041-PKWT/ADM-APM/PC/III/2026<br/>
        $html = '<div style="text-align:center;"><b><u>PERJANJIAN KERJA WAKTU TERTENTU</u></b><br/>
        No.'.$no_kontrak.'<br/>
        ANTARA<br/>
        <b>PT. ALITA PRAYA MITRA</b><br/>
        DENGAN<br/>
        <b>'.strtoupper($karyawan->nama).'</b></div>';
        // $pdf::writeHTMLCell(0, 0, 0, $y, $html, 0, 1, 0, true, '', true);
        $pdf::writeHTMLCell(0, 0, $x, $y+=5, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:justify;">Pada hari ini '.$tanggal_kontrak.' di Kantor PT. ALITA PRAYA MITRA Jl. Muara Dalam No.108 Kelurahan Tanjung Barat Kecamatan Jagakarsa, Jakarta Selatan, telah tercapai kesepakatan antara pihak-pihak :</div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=35, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:justify;"><table>
        <tr>
            <td width="4%">I.</td>
            <td width="96%"><b>PT. ALITA PRAYA MITRA</b> berkedudukan di Jl. Muara Dalam No.108 Kelurahan Tanjung Barat Kecamatan Jagakarsa, Jakarta 12530, yang dalam perbuatan hukum ini diwakili secara sah oleh <b>'.$hco->nama.'</b> dalam kapasitasnya mewakili Direksi sebagai '.$hco->_jabatan->jabatan.', dan untuk selanjutnya disebut sebagai <b>Pihak Pertama</b>.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>II.</td>
            <td><table>
                    <tr>
                        <td width="25%">Nama</td>
                        <td width="2%">:</td>
                        <td width="73%">'.$karyawan['nama'].'</td>
                    </tr>
                    <tr>
                        <td>Tempat & tgl lahir</td>
                        <td>:</td>
                        <td>'.ucfirst($karyawan->tempat_lahir).' / '.$tanggal_lahir.'</td>
                    </tr>
                    <tr>
                        <td>NIK/KTP</td>
                        <td>:</td>
                        <td>'.$karyawan->no_ktp.'</td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td>:</td>
                        <td>'.$karyawan->_kelamin->kelamin.'</td>
                    </tr>
                    <tr>
                        <td>Agama</td>
                        <td>:</td>
                        <td>'.$karyawan->_agama->agama.'</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>:</td>
                        <td>'.$karyawan->_kawin->status_kawin.'</td>
                    </tr>
                    <tr>
                        <td>Pendidikan terakhir</td>
                        <td>:</td>
                        <td>'.$pendidikan.'</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td>'.$karyawan->alamat_ktp.'</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Untuk selanjutnya disebut <b>Pihak Kedua</b>.</td>
        </tr>
        </table></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=15, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:justify;">Dan secara bersamaan dalam Perjanjian Kerja Waktu Tertentu (<b>“Perjanjian”</b>) ini disebut sebagai <b>Para Pihak</b>.</div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=75, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:center;"><b>PASAL 1<br/>HUBUNGAN KERJA DAN MASA KERJA</b></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=10, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:justify;"><table>
        <tr>
            <td width="4%">1)</td>
            <td width="96%">Bahwa Pihak Pertama dengan ini sepakat untuk mempekerjakan Pihak Kedua secara tidak tetap dan Pihak Kedua dengan ini sepakat untuk bekerja bagi dan untuk kepentingan Pihak Pertama secara tidak tetap dengan hubungan kerja berdasarkan Perjanjian Kerja Waktu Tertentu (PKWT) sebagaimana ditentukan dalam Perjanjian ini.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>2)</td>
            <td>Yang dimaksud dengan Waktu Tertentu adalah bahwa Pihak Pertama mempekerjakan Pihak Kedua di bawah Divisi <b>'.$trx->_departemen->nama.'</b> selama '.$bulan.' terhitung mulai tanggal '.$durasi.' dan dapat diperpanjang dan/atau diperbaharui atas persetujuan kedua belah pihak.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>3)</td>
            <td>Perjanjian ini adalah berbasis proyek dan bisa diakhiri jika proyek berakhir sebelum masa kontrak yang tercantum dalam Pasal 1 ayat (2).</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>4)</td>
            <td>Dengan berakhirnya Perjanjian sebagaimana yang dimaksud pada ayat (2) di atas, maka Karyawan berhak mendapatkan uang kompensasi sebagaimana diatur pada PP No. 35 tahun 2021 atau Peraturan Perundang-undangan yang berlaku, maka Pihak Kedua berhak atas uang kompensasi. Pemberian uang kompensasi diberikan oleh Pihak Pertama pada saat bersamaan dengan tanggal pembayaran upah bulanan.</td>
        </tr>
        </table></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=15, $html, 0, 1, 0, true, '', true);
        // page 2
        $pdf::AddPage();
        $pdf::SetAutoPageBreak(true, 0);
        if($header){
            $bgImagePath = public_path('assets/images/matta/bg_alita_a4_header_p2.png');
            $pdf::Image($bgImagePath, 0, 0, 210, 0, '', '', '', false, 300, '', false, false, 0);
            $bgImagePath = public_path('assets/images/matta/bg_alita_a4_footer.png');
            $pdf::Image($bgImagePath, 0, 275, 210, 0, '', '', '', false, 300, '', false, false, 0);
        }
        // 
        $x = $left;
        $y = $top;
        $html = '<div style="text-align:center;"><b>PASAL 2<br/>PEKERJAAN DAN PENEMPATAN</b></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:justify;"><table>
        <tr>
            <td width="4%">1)</td>
            <td width="96%">Pekerjaan sebagaimana dimaksud dalam Perjanjian ini adalah sebagai berikut:<br/>
                <table>
                        <tr>
                            <td width="2%">a.</td>
                            <td width="20%">Posisi / Jabatan</td>
                            <td width="2%">:</td>
                            <td width="76%">'.$trx->_jabatan->jabatan.'</td>
                        </tr>
                        <tr>
                            <td>b.</td>
                            <td>Level</td>
                            <td>:</td>
                            <td>'.$trx->_level->level.'</td>
                        </tr>
                        <tr>
                            <td>c.</td>
                            <td>Unit Kerja</td>
                            <td>:</td>
                            <td>'.$trx->_sub_departemen->nama.'</td>
                        </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>2)</td>
            <td>Selain melakukan pekerjaan sebagaimana dimaksud dalam ayat (1) Pasal ini, Pihak Kedua sepakat untuk melakukan pekerjaan tambahan diluar ruang lingkup Pekerjaan tersebut yang ditugaskan oleh Pihak Pertama sepanjang untuk kepentingan ruang lingkup pekerjaan tersebut.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>3)</td>
            <td>Pihak Kedua bertempat kerja di Kantor Pihak Pertama, Jl. Muara Dalam No.108 Kelurahan Tanjung Barat Kecamatan Jagakarsa, Jakarta Selatan dan/atau di tempat yang telah ditunjuk oleh Pihak Pertama.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>4)</td>
            <td>Bahwa yang bersangkutan bersedia untuk ditugaskan dan/atau ditempatkan di Perusahaan lain dalam lingkungan Pihak Pertama dan/atau anak perusahaannya.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>5)</td>
            <td>Pihak Kedua dapat melakukan perjalanan dinas selama diperintahkan oleh Atasan Langsung pada Divisi terkait dan/atau Direktorat lainnya. Untuk itu Pihak Kedua mendapatkan Biaya Perjalanan Dinas sesuai dengan ketentuan yang berlaku (<b>“Biaya Perjalanan Dinas”</b>).</td>
        </tr>
        </table></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=15, $html, 0, 1, 0, true, '', true);
        // 
        $html = '<div style="text-align:center;"><b>PASAL 3<br/>PENDIDIKAN / TRAINING / SEMINAR</b></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=85, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:justify;"><table>
        <tr>
            <td width="4%">1)</td>
            <td width="96%">Apabila Pihak Pertama menugaskan Pihak Kedua untuk mengikuti Pendidikan / Training / Seminar, maka Pihak Kedua <b>wajib</b> mengaplikasikan ilmunya sampai dengan masa yang diatur dalam Pasal 1 Ayat 2 Perjanjian ini berakhir.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>2)</td>
            <td>Apabila Pihak Kedua memutuskan Hubungan Kerja sebelum masa yang diatur dalam Pasal 1 Ayat 2 Perjanjian ini berakhir dan telah mengikuti Pendidikan / Training / Seminar yang ditugaskan Pihak Pertama, maka Pihak Kedua <b>wajib</b> mengganti seluruh Biaya Pendidikan / Training / Seminar yang telah dikeluarkan oleh Pihak Pertama.</td>
        </tr>
        </table></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=15, $html, 0, 1, 0, true, '', true);
        // 
        $html = '<div style="text-align:center;"><b>PASAL 4<br/>UPAH</b></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=35, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:justify;"><table>
        <tr>
            <td width="4%">1)</td>
            <td width="96%">Besarnya Upah yang diterima Pihak Kedua adalah sebagaimana terlampir.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>2)</td>
            <td>Besarnya Upah tersebut adalah bersifat <b>RAHASIA</b> dan tidak untuk di-informasikan kepada Pihak manapun termasuk kepada Rekan Kerja.</td>
        </tr>
        </table></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=15, $html, 0, 1, 0, true, '', true);
        // 
        $html = '<div style="text-align:center;"><b>PASAL 5<br/>JADWAL KERJA</b></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=25, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:justify;"><table>
        <tr>
            <td width="4%">1)</td>
			<td width="96%">Pihak Kedua berkewajiban untuk memenuhi Jadwal Kerja yang berlaku yaitu Senin – Jumat pukul 08.30 – 17.30.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>2)</td>
            <td>Pihak Kedua berkewajiban untuk melakukan absensi kehadiran melalui aplikasi MATTA.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>3)</td>
            <td>Apabila dalam jangka waktu 5 (lima) hari berturut-turut Pihak Kedua mangkir dari Pekerjaan, maka Pihak Pertama dapat menjatuhkan sanksi berupa Pemutusan Hubungan Kerja.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>4)</td>
            <td>Pihak Kedua berkewajiban untuk memberikan keterangan apabila berhalangan untuk bekerja.</td>
        </tr>
        </table></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=15, $html, 0, 1, 0, true, '', true);
        // page 3
        $pdf::AddPage();
        $pdf::SetAutoPageBreak(true, 0);
        if($header){
            $bgImagePath = public_path('assets/images/matta/bg_alita_a4_header_p2.png');
            $pdf::Image($bgImagePath, 0, 0, 210, 0, '', '', '', false, 300, '', false, false, 0);
            $bgImagePath = public_path('assets/images/matta/bg_alita_a4_footer.png');
            $pdf::Image($bgImagePath, 0, 275, 210, 0, '', '', '', false, 300, '', false, false, 0);
        }
        // 
        $x = $left;
        $y = $top;
        $html = '<div style="text-align:center;"><b>PASAL 6<br/>SANKSI</b></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:justify;"><table>
        <tr>
            <td width="4%">1)</td>
            <td width="96%">Pihak Pertama dapat memutuskan Hubungan Kerja dengan Pihak Kedua sebelum masa yang diatur dalam Pasal 1 Ayat 2 Perjanjian ini berakhir apabila Pihak Kedua tidak memenuhi pasal-pasal yang ditentukan dalam Perjanjian ini.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>2)</td>
            <td>Apabila Pihak Kedua memutuskan hubungan kerja dengan Pihak Pertama sebelum melewati masa kerja seperti yang diatur dalam Pasal 1 ayat 2 Perjanjian ini berakhir, maka Pihak Kedua <b>wajib</b> membayar kompensasi kepada Pihak Pertama sebesar <b>Nilai Gaji x Sisa Masa Kontrak</b> dan/atau sesuai dengan kesepakatan, hal ini berlaku juga sebaliknya.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>3)</td>
            <td>Pihak Pertama dapat memutuskan Hubungan Kerja dengan Pihak Kedua sebelum masa yang diatur dalam Pasal 1 Ayat 2 Perjanjian ini berakhir apabila Pihak Kedua tidak menunjukkan kinerja yang diharapkan oleh Pihak Pertama dengan disertai hasil Penilaian Kinerja oleh Atasan Langsung dan/atau Kepala Divisi terkait dan telah disepakati Kedua Pihak, TANPA dikenakan kompensasi seperti yang diatur dalam Pasal 6 Ayat 2.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>4)</td>
            <td>Dalam hal pengakhiran hubungan kerja pada ayat (3) diatas atau dikarenakan Pihak Pertama melakukan PHK dikarenakan Pihak Kedua melakukan pelanggaran mendesak dan melanggar ketentuan Perusahaan, maka Pihak Pertama tidak berkewajiban memberikan kompensasi yang tercantum dalam pasal 1 ayat (4) perjanjian ini, dalam bentuk apapun termasuk ganti rugi kepada pihak lainnya sebesar upah pekerja sampai batas waktu berakhirnya jangka waktu perjanjian kerja.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>5)</td>
            <td>Pihak Pertama dapat memutuskan Hubungan Kerja dengan Pihak Kedua sebelum masa yang diatur dalam Pasal 1 Ayat 2 Perjanjian ini berakhir apabila Negara dan/atau Perusahaan dalam kondisi <i>Force Majeure</i>, TANPA dikenakan kompensasi seperti yang diatur dalam Pasal 6 Ayat 2.</td>
        </tr>
        </table></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=15, $html, 0, 1, 0, true, '', true);
        // 
        $html = '<div style="text-align:center;"><b>PASAL 7<br/>KERAHASIAAN DATA DAN INFORMASI</b></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=110, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:justify;"><table>
        <tr>
            <td width="4%">1)</td>
            <td width="96%">Kerahasiaan Data dan Informasi yang dimaksud adalah Kerahasiaan Data dan Informasi yang tidak diketahui oleh pihak-pihak diluar kepentingan Pihak Pertama termasuk, namun tidak terbatas pada, aplikasi paten, data dan informasi yang berkaitan dengan inovasi dan penemuan baru, produk, perencanaan, perhitungan, konsep desain sistem, sistem informasi, program komputer, rencana marketing dan bisnis, pembiayaan, penentuan harga atau data dan informasi lain yang berkaitan dengan bisnis atau potensi bisnis Pihak Pertama.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>2)</td>
            <td>Pihak Kedua memahami dan menyetujui bahwa kerahasiaan data dan informasi tersebut merupakan rahasia bisnis dari Pihak Pertama dan selalu berkaitan dengan Perjanjian Kerja Karyawan ini, Pihak Pertama telah melakukan segala upaya untuk menjaga kerahasiaan data dan informasi yang dimaksud.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>3)</td>
            <td>Semua catatan, memorandum, disk computer dan/atau alat lain yang memiliki fungsi yang sama sebagai penyimpan data dan informasi adalah milik dan atau asset Pihak Pertama. Memindahtangankan, menyimpan untuk keperluan pribadi dan pemanfaatan di luar kepentingan perusahaan merupakan pelanggaran yang dapat dituntut secara hukum.</td>
        </tr>
        </table></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=15, $html, 0, 1, 0, true, '', true);
        // 
        $html = '<div style="text-align:center;"><b>PASAL 8<br/>LARANGAN PENGGUNAAN APLIKASI ILEGAL</b></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=70, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:justify;"><table>
        <tr>
            <td width="4%">1)</td>
            <td width="96%">Aplikasi ilegal adalah perangkat lunak yang digunakan tanpa lisensi resmi atau yang melanggar hak cipta dan peraturan hukum lainnya.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>2)</td>
            <td>Pihak Kedua dilarang keras mengunduh, menginstal, atau menggunakan aplikasi ilegal di perangkat milik perusahaan maupun perangkat pribadi yang digunakan untuk kepentingan pekerjaan. Setiap penggunaan aplikasi ilegal akan dianggap sebagai pelanggaran serius terhadap Peraturan Perusahaan.</td>
        </tr>
        </table></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=15, $html, 0, 1, 0, true, '', true);
        // page 4
        $pdf::AddPage();
        $pdf::SetAutoPageBreak(true, 0);
        if($header){
            $bgImagePath = public_path('assets/images/matta/bg_alita_a4_header_p2.png');
            $pdf::Image($bgImagePath, 0, 0, 210, 0, '', '', '', false, 300, '', false, false, 0);
            $bgImagePath = public_path('assets/images/matta/bg_alita_a4_footer.png');
            $pdf::Image($bgImagePath, 0, 275, 210, 0, '', '', '', false, 300, '', false, false, 0);
        }
        // 
        $x = $left;
        $y = $top;
        $html = '<div style="text-align:justify;"><table>
        <tr>
            <td width="4%">3)</td>
            <td width="96%">Pihak Kedua bertanggung jawab untuk memastikan semua aplikasi yang digunakan telah memiliki lisensi resmi dan sesuai dengan Peraturan Perusahaan dan harus melaporkan kepada atasan langsung jika mengetahui adanya penggunaan aplikasi ilegal di lingkungan kerja.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>4)</td>
            <td>Pelanggaran terhadap ketentuan ini dapat mengakibatkan tindakan disiplin, termasuk namun tidak terbatas pada pemutusan kontrak kerja, penghapusan hak atas tunjangan, dan tindakan hukum lainnya sesuai dengan kebijakan perusahaan dan peraturan perundang-undangan yang berlaku.</td>
        </tr>
        </table></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y, $html, 0, 1, 0, true, '', true);
        // 
        $html = '<div style="text-align:center;"><b>PASAL 9<br/>PERLINDUNGAN DATA PRIBADI</b></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=35, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:justify;"><table>
        <tr>
            <td width="4%">1)</td>
            <td width="96%">Selama jangka waktu Perjanjian ini, Pihak Kedua mengetahui dan menyetujui bahwa akan ada pemrosesan Data Pribadi (“<b>Data Pribadi</b>”) yang telah diberikan kepada Pihak Pertama.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>2)</td>
            <td>Pihak Kedua menjamin keaslian dan kebenaran Data Pribadi yang diberikan kepada Pihak Pertama.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>3)</td>
            <td>Pihak Pertama berkewajiban untuk melakukan pemrosesan dan/atau penyimpanan data dengan tujuan dan tata cara yang tercantum dalam Undang-undang Nomor 27 Tahun 2022 tentang Perlindungan Data Pribadi (“<b>UU PDP</b>”), maupun peraturan pelaksana lainnya.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>4)</td>
            <td>Pihak Pertama akan memastikan bahwa hak-hak yang dimiliki oleh Pihak Kedua selaku pemilik Data Pribadi sesuai dengan UU PDP, akan selalu berlaku, kecuali apabila ada sebab-sebab lain yang disepakati oleh Para Pihak.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>5)</td>
            <td>Pihak Kedua menjamin, melepaskan dan/atau membebaskan Pihak Pertama dari ganti rugi atas segala kerugian, risiko dan/atau konsekuensi akibat kegiatan pemrosesan Data Pribadi secara ilegal oleh pihak ketiga.</td>
        </tr>
        </table></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=15, $html, 0, 1, 0, true, '', true);
        // 
        $html = '<div style="text-align:center;"><b>PASAL 10<br/>PENANDATANGANAN DIGITAL</b></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=70, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:justify;"><table>
        <tr>
            <td width="4%">1)</td>
            <td width="96%">Perjanjian ini dapat dilaksanakan dengan tanda tangan elektronik atau tanda tangan tulisan tangan oleh salah satu Pihak. Dalam hal salah satu Pihak melaksanakan Perjanjian ini dengan tanda tangan elektronik, tanda tangan elektronik tersebut akan dianggap sebagai tanda tangan asli dari Pihak terkait. Para Pihak setuju bahwa tanda tangan elektronik memiliki efek hukum yang sama dengan tanda tangan tulisan tangan.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>2)</td>
            <td>Jika diminta dan disetujui oleh Para Pihak untuk secara fisik menandatangani Perjanjian setelahnya, Para Pihak setuju untuk memberikan tanda tangan pada Perjanjian ini dan Para Pihak selanjutnya setuju bahwa meskipun ada tanda tangan dengan tulisan tangan, Perjanjian ini akan dianggap telah dilaksanakan sejak tanggal Perjanjian ditandatangani dengan tanda tangan elektronik dan berlaku sejak tanggal efektif Perjanjian ini.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>3)</td>
            <td>Menyimpang dari ketentuan di atas, Perjanjian ini dapat dilakukan dengan menggunakan tanda tangan campuran, sedangkan satu Pihak melaksanakan dengan tanda tangan elektronik dan Pihak lainnya melaksanakan dengan tanda tangan tulisan tangan.</td>
        </tr>
        </table></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=15, $html, 0, 1, 0, true, '', true);
        // 
        $html = '<div style="text-align:center;"><b>PASAL 11<br/>PENYELESAIAN PERSELISIHAN</b></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=70, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:justify;"><table>
        <tr>
            <td width="4%">1)</td>
            <td width="96%">Hal-hal yang belum tercakup di dalam perjanjian ini, Para Pihak sepakat untuk tunduk dan mentaati pada Peraturan Perusahaan PT. ALITA PRAYA MITRA yang telah mendapat pengesahan dari Kepala Dinas Tenaga Kerja, Transmigrasi dan Energi Provinsi DKI Jakarta Nomor 1985/PP/L/XII/SS/2025.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>2)</td>
            <td>Segala perselisihan tanpa ada yang dikecualikan, yang timbul dari Perjanjian ini, dan/atau sebagai akibat dari pelaksanaanya, untuk pertama kalinya akan diselesaikan secara musyawarah.</td>
        </tr>
        </table></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=15, $html, 0, 1, 0, true, '', true);
        // page 5
        $pdf::AddPage();
        $pdf::SetAutoPageBreak(true, 0);
        if($header){
            $bgImagePath = public_path('assets/images/matta/bg_alita_a4_header_p2.png');
            $pdf::Image($bgImagePath, 0, 0, 210, 0, '', '', '', false, 300, '', false, false, 0);
            $bgImagePath = public_path('assets/images/matta/bg_alita_a4_footer.png');
            $pdf::Image($bgImagePath, 0, 275, 210, 0, '', '', '', false, 300, '', false, false, 0);
        }
        // 
        $x = $left;
        $y = $top;
        $html = '<div style="text-align:justify;"><table>
        <tr>
            <td>Pihak Pertama dan Pihak Kedua dalam melaksanakan Perjanjian ini terikat oleh Peraturan Perusahaan, Keputusan Perusahaan dan peraturan perundang-undangan yang berlaku. Demikian Perjanjian ini dibuat dalam rangkap 2 (dua) dan bermeterai cukup, Pihak Pertama dan Pihak Kedua masing-masing memperoleh 1 (satu) rangkap asli yang kesemuanya mempunyai kekuatan hukum dan pembuktian yang sama.</td>
        </tr>
        </table></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y, $html, 0, 1, 0, true, '', true);
        // 
        $html = '<div style="text-align:center;"><b>PT. ALITA PRAYA MITRA</b><br/>Jakarta, '.Carbon::parse($trx->tanggal_kontrak)->translatedFormat('j F Y').'</div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=40, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:center;"><table>
        <tr>
            <td width="50%">Pihak Pertama<br/>a.n Direktur<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><b><u>'.strtoupper($hco->nama).'</u></b><br/>'.$hco->_jabatan->jabatan.'</td>
            <td width="50%">Pihak Kedua<br/><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><b><u>'.strtoupper($karyawan->nama).'</u></b></td>
        </tr>
        </table></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=15, $html, 0, 1, 0, true, '', true);
        // page 6
        $pdf::AddPage();
        $pdf::SetAutoPageBreak(true, 0);
        if($header){
            $bgImagePath = public_path('assets/images/matta/bg_alita_a4_header_p2.png');
            $pdf::Image($bgImagePath, 0, 0, 210, 0, '', '', '', false, 300, '', false, false, 0);
            $bgImagePath = public_path('assets/images/matta/bg_alita_a4_footer.png');
            $pdf::Image($bgImagePath, 0, 275, 210, 0, '', '', '', false, 300, '', false, false, 0);
        }
        // 
        $x = $left;
        $y = $top;
        $html = '<div style="text-align:center;"><b>LAMPIRAN PERJANJIAN KERJA KARYAWAN<br/>No.'.$no_kontrak.'</b></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:justify;"><table>
        <tr>
            <td width="3%">1.</td>
            <td width="97%">Besarnya Upah yang diterima Pihak Kedua adalah sebesar Rp. '.number_format($payroll, 0, ',', '.').' ('.$nominal.' Rupiah) per bulan dengan rincian:</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><table>
                <tr>
                    <td width="35%">Gaji Pokok</td>
                    <td width="5%">: Rp</td>
                    <td width="12%" style="text-align:right;">'.number_format($trx->gapok, 0, ',', '.').'</td>
                </tr>
                <tr>
                    <td>Tunjangan Makan</td>
                    <td>: Rp</td>
                    <td style="text-align:right;">'.number_format($trx->t_makan, 0, ',', '.').'</td>
                </tr>
                <tr>
                    <td>Tunjangan Transport</td>
                    <td>: Rp</td>
                    <td style="text-align:right;">'.number_format($trx->t_transportasi, 0, ',', '.').'</td>
                </tr>
                <tr>
                    <td>Tunjangan Jabatan</td>
                    <td>: Rp</td>
                    <td style="text-align:right;">'.number_format($trx->t_jabatan, 0, ',', '.').'</td>
                </tr>
                <tr>
                    <td>Tunjangan Telekomunikasi</td>
                    <td>: Rp</td>
                    <td style="text-align:right;">'.number_format($trx->t_telekomunikasi, 0, ',', '.').'</td>
                </tr>
                <tr>
                    <td>Tunjangan Lain-lain</td>
                    <td>: Rp</td>
                    <td style="text-align:right;">'.number_format($trx->t_lain, 0, ',', '.').'</td>
                </tr>
            </table></td>
        </tr>
        <tr>
            <td>2.</td>
            <td>Upah Lembur diberikan secara khusus untuk Level Non-Staff dan Staff sesuai ketentuan dan prosedur yang berlaku.</td>
        </tr>
        <tr>
            <td>3.</td>
            <td>Tunjangan lain yang diberikan Pihak Pertama kepada Pihak Kedua antara lain:</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><table>
                <tr>
                    <td width="3%">a.</td>
                    <td width="97%">BPJS Kesehatan sesuai dengan Pasal 39 Peraturan Perusahaan PT. ALITA PRAYA MITRA.</td>
                </tr>
                <tr>
                    <td>b.</td>
                    <td>BPJS Ketenagakerjaan sesuai Pasal 39 Peraturan Perusahaan PT. ALITA PRAYA MITRA. </td>
                </tr>
                <tr>
                    <td>c.</td>
                    <td>Penggantian Biaya Kesehatan (Medical Reimbursement) dengan ketentuan sebagai berikut:</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><table>
                        <tr>
                            <td width="1%">&nbsp;</td>
                            <td width="4%">1.</td>
                            <td width="95%">Melampirkan copy resep, bukti pembelian/kuitansi dan bukti pembayaran, termasuk print out EDC (jika pembayaran melalui EDC), disertai diagnosa dokter.</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>2.</td>
                            <td>Bukti pembelian/kuitansi Dokter/Klinik/Rumah Sakit/Apotek asli dengan cap/stample baik Rawat Jalan maupun Rawat Inap dan dilengkapi meterai untuk kuitansi dengan nilai minimum Rp5.000.000,- (Lima Juta Rupiah).</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>3.</td>
                            <td>Biaya administrasi klinik/rumah sakit/aplikasi berobat online, ongkos kirim dan kantong belanja dapat diberikan jika tertera dalam satu transaksi/kuitansi pembelian yang sama dengan obat-obatan.</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>4.</td>
                            <td>Masa berlaku kuitansi Dokter/Klinik/Rumah Sakit/Apotek adalah maksimal 3(tiga) bulan dari tanggal yang tertera dalam kuitansi.</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>5.</td>
                            <td>Pemeriksaan Laboratorium, USG, MRI dan sejenisnya harus disertai dengan surat pengantar dari dokter dan keterangan diagnosa dari dokter.</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>6.</td>
                            <td>Tidak termasuk penggantian General Check-Up, perawatan estetika, pemeriksaan hormonal/kesuburan, obat-obatan ringan yang dijual bebas, pengobatan/obat-obatan herbal.</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>7.</td>
                            <td>Tidak termasuk program kehamilan, pemeriksaan kehamilan dan kelahiran.</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>8.</td>
                            <td>Tidak termasuk pembelian susu, obat-obatan herbal untuk program kehamilan, pemeriksaan kehamilan dan kelahiran.</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>9.</td>
                            <td>Tertanggung adalah termasuk keluarga inti bagi yang sudah menikah, antara lain:<br/>- istri dan anak bagi karyawan<br/>- anak (tidak termasuk suami) bagi karyawati</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>10.</td>
                            <td>Penggantian maksimum sebesar 1 (satu) bulan Upah per Tahun dan berlaku proporsional sesuai masa perjanjian kerja.</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>11.</td>
                            <td>Pengajuan Biaya Kesehatan (Medical Reimbursement) sesuai prosedur yang berlaku melalui aplikasi yang ditentukan oleh Pihak Pertama.</td>
                        </tr>
                    </table></td>
                </tr>
            </table></td>
        </tr>
        <tr>
            <td>4.</td>
            <td>PPh 21 akan ditanggung dan dibayarkan oleh Pihak Pertama.</td>
        </tr>
        </table></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=15, $html, 0, 1, 0, true, '', true);
        // 
        $html = '<div style="text-align:center;"><b>PT. ALITA PRAYA MITRA</b><br/>Jakarta, '.Carbon::parse($trx->tanggal_kontrak)->translatedFormat('j F Y').'</div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=180, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:center;"><table>
        <tr>
            <td width="50%">Pihak Pertama<br/>a.n Direktur<p>&nbsp;</p><p>&nbsp;</p><b><u>'.strtoupper($hco->nama).'</u></b><br/>'.$hco->_jabatan->jabatan.'</td>
            <td width="50%">Pihak Kedua<br/><p>&nbsp;</p><p>&nbsp;</p><b><u>'.strtoupper($karyawan->nama).'</u></b></td>
        </tr>
        </table></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=10, $html, 0, 1, 0, true, '', true);
        // 
        $pdf::Output(public_path($filename), 'I');
        return response()->download(public_path($filename));
        
    }

    public function tanggalKeTeks(string $tanggal)
    {
        Carbon::setLocale('id');
        $date = Carbon::parse($tanggal);
        $hari = $date->translatedFormat('l');
        $bulan = $date->translatedFormat('F');
        $formatter = new \NumberFormatter('id', \NumberFormatter::SPELLOUT);
        $tanggalKata = $formatter->format($date->day);
        $tahunKata = $formatter->format($date->year);
        return Str::title($hari.' '.$tanggalKata.' '.$bulan.' '.$tahunKata);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $no = 0;
        $trx = TrxPenilaian::orderBy('id', 'desc')->get();
        if(Gate::allows('hc_nkp')){
            $trx = TrxPenilaian::whereIn('nik_karyawan',$arr_nik_nkp)->orderBy('id', 'desc')->get();
        }
        if(auth()->user()->userrole == 'nhc'){
            // nik_karyawan
            $where = ['nik_karyawan' => auth()->user()->nik];
            $trx = TrxPenilaian::where($where)->orderBy('id', 'desc')->get();
            // nik_penilai_1
            $where = ['nik_penilai_1' => auth()->user()->nik];
            $trx = $trx->concat(TrxPenilaian::where($where)->orderBy('id', 'desc')->get());
            // nik_penilai_2
            $where = ['nik_penilai_2' => auth()->user()->nik];
            $trx = $trx->concat(TrxPenilaian::where($where)->orderBy('id', 'desc')->get());
            // nik_penilai_3
            $where = ['nik_penilai_3' => auth()->user()->nik];
            $trx = $trx->concat(TrxPenilaian::where($where)->orderBy('id', 'desc')->get());
        }
        // foreach ($trx as $value) {
        //     $trxM = TrxPenilaian::find( $value->id );
        //     $trxM->karyawan_property = $this->getNikProperty($value->nik_karyawan);
        //     $trxM->penilai1_property = $this->getNikProperty($value->nik_penilai_1);
        //     $trxM->penilai2_property = $this->getNikProperty($value->nik_penilai_2);
        //     $trxM->penilai3_property = $this->getNikProperty($value->nik_penilai_3);
        //     $trxM->update();
        // }
        // filter parameter
        function forgetById($collection,$id){
            foreach($collection as $key => $item){
                if($item->id == $id){
                    $collection->forget($key);
                    break;
                }
            }
            return $collection;
        }
        // 
        $arr_id_status = array();
        $arr_is_perpanjang = array();
        $arr_id_status_lengkap = array();
        $arr_perusahaan = Perusahaan::select('id','nama')->whereNotIn('id', [0,3])->orderBy('nama', 'asc')->get();
        $perusahaan = $arr_perusahaan;
        $level = Level::select('id','level')->whereNotIn('id', [0])->orderBy('id', 'asc')->get();
        $jabatan = Jabatan::select('id','jabatan')->whereNotIn('id', [0])->orderBy('jabatan', 'asc')->get();
        // foreach ($trx as $data){
        //     array_push($arr_id_status, $data->id_status);
        //     array_push($arr_is_perpanjang, $data->is_perpanjang);
        // }
        // $id_status = MtrPenilaianStatus::whereIn('id', $arr_id_status)->orderBy('id', 'asc')->get();
        // $is_perpanjang = MtrPenilaianPerpanjang::whereIn('id', $arr_is_perpanjang)->orderBy('id', 'asc')->get();
        $id_status = MtrPenilaianStatus::orderBy('id', 'asc')->get();
        $is_perpanjang = MtrPenilaianPerpanjang::orderBy('id', 'asc')->get();
        $id_status_lengkap = [23];
        // start dibatasi 30 hari terakhir dari created_at
        $trx = $trx->filter(function ($item) {
            return Carbon::parse($item->created_at)->gte(Carbon::now()->subDays(30));
        });
        $tanggal_mulai = '';
        $tanggal_akhir = '';
        // end
        // start filter
        if($request->filter == true){
            $_id_status = $request->input('id_status', []);
            $_is_perpanjang = $request->input('is_perpanjang', []);
            $_id_status_lengkap = $request->input('id_status_lengkap', []);
            $_perusahaan = $request->input('perusahaan', []);
            $tanggal_mulai = $request->tanggal_mulai;
            $tanggal_akhir = $request->tanggal_akhir;
            if(count($_id_status) > 0){
                $arr_id_status = $_id_status;
            }
            else{
                $arr_id_status = array();
            }
            if(count($_is_perpanjang) > 0){
                $arr_is_perpanjang = $_is_perpanjang;
            }
            else{
                $arr_is_perpanjang = array();
            }
            if(count($_id_status_lengkap) > 0){
                $arr_id_status_lengkap = $_id_status_lengkap;
            }
            else{
                $arr_id_status_lengkap = array();
            }
            if(count($_perusahaan) > 0){
                $arr_perusahaan = $_perusahaan;
            }
            else{
                $arr_perusahaan = array();
            }
            $nik_karyawan = Karyawan::select('nik')->whereIn('kode_perusahaan', $arr_perusahaan)->get()->toArray();
            $trx = TrxPenilaian::whereIn('nik_karyawan', $nik_karyawan)->whereIn('id_status', $arr_id_status)->whereIn('is_perpanjang', $arr_is_perpanjang)->whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->orderBy('id', 'desc')->get();
            if(Gate::allows('hc_nkp')){
                $trx = TrxPenilaian::whereIn('nik_karyawan',$arr_nik_nkp)->whereIn('nik_karyawan', $nik_karyawan)->whereIn('id_status', $arr_id_status)->whereIn('is_perpanjang', $arr_is_perpanjang)->whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->orderBy('id', 'desc')->get();
            }
            if(count($_id_status_lengkap) > 0){
                $trx = TrxPenilaian::whereIn('nik_karyawan', $nik_karyawan)->whereIn('is_perpanjang', $arr_is_perpanjang)->whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->orderBy('id', 'desc')->get();
                if(Gate::allows('hc_nkp')){
                    $trx = TrxPenilaian::whereIn('nik_karyawan',$arr_nik_nkp)->whereIn('nik_karyawan', $nik_karyawan)->whereIn('is_perpanjang', $arr_is_perpanjang)->whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->orderBy('id', 'desc')->get();
                }
                foreach ($trx as $_trx){
                    $data = DB::select("SELECT * FROM trx_tbl_penilaian_log t
                    WHERE t.id_trx IN (
                        SELECT id_trx
                        FROM trx_tbl_penilaian_log
                        WHERE id_status IN (6,7)
                        GROUP BY id_trx
                        HAVING 
                            SUM(id_status = 6) > 0
                            AND SUM(id_status = 7) > 0
                    ) AND id_trx=".$_trx->id);
                    if(count($data)>0){
                        // 
                    }else{
                        $trx = forgetById($trx, $_trx->id);
                    }
                }
            }
            if(auth()->user()->userrole == 'nhc'){
                // nik_karyawan
                $where = ['nik_karyawan' => auth()->user()->nik];
                $trx = TrxPenilaian::whereIn('nik_karyawan', $nik_karyawan)->whereIn('id_status', $arr_id_status)->whereIn('is_perpanjang', $arr_is_perpanjang)->where($where)->whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->orderBy('id', 'desc')->get();
                // nik_penilai_1
                $where = ['nik_penilai_1' => auth()->user()->nik];
                $trx = $trx->concat(TrxPenilaian::whereIn('nik_karyawan', $nik_karyawan)->whereIn('id_status', $arr_id_status)->whereIn('is_perpanjang', $arr_is_perpanjang)->where($where)->whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->orderBy('id', 'desc')->get());
                // nik_penilai_2
                $where = ['nik_penilai_2' => auth()->user()->nik];
                $trx = $trx->concat(TrxPenilaian::whereIn('nik_karyawan', $nik_karyawan)->whereIn('id_status', $arr_id_status)->whereIn('is_perpanjang', $arr_is_perpanjang)->where($where)->whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->orderBy('id', 'desc')->get());
                // nik_penilai_3
                $where = ['nik_penilai_3' => auth()->user()->nik];
                $trx = $trx->concat(TrxPenilaian::whereIn('nik_karyawan', $nik_karyawan)->whereIn('id_status', $arr_id_status)->whereIn('is_perpanjang', $arr_is_perpanjang)->where($where)->whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->orderBy('id', 'desc')->get());
            }
        }else{
            $arr_id_status = array();
            $arr_is_perpanjang = array();
            $arr_id_status_lengkap = array();
            $arr_perusahaan = array();
        }
        // return response()->json(['id_status_lengkap' => $id_status_lengkap, 'arr_id_status_lengkap' => $arr_id_status_lengkap]);
        // end filter
        return view( 'formulir.penilaian.index', compact( 'trx', 'no', 'id_status', 'is_perpanjang', 'id_status_lengkap', 'perusahaan', 'level', 'jabatan', 'arr_id_status', 'arr_is_perpanjang', 'arr_id_status_lengkap', 'arr_perusahaan', 'tanggal_mulai', 'tanggal_akhir'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('hc');
        //passing parameter ke formulir page
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        $tipe = MtrPenilaianTipe::orderBy('tipe_penilaian', 'asc')->get();
        if(Gate::allows('hc_nkp')){
            $karyawan = Karyawan::select('nik', 'nama')->whereIn('nik',$arr_nik_nkp)->where($where)->orderBy('nama', 'asc')->get();
            $tipe = MtrPenilaianTipe::whereIn('id',[6])->orderBy('tipe_penilaian', 'asc')->get();
        }
        $status = 'create';
        return view( 'formulir.penilaian.insert', compact( 'karyawan', 'tipe', 'status' ) );
    }

    public function layer($id=0)
    {
        // ambil parameter karyawan dan semua layer
        $where = ['is_aktif' => 1, 'nik'=> $id];
        $layer = Karyawan::select('layer1', 'layer2', 'layer3')->where($where)->first();
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        return response()->json(['layer' => $layer, 'karyawan' => $karyawan]);
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
        $trx = New TrxPenilaian;
        $trx->nik_karyawan = $request->nik_karyawan;
        $trx->nik_penilai_1 = $request->penilai_1;
        $trx->karyawan_property = $this->getNikProperty($request->nik_karyawan);
        $trx->penilai1_property = $this->getNikProperty($request->penilai_1);
        if($request->has('penilai_2')){
            $trx->nik_penilai_2 = $request->penilai_2;
            $trx->penilai2_property = $this->getNikProperty($request->penilai_2);
        }
        if($request->has('penilai_3')){
            $trx->nik_penilai_3 = $request->penilai_3;
            $trx->penilai3_property = $this->getNikProperty($request->penilai_3);
        }
        $trx->id_tipe = $request->id_tipe;
        $trx->id_status = 1;
        // 6 = Penilaian Outsourcing
        if($trx->id_tipe == 6){
            $trx->id_status = 16;
        }
        $trx->save();
        $this->insertLog($trx->id, $trx->id_status, auth()->user()->nik);
        // simpan history perubahan
        $data = New TrxPenilaianLogHistory;
        $data->id_trx = $trx->id;
        $data->id_status = $trx->id_status;
        $data->nik_pic = auth()->user()->nik;
        $data->revised_by = auth()->user()->nik;
        $data->save();
        // 
        $array = json_decode($this->getPertanyaanPenilaian(), true);
        $id_level = json_decode($trx->karyawan_property, true)['id_level'];
        // 6 = Penilaian Outsourcing
        if($trx->id_tipe == 6){
            $array = json_decode($this->getPertanyaanPenilaianOutsourcing(0), true);
            // 3 = Supervisor (SPV)
            // untuk level spv keatas
            if($id_level > 2){
                $array = json_decode($this->getPertanyaanPenilaianOutsourcing(1), true);
            }
            foreach($array as $values){
                $this->insertPenilaianSkorOutsourcing($trx->id, $values);
            }
        }
        // selain Outsourcing
        else{
            foreach($array as $values) {
                // hanya level spv ke atas (>2) yang diberikan pertanyaan ini
                if($values['is_spv']==1){
                    if($id_level > 2){
                        $this->insertPenilaianSkor($trx->id,$values['id_nilai3'],$values['penilaian_1'],$values['penilaian_2'],$values['penilaian_2_ket'],$values['penilaian_3'],$values['is_spv']);
                    }
                }
                // semua level mendapatkan pertanyaan ini
                else{
                    $this->insertPenilaianSkor($trx->id,$values['id_nilai3'],$values['penilaian_1'],$values['penilaian_2'],$values['penilaian_2_ket'],$values['penilaian_3'],$values['is_spv']);
                }
            }
        }
        // parameter yang dipassing ke sendemail
        $karyawan = json_decode($trx->karyawan_property, true);
        $atasan = json_decode($trx->penilai1_property, true);
        $nik = $request->nik_karyawan;
        $pic = 'nik_karyawan';
        $id_status = 1;
        $mailData['title'] = 'Formulir Penilaian Karyawan';
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
            $mailData['to'] = $atasan['email'];
            $mailData['filename'] = 'emails.penilaian0_outsourcing';
            $nik = $request->penilai_1;
            $pic = 'nik_penilai_1';
            $id_status = 16;
        }
        $mailData['data'] = ['nama'=>$karyawan['nama'].'/'.$karyawan['nik'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('penilaianskor.show', [(new OpenDataController)->encryptWithKey($trx->id), 'nik' => (new OpenDataController)->encryptWithKey($nik), 'id_status' => (new OpenDataController)->encryptWithKey($id_status), 'pic' => (new OpenDataController)->encryptWithKey($pic)]), 'id_trx' => $trx->id ];
        $todolist = ['id_trx' => $trx->id, 'nik' => $nik, 'id_status' => $id_status, 'pic' => $pic];
        $this->insertToDoList($todolist, $mailData);
        $this->sendEmail($mailData);
        // 
        return redirect( 'penilaian' )->with( 'msg', 'Data is saved successfully.' );
    }

    public function sendEmail($mailData)
    {
        $ccEmails = explode(',', MtrUtility::find(11)->informasi);
        Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
    }

    public function insertLog($id_trx, $id_status, $nik_pic)
    {
        $data = New TrxPenilaianLog;
        $data->id_trx = $id_trx;
        $data->id_status = $id_status;
        $data->nik_pic = $nik_pic;
        $data->save();
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

    public function insertPenilaianSkor($id_trx,$id_nilai3,$penilaian_1,$penilaian_2,$penilaian_2_ket,$penilaian_3,$is_spv)
    {
        $data = New TrxPenilaianSkor;
        $data->id_trx = $id_trx;
        $data->id_nilai3 = $id_nilai3;
        $data->penilaian_1 = $penilaian_1;
        $data->penilaian_2 = $penilaian_2;
        $data->penilaian_2_ket = $penilaian_2_ket;
        $data->penilaian_3 = $penilaian_3;
        $data->is_spv = $is_spv;
        $data->save();
    }

    public function insertPenilaianSkorOutsourcing($id_trx, $array)
    {
        $data = New TrxPenilaianSkor;
        $data->id_trx = $id_trx;
        $data->id_nilai3 = $array['id'];
        $data->penilaian_1 = $array['obyek'];
        $data->save();
    }

    public function getNikProperty($nik)
    {
        $where = [ 'nik' => $nik ];
        $data = Karyawan::select('mtr_tbl_karyawan.nik','mtr_tbl_karyawan.nama','mtr_tbl_karyawan.email','mtr_tbl_perusahaan.nama as perusahaan','mtr_tbl_divisi.nama as divisi','mtr_tbl_departemen.nama as dep','mtr_tbl_sub_departemen.nama as sub_dep','mtr_tbl_level.level as level','mtr_tbl_jabatan.jabatan as jabatan','mtr_tbl_level.id as id_level','mtr_tbl_karyawan.tanggal_bergabung','mtr_tbl_karyawan.tanggal_berakhir','mtr_tbl_karyawan.tanggal_karyawan_tetap')
        ->leftJoin('mtr_tbl_perusahaan', 'mtr_tbl_perusahaan.id', '=', 'mtr_tbl_karyawan.kode_perusahaan')
        ->leftJoin('mtr_tbl_divisi', 'mtr_tbl_divisi.id', '=', 'mtr_tbl_karyawan.kode_divisi')
        ->leftJoin('mtr_tbl_departemen', 'mtr_tbl_departemen.id', '=', 'mtr_tbl_karyawan.kode_departemen')
        ->leftJoin('mtr_tbl_sub_departemen', 'mtr_tbl_sub_departemen.id', '=', 'mtr_tbl_karyawan.kode_sub_departemen')
        ->leftJoin('mtr_tbl_level', 'mtr_tbl_level.id', '=', 'mtr_tbl_karyawan.kode_level')
        ->leftJoin('mtr_tbl_jabatan', 'mtr_tbl_jabatan.id', '=', 'mtr_tbl_karyawan.kode_jabatan')
        ->where($where)->first();
        return json_encode( $data );
    }

    public function getPertanyaanPenilaian()
    {
        $data = MtrPenilaian3::select('mtr_tbl_penilaian_3.id as id_nilai3','mtr_tbl_penilaian_3.obyek as penilaian_3','mtr_tbl_penilaian_2.obyek as penilaian_2','mtr_tbl_penilaian_2.keterangan as penilaian_2_ket','mtr_tbl_penilaian_1.obyek as penilaian_1','mtr_tbl_penilaian_1.is_spv as is_spv')
        ->leftJoin('mtr_tbl_penilaian_2', 'mtr_tbl_penilaian_3.id_nilai2', '=', 'mtr_tbl_penilaian_2.id')
        ->leftJoin('mtr_tbl_penilaian_1', 'mtr_tbl_penilaian_2.id_nilai1', '=', 'mtr_tbl_penilaian_1.id')
        ->orderBy('mtr_tbl_penilaian_3.id', 'asc')->get();
        return json_encode( $data );
    }

    public function getPertanyaanPenilaianOutsourcing($is_spv)
    {
        $data = MtrPenilaianOutsource::orderBy('id', 'asc')->get();
        if($is_spv==0){
            $data = MtrPenilaianOutsource::where(['is_spv'=>0])->orderBy('id', 'asc')->get();
        }
        return json_encode( $data );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //
        $id = (new OpenDataController)->decryptWithKey($id);
        $penilaian = TrxPenilaian::find($id);
        // ambil property para pihak
        $karyawan = json_decode($penilaian->karyawan_property, true);
        $penilai1 = json_decode($penilaian->penilai1_property, true);
        $penilai2 = json_decode($penilaian->penilai2_property, true);
        $penilai3 = json_decode($penilaian->penilai3_property, true);
        // 
        $view = $request->view;
        $view = (new OpenDataController)->decryptWithKey($view);
        switch ($view) {
            case 'detail':
                // 
                $where = [ 'id_trx' => $id ];
                $history = TrxPenilaianLog::where($where)->orderBy('created_at','desc')->get();
                // 
                $txt_perpanjang1 = '';
                $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai1['nik'] ];
                $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
                if(!empty($trx)){
                    if($trx->is_perpanjang){
                        $txt_perpanjang1 = 'Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                    }else{
                        $txt_perpanjang1 = 'Status Perpanjangan: Tidak Diperpanjang';
                    }
                }
                // 
                $txt_perpanjang2 = '';
                if(!empty($penilaian->nik_penilai_2)){
                    $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai2['nik'] ];
                    $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
                    if(!empty($trx)){
                        if($trx->is_perpanjang){
                            $txt_perpanjang2 = 'Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                        }else{
                            $txt_perpanjang2 = 'Status Perpanjangan: Tidak Diperpanjang';
                        }
                    }
                }
                // 
                $txt_perpanjang3 = '';
                if(!empty($penilaian->nik_penilai_3)){
                    $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai3['nik'] ];
                    $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
                    if(!empty($trx)){
                        if($trx->is_perpanjang){
                            $txt_perpanjang3 = 'Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                        }else{
                            $txt_perpanjang3 = 'Status Perpanjangan: Tidak Diperpanjang';
                        }
                    }
                }
                return view( 'formulir.penilaian.view', compact( 'history', 'penilaian', 'karyawan', 'penilai1', 'penilai2', 'penilai3', 'txt_perpanjang1', 'txt_perpanjang2', 'txt_perpanjang3' ) );
                break;
            case 'result':
                // ambil angka rata-rata berdasarkan penilaian_2
                $where = ['id_trx' => $id];
                $trxSkor2 = DB::table('trx_tbl_penilaian_skor')
                ->selectRaw('penilaian_2, CAST(AVG(skor_karyawan) AS DECIMAL(6,2)) AS avg_kar, CAST(AVG(skor_penilai_1) AS DECIMAL(6,2)) AS avg_nilai1')
                ->where($where)->groupBy('penilaian_2')
                ->orderBy('id_nilai3', 'asc')->get();
                // 
                $angka_skor = '';
                $no = 1;
                foreach ($trxSkor2 as $value2) {
                    $strNilai2 = $value2->penilaian_2;
                    $angka_skor .= '<tr class="bodycopy"><td><strong>'.$strNilai2.'</strong></td>
                    <td><strong><div align="center">'.$value2->avg_kar.'</div></strong></td>
                    <td><strong><div align="center">'.$value2->avg_nilai1.'</div></strong></td></tr>';
                    // 
                    $where = ['id_trx' => $id];
                    $trxSkor3 = TrxPenilaianSkor::select('id_nilai3','penilaian_3','skor_karyawan','skor_penilai_1')
                    ->where('penilaian_2', '=', $strNilai2)->where($where)->orderBy('id_nilai3', 'asc')->get();
                    foreach ($trxSkor3 as $value3) {
                        $strNilai3 = $value3['penilaian_3'];
                        $angka_skor .= '<tr class="bodycopy"><td>'.$no.'. '.$strNilai3.'</td><td><div align="center">'.$value3['skor_karyawan'].'</div></td><td><div align="center">'.$value3['skor_penilai_1'].'</div></td></tr>';
                        $no++;
                    }
                }
                // ambil angka rata-rata semua
                $where = ['id_trx' => $id];
                $trxSkor2 = DB::table('trx_tbl_penilaian_skor')
                ->selectRaw('CAST(AVG(skor_karyawan) AS DECIMAL(6,2)) AS avg_kar, CAST(AVG(skor_penilai_1) AS DECIMAL(6,2)) AS avg_nilai1')
                ->where($where)->first();
                $trxSkorTotal = DB::table('trx_tbl_penilaian_skor')->selectRaw('SUM(skor_karyawan) AS skor_karyawan, SUM(skor_penilai_1) AS skor_penilai_1')->where($where)->first();
                // 
                $angka_rata = '<tr class="bodycopy"><td><strong><div align="right">RATA-RATA</div></strong></td>
                <td><strong><div align="center">'.$trxSkor2->avg_kar.'</div></strong></td>
                <td><strong><div align="center">'.$trxSkor2->avg_nilai1.'</div></strong></td>
                </tr>';
                $angka_total = '<tr class="bodycopy"><td><strong><div align="right">NILAI TOTAL</div></strong></td>
                <td><strong><div align="center">'.$trxSkorTotal->skor_karyawan.'</div></strong></td>
                <td><strong><div align="center">'.$trxSkorTotal->skor_penilai_1.'</div></strong></td>
                </tr>';
                // ambil isian kesimpulan dan rekomendasi
                $txt_perpanjang = '';
                $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai1['nik'] ];
                $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
                if(!empty($trx)){
                    if($trx->is_perpanjang){
                        $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                    }else{
                        $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
                    }
                }
                $isian_kes_rek = '<tr><td class="innerpadding bodycopy"><b>Kesimpulan dan Rekomendasi oleh '.$penilai1['nama'].':<br/>'.$penilaian->kes_rek_1.$txt_perpanjang;
                if(!empty($penilaian->nik_penilai_2)){
                    $txt_perpanjang = '';
                    $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai2['nik'] ];
                    $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
                    if(!empty($trx)){
                        if($trx->is_perpanjang){
                            $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                        }else{
                            $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
                        }
                    }
                    $isian_kes_rek .= '<br/><br/>Kesimpulan dan Rekomendasi oleh '.$penilai2['nama'].':<br/>'.$penilaian->kes_rek_2.$txt_perpanjang;
                }
                if(!empty($penilaian->nik_penilai_3)){
                    $txt_perpanjang = '';
                    $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai3['nik'] ];
                    $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
                    if(!empty($trx)){
                        if($trx->is_perpanjang){
                            $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                        }else{
                            $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
                        }
                    }
                    $isian_kes_rek .= '<br/><br/>Kesimpulan dan Rekomendasi oleh '.$penilai3['nama'].':<br/>'.$penilaian->kes_rek_3.$txt_perpanjang;
                }
                $isian_kes_rek .= '</b></td></tr>';
                // nilai outsourcing
                if($penilaian->id_tipe == 6){
                    $where = [ 'id_trx' => $id ];
                    $angka_skor = TrxPenilaianSkor::where($where)->orderBy('id_nilai3', 'asc')->get();
                    $angka_rata = TrxPenilaianSkor::where($where)->sum('skor_penilai_1');
                }
                // 
                return view( 'formulir.penilaian.result', compact( 'penilaian', 'karyawan', 'penilai1', 'angka_skor', 'angka_rata', 'isian_kes_rek', 'angka_total' ) );
                break;
            case 'pdf':
                // ambil angka rata-rata berdasarkan penilaian_2
                $where = ['id_trx' => $id];
                $trxSkor2 = DB::table('trx_tbl_penilaian_skor')
                ->selectRaw('penilaian_2, CAST(AVG(skor_karyawan) AS DECIMAL(6,2)) AS avg_kar, CAST(AVG(skor_penilai_1) AS DECIMAL(6,2)) AS avg_nilai1')
                ->where($where)->groupBy('penilaian_2')
                ->orderBy('id_nilai3', 'asc')->get();
                // 
                $angka_skor = '';
                $no = 1;
                foreach ($trxSkor2 as $value2) {
                    $strNilai2 = $value2->penilaian_2;
                    $angka_skor .= '<tr><td><strong>'.$strNilai2.'</strong></td>
                    <td align="center"><strong>'.$value2->avg_kar.'</strong></td>
                    <td align="center"><strong>'.$value2->avg_nilai1.'</strong></td></tr>';
                    // 
                    $where = ['id_trx' => $id];
                    $trxSkor3 = TrxPenilaianSkor::select('id_nilai3','penilaian_3','skor_karyawan','skor_penilai_1')
                    ->where('penilaian_2', '=', $strNilai2)->where($where)->orderBy('id_nilai3', 'asc')->get();
                    foreach ($trxSkor3 as $value3) {
                        $strNilai3 = $value3['penilaian_3'];
                        $angka_skor .= '<tr><td>'.$no.'. '.$strNilai3.'</td>
                        <td><div align="center">'.$value3['skor_karyawan'].'</div></td>
                        <td><div align="center">'.$value3['skor_penilai_1'].'</div></td></tr>';
                        $no++;
                    }
                }
                // ambil angka rata-rata semua
                $where = ['id_trx' => $id];
                $trxSkor2 = DB::table('trx_tbl_penilaian_skor')
                ->selectRaw('CAST(AVG(skor_karyawan) AS DECIMAL(6,2)) AS avg_kar, CAST(AVG(skor_penilai_1) AS DECIMAL(6,2)) AS avg_nilai1')
                ->where($where)->first();
                $trxSkorTotal = DB::table('trx_tbl_penilaian_skor')->selectRaw('SUM(skor_karyawan) AS skor_karyawan, SUM(skor_penilai_1) AS skor_penilai_1')->where($where)->first();
                // 
                $angka_rata = '<tr><td width="70%" align="right"><strong>RATA-RATA</strong></td>
                <td width="15%" align="center"><strong>'.$trxSkor2->avg_kar.'</strong></td>
                <td width="15%" align="center"><strong>'.$trxSkor2->avg_nilai1.'</strong></td></tr>';
                $angka_total = '<tr><td width="70%" align="right"><strong>NILAI TOTAL</strong></td>
                <td width="15%" align="center"><strong>'.$trxSkorTotal->skor_karyawan.'</strong></td>
                <td width="15%" align="center"><strong>'.$trxSkorTotal->skor_penilai_1.'</strong></td></tr>';
                // ambil property para penilai
                $para_penilai = '<tr><td width="30%">Nama Penilai 1<br/>NIP<br/>Jabatan<br/>Direktorat / Divisi / Departemen</td>
                <td width="70%">: '.$penilai1['nama'].'<br/>: '.$penilai1['nik'].'<br/>: '.$penilai1['jabatan'].'<br/>: '.$penilai1['divisi'].' / '.$penilai1['dep'].' / '.$penilai1['sub_dep'].'</td></tr>';
                // ambil isian kesimpulan dan rekomendasi
                $txt_perpanjang = '';
                $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai1['nik'] ];
                $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
                // return response()->json( [ 'trx' => $trx ], 200 );
                if(!empty($trx)){
                    if($trx->is_perpanjang){
                        $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                    }else{
                        $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
                    }
                }
                $isian_kes_rek = '<table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr><td>Kesimpulan dan Rekomendasi Penilai 1:<br/>'.$penilaian->kes_rek_1.$txt_perpanjang.'</td></tr>
                    </table><hr/>';
                // ambil timestamp terakhir para pihak
                $karyawan_timestamp = '';
                $penilai1_timestamp = '';
                $penilai2_timestamp = '';
                $penilai3_timestamp = '';
                $where = [ 'id_trx' => $id, 'nik_pic'=> $karyawan['nik'] ];
                $trx = TrxPenilaianLog::where($where)->orderBy('created_at','desc')->first();
                if(!empty($trx)){
                    $karyawan_timestamp = '<img src="'.public_path("assets/images/matta/assessment_1.png").'" width="85" height="85" /><br/>
                    '.$karyawan['nama'].'<br/>'.$karyawan['jabatan'].'<br/>'.$trx->created_at;
                }
                // 
                $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai1['nik'] ];
                $trx = TrxPenilaianLog::where($where)->orderBy('created_at','desc')->first();
                if(!empty($trx)){
                    $penilai1_timestamp = '<img src="'.public_path("assets/images/matta/assessment_1.png").'" width="85" height="85" /><br/>
                    '.$penilai1['nama'].'<br/>'.$penilai1['jabatan'].'<br/>'.$trx->created_at;
                }
                // ambil timestamp semua para pihak
                $timestamp = '<td>Karyawan:<br/>'.$karyawan_timestamp.'</td>';
                if($penilaian->id_tipe == 6){
                    $timestamp = '';
                }
                // 
                $timestamp .= '<td>Penilai 1:<br/>'.$penilai1_timestamp.'</td>';
                // 
                if(!empty($penilaian->nik_penilai_2)){
                    $para_penilai .= '<tr><td>Nama Penilai 2<br/>NIP<br/>Jabatan<br/>Direktorat / Divisi / Departemen</td>
                    <td>: '.$penilai2['nama'].'<br/>: '.$penilai2['nik'].'<br/>: '.$penilai2['jabatan'].'<br/>: '.$penilai2['divisi'].' / '.$penilai2['dep'].' / '.$penilai2['sub_dep'].'</td></tr>';
                    // ambil isian kesimpulan dan rekomendasi
                    $txt_perpanjang = '';
                    $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai2['nik'] ];
                    $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
                    if(!empty($trx)){
                        if($trx->is_perpanjang){
                            $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                        }else{
                            $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
                        }
                    }
                    $isian_kes_rek .= '<table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr><td>Kesimpulan dan Rekomendasi Penilai 2:<br/>'.$penilaian->kes_rek_2.$txt_perpanjang.'</td></tr>
                    </table><hr/>';
                    // 
                    $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai2['nik'] ];
                    $trx = TrxPenilaianLog::where($where)->orderBy('created_at','desc')->first();
                    if(!empty($trx)){
                        $penilai2_timestamp = '<img src="'.public_path("assets/images/matta/assessment_1.png").'" width="85" height="85" /><br/>
                        '.$penilai2['nama'].'<br/>
                        '.$penilai2['jabatan'].'<br/>'.$trx->created_at;
                    }
                    // 
                    $timestamp .= '<td>Penilai 2:<br/>
                    '.$penilai2_timestamp.'</td>';
                }
                if(!empty($penilaian->nik_penilai_3)){
                    $para_penilai .= '<tr><td>Nama Penilai 3<br/>NIP<br/>Jabatan<br/>Direktorat / Divisi / Departemen</td>
                    <td>: '.$penilai3['nama'].'<br/>: '.$penilai3['nik'].'<br/>: '.$penilai3['jabatan'].'<br/>: '.$penilai3['divisi'].' / '.$penilai3['dep'].' / '.$penilai3['sub_dep'].'</td></tr>';
                    // ambil isian kesimpulan dan rekomendasi
                    $txt_perpanjang = '';
                    $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai3['nik'] ];
                    $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
                    if(!empty($trx)){
                        if($trx->is_perpanjang){
                            $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                        }else{
                            $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
                        }
                    }
                    $isian_kes_rek .= '<table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr><td>Kesimpulan dan Rekomendasi Penilai 3:<br/>'.$penilaian->kes_rek_3.$txt_perpanjang.'</td></tr>
                    </table><hr/>';
                    // 
                    $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai3['nik'] ];
                    $trx = TrxPenilaianLog::where($where)->orderBy('created_at','desc')->first();
                    if(!empty($trx)){
                        $penilai3_timestamp = '<img src="'.public_path("assets/images/matta/assessment_1.png").'" width="85" height="85" /><br/>
                        '.$penilai3['nama'].'<br/>
                        '.$penilai3['jabatan'].'<br/>'.$trx->created_at;
                    }
                    // 
                    $timestamp .= '<td>Penilai 3:<br/>
                    '.$penilai3_timestamp.'</td>';
                }
                if($penilaian->id_status == 4 || $penilaian->id_status == 8){
                    $where = [ 'id_trx' => $id ];
                    $trx = TrxPenilaianLog::where($where)->orderBy('created_at','desc')->first();
                    $where = [ 'nik' => $trx->nik_pic ];
                    $hco_pic = Karyawan::where($where)->first();
                    if(!empty($trx)){
                        $hco_timestamp = '<img src="'.public_path("assets/images/matta/assessment_1.png").'" width="85" height="85" /><br/>
                        '.$hco_pic->nama.'<br/>
                        '.$hco_pic->_jabatan->jabatan.'<br/>'.$trx->created_at;
                    }
                    $timestamp .= '<td>Mengetahui:<br/>
                    '.$hco_timestamp.'</td>';
                }
                // ambil isian kesimpulan dan rekomendasi
                $isian_kes_rek .= '<table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td>Kesimpulan dan Rekomendasi '.MtrUtility::find(51)->informasi.':<br/>'.$penilaian->kes_rek_final.'</td></tr></table>';
                // nilai outsourcing
                if($penilaian->id_tipe == 6){
                    $where = [ 'id_trx' => $id ];
                    $angka_skor = TrxPenilaianSkor::where($where)->orderBy('id_nilai3', 'asc')->get();
                    $angka_rata = TrxPenilaianSkor::where($where)->sum('skor_penilai_1');
                }
                // print to pdf
                $waktu = date('YmdHis');
                $filename = $waktu.'_formulir_penilaian.pdf';
                if($penilaian->is_perpanjang == 1){
                    $_perpanjang = $penilaian->_perpanjang->informasi.' '.$penilaian->bulan.' bulan';
                }else{
                    $_perpanjang = $penilaian->_perpanjang->informasi;
                }
                $data = ['id_data'=>$penilaian->id, 'id_tipe'=>$penilaian->id_tipe, 'tipe'=>$penilaian->_tipe->tipe_penilaian, 'perpanjang'=>$_perpanjang ];
                $view = \View::make( 'formulir.penilaian.pdf', compact( 'data', 'karyawan', 'penilai1', 'para_penilai', 'angka_skor', 'angka_rata', 'isian_kes_rek', 'timestamp', 'angka_total' ) );
                $html = $view->render();
                $pdf = new TCPDF;
                $pdf::SetTitle('Formulir Penilaian Karyawan');
                $pdf::SetFont('times', '', 10, '', true);
                $pdf::AddPage();
                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output(public_path($filename), 'I');
                return response()->download(public_path($filename));
                // 
                break;
            default:
            //   return response()->json( [ 'success' => false, 'message' => 'No Data is viewed', 'data' => [] ], 200 );
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
        $this->authorize('hc');
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $id = (new OpenDataController)->decryptWithKey($id);
        // dd($id);
        $where = [ 'id_trx' => $id ];
        $history = TrxPenilaianLog::where($where)->orderBy('created_at','desc')->get();
        $penilaian = TrxPenilaian::find($id);
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        if(Gate::allows('hc_nkp')){
            $karyawan = Karyawan::select('nik', 'nama')->whereIn('nik',$arr_nik_nkp)->where($where)->orderBy('nama', 'asc')->get();
        }
        $_karyawan = json_decode($penilaian->karyawan_property, true);
        $penilai1 = json_decode($penilaian->penilai1_property, true);
        $penilai2 = '';
        $penilai3 = '';
        if(!empty($penilaian->nik_penilai_2)){
            $penilai2 = json_decode($penilaian->penilai2_property, true);
        }
        if(!empty($penilaian->nik_penilai_3)){
            $penilai3 = json_decode($penilaian->penilai3_property, true);
        }
        $tipe = MtrPenilaianTipe::whereNotIn('id', [6])->orderBy('tipe_penilaian', 'asc')->get();
        if($penilaian->id_tipe == 6){
            $tipe = MtrPenilaianTipe::whereIn('id', [6])->orderBy('tipe_penilaian', 'asc')->get();
        }
        $status = MtrPenilaianStatus::whereIn('id', [4,5,8,9,10,11,12,13,14,15,$penilaian->id_status])->orderBy('id', 'asc')->get();
        if($penilaian->id_tipe == 6){
            $status = MtrPenilaianStatus::whereIn('id', [8,15,$penilaian->id_status])->orderBy('id', 'asc')->get();
        }
        $perpanjang = MtrPenilaianPerpanjang::orderBy('id', 'asc')->get();// ambil angka rata-rata berdasarkan penilaian_2
        $where = ['id_trx' => $id];
        $trxSkor2 = DB::table('trx_tbl_penilaian_skor')
        ->selectRaw('penilaian_2, CAST(AVG(skor_karyawan) AS DECIMAL(6,2)) AS avg_kar, CAST(AVG(skor_penilai_1) AS DECIMAL(6,2)) AS avg_nilai1')
        ->where($where)->groupBy('penilaian_2')
        ->orderBy('id_nilai3', 'asc')->get();
        // 
        $angka_skor = '';
        $no = 1;
        foreach ($trxSkor2 as $value2) {
            $strNilai2 = $value2->penilaian_2;
            $angka_skor .= '<tr><td><strong>'.$strNilai2.'</strong></td>
            <td><div align="center"><strong>'.$value2->avg_kar.'</strong></div></td>
            <td><div align="center"><strong>'.$value2->avg_nilai1.'</strong></div></td></tr>';
            // 
            $where = ['id_trx' => $id];
            $trxSkor3 = TrxPenilaianSkor::select('id_nilai3','penilaian_3','skor_karyawan','skor_penilai_1')
            ->where('penilaian_2', '=', $strNilai2)->where($where)->orderBy('id_nilai3', 'asc')->get();
            foreach ($trxSkor3 as $value3) {
                $strNilai3 = $value3['penilaian_3'];
                $angka_skor .= '<tr><td>'.$no.'. '.$strNilai3.'</td><td><div align="center">'.$value3['skor_karyawan'].'</div></td><td><div align="center">'.$value3['skor_penilai_1'].'</div></td></tr>';
                $no++;
            }
        }
        // ambil angka rata-rata semua
        $where = ['id_trx' => $id];
        $trxSkor2 = DB::table('trx_tbl_penilaian_skor')
        ->selectRaw('CAST(AVG(skor_karyawan) AS DECIMAL(6,2)) AS avg_kar, CAST(AVG(skor_penilai_1) AS DECIMAL(6,2)) AS avg_nilai1')
        ->where($where)->first();
        $trxSkorTotal = DB::table('trx_tbl_penilaian_skor')->selectRaw('SUM(skor_karyawan) AS skor_karyawan, SUM(skor_penilai_1) AS skor_penilai_1')->where($where)->first();
        // 
        $angka_rata = '<tr><td><div align="right"><strong>RATA-RATA</strong></div></td>
        <td><div align="center"><strong>'.$trxSkor2->avg_kar.'</strong></div></td>
        <td><div align="center"><strong>'.$trxSkor2->avg_nilai1.'</strong></div></td>
        </tr>';
        $angka_total = '<tr><td><div align="right"><strong>NILAI TOTAL</strong></div></td>
        <td><div align="center"><strong>'.$trxSkorTotal->skor_karyawan.'</strong></div></td>
        <td><div align="center"><strong>'.$trxSkorTotal->skor_penilai_1.'</strong></div></td>
        </tr>';
        // ambil isian kesimpulan dan rekomendasi
        $txt_perpanjang = '';
        $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai1['nik'] ];
        $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
        if(!empty($trx)){
            if($trx->is_perpanjang){
                $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
            }else{
                $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
            }
        }
        $isian_kes_rek = '<tr><td><b>Kesimpulan dan Rekomendasi oleh '.$penilai1['nama'].':<br/>'.$penilaian->kes_rek_1.$txt_perpanjang;
        if(!empty($penilaian->nik_penilai_2)){
            $txt_perpanjang = '';
            $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai2['nik'] ];
            $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
            if(!empty($trx)){
                if($trx->is_perpanjang){
                    $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                }else{
                    $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
                }
            }
            $isian_kes_rek .= '<br/><br/>Kesimpulan dan Rekomendasi oleh '.$penilai2['nama'].':<br/>'.$penilaian->kes_rek_2.$txt_perpanjang;
        }
        if(!empty($penilaian->nik_penilai_3)){
            $txt_perpanjang = '';
            $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai3['nik'] ];
            $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
            if(!empty($trx)){
                if($trx->is_perpanjang){
                    $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                }else{
                    $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
                }
            }
            $isian_kes_rek .= '<br/><br/>Kesimpulan dan Rekomendasi oleh '.$penilai3['nama'].':<br/>'.$penilaian->kes_rek_3.$txt_perpanjang;
        }
        $isian_kes_rek .= '</b></td></tr>';
        // nilai outsourcing
        if($penilaian->id_tipe == 6){
            $where = [ 'id_trx' => $id ];
            $angka_skor = TrxPenilaianSkor::where($where)->orderBy('id_nilai3', 'asc')->get();
            $angka_rata = TrxPenilaianSkor::where($where)->sum('skor_penilai_1');
        }
        // 
        return view( 'formulir.penilaian.update', compact( 'history', 'penilaian', 'karyawan', '_karyawan', 'penilai1', 'penilai2', 'penilai3', 'status', 'tipe', 'perpanjang', 'angka_skor', 'angka_rata', 'isian_kes_rek', 'angka_total' ) );
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
        $id = (new OpenDataController)->decryptWithKey($id);
        // dd($request->all(), $id);
        $id_status = $request->id_status;
        $id_tipe = $request->id_tipe;
        if($id_status==14 || $id_status==15){
            $id_status = 5;
            if($id_tipe == 6){
                $id_status = 15;
            }
        }
        $trx = TrxPenilaian::find($id);
        if($request->has('penilai_2')){
            $trx->nik_penilai_2 = $request->penilai_2;
            $trx->penilai2_property = $this->getNikProperty($request->penilai_2);
        }
        if($request->has('penilai_3')){
            $trx->nik_penilai_3 = $request->penilai_3;
            $trx->penilai3_property = $this->getNikProperty($request->penilai_3);
        }
        if($request->has('is_perpanjang')){
            $trx->is_perpanjang = $request->is_perpanjang;
        }
        if($request->has('bulan')){
            $trx->bulan = $request->bulan;
        }
        if($request->has('kes_rek_final')){
            $trx->kes_rek_final = $request->kes_rek_final;
        }
        $trx->id_tipe = $id_tipe;
        $trx->id_status = $id_status;
        $trx->update();
        // 6=Penilaian Outsourcing
        if($id_tipe == 6){
            // tidak digunakan karena pic_hco menjadi 2 nik sedangkan ini nik sudah ditentukan dari 'nik'=>auth()->user()->nik
            // $this->deleteToDoList(['id_trx'=>$id, 'nik'=>auth()->user()->nik, 'pic'=>'hco', 'filename'=>'emails.penilaian0_outsourcing']);
            // ini yang digunakan tidak menggunaka nik
            $this->deleteToDoList(['id_trx'=>$id, 'pic'=>'hco', 'filename'=>'emails.penilaian0_outsourcing']);
        }
        // selain Penilaian Outsourcing
        else{
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>auth()->user()->nik, 'pic'=>'hco', 'filename'=>'emails.penilaian0']);
        }
        $this->insertLog($trx->id, $trx->id_status, auth()->user()->nik);
        // simpan history perubahan
        if($id_status==4 || $id_status==8){
            $data = New TrxPenilaianLogHistory;
            $data->id_trx = $id;
            $data->id_status = $id_status;
            $data->nik_pic = auth()->user()->nik;
            $data->revised_by = auth()->user()->nik;
            if($request->has('is_perpanjang')){
                $data->is_perpanjang = $request->is_perpanjang;
            }
            if($request->has('bulan')){
                $data->bulan = $request->bulan;
            }
            if($request->has('kes_rek_final')){
                $data->keterangan = $request->kes_rek_final;
            }
            $data->save();
        }
        // parameter yang dipassing ke sendemail
        $karyawan = json_decode($trx->karyawan_property, true);
        $atasan = json_decode($trx->penilai1_property, true);
        $penilai2 = json_decode($trx->penilai2_property, true);
        $penilai3 = json_decode($trx->penilai3_property, true);
        $mailData['title'] = 'Formulir Penilaian Karyawan';
        $mailData['filename'] = 'emails.penilaian0';
        if($trx->id_tipe == 2 || $trx->id_tipe == 4){
            $mailData['title'] .= '- Reguler';
        }else if($trx->id_tipe == 3 || $trx->id_tipe == 5){
            $mailData['title'] .= '- Assesment';
        }
        // 6 = Penilaian Outsourcing
        else if($trx->id_tipe == 6){
            $mailData['title'] .= ' - Outsourcing';
            $mailData['filename'] = 'emails.penilaian0_outsourcing';
        }
        if($request->has('penilai_2')){
            $mailData['data'] = ['nama'=>$karyawan['nama'].'/'.$karyawan['nik'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('penilaianskor.show', [(new OpenDataController)->encryptWithKey($trx->id), 'nik' => (new OpenDataController)->encryptWithKey($trx->nik_penilai_2), 'id_status' => (new OpenDataController)->encryptWithKey($id_status), 'pic' => (new OpenDataController)->encryptWithKey('nik_penilai_2')]), 'id_trx' => $trx->id ];
            $mailData['to'] = $penilai2['email'];
            $todolist = ['id_trx' => $trx->id, 'nik' => $trx->nik_penilai_2, 'id_status' => $id_status, 'pic' => 'nik_penilai_2'];
            $this->insertToDoList($todolist, $mailData);
            $this->sendEmail($mailData);
        }
        if($request->has('penilai_3')){
            $mailData['data'] = ['nama'=>$karyawan['nama'].'/'.$karyawan['nik'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('penilaianskor.show', [(new OpenDataController)->encryptWithKey($trx->id), 'nik' => (new OpenDataController)->encryptWithKey($trx->nik_penilai_3), 'id_status' => (new OpenDataController)->encryptWithKey($id_status), 'pic' => (new OpenDataController)->encryptWithKey('nik_penilai_3')]), 'id_trx' => $trx->id ];
            $mailData['to'] = $penilai3['email'];
            $todolist = ['id_trx' => $trx->id, 'nik' => $trx->nik_penilai_3, 'id_status' => $id_status, 'pic' => 'nik_penilai_3'];
            $this->insertToDoList($todolist, $mailData);
            $this->sendEmail($mailData);
        }
        // start
        // pembuatan dokumen kontrak PKWT
        // 1=PT ALITA PRAYA MITRA
        // 2=KKWT
        $check1 = Karyawan::where(['nik'=>$trx->nik_karyawan, 'is_aktif'=>1, 'kode_perusahaan'=>1, 'kode_status'=>2])->first();
        // 1=Diperpanjang
        // 4=Done by HC (180)
        // 8=Done by HC (360)
        $check2 = TrxPenilaian::where(['id'=>$id, 'is_perpanjang'=>1])->whereIn('id_status', [4,8])->exists();
        $no_kontrak = TrxPenilaian::where(['is_perpanjang'=>1])->whereIn('id_status', [4,8])->whereYear('tanggal_kontrak', now()->year)->orderByRaw('no_kontrak*1 DESC')->first()->no_kontrak;
        if(empty($no_kontrak)){
            $no_kontrak = 1;
        }else{
            $no_kontrak += 1;
        }
        if(!empty($check1) && $check2){
            if(empty($trx->no_kontrak)){
                $trx->no_kontrak = $no_kontrak;
            }
            // $trx->tanggal_kontrak = Carbon::parse($check1->tanggal_berakhir)->addDay()->format('Y-m-d');
            // tanggal_kontrak diambil dari tanggal hari ini
            $trx->tanggal_kontrak = Carbon::today()->format('Y-m-d');
            // tanggal_terhitung diambil dari 1 hari setelah tanggal_berakhir
            $trx->tanggal_terhitung = Carbon::parse($check1->tanggal_berakhir)->addDay()->format('Y-m-d');
            $trx->id_perusahaan = $check1->kode_perusahaan;
            $trx->id_divisi = $check1->kode_divisi;
            $trx->id_departemen = $check1->kode_departemen;
            $trx->id_sub_departemen = $check1->kode_sub_departemen;
            $trx->id_level = $check1->kode_level;
            $trx->id_jabatan = $check1->kode_jabatan;
            // ambil angka payroll
            $payroll = TrxPayroll::where(['nik'=>$trx->nik_karyawan, 'bulan'=>now()->month, 'tahun'=>now()->year])->first();
            if(empty($payroll)){
                $payroll = TrxPayrollLog::where(['nik'=>$trx->nik_karyawan, 'bulan'=>now()->subMonth()->month, 'tahun'=>now()->subMonth()->year])->first();
            }
            // ambil angka tunjangan
            $tunjangan = TrxPayrollAddsub::where(['nik'=>$trx->nik_karyawan, 'bulan'=>now()->month, 'tahun'=>now()->year])->whereIn('id_addsub', [1,2,3,4,5])->get();
            if(count($tunjangan)==0){
                $tunjangan = TrxPayrollAddsubLog::where(['nik'=>$trx->nik_karyawan, 'bulan'=>now()->subMonth()->month, 'tahun'=>now()->subMonth()->year])->whereIn('id_addsub', [1,2,3,4,5])->get();
            }
            $trx->gapok = $payroll->gapok;
            // 1=Tunjangan Makan
            $trx->t_makan = $tunjangan->where('id_addsub', 1)->first()->angka;
            // 2=Tunjangan Transportasi
            $trx->t_transportasi = $tunjangan->where('id_addsub', 2)->first()->angka;
            // 3=Tunjangan Jabatan
            $trx->t_jabatan = $tunjangan->where('id_addsub', 3)->first()->angka;
            // 4=Tunjangan Telekomunikasi
            $trx->t_telekomunikasi = $tunjangan->where('id_addsub', 4)->first()->angka;
            // 5=Tunjangan Lainnya
            $trx->t_lain = $tunjangan->where('id_addsub', 5)->first()->angka;
            $trx->update();
        }
        // end
        // start
        // update tanggal_berakhir untuk
        // 1=Diperpanjang
        // 4=Done by HC (180)
        // 8=Done by HC (360)
        $tanggal_berakhir_before = Karyawan::where('nik', $trx->nik_karyawan)->value('tanggal_berakhir');
        if($request->is_perpanjang == 1 && in_array($id_status, [4,8])){
            Karyawan::where('nik', $trx->nik_karyawan)->update([
                'tanggal_berakhir' => DB::raw("LAST_DAY(DATE_ADD(tanggal_berakhir, INTERVAL {$request->bulan} MONTH))")
            ]);
            // start
            $log = New KaryawanLog;
            $log->nik = $trx->nik_karyawan;
            // {"tanggal_berakhir":{"before":"2025-12-31","after":"2026-12-31"}}
            $tanggal_berakhir_after = Karyawan::where('nik', $trx->nik_karyawan)->value('tanggal_berakhir');
            $arr['tanggal_berakhir'] = ['before'=>$tanggal_berakhir_before, 'after'=>$tanggal_berakhir_after];
            $log->perubahan = json_encode($arr);
            $log->insert_by = auth()->user()->nik;
            $catatan = json_encode(['sumber'=>'Penilaian Karyawan']);
            $log->catatan = $catatan;
            $log->save();
            // start sendEmail
            $mailData['title'] = 'Perubahan Data Karyawan';
            $mailData['filename'] = 'emails.data0';
            $lbl_history = ["tanggal_lahir"=>"Tanggal Lahir","tempat_lahir"=>"Tempat Lahir","alamat_domisili"=>"Alamat Domisili","alamat_ktp"=>"Alamat KTP","alamat_npwp"=>"Alamat NPWP","telepon1"=>"Telepon 1","telepon2"=>"Telepon 2","telepon3"=>"Telepon 3","no_ktp"=>"NIK KTP","no_npwp"=>"NPWP","no_bpjs"=>"No BPJS Kesehatan","no_bpjstk"=>"No BPJS Ketenagakerjaan","nama_pasangan"=>"Nama Pasangan","kode_kelamin"=>"Jenis Kelamin","kode_agama"=>"Agama","kode_kawin"=>"Status Kawin","kode_warga"=>"Kewarganegaraan","nama"=>"Nama","telepon_darurat"=>"Telepon Darurat","nama_anak"=>"Nama Anak","email"=>"Email","kode_perusahaan"=>"Perusahaan","alamat_kantor"=>"Alamat Kantor","kode_divisi"=>"Direktorat","kode_departemen"=>"Divisi","kode_sub_departemen"=>"Departemen","layer1"=>"Layer 1","layer2"=>"Layer 2","layer3"=>"Layer 3","kode_status"=>"Status Karyawan","wg_id"=>"Grup Kerja","is_aktif"=>"Aktif","gaji_pokok"=>"Gaji Pokok","kode_level"=>"Level","kode_jabatan"=>"Jabatan","tanggal_mulai"=>"Tanggal Mulai Bekerja","tanggal_bergabung"=>"Tanggal Bergabung","tanggal_berakhir"=>"Tanggal Berakhir","tanggal_karyawan_tetap"=>"Tanggal Karyawan Tetap","tanggal_tidak_aktif"=>"Tanggal Tidak Aktif","no_rek"=>"Nomor Rekening","nama_bank"=>"Nama Bank","nama_rek"=>"Rekening Atas Nama","findim_project"=>"Findim Project","findim_divisi"=>"Findim Divisi","findim_departemen"=>"Findim Departemen","findim_subdepartemen"=>"Findim Sub Departemen","id_grade"=>"Grade","id_fp"=>"ID Finger Print"];
            $mailData['data'] = ['insert_by' => $log->_insert_by->nama.' / '.$log->insert_by, 'nik' => $log->nik, 'nama' => $log->_nik->nama, 'perubahan' => $log->perubahan, 'lbl_history' => $lbl_history, 'created_at' => $log->created_at, 'catatan' => $catatan];
            $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
            $this->sendEmail($mailData);
            // end
        }
        // end
        return redirect( 'penilaian' )->with( 'msg', 'Data is saved successfully.' );
    }
    
    public function revisi(Request $request, $id)
    {
        // dd($request->all(), $id);
        $pic = $request->pic;
        $id = (new OpenDataController)->decryptWithKey($id);
        $penilaian = TrxPenilaian::find($id);
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        $_karyawan = json_decode($penilaian->karyawan_property, true);
        $penilai1 = json_decode($penilaian->penilai1_property, true);
        $penilai2 = '';
        $penilai3 = '';
        if(!empty($penilaian->nik_penilai_2)){
            $penilai2 = json_decode($penilaian->penilai2_property, true);
        }
        if(!empty($penilaian->nik_penilai_3)){
            $penilai3 = json_decode($penilaian->penilai3_property, true);
        }
        $tipe = MtrPenilaianTipe::whereNotIn('id', [6])->orderBy('tipe_penilaian', 'asc')->get();
        if($penilaian->id_tipe == 6){
            $tipe = MtrPenilaianTipe::whereIn('id', [6])->orderBy('tipe_penilaian', 'asc')->get();
        }
        $status = MtrPenilaianStatus::whereIn('id', [4,5,8,9,10,11,12,13,14,15,$penilaian->id_status])->orderBy('id', 'asc')->get();
        if($penilaian->id_tipe == 6){
            $status = MtrPenilaianStatus::whereIn('id', [8,15,$penilaian->id_status])->orderBy('id', 'asc')->get();
        }
        $perpanjang = MtrPenilaianPerpanjang::orderBy('id', 'asc')->get();// ambil angka rata-rata berdasarkan penilaian_2
        $where = ['id_trx' => $id];
        $trxSkor2 = DB::table('trx_tbl_penilaian_skor')
        ->selectRaw('penilaian_2, CAST(AVG(skor_karyawan) AS DECIMAL(6,2)) AS avg_kar, CAST(AVG(skor_penilai_1) AS DECIMAL(6,2)) AS avg_nilai1')
        ->where($where)->groupBy('penilaian_2')
        ->orderBy('id_nilai3', 'asc')->get();
        // 
        $angka_skor = '';
        $no = 1;
        foreach ($trxSkor2 as $value2) {
            $strNilai2 = $value2->penilaian_2;
            $angka_skor .= '<tr><td><strong>'.$strNilai2.'</strong></td>
            <td><div align="center"><strong>'.$value2->avg_kar.'</strong></div></td>
            <td><div align="center"><strong>'.$value2->avg_nilai1.'</strong></div></td></tr>';
            // 
            $where = ['id_trx' => $id];
            $trxSkor3 = TrxPenilaianSkor::select('id_nilai3','penilaian_3','skor_karyawan','skor_penilai_1')
            ->where('penilaian_2', '=', $strNilai2)->where($where)->orderBy('id_nilai3', 'asc')->get();
            foreach ($trxSkor3 as $value3) {
                $strNilai3 = $value3['penilaian_3'];
                $angka_skor .= '<tr><td>'.$no.'. '.$strNilai3.'</td><td><div align="center">'.$value3['skor_karyawan'].'</div></td><td><div align="center">'.$value3['skor_penilai_1'].'</div></td></tr>';
                $no++;
            }
        }
        // ambil angka rata-rata semua
        $where = ['id_trx' => $id];
        $trxSkor2 = DB::table('trx_tbl_penilaian_skor')
        ->selectRaw('CAST(AVG(skor_karyawan) AS DECIMAL(6,2)) AS avg_kar, CAST(AVG(skor_penilai_1) AS DECIMAL(6,2)) AS avg_nilai1')
        ->where($where)->first();
        $trxSkorTotal = DB::table('trx_tbl_penilaian_skor')->selectRaw('SUM(skor_karyawan) AS skor_karyawan, SUM(skor_penilai_1) AS skor_penilai_1')->where($where)->first();
        // 
        $angka_rata = '<tr><td><div align="right"><strong>RATA-RATA</strong></div></td>
        <td><div align="center"><strong>'.$trxSkor2->avg_kar.'</strong></div></td>
        <td><div align="center"><strong>'.$trxSkor2->avg_nilai1.'</strong></div></td>
        </tr>';
        $angka_total = '<tr><td><div align="right"><strong>NILAI TOTAL</strong></div></td>
        <td><div align="center"><strong>'.$trxSkorTotal->skor_karyawan.'</strong></div></td>
        <td><div align="center"><strong>'.$trxSkorTotal->skor_penilai_1.'</strong></div></td>
        </tr>';
        // ambil isian kesimpulan dan rekomendasi
        $txt_perpanjang = '';
        $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai1['nik'] ];
        $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
        if(!empty($trx)){
            if($trx->is_perpanjang){
                $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
            }else{
                $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
            }
        }
        $isian_kes_rek = '<tr><td><b>Kesimpulan dan Rekomendasi oleh '.$penilai1['nama'].':<br/>'.$penilaian->kes_rek_1.$txt_perpanjang;
        if(!empty($penilaian->nik_penilai_2)){
            $txt_perpanjang = '';
            $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai2['nik'] ];
            $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
            if(!empty($trx)){
                if($trx->is_perpanjang){
                    $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                }else{
                    $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
                }
            }
            $isian_kes_rek .= '<br/><br/>Kesimpulan dan Rekomendasi oleh '.$penilai2['nama'].':<br/>'.$penilaian->kes_rek_2.$txt_perpanjang;
        }
        if(!empty($penilaian->nik_penilai_3)){
            $txt_perpanjang = '';
            $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai3['nik'] ];
            $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
            if(!empty($trx)){
                if($trx->is_perpanjang){
                    $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                }else{
                    $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
                }
            }
            $isian_kes_rek .= '<br/><br/>Kesimpulan dan Rekomendasi oleh '.$penilai3['nama'].':<br/>'.$penilaian->kes_rek_3.$txt_perpanjang;
        }
        $isian_kes_rek .= '</b></td></tr>';
        // nilai outsourcing
        if($penilaian->id_tipe == 6){
            $where = [ 'id_trx' => $id ];
            $angka_skor = TrxPenilaianSkor::where($where)->orderBy('id_nilai3', 'asc')->get();
            $angka_rata = TrxPenilaianSkor::where($where)->sum('skor_penilai_1');
        }
        // 
        return view( 'formulir.penilaian.revisi', compact( 'penilaian', 'karyawan', '_karyawan', 'penilai1', 'penilai2', 'penilai3', 'status', 'tipe', 'perpanjang', 'angka_skor', 'angka_rata', 'isian_kes_rek', 'pic', 'angka_total' ) );
    }

    public function revisi_save(Request $request)
    {
        // dd($request->all());
        $trx = TrxPenilaian::find( $request->id );
        // nik_penilai_1
        if(!empty($trx->nik_penilai_1)){
            $trx->kes_rek_1 = $request->kesrek_1;
            $trx->update();
            $log = TrxPenilaianLog::find( $request->logid_1 );
            if(!empty($log)){
                $log->keterangan = $request->kesrek_1;
                $log->is_perpanjang = $request->isperpanjang_1;
                $log->bulan = $request->bulan_1;
                $log->update();
                // simpan history perubahan
                $data = New TrxPenilaianLogHistory;
                $data->id_trx = $log->id_trx;
                $data->id_status = $log->id_status;
                $data->nik_pic = $log->nik_pic;
                $data->keterangan = $request->kesrek_1;
                $data->is_perpanjang = $request->isperpanjang_1;
                $data->bulan = $request->bulan_1;
                $data->revised_by = auth()->user()->nik;
                $data->save();
            }
        }
        // nik_penilai_2
        if(!empty($trx->nik_penilai_2)){
            $trx->kes_rek_2 = $request->kesrek_2;
            $trx->update();
            $log = TrxPenilaianLog::find( $request->logid_2 );
            if(!empty($log)){
                $log->keterangan = $request->kesrek_2;
                $log->is_perpanjang = $request->isperpanjang_2;
                $log->bulan = $request->bulan_2;
                $log->update();
                // simpan history perubahan
                $data = New TrxPenilaianLogHistory;
                $data->id_trx = $log->id_trx;
                $data->id_status = $log->id_status;
                $data->nik_pic = $log->nik_pic;
                $data->keterangan = $request->kesrek_2;
                $data->is_perpanjang = $request->isperpanjang_2;
                $data->bulan = $request->bulan_2;
                $data->revised_by = auth()->user()->nik;
                $data->save();
            }
        }
        // nik_penilai_3
        if(!empty($trx->nik_penilai_3)){
            $trx->kes_rek_3 = $request->kesrek_3;
            $trx->update();
            $log = TrxPenilaianLog::find( $request->logid_3 );
            if(!empty($log)){
                $log->keterangan = $request->kesrek_3;
                $log->is_perpanjang = $request->isperpanjang_3;
                $log->bulan = $request->bulan_3;
                $log->update();
                // simpan history perubahan
                $data = New TrxPenilaianLogHistory;
                $data->id_trx = $log->id_trx;
                $data->id_status = $log->id_status;
                $data->nik_pic = $log->nik_pic;
                $data->keterangan = $request->kesrek_3;
                $data->is_perpanjang = $request->isperpanjang_3;
                $data->bulan = $request->bulan_3;
                $data->revised_by = auth()->user()->nik;
                $data->save();
            }
        }
        // hco
        if($trx->id_status==4 || $trx->id_status==8){
            $trx->kes_rek_final = $request->kesrek_hco;
            $trx->is_perpanjang = $request->isperpanjang_hco;
            if($request->isperpanjang_hco == 0){
                $trx->is_perpanjang = 2;
            }
            $trx->bulan = $request->bulan_hco;
            $getOriginal = $trx->getOriginal();
            $trx->update();
            // check apakah ada perubahan bulan perpanjangan
            $getChanges = $trx->getChanges();
            if($request->isperpanjang_hco == 1 && $getOriginal['bulan'] <> $getChanges['bulan']){
                $tanggal_berakhir_before = Karyawan::where('nik', $trx->nik_karyawan)->value('tanggal_berakhir');
                // ambil first date dari tanggal_berakhir dikurangi bulan perpanjangan before
                $first_date = Karyawan::where('nik', $trx->nik_karyawan)
                    ->selectRaw("DATE_FORMAT(DATE_SUB(tanggal_berakhir, INTERVAL {$getOriginal['bulan']} MONTH), '%Y-%m-01') as first_date")
                    ->value('first_date');
                // kemudian tambahkan dengan bulan perpanjangan after
                $tanggal_berakhir_after = Carbon::parse($first_date)->addMonths($getChanges['bulan'])->endOfMonth()->format('Y-m-d');
                Karyawan::where('nik', $trx->nik_karyawan)->update(['tanggal_berakhir' => $tanggal_berakhir_after]);
                // start
                $log = New KaryawanLog;
                $log->nik = $trx->nik_karyawan;
                // {"tanggal_berakhir":{"before":"2025-12-31","after":"2026-12-31"}}
                $tanggal_berakhir_after = Karyawan::where('nik', $trx->nik_karyawan)->value('tanggal_berakhir');
                $arr['tanggal_berakhir'] = ['before'=>$tanggal_berakhir_before, 'after'=>$tanggal_berakhir_after];
                $log->perubahan = json_encode($arr);
                $log->insert_by = auth()->user()->nik;
                $catatan = json_encode(['sumber'=>'Revisi Penilaian Karyawan']);
                $log->catatan = $catatan;
                $log->save();
                // start sendEmail
                $mailData['title'] = 'Perubahan Data Karyawan';
                $mailData['filename'] = 'emails.data0';
                $lbl_history = ["tanggal_lahir"=>"Tanggal Lahir","tempat_lahir"=>"Tempat Lahir","alamat_domisili"=>"Alamat Domisili","alamat_ktp"=>"Alamat KTP","alamat_npwp"=>"Alamat NPWP","telepon1"=>"Telepon 1","telepon2"=>"Telepon 2","telepon3"=>"Telepon 3","no_ktp"=>"NIK KTP","no_npwp"=>"NPWP","no_bpjs"=>"No BPJS Kesehatan","no_bpjstk"=>"No BPJS Ketenagakerjaan","nama_pasangan"=>"Nama Pasangan","kode_kelamin"=>"Jenis Kelamin","kode_agama"=>"Agama","kode_kawin"=>"Status Kawin","kode_warga"=>"Kewarganegaraan","nama"=>"Nama","telepon_darurat"=>"Telepon Darurat","nama_anak"=>"Nama Anak","email"=>"Email","kode_perusahaan"=>"Perusahaan","alamat_kantor"=>"Alamat Kantor","kode_divisi"=>"Direktorat","kode_departemen"=>"Divisi","kode_sub_departemen"=>"Departemen","layer1"=>"Layer 1","layer2"=>"Layer 2","layer3"=>"Layer 3","kode_status"=>"Status Karyawan","wg_id"=>"Grup Kerja","is_aktif"=>"Aktif","gaji_pokok"=>"Gaji Pokok","kode_level"=>"Level","kode_jabatan"=>"Jabatan","tanggal_mulai"=>"Tanggal Mulai Bekerja","tanggal_bergabung"=>"Tanggal Bergabung","tanggal_berakhir"=>"Tanggal Berakhir","tanggal_karyawan_tetap"=>"Tanggal Karyawan Tetap","tanggal_tidak_aktif"=>"Tanggal Tidak Aktif","no_rek"=>"Nomor Rekening","nama_bank"=>"Nama Bank","nama_rek"=>"Rekening Atas Nama","findim_project"=>"Findim Project","findim_divisi"=>"Findim Divisi","findim_departemen"=>"Findim Departemen","findim_subdepartemen"=>"Findim Sub Departemen","id_grade"=>"Grade","id_fp"=>"ID Finger Print"];
                $mailData['data'] = ['insert_by' => $log->_insert_by->nama.' / '.$log->insert_by, 'nik' => $log->nik, 'nama' => $log->_nik->nama, 'perubahan' => $log->perubahan, 'lbl_history' => $lbl_history, 'created_at' => $log->created_at, 'catatan' => $catatan];
                $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
                $this->sendEmail($mailData);
                // end
            }
            $log = TrxPenilaianLog::find( $request->logid_hco );
            if(!empty($log)){
                $log->keterangan = $request->kesrek_hco;
                $log->is_perpanjang = $request->isperpanjang_hco;
                $log->bulan = $request->bulan_hco;
                $log->update();
                // simpan history perubahan
                $data = New TrxPenilaianLogHistory;
                $data->id_trx = $log->id_trx;
                $data->id_status = $log->id_status;
                $data->nik_pic = $log->nik_pic;
                $data->keterangan = $request->kesrek_hco;
                $data->is_perpanjang = $request->isperpanjang_hco;
                $data->bulan = $request->bulan_hco;
                $data->revised_by = auth()->user()->nik;
                $data->save();
            }
        }
        return redirect( 'penilaian' )->with( 'msg', 'Data is saved successfully.' );
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

    public function deleteToDoList($where)
    {
        // $trx = ToDoList::where($where)->first();
        // if (!empty($trx)) {
        //     $trx->delete();
        // }
        $trx = ToDoList::where($where)->delete();
    }
}
