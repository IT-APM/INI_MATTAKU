<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\MtrReminderSp;
use App\Models\TrxReminderSp;
use App\Models\TrxReminderSpSend;
use App\Models\MtrUtility;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use DB;
use App\Models\Karyawan;
use App\Models\WorkingDay;
use App\Models\WorkingGroup;
use App\Models\Level;
use App\Models\Attendance;
use App\Models\TrxIjin;

class ReminderSpController extends Controller
{
    public function history($id)
    {
        dd('ok');
    }
    // reminder_sp
    public function reminder_sp($id)
    {
        // dijalankan hanya di tanggal 01 setiap bulannya
        if(explode('-', Carbon::now()->format('Y-m-d'))[2] == '01'){
            $date_from = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
            $date_to = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
            // dd($date_from, $date_to);
            // pengecualian nik
            $kecuali = explode(',', MtrReminderSp::find(2)->isi);
            array_push($kecuali, 'Hco.all', 'IT.BM');
            $where = ['is_aktif' => 1];
            $karyawan = Karyawan::where($where)->whereNotIn('nik', $kecuali)->where('nik', 'not like', 'CS%')->get();
            $arr_date = (new RecapAttendanceController)->dateRange($date_from, $date_to);
            // pengecualian karena pada tanggal ini terjadi suspend domain alita.id
            $arr_date = array_values(array_diff($arr_date, ['2024-09-23']));
            // dd($arr_date);
            // $trx = [];
            foreach ($karyawan as $_karyawan) {
                $nik = $_karyawan->nik;
                $arr_base = [];
                $arr_clock = [];
                $tanggal_bergabung = $_karyawan->tanggal_bergabung;
                // cek apakah karyawan berjenis kerja reguler
                if ($_karyawan->_wg->is_reguler == 1) {
                    foreach ($arr_date as $data) {
                        if (!(new RecapAttendanceController)->isWeekend($data)) {
                            if (!(new RecapAttendanceController)->isHoliday($data)) {
                                if (!(new RecapAttendanceController)->isCuti($nik, $data)) {
                                    array_push($arr_base, $data);
                                }
                                if ((new RecapAttendanceController)->isSakit($nik, $data)) {
                                    if (($key = array_search($data, $arr_base)) !== false) {
                                        unset($arr_base[$key]);
                                    }
                                }
                                if ($tanggal_bergabung > $data) {
                                    if (($key = array_search($data, $arr_base)) !== false) {
                                        unset($arr_base[$key]);
                                    }
                                }
                            }
                        }
                    }
                }
                // karyawan non reguler dengan jadwal kerja sesuai working group nya
                else {
                    $where = ['nik' => $nik];
                    $data = WorkingDay::where($where)->whereIn('working_date', $arr_date)->get();
                    foreach ($data as $_data) {
                        if (!(new RecapAttendanceController)->isCuti($nik, $_data->working_date)) {
                            array_push($arr_base, $_data->working_date);
                        }
                        if ((new RecapAttendanceController)->isSakit($nik, $_data->working_date)) {
                            if (($key = array_search($_data->working_date, $arr_base)) !== false) {
                                unset($arr_base[$key]);
                            }
                        }
                        if ($tanggal_bergabung > $data) {
                            if (($key = array_search($data, $arr_base)) !== false) {
                                unset($arr_base[$key]);
                            }
                        }
                    }
                }
                foreach ($arr_base as $data) {
                    if ((new RecapAttendanceController)->isClockin($nik, $data)) {
                        array_push($arr_clock, $data);
                    }
                }
                $persentase = 0;
                if (count($arr_base) > 0) {
                    $persentase = count($arr_clock) / count($arr_base);
                }
                $nik_property = $this->getNikProperty($nik);
                // tangkap jika ada keterlambatan
                $terlambat = [];
                foreach ($arr_clock as $att_date) {
                    $data = Attendance::where(['att_date'=>$att_date, 'att_nik'=>$nik, 'att_data_src'=>'Regular', 'att_type'=>'Clock-in', 'att_status'=>'Late-in']);
                    if($data->exists()){
                        // cek apakah ada izin
                        // 2=Approve by L1
                        // 4=Done by P&C
                        // 6=Approve by L2
                        $izin = TrxIjin::where(['tanggal'=>$att_date, 'karyawan'=>$nik])->whereIn('status', [2,4,6])->get();
                        // 
                        $data = $data->get()[0];
                        if($data->wg_id == 1){
                            $clockin = '09:00:00';
                            $totalDetik = Carbon::createFromFormat('H:i:s', Carbon::parse($data->att_timestamp)->format('H:i:s'))->diffInSeconds(Carbon::createFromFormat('H:i:s', $clockin));
                            $durasi = Carbon::createFromTime(floor($totalDetik / 3600), floor(($totalDetik % 3600) / 60), $totalDetik % 60)->format('H:i:s');
                            array_push($terlambat, ['id_attendance'=>$data->id, 'tanggal'=>$att_date, 'clockin'=>$clockin, 'att_timestamp'=>$data->att_timestamp, 'durasi'=>$durasi, 'izin'=>$izin]);
                        }
                        elseif($data->wg_id > 1){
                            $clockin = WorkingDay::find($data->att_wd_ot_id)?->clockin;
                            $totalDetik = Carbon::createFromFormat('H:i:s', Carbon::parse($data->att_timestamp)->format('H:i:s'))->diffInSeconds(Carbon::parse($clockin)->format('H:i:s'));
                            $durasi = Carbon::createFromTime(floor($totalDetik / 3600), floor(($totalDetik % 3600) / 60), $totalDetik % 60)->format('H:i:s');
                            array_push($terlambat, ['id_attendance'=>$data->id, 'tanggal'=>$att_date, 'clockin'=>$clockin, 'att_timestamp'=>$data->att_timestamp, 'durasi'=>$durasi, 'izin'=>$izin]);
                        }
                    }
                }
                // maksimal 3 (tiga) kali dalam satu bulan
                if(count($terlambat) > 3){
                    $remindersp = ['date_from' => $date_from, 'date_to' => $date_to, 'nik' => $nik, 'nik_property' => $nik_property, 'arr_base' => count($arr_base), 'arr_clock' => count($arr_clock), 'persentase' => $persentase * 100, 'terlambat' => $terlambat];
                    // array_push($trx, $remindersp);
                    $this->insertTrxReminderSp($remindersp);
                }
                // tangkap jika kurang dari 80% dan ada hari kehadirannya
                if(round($persentase*100) < MtrReminderSp::find(1)->isi && count($arr_base) > 0){
                    $remindersp = ['date_from' => $date_from, 'date_to' => $date_to, 'nik' => $nik, 'nik_property' => $nik_property, 'arr_base' => count($arr_base), 'arr_clock' => count($arr_clock), 'persentase' => $persentase * 100];
                    // array_push($trx, $remindersp);
                    $this->insertTrxReminderSp($remindersp);
                }
            }
            // dd($trx);
            dd('DONE');
        }else{
            dd('NA');
        }
    }

