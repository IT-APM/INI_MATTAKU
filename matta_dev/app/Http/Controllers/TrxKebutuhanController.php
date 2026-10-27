<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
// 
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Level;
use App\Models\Perusahaan;
use App\Models\Divisi;
use App\Models\Departemen;
use App\Models\SubDepartemen;
// 
use App\Models\MtrFindimProject;
use App\Models\MtrFindimDivisi;
use App\Models\MtrFindimDepartemen;
use App\Models\MtrFindimSubdepartemen;
// 
use App\Models\Kelamin;
use App\Models\Propinsi;
use App\Models\PropinsiKota;
use App\Models\JenisPermintaan;
use App\Models\PendidikanJenjang;
use App\Models\PendidikanJurusan;
use App\Models\PelatihanSub;
use App\Models\SertifikasiSub;
use App\Models\KeterampilanSub;
use App\Models\KepribadianSub;
use App\Models\StatusKerja;
use App\Models\PekerjaanSub;
use App\Models\Pengalaman;
use App\Models\KebutuhanStatus;
use App\Models\KebutuhanNikKecuali;
use App\Models\TrxKebutuhanMaster;
use App\Models\TrxKebutuhanMasterEdit;
use App\Models\TrxKebutuhanDetail;
use App\Models\TrxKebutuhanLog;
use App\Models\TrxRecruitment;
use App\Models\TrxRecruitmentIdentitas;
use App\Models\TrxRecruitmentNegosiasi;
use App\Models\TrxRecruitmentLog;
use App\Models\Holiday;
// 
use App\Models\ToDoList;
use App\Models\ToDoListLog;
use App\Models\MtrUtility;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;
// 
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Support\Facades\Crypt;
// 
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Shared\Html;
// 

