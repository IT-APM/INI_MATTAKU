<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Attendance;
use App\Models\Karyawan;
use App\Models\KaryawanBulanan;
use App\Models\TrxPenilaian;
use App\Models\TrxPenilaianAuto;
use App\Models\TrxNonaktifAuto;
use App\Models\TrxCuti;
use App\Models\TrxCutiLog;
use App\Models\MtrUtility;
use App\Models\Holiday;
use App\Models\ToDoList;
use App\Models\ToDoListLog;
use App\Models\TrxBirthday;
use App\Models\TrxFingePrint;
use App\Models\TrxFingePrintMatta;
use App\Models\WorkingGroup;
use App\Models\TrxKebutuhanMaster;
use App\Models\TrxKebutuhanLog;
use App\Models\TrxRecruitmentIdentitas;
use App\Models\TrxRecruitmentLog;
use App\Models\Divisi;
use App\Models\Departemen;
use App\Models\SubDepartemen;
use App\Models\Jabatan;
// 
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Storage;
use DB;
use URL;
// 
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class DataCleansingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // check apakah sla kebutuhan karyawan lebih dari 14 hari
        $this->SlaKebutuhanKaryawan();
        // dijalankan hanya di tanggal 01 setiap bulannya
        if(explode('-', Carbon::now()->format('Y-m-d'))[2] == '01'){
            $this->PerubahanDataKaryawanBulanan();
            // dd('done');
        }
        // $data = $this->PerubahanDataKaryawanBulanan();
        // return response()->json( [ 'success' => true, 'layer1' => $data ], 200 );
        // dijalankan sendiri karena lama
        // $this->CleansingToDoList();
        $filename = ['emails.penilaian0', 'emails.penilaian0_outsourcing'];
        $this->getToDoList($filename);
        // tidak digunakan karena sudah diberikan ketika pengajuan cuti
        // $this->getDataCuti();
        $this->KaryawanNonAktif();
        $this->CleansingDataAbsensi();
        $this->PenilaianAuto();
        $this->PenilaianAutoOutsourcing();
        // 4 = Disetujui oleh Layer 1
        // tidak digunakan karena hco kembali dilibatkan
        // $trx = $this->GetTrxCuti();
        // foreach($trx as $value){
        //     $_trx = TrxCuti::find($value->id);
        //     // 6 = Selesai
        //     $_trx->status_cuti = 6;
        //     $_trx->update();
        //     $this->InsertTrxCutiLog($value->id);
        // }
        $date_from = date("Y-m-d");
        $date_to = date("Y-m-d");
        $trx = $this->InsertToAttendance($date_from, $date_to);
        // dipindahkan ke C:\xampp\htdocs\matta\app\Http\Controllers\TrxBirthdayController.php
        // karena dijalankan setiap jam 7 pagi
        // $this->getBirthday();
        // start rekon data finger print
        // $yesterday = Carbon::now()->subDays(3)->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $start_date = $yesterday;
        $end_date = $yesterday;
        // $start_date = '2026-06-01';
        // $end_date = '2026-08-18';
        $trx = TrxFingePrint::select(
            DB::raw('MIN(trx_tbl_fp_data.id) as id_clock_in'),
            DB::raw('CAST(CASE WHEN COUNT(*) > 1 THEN MAX(trx_tbl_fp_data.id) ELSE NULL END AS UNSIGNED) as id_clock_out'),
            'trx_tbl_fp_data.id_nip',
            'mtr_tbl_karyawan.nik',
            'mtr_tbl_karyawan.nama',
            'mtr_tbl_karyawan.wg_id',
            DB::raw('DATE(trx_tbl_fp_data.waktu) as tanggal'),
            DB::raw('MIN(trx_tbl_fp_data.waktu) as clock_in'),
            DB::raw('CASE WHEN COUNT(*) > 1 THEN MAX(trx_tbl_fp_data.waktu) ELSE NULL END as clock_out')
        )
        ->leftJoin('mtr_tbl_karyawan', 'mtr_tbl_karyawan.id_fp', '=', 'trx_tbl_fp_data.id_nip')
        ->whereBetween(DB::raw('DATE(trx_tbl_fp_data.waktu)'), [$start_date, $end_date])
        ->where(['mtr_tbl_karyawan.wg_id' => 1])
        // ->whereIn('trx_tbl_fp_data.id_nip', [421,422,424,414,425,415,420,423,426,416,428,417,429,418,412,419,411,427,409])
        ->groupBy('trx_tbl_fp_data.id_nip', DB::raw('DATE(trx_tbl_fp_data.waktu)'))
        ->orderBy('tanggal')
        ->orderBy('trx_tbl_fp_data.id_nip')
        ->get();
        // dd($trx, $start_date);
        $wg = WorkingGroup::find(1);
        $start_clock_time = Carbon::parse($wg->start_clockin)->format('H:i:s');
        // return response()->json( [ 'start_date' => $start_date, 'end_date' => $end_date, 'trx' => $trx ], 200 );
        foreach($trx as $_trx){
            // CLOCK-IN
            $ci_fp = $_trx->clock_in;
            $clock_in = $ci_fp;
            $att_in = Attendance::where(['att_date'=>$_trx->tanggal, 'att_nik'=>$_trx->nik, 'att_type'=>'Clock-in', 'att_data_src'=>'Regular', 'wg_id'=>1]);
            // $data = [];
            // cek apakah ada ci matta, jika ada
            // contoh 
            // $nik = '01031908799';
            // if($att_in->exists() && $_trx->nik == $nik){
            if($att_in->exists()){
                $ci_matta = $att_in->orderBy('id', 'asc')->first()->att_timestamp;
                $clock_in = $ci_matta;
                // jika ci fp lebih awal dari ci matta maka gunakan ci fp
                if($ci_fp < $ci_matta){
                    $clock_in = $ci_fp;
                    $clock_in_time = Carbon::parse($clock_in)->format('H:i:s');
                    // jika clock_in_time lebih awal dari start_clock_time maka ganti time-nya dengan start_clock_time
                    if ($clock_in_time < $start_clock_time) {
                        $clock_in = Carbon::parse($clock_in)->format('Y-m-d').' '.$start_clock_time;
                    }
                    // kemudian ci matta dicopy ke TrxFingePrintMatta sebagai log
                    $data = $att_in->orderBy('id', 'asc')->first()->toArray();
                    $data['att_ref_address'] = 'edit';
                    $data['created_at'] = Carbon::createFromFormat('d-m-Y H:i:s', $data['created_at'])->format('Y-m-d H:i:s');
                    $data['updated_at'] = Carbon::createFromFormat('d-m-Y H:i:s', $data['updated_at'])->format('Y-m-d H:i:s');
                    $data['id_fp_trx'] = $_trx->id_clock_in;
                    TrxFingePrintMatta::insert($data);
                    // kemudian update Attendance
                    // $data = [];
                    $data = $att_in->orderBy('id', 'asc')->first()->toArray();
                    $data['att_timestamp'] = $clock_in;
                    $data['att_long'] = NULL;
                    $data['att_lat'] = NULL;
                    $data['att_address'] = NULL;
                    $data['att_status'] = 'Book-in';
                    // refer to cm 559
                    if(Carbon::parse($clock_in)->format('H:i:s') > '09:01:00'){
                        $data['att_status'] = 'Late-in';
                    }
                    $data['att_ref_address'] = 'edit';
                    $data['created_at'] = $clock_in;
                    $data['updated_at'] = $clock_in;
                    $data['id_fp_trx'] = $_trx->id_clock_in;
                    Attendance::where('id', $data['id'])->update($data);
                    // dd($data);
                }else{
                    // maka gunakan ci matta (do nothing)
                    // kemudian ci matta dicopy ke TrxFingePrintMatta sebagai log
                    // $data = [];
                    $data = $att_in->orderBy('id', 'asc')->first()->toArray();
                    $data['att_ref_address'] = 'copy';
                    $data['created_at'] = Carbon::createFromFormat('d-m-Y H:i:s', $data['created_at'])->format('Y-m-d H:i:s');
                    $data['updated_at'] = Carbon::createFromFormat('d-m-Y H:i:s', $data['updated_at'])->format('Y-m-d H:i:s');
                    $data['id_fp_trx'] = $_trx->id_clock_in;
                    TrxFingePrintMatta::insert($data);
                }
                // return response()->json( [ 'clock_in' => $clock_in, 'trx' => $trx->where('nik', $nik), 'att' => $att_in->get() ], 200 );
            }
            // jika tidak ada ci matta maka $clock_in = $ci_fp;
            else{
                $clock_in_time = Carbon::parse($clock_in)->format('H:i:s');
                // jika clock_in_time lebih awal dari start_clock_time maka ganti time-nya dengan start_clock_time
                if ($clock_in_time < $start_clock_time) {
                    $clock_in = Carbon::parse($clock_in)->format('Y-m-d').' '.$start_clock_time;
                }
                $data = New Attendance;
                $data->att_date = $_trx->tanggal;
                $data->att_timestamp = $clock_in;
                $data->att_nik = $_trx->nik;
                $data->att_type = 'Clock-in';
                $data->att_data_src = 'Regular';
                $data->att_wd_ot_id = 0;
                $data->att_status = 'Book-in';
                // refer to cm 559
                if(Carbon::parse($clock_in)->format('H:i:s') > '09:01:00'){
                    $data->att_status = 'Late-in';
                }
                $data->att_property = $this->getNikProperty($_trx->nik);
                $data->wg_id = 1;
                $data->att_ref_address = 'copy';
                $data->id_fp_trx = $_trx->id_clock_in;
                $data->save();
            }
            // CLOCK-OUT
            $att_out = Attendance::where(['att_date'=>$_trx->tanggal, 'att_nik'=>$_trx->nik, 'att_type'=>'Clock-out', 'att_data_src'=>'Regular', 'wg_id'=>1]);
            // $data = [];
            // jika ada co matta maka gunakan co matta
            // kemudian dicopy ke TrxFingePrintMatta sebagai log
            if($att_out->exists()){
                $data = $att_out->orderBy('id', 'asc')->first()->toArray();
                $data['att_ref_address'] = 'copy';
                $data['created_at'] = Carbon::createFromFormat('d-m-Y H:i:s', $data['created_at'])->format('Y-m-d H:i:s');
                $data['updated_at'] = Carbon::createFromFormat('d-m-Y H:i:s', $data['updated_at'])->format('Y-m-d H:i:s');
                $data['id_fp_trx'] = $_trx->id_clock_out;
                TrxFingePrintMatta::insert($data);
            }
            // jika tidak ada co matta dan ada co fp maka gunakan co fp
            if(!$att_out->exists() && !empty($_trx->clock_out)){
                // ambil ci matta
                $att_in = Attendance::where(['att_date'=>$_trx->tanggal, 'att_nik'=>$_trx->nik, 'att_type'=>'Clock-in', 'att_data_src'=>'Regular', 'wg_id'=>1]);
                $clock_in = $att_in->orderBy('id', 'asc')->first()->att_timestamp;
                $clock_out = $_trx->clock_out;
                // check apakah durasi 9 jam
                $clockIn = Carbon::parse($clock_in);
                $clockOut = Carbon::parse($clock_out);
                $diffHours = $clockIn->diffInHours($clockOut);
                $data = New Attendance;
                $data->att_date = $_trx->tanggal;
                $data->att_timestamp = $_trx->clock_out;
                $data->att_nik = $_trx->nik;
                $data->att_type = 'Clock-out';
                $data->att_data_src = 'Regular';
                $data->att_wd_ot_id = 0;
                $data->att_status = 'Book-out';
                if($diffHours < 9){
                    $data->att_status = 'Early-out';
                }
                $data->att_property = $this->getNikProperty($_trx->nik);
                $data->wg_id = 1;
                $data->att_ref_address = 'copy';
                $data->id_fp_trx = $_trx->id_clock_out;
                $data->save();
            }
        }
        // end rekon
        return response()->json( [ 'success' => true ], 200 );
    }

    public function SlaKebutuhanKaryawan()
    {
        // 37=NIP PIC Recruitment
        $nik = MtrUtility::find(37)->informasi;
        $hco = Karyawan::where('nik', $nik);
        $this->deleteToDoList(['nik'=>$nik, 'pic'=>'hco', 'filename'=>'emails.recruitment0']);
        $trx = TrxKebutuhanMaster::where(['status_pengajuan'=>9])->orderBy('id', 'asc')->get();
        $arr = [];
        foreach($trx as $_trx){
            // 9=Approved by L2 to P&C
            $sla_start = TrxKebutuhanLog::where(['id_trx'=>$_trx->id, 'id_status'=>9])->first()?->created_at;
            $sla_start = Carbon::parse($sla_start)->format('Y-m-d H:i:s');
            // 10=Done by P&C
            $sla_end = TrxKebutuhanLog::where(['id_trx' => $_trx->id, 'id_status' => 10])->first();
            $sla_end = $sla_end ? $sla_end->created_at->format('Y-m-d H:i:s') : Carbon::now()->format('Y-m-d H:i:s');
            // jika belum 10=Done by P&C maka hitung 14 hari kerja dari hari ini
            if(!empty($sla_end)){
                $period = CarbonPeriod::create($sla_start, now());
                $workingDays = 0;
                $holidays = Holiday::whereBetween('tanggal', [$sla_start, $sla_end])->pluck('tanggal')->toArray();
                // $arr_days = [$sla_start, $sla_end];
                foreach ($period as $date) {
                    // Cek jika hari Sabtu (6) atau Minggu (0)
                    if ($date->isWeekend()) {
                        continue;
                    }
                    // Cek jika tanggal ada di daftar hari libur
                    if (in_array($date->format('Y-m-d'), $holidays)) {
                        continue;
                    }
                    // array_push($arr_days, $date->format('Y-m-d H:i:s'));
                    $workingDays++;
                }
                // dd($workingDays, $arr_days);
                if($workingDays > 14){
                    array_push($arr, $_trx->id);
                }
            }
        }
        $trx = TrxRecruitmentIdentitas::whereIn('id_kebutuhan', $arr)->orderBy('id', 'desc')->get();
        foreach($trx as $_trx){
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Pemberitahuan Proses Seleksi Penerimaan Karyawan';
            // $mailData['data'] = ['msg'=>$msg];
            $mailData['filename'] = 'emails.recruitment0';
            $mailData['to'] = $email = $hco->value('email');
            $todolist = ['id_trx' => $_trx->id, 'nik' => $nik, 'id_status' => $_trx->id_status, 'pic' => 'hco'];
            $mailData['data'] = ['msg'=>'msg', 'nama' => $hco->value('nama'), 'jabatan' => Jabatan::find($hco->value('kode_jabatan'))->jabatan, 'unit_kerja' => Divisi::find($hco->value('kode_divisi'))->nama . '/' . Departemen::find($hco->value('kode_departemen'))->nama . '/' . SubDepartemen::find($hco->value('kode_sub_departemen'))->nama, 'link' => route('recruitment.applicant_process', [Crypt::encrypt($_trx->id)])];
            // check apakah status bukan
            // 24=Join
            // 25=Not Join
            if(!TrxRecruitmentLog::whereIn('id_status', [24,25])->where(['id_identitas'=>$_trx->id])->exists()){
                $this->insertToDoList($todolist, $mailData);
                // $this->sendEmail($mailData);
            }
        }
        // dd($trx);
        // return $arr;
    }

    public function PerubahanDataKaryawanBulanan()
    {
        $arr_not_in_nik = ['Hco.all','IT Infras','IT.BM'];
        foreach(explode(',', MtrUtility::find(55)->informasi) as $arr_nkp){
            array_push($arr_not_in_nik, $arr_nkp);
        }
        KaryawanBulanan::where('is_berubah', 0)->delete();
        $trx = Karyawan::select(
            DB::raw('COUNT(*) as total'),
            'layer1'
        )
        ->where('is_aktif', 1)
        ->whereNotIn('nik', $arr_not_in_nik)
        ->whereNotNull('layer1')
        ->whereRaw("TRIM(layer1) <> ''")
        ->groupBy('layer1')
        ->get();
        foreach($trx as $data){
            $_trx = Karyawan::where(['is_aktif'=>1, 'layer1'=>$data->layer1])->whereNotIn('nik', $arr_not_in_nik)->orderBy('nama', 'asc')->get();
            foreach($_trx as $_data){
                $insert = new KaryawanBulanan;
                $insert->tanggal = Carbon::now()->format('Y-m-d');
                $insert->nik = $_data->nik;
                $insert->nik_property = $this->getNikProperty($_data->nik);
                $insert->layer1 = $_data->layer1;
                $insert->layer1_property = $this->getNikProperty($_data->layer1);
                $insert->layer2 = $_data->layer2;
                $insert->layer2_property = $this->getNikProperty($_data->layer2);
                $insert->save();
            }
        }
        return $trx;
    }

    public function CleansingToDoList()
    {
        // dd('ok');
        ini_set('max_execution_time', 600);
        $arr_ok = array();
        $arr_nok = array();
        $trx = ToDoList::all();
        foreach($trx as $_trx){
            try {
                // $response = Http::get('http://localhost/matta/pelatihan/10049/edit');
                $response = Http::get($_trx->link);
                if ($response->successful()) {
                    $lines = explode(PHP_EOL, $response->body());
                    $sentence = '<meta name="status" content="true">';
                    foreach ($lines as $line) {
                        if($sentence == trim($line)){
                            array_push($arr_ok, ['id' => $_trx->id, 'link' => $_trx->link]);
                            ToDoList::find($_trx->id)->delete();
                            break;
                        }
                    }
                } else {
                    array_push($arr_nok, ['id' => $_trx->id, 'link' => $_trx->link, 'error' => "Error: " . $response->status()]);
                    continue;
                    // dd($_trx->id." Error: " . $response->status());
                }
            } catch (RequestException $e) {
                array_push($arr_nok, ['id' => $_trx->id, 'link' => $_trx->link, 'error' => "Request failed: " . $e->getMessage()]);
                continue;
                // dd($_trx->id." Request failed: " . $e->getMessage());
            } catch (\Exception $e) {
                array_push($arr_nok, ['id' => $_trx->id, 'link' => $_trx->link, 'error' => "An error occurred: " . $e->getMessage()]);
                continue;
                // dd($_trx->id." An error occurred: " . $e->getMessage());
            }
        }
        Storage::put('data_cleansing/'.Carbon::now()->format('Ymd_His').'.todolist.txt', json_encode(['$arr_ok'=>$arr_ok, '$arr_nok'=>$arr_nok]));
        dd(['$arr_ok'=>$arr_ok, '$arr_nok'=>$arr_nok]);
    }

    public function getToDoList($filename)
    {
        $trx = TrxPenilaianAuto::whereNotNull('id_trx')->delete();
        $trx = ToDoList::whereIn('filename', $filename)->orderBy('id', 'desc')->get();
        foreach ($trx as $value){
            $where = ['id' => $value->id_trx];
            $_trx = TrxPenilaian::whereNotIn('id_status', [4,8,9,10,11,12,13])->where($where)->first();
            if(!empty($_trx)){
                $where = ['nik' => $_trx->nik_karyawan];
                $tanggal_berakhir = Karyawan::select('tanggal_berakhir')->where($where)->first();
                $this->StorePenilaianReminder($_trx, date('Y-m-d'), $tanggal_berakhir->tanggal_berakhir, $value->id);
            }
        }
    }

    public function getDataCuti()
    {
        $filename = Carbon::now()->format('Ymd_His');
        $arr['OK'] = array();
        $arr['NOK'] = array();
        $tahun = Carbon::parse(now())->format('Y');
        $bulan = Carbon::parse(now())->format('m');
        $tahun_kemarin = DB::select(DB::raw("SELECT 12-SUM(jumlah_hari) AS sisa,nik_karyawan FROM trx_tbl_cuti WHERE tipe_cuti=0 AND status_cuti NOT IN (5,7) AND periode_cuti=".($tahun-1)." GROUP BY nik_karyawan HAVING SUM(jumlah_hari)<12 ORDER BY nik_karyawan"));
        if($bulan == 1){
            foreach($tahun_kemarin as $kemarin){
                $tahun_sekarang = DB::select(DB::raw("SELECT id,nik_karyawan,jumlah_hari FROM trx_tbl_cuti WHERE tipe_cuti=0 AND status_cuti NOT IN (5,7) AND periode_cuti=".$tahun." AND MONTH(tanggal_mulai)=$bulan AND nik_karyawan='".$kemarin->nik_karyawan."'"));
                foreach($tahun_sekarang as $sekarang){
                    if($kemarin->sisa >= $sekarang->jumlah_hari){
                        DB::update(DB::raw("UPDATE trx_tbl_cuti SET periode_cuti=".($tahun-1)." WHERE id=".$sekarang->id));
                        $kemarin->sisa -= $sekarang->jumlah_hari;
                        array_push($arr['OK'], $kemarin);
                        array_push($arr['OK'], $tahun_sekarang);
                    }else{
                        array_push($arr['NOK'], $kemarin);
                        array_push($arr['NOK'], $tahun_sekarang);
                    }
                }
            }
            Storage::put('data_cleansing/'.$filename.'.cuti.txt', json_encode($arr));
        }
    }

    // start
    public function InsertTrxCutiLog($id_trx)
    {
        $where = ['id_trx' => $id_trx, 'id_status' => 4];
        $trx = TrxCutiLog::where($where)->orderBy('id', 'desc')->first();
        // 
        $data = new TrxCutiLog;
        $data->created_at = date ("Y-m-d 00:00:00", strtotime("+1 days", strtotime($trx->created_at)));
        $data->updated_at = date ("Y-m-d 00:00:00", strtotime("+1 days", strtotime($trx->updated_at)));
        $data->id_trx = $id_trx;
        // 6 = Selesai
        $data->id_status = 6;
        // 23 = PIC PC Formulir Cuti (Novia Rahmayanti Ikmal)
        $data->nik_pic = MtrUtility::find(23)->informasi;
        $data->save();
        // return ['created_at'=>$trx->created_at, 'created_at+1'=>date ("Y-m-d 00:00:00", strtotime("+1 days", strtotime($trx->created_at)))];
    }

    public function GetTrxCuti(){
        // 4 = Disetujui oleh Layer 1
        $trx = TrxCuti::whereIn('status_cuti', [4])->orderBy('id', 'asc')->get();
        return $trx;
    }

    public function InsertToAttendance($date_from, $date_to){
        $arr = array();
        while (strtotime($date_from) <= strtotime($date_to)) {
            // 6 = Selesai
            $trx = TrxCuti::whereIn('status_cuti', [6])->where('tanggal_cuti', 'like', '%'.$date_from.'%')->orderBy('id', 'asc')->get();
            if($this->isWeekend($date_from)['status'] || $this->isHoliday($date_from)['status']){
                // do nothing
            }else{
                foreach($trx as $value){
                    $data = New Attendance;
                    $data->att_date = $date_from;
                    $data->att_timestamp = $date_from.' 00:00:00';
                    $data->att_nik = $value->nik_karyawan;
                    $data->att_type = 'Leave';
                    $data->att_status = 'Leave';
                    // $data->att_data_src = $value->_tipe->tipe_cuti;
                    $data->att_data_src = 'Leave';
                    $data->att_property = $this->getNikProperty($value->nik_karyawan);
                    $data->wg_id = Karyawan::where(['nik' => $value->nik_karyawan, 'is_aktif' => 1])->first()->wg_id;
                    $data->save();
                    array_push($arr, ['date_from'=>$date_from, 'trx'=>$value]);
                }
            }
            $date_from = date ("Y-m-d", strtotime("+1 days", strtotime($date_from)));
        }
        return $arr;
    }

    // cek apakah hari ini sabtu minggu
    public function isWeekend($date)
    {
        $weekDay = date('w', strtotime($date));
        $cek = $weekDay == 0 || $weekDay == 6;
        return ['status' => $cek];
    }

    // cek apakah hari ini adalah hari libur nasional
    public function isHoliday($date)
    {
        $cek = false;
        $where = ['tanggal' => $date];
        $data = Holiday::where($where)->first();
        if ($data) {
            $cek = true;
        }
        return ['status' => $cek];
    }
    // finish

    public function PenilaianAuto(){
        // 
        // DB::table('trx_tbl_penilaian_auto')->truncate();
        // 
        $result = array();
        $data = DB::select(DB::raw("SELECT nik,layer1,layer2,layer3,DATE_SUB(tanggal_berakhir,INTERVAL 60 DAY) AS tanggal_awal,tanggal_berakhir FROM mtr_tbl_karyawan WHERE kode_perusahaan=1 AND is_aktif=1 AND kode_status IN (1,2) AND DATEDIFF(tanggal_berakhir, DATE(NOW())) BETWEEN 0 AND 60 ORDER BY tanggal_berakhir"));
        foreach ($data as $value){
            $_data = DB::select(DB::raw("SELECT * FROM trx_tbl_penilaian WHERE nik_karyawan='".$value->nik."' AND DATE(created_at) BETWEEN '".$value->tanggal_awal."' AND '".$value->tanggal_berakhir."'"));
            if(count($_data)==0){
                $where = ['nik_karyawan' => $value->nik];
                $check = TrxPenilaianAuto::where($where)->whereNull('id_trx')->exists();
                if(!$check){
                    array_push($result, $value);
                    $this->StorePenilaianAuto($value);
                }else{
                    TrxPenilaianAuto::where($where)->whereNull('id_trx')->delete();
                    array_push($result, $value);
                    $this->StorePenilaianAuto($value);
                }
            }
        }
        // jika sudah non aktif maka delete dari trx_tbl_penilaian_auto
        $data = Karyawan::select('nik')->where(['is_aktif'=>0])->get()->toArray();
        TrxPenilaianAuto::whereIn('nik_karyawan', $data)->delete();
        return $result;
    }

    public function PenilaianAutoOutsourcing(){
        // 
        $result = array();
        $kode_perusahaan = MtrUtility::find(27)->informasi;
        $data = DB::select(DB::raw("SELECT nik,layer1,layer2,layer3,DATE_SUB(tanggal_berakhir,INTERVAL 60 DAY) AS tanggal_awal,tanggal_berakhir FROM mtr_tbl_karyawan WHERE kode_perusahaan IN (".$kode_perusahaan.") AND is_aktif=1 AND DATEDIFF(tanggal_berakhir, DATE(NOW())) BETWEEN 0 AND 60 ORDER BY tanggal_berakhir"));
        foreach ($data as $value){
            $_data = DB::select(DB::raw("SELECT * FROM trx_tbl_penilaian WHERE nik_karyawan='".$value->nik."' AND DATE(created_at) BETWEEN '".$value->tanggal_awal."' AND '".$value->tanggal_berakhir."'"));
            if(count($_data)==0){
                $where = ['nik_karyawan' => $value->nik];
                $check = TrxPenilaianAuto::where($where)->whereNull('id_trx')->exists();
                if(!$check){
                    array_push($result, $value);
                    $this->StorePenilaianAutoOutsourcing($value);
                }else{
                    TrxPenilaianAuto::where($where)->whereNull('id_trx')->delete();
                    array_push($result, $value);
                    $this->StorePenilaianAutoOutsourcing($value);
                }
            }
        }
        // jika sudah non aktif maka delete dari trx_tbl_penilaian_auto
        $data = Karyawan::select('nik')->where(['is_aktif'=>0])->get()->toArray();
        TrxPenilaianAuto::whereIn('nik_karyawan', $data)->delete();
        return $result;
    }

    public function KaryawanNonAktif(){
        // 
        $trx = TrxNonaktifAuto::truncate();
        $result = array();
        $data = DB::select(DB::raw("SELECT nik,layer1,DATE_SUB(tanggal_berakhir,INTERVAL 30 DAY) AS tanggal_awal,tanggal_berakhir FROM mtr_tbl_karyawan WHERE kode_perusahaan IN (1,2,6) AND is_aktif=1 AND kode_status IN (2) AND DATEDIFF(tanggal_berakhir, DATE(NOW())) BETWEEN 0 AND 30 ORDER BY tanggal_berakhir"));
        foreach ($data as $value){
            $_data = DB::select(DB::raw("SELECT * FROM trx_tbl_nonaktif_master WHERE pengaju='".$value->nik."' AND DATE(created_at) BETWEEN '".$value->tanggal_awal."' AND '".$value->tanggal_berakhir."'"));
            if(count($_data)==0){
                $where = ['nik_karyawan' => $value->nik];
                $check = TrxNonaktifAuto::where($where)->exists();
                if(!$check){
                    array_push($result, $value);
                    $trx = New TrxNonaktifAuto;
                    $trx->nik_karyawan = $value->nik;
                    $trx->nik_atasan = $value->layer1;
                    $trx->id_status = 1;
                    // $trx->id_trx = 0;
                    // $trx->is_email = 0;
                    $trx->start_date = $value->tanggal_awal;
                    $trx->end_date = $value->tanggal_berakhir;
                    $trx->save();
                }
            }
        }
        // return count($result);
        return $result;
    }

    public function StorePenilaianAuto($value){
        $trx = New TrxPenilaianAuto;
        $trx->nik_karyawan = $value->nik;
        $trx->nik_penilai_1 = $value->layer1;
        $trx->nik_penilai_2 = $value->layer2;
        $trx->nik_penilai_3 = $value->layer3;
        // 2 = Penilaian 360-Reguler
        $trx->id_tipe = 2;
        // 1 = Send to Employee
        $trx->id_status = 1;
        // $trx->id_trx = 0;
        // $trx->is_email = 0;
        $trx->start_date = $value->tanggal_awal;
        $trx->end_date = $value->tanggal_berakhir;
        $trx->save();
    }

    public function StorePenilaianAutoOutsourcing($value){
        $trx = New TrxPenilaianAuto;
        $trx->nik_karyawan = $value->nik;
        $trx->nik_penilai_1 = $value->layer1;
        $trx->nik_penilai_2 = $value->layer2;
        $trx->nik_penilai_3 = $value->layer3;
        // 6 = Penilaian Outsourcing
        $trx->id_tipe = 6;
        // 16 = Send to Reviewer 1
        $trx->id_status = 16;
        $trx->start_date = $value->tanggal_awal;
        $trx->end_date = $value->tanggal_berakhir;
        $trx->save();
    }

    public function StorePenilaianReminder($value, $start_date, $end_date, $id_trx){
        $trx = New TrxPenilaianAuto;
        $trx->nik_karyawan = $value->nik_karyawan;
        $trx->nik_penilai_1 = $value->nik_penilai_1;
        $trx->nik_penilai_2 = $value->nik_penilai_2;
        $trx->nik_penilai_3 = $value->nik_penilai_3;
        $trx->id_tipe = $value->id_tipe;
        $trx->id_status = $value->id_status;
        $trx->id_trx = $id_trx;
        $trx->start_date = $start_date;
        $trx->end_date = $end_date;
        $trx->save();
    }

    public function CleansingDataAbsensi(){
        // 
        // $tahun = Carbon::parse(now())->format('Y');
        // $bulan = Carbon::parse(now())->format('m');
        // $att = Attendance::whereMonth('att_timestamp', $bulan)->whereYear('att_timestamp', $tahun)->orderBy( 'att_timestamp', 'desc' )->get();
        $filename = Carbon::now()->format('Ymd_His');
        // Regular
        $result = array();
        $karyawan = Karyawan::select('nik')->whereIn('wg_id', [1])->get();
        $att = Attendance::where(['att_data_src' => 'Regular'])->whereIn('att_nik', $karyawan)->whereIn('att_type', ['Clock-in','Clock-out'])->groupBy('att_date','att_nik','att_type','att_wd_ot_id')->having(DB::raw('count(*)'), '>', 1)->orderBy('att_date','asc')->orderBy('att_nik','asc')->get();
        foreach ($att as $_att) {
            $where = [ 'att_data_src' => 'Regular', 'att_date' => $_att->att_date, 'att_nik' => $_att->att_nik, 'att_type' => $_att->att_type, 'att_wd_ot_id' => $_att->att_wd_ot_id ];
            $_att_ = Attendance::where($where)->orderBy('id','asc')->get();
            for ($x = 0; $x < count($_att_); $x++) {
                if($x > 0){
                    array_push($result, Attendance::find($_att_[$x]->id));
                    Attendance::find($_att_[$x]->id)->delete();
                    // return response()->json( [ 'success' => true, 'jumlah' => count($att), 'where' => $where, '_att_' => $_att_[$x]->id ], 200 );
                }
            }
        }
        Storage::put('data_cleansing/'.$filename.'.reg.txt', json_encode($result));
        // Overtime
        // $resultOt = array();
        // $att = Attendance::where(['att_data_src' => 'Overtime'])->whereIn('att_type', ['Clock-in','Clock-out'])->groupBy('att_date','att_nik','att_type','att_wd_ot_id')->having(DB::raw('count(*)'), '>', 1)->orderBy('att_date','asc')->orderBy('att_nik','asc')->get();
        // foreach ($att as $_att) {
        //     $where = [ 'att_data_src' => 'Overtime', 'att_date' => $_att->att_date, 'att_nik' => $_att->att_nik, 'att_type' => $_att->att_type, 'att_wd_ot_id' => $_att->att_wd_ot_id ];
        //     $_att_ = Attendance::where($where)->orderBy('id','asc')->get();
        //     for ($x = 0; $x < count($_att_); $x++) {
        //         if($x > 0){
        //             array_push($resultOt, Attendance::find($_att_[$x]->id));
        //             Attendance::find($_att_[$x]->id)->delete();
        //             // return response()->json( [ 'success' => true, 'jumlah' => count($att), 'where' => $where, '_att_' => $_att_[$x]->id ], 200 );
        //         }
        //     }
        // }
        // Storage::put('data_cleansing/'.$filename.'.ot.txt', json_encode($resultOt));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $filename = $request->filename;
        $trx = json_decode(file_get_contents(Storage::disk('local')->path('data_cleansing/'.$filename)), true);
        // return response()->json( [ 'success' => true, 'data' => count($trx) ], 200 );
        foreach ($trx as $_trx){
            $data = New Attendance;
            $data->id = $_trx['id'];
            $data->att_date = $_trx['att_date'];
            $data->att_timestamp = $_trx['att_timestamp'];
            $data->att_nik = $_trx['att_nik'];
            $data->att_type = $_trx['att_type'];
            $data->att_long = $_trx['att_long'];
            $data->att_lat = $_trx['att_lat'];
            $data->att_address = $_trx['att_address'];
            $data->att_ref_long = $_trx['att_ref_long'];
            $data->att_ref_lat = $_trx['att_ref_lat'];
            $data->att_ref_address = $_trx['att_ref_address'];
            $data->att_distance_gap = $_trx['att_distance_gap'];
            $data->att_clock_gap = $_trx['att_clock_gap'];
            $data->att_status = $_trx['att_status'];
            $data->att_data_src = $_trx['att_data_src'];
            $data->att_wd_ot_id = $_trx['att_wd_ot_id'];
            $data->created_at = $_trx['created_at'];
            $data->updated_at = $_trx['updated_at'];
            $data->save();
        }
        return response()->json( [ 'success' => true, 'data' => $trx ], 200 );
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
        if($id=='aDRkMQ'){
            $att = Attendance::whereNull('att_property')->whereBetween('att_date', ['2024-05-23', '2024-05-29'])->get();
            foreach($att as $value){
                $_att = Attendance::find($value->id);
                $_att->att_property = $this->getNikProperty($_att->att_nik);
                $_att->update();
            }
            dd('OK DONE');
        }elseif($id=='d2dfaWQ'){
            $att = Attendance::whereNotNull('att_property')->get();
            foreach($att as $value){
                $_att = Attendance::find($value->id);
                $data = json_decode($_att->att_property, true);
                $_att->wg_id = $data['wg_id'];
                $_att->update();
            }
            dd('OK DONE');
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

    public function getNikProperty($nik)
    {
        // $where = ['nik' => $nik, 'is_aktif' => 1];
        $where = ['nik' => $nik];
        $data = Karyawan::select('mtr_tbl_karyawan.nik', 'mtr_tbl_karyawan.nama', 'mtr_tbl_karyawan.email', 'mtr_tbl_karyawan.telepon1', 
        'mtr_tbl_perusahaan.nama as perusahaan', 'mtr_tbl_divisi.nama as divisi', 'mtr_tbl_departemen.nama as dep', 'mtr_tbl_sub_departemen.nama as sub_dep', 
        'mtr_tbl_level.level as level', 'mtr_tbl_jabatan.jabatan as jabatan', 'mtr_tbl_level.id as id_level', 'mtr_tbl_working_group.wg_name as wg_name', 
        'mtr_tbl_working_group.id as wg_id', 'mtr_tbl_working_group.is_reguler as is_reguler',
        'mtr_tbl_status_karyawan.status_karyawan as status_karyawan')
            ->leftJoin('mtr_tbl_perusahaan', 'mtr_tbl_perusahaan.id', '=', 'mtr_tbl_karyawan.kode_perusahaan')
            ->leftJoin('mtr_tbl_divisi', 'mtr_tbl_divisi.id', '=', 'mtr_tbl_karyawan.kode_divisi')
            ->leftJoin('mtr_tbl_departemen', 'mtr_tbl_departemen.id', '=', 'mtr_tbl_karyawan.kode_departemen')
            ->leftJoin('mtr_tbl_sub_departemen', 'mtr_tbl_sub_departemen.id', '=', 'mtr_tbl_karyawan.kode_sub_departemen')
            ->leftJoin('mtr_tbl_level', 'mtr_tbl_level.id', '=', 'mtr_tbl_karyawan.kode_level')
            ->leftJoin('mtr_tbl_jabatan', 'mtr_tbl_jabatan.id', '=', 'mtr_tbl_karyawan.kode_jabatan')
            ->leftJoin('mtr_tbl_working_group', 'mtr_tbl_working_group.id', '=', 'mtr_tbl_karyawan.wg_id')
            ->leftJoin('mtr_tbl_status_karyawan', 'mtr_tbl_status_karyawan.id', '=', 'mtr_tbl_karyawan.kode_status')
            ->where($where)->first();
        return json_encode($data);
    }

    public function getBirthday(){
        $today = Carbon::today();
        // exclude BOD & Executive
        // 14=Direktur
        $trx = Karyawan::whereNotIn('kode_level', [14])->where(['is_aktif' => 1])->whereMonth('tanggal_lahir', $today->month)->whereDay('tanggal_lahir', $today->day)->get();
        foreach ($trx as $_trx) {
            $atasan = Karyawan::where(['nik' => $_trx->layer1, 'is_aktif' => 1])->first();
            $data = new TrxBirthday;
            $data->nik = $_trx->nik;
            $data->nik_property = $this->getNikProperty($_trx->nik);
            $data->email = json_encode(['subject'=>'Happy Birthday', 'body'=>$this->getBodyEmail($_trx->nama), 'to'=>$_trx->email, 'cc'=>MtrUtility::find(11)->informasi.','.$atasan->email]);
            $data->birthday = $_trx->tanggal_lahir;
            $data->save();
        }
    }

    public function getBodyEmail($nama)
    {
        $body = '<div class="container">';
        $body .= '<br/><p>Happy Birthday to <b>'.$nama.'</b></p>';
        $body .= '<br/>';
        // $images = asset('assets/images/matta/BithdayMatta1.gif');
        $images = 'https://matta-reborn.alita.id/matta/assets/images/matta/BithdayMatta1.gif';
        // img-responsive
        $body .= '<img class="img-responsive" src="'.$images.'" alt="Happy Birthday">';
        $body .= '<br/><br/><br/>';
        $body .= MtrUtility::find(47)->informasi;
        $body .= '</div>';
        return $body;
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
        $trx = ToDoList::where($where)->delete();
    }
}