    public function insertTrxReminderSp($trx)
    {
        $data = new TrxReminderSp;
        $data->date_from = $trx['date_from'];
        $data->date_to = $trx['date_to'];
        $data->nik = $trx['nik'];
        $data->nik_property = $trx['nik_property'];
        $data->arr_base = $trx['arr_base'];
        $data->arr_clock = $trx['arr_clock'];
        $data->persentase = $trx['persentase'];
        if(!empty($trx['terlambat'])){
            $data->terlambat = json_encode($trx['terlambat']);
        }
        $data->save();
    }

    public function insertTrxReminderSpSend($trx, $number, $type, $body_email)
    {
        $data = new TrxReminderSpSend;
        $data->number = $number;
        $data->type = $type;
        $data->date_from = $trx['date_from'];
        $data->date_to = $trx['date_to'];
        $data->nik = $trx['nik'];
        $data->nik_property = $trx['nik_property'];
        $data->arr_base = $trx['arr_base'];
        $data->arr_clock = $trx['arr_clock'];
        $data->persentase = $trx['persentase'];
        $data->terlambat = $trx['terlambat'];
        $data->body_email = $body_email;
        $data->save();
    }

    public function getNikProperty($nik)
    {
        $data = DB::select(DB::raw("SELECT a.nik,a.nama,a.layer1,a.layer2,a.layer3,a.kode_status,b.status_karyawan,a.email,a.wg_id,c.wg_name,a.kode_perusahaan,d.nama AS perusahaan,a.kode_divisi,e.nama AS divisi,a.kode_departemen,f.nama AS departemen,a.kode_sub_departemen,g.nama AS sub_departemen,a.kode_level,h.level,a.kode_jabatan,i.jabatan,a.tanggal_mulai,a.tanggal_bergabung,a.tanggal_berakhir,a.tanggal_karyawan_tetap FROM mtr_tbl_karyawan AS a LEFT JOIN mtr_tbl_status_karyawan AS b ON b.id=a.kode_status LEFT JOIN mtr_tbl_working_group AS c ON c.id=a.wg_id LEFT JOIN mtr_tbl_perusahaan AS d ON d.id=a.kode_perusahaan LEFT JOIN mtr_tbl_divisi AS e ON e.id=a.kode_divisi LEFT JOIN mtr_tbl_departemen AS f ON f.id=a.kode_departemen LEFT JOIN mtr_tbl_sub_departemen AS g ON g.id=a.kode_sub_departemen LEFT JOIN mtr_tbl_level AS h ON h.id=a.kode_level LEFT JOIN mtr_tbl_jabatan AS i ON i.id=a.kode_jabatan WHERE a.nik='".$nik."'"));
        return json_encode($data[0]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // start uji coba
        // $trx = TrxReminderSp::find(3);
        // $teguran_ke = 'Pertama';
        // $history_email = '';
        // $mailData['title'] = 'Surat Pemberitahuan '.$teguran_ke;
        // $mailData['filename'] = 'emails.reminder1';
        // // $mailData['filename'] = 'emails.warning1';
        // $mailData['to'] = 'riznaky.roosmanita@alita.id';
        // // $mailData['to'] = 'abdulhadi@tigaluhursejahtera.com';
        // $data = ['trx' => $trx, 'teguran_ke' => $teguran_ke, 'history_email' => $history_email, 'datetime' => Carbon::now()];
        // $mailData['data'] = $data;
        // $ccEmails = ['abdulhadi@tigaluhursejahtera.com','hco.all@alita.id','hadidisini@yahoo.com'];
        // // $ccEmails = ['hadidisini@yahoo.com'];
        // $mail = Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
        // dd($mail);
        // end uji coba
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $trx = TrxReminderSp::orderBy( 'date_from', 'asc' )->get();
        if(Gate::allows('hc_nkp')){
            $trx = TrxReminderSp::whereIn('nik',$arr_nik_nkp)->orderBy( 'date_from', 'asc' )->get();
        }
        // check apakah ada karyawan non aktif jika ada maka delete
        foreach($trx as $key => $item) {
            if(Karyawan::where(['nik'=>$item->nik, 'is_aktif'=>0])->exists()){
                // TrxReminderSp::find($item->id)->delete();
                $item->delete();
                $trx->forget($key);
            }
        }
        return view( 'reminder_sp.index', compact( 'trx' ) );
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
        $submit = $request->all();
        // dd($submit);
        foreach($submit as $_id => $value){
            if(str_starts_with($_id, 'submit')){
                if(empty($request->reminder_sp)){
                    if($value == 'execute'){
                        return redirect( 'reminder_sp' )->with( 'msg', 'No Data is executed.' );
                    }else if($value == 'delete'){
                        return redirect( 'reminder_sp' )->with( 'msg', 'No Data is deleted.' );
                    }
                }else{
                    if($value == 'execute'){
                        $reminder_sp = $request->reminder_sp;
                        foreach($reminder_sp as $_reminder_sp){
                            $trx = TrxReminderSp::find($_reminder_sp);
                            $nik_property = json_decode($trx->nik_property, true);
                            // check history
                            $teguran_ke = 'Pertama';
                            $number = 1;
                            $type = 'reminder';
                            $history_email = '';
                            // check nik yang sama pada 1 bulan sebelumnya
                            $where = ['nik' => $trx->nik, 'date_from' => Carbon::parse($trx->date_from)->modify('-1 month')->format('Y-m-d')];
                            $trxSend = TrxReminderSpSend::where($where)->whereNull('terlambat')->orderBy( 'date_from', 'desc' )->first();
                            if(!empty($trxSend)){
                                $history_email = $trxSend->body_email;
                                if($trxSend->type == 'reminder'){
                                    if($trxSend->number == 1){
                                        $teguran_ke = 'Kedua';
                                        $number = 2;
                                    }elseif($trxSend->number == 2){
                                        $teguran_ke = 'Ketiga';
                                        $number = 3;
                                    }elseif($trxSend->number == 3){
                                        $teguran_ke = 'Keempat';
                                        $number = 4;
                                    }elseif($trxSend->number == 4){
                                        $teguran_ke = 'Kelima';
                                        $number = 5;
                                    }elseif($trxSend->number == 5){
                                        $teguran_ke = 'Pertama';
                                        $number = 1;
                                        $type = 'warning';
                                    }
                                }elseif($trxSend->type == 'warning'){
                                    $type = 'warning';
                                    if($trxSend->number == 1){
                                        $teguran_ke = 'Kedua';
                                        $number = 2;
                                    }elseif($trxSend->number == 2){
                                        $teguran_ke = 'Ketiga';
                                        $number = 3;
                                    }
                                }
                            }
                            // terlambat
                            if(!empty($trx->terlambat)){
                                $type = 'late';
                                // check history
                                $teguran_ke = 'Pertama';
                                $number = 1;
                                $history_email = '';
                                // check nik yang sama pada 1 bulan sebelumnya
                                $where = ['nik' => $trx->nik, 'date_from' => Carbon::parse($trx->date_from)->modify('-1 month')->format('Y-m-d')];
                                $trxSend = TrxReminderSpSend::where($where)->whereNotNull('terlambat')->orderBy( 'date_from', 'desc' )->first();
                                if(!empty($trxSend)){
                                    $history_email = $trxSend->body_email;
                                    if($trxSend->type == 'late'){
                                        $arr = ['Pertama', 'Kedua', 'Ketiga', 'Keempat', 'Kelima', 'Keenam', 'Ketujuh', 'Kedelapan', 'Kesembilan', 'Kesepuluh', 'Kesebelas', 'Keduabelas'];
                                        $teguran_ke = $arr[$trxSend->number];
                                        $number = $trxSend->number + 1;
                                    }
                                }
                            }
                            // start
                            // parameter yang dipassing ke sendemail
                            // $mailData['title'] = 'Surat Pemberitahuan '.$teguran_ke;
                            $mailData['title'] = 'Pengingat Absensi '.$teguran_ke;
                            $mailData['filename'] = 'emails.reminder1';
                            if($type == 'warning'){
                                $mailData['title'] = 'Surat Peringatan '.$teguran_ke;
                                $mailData['filename'] = 'emails.warning1';
                            }elseif($type == 'late'){
                                $mailData['title'] = 'Pengingat Keterlambatan '.$teguran_ke;
                                $mailData['filename'] = 'emails.late1';
                            }
                            $mailData['to'] = $nik_property['email'];
                            $data = ['trx' => $trx, 'teguran_ke' => $teguran_ke, 'history_email' => $history_email, 'datetime' => Carbon::now()];
                            $mailData['data'] = $data;
                            $ccEmails = explode(',', MtrUtility::find(11)->informasi);
                            $where = ['nik' => $nik_property['layer1']];
                            // cc email ke layer1
                            $layer1 = Karyawan::where($where)->first();
                            array_push($ccEmails, $layer1->email);
                            if($type == 'reminder' && $type == 'late'){
                                if(in_array($number, [4, 5, 6, 7, 8, 9, 10, 11, 12])){
                                    // cc email ke layer2
                                    $where = ['nik' => $nik_property['layer2']];
                                    $layer2 = Karyawan::where($where)->first();
                                    array_push($ccEmails, $layer2->email);
                                    if(in_array($number, [5, 6, 7, 8, 9, 10, 11, 12])){
                                        // cc email head of cs
                                        array_push($ccEmails, MtrReminderSp::find(3)->isi);
                                    }
                                }
                            }elseif($type == 'warning'){
                                // cc email ke layer2
                                $where = ['nik' => $nik_property['layer2']];
                                $layer2 = Karyawan::where($where)->first();
                                array_push($ccEmails, $layer2->email);
                                // cc email head of cs
                                array_push($ccEmails, MtrReminderSp::find(3)->isi);
                            }
                            // $_terlambat = json_decode($data['trx']->terlambat, true);
                            // return response()->json(['response'=>true, 'mailData'=>$_terlambat]);
                            Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
                            // end
                            $this->insertTrxReminderSpSend($trx, $number, $type, view($mailData['filename'], compact('data')));
                            // dihapus jika sudah diemail
                            $this->destroy($_reminder_sp);
                        }
                        return redirect( 'reminder_sp' )->with( 'msg', 'Data is executed successfully.' );
                    }else if($value == 'delete'){
                        $reminder_sp = $request->reminder_sp;
                        foreach($reminder_sp as $_reminder_sp){
                            $this->destroy($_reminder_sp);
                        }
                        return redirect( 'reminder_sp' )->with( 'msg', 'Data is deleted successfully.' );
                    }
                }
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $trx = TrxReminderSpSend::orderBy( 'date_from', 'desc', 'persentase', 'asc' )->get();
        if(Gate::allows('hc_nkp')){
            $trx = TrxReminderSpSend::whereIn('nik',$arr_nik_nkp)->orderBy( 'date_from', 'desc', 'persentase', 'asc' )->get();
        }
        $arr_nik = [];
        $arr_wg = [];
        $arr_level = [];
        foreach(($trx->toArray()) as $data){
            $nik_property = json_decode($data['nik_property'], true);
            array_push($arr_nik, $data['nik']);
            array_push($arr_wg, $nik_property['wg_id']);
            array_push($arr_level, $nik_property['kode_level']);
        }
        $karyawan = Karyawan::select('nama','nik')->whereIn('nik', $arr_nik)->orderBy('nama', 'asc')->get();
        $wg = WorkingGroup::whereIn('id', $arr_wg)->orderBy('id', 'asc')->get();
        $level = Level::whereIn('id', $arr_level)->orderBy('id', 'asc')->get();
        // filter parameter
        $bulan_tahun_dari = '';
        $bulan_tahun_sampai = '';
        $_arr_nik = [];
        $_arr_wg = [];
        $_arr_level = [];
        // start filter
        if($request->filter == true){
            $bulan_tahun_dari = $request->bulan_tahun_dari;
            $bulan_tahun_sampai = $request->bulan_tahun_sampai;
            $_arr_nik = [$request->nik];
            $_arr_wg = $request->input('wg', []);
            $_arr_level = $request->input('level', []);
            $patterns_wg = [];
            $patterns_level = [];
            foreach($_arr_wg as $data){
                array_push($patterns_wg, '"wg_id":'.$data.',');
            }
            foreach($_arr_level as $data){
                array_push($patterns_level, '"kode_level":'.$data.',');
            }
            $query = TrxReminderSpSend::query();
            $trx = $query->where(function($query) use ($patterns_level) {
                // jika level ada pilihan
                if(count($patterns_level)>0){
                    foreach ($patterns_level as $index => $pattern) {
                        if($index == 0){
                            $query->where('nik_property', 'like', '%' . $pattern . '%');
                        }else{
                            $query->orWhere('nik_property', 'like', '%' . $pattern . '%');
                        }
                    }
                }else{
                    $query->whereNull('nik_property');
                }
            })
            ->where(function($query) use ($patterns_wg) {
                // jika wg ada pilihan
                if(count($patterns_wg)>0){
                    foreach ($patterns_wg as $index => $pattern) {
                        if($index == 0){
                            $query->where('nik_property', 'like', '%' . $pattern . '%');
                        }else{
                            $query->orWhere('nik_property', 'like', '%' . $pattern . '%');
                        }
                    }
                }else{
                    $query->whereNull('nik_property');
                }
            })
            ->whereIn('nik', $_arr_nik)
            ->whereBetween(DB::raw('date_from'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
            ->orderBy( 'date_from', 'desc', 'persentase', 'asc' )->get();
            if(Gate::allows('hc_nkp')){
                $trx = $query->where(function($query) use ($patterns_level) {
                    // jika level ada pilihan
                    if(count($patterns_level)>0){
                        foreach ($patterns_level as $index => $pattern) {
                            if($index == 0){
                                $query->where('nik_property', 'like', '%' . $pattern . '%');
                            }else{
                                $query->orWhere('nik_property', 'like', '%' . $pattern . '%');
                            }
                        }
                    }else{
                        $query->whereNull('nik_property');
                    }
                })
                ->where(function($query) use ($patterns_wg) {
                    // jika wg ada pilihan
                    if(count($patterns_wg)>0){
                        foreach ($patterns_wg as $index => $pattern) {
                            if($index == 0){
                                $query->where('nik_property', 'like', '%' . $pattern . '%');
                            }else{
                                $query->orWhere('nik_property', 'like', '%' . $pattern . '%');
                            }
                        }
                    }else{
                        $query->whereNull('nik_property');
                    }
                })
                ->whereIn('nik',$arr_nik_nkp)
                ->whereIn('nik', $_arr_nik)
                ->whereBetween(DB::raw('date_from'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
                ->orderBy( 'date_from', 'desc', 'persentase', 'asc' )->get();
            }
            // jika semua karyawan dipilih
            if(in_array('0', $_arr_nik)){
                $query = TrxReminderSpSend::query();
                $trx = $query->where(function($query) use ($patterns_level) {
                    // jika level ada pilihan
                    if(count($patterns_level)>0){
                        foreach ($patterns_level as $index => $pattern) {
                            if($index == 0){
                                $query->where('nik_property', 'like', '%' . $pattern . '%');
                            }else{
                                $query->orWhere('nik_property', 'like', '%' . $pattern . '%');
                            }
                        }
                    }else{
                        $query->whereNull('nik_property');
                    }
                })
                ->where(function($query) use ($patterns_wg) {
                    // jika wg ada pilihan
                    if(count($patterns_wg)>0){
                        foreach ($patterns_wg as $index => $pattern) {
                            if($index == 0){
                                $query->where('nik_property', 'like', '%' . $pattern . '%');
                            }else{
                                $query->orWhere('nik_property', 'like', '%' . $pattern . '%');
                            }
                        }
                    }else{
                        $query->whereNull('nik_property');
                    }
                })
                ->whereBetween(DB::raw('date_from'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
                ->orderBy( 'date_from', 'desc', 'persentase', 'asc' )->get();
                if(Gate::allows('hc_nkp')){
                    $trx = $query->where(function($query) use ($patterns_level) {
                        // jika level ada pilihan
                        if(count($patterns_level)>0){
                            foreach ($patterns_level as $index => $pattern) {
                                if($index == 0){
                                    $query->where('nik_property', 'like', '%' . $pattern . '%');
                                }else{
                                    $query->orWhere('nik_property', 'like', '%' . $pattern . '%');
                                }
                            }
                        }else{
                            $query->whereNull('nik_property');
                        }
                    })
                    ->where(function($query) use ($patterns_wg) {
                        // jika wg ada pilihan
                        if(count($patterns_wg)>0){
                            foreach ($patterns_wg as $index => $pattern) {
                                if($index == 0){
                                    $query->where('nik_property', 'like', '%' . $pattern . '%');
                                }else{
                                    $query->orWhere('nik_property', 'like', '%' . $pattern . '%');
                                }
                            }
                        }else{
                            $query->whereNull('nik_property');
                        }
                    })
                    ->whereIn('nik',$arr_nik_nkp)
                    ->whereBetween(DB::raw('date_from'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
                    ->orderBy( 'date_from', 'desc', 'persentase', 'asc' )->get();
                }
            }
            // $sql = $query->toSql();
            // $bindings = $query->getBindings();
            // foreach ($bindings as $binding) {
            //     $sql = preg_replace('/\?/', "'" . addslashes($binding) . "'", $sql, 1); // Replace each placeholder
            // }
            // dd($sql);
        }
        // end filter
        return view( 'reminder_sp.history', compact( 'trx', 'karyawan', 'bulan_tahun_dari', 'bulan_tahun_sampai', 'wg', 'level', 'arr_nik', 'arr_wg', 'arr_level', '_arr_nik', '_arr_wg', '_arr_level' ) );
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
        $trx = TrxReminderSp::find($id);
        $trx->delete();
    }
}