class TrxKebutuhanController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:asm', ['except' => ['show','update','kota']]);
    }

    public function cleanText($text)
    {
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
        $text = str_replace('&', 'dan', $text);
        $text = str_replace(['<', '>'], ['(', ')'], $text);
        return trim($text);
    }

    public function export_word($id)
    {
        $id = (new OpenDataController)->decryptWithKey($id);
        $trxM = TrxKebutuhanMaster::find($id);
        // dd($trxM);
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('times');
        $phpWord->setDefaultFontSize(10);
        $section = $phpWord->addSection([
            'marginTop'    => 720,
            'marginBottom' => 720,
            'marginLeft'   => 720,
            'marginRight'  => 720,
        ]);
        $boldCenter  = ['bold' => true];
        $boldCenterHeader = ['bold' => true, 'size' => 14];
        $centerPara  = ['alignment' => Jc::CENTER];
        $boldStyle   = ['bold' => true];
        $normalStyle = [];
        $leftPara    = ['alignment' => Jc::LEFT];
        $tableStyle  = [
            'borderSize'  => 0,
            'borderColor' => '000000',
            'cellMargin'  => 50,
            'width'       => 10800,
            // 'unit'        => 'dxa',
        ];
        $parStyle = [
            'alignment'   => Jc::LEFT,
            'spacing'     => [
                'after'  => 0,
                'line'   => 360, // 1.5 line spacing (240 = single, 360 = 1.5, 480 = double)
                'lineRule' => 'auto',
            ],
        ];
        $headerCellStyle = [
            'shading' => [
                'type'  => \PhpOffice\PhpWord\Style\Shading::PATTERN_CLEAR,
                'color' => 'auto',
                'fill'  => 'D9D9D9',
            ],
        ];
        // $section->addText('URAIAN JABATAN', $boldCenterHeader, $centerPara);
        $tblHeader = $section->addTable([
            'borderSize'  => 1,
            'borderColor' => '000000',
            'cellMargin'  => 80,
            // 'width'       => 10800,
            'unit'        => 'dxa',
        ]);

        $tblHeader->addRow();

        // Kolom 1 - Gambar kiri
        $cellLogo = $tblHeader->addCell(1000, ['vAlign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER]);
        $cellLogo->addImage(
            public_path('assets/images/matta/alita_new.png'), // sesuaikan path gambar
            [
                'width'  => 90,
                'height' => 34,
            ]
        );

        // Kolom 2 - Judul tengah
        $cellJudul = $tblHeader->addCell(6800, ['vAlign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER]);
        $cellJudul->addText('URAIAN JABATAN', $boldCenterHeader, ['alignment' => Jc::CENTER]);

        // Kolom 3 - Kode kanan
        $cellKode = $tblHeader->addCell(2000, ['vAlign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER]);
        $cellKode->addText('No: '. $trxM->id, $normalStyle, ['alignment' => Jc::CENTER]);
        // $section->addText('KEBUTUHAN REKRUITMEN', $boldCenter, $centerPara);
        $section->addTextBreak(1);
        $atasan_property = json_decode($trxM->atasan_property, true);
        $tblInfo = $section->addTable($tableStyle);
        $infoRows = [
            ['Nama Jabatan/Posisi', $trxM->_pekerjaan->pekerjaan],
            ['Level', $trxM->_level->level],
            ['Jabatan Atasan Langsung', !is_null($trxM->atasan_property) ? $atasan_property['jabatan'] : ''],
            ['Direktorat / Divisi / Departemen', !is_null($trxM->atasan_property) ? $atasan_property['divisi'].' / '.$atasan_property['dep'].' / '.$atasan_property['sub_dep'] : ''],
            ['Status Kerja', $trxM->_status_kerja->status_kerja],
        ];
        foreach ($infoRows as $row) {
            $tblInfo->addRow();
            $tblInfo->addCell(3500)->addText($row[0], $normalStyle, $parStyle);
            $tblInfo->addCell(7300)->addText($row[1], $normalStyle, $parStyle);
        }
        $section->addTextBreak(1);
        $section->addText('TUGAS, WEWENANG DAN TANGGUNG JAWAB', $boldCenter, $centerPara);
        $tblInfo = $section->addTable($tableStyle);
        $infoRows = [
            ['Tugas',          $trxM->tugas],
            ['Wewenang',       $trxM->wewenang],
            ['Tanggung jawab', $trxM->tanggung_jawab],
        ];

        foreach ($infoRows as $row) {
            $tblInfo->addRow();
            $tblInfo->addCell(3500)->addText($row[0], $normalStyle, $parStyle);

            $cell  = $tblInfo->addCell(7300);
            $lines = preg_split('/\r\n|\r|\n/', $row[1]);
            foreach ($lines as $line) {
                if (!empty(trim($line))) {
                    $line = $this->cleanText($line);
                    $cell->addText($line, $normalStyle, $parStyle);
                }
            }
        }
        $section->addTextBreak(1);
        $section->addText('KUALIFIKASI / SPESIFIKASI JABATAN', $boldCenter, $centerPara);
        $tblInfo = $section->addTable($tableStyle);
        $infoRows = [
            ['Pendidikan Minimum', $trxM->_jenjang_pendidikan->jenjang],
            ['Usia Minimum', $trxM->umur_minimum.' (Tahun)'],
            ['Usia Maksimum', $trxM->umur_maksimum.' (Tahun)'],
            ['Pengalaman Kerja (Tahun)', $trxM->_pengalaman->pengalaman],
        ];
        foreach ($infoRows as $row) {
            $tblInfo->addRow();
            $tblInfo->addCell(3500)->addText($row[0], $normalStyle, $parStyle);
            $tblInfo->addCell(7300)->addText($row[1], $normalStyle, $parStyle);
        }
        $section->addTextBreak(1);
        $tblInfo = $section->addTable($tableStyle);
        // Row 1 - PelatihanSub
        $arr   = explode(',', $trxM->id_pelatihan);
        $lines = [];
        if (!empty($trxM->id_pelatihan)) {
            foreach ($arr as $index => $value) {
                $lines[] = ($index + 1) . '. ' . $this->cleanText(PelatihanSub::find($value)?->sub_pelatihan ?? '');
            }
        }
        $tblInfo->addRow();
        $tblInfo->addCell(3500)->addText('Training yang seharusnya sudah diikuti', $normalStyle, $parStyle);
        $cell_str = $tblInfo->addCell(7300);
        if (!empty($lines)) {
            foreach ($lines as $line) {
                $cell_str->addText($line, $normalStyle, $parStyle);
            }
        } else {
            $cell_str->addText('', $normalStyle, $parStyle);
        }
        // Row 2 - SertifikasiSub
        $arr   = explode(',', $trxM->id_sertifikasi);
        $lines = [];
        if (!empty($trxM->id_sertifikasi)) {
            foreach ($arr as $index => $value) {
                $lines[] = ($index + 1) . '. ' . $this->cleanText(SertifikasiSub::find($value)?->sub_sertifikasi ?? '');
            }
        }
        $tblInfo->addRow();
        $tblInfo->addCell(3500)->addText('Kursus/sertifikasi yang seharusnya sudah diperoleh', $normalStyle, $parStyle);
        $cell_str = $tblInfo->addCell(7300);
        if (!empty($lines)) {
            foreach ($lines as $line) {
                $cell_str->addText($line, $normalStyle, $parStyle);
            }
        } else {
            $cell_str->addText('', $normalStyle, $parStyle);
        }
        $section->addTextBreak(1);
        $section->addText('KEMAMPUAN PRIBADI', $boldCenter, $centerPara);
        $tblInfo = $section->addTable($tableStyle);
        // Row 3 - KeterampilanSub
        $arr   = explode(',', $trxM->id_keterampilan);
        $lines = [];
        if (!empty($trxM->id_keterampilan)) {
            foreach ($arr as $index => $value) {
                $lines[] = ($index + 1) . '. ' . $this->cleanText(KeterampilanSub::find($value)?->sub_keterampilan ?? '');
            }
        }
        $tblInfo->addRow();
        $tblInfo->addCell(3500)->addText('Ketrampilan yang harus sudah dipunyai', $normalStyle, $parStyle);
        $cell_str = $tblInfo->addCell(7300);
        if (!empty($lines)) {
            foreach ($lines as $line) {
                $cell_str->addText($line, $normalStyle, $parStyle);
            }
        } else {
            $cell_str->addText('', $normalStyle, $parStyle);
        }
        // Row 4 - KepribadianSub
        $arr   = explode(',', $trxM->id_kepribadian);
        $lines = [];
        if (!empty($trxM->id_kepribadian)) {
            foreach ($arr as $index => $value) {
                $lines[] = ($index + 1) . '. ' . $this->cleanText(KepribadianSub::find($value)?->sub_kepribadian ?? '');
            }
        }
        $tblInfo->addRow();
        $tblInfo->addCell(3500)->addText('Kepribadian dasar yang harus dimiliki', $normalStyle, $parStyle);
        $cell_str = $tblInfo->addCell(7300);
        if (!empty($lines)) {
            foreach ($lines as $line) {
                $cell_str->addText($line, $normalStyle, $parStyle);
            }
        } else {
            $cell_str->addText('', $normalStyle, $parStyle);
        }
        // 
        $filename = 'uraian_' . $id . '_' . now()->format('YmdHis') . '.docx';
        $path = storage_path('app/' . $filename);
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($path);
        return response()->download($path, $filename)->deleteFileAfterSend(true);
    }

    public function dashboard(Request $request)
    {
        // dd($request->all());
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        // start dashboard
        $lable_data = ['Pengajuan Recruitment','Kebutuhan Personil','Pengajuan Terpenuhi','Kebutuhan Personil Terpenuhi','Pengajuan Batal','Kebutuhan Personil Batal','Pengajuan Belum Terpenuhi','Kebutuhan Personil Belum Terpenuhi'];
        $tahun = [];
        $_direktorat = [];
        $_divisi = [];
        $_departemen = [];
        $_status_kerja = [];
        $_area = [];
        // 10=Done by HC
        $sts_terpenuhi = [10];
        // 1=Requested by Requestor
        // 2=Approved by L1
        // 9=Approved by L2 to HC
        $sts_outstanding = [1,2,9];
        // $sts_cancel = array_merge($sts_terpenuhi, $sts_outstanding);
        $sts_cancel = [3,5,7,12,13,17,20,24,26,27,28];
        // start filter
        $select_tahun = TrxKebutuhanMaster::select(DB::raw('YEAR(created_at) as tahun'))->distinct()->orderBy('tahun', 'asc')->pluck('tahun');
        $all = TrxKebutuhanMaster::all();
        $arr_direktorat = [];
        $arr_divisi = [];
        $arr_departemen = [];
        $arr_status_kerja = [];
        $arr_area = [];
        foreach($all as $data){
            $pengaju_property = json_decode($data->pengaju_property);
            array_push($arr_direktorat, $pengaju_property->divisi);
            array_push($arr_divisi, $pengaju_property->dep);
            array_push($arr_departemen, $pengaju_property->sub_dep);
            array_push($arr_status_kerja, StatusKerja::find($data->id_status_kerja)->status_kerja);
            array_push($arr_area, Propinsi::find($data->id_area)->propinsi);
        }
        $arr_select_tahun = [];
        $select_direktorat = array_values(array_unique($arr_direktorat));
        sort($select_direktorat);
        $arr_select_direktorat = [];
        $select_divisi = array_values(array_unique($arr_divisi));
        sort($select_divisi);
        $arr_select_divisi = [];
        $select_departemen = array_values(array_unique($arr_departemen));
        sort($select_departemen);
        $arr_select_departemen = [];
        $select_status_kerja = array_values(array_unique($arr_status_kerja));
        sort($select_status_kerja);
        $arr_select_status_kerja = [];
        $select_area = array_values(array_unique($arr_area));
        sort($select_area);
        $arr_select_area = [];
        if ($request->filter == true) {
            $tahun = $request->input('tahun', []);
            $_direktorat = $request->input('direktorat', []);
            $_divisi = $request->input('divisi', []);
            $_departemen = $request->input('departemen', []);
            $_status_kerja = $request->input('status_kerja', []);
            $_area = $request->input('area', []);
            if (count($tahun) > 0) {
                $arr_select_tahun = $tahun;
            }
            if (count($_direktorat) > 0) {
                $arr_select_direktorat = $_direktorat;
            }
            if (count($_divisi) > 0) {
                $arr_select_divisi = $_divisi;
            }
            if (count($_departemen) > 0) {
                $arr_select_departemen = $_departemen;
            }
            if (count($_status_kerja) > 0) {
                $arr_select_status_kerja = $_status_kerja;
            }
            if (count($_area) > 0) {
                $arr_select_area = $_area;
            }
            // all
            $query = TrxKebutuhanMaster::whereIn(DB::raw('YEAR(created_at)'), $tahun);
            $all = $query->get();
            $n_all = $query->sum('jumlah');
            $arr_direktorat = [];
            $arr_divisi = [];
            $arr_departemen = [];
            $arr_status_kerja = [];
            $arr_area = [];
            $arr_direktorat_year = [];
            $arr_direktorat_month_year = [];
            $arr_posisi = [];
            foreach($all as $data){
                $pengaju_property = json_decode($data->pengaju_property);
                if(in_array($pengaju_property->divisi, $_direktorat)){
                    array_push($arr_direktorat, $pengaju_property->divisi);
                    array_push($arr_direktorat_year, ['tahun'=>Carbon::createFromFormat('d-m-Y H:i:s', $data->created_at)->year, 'direktorat'=>$pengaju_property->divisi]);
                    array_push($arr_direktorat_month_year, ['bulan_tahun'=>Carbon::createFromFormat('d-m-Y H:i:s', $data->created_at)->format('M Y'), 'direktorat'=>$pengaju_property->divisi]);
                    array_push($arr_posisi, ['posisi'=>PekerjaanSub::find($data->id_pekerjaan)->pekerjaan, 'direktorat'=>$pengaju_property->divisi]);
                    
                }
                if(in_array($pengaju_property->dep, $_divisi)){
                    array_push($arr_divisi, $pengaju_property->dep);
                }
                if(in_array($pengaju_property->sub_dep, $_departemen)){
                    array_push($arr_departemen, $pengaju_property->sub_dep);
                }
                if(in_array(StatusKerja::find($data->id_status_kerja)->status_kerja, $_status_kerja)){
                    array_push($arr_status_kerja, StatusKerja::find($data->id_status_kerja)->status_kerja);
                }
                if(in_array(Propinsi::find($data->id_area)->propinsi, $_area)){
                    array_push($arr_area, Propinsi::find($data->id_area)->propinsi);
                }
            }
            $per_direktorat_all = array_count_values($arr_direktorat);
            ksort($per_direktorat_all);
            $per_divisi_all = array_count_values($arr_divisi);
            ksort($per_divisi_all);
            $per_departemen_all = array_count_values($arr_departemen);
            ksort($per_departemen_all);
            $per_status_kerja_all = array_count_values($arr_status_kerja);
            ksort($per_status_kerja_all);
            $per_area_all = array_count_values($arr_area);
            ksort($per_area_all);
            // tahun
            $result = collect($arr_direktorat_year);
            $_arr_direktorat_year = $result->groupBy(['tahun', 'direktorat'])
                ->map(function ($groupBy) {
                    return $groupBy->map(function ($items) {
                        return $items->count();
                    });
                });
            // bulan_tahun
            $result = collect($arr_direktorat_month_year);
            $_arr_direktorat_month_year = $result->groupBy(['bulan_tahun', 'direktorat'])
                ->map(function ($groupBy) {
                    return $groupBy->map(function ($items) {
                        return $items->count();
                    });
                });
            // posisi
            $result = collect($arr_posisi);
            $_arr_direktorat_posisi = $result->groupBy(['posisi', 'direktorat'])
                ->map(function ($groupBy) {
                    return $groupBy->map(function ($items) {
                        return $items->count();
                    });
                });
            // terpenuhi
            $query = TrxKebutuhanMaster::whereIn(DB::raw('YEAR(created_at)'), $tahun);
            $terpenuhi = $query->whereIn('status_pengajuan', $sts_terpenuhi)->get();
            $n_terpenuhi = $terpenuhi->sum('jumlah');
            $arr_direktorat = [];
            $arr_direktorat_ontime = [];
            $arr_direktorat_over = [];
            $arr_divisi = [];
            $arr_divisi_ontime = [];
            $arr_divisi_over = [];
            $arr_departemen = [];
            $arr_departemen_ontime = [];
            $arr_departemen_over = [];
            $arr_status_kerja = [];
            $arr_area = [];
            foreach($terpenuhi as $data){
                $pengaju_property = json_decode($data->pengaju_property);
                if(in_array($pengaju_property->divisi, $_direktorat)){
                    array_push($arr_direktorat, $pengaju_property->divisi);
                }
                if(in_array($pengaju_property->dep, $_divisi)){
                    array_push($arr_divisi, $pengaju_property->dep);
                }
                if(in_array($pengaju_property->sub_dep, $_departemen)){
                    array_push($arr_departemen, $pengaju_property->sub_dep);
                }
                if(in_array(StatusKerja::find($data->id_status_kerja)->status_kerja, $_status_kerja)){
                    array_push($arr_status_kerja, StatusKerja::find($data->id_status_kerja)->status_kerja);
                }
                if(in_array(Propinsi::find($data->id_area)->propinsi, $_area)){
                    array_push($arr_area, Propinsi::find($data->id_area)->propinsi);
                }
                $sla_start = '';
                $sla_end = '';
                // tidak digunakan
                // start sla
                // 2=Posting
                // $sla = TrxRecruitment::where(['id_kebutuhan'=>$data->id, 'id_status'=>2])->first();
                $kandidat = TrxRecruitmentIdentitas::where(['id_kebutuhan'=>$data->id])->get();
                $id_identitas = [];
                foreach($kandidat as $_kandidat){
                    array_push($id_identitas, $_kandidat->id);
                }
                // 24=Join
                $sla = TrxRecruitmentLog::whereIn('id_identitas',$id_identitas)->where(['id_status'=>24])->orderBy('created_at', 'desc')->get();
                // if(!empty($sla->created_at)){
                //     $sla_start = Carbon::parse($sla->created_at)->format('Y-m-d');
                // }
                if($sla->count() >= $data->jumlah){
                    $sla_end = Carbon::parse($sla[0]->created_at)->format('Y-m-d');
                }
                // merujuk ke CM 507
                // Default : SLA Add employee dimulai dari Approval By L2 ke Done By HC agar muncul Status SLA
                // 9=Approved by L2 to HC
                $sla = TrxKebutuhanLog::where(['id_trx'=>$data->id, 'id_status'=>9])->first();
                if(!empty($sla->created_at)){
                    $sla_start = Carbon::parse($sla->created_at)->format('Y-m-d');
                }
                // 10=Done by HC
                if(empty($sla_end)){
                    $sla = TrxKebutuhanLog::where(['id_trx'=>$data->id, 'id_status'=>10])->first();
                    if(!empty($sla->created_at)){
                        $sla_end = Carbon::parse($sla->created_at)->format('Y-m-d');
                    }
                }
                if(!empty($sla_start) && !empty($sla_end)){
                    $period = CarbonPeriod::create($sla_start, $sla_end);
                    $workingDays = 0;
                    $holidays = Holiday::whereBetween('tanggal', [$sla_start, $sla_end])->pluck('tanggal')->toArray();
                    foreach ($period as $date) {
                        // Cek jika hari Sabtu (6) atau Minggu (0)
                        if ($date->isWeekend()) {
                            continue;
                        }
                        // Cek jika tanggal ada di daftar hari libur
                        if (in_array($date->format('Y-m-d'), $holidays)) {
                            continue;
                        }
                        $workingDays++;
                    }
                    // direktorat
                    if(in_array($pengaju_property->divisi, $_direktorat)){
                        // ontime
                        if($workingDays <= 14){
                            array_push($arr_direktorat_ontime, $pengaju_property->divisi);
                        }
                        // overdue
                        else{
                            array_push($arr_direktorat_over, $pengaju_property->divisi);
                        }
                    }
                    // divisi
                    if(in_array($pengaju_property->dep, $_divisi)){
                        // ontime
                        if($workingDays <= 14){
                            array_push($arr_divisi_ontime, $pengaju_property->dep);
                        }
                        // overdue
                        else{
                            array_push($arr_divisi_over, $pengaju_property->dep);
                        }
                    }
                    // departemen
                    if(in_array($pengaju_property->sub_dep, $_departemen)){
                        // ontime
                        if($workingDays <= 14){
                            array_push($arr_departemen_ontime, $pengaju_property->sub_dep);
                        }
                        // overdue
                        else{
                            array_push($arr_departemen_over, $pengaju_property->sub_dep);
                        }
                    }
                }
                // end sla
            }
            $per_direktorat_terpenuhi = array_count_values($arr_direktorat);
            ksort($per_direktorat_terpenuhi);
            // start sla
            // direktorat
            $per_direktorat_terpenuhi_ontime = array_count_values($arr_direktorat_ontime);
            ksort($per_direktorat_terpenuhi_ontime);
            $per_direktorat_terpenuhi_over = array_count_values($arr_direktorat_over);
            ksort($per_direktorat_terpenuhi_over);
            // divisi
            $per_divisi_terpenuhi_ontime = array_count_values($arr_divisi_ontime);
            ksort($per_divisi_terpenuhi_ontime);
            $per_divisi_terpenuhi_over = array_count_values($arr_divisi_over);
            ksort($per_divisi_terpenuhi_over);
            // departemen
            $per_departemen_terpenuhi_ontime = array_count_values($arr_departemen_ontime);
            ksort($per_departemen_terpenuhi_ontime);
            $per_departemen_terpenuhi_over = array_count_values($arr_departemen_over);
            ksort($per_departemen_terpenuhi_over);
            // end sla
            $per_divisi_terpenuhi = array_count_values($arr_divisi);
            ksort($per_divisi_terpenuhi);
            $per_departemen_terpenuhi = array_count_values($arr_departemen);
            ksort($per_departemen_terpenuhi);
            $per_status_kerja_terpenuhi = array_count_values($arr_status_kerja);
            ksort($per_status_kerja_terpenuhi);
            $per_area_terpenuhi = array_count_values($arr_area);
            ksort($per_area_terpenuhi);
            // cancel
            $id_trx = TrxKebutuhanLog::select('id_trx')->whereIn('id_status', $sts_cancel)->pluck('id_trx')->toArray();
            $query = TrxKebutuhanMaster::whereIn(DB::raw('YEAR(created_at)'), $tahun);
            // $cancel = $query->whereNotIn('status_pengajuan', $sts_cancel)->get();
            $cancel = $query->whereIn('id', $id_trx)->get();
            $n_cancel = $cancel->sum('jumlah');
            $arr_direktorat = [];
            $arr_divisi = [];
            $arr_departemen = [];
            $arr_status_kerja = [];
            $arr_area = [];
            foreach($cancel as $data){
                $pengaju_property = json_decode($data->pengaju_property);
                if(in_array($pengaju_property->divisi, $_direktorat)){
                    array_push($arr_direktorat, $pengaju_property->divisi);
                }
                if(in_array($pengaju_property->dep, $_divisi)){
                    array_push($arr_divisi, $pengaju_property->dep);
                }
                if(in_array($pengaju_property->sub_dep, $_departemen)){
                    array_push($arr_departemen, $pengaju_property->sub_dep);
                }
                if(in_array(StatusKerja::find($data->id_status_kerja)->status_kerja, $_status_kerja)){
                    array_push($arr_status_kerja, StatusKerja::find($data->id_status_kerja)->status_kerja);
                }
                if(in_array(Propinsi::find($data->id_area)->propinsi, $_area)){
                    array_push($arr_area, Propinsi::find($data->id_area)->propinsi);
                }
            }
            $per_direktorat_cancel = array_count_values($arr_direktorat);
            ksort($per_direktorat_cancel);
            $per_divisi_cancel = array_count_values($arr_divisi);
            ksort($per_divisi_cancel);
            $per_departemen_cancel = array_count_values($arr_departemen);
            ksort($per_departemen_cancel);
            $per_status_kerja_cancel = array_count_values($arr_status_kerja);
            ksort($per_status_kerja_cancel);
            $per_area_cancel = array_count_values($arr_area);
            ksort($per_area_cancel);
            // outstanding
            $query = TrxKebutuhanMaster::whereIn(DB::raw('YEAR(created_at)'), $tahun);
            $outstanding = $query->whereIn('status_pengajuan', $sts_outstanding)->get();
            // dd($outstanding);
            $arr = array();
            foreach($outstanding as $data){
                array_push($arr, $data->id);
            }
            // dd($arr);
            $n_outstanding = $outstanding->sum('jumlah');
            $arr_direktorat = [];
            $arr_divisi = [];
            $arr_departemen = [];
            $arr_status_kerja = [];
            $arr_area = [];
            foreach($outstanding as $data){
                $pengaju_property = json_decode($data->pengaju_property);
                if(in_array($pengaju_property->divisi, $_direktorat)){
                    array_push($arr_direktorat, $pengaju_property->divisi);
                }
                if(in_array($pengaju_property->dep, $_divisi)){
                    array_push($arr_divisi, $pengaju_property->dep);
                }
                if(in_array($pengaju_property->sub_dep, $_departemen)){
                    array_push($arr_departemen, $pengaju_property->sub_dep);
                }
                if(in_array(StatusKerja::find($data->id_status_kerja)->status_kerja, $_status_kerja)){
                    array_push($arr_status_kerja, StatusKerja::find($data->id_status_kerja)->status_kerja);
                }
                if(in_array(Propinsi::find($data->id_area)->propinsi, $_area)){
                    array_push($arr_area, Propinsi::find($data->id_area)->propinsi);
                }
            }
            $per_direktorat_outstanding = array_count_values($arr_direktorat);
            ksort($per_direktorat_outstanding);
            $per_divisi_outstanding = array_count_values($arr_divisi);
            ksort($per_divisi_outstanding);
            $per_departemen_outstanding = array_count_values($arr_departemen);
            ksort($per_departemen_outstanding);
            $per_status_kerja_outstanding = array_count_values($arr_status_kerja);
            ksort($per_status_kerja_outstanding);
            $per_area_outstanding = array_count_values($arr_area);
            ksort($per_area_outstanding);
            // return response()->json(
            $data = [
                'status' => 'success',
                'tahun' => $tahun,
                'all' => [
                    'l_trx' => $lable_data[0],
                    'l_orang' => $lable_data[1],
                    'n_trx' => count($all),
                    'n_orang' => (int)$n_all,
                ],
                'terpenuhi' => [
                    'l_trx' => $lable_data[2],
                    'l_orang' => $lable_data[3],
                    'n_trx' => count($terpenuhi),
                    'n_orang' => (int)$n_terpenuhi,
                ],
                'cancel' => [
                    'l_trx' => $lable_data[4],
                    'l_orang' => $lable_data[5],
                    'n_trx' => count($cancel),
                    'n_orang' => (int)$n_cancel,
                ],
                'outstanding' => [
                    'l_trx' => $lable_data[6],
                    'l_orang' => $lable_data[7],
                    'n_trx' => count($outstanding),
                    'n_orang' => (int)$n_outstanding,
                ],
                'per_direktorat' => [
                    'Terpenuhi'=>$per_direktorat_terpenuhi,
                    'Cancel'=>$per_direktorat_cancel,
                    'Outstanding'=>$per_direktorat_outstanding,
                ],
                'per_direktorat_sla' => [
                    'Ontime'=>$per_direktorat_terpenuhi_ontime,
                    'Overdue'=>$per_direktorat_terpenuhi_over,
                    'Cancel'=>$per_direktorat_cancel,
                    'On Progress'=>$per_direktorat_outstanding,
                ],
                'per_divisi' => [
                    'Terpenuhi'=>$per_divisi_terpenuhi,
                    'Cancel'=>$per_divisi_cancel,
                    'Outstanding'=>$per_divisi_outstanding,
                ],
                'per_divisi_sla' => [
                    'Ontime'=>$per_divisi_terpenuhi_ontime,
                    'Overdue'=>$per_divisi_terpenuhi_over,
                    'Cancel'=>$per_divisi_cancel,
                    'On Progress'=>$per_divisi_outstanding,
                ],
                'per_departemen' => [
                    'Terpenuhi'=>$per_departemen_terpenuhi,
                    'Cancel'=>$per_departemen_cancel,
                    'Outstanding'=>$per_departemen_outstanding,
                ],
                'per_departemen_sla' => [
                    'Ontime'=>$per_departemen_terpenuhi_ontime,
                    'Overdue'=>$per_departemen_terpenuhi_over,
                    'Cancel'=>$per_departemen_cancel,
                    'On Progress'=>$per_departemen_outstanding,
                ],
                'per_status_kerja' => [
                    'Terpenuhi'=>$per_status_kerja_terpenuhi,
                    'Cancel'=>$per_status_kerja_cancel,
                    'Outstanding'=>$per_status_kerja_outstanding,
                ],
                'per_area' => [
                    'Terpenuhi'=>$per_area_terpenuhi,
                    'Cancel'=>$per_area_cancel,
                    'Outstanding'=>$per_area_outstanding,
                ],
                'chart_type' => [
                    'type_direktorat'=>is_null($request->type_direktorat) ? 'bar' : $request->type_direktorat,
                    'type_divisi'=>is_null($request->type_divisi) ? 'bar' : $request->type_divisi,
                    'type_departemen'=>is_null($request->type_departemen) ? 'bar' : $request->type_departemen,
                    'type_status_kerja'=>is_null($request->type_status_kerja) ? 'bar' : $request->type_status_kerja,
                    'type_area'=>is_null($request->type_area) ? 'bar' : $request->type_area,
                ],
                'arr_direktorat_year' => $_arr_direktorat_year,
                'arr_direktorat_month_year' => $_arr_direktorat_month_year,
                'arr_direktorat_posisi' => $_arr_direktorat_posisi,
            ];
        }
        // end filter
        // dd(gettype($data), $data);
        // return $data;
        // end dashboard
        return view('formulir.kebutuhan.dashboard', compact('data','lable_data','select_tahun','select_direktorat','select_divisi','select_departemen','select_status_kerja','select_area','tahun','_direktorat','_divisi','_departemen','_status_kerja','_area','arr_select_tahun','arr_select_direktorat','arr_select_divisi','arr_select_departemen','arr_select_status_kerja','arr_select_area'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // // start default isi kolom kebutuhan_atribute pada tabel trx_tbl_recruitment_identitas
        // $trx = TrxRecruitmentIdentitas::all();
        // foreach($trx as $_trx){
        //     $data = TrxKebutuhanMaster::find($_trx->id_kebutuhan);
        //     $_data = json_encode([
        //         'id_pekerjaan'=>$data->id_pekerjaan,
        //         'id_level'=>$data->id_level,
        //         'id_area'=>$data->id_area,
        //         'id_kota'=>$data->id_kota,
        //         'atasan'=>$data->atasan,
        //         'atasan_property'=>$data->atasan_property,
        //         'id_perusahaan_kandidat'=>Divisi::find($data->id_divisi_kandidat)->kode_perusahaan,
        //         'id_divisi_kandidat'=>$data->id_divisi_kandidat,
        //         'id_dep_kandidat'=>$data->id_dep_kandidat,
        //         'id_sub_dep_kandidat'=>$data->id_sub_dep_kandidat,
        //     ]);
        //     if(in_array($_trx->id, [11460])){
        //         TrxRecruitmentIdentitas::where('id', $_trx->id)->update(['kebutuhan_atribute' => $_data]);
        //     }
        // }
        // // end
        // // start sync jika status_pengajuan=10 maka insert trx_tbl_kebutuhan_log
        // $trx_id = TrxKebutuhanMaster::where(['status_pengajuan'=>10])->get();
        // $trx_dengan_status_10 = TrxKebutuhanLog::whereIn('id_trx', $trx_id->pluck('id')->toArray())
        // ->where('id_status', 10)
        // ->pluck('id_trx')
        // ->toArray();
        // $trx = TrxKebutuhanLog::whereIn('id_trx', $trx_id->pluck('id')->toArray())
        // ->whereNotIn('id_trx', $trx_dengan_status_10)
        // ->distinct()
        // ->pluck('id_trx')
        // ->toArray();
        // foreach($trx as $value){
        //     $updated_at = Carbon::createFromFormat('d-m-Y H:i:s', $trx_id->where('id', $value)->first()?->updated_at)->format('Y-m-d H:i:s');
        //     DB::insert("INSERT INTO `trx_tbl_kebutuhan_log`(`id_trx`,`id_status`,`nik_pic`,`created_at`,`updated_at`) VALUES (?, ?, ?, ?, ?)", [
        //         $value,
        //         10,
        //         '01031808735',
        //         $updated_at,
        //         $updated_at,
        //     ]);
        // }
        // return response()->json(['trx' => $trx]);
        // // end
        $no = 0;
        $karyawan = Karyawan::select('nik', 'nama')->where(['is_aktif' => 1])->where('kode_level', '>=', 4)->whereNotIn('nik', ['Hco.all','IT.BM','IT Infras'])->orderBy('nama', 'asc')->get();
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $trx = TrxKebutuhanMaster::orderBy('id', 'desc')->get();
        if(Gate::allows('hc_nkp')){
            $trx = TrxKebutuhanMaster::whereIn('pengaju',$arr_nik_nkp)->orderBy('id', 'desc')->get();
        }
        // foreach ($trx as $value) {
        //     $trxM = TrxKebutuhanMaster::find($value->id);
        //     // $trxM->pengaju_property = $this->getNikProperty($value->pengaju);
        //     // $trxM->layer1_property = $this->getNikProperty($value->layer1);
        //     // $trxM->layer2_property = $this->getNikProperty($value->layer2);
        //     $pengaju_property = json_decode($trxM->pengaju_property, true);
        //     $id_sub_departemen = $pengaju_property['id_sub_departemen'];
        //     $id_dep_kandidat = SubDepartemen::find($id_sub_departemen)->kode_departemen;
        //     $id_divisi_kandidat = Departemen::find($id_dep_kandidat)->kode_divisi;
        //     $trxM->id_sub_dep_kandidat = $id_sub_departemen;
        //     $trxM->id_dep_kandidat = $id_dep_kandidat;
        //     $trxM->id_divisi_kandidat = $id_divisi_kandidat;
        //     // dd($id_sub_departemen);
        //     $trxM->update();
        // }
        if(auth()->user()->userrole == 'nhc'){
            $where = ['pengaju' => auth()->user()->nik];
            $trx = TrxKebutuhanMaster::where($where)->orderBy('id', 'desc')->get();
            // 
            $arr_id = [];
            foreach($trx as $_trx){
                array_push($arr_id, $_trx->id);
            }
            $where = ['nik_pic' => auth()->user()->nik];
            $trx = TrxKebutuhanLog::where($where)->orderBy('id_trx', 'desc')->get();
            foreach($trx as $_trx){
                array_push($arr_id, $_trx->id_trx);
            }
            // 
            $trx = TrxKebutuhanMaster::whereIn('id', $arr_id)->orderBy('id', 'desc')->get();
        }
        // filter parameter
        $arr_status_pengajuan = array();
        $arr_id_area = array();
        $arr_id_jenis_permintaan = array();
        foreach ($trx as $data){
            array_push($arr_status_pengajuan, $data->status_pengajuan);
            array_push($arr_id_area, $data->id_area);
            array_push($arr_id_jenis_permintaan, $data->id_jenis_permintaan);
        }
        // start dibatasi 30 hari terakhir dari created_at
        $trx = $trx->filter(function ($item) {
            return Carbon::parse($item->created_at)->gte(Carbon::now()->subDays(30));
        });
        $tanggal_mulai = '';
        $tanggal_akhir = '';
        // end
        $status_pengajuan = KebutuhanStatus::whereIn('id', $arr_status_pengajuan)->orderBy('id', 'asc')->get();
        $id_area = Propinsi::whereIn('id', $arr_id_area)->orderBy('order_by', 'asc')->get();
        $id_jenis_permintaan = JenisPermintaan::whereIn('id', $arr_id_jenis_permintaan)->orderBy('id', 'asc')->get();
        // start filter
        if($request->filter == true){
            $tanggal_mulai = $request->tanggal_mulai;
            $tanggal_akhir = $request->tanggal_akhir;
            $_status_pengajuan = $request->input('status_pengajuan', []);
            $_id_area = $request->input('id_area', []);
            $_id_jenis_permintaan = $request->input('id_jenis_permintaan', []);
            if(count($_status_pengajuan) > 0){
                $arr_status_pengajuan = $_status_pengajuan;
            }
            else{
                $arr_status_pengajuan = array();
            }
            if(count($_id_area) > 0){
                $arr_id_area = $_id_area;
            }
            else{
                $arr_id_area = array();
            }
            if(count($_id_jenis_permintaan) > 0){
                $arr_id_jenis_permintaan = $_id_jenis_permintaan;
            }
            else{
                $arr_id_jenis_permintaan = array();
            }
            // return response()->json(['trx' => $request->input('status_pengajuan', [])]);
            $trx = TrxKebutuhanMaster::whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->whereIn('status_pengajuan', $arr_status_pengajuan)->whereIn('id_area', $arr_id_area)->whereIn('id_jenis_permintaan', $arr_id_jenis_permintaan)->orderBy('id', 'desc')->get();
            if(Gate::allows('hc_nkp')){
                $trx = TrxKebutuhanMaster::whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->whereIn('pengaju',$arr_nik_nkp)->whereIn('status_pengajuan', $arr_status_pengajuan)->whereIn('id_area', $arr_id_area)->whereIn('id_jenis_permintaan', $arr_id_jenis_permintaan)->orderBy('id', 'desc')->get();
            }
            if(auth()->user()->userrole == 'nhc'){
                $where = ['pengaju' => auth()->user()->nik];
                $trx = TrxKebutuhanMaster::whereBetween('created_at', [Carbon::parse($tanggal_mulai)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()])->whereIn('status_pengajuan', $arr_status_pengajuan)->whereIn('id_area', $arr_id_area)->whereIn('id_jenis_permintaan', $arr_id_jenis_permintaan)->where($where)->orderBy('id', 'desc')->get();
                // 
                $arr_id = [];
                foreach($trx as $_trx){
                    array_push($arr_id, $_trx->id);
                }
                $where = ['nik_pic' => auth()->user()->nik];
                $trx = TrxKebutuhanLog::where($where)->orderBy('id_trx', 'desc')->get();
                foreach($trx as $_trx){
                    array_push($arr_id, $_trx->id_trx);
                }
                // 
                $trx = TrxKebutuhanMaster::whereIn('id', $arr_id)->whereIn('status_pengajuan', $arr_status_pengajuan)->whereIn('id_area', $arr_id_area)->whereIn('id_jenis_permintaan', $arr_id_jenis_permintaan)->orderBy('id', 'desc')->get();
            }
        }else{
            $arr_status_pengajuan = array();
            $arr_id_area = array();
            $arr_id_jenis_permintaan = array();
        }
        // end filter
        return view('formulir.kebutuhan.index', compact('trx', 'no', 'status_pengajuan', 'id_area', 'id_jenis_permintaan', 'arr_status_pengajuan', 'arr_id_area', 'arr_id_jenis_permintaan', 'karyawan', 'tanggal_mulai', 'tanggal_akhir'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $nik_pengaju = Crypt::decrypt($request->nik_pengaju);
        $pengaju = Karyawan::where(['nik' => $nik_pengaju])->first();
        // $pengaju = $pengaju->nama.' - '.$pengaju->nik;
        // 
        $pekerjaan = PekerjaanSub::orderBy('pekerjaan', 'asc')->get();
        $level = Level::orderBy('id', 'asc')->get();
        $status_kerja = StatusKerja::orderBy('status_kerja', 'asc')->get();
        $kelamin = Kelamin::orderBy('id', 'asc')->get();
        $area = Propinsi::orderBy('order_by', 'asc')->get();
        $kota = PropinsiKota::orderBy('kota', 'asc')->get();
        $jenis_permintaan = JenisPermintaan::orderBy('jenis', 'asc')->get();
        $jenjang_pendidikan = PendidikanJenjang::orderBy('id', 'asc')->get();
        $jurusan_pendidikan = PendidikanJurusan::orderBy('jurusan', 'asc')->get();
        $pengalaman_kerja = Pengalaman::orderBy('id', 'asc')->get();
        $pelatihan = PelatihanSub::orderBy('sub_pelatihan', 'asc')->get();
        $sertifikasi = SertifikasiSub::orderBy('sub_sertifikasi', 'asc')->get();
        $keterampilan = KeterampilanSub::orderBy('sub_keterampilan', 'asc')->get();
        $kepribadian = KepribadianSub::orderBy('sub_kepribadian', 'asc')->get();
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        $findim_project = MtrFindimProject::all();
        $findim_divisi = MtrFindimDivisi::all();
        $findim_departemen = MtrFindimDepartemen::all();
        $findim_subdepartemen = MtrFindimSubdepartemen::all();
        // atasan langsung dengan level minimal 3 = Supervisor (SPV)
        $atasan = Karyawan::select('nik', 'nama')->where(['is_aktif' => 1])->where('kode_level','>=',3)->orderBy('nama', 'asc')->get();
        return view('formulir.kebutuhan.insert', compact('karyawan', 'pekerjaan', 'level', 'status_kerja', 'kelamin', 'area', 'kota', 'jenis_permintaan', 'jenjang_pendidikan', 'jurusan_pendidikan', 'pengalaman_kerja', 'pelatihan', 'sertifikasi', 'keterampilan', 'kepribadian', 'findim_project', 'findim_divisi', 'findim_departemen', 'findim_subdepartemen', 'atasan', 'nik_pengaju', 'pengaju'));
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
        // karyawan masih di hardcode
        // Andika Cahya Fajar. R
        // $pengaju = '01031406353';
        // $pengaju = auth()->user()->nik;
        $pengaju = $request->nik_pengaju;
        $layer1 = $request->layer1;
        $layer2 = $request->layer2;
        $atasan = $request->atasan;
        // 
        $pengaju_property = $this->getNikProperty($pengaju);
        $layer1_property = $this->getNikProperty($layer1);
        $layer2_property = $this->getNikProperty($layer2);
        $atasan_property = $this->getNikProperty($atasan);
        // 
        $trxM = new TrxKebutuhanMaster;
        $trxM->pengaju = $pengaju;
        $trxM->layer1 = $layer1;
        $trxM->layer2 = $layer2;
        $trxM->atasan = $atasan;
        $trxM->id_divisi_kandidat = $request->kode_divisi;
        $trxM->id_dep_kandidat = $request->kode_departemen;
        $trxM->id_sub_dep_kandidat = $request->kode_sub_departemen;
        $trxM->pengaju_property = $pengaju_property;
        $trxM->layer1_property = $layer1_property;
        $trxM->layer2_property = $layer2_property;
        $trxM->atasan_property = $atasan_property;
        // 
        $id_pekerjaan = $request->id_pekerjaan;
        $trxM->id_pekerjaan = $id_pekerjaan;
        $trxM->tugas = $request->tugas;
        $trxM->wewenang = $request->wewenang;
        $trxM->tanggung_jawab = $request->tanggung_jawab;
        // 
        $trxM->id_level = $request->id_level;
        $trxM->id_status_kerja = $request->id_status_kerja;
        $trxM->waktu_kontrak = $request->waktu_kontrak;
        // 
        $jumlah = $request->jumlah;
        $trxM->jumlah = $jumlah;
        // 
        $trxM->id_kelamin = $request->id_kelamin;
        $trxM->id_area = $request->id_area;
        $trxM->id_kota = $request->id_kota;
        $trxM->id_jenis_permintaan = $request->id_jenis_permintaan;
        // $_nik_karyawan = $request->input('nik_karyawan', []);
        $nik_karyawan = explode(',', $request->_nik_karyawan_);
        $trxM->nik_karyawan = implode(';', $nik_karyawan);
        $nik_karyawan_property = array();
        foreach ($nik_karyawan as $value) {
            array_push($nik_karyawan_property, $this->getNikProperty($value));
        }
        $trxM->nik_karyawan_property = implode(';', $nik_karyawan_property);
        $trxM->penjelasan = $request->penjelasan;
        $trxM->id_rencana = $request->id_rencana;
        // 
        $id_jenjang_pendidikan = $request->id_jenjang_pendidikan;
        $trxM->id_jenjang_pendidikan = $id_jenjang_pendidikan;
        // 
        // $trxM->id_jurusan_pendidikan = $request->id_jurusan_pendidikan;
        if($request->has('id_jurusan_pendidikan')){
            $trxM->id_jurusan_pendidikan = implode(',', $request->id_jurusan_pendidikan);
        }
        $trxM->nilai_ipk = $request->nilai_ipk;
        $trxM->umur_minimum = $request->umur_minimum;
        $trxM->umur_maksimum = $request->umur_maksimum;
        $trxM->id_pengalaman = $request->id_pengalaman;
        // 
        if($request->has('id_pelatihan')){
            $trxM->id_pelatihan = implode(',', $request->id_pelatihan);
        }
        if($request->has('id_sertifikasi')){
            $trxM->id_sertifikasi = implode(',', $request->id_sertifikasi);
        }
        if($request->has('id_keterampilan')){
            $trxM->id_keterampilan = implode(',', $request->id_keterampilan);
        }
        if($request->has('id_kepribadian')){
            $trxM->id_kepribadian = implode(',', $request->id_kepribadian);
        }
        $trxM->tanggal = $request->tanggal;
        $trxM->keterangan_lain = $request->keterangan_lain;
        $trxM->id_penambahan = $request->id_penambahan;
        $status_pengajuan = 1;
        $trxM->status_pengajuan = $status_pengajuan;
        $trxM->findim_project = $request->findim_project;
        $trxM->findim_divisi = $request->findim_divisi;
        $trxM->findim_departemen = $request->findim_departemen;
        $trxM->findim_subdepartemen = $request->findim_subdepartemen;
        $trxM->save();
        $this->insertLog($trxM->id, $status_pengajuan, $pengaju);
        // 
        $_pengaju = json_decode($pengaju_property, true);
        $_layer1 = json_decode($layer1_property, true);
        // 
        for ($x = 0; $x < $jumlah; $x++) {
            $trxD = new TrxKebutuhanDetail;
            $trxD->id_kebutuhan = $trxM->id;
            // $trxD->id_pendidikan = $id_jenjang_pendidikan;
            $trxD->id_posisi = $id_pekerjaan;
            $trxD->id_divisi = $_pengaju['id_sub_departemen'];
            $trxD->save();
        }
        // 
        // parameter yang dipassing ke sendemail
        $mailData['title'] = 'Formulir Kebutuhan Karyawan';
        $mailData['data'] = ['nama'=>$_pengaju['nama'].'/'.$_pengaju['nik'], 'jabatan'=>$_pengaju['jabatan'], 'unit_kerja'=>$_pengaju['divisi'].'/'.$_pengaju['dep'].'/'.$_pengaju['sub_dep'], 'link'=> route('kebutuhan.show', [(new OpenDataController)->encryptWithKey($trxM->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($_layer1['nik']), 'id_status' => (new OpenDataController)->encryptWithKey($status_pengajuan), 'pic' => (new OpenDataController)->encryptWithKey('layer1')]), 'id_trx' => $trxM->id ];
        $mailData['filename'] = 'emails.kebutuhan0';
        $mailData['to'] = $_layer1['email'];
        $todolist = ['id_trx' => $trxM->id, 'nik' => $_layer1['nik'], 'id_status' => $status_pengajuan, 'pic' => 'layer1'];
        $this->insertToDoList($todolist, $mailData);
        $this->sendEmail($mailData);
        // 
        return redirect('kebutuhan')->with('msg', 'Data is saved successfully.');
    }

    public function getNikProperty($nik)
    {
        $where = ['nik' => $nik, 'is_aktif' => 1];
        $data = Karyawan::select('mtr_tbl_karyawan.nik', 'mtr_tbl_karyawan.nama', 'mtr_tbl_karyawan.email', 'mtr_tbl_karyawan.telepon1', 'mtr_tbl_perusahaan.nama as perusahaan', 'mtr_tbl_divisi.nama as divisi', 'mtr_tbl_departemen.nama as dep', 'mtr_tbl_sub_departemen.nama as sub_dep', 'mtr_tbl_level.level as level', 'mtr_tbl_jabatan.jabatan as jabatan', 'mtr_tbl_level.id as id_level', 'mtr_tbl_sub_departemen.id as id_sub_departemen')
            ->leftJoin('mtr_tbl_perusahaan', 'mtr_tbl_perusahaan.id', '=', 'mtr_tbl_karyawan.kode_perusahaan')
            ->leftJoin('mtr_tbl_divisi', 'mtr_tbl_divisi.id', '=', 'mtr_tbl_karyawan.kode_divisi')
            ->leftJoin('mtr_tbl_departemen', 'mtr_tbl_departemen.id', '=', 'mtr_tbl_karyawan.kode_departemen')
            ->leftJoin('mtr_tbl_sub_departemen', 'mtr_tbl_sub_departemen.id', '=', 'mtr_tbl_karyawan.kode_sub_departemen')
            ->leftJoin('mtr_tbl_level', 'mtr_tbl_level.id', '=', 'mtr_tbl_karyawan.kode_level')
            ->leftJoin('mtr_tbl_jabatan', 'mtr_tbl_jabatan.id', '=', 'mtr_tbl_karyawan.kode_jabatan')
            ->where($where)->first();
        return json_encode($data);
    }

    public function insertLog($id_trx, $id_status, $nik_pic)
    {
        $data = new TrxKebutuhanLog;
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

    public function deleteToDoList($where)
    {
        // $trx = ToDoList::where($where)->first();
        // if (!empty($trx)) {
        //     $trx->delete();
        // }
        $trx = ToDoList::where($where)->delete();
    }

    public function sendEmail($mailData)
    {
        $ccEmails = explode(',', MtrUtility::find(11)->informasi);
        Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
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
        $linkAction = route('kebutuhan.update', $id);
        $id = (new OpenDataController)->decryptWithKey($id);
        $where = ['id_trx' => $id];
        $history = TrxKebutuhanLog::where($where)->orderBy('created_at', 'desc')->get();
        $trxM = TrxKebutuhanMaster::find($id);
        // 
        // $where = ['id_trx' => $id];
        // $trxD = TrxKebutuhanDetail::where($where)->get();
        // ambil property para pihak
        $pengaju = json_decode($trxM->pengaju_property, true);
        $layer1 = json_decode($trxM->layer1_property, true);
        $layer2 = json_decode($trxM->layer2_property, true);
        // 
        $arr_nik_karyawan = array();
        if(!empty($trxM->nik_karyawan)){
            $arr = explode(";", $trxM->nik_karyawan_property);
            foreach ($arr as $value){
                array_push($arr_nik_karyawan, json_decode($value, true));
            }
        }
        // return response()->json(['arr_nik_karyawan' => $arr_nik_karyawan]);
        // 
        $pelatihan = '';
        if(!empty($trxM->id_pelatihan)){
            $arr = explode(",", $trxM->id_pelatihan);
            $pelatihan = PelatihanSub::whereIn('id', $arr)->orderBy('sub_pelatihan', 'asc')->get();
        }
        $sertifikasi = '';
        if(!empty($trxM->id_sertifikasi)){
            $arr = explode(",", $trxM->id_sertifikasi);
            $sertifikasi = SertifikasiSub::whereIn('id', $arr)->orderBy('sub_sertifikasi', 'asc')->get();
        }
        $keterampilan = '';
        if(!empty($trxM->id_keterampilan)){
            $arr = explode(",", $trxM->id_keterampilan);
            $keterampilan = KeterampilanSub::whereIn('id', $arr)->orderBy('sub_keterampilan', 'asc')->get();
        }
        $kepribadian = '';
        if(!empty($trxM->id_kepribadian)){
            $arr = explode(",", $trxM->id_kepribadian);
            $kepribadian = KepribadianSub::whereIn('id', $arr)->orderBy('sub_kepribadian', 'asc')->get();
        }
        // 
        $view = $request->view;
        $pic = $request->pic;
        $nik = $request->nik;
        $id_status = $request->id_status;
        $view = (new OpenDataController)->decryptWithKey($view);
        if(!empty($pic)){
            $pic = (new OpenDataController)->decryptWithKey($pic);
        }
        if(!empty($nik)){
            $nik = (new OpenDataController)->decryptWithKey($nik);
        }
        if(!empty($id_status)){
            $id_status = (new OpenDataController)->decryptWithKey($id_status);
        }
        $where = ['id_trx' => $id, 'nik_pic' => $nik];
        // 2 = Approved by L1
        // 5 = Rejected by L1
        $nLayer1 = TrxKebutuhanLog::where($where)->whereIn('id_status', [2, 5])->count();
        // 7 = Rejected by L2
        // 9 = Approved by L2 to HC
        $nLayer2 = TrxKebutuhanLog::where($where)->whereIn('id_status', [7, 9])->count();
        switch ($view) {
            case 'approval':
                // layer1
                if ($pic == 'layer1' && $nik == $trxM->layer1 && $nLayer1 == 0 && $id_status == 1) {
                    $approve = 2;
                    $reject = 5;
                    return view('emails.kebutuhan', compact('trxM', 'arr_nik_karyawan', 'nik', 'pic', 'approve', 'reject', 'pengaju', 'linkAction', 'pelatihan', 'sertifikasi', 'keterampilan', 'kepribadian', 'history'));
                } 
                // layer2
                else if ($pic == 'layer2' && $nik == $trxM->layer2 && $nLayer2 == 0 && $id_status == 2) {
                    $approve = 9;
                    $reject = 7;
                    return view('emails.kebutuhan', compact('trxM', 'arr_nik_karyawan', 'nik', 'pic', 'approve', 'reject', 'pengaju', 'linkAction', 'pelatihan', 'sertifikasi', 'keterampilan', 'kepribadian', 'history'));
                } 
                // selainnya
                else {
                    return view('emails.kebutuhan_ok');
                }
                break;
            case 'detail':
                $this->authorize('auth');
                $where = ['id_trx' => $id];
                $history = TrxKebutuhanLog::where($where)->orderBy('created_at', 'desc')->get();
                return view('formulir.kebutuhan.view', compact('trxM', 'arr_nik_karyawan', 'pengaju', 'layer1', 'layer2', 'pelatihan', 'sertifikasi', 'keterampilan', 'kepribadian', 'history'));
                break;
            case 'doc':
                $this->authorize('hc');
                $no = 0;
                $where = ['id_kebutuhan' => $id];
                $trx = TrxKebutuhanDetail::where($where)->orderBy('id', 'desc')->get();
                return view('formulir.kebutuhan.doc_index', compact('trx', 'no'));
                break;
            case 'doc_file':
                echo 'ok';
                // $no = 0;
                // $where = ['id_kebutuhan' => $id];
                // $trx = TrxKebutuhanDetail::where($where)->orderBy('id', 'desc')->get();
                // return view('formulir.kebutuhan.doc_index', compact('trx', 'no'));
                break;
            case 'pdf':
                $this->authorize('auth');
                // print to pdf
                $waktu = date('YmdHis');
                $no = 0;
                // 
                // start
                $htmlLog = '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0"><tr>';
                // pengaju
                $where = ['id_trx' => $id, 'nik_pic' => $trxM->pengaju, 'id_status' => 1];
                $kebutuhanLog = TrxKebutuhanLog::where($where)->orderBy('id', 'desc')->first();
                $htmlLog .= '<td>Mengajukan<br/><img src="' . public_path('assets/images/matta/requested_new.png') . '" height="30" /><br/>' . $pengaju['nama'] . '<br/>' . $pengaju['jabatan'] . '<br/>' . $kebutuhanLog->created_at . '</td>';
                // layer1
                // 2 = Approved by L1
                // 5 = Rejected by L1
                $where = ['id_trx' => $id, 'nik_pic' => $trxM->layer1];
                $kebutuhanLog = TrxKebutuhanLog::where($where)->whereIn('id_status', [2, 5])->orderBy('id', 'desc')->first();
                if (!empty($kebutuhanLog)) {
                    if($kebutuhanLog->id_status == 2){
                        $htmlLog .= '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $layer1['nama'] . '<br/>' . $layer1['jabatan'] . '<br/>' . $kebutuhanLog->created_at . '</td>';
                    }else{
                        $htmlLog .= '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $layer1['nama'] . '<br/>' . $layer1['jabatan'] . '<br/>' . $kebutuhanLog->created_at . '</td>';
                    }
                }
                // layer2
                // 7 = Rejected by L2
                // 9 = Approved by L2 to HC
                $where = ['id_trx' => $id, 'nik_pic' => $trxM->layer2];
                $kebutuhanLog = TrxKebutuhanLog::where($where)->whereIn('id_status', [7, 9])->orderBy('id', 'desc')->first();
                if (!empty($kebutuhanLog)) {
                    if($kebutuhanLog->id_status == 9){
                        $htmlLog .= '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $layer2['nama'] . '<br/>' . $layer2['jabatan'] . '<br/>' . $kebutuhanLog->created_at . '</td>';
                    }else{
                        $htmlLog .= '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $layer2['nama'] . '<br/>' . $layer2['jabatan'] . '<br/>' . $kebutuhanLog->created_at . '</td>';
                    }
                }
                // hco
                // 3 = Reject Checked by HC
                // 10 = Done by HC
                // 28 = Terminate Cancel by HC
                $where = ['id_trx' => $id];
                $kebutuhanLog = TrxKebutuhanLog::where($where)->whereIn('id_status', [3, 10, 28])->orderBy('id', 'desc')->first();
                if (!empty($kebutuhanLog)) {
                    $htmlLog .= '<td>Mengetahui<br/><img src="' . public_path('assets/images/matta/informed_new.png') . '" height="30" /><br/>' . $kebutuhanLog->_pic->nama . '<br/>' . $kebutuhanLog->_pic->_jabatan->jabatan . '<br/>' . $kebutuhanLog->created_at . '</td>';
                }
                // finish
                $htmlLog .= '</tr></table>';
                // 
                $filename = $waktu . '_formulir_kebutuhan_karyawan.pdf';
                $view = \View::make('formulir.kebutuhan.pdf', compact('trxM', 'arr_nik_karyawan', 'pengaju', 'pelatihan', 'sertifikasi', 'keterampilan', 'kepribadian', 'htmlLog'));
                $html = $view->render();
                $pdf = new TCPDF;
                $pdf::SetTitle('Formulir Kebutuhan Karyawan');
                $pdf::SetFont('times', '', 10, '', true);
                $pdf::AddPage();
                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output(public_path($filename), 'I');
                return response()->download(public_path($filename));
                break;
            case 'antrian':
                // print to pdf
                // $filename = 'nomor_antrian.pdf';
                // $pdf = new TCPDF;
                // $pdf::SetTitle('nomor_antrian');
                // $pdf::SetFont('times', '', 11, '', true);
                // $no = 1;
                // $jumlah = 250;
                // for($i = 1; $i<=$jumlah;$i++){
                //     $view = \View::make('formulir.kebutuhan.antrian', compact('no'));
                //     $html = $view->render();
                //     $pdf::AddPage('L', 'A4');
                //     $pdf::writeHTML($html, true, false, true, false, '');
                //     $no = ($i*6)+1;
                // }
                // // 
                // $pdf::Output(public_path($filename), 'I');
                // return response()->download(public_path($filename));
                // break;
            default:
                return view('emails.kebutuhan_ok');
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
        $this->authorize('hc');
        $id = (new OpenDataController)->decryptWithKey($id);
        $trxM = TrxKebutuhanMaster::find($id);
        // 
        $status_pengajuan = KebutuhanStatus::whereIn('id', [3,10,28,$trxM->status_pengajuan])->orderBy('id', 'asc')->get();
        // 
        // ambil property para pihak
        $pengaju = json_decode($trxM->pengaju_property, true);
        $layer1 = json_decode($trxM->layer1_property, true);
        $layer2 = json_decode($trxM->layer2_property, true);
        $pekerjaan = PekerjaanSub::orderBy('pekerjaan', 'asc')->get();
        $level = Level::orderBy('id', 'asc')->get();
        return view('formulir.kebutuhan.update', compact('trxM', 'pengaju', 'layer1', 'layer2', 'status_pengajuan', 'pekerjaan', 'level'));
        // } else {
        //     abort('404');
        // }
    }
    
    public function edit_kolom(Request $request, $id)
    {
        $id = (new OpenDataController)->decryptWithKey($id);
        $field = $request->field;
        // dd($request->all(), $id);
        $trxM = TrxKebutuhanMaster::find($id);
        $where = ['id_kebutuhan' => $id];
        $history = TrxKebutuhanMasterEdit::where($where)->orderBy('created_at', 'desc')->get();
        return view('formulir.kebutuhan.edit_kolom', compact('trxM', 'field', 'history') );
        // 
    }
    
    public function get_atribute(Request $request, $id)
    {
        dd($request->all(), $id);
    }
    
    // public function post_atribute(Request $request, $id)
    // {
    //     $id = (new OpenDataController)->decryptWithKey($id);
    //     // dd($request->all(), $id);
    //     // update TrxKebutuhanMaster
    //     $trxM = TrxKebutuhanMaster::find($id);
    //     $trxM->id_pekerjaan = $request->id_pekerjaan;
    //     $trxM->id_level = $request->id_level;
    //     $trxM->update();
    //     // update TrxRecruitmentNegosiasi
    //     $id_identitas = TrxRecruitmentIdentitas::where('id_kebutuhan', $id)->pluck('id');
    //     TrxRecruitmentNegosiasi::whereIn('id_identitas', $id_identitas)->update(['posisi' => PekerjaanSub::find($request->id_pekerjaan)->pekerjaan]);
    //     return redirect('kebutuhan')->with('msg', 'Data is saved successfully.');
    // }
    
    public function post_kolom(Request $request, $id)
    {
        $id = (new OpenDataController)->decryptWithKey($id);
        // dd($request->all(), $id);
        $trxM = TrxKebutuhanMaster::find($id);
        // check jumlah data TrxKebutuhanDetail yang dapat disesuaikan
        $check = false;
        $count = TrxKebutuhanDetail::where(['id_kebutuhan'=>$id])->whereNull('nama_karyawan')->count();
        $sebelum = $trxM->jumlah;
        $sesudah = $request->jumlah;
        $selisih = abs($sebelum - $sesudah);
        // data TrxKebutuhanDetail bertambah
        if($sesudah > $sebelum){
            $check = true;
            $acuan = TrxKebutuhanDetail::where(['id_kebutuhan'=>$id])->first();
            for ($x = 0; $x < $selisih; $x++) {
                $trxD = new TrxKebutuhanDetail;
                $trxD->id_kebutuhan = $acuan->id_kebutuhan;
                $trxD->id_posisi = $acuan->id_posisi;
                $trxD->id_divisi = $acuan->id_divisi;
                $trxD->save();
            }
        }
        // data TrxKebutuhanDetail berkurang
        else if($sebelum > $sesudah){
            // check apakah jumlah pengurangan tersedia
            if($count >= $selisih){
                $check = true;
                $acuan = TrxKebutuhanDetail::where(['id_kebutuhan'=>$id])->whereNull('nama_karyawan')->orderBy('id', 'asc')->get();
                for ($x = 0; $x < $selisih; $x++) {
                    TrxKebutuhanDetail::find($acuan[$x]->id)->delete();
                }
            }
        }
        // 
        if($check){
            $data = new TrxKebutuhanMasterEdit;
            $data->id_kebutuhan = $id;
            $data->kolom = $request->field;
            $data->sebelum = $sebelum;
            $data->sesudah = $sesudah;
            $data->keterangan = $request->keterangan;
            $data->pic = auth()->user()->nik;
            $data->save();
            // 
            $trxM->jumlah = $sesudah;
            // check apakah perubahan jumlah sudah sama dengan jumlah kandidat yang join dari recruitment jika sama maka status menjadi 
            // 10=Done by P&C
            $jumlah_present = TrxKebutuhanDetail::where(['id_kebutuhan' => $id])->whereNotNull('id_identitas')->get();
            if($jumlah_present->count() == $sesudah){
                $trxM->status_pengajuan = 10;
                $this->insertLog($id, 10, auth()->user()->nik);
            }
            $trxM->update();
            // 
            return redirect('kebutuhan')->with('msg', 'Data is saved successfully.');
        }else{
            return redirect('kebutuhan')->with('error', 'No data is saved successfully.');
        }
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
        // masih di hardcode pic hco -> Khatrin Rismasany
        // $hco = '01031908799';
        // $hco = auth()->user()->nik;
		// MtrUtility::find(11)->informasi
		// $hco = 01031808735 = Sofyan Farid Ma'Arif
        $id = (new OpenDataController)->decryptWithKey($id);
		$hco = MtrUtility::find(20)->informasi;
        $trxM = TrxKebutuhanMaster::find($id);
        $pengaju = json_decode($trxM->pengaju_property, true);
        $layer1 = json_decode($trxM->layer1_property, true);
        $layer2 = json_decode($trxM->layer2_property, true);
        // 
        $pic = $request->pic;
        $nik = $request->nik;
        $status_pengajuan = $request->status_pengajuan;
        $catatan = $request->catatan;
        // 2 = Approved by L1
        // 5 = Rejected by L1
        if (($status_pengajuan == 2 || $status_pengajuan == 5) && $pic == 'layer1' && $nik == $trxM->layer1) {
            $trxM->status_pengajuan = $status_pengajuan;
            $trxM->catatan_layer1 = $catatan;
            $trxM->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.kebutuhan0']);
            $this->insertLog($id, $status_pengajuan, $nik);
            // start
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Formulir Kebutuhan Karyawan';
            // 5 = Rejected by L1
            if($status_pengajuan == 5){
                $mailData['filename'] = 'emails.kebutuhan1';
                $mailData['data'] = ['nama' => $pengaju['nama'], 'jabatan' => $pengaju['jabatan'], 'unit_kerja' => $pengaju['divisi'] . '/' . $pengaju['dep'] . '/' . $pengaju['sub_dep'], 'id_trx' => $id];
                $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
                $this->sendEmail($mailData);
            }
            // 2 = Approved by L1
            else if($status_pengajuan == 2){
                $mailData['data'] = ['nama'=>$pengaju['nama'].'/'.$pengaju['nik'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'link'=> route('kebutuhan.show', [(new OpenDataController)->encryptWithKey($trxM->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($layer2['nik']), 'id_status' => (new OpenDataController)->encryptWithKey($status_pengajuan), 'pic' => (new OpenDataController)->encryptWithKey('layer2')]), 'id_trx' => $trxM->id ];
                $mailData['filename'] = 'emails.kebutuhan0';
                $mailData['to'] = $layer2['email'];
                $todolist = ['id_trx' => $trxM->id, 'nik' => $layer2['nik'], 'id_status' => $status_pengajuan, 'pic' => 'layer2'];
                $this->insertToDoList($todolist, $mailData);
                $this->sendEmail($mailData);
            }
            return view('emails.kebutuhan_ok');
            // finish
        }
        // 7 = Rejected by L2
        // 9 = Approved by L2 to HC
        else if (($status_pengajuan == 7 || $status_pengajuan == 9) && $pic == 'layer2' && $nik == $trxM->layer2) {
            $trxM->status_pengajuan = $status_pengajuan;
            $trxM->catatan_layer2 = $catatan;
            $trxM->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.kebutuhan0']);
            $this->insertLog($id, $status_pengajuan, $nik);
            // start
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Formulir Kebutuhan Karyawan';
            $mailData['filename'] = 'emails.kebutuhan1';
            $mailData['data'] = ['nama' => $pengaju['nama'], 'jabatan' => $pengaju['jabatan'], 'unit_kerja' => $pengaju['divisi'] . '/' . $pengaju['dep'] . '/' . $pengaju['sub_dep'], 'id_trx' => $id];
            $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
            $this->sendEmail($mailData);
            return view('emails.kebutuhan_ok');
            // finish
        }
        // 3 = Reject Checked by HC
        // 10 = Done by HC
        // 28 = Terminate Cancel by HC
        else if ($status_pengajuan == 3 || $status_pengajuan == 10 || $status_pengajuan == 28) {
            $trxM->status_pengajuan = $status_pengajuan;
            $trxM->keterangan_lain = $request->keterangan_lain;
            // 
            $trxM->update();
            $this->insertLog($id, $status_pengajuan, $hco);
            // 
            return redirect('kebutuhan')->with('msg', 'Data is saved successfully.');
        }
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

    public function kota($id=0)
    {
        // ambil kota sesuai id_propinsi
        $where = ['id_propinsi' => $id];
        $kota = PropinsiKota::where($where)->orderBy('kota', 'asc')->get();
        return response()->json(['kota' => $kota]);
    }

    public function layer($id=0)
    {
        // ambil parameter karyawan dan semua layer
        $where = ['is_aktif' => 1, 'nik'=> $id];
        $layer = Karyawan::select('layer1', 'layer2', 'layer3')->where($where)->first();
        // approval dengan level minimal 4 = Assistant Manager
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->where('kode_level','>=',4)->orderBy('nama', 'asc')->get();
        return response()->json(['layer' => $layer, 'karyawan' => $karyawan]);
    }
}
