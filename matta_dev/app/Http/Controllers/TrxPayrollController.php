<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use DB;
use Elibyy\TCPDF\Facades\TCPDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TrxPayrollEmailExport;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File;
use URL;
// 
use App\Models\ToDoList;
use App\Models\ToDoListLog;
// 
use App\Models\Overtime;
use App\Models\MtrUtility;
use App\Models\Karyawan;
use App\Models\KaryawanHistory;
use App\Models\MtrPayroll;
use App\Models\MtrPayrollLog;
use App\Models\MtrPayrollAddsub;
use App\Models\MtrPayrollBpjstk;
use App\Models\MtrPayrollBpjskes;
use App\Models\MtrPayrollBpjsMax;
use App\Models\MtrStrukturGaji;
// 
use App\Models\TrxPayroll;
use App\Models\TrxPayrollLog;
use App\Models\TrxPayrollAddsub;
use App\Models\TrxPayrollAddsubLog;
use App\Models\TrxPayrollBpjstk;
use App\Models\TrxPayrollBpjstkLog;
use App\Models\TrxPayrollBpjskes;
use App\Models\TrxPayrollBpjskesLog;
use App\Models\TrxPayrollEmail;
use App\Models\TrxPayrollEmailLog;
use App\Models\TrxPayrollPenyesuaianUpahLog;
// 
use App\Models\MtrFindimSubdepartemen;
use App\Models\MtrFindimDepartemen;
use App\Models\MtrFindimDivisi;
use App\Models\MtrFindimProject;
use App\Models\Divisi;
use App\Models\Departemen;
use App\Models\SubDepartemen;
use App\Models\KaryawanStatus;
use App\Models\Jabatan;
use App\Models\Level;
// 
class TrxPayrollController extends Controller
{

    // http://localhost/matta/payroll/set_paid_date/0
    // https://matta-reborn.alita.id/matta/payroll/set_paid_date/0
    public function set_paid_date($id)
    {
        // cek tanggal
        $today = Carbon::today();
        // setiap tanggal 1 memindahkan trx_tbl_payroll ke trx_tbl_payroll_log
        // atau
        // tidak ada data bulan dan tahun saat ini
        $exists = TrxPayroll::where('bulan', $today->month)->where('tahun', $today->year)->exists();
        if($today->day == 1 || !$exists) {
            $this->bkp(0);
        }
        $check = TrxPayroll::whereNull('paid_at')->exists();
        $arr = [];
        if($check){
            $trx = TrxPayroll::whereNull('paid_at')->get();
            foreach($trx as $_trx){
                $tgl = Carbon::parse("{$_trx->tahun}-{$_trx->bulan}-25")->format('Y-m-d');
                while(!(new TrxCutiController)->isWeekend($tgl)['status'] || !(new TrxCutiController)->isHoliday($tgl)['status']) {
                    $tgl = date("Y-m-d", strtotime($tgl . "-1 day"));
                }
                TrxPayroll::whereNull('paid_at')->where(['id'=>$_trx->id])->update(['paid_at' => $tgl]);
                array_push($arr, $tgl);
            }
        }
        // $tgl = Carbon::parse($today)->format('Y-m-d');
        // while(!(new TrxCutiController)->isWeekend($tgl)['status'] || !(new TrxCutiController)->isHoliday($tgl)['status']) {
        //     $tgl = date("Y-m-d", strtotime($tgl . "-1 day"));
        // }
        // dd($arr);
        // TrxPayroll::whereNull('paid_at')->where(['bulan'=>$today->month, 'tahun'=>$today->year])->update(['paid_at' => $tgl]);
        // DB::update(DB::raw("UPDATE trx_tbl_payroll SET paid_at='".Carbon::parse($tgl)->format('Y-m-d')."' WHERE bulan=".$today->month." AND tahun=".$today->year));
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

    public function sendEmail($mailData)
    {
        $ccEmails = explode(',', $mailData['cc']);
        // tidak digunakan cc
        // Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
        // menjadi bcc
        Mail::to($mailData['to'])->bcc($ccEmails)->send(new MattaRebornMail($mailData));
    }
    
    public function send_email_exe(Request $request)
    {
        // dd($request->all());
        if($request->submit == 'execute'){
            $id = $request->input('id_current', []);
            if(count($id)==0){
                return redirect( 'payroll/send_email/0' )->with( 'msg', 'No Payroll Email is executed.' );
            }else{
                foreach($id as $_id){
                    $_trx = TrxPayrollEmail::find($_id);
                    $email = json_decode($_trx->email, true);
                    // parameter yang dipassing ke sendemail
                    $mailData['title'] = $email['subject'];
                    $mailData['data'] = ['msg' => $email['body']];
                    $mailData['filename'] = 'emails.payroll0';
                    $mailData['to'] = $email['to'];
                    $mailData['cc'] = $email['cc'];
                    if($_trx->id_addsub == '0' && !empty($_trx->filename)){
                        // menambahkan parameter
                        $request->merge(['view' => 'pdf', 'perihal' => 'pemberitahuan_upah', 'redaksi' => $_trx->param1, 'no_surat' => $_trx->no_surat, 'filename' => $_trx->filename, 'email_id' => $_id, 'password' => true]);
                        $this->slip_detail($request, (new OpenDataController)->encryptWithKey(TrxPayroll::where(['tahun'=>$_trx->tahun, 'bulan'=>$_trx->bulan, 'nik'=>$_trx->nik])->first()->id));
                        $mailData['attachments'] = public_path('assets/penyesuaian_upah').'/'.$_trx->filename;
                        $mailData['namafile'] = $_trx->filename;
                    }
                    $this->sendEmail($mailData);
                    // insert into trx_tbl_payroll_email_log
                    $trx = New TrxPayrollEmailLog;
                    $trx->nik = $_trx->nik;
                    $trx->nik_property = $_trx->nik_property;
                    $trx->bulan = $_trx->bulan;
                    $trx->tahun = $_trx->tahun;
                    $trx->email = $_trx->email;
                    $trx->id_addsub = $_trx->id_addsub;
                    $trx->param1 = $_trx->param1;
                    $trx->filename = $_trx->filename;
                    $trx->no_surat = $_trx->no_surat;
                    $trx->spu = $_trx->spu;
                    $trx->save();
                    // delete
                    TrxPayrollEmail::find($_id)->delete();
                }
                return redirect( 'payroll/send_email/0' )->with( 'msg', 'Payroll Email is executed successfully.' );
            }
        }
        elseif($request->submit == 'delete'){
            $id = $request->input('id_current', []);
            if(count($id)==0){
                return redirect( 'payroll/send_email/0' )->with( 'msg', 'No Payroll Email is deleted.' );
            }else{
                foreach($id as $_id){
                    TrxPayrollEmail::find($_id)->delete();
                }
                return redirect( 'payroll/send_email/0' )->with( 'msg', 'Payroll Email is deleted successfully.' );
            }
        }
    }

    public function send_email(Request $request, $id)
    {
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $trx_current = TrxPayrollEmail::orderBy('id', 'desc')->get();
        $trx_history = TrxPayrollEmailLog::orderBy('id', 'desc')->get();
        if(Gate::allows('hc_nkp')){
            $trx_current = TrxPayrollEmail::whereIn('nik',$arr_nik_nkp)->orderBy('id', 'desc')->get();
            $trx_history = TrxPayrollEmailLog::whereIn('nik',$arr_nik_nkp)->orderBy('id', 'desc')->get();
        }
        return view('payroll.send_email', compact('trx_current', 'trx_history'));
    }
    
    public function employee_gap(Request $request, $id)
    {
        $result = array();
        // membandingkan data 1 bulan sebelumnya untuk mengecek apakah ada perbedaan karyawan
        // present
        $periode = DB::select(DB::raw("SELECT bulan,tahun FROM trx_tbl_payroll GROUP BY bulan,tahun ORDER BY tahun DESC,bulan DESC"));
        foreach($periode as $data){
            array_push($result, $this->get_employee_gap($data->bulan, $data->tahun, 'present'));
        }
        // past
        $periode = DB::select(DB::raw("SELECT bulan,tahun FROM trx_tbl_payroll_log GROUP BY bulan,tahun ORDER BY tahun DESC,bulan DESC"));
        foreach($periode as $data){
            array_push($result, $this->get_employee_gap($data->bulan, $data->tahun, 'past'));
        }
        // 
        $bulan_tahun_dari = '';
        $bulan_tahun_sampai = '';
        // start filter
        if($request->filter == true){
            $result = array();
            $bulan_tahun_dari = $request->bulan_tahun_dari;
            $bulan_tahun_sampai = $request->bulan_tahun_sampai;
            // present
            $periode = DB::select(DB::raw("SELECT bulan,tahun FROM trx_tbl_payroll WHERE DATE(CONCAT(tahun, '-', bulan, '-01')) BETWEEN '".$request->bulan_tahun_dari."-01' AND '".$request->bulan_tahun_sampai."-01' GROUP BY bulan,tahun ORDER BY tahun DESC,bulan DESC"));
            foreach($periode as $data){
                array_push($result, $this->get_employee_gap($data->bulan, $data->tahun, 'present'));
            }
            // past
            $periode = DB::select(DB::raw("SELECT bulan,tahun FROM trx_tbl_payroll_log WHERE DATE(CONCAT(tahun, '-', bulan, '-01')) BETWEEN '".$request->bulan_tahun_dari."-01' AND '".$request->bulan_tahun_sampai."-01' GROUP BY bulan,tahun ORDER BY tahun DESC,bulan DESC"));
            foreach($periode as $data){
                array_push($result, $this->get_employee_gap($data->bulan, $data->tahun, 'past'));
            }
        }
        return view('payroll.employee_gap', compact('result', 'bulan_tahun_dari', 'bulan_tahun_sampai'));
        // $where = ['bulan'=>Carbon::now()->month, 'tahun'=>Carbon::now()->year];
        // $payroll = TrxPayroll::where(['status'=>'apm'])->where($where)->get();
        // $where = ['bulan'=>Carbon::now()->subMonth(2)->month, 'tahun'=>Carbon::now()->subMonth(2)->year];
        // $payroll = TrxPayrollLog::where(['status'=>'apm'])->where($where)->get();
        // $where = ['bulan'=>Carbon::now()->subMonth(3)->month, 'tahun'=>Carbon::now()->subMonth(3)->year];
        // $payroll_h = TrxPayrollLog::where(['status'=>'apm'])->where($where)->get();
        // $arr_nik = [];
        // $arr_nik_h = [];
        // foreach(($payroll->toArray()) as $data){
        //     array_push($arr_nik, $data['nik']);
        // }
        // foreach(($payroll_h->toArray()) as $data){
        //     array_push($arr_nik_h, $data['nik']);
        // }
        // $collection1 = collect($arr_nik);
        // $collection2 = collect($arr_nik_h);
        // $diff = $collection2->diff($collection1);
        // if ($diff->isNotEmpty()) {
        //     dd('Ada anggota baru di array kedua yang tidak ada di array pertama:', $diff);
        // } else {
        //     dd('Tidak ada anggota baru di array kedua yang tidak ada di array pertama.');
        // }
        // dd($diff);
    }
    
    public function get_employee_gap($bulan, $tahun, $periode)
    {
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $submonth = Carbon::createFromFormat('Y-m-d', $tahun."-".$bulan."-01")->subMonth();
        $where = ['bulan'=>$bulan, 'tahun'=>$tahun];
        $payroll = TrxPayroll::where(['status'=>'apm'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
        if(Gate::allows('hc_nkp')){
            $payroll = TrxPayroll::whereIn('nik',$arr_nik_nkp)->where(['status'=>'apm'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
        }
        // cek jika jumlah gapok + id_addsub IN (1,2,3,4,5) = 0 maka dikeluarkan
        $reject_nik = array();
        foreach($payroll as $data){
            $where = ['bulan'=>$bulan, 'tahun'=>$tahun, 'nik'=>$data->nik];
            $arr_id = [1,2,3,4,5];
            $add = TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->sum('angka');
            if($data->gapok + $add == 0){
                array_push($reject_nik, $data->nik);
            }
        }
        foreach($reject_nik as $data){
            $payroll = $payroll->reject(function ($item) use ($data) {
                return $item['nik'] == $data;
            });
        }
        if($periode == 'past'){
            $payroll = TrxPayrollLog::where(['status'=>'apm'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
            if(Gate::allows('hc_nkp')){
                $payroll = TrxPayrollLog::whereIn('nik',$arr_nik_nkp)->where(['status'=>'apm'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
            }
            // cek jika jumlah gapok + id_addsub IN (1,2,3,4,5) = 0 maka dikeluarkan
            $reject_nik = array();
            foreach($payroll as $data){
                $where = ['bulan'=>$bulan, 'tahun'=>$tahun, 'nik'=>$data->nik];
                $arr_id = [1,2,3,4,5];
                $add = TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->sum('angka');
                if($data->gapok + $add == 0){
                    array_push($reject_nik, $data->nik);
                }
            }
            foreach($reject_nik as $data){
                $payroll = $payroll->reject(function ($item) use ($data) {
                    return $item['nik'] == $data;
                });
            }
        }
        $where = ['bulan'=>$submonth->month, 'tahun'=>$submonth->year];
        $payroll_h = TrxPayrollLog::where(['status'=>'apm'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
        if(Gate::allows('hc_nkp')){
            $payroll_h = TrxPayrollLog::whereIn('nik',$arr_nik_nkp)->where(['status'=>'apm'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
        }
        // cek jika jumlah gapok + id_addsub IN (1,2,3,4,5) = 0 maka dikeluarkan
        $reject_nik = array();
        foreach($payroll_h as $data){
            $where = ['bulan'=>$bulan, 'tahun'=>$tahun, 'nik'=>$data->nik];
            $arr_id = [1,2,3,4,5];
            $add = TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->sum('angka');
            if($data->gapok + $add == 0){
                array_push($reject_nik, $data->nik);
            }
        }
        foreach($reject_nik as $data){
            $payroll_h = $payroll_h->reject(function ($item) use ($data) {
                return $item['nik'] == $data;
            });
        }
        $arr_nik = [];
        $arr_nik_h = [];
        foreach(($payroll->toArray()) as $data){
            array_push($arr_nik, $data['nik']);
        }
        foreach(($payroll_h->toArray()) as $data){
            array_push($arr_nik_h, $data['nik']);
        }
        $coll = collect($arr_nik);
        $coll_h = collect($arr_nik_h);
        $diff_after = $coll->diff($coll_h);
        $diff_before = $coll_h->diff($coll);
        // 
        $where = ['bulan'=>$bulan, 'tahun'=>$tahun];
        $payroll = TrxPayroll::where(['status'=>'apm'])->where($where)->whereIn('nik', $diff_after)->orderByRaw('tahun desc, bulan desc')->get();
        if(Gate::allows('hc_nkp')){
            $payroll = TrxPayroll::whereIn('nik',$arr_nik_nkp)->where(['status'=>'apm'])->where($where)->whereIn('nik', $diff_after)->orderByRaw('tahun desc, bulan desc')->get();
        }
        if($periode == 'past'){
            $payroll = TrxPayrollLog::where(['status'=>'apm'])->where($where)->whereIn('nik', $diff_after)->orderByRaw('tahun desc, bulan desc')->get();
            if(Gate::allows('hc_nkp')){
                $payroll = TrxPayrollLog::whereIn('nik',$arr_nik_nkp)->where(['status'=>'apm'])->where($where)->whereIn('nik', $diff_after)->orderByRaw('tahun desc, bulan desc')->get();
            }
        }
        $where = ['bulan'=>$submonth->month, 'tahun'=>$submonth->year];
        $payroll_h = TrxPayrollLog::where(['status'=>'apm'])->where($where)->whereIn('nik', $diff_before)->orderByRaw('tahun desc, bulan desc')->get();
        if(Gate::allows('hc_nkp')){
            $payroll_h = TrxPayrollLog::whereIn('nik',$arr_nik_nkp)->where(['status'=>'apm'])->where($where)->whereIn('nik', $diff_before)->orderByRaw('tahun desc, bulan desc')->get();
        }
        // 
        return ['bulan'=>$bulan, 'tahun'=>$tahun, 'after'=>$payroll, 'before'=>$payroll_h];
    }
    
    public function edit_payroll(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $back_url = $request->back_url;
        $type = $request->type;
        $trx = TrxPayrollLog::find($id);
        if($type=='overtime_present'){
            $trx = TrxPayroll::find($id);
        }
        return view('payroll.edit_payroll', compact('trx', 'back_url', 'type'));
    }
    
    public function save_edit_payroll(Request $request)
    {
        // dd($request->all());
        $back_url = $request->back_url;
        $type = $request->type;
        // $tab = $request->tab;
        $trx = TrxPayrollLog::find($request->id);
        if($type=='overtime_present'){
            $trx = TrxPayroll::find($request->id);
        }
        $trx->overtime = str_replace(',', '', $request->overtime);
        $trx->update();
        return redirect($back_url)->with( 'msg', 'Data is saved successfully.' );
        // $msg = 'Overtime Payroll Value Data is saved successfully.';
        // return redirect($back_url)->with(compact('msg','tab'));
        // return redirect($back_url, compact('msg','tab'));
        // return redirect($back_url)->with(['msg'=>$tab, 'tab'=>$tab]);
    }
    
    public function slip(Request $request, $id)
    {
        // fungsi untuk set paid_at default pada trx_tbl_payroll_log
        // $arr = array();
        // $startYear = 2022;
        // $endYear = 2024;
        // for ($year = $startYear; $year <= $endYear; $year++) {
        //     for ($month = 1; $month <= 12; $month++) {
        //         $tgl = Carbon::create($year, $month, 25);
        //         while (!(new TrxCutiController)->isWeekend($tgl)['status'] || !(new TrxCutiController)->isHoliday($tgl)['status']) {
        //             $tgl = date("Y-m-d", strtotime($tgl . "-1 day"));
        //         }
        //         array_push($arr, "UPDATE trx_tbl_payroll_log SET paid_at='".Carbon::parse($tgl)->format('Y-m-d')."' WHERE bulan=".$month." AND tahun=".$year.";");
        //         echo "UPDATE trx_tbl_payroll_log SET paid_at='".Carbon::parse($tgl)->format('Y-m-d')."' WHERE bulan=".$month." AND tahun=".$year.";<br/>";
        //         // array_push($arr, (new TrxCutiController)->getTanggalIndonesia(Carbon::parse($tgl)->format('Y-m-d')));
        //     }
        // }
        // dd('');
        $nik = (new OpenDataController)->decryptWithKey($id);
        if($nik != auth()->user()->nik){
            return redirect()->route('error-403');
        }
        $where = ['nik'=>$nik];
        $payroll = TrxPayroll::where(['status'=>'apm'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
        $payroll_h = TrxPayrollLog::where(['status'=>'apm'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
        // filter parameter
        $bulan_tahun_dari = '';
        $bulan_tahun_sampai = '';// start filter
        if($request->filter == true){
            $bulan_tahun_dari = $request->bulan_tahun_dari;
            $bulan_tahun_sampai = $request->bulan_tahun_sampai;
            $payroll = TrxPayroll::where(['status'=>'apm'])->where($where)->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])->orderByRaw('tahun desc, bulan desc')->get();
            $payroll_h = TrxPayrollLog::where(['status'=>'apm'])->where($where)->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])->orderByRaw('tahun desc, bulan desc')->get();
        }
        return view('payroll.self', compact('payroll', 'payroll_h', 'bulan_tahun_dari', 'bulan_tahun_sampai'));
    }
    
    public function slip_detail(Request $request, $id)
    {
        set_time_limit(600);
        // $pdf = new TCPDF;
        // $pdf::AddPage('P', 'A4');
        // // $pdf::setPrintHeader(false);
        // // $pdf::setPrintFooter(true);
        // $pdf::SetAutoPageBreak(true, 0);
        // $pdf::SetFont('helvetica', '', 8, '', true);
        // $bgImagePath = public_path('assets/images/matta/bg_alita_a4_header.png');
        // $pdf::Image($bgImagePath, 0, 0, 210, 23, '', '', '', false, 300, '', false, false, 0);
        // $bgImagePath = public_path('assets/images/matta/bg_alita_a4_footer.png');
        // $pdf::Image($bgImagePath, 0, 275, 210, 16, '', '', '', false, 300, '', false, false, 0);
        // // $bgImagePath = public_path('assets/images/matta/bg_alita_a4.png');
        // // $pdf::Image($bgImagePath, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 1);
        // $html = '<h1 style="color:red; text-align:left;">'.$pdf::GetX().' = '.$pdf::GetY().'</h1>';
        // $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        // // $pdf::Line(0, 274, 210, 274);
        // $pdf::Output(public_path('assets/penyesuaian_upah/pdf.pdf'), 'I');
        // 
        $bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $id = (new OpenDataController)->decryptWithKey($id);
        // dd($request->all(), $id);
        $payroll = null;
        $_view = $request->view;
        if($_view == 'pdf_h'){
            $payroll = TrxPayrollLog::find($id);
            // $view = \View::make('payroll.pdf_h', compact('payroll'));
        }elseif($_view == 'pdf'){
            $payroll = TrxPayroll::find($id);
            // $view = \View::make('payroll.pdf', compact('payroll'));
        }
        $pic_paraf = Karyawan::where(['nik' => MtrUtility::find(35)->informasi])->first();
        $pic_paraf = $pic_paraf->nama;
        $view = \View::make('payroll.pdf', compact('payroll', '_view', 'pic_paraf'));
        $password = Carbon::parse(Karyawan::where(['nik' => $payroll->nik])->first()->tanggal_lahir)->format("dmY");
        $filename = (new OpenDataController)->encryptWithKey($payroll->nik).' Slip Gaji '.$bulan[$payroll->bulan - 1].' '.$payroll->tahun.'.pdf';
        $html = $view->render();
        $pdf = new TCPDF;
        $pdf::SetTitle($filename);
        // $pdf::SetFont('times', '', 8, '', true);
        $pdf::SetFont('helvetica', '', 8, '', true);
        // Surat Pemberitahuan Penyesuaian Upah
        if(!empty($request->perihal)){
            if($request->perihal == 'pemberitahuan_upah'){
                $str_html = [];
                $page1 = \View::make('payroll.penyesuaian_upah', compact('payroll', '_view', 'pic_paraf'))->render();
                $pdf::AddPage('P', 'A4');
                $pdf::SetAutoPageBreak(true, 0);
                $bgImagePath = public_path('assets/images/matta/bg_alita_a4_header.png');
                $pdf::Image($bgImagePath, 0, 0, 210, 23, '', '', '', false, 300, '', false, false, 0);
                $bgImagePath = public_path('assets/images/matta/bg_alita_a4_footer.png');
                $pdf::Image($bgImagePath, 0, 275, 210, 16, '', '', '', false, 300, '', false, false, 0);
                // $pdf::writeHTML($page1, true, false, true, false, '');
                // penjelasan
                // writeHTMLCell(
                //     $w, $h,           // Lebar & tinggi sel (0 = auto)
                //     $x, $y,           // Posisi X & Y (dalam mm)
                //     $html,           // Konten HTML
                //     $border = 0,      // 0 = tanpa border, 1 = dengan border
                //     $ln = 1,          // 1 = pindah ke baris baru setelahnya
                //     $fill = 0,        // 1 = latar belakang diisi
                //     $reseth = true,   // reset height untuk selanjutnya?
                //     $align = '',      // align: L, C, R, J
                //     $autopadding = true // padding otomatis?
                // )
                $karyawan = json_decode($payroll->nik_property, true);
                // ambil data bulan berjalan
                $where = ['nik'=>$payroll->nik, 'bulan'=>$payroll->bulan, 'tahun'=>$payroll->tahun];
                $arr_id = [1,2,3,4,5];
                $tunjangan_present = TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                $total_tunjangan_present = $tunjangan_present->sum('angka');
                $upah_present = $payroll->gapok + $total_tunjangan_present;
                // ambil data bulan lalu
                $submonth = Carbon::createFromFormat('Y-m-d', "{$payroll->tahun}-{$payroll->bulan}-01")->subMonth();
                $where = ['nik'=>$payroll->nik, 'bulan'=>$submonth->month, 'tahun'=>$submonth->year];
                $payroll_past = TrxPayrollLog::where($where)->first();
                $tunjangan_past = TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                $total_tunjangan_past = $tunjangan_past->sum('angka');
                $upah_past = $payroll_past->gapok + $total_tunjangan_past;
                // dd($payroll, $payroll_past);
                $level_present = '';
                $grade_present = '';
                $level_past = '';
                $grade_past = '';
                // $where = ['tahun'=>$payroll->tahun, 'gapok'=>$payroll->gapok, 't_makan'=>$tunjangan_present[0]->angka, 't_transport'=>$tunjangan_present[1]->angka, 't_jabatan'=>$tunjangan_present[2]->angka, 't_telekomunikasi'=>$tunjangan_present[3]->angka];
                $where = ['gapok'=>$payroll->gapok];
                $str_present = MtrStrukturGaji::where($where)->orderBy('id', 'asc')->first();
                if(!empty($str_present)){
                    $level_present = $str_present->_grade->level;
                    $grade_present = $str_present->_grade->grade;
                    // level = NON STAFF
                    // grade = I
                    if(in_array($payroll->nik, ['01020707028','01031604597','01030908046','01021007065','01021109082','01020703027'])){
                        $level_present = 'NON STAFF';
                        $grade_present = 'I';
                    }
                    // level = STAFF
                    // grade = II
                    elseif(in_array($payroll->nik, ['01032505879','01031806718','01031002054','01031809745','01032307866','01032506880','01032308867'])){
                        $level_present = 'STAFF';
                        $grade_present = 'II';
                    }
                }
                // $where = ['tahun'=>$payroll_past->tahun, 'gapok'=>$payroll_past->gapok, 't_makan'=>$tunjangan_past[0]->angka, 't_transport'=>$tunjangan_past[1]->angka, 't_jabatan'=>$tunjangan_past[2]->angka, 't_telekomunikasi'=>$tunjangan_past[3]->angka];
                $where = ['gapok'=>$payroll_past->gapok];
                $str_past = MtrStrukturGaji::where($where)->orderBy('id', 'asc')->first();
                if(!empty($str_past)){
                    $level_past = $str_past->_grade->level;
                    $grade_past = $str_past->_grade->grade;
                    // level = NON STAFF
                    // grade = I
                    if(in_array($payroll->nik, ['01020707028','01031604597','01030908046','01021007065','01021109082','01020703027'])){
                        $level_past = 'NON STAFF';
                        $grade_past = 'I';
                    }
                    // level = STAFF
                    // grade = II
                    elseif(in_array($payroll->nik, ['01032505879','01031806718','01031002054','01031809745','01032307866','01032506880','01032308867'])){
                        $level_past = 'STAFF';
                        $grade_past = 'II';
                    }
                }
                // 
                $y = 25;
                $border_top = 'border-top: 0.5px thin black;';
                $border_bottom = 'border-bottom: 0.5px thin black;';
                $border_left = 'border-left: 0.5px thin black;';
                $border_right = 'border-right: 0.5px thin black;';
                $indent_kiri = 'text-indent: 5px;';
                $indent_kanan = '<span style="color:white;">x</span>';
                $font_size_family = 'font-size:10px; font-family:helvetica;';
                $text = '<span style="color:red; text-align:left; font-size:14px; font-family:helvetica;"><b><u>CONFIDENTIAL</u></b></span>';
                $pdf::writeHTMLCell(0, 0, 10, $y, $text, 0, 1, 0, true, '', true);
                $text = '<span style="text-align:center; '.$font_size_family.'"><b><u>SURAT PEMBERITAHUAN UPAH</u></b></span>';
                $pdf::writeHTMLCell(0, 0, 10, $y+=10, $text, 0, 1, 0, true, '', true);
                $text = '<span style="text-align:justify; '.$font_size_family.'">No</span>';
                $pdf::writeHTMLCell(0, 0, 10, $y+=10, $text, 0, 1, 0, true, '', true);
                $text = '<span style="text-align:justify; '.$font_size_family.'">: '.$request->no_surat.'</span>';
                $pdf::writeHTMLCell(0, 0, 35, $y, $text, 0, 1, 0, true, '', true);
                $str_html['no_surat'] = $request->no_surat;
                $text = '<span style="text-align:right; '.$font_size_family.'"><b>NIP : '.$payroll->nik.'</b></span>';
                $pdf::writeHTMLCell(0, 0, 50, $y, $text, 0, 1, 0, true, '', true);
                $str_html['nik'] = $payroll->nik;
                $text = '<span style="text-align:justify; '.$font_size_family.'">Kepada</span>';
                $pdf::writeHTMLCell(0, 0, 10, $y+=5, $text, 0, 1, 0, true, '', true);
                $text = '<span style="text-align:justify; '.$font_size_family.'">: '.$karyawan['nama'].'</span>';
                $pdf::writeHTMLCell(0, 0, 35, $y, $text, 0, 1, 0, true, '', true);
                $str_html['nama'] = $karyawan['nama'];
                $text = '<span style="text-align:justify; '.$font_size_family.'">Dari</span>';
                $pdf::writeHTMLCell(0, 0, 10, $y+=5, $text, 0, 1, 0, true, '', true);
                $text = '<span style="text-align:justify; '.$font_size_family.'">: '.$payroll->subdepartemen.'</span>';
                $pdf::writeHTMLCell(0, 0, 35, $y, $text, 0, 1, 0, true, '', true);
                $str_html['departemen'] = $payroll->subdepartemen;
                $text = '<span style="text-align:justify; '.$font_size_family.'">Perihal</span>';
                $pdf::writeHTMLCell(0, 0, 10, $y+=5, $text, 0, 1, 0, true, '', true);
                $text = '<span style="text-align:justify; '.$font_size_family.'">: Penyesuaian Upah</span>';
                $pdf::writeHTMLCell(0, 0, 35, $y, $text, 0, 1, 0, true, '', true);
                $text = '<span style="text-align:justify; '.$font_size_family.'">Berdasarkan '.$request->redaksi.', bersama ini disampaikan bahwa terhitung mulai bulan <b>'.$bulan[$payroll->bulan - 1].' '.$payroll->tahun.'</b> posisi level, grade dan gaji Saudara/i adalah sebagai berikut:</span>';
                $str_html['redaksi'] = $request->redaksi;
                $pdf::writeHTMLCell(0, 0, 10, $y+=10, $text, 0, 1, 0, true, '', true);
                $str_html['bulan_tahun_present'] = $bulan[$payroll->bulan - 1].' '.$payroll->tahun;
                $text = '<table width="100%" cellspacing="0" cellpadding="1" style="text-align:left; '.$font_size_family.' '.$border_top.' '.$border_bottom.' '.$border_left.' '.$border_right.'">
                    <tr>
                        <td width="3%">&nbsp;</td>
                        <td width="27%" style="'.$border_right.'">&nbsp;</td>
                        <td width="35%" style="'.$border_right.' '.$indent_kiri.'"><b>'.strtoupper("sampai ".$bulan[$payroll_past->bulan - 1]." ".$payroll_past->tahun).'</b></td>
                        <td width="35%" style="'.$indent_kiri.'"><b>'.strtoupper("setelah penyesuaian").'</b></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td style="'.$border_right.'">&nbsp;</td>
                        <td style="'.$border_right.'">&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;A.</td>
                        <td style="'.$border_right.'">Level</td>
                        <td style="'.$border_right.' '.$indent_kiri.'">'.$level_past.'</td>
                        <td style="'.$indent_kiri.'">'.$level_present.'</td>
                    </tr>
                    <tr>
                        <td>&nbsp;B.</td>
                        <td style="'.$border_right.'">Grade</td>
                        <td style="'.$border_right.' '.$indent_kiri.'">'.$grade_past.'</td>
                        <td style="'.$indent_kiri.'">'.$grade_present.'</td>
                    </tr>
                    <tr>
                        <td>&nbsp;C.</td>
                        <td style="'.$border_right.'">Gaji Pokok</td>
                        <td style="'.$border_right.' text-align:right;"><b>'.number_format($payroll_past->gapok, 0).'</b>'.$indent_kanan.'</td>
                        <td style="text-align:right;"><b>'.number_format($payroll->gapok, 0).'</b>'.$indent_kanan.'</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td style="'.$border_right.'">&nbsp;</td>
                        <td style="'.$border_right.'">&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;D.</td>
                        <td style="'.$border_right.'">Tunjangan</td>
                        <td style="'.$border_right.'">&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td style="'.$border_right.'">Makan</td>
                        <td style="'.$border_right.' text-align:right;">'.number_format($tunjangan_past[0]->angka, 0).''.$indent_kanan.'</td>
                        <td style="text-align:right;">'.number_format($tunjangan_present[0]->angka, 0).''.$indent_kanan.'</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td style="'.$border_right.'">Transport</td>
                        <td style="'.$border_right.' text-align:right;">'.number_format($tunjangan_past[1]->angka, 0).''.$indent_kanan.'</td>
                        <td style="text-align:right;">'.number_format($tunjangan_present[1]->angka, 0).''.$indent_kanan.'</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td style="'.$border_right.'">Jabatan</td>
                        <td style="'.$border_right.' text-align:right;">'.number_format($tunjangan_past[2]->angka, 0).''.$indent_kanan.'</td>
                        <td style="text-align:right;">'.number_format($tunjangan_present[2]->angka, 0).''.$indent_kanan.'</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td style="'.$border_right.'">Komunikasi</td>
                        <td style="'.$border_right.' text-align:right;">'.number_format($tunjangan_past[3]->angka, 0).''.$indent_kanan.'</td>
                        <td style="text-align:right;">'.number_format($tunjangan_present[3]->angka, 0).''.$indent_kanan.'</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td style="'.$border_right.'">Lain-lain</td>
                        <td style="'.$border_right.' text-align:right;">'.number_format($tunjangan_past[4]->angka, 0).''.$indent_kanan.'</td>
                        <td style="text-align:right;">'.number_format($tunjangan_present[4]->angka, 0).''.$indent_kanan.'</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td style="'.$border_right.'">Kesehatan</td>
                        <td style="'.$border_right.' text-align:right;">*'.$indent_kanan.'</td>
                        <td style="text-align:right;">*'.$indent_kanan.'</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td style="'.$border_right.'">BPJS TK</td>
                        <td style="'.$border_right.' text-align:right;">**'.$indent_kanan.'</td>
                        <td style="text-align:right;">**'.$indent_kanan.'</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td style="'.$border_right.'">PPH 21</td>
                        <td style="'.$border_right.' text-align:right;">***'.$indent_kanan.'</td>
                        <td style="text-align:right;">***'.$indent_kanan.'</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td style="'.$border_right.'">Total Tunjangan</td>
                        <td style="'.$border_right.' text-align:right;"><b>'.number_format($total_tunjangan_past, 0).'</b>'.$indent_kanan.'</td>
                        <td style="text-align:right;"><b>'.number_format($total_tunjangan_present, 0).'</b>'.$indent_kanan.'</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td style="'.$border_right.'">&nbsp;</td>
                        <td style="'.$border_right.'">&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td style="'.$border_right.'"><b>Take Home Pay (THP)</b></td>
                        <td style="'.$border_right.' text-align:right;"><b>'.number_format($upah_past, 0).'</b>'.$indent_kanan.'</td>
                        <td style="text-align:right;"><b>'.number_format($upah_present, 0).'</b>'.$indent_kanan.'</td>
                    </tr>
                </table>';
                $str_html['bulan_tahun_past'] = $bulan[$payroll_past->bulan - 1]." ".$payroll_past->tahun;
                $str_html['level_past'] = $level_past;
                $str_html['level_present'] = $level_present;
                $str_html['grade_past'] = $grade_past;
                $str_html['grade_present'] = $grade_present;
                $str_html['gapok_past'] = $payroll_past->gapok;
                $str_html['gapok_present'] = $payroll->gapok;
                $str_html['t_makan_past'] = $tunjangan_past[0]->angka;
                $str_html['t_makan_present'] = $tunjangan_present[0]->angka;
                $str_html['t_transport_past'] = $tunjangan_past[1]->angka;
                $str_html['t_transport_present'] = $tunjangan_present[1]->angka;
                $str_html['t_jabatan_past'] = $tunjangan_past[2]->angka;
                $str_html['t_jabatan_present'] = $tunjangan_present[2]->angka;
                $str_html['t_komunikasi_past'] = $tunjangan_past[3]->angka;
                $str_html['t_komunikasi_present'] = $tunjangan_present[3]->angka;
                $str_html['t_lain_past'] = $tunjangan_past[4]->angka;
                $str_html['t_lain_present'] = $tunjangan_present[4]->angka;
                $str_html['total_tunjangan_past'] = $total_tunjangan_past;
                $str_html['total_tunjangan_present'] = $total_tunjangan_present;
                $str_html['upah_past'] = $upah_past;
                $str_html['upah_present'] = $upah_present;
                $pdf::writeHTMLCell(0, 0, 10, $y+=20, $text, 0, 1, 0, true, '', true);
                $text = '<span style="text-align:justify; '.$font_size_family.'"><u>Catatan</u></span>';
                $pdf::writeHTMLCell(0, 0, 10, $y+=95, $text, 0, 1, 0, true, '', true);
                $text = '<span style="text-align:justify; '.$font_size_family.'">*,**, dan ***, ditanggung oleh perusahaan</span>';
                $pdf::writeHTMLCell(0, 0, 10, $y+=5, $text, 0, 1, 0, true, '', true);
                $text = '<span style="text-align:justify; '.$font_size_family.'">Demikian agar maklum adanya.</span>';
                $pdf::writeHTMLCell(0, 0, 10, $y+=10, $text, 0, 1, 0, true, '', true);
                $text = '<span style="text-align:justify; '.$font_size_family.'">Terima kasih.</span>';
                $pdf::writeHTMLCell(0, 0, 10, $y+=5, $text, 0, 1, 0, true, '', true);
                // reviewer1
                $pic = explode(',', MtrUtility::find(45)->informasi)[0];
                $reviewer1 = TrxPayrollPenyesuaianUpahLog::where(['no_surat'=>$request->no_surat, 'pic'=>$pic, 'id_status'=>1, 'nik'=>$payroll->nik, 'bulan'=>$payroll->bulan, 'tahun'=>$payroll->tahun])->orderBy('id', 'desc')->first();
                // reviewer2
                $pic = explode(',', MtrUtility::find(45)->informasi)[1];
                $reviewer2 = TrxPayrollPenyesuaianUpahLog::where(['no_surat'=>$request->no_surat, 'pic'=>$pic, 'id_status'=>2, 'nik'=>$payroll->nik, 'bulan'=>$payroll->bulan, 'tahun'=>$payroll->tahun])->orderBy('id', 'desc')->first();
                // jika sudah direview oleh reviewer2
                if(!empty($reviewer2)){
                    $tanggal = Carbon::parse($reviewer2->created_at)->format('d').' '.$bulan[Carbon::parse($reviewer2->created_at)->format('m')-1].' '.Carbon::parse($reviewer2->created_at)->format('Y');
                    $pic_property = json_decode($reviewer2->pic_property, true);
                    $text = '<span style="text-align:justify; '.$font_size_family.'">Jakarta, '.$tanggal.'</span>';
                    $pdf::writeHTMLCell(0, 0, 10, $y+=10, $text, 0, 1, 0, true, '', true);
                    $text = '<span style="text-align:justify; '.$font_size_family.'"><b>PT ALITA PRAYA MITRA</b></span>';
                    $pdf::writeHTMLCell(0, 0, 10, $y+=5, $text, 0, 1, 0, true, '', true);
                    $text = '<span style="text-align:justify; '.$font_size_family.'"><b><u>'.strtoupper($pic_property['nama']).'</u></b></span>';
                    $pdf::writeHTMLCell(0, 0, 10, $y+=35, $text, 0, 1, 0, true, '', true);
                    $text = '<span style="text-align:justify; '.$font_size_family.'">'.$pic_property['jabatan'].'</span>';
                    $pdf::writeHTMLCell(0, 0, 10, $y+=5, $text, 0, 1, 0, true, '', true);
                    // reviewer1
                    // $qrcode = public_path('assets/penyesuaian_upah/'.$reviewer1->qrcode);
                    // $pdf::Image($qrcode, 50, 245, 10, 10, '', '', '', false, 300, '', false, false, 0);
                    // reviewer2
                    $qrcode = public_path('assets/penyesuaian_upah/'.$reviewer2->qrcode);
                    $pdf::Image($qrcode, 11.4, 225, 30, 30, '', '', '', false, 300, '', false, false, 0);
                }
                // 
                TrxPayrollEmail::where('id', $request->email_id)->update(['spu' => json_encode($str_html)]);
                // 
            }
        }
        $pdf::AddPage('L', 'A4');
        $pdf::writeHTML($html, true, false, true, false, '');
        // Set PDF protection settings
        // The first parameter: permissions, ['copy', 'print'] means the user can copy and print but cannot modify the PDF
        // The second parameter: User password (this can be empty)
        // The third parameter: Owner password (this is used to unlock for modification)
        // The fourth parameter: Encryption type (0 = no encryption, 256 = AES encryption)
        // $pdf::SetProtection(array('print', 'copy'), $password, $password);
        if($request->password || empty($request->perihal)){
            $pdf::SetProtection(array('print', 'copy'), $password, md5(123456), 0, null);
        }
        // open browser
        // $pdf::Output(public_path($filename), 'I');
        // Surat Pemberitahuan Penyesuaian Upah
        if(!empty($request->perihal)){
            if($request->perihal == 'pemberitahuan_upah'){
                $filePath = public_path('assets/penyesuaian_upah').'/'.$request->filename;
                $pdf::SetTitle($request->filename);
                $pdf::Output($filePath, 'F');
                $pdf::reset();
            }
        }
        else{
            // download pdf
            $pdf::Output(public_path($filename), 'D');
            $pdf::reset();
            return response()->download(public_path($filename));
        }
    }
    
    // Surat Pemberitahuan Penyesuaian Upah
    // tidak digunakan diganti dengan attachment file
    // public function surat_upah(Request $request, $id)
    // {
    //     // dd($request->all(), $id);
    //     $bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    //     $id = (new OpenDataController)->decryptWithKey($id);
    //     $payroll = null;
    //     $_view = $request->view;
    //     if($_view == 'pdf_h'){
    //         $payroll = TrxPayrollLog::find($id);
    //         // $view = \View::make('payroll.pdf_h', compact('payroll'));
    //     }elseif($_view == 'pdf'){
    //         $payroll = TrxPayroll::find($id);
    //         // $view = \View::make('payroll.pdf', compact('payroll'));
    //     }
    //     $pic_paraf = Karyawan::where(['nik' => MtrUtility::find(35)->informasi])->first();
    //     $pic_paraf = $pic_paraf->nama;
    //     $view = \View::make('payroll.pdf', compact('payroll', '_view', 'pic_paraf'));
    //     $password = Carbon::parse(Karyawan::where(['nik' => $payroll->nik])->first()->tanggal_lahir)->format("dmY");
    //     $filename = (new OpenDataController)->encryptWithKey($payroll->nik).' Slip Gaji '.$bulan[$payroll->bulan - 1].' '.$payroll->tahun.'.pdf';
    //     $html = $view->render();
    //     $pdf = new TCPDF;
    //     $pdf::SetTitle($filename);
    //     // $pdf::SetFont('times', '', 8, '', true);
    //     $pdf::SetFont('helvetica', '', 8, '', true);
    //     $pdf::AddPage('L', 'A4');
    //     $pdf::writeHTML($html, true, false, true, false, '');
    //     // Set PDF protection settings
    //     // The first parameter: permissions, ['copy', 'print'] means the user can copy and print but cannot modify the PDF
    //     // The second parameter: User password (this can be empty)
    //     // The third parameter: Owner password (this is used to unlock for modification)
    //     // The fourth parameter: Encryption type (0 = no encryption, 256 = AES encryption)
    //     // $pdf::SetProtection(array('print', 'copy'), $password, $password);
    //     $pdf::SetProtection(array('print', 'copy'), $password, md5(123456), 0, null);
    //     // open browser
    //     // $pdf::Output(public_path($filename), 'I');
    //     // download pdf
    //     $pdf::Output(public_path($filename), 'D');
    //     return response()->download(public_path($filename));
    // }
    
    public function terbilang($x)
    {
        $angka = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
        if ($x < 12)
            return " " . $angka[$x];
        elseif($x < 20)
            return $this->terbilang($x - 10) . " Belas";
        elseif($x < 100)
            return $this->terbilang($x / 10) . " Puluh" . $this->terbilang($x % 10);
        elseif($x < 200)
            return " Seratus" . $this->terbilang($x - 100);
        elseif($x < 1000)
            return $this->terbilang($x / 100) . " Ratus" . $this->terbilang($x % 100);
        elseif($x < 2000)
            return " Seribu" . $this->terbilang($x - 1000);
        elseif($x < 1000000)
            return $this->terbilang($x / 1000) . " Ribu" . $this->terbilang($x % 1000);
        elseif($x < 1000000000)
            return $this->terbilang($x / 1000000) . " Juta" . $this->terbilang($x % 1000000);
    }
    
    public function history($id, Request $request)
    {
        // dd($id, $request->all());
        // $payroll = TrxPayrollLog::where(['status'=>'apm'])->orderByRaw('tahun desc, bulan desc')->get();
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $payroll = TrxPayrollLog::orderByRaw('tahun desc, bulan desc')->get();
        if(Gate::allows('hc_nkp')){
            $payroll = TrxPayrollLog::whereIn('nik',$arr_nik_nkp)->orderByRaw('tahun desc, bulan desc')->get();
        }
        if(in_array(auth()->user()->nik, explode(',', MtrUtility::find(36)->informasi))){
            $payroll = TrxPayrollLog::where(['status'=>'apm'])->orderByRaw('tahun desc, bulan desc')->get();
        }
        // 
        $arr_nik = [];
        $arr_findim_subdepartemen = [];
        $arr_findim_departemen = [];
        $arr_findim_divisi = [];
        $arr_findim_project = [];
        $arr_divisi = [];
        $arr_departemen = [];
        $arr_subdepartemen = [];
        $arr_status_karyawan = [];
        $arr_jabatan = [];
        $arr_level = [];
        foreach(($payroll->toArray()) as $data){
            array_push($arr_nik, $data['nik']);
            array_push($arr_findim_subdepartemen, explode('/', $data['findim_subdepartemen'])[0]);
            array_push($arr_findim_departemen, explode('/', $data['findim_departemen'])[0]);
            array_push($arr_findim_divisi, explode('/', $data['findim_divisi'])[0]);
            array_push($arr_findim_project, explode('/', $data['findim_project'])[0]);
            array_push($arr_divisi, $data['divisi']);
            array_push($arr_departemen, $data['departemen']);
            array_push($arr_subdepartemen, $data['subdepartemen']);
            array_push($arr_status_karyawan, $data['status_karyawan']);
            array_push($arr_jabatan, $data['jabatan']);
            array_push($arr_level, $data['level']);
        }
        // 
        $karyawan = Karyawan::select('nama','nik')->whereIn('nik', $arr_nik)->orderBy('nama', 'asc')->get();
        $findim_subdepartemen = MtrFindimSubdepartemen::whereIn('subdepartemen_id', $arr_findim_subdepartemen)->orderBy('subdepartemen_id', 'asc')->get();
        $findim_departemen = MtrFindimDepartemen::whereIn('departemen_id', $arr_findim_departemen)->orderBy('departemen_id', 'asc')->get();
        $findim_divisi = MtrFindimDivisi::whereIn('divisi_id', $arr_findim_divisi)->orderBy('divisi_id', 'asc')->get();
        $findim_project = MtrFindimProject::whereIn('project_id', $arr_findim_project)->orderBy('project_id', 'asc')->get();
        $divisi = Divisi::whereIn('nama', $arr_divisi)->orderBy('nama', 'asc')->groupBy('nama')->get();
        $departemen = Departemen::whereIn('nama', $arr_departemen)->orderBy('nama', 'asc')->groupBy('nama')->get();
        $subdepartemen = SubDepartemen::whereIn('nama', $arr_subdepartemen)->orderBy('nama', 'asc')->groupBy('nama')->get();
        $status_karyawan = KaryawanStatus::whereIn('status_karyawan', $arr_status_karyawan)->orderBy('status_karyawan', 'asc')->groupBy('status_karyawan')->get();
        $jabatan = Jabatan::whereIn('jabatan', $arr_jabatan)->orderBy('jabatan', 'asc')->groupBy('jabatan')->get();
        $level = Level::whereIn('level', $arr_level)->orderBy('id', 'asc')->groupBy('level')->get();
        // filter parameter
        $bulan_tahun_dari = '';
        $bulan_tahun_sampai = '';
        $_arr_nik = [];
        $_arr_findim_subdepartemen = [];
        $_arr_findim_departemen = [];
        $_arr_findim_divisi = [];
        $_arr_findim_project = [];
        $_arr_divisi = [];
        $_arr_departemen = [];
        $_arr_subdepartemen = [];
        $_arr_status_karyawan = [];
        $_arr_jabatan = [];
        $_arr_level = [];
        // default only one month before and year displayed
        $where = ['bulan'=>Carbon::now()->subMonth()->month, 'tahun'=>Carbon::now()->subMonth()->year];
        $payroll = TrxPayrollLog::where(['status'=>'apm'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
        $payroll_out = TrxPayrollLog::where(['status'=>'osc'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
        if(Gate::allows('hc_nkp')){
            $payroll = TrxPayrollLog::whereIn('nik',$arr_nik_nkp)->where(['status'=>'apm'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
            $payroll_out = TrxPayrollLog::whereIn('nik',$arr_nik_nkp)->where(['status'=>'osc'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
        }
        // start filter
        if($request->filter == true){
            $bulan_tahun_dari = $request->bulan_tahun_dari;
            $bulan_tahun_sampai = $request->bulan_tahun_sampai;
            // $_arr_nik = [$request->nik];
            $_arr_nik = $request->input('nik', []);
            $_arr_findim_subdepartemen = $request->input('findim_subdepartemen', []);
            $_arr_findim_departemen = $request->input('findim_departemen', []);
            $_arr_findim_divisi = $request->input('findim_divisi', []);
            $_arr_findim_project = $request->input('findim_project', []);
            $_arr_divisi = $request->input('divisi', []);
            $_arr_departemen = $request->input('departemen', []);
            $_arr_subdepartemen = $request->input('subdepartemen', []);
            $_arr_status_karyawan = $request->input('status_karyawan', []);
            $_arr_jabatan = $request->input('jabatan', []);
            $_arr_level = $request->input('level', []);
            $payroll = TrxPayrollLog::where(['status'=>'apm'])->whereIn('nik', $_arr_nik)
            // ->whereIn('findim_subdepartemen', $_arr_findim_subdepartemen)
            // ->whereIn('findim_departemen', $_arr_findim_departemen)
            // ->whereIn('findim_divisi', $_arr_findim_divisi)
            // ->whereIn('findim_project', $_arr_findim_project)
            // ->whereIn('divisi', $_arr_divisi)
            // ->whereIn('departemen', $_arr_departemen)
            // ->whereIn('subdepartemen', $_arr_subdepartemen)
            ->whereIn('status_karyawan', $_arr_status_karyawan)
            // ->whereIn('jabatan', $_arr_jabatan)
            ->whereIn('level', $_arr_level)
            ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
            ->orderByRaw('tahun desc, bulan desc')->get();
            // 
            $payroll_out = TrxPayrollLog::where(['status'=>'osc'])->whereIn('nik', $_arr_nik)
            ->whereIn('status_karyawan', $_arr_status_karyawan)
            ->whereIn('level', $_arr_level)
            ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
            ->orderByRaw('tahun desc, bulan desc')->get();
            if(Gate::allows('hc_nkp')){
                $payroll = TrxPayrollLog::whereIn('nik',$arr_nik_nkp)->where(['status'=>'apm'])->whereIn('nik', $_arr_nik)
                // ->whereIn('findim_subdepartemen', $_arr_findim_subdepartemen)
                // ->whereIn('findim_departemen', $_arr_findim_departemen)
                // ->whereIn('findim_divisi', $_arr_findim_divisi)
                // ->whereIn('findim_project', $_arr_findim_project)
                // ->whereIn('divisi', $_arr_divisi)
                // ->whereIn('departemen', $_arr_departemen)
                // ->whereIn('subdepartemen', $_arr_subdepartemen)
                ->whereIn('status_karyawan', $_arr_status_karyawan)
                // ->whereIn('jabatan', $_arr_jabatan)
                ->whereIn('level', $_arr_level)
                ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
                ->orderByRaw('tahun desc, bulan desc')->get();
                // 
                $payroll_out = TrxPayrollLog::whereIn('nik',$arr_nik_nkp)->where(['status'=>'osc'])->whereIn('nik', $_arr_nik)
                ->whereIn('status_karyawan', $_arr_status_karyawan)
                ->whereIn('level', $_arr_level)
                ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
                ->orderByRaw('tahun desc, bulan desc')->get();
            }
            // 
            if(in_array('0', $_arr_nik)){
                // $payroll = TrxPayrollLog::where(['status'=>'apm'])->whereIn('findim_subdepartemen', $_arr_findim_subdepartemen)
                // ->whereIn('findim_departemen', $_arr_findim_departemen)
                // ->whereIn('findim_divisi', $_arr_findim_divisi)
                // ->whereIn('findim_project', $_arr_findim_project)
                // ->whereIn('divisi', $_arr_divisi)
                // ->whereIn('departemen', $_arr_departemen)
                // ->whereIn('subdepartemen', $_arr_subdepartemen)
                $payroll = TrxPayrollLog::where(['status'=>'apm'])->whereIn('status_karyawan', $_arr_status_karyawan)
                // ->whereIn('jabatan', $_arr_jabatan)
                ->whereIn('level', $_arr_level)
                ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
                ->orderByRaw('tahun desc, bulan desc')->get();
                // 
                $payroll_out = TrxPayrollLog::where(['status'=>'osc'])->whereIn('status_karyawan', $_arr_status_karyawan)
                ->whereIn('level', $_arr_level)
                ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
                ->orderByRaw('tahun desc, bulan desc')->get();
                if(Gate::allows('hc_nkp')){
                    $payroll = TrxPayrollLog::whereIn('nik',$arr_nik_nkp)->where(['status'=>'apm'])->whereIn('status_karyawan', $_arr_status_karyawan)
                    // ->whereIn('jabatan', $_arr_jabatan)
                    ->whereIn('level', $_arr_level)
                    ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
                    ->orderByRaw('tahun desc, bulan desc')->get();
                    // 
                    $payroll_out = TrxPayrollLog::whereIn('nik',$arr_nik_nkp)->where(['status'=>'osc'])->whereIn('status_karyawan', $_arr_status_karyawan)
                    ->whereIn('level', $_arr_level)
                    ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
                    ->orderByRaw('tahun desc, bulan desc')->get();
                }
            }
        }
        // end filter
        return view('payroll.history', compact('payroll', 'payroll_out', 'karyawan', 'bulan_tahun_dari', 'bulan_tahun_sampai', 'findim_subdepartemen', 'findim_departemen', 'findim_divisi', 'findim_project', 'divisi', 'departemen', 'subdepartemen', 'status_karyawan', 'jabatan', 'level', '_arr_nik', '_arr_findim_subdepartemen', '_arr_findim_departemen', '_arr_findim_divisi', '_arr_findim_project', '_arr_divisi', '_arr_departemen', '_arr_subdepartemen', '_arr_status_karyawan', '_arr_jabatan', '_arr_level'));
        // return view('payroll.history', compact('payroll'));
    }

    // http://localhost/matta/payroll/bkp/0
    // https://matta-reborn.alita.id/matta/payroll/bkp/0
    public function bkp($id)
    {
        // // bkp MtrPayroll
        // $trx = MtrPayroll::all();
        // foreach($trx as $_trx){
        //     MtrPayrollLog::insert(array(
        //         'nik' => $_trx->nik,
        //         'nik' => $_trx->nik,
        //         'bulan' => $_trx->bulan,
        //         'tahun' => $_trx->tahun,
        //         'gapok' => $_trx->gapok,
        //         'status' => $_trx->status,
        //         'pasangan' => $_trx->pasangan,
        //         'anak' => $_trx->anak,
        //         'tambahan' => $_trx->tambahan,
        //         'no_ktp' => $_trx->no_ktp,
        //         'no_kpj' => $_trx->no_kpj,
        //         'no_jkn' => $_trx->no_jkn,
        //         'nama_rek' => $_trx->nama_rek,
        //         'nama_bank' => $_trx->nama_bank,
        //         'no_rek' => $_trx->no_rek,
        //         'created_at' => Carbon::parse($_trx->created_at) ->format('Y-m-d H:i:s'),
        //         'updated_at' => Carbon::parse($_trx->updated_at) ->format('Y-m-d H:i:s'),
        //     ));
        // }
        // bkp TrxPayroll
        $trx = TrxPayroll::all();
        foreach($trx as $_trx){
            // kesehatan data bulan berjalan
            $date_from = Carbon::createFromFormat('Y-m-d', "{$_trx->tahun}-{$_trx->bulan}-01")->startOfMonth()->format('Y-m-d');
            $date_to = Carbon::createFromFormat('Y-m-d', "{$_trx->tahun}-{$_trx->bulan}-01")->endOfMonth()->format('Y-m-d');
            // $total_kesehatan = 0;
            // 4 = Kehamilan
            $kehamilan = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$_trx->nik."' AND DATE(b.updated_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (4)"))[0]->jumlah_klaim;
            // $total_kesehatan += $kehamilan;
            // 5,6 = Persalinan-Normal dan Persalinan-Caesar
            $persalinan = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$_trx->nik."' AND DATE(b.updated_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (5,6)"))[0]->jumlah_klaim;
            // $total_kesehatan += $persalinan;
            // 2,3 = Kacamata-Frame dan Kacamata-Lensa
            $kacamata = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$_trx->nik."' AND DATE(b.updated_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (2,3)"))[0]->jumlah_klaim;
            // $total_kesehatan += $kacamata;
            // 1 = Kesehatan
            $kesehatan = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$_trx->nik."' AND DATE(b.updated_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (1)"))[0]->jumlah_klaim;
            // $total_kesehatan += $kesehatan;
            // 7 = Ekses
            $ekses = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$_trx->nik."' AND DATE(b.updated_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (7)"))[0]->jumlah_klaim;
            // $total_kesehatan += $ekses;
            // tidak digunakan lagi karena sudah diambil dari trx_tbl_payroll
            // overtime data bulan lalu
            // start
            // $date_from = Carbon::createFromFormat('Y-m-d', "{$_trx->tahun}-{$_trx->bulan}-01")->subMonth()->startOfMonth()->format('Y-m-d');
            // $date_to = Carbon::createFromFormat('Y-m-d', "{$_trx->tahun}-{$_trx->bulan}-01")->subMonth()->endOfMonth()->format('Y-m-d');
            // $where = ['ot_maker_by'=>$_trx->nik, 'ot_approver_status'=>'Approve'];
            // $overtime = Overtime::select(DB::raw('SUM(ROUND(ot_length_rp_adj,-3)) as ot_length_rp_adj'))->where($where)->whereBetween(DB::raw('DATE(ot_start_at)'), [$date_from, $date_to])->orderBy( 'ot_start_at', 'desc' )->groupBy('ot_maker_by')->get();
            // $overtime = $overtime->sum('ot_length_rp_adj');
            // end
            // if($_trx->gapok == 0){
            //     $kehamilan = 0;
            //     $persalinan = 0;
            //     $kacamata = 0;
            //     $kesehatan = 0;
            //     $ekses = 0;
            //     $overtime = 0;
            // }
            TrxPayrollLog::insert(array(
                'nik' => $_trx->nik,
                'nik_property' => $_trx->nik_property,
                'bulan' => $_trx->bulan,
                'tahun' => $_trx->tahun,
                'gapok' => $_trx->gapok,
                'status' => $_trx->status,
                'kehamilan' => $kehamilan,
                'persalinan' => $persalinan,
                'kacamata' => $kacamata,
                'kesehatan' => $kesehatan,
                'ekses' => $ekses,
                'overtime' => $_trx->overtime,
                'overtime_max' => $_trx->overtime_max,
                'overtime_ori' => $_trx->overtime_ori,
                'created_by' => $_trx->created_by,
                'updated_by' => $_trx->updated_by,
                'created_at' => Carbon::parse($_trx->created_at) ->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::parse($_trx->updated_at) ->format('Y-m-d H:i:s'),
                'findim_subdepartemen' => $_trx->findim_subdepartemen,
                'findim_departemen' => $_trx->findim_departemen,
                'findim_divisi' => $_trx->findim_divisi,
                'findim_project' => $_trx->findim_project,
                'perusahaan' => $_trx->perusahaan,
                'divisi' => $_trx->divisi,
                'departemen' => $_trx->departemen,
                'subdepartemen' => $_trx->subdepartemen,
                'status_karyawan' => $_trx->status_karyawan,
                'status_aktif' => $_trx->status_aktif,
                'jabatan' => $_trx->jabatan,
                'level' => $_trx->level,
                'grade' => $_trx->grade,
                'paid_at' => $_trx->paid_at,
                'pajak' => $_trx->pajak,
                'mgt_fee' => $_trx->mgt_fee,
                'mgt_fee_kkwt' => $_trx->mgt_fee_kkwt,
            ));
        }
        // bkp TrxPayrollAddsub
        $trx = TrxPayrollAddsub::all();
        foreach($trx as $_trx){
            TrxPayrollAddsubLog::insert(array(
                'nik' => $_trx->nik,
                'bulan' => $_trx->bulan,
                'tahun' => $_trx->tahun,
                'angka' => $_trx->angka,
                'id_addsub' => $_trx->id_addsub,
                'tipe' => $_trx->tipe,
                'status' => $_trx->status,
                'created_by' => $_trx->created_by,
                'updated_by' => $_trx->updated_by,
                'created_at' => Carbon::parse($_trx->created_at) ->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::parse($_trx->updated_at) ->format('Y-m-d H:i:s'),
            ));
        }
        // bkp TrxPayrollBpjstk
        $trx = TrxPayrollBpjstk::all();
        foreach($trx as $_trx){
            TrxPayrollBpjstkLog::insert(array(
                'nik' => $_trx->nik,
                'bulan' => $_trx->bulan,
                'tahun' => $_trx->tahun,
                'upah' => $_trx->upah,
                'maksimal' => $_trx->maksimal,
                'angka' => $_trx->angka,
                'persen' => $_trx->persen,
                'id_bpjstk' => $_trx->id_bpjstk,
                'status' => $_trx->status,
                'created_by' => $_trx->created_by,
                'updated_by' => $_trx->updated_by,
                'created_at' => Carbon::parse($_trx->created_at) ->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::parse($_trx->updated_at) ->format('Y-m-d H:i:s'),
                'thp_dilaporkan' => $_trx->thp_dilaporkan,
            ));
        }
        // bkp TrxPayrollBpjskes
        $trx = TrxPayrollBpjskes::all();
        foreach($trx as $_trx){
            TrxPayrollBpjskesLog::insert(array(
                'nik' => $_trx->nik,
                'bulan' => $_trx->bulan,
                'tahun' => $_trx->tahun,
                'pasangan' => $_trx->pasangan,
                'anak' => $_trx->anak,
                'tambahan' => $_trx->tambahan,
                'upah' => $_trx->upah,
                'maksimal' => $_trx->maksimal,
                'angka' => $_trx->angka,
                'persen' => $_trx->persen,
                'id_bpjskes' => $_trx->id_bpjskes,
                'status' => $_trx->status,
                'created_by' => $_trx->created_by,
                'updated_by' => $_trx->updated_by,
                'created_at' => Carbon::parse($_trx->created_at) ->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::parse($_trx->updated_at) ->format('Y-m-d H:i:s'),
                'thp_dilaporkan' => $_trx->thp_dilaporkan,
            ));
        }
        // menjadi bulan dan tahun berjalan
        // MtrPayroll::whereNotNull('id')->update(['bulan' => Carbon::now()->month, 'tahun' => Carbon::now()->year]);
        TrxPayroll::truncate();
        TrxPayrollAddsub::truncate();
        TrxPayrollBpjstk::truncate();
        TrxPayrollBpjskes::truncate();
        // dd('done');
    }

    public function upsert_master(Request $request)
    {
        // dd($request->all());
        $id_data = $request->input('id_data');
        if(!empty($id_data)){
            $id_data = (new OpenDataController)->decryptWithKey($id_data);
        }
        // karyawan
        if(in_array($request->_id, [0])){
            if($request->tipe == 'edit_karyawan'){
                $trx = MtrPayroll::find($id_data);
                // $trx->nik = $request->nik;
                $trx->pasangan = $request->pasangan;
                $trx->anak = $request->anak;
                $trx->tambahan = $request->tambahan;
                $trx->no_ktp = $request->no_ktp;
                $trx->no_kpj = $request->no_kpj;
                $trx->no_jkn = $request->no_jkn;
                $trx->nama_rek = $request->nama_rek;
                $trx->nama_bank = $request->nama_bank;
                $trx->no_rek = $request->no_rek;
                $trx->update();
                return redirect('payroll/master/'.$request->_id)->with('msg', 'Data is saved successfully.');
            }
            $trx = New MtrPayroll;
                $trx->nik = $request->nik;
                $trx->pasangan = $request->pasangan;
                $trx->anak = $request->anak;
                $trx->tambahan = $request->tambahan;
                $trx->no_ktp = $request->no_ktp;
                $trx->no_kpj = $request->no_kpj;
                $trx->no_jkn = $request->no_jkn;
                $trx->nama_rek = $request->nama_rek;
                $trx->nama_bank = $request->nama_bank;
                $trx->no_rek = $request->no_rek;
            $trx->save();
            return redirect('payroll/master/'.$request->_id)->with('msg', 'Data is saved successfully.');
        }
        // add dan sub
        elseif(in_array($request->_id, [1, 2])){
            if($request->tipe == 'edit'){
                $trx = MtrPayrollAddsub::find($id_data);
                $trx->nama = $request->nama;
                if($request->bulanan == 'on'){
                    $trx->bulanan = 1;
                }else{
                    $trx->bulanan = 0;
                }
                $trx->update();
                return redirect('payroll/master/'.$request->_id)->with('msg', 'Data is saved successfully.');
            }
            $trx = New MtrPayrollAddsub;
            $trx->nama = $request->nama;
            if($request->_id == 1){
                $trx->tipe = 'add';
            }elseif($request->_id == 2){
                $trx->tipe = 'sub';
            }
            if($request->bulanan == 'on'){
                $trx->bulanan = 1;
            }else{
                $trx->bulanan = 0;
            }
            $trx->save();
            return redirect('payroll/master/'.$request->_id)->with('msg', 'Data is saved successfully.');
        }
        // bpjs
        elseif(in_array($request->_id, [3, 4, 5])){
            if(in_array($request->tipe, ['edit_bpjstk', 'edit_bpjsks', 'edit_bpjsmx'])){
                if(in_array($request->tipe, ['edit_bpjstk'])){
                    $trx = MtrPayrollBpjstk::find($id_data);
                    $trx->nama = $request->nama;
                    $trx->persen = $request->persen;
                }elseif(in_array($request->tipe, ['edit_bpjsks'])){
                    $trx = MtrPayrollBpjskes::find($id_data);
                    $trx->nama = $request->nama;
                    $trx->persen = $request->persen;
                }elseif(in_array($request->tipe, ['edit_bpjsmx'])){
                    $trx = MtrPayrollBpjsMax::find($id_data);
                    $trx->nama = $request->nama;
                    $trx->angka = str_replace(',', '', $request->angka);
                }
                $trx->update();
                return redirect('payroll/master/'.$request->_id)->with('msg', 'Data is saved successfully.');
            }
            elseif(in_array($request->tipe, ['bpjstk', 'bpjsks', 'bpjsmx'])){
                if(in_array($request->tipe, ['bpjstk'])){
                    $trx = New MtrPayrollBpjstk;
                    $trx->nama = $request->nama;
                    $trx->persen = $request->persen;
                }elseif(in_array($request->tipe, ['bpjsks'])){
                    $trx = New MtrPayrollBpjskes;
                    $trx->nama = $request->nama;
                    $trx->persen = $request->persen;
                }elseif(in_array($request->tipe, ['bpjsmx'])){
                    $trx = New MtrPayrollBpjsMax;
                    $trx->nama = $request->nama;
                    $trx->angka = $request->angka;
                }
                $trx->save();
                return redirect('payroll/master/'.$request->_id)->with('msg', 'Data is saved successfully.');
            }
        }
        // trxpayroll
        // TrxPayrollAddsub
        elseif(in_array($request->_id, [6])){
            $data = $request->all();
            if($request->tipe == 'edit_trxpayroll'){
                $trx = TrxPayroll::find($id_data);
                $trx->gapok = str_replace(',', '', $request->gapok);
                $trx->update();
                foreach($data as $_id => $value){
                    if(str_starts_with($_id, 'add_angka_') || str_starts_with($_id, 'sub_angka_')){
                        $addsub = TrxPayrollAddsub::find(explode('_', $_id)[2]);
                        $addsub->angka = str_replace(',', '', $value);
                        $addsub->update();
                    }
                }
                return redirect('payroll')->with('msg', 'Data is saved successfully.');
            }
            $trx = New TrxPayroll;
            $trx->nik = $request->nik;
            $trx->nik_property = $this->getNikProperty($request->nik);
            $trx->bulan = $request->bulan;
            $trx->tahun = $request->tahun;
            $trx->gapok = str_replace(',', '', $request->gapok);
            $trx->save();
            foreach($data as $_id => $value){
                if(str_starts_with($_id, 'add_angka_') || str_starts_with($_id, 'sub_angka_')){
                    $addsub = New TrxPayrollAddsub;
                    $addsub->nik = $request->nik;
                    $addsub->bulan = $request->bulan;
                    $addsub->tahun = $request->tahun;
                    $addsub->id_addsub = explode('_', $_id)[2];
                    $addsub->tipe = explode('_', $_id)[0];
                    $addsub->angka = str_replace(',', '', $value);
                    $addsub->save();
                }
            }
            return redirect('payroll')->with('msg', 'Data is saved successfully.');
        }
    }

    public function upload_payroll(Request $request)
    {
        // dd($request->all());
        set_time_limit(3600);
        $jenis = $request->jenis;
        if($request->hasFile('template')){
            $request->validate([
                'template'=>'required|file|mimes:xls,xlsx|max:2048',
            ]);
            // upload transaksi present
            if($jenis == 'trx'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                // data payroll dan penambahan
                $data0 = $trx[0];
                // data potongan
                // $data1 = $trx[1];
                // 100=Sumber
                // 101=NIP
                // 102=Bulan
                // 103=Tahun
                // 104=Gaji Pokok
                // 204=BPJS Kesehatan
                // 307=BPJS Ketenagakerjaan
                // 401=PPh21
                // 501=Management Fee (%)
                // 502=Management Fee Kompensasi KKWT(%)
                $_100 = array_search(100, $data0[0]);
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_104 = array_search(104, $data0[0]);
                $_204 = array_search(204, $data0[0]);
                $_307 = array_search(307, $data0[0]);
                $_401 = array_search(401, $data0[0]);
                $_501 = array_search(501, $data0[0]);
                $_502 = array_search(502, $data0[0]);
                // dimulai dari baris ke-3
                // data payroll
                for ($x = 2; $x < count($data0); $x++) {
                    // tidak boleh row ada yang kosong
                    if(!empty($data0[$x][$_100])){
                        // delete data existing
                        $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                        TrxPayroll::where($where)->delete();
                        TrxPayrollAddsub::where($where)->delete();
                        TrxPayrollBpjstk::where($where)->delete();
                        TrxPayrollBpjskes::where($where)->delete();
                        TrxPayrollEmail::where($where)->delete();
                        // default akan menggunakan dari mtr_tbl_karyawan
                        $where = ['nik' => $data0[$x][$_101]];
                        $karyawan = Karyawan::where($where)->first();
                        // jika tidak maka gunakan data lampau dari mtr_tbl_karyawan_history
                        $_check = Carbon::create($data0[$x][$_103], $data0[$x][$_102], 25);
                        $check = $_check->isSameMonth(Carbon::now()) && $_check->isSameYear(Carbon::now());
                        if(!$check && KaryawanHistory::whereDate('created_at', $_check)->exists()){
                            $karyawan = KaryawanHistory::whereDate('created_at', $_check)->first();
                        }
                        // dd($karyawan);
                        // $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                        // TrxPayroll::where($where)->delete();
                        $trx = New TrxPayroll;
                        $trx->status = $data0[$x][$_100];
                        $trx->nik = $data0[$x][$_101];
                        $trx->nik_property = $this->getNikProperty($data0[$x][$_101]);
                        $trx->bulan = $data0[$x][$_102];
                        $trx->tahun = $data0[$x][$_103];
                        $trx->gapok = $data0[$x][$_104];
                        $trx->findim_subdepartemen = $karyawan->_findim_subdepartemen->subdepartemen_id.'/'.$karyawan->_findim_subdepartemen->subdepartemen_desc;
                        $trx->findim_departemen = $karyawan->_findim_departemen->departemen_id.'/'.$karyawan->_findim_departemen->departemen_desc;
                        $trx->findim_divisi = $karyawan->_findim_divisi->divisi_id.'/'.$karyawan->_findim_divisi->divisi_desc;
                        $trx->findim_project = $karyawan->_findim_project->project_id.'/'.$karyawan->_findim_project->project_desc;
                        $trx->perusahaan = $karyawan->_perusahaan->nama;
                        $trx->divisi = $karyawan->_divisi->nama;
                        $trx->departemen = $karyawan->_departemen->nama;
                        $trx->subdepartemen = $karyawan->_subdepartemen->nama;
                        $trx->status_karyawan = $karyawan->_status->status_karyawan;
                        $trx->status_aktif = 'Aktif';
                        $trx->jabatan = $karyawan->_jabatan->jabatan;
                        $trx->level = $karyawan->_level->level;
                        $trx->grade = $karyawan->_grade->grade;
                        $trx->created_by = auth()->user()->nik;
                        $trx->updated_by = auth()->user()->nik;
                        // overtime data bulan lalu
                        // start
                        $date_from = Carbon::createFromFormat('Y-m-d', "{$data0[$x][$_103]}-{$data0[$x][$_102]}-01")->subMonth()->startOfMonth()->format('Y-m-d');
                        $date_to = Carbon::createFromFormat('Y-m-d', "{$data0[$x][$_103]}-{$data0[$x][$_102]}-01")->subMonth()->endOfMonth()->format('Y-m-d');
                        $where = ['ot_maker_by'=>$data0[$x][$_101], 'ot_approver_status'=>'Approve'];
                        $overtime = Overtime::select(DB::raw('SUM(ROUND(ot_length_rp_adj,-3)) as ot_length_rp_adj'))->where($where)->whereBetween(DB::raw('DATE(ot_start_at)'), [$date_from, $date_to])->orderBy( 'ot_start_at', 'desc' )->groupBy('ot_maker_by')->get();
                        $overtime = $overtime->sum('ot_length_rp_adj');
                        $trx->overtime = $overtime;
                        $overtime_max = MtrUtility::find(33)->informasi;
                        $trx->overtime_max = $overtime_max;
                        // overtime_ori sama dengan overtime
                        $trx->overtime_ori = $overtime;
                        // jika overtime > overtime_max maka overtime = overtime_max
                        if($overtime > $overtime_max){
                            $overtime = $overtime_max;
                        }
                        // end
                        // hanya outsource saja
                        if($data0[$x][$_100] == 'osc'){
                            // 401=PPh21
                            $trx->pajak = $data0[$x][$_401];
                            // parameter tidak digunakan lagi
                            // 4=Management Fee(%)
                            // $trx->mgt_fee = MtrPayrollBpjsMax::find(4)->angka;
                            // 5=Management Fee Kompensasi KKWT(%)
                            // $trx->mgt_fee_kkwt = MtrPayrollBpjsMax::find(5)->angka;
                            // 
                            // 501=Management Fee (%)
                            $trx->mgt_fee = $data0[$x][$_501]*100;
                            // 502=Management Fee Kompensasi KKWT(%)
                            $trx->mgt_fee_kkwt = $data0[$x][$_502]*100;
                            // dd($data0[$x][$_501], $data0[$x][$_502]);
                        }
                        $trx->save();
                    }
                }
                // data tambahan
                $addsub = MtrPayrollAddsub::whereIn('id', $data0[0])->orderBy('id', 'asc')->get();
                $arr = [];
                foreach($addsub as $_addsub){
                    if(in_array($_addsub->id, $data0[0])){
                        $_arr = [];
                        array_push($_arr, array_search($_addsub->id, $data0[0]), $_addsub->id, $_addsub->tipe);
                    }
                    array_push($arr, $_arr);
                }
                for ($x = 2; $x < count($data0); $x++) {
                    foreach($arr as $_arr){
                        // $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_addsub'=>$_arr[1]];
                        // TrxPayrollAddsub::where($where)->delete();
                        if(!empty($data0[$x][$_100])){
                            $trx = New TrxPayrollAddsub;
                            $trx->status = $data0[$x][$_100];
                            $trx->nik = $data0[$x][$_101];
                            $trx->bulan = $data0[$x][$_102];
                            $trx->tahun = $data0[$x][$_103];
                            // 0=index data
                            $trx->angka = $data0[$x][$_arr[0]];
                            // 1=id_addsub
                            $trx->id_addsub = $_arr[1];
                            // 2=tipe
                            $trx->tipe = $_arr[2];
                            $trx->created_by = auth()->user()->nik;
                            $trx->updated_by = auth()->user()->nik;
                            $trx->save();
                        }
                    }
                    // hanya alita saja
                    if($data0[$x][$_100] == 'apm'){
                        // insert into bpjs
                        $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                        $payroll = TrxPayroll::where($where)->orderBy('id', 'asc')->get();
                        $this->insertBpjs($payroll);
                        // sudah tidak diguankan lagi refer to CM 371
                        // // membandingkan data 1 bulan sebelumnya untuk mengecek apakah ada penambahan Insentif dan atau Kompensasi KKWT
                        // $is_insentif = false;
                        // $is_kompensasi = false;
                        // 6=Insentif
                        // $arr_id = [6];
                        // $insentif = TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get()->sum('angka');
                        // // 9=Kompensasi KKWT
                        // $arr_id = [9];
                        // $kompensasi = TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get()->sum('angka');
                        // // ambil kondisi bulan lalu
                        // $submonth = Carbon::createFromFormat('Y-m-d', "{$data0[$x][$_103]}-{$data0[$x][$_102]}-01")->subMonth();
                        // $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$submonth->month, 'tahun'=>$submonth->year];
                        // $arr_id = [6];
                        // $insentif_submonth = TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get()->sum('angka');
                        // $arr_id = [9];
                        // $kompensasi_submonth = TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get()->sum('angka');
                        // if($insentif > $insentif_submonth){
                        //     $is_insentif = true;
                        // }
                        // if($kompensasi > $kompensasi_submonth){
                        //     $is_kompensasi = true;
                        // }
                        // 
                        // sudah tidak diguankan lagi refer to CM 371
                        // // cek bulan sebelumnya
                        // // insert into payroll_email
                        // if($is_insentif && $is_kompensasi){
                        //     // delete data existing
                        //     $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                        //     TrxPayrollEmail::where($where)->delete();
                        //     // insert new data
                        //     $trx = New TrxPayrollEmail;
                        //     $trx->nik = $data0[$x][$_101];
                        //     $trx->nik_property = $this->getNikProperty($data0[$x][$_101]);
                        //     $trx->bulan = $data0[$x][$_102];
                        //     $trx->tahun = $data0[$x][$_103];
                        //     $subject = 'Penambahan Insentif dan Kompensasi PKWT';
                        //     $trx->email = json_encode(['nik'=>$data0[$x][$_101], 'subject'=>$subject, 'body'=>$this->getBodyEmail($karyawan->nama, $data0[$x][$_102], $data0[$x][$_103], 69, $karyawan->kode_kelamin), 'to'=>$karyawan->email, 'cc'=>implode(',', $email_hco)]);
                        //     $trx->id_addsub = '6,9';
                        //     $trx->save();
                        // }
                        // // cek bulan sebelumnya
                        // elseif($is_insentif){
                        //     // delete data existing
                        //     $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                        //     TrxPayrollEmail::where($where)->delete();
                        //     // insert new data
                        //     $trx = New TrxPayrollEmail;
                        //     $trx->nik = $data0[$x][$_101];
                        //     $trx->nik_property = $this->getNikProperty($data0[$x][$_101]);
                        //     $trx->bulan = $data0[$x][$_102];
                        //     $trx->tahun = $data0[$x][$_103];
                        //     $subject = 'Penambahan Insentif';
                        //     $trx->email = json_encode(['nik'=>$data0[$x][$_101], 'subject'=>$subject, 'body'=>$this->getBodyEmail($karyawan->nama, $data0[$x][$_102], $data0[$x][$_103], 6, $karyawan->kode_kelamin), 'to'=>$karyawan->email, 'cc'=>implode(',', $email_hco)]);
                        //     $trx->id_addsub = '6';
                        //     $trx->save();
                        // }
                        // // cek bulan sebelumnya
                        // elseif($is_kompensasi){
                        //     // delete data existing
                        //     $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                        //     TrxPayrollEmail::where($where)->delete();
                        //     // insert new data
                        //     $trx = New TrxPayrollEmail;
                        //     $trx->nik = $data0[$x][$_101];
                        //     $trx->nik_property = $this->getNikProperty($data0[$x][$_101]);
                        //     $trx->bulan = $data0[$x][$_102];
                        //     $trx->tahun = $data0[$x][$_103];
                        //     $subject = 'Penambahan Kompensasi PKWT';
                        //     $trx->email = json_encode(['nik'=>$data0[$x][$_101], 'subject'=>$subject, 'body'=>$this->getBodyEmail($karyawan->nama, $data0[$x][$_102], $data0[$x][$_103], 9, $karyawan->kode_kelamin), 'to'=>$karyawan->email, 'cc'=>implode(',', $email_hco)]);
                        //     $trx->id_addsub = '9';
                        //     $trx->save();
                        // }
                        // 
                        $arr_addsub = [];
                        // 6=Insentif
                        $arr_id = [6];
                        if(TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get()->sum('angka') > 0){
                            array_push($arr_addsub, 6);
                        }
                        // 9=Kompensasi KKWT
                        $arr_id = [9];
                        if(TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get()->sum('angka') > 0){
                            array_push($arr_addsub, 9);
                        }
                        // 10=Rapel
                        $arr_id = [10];
                        if(TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get()->sum('angka') > 0){
                            array_push($arr_addsub, 10);
                        }
                        $karyawan = Karyawan::where(['nik'=>$data0[$x][$_101]])->first();
                        $email_hco = array();
                        $hco = Karyawan::whereIn('nik', explode(',', MtrUtility::find(32)->informasi))->get();
                        foreach($hco as $data){
                            array_push($email_hco, $data->email);
                        }
                        // delete data existing
                        $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                        TrxPayrollEmail::where($where)->delete();
                        // insert new data
                        $trx = New TrxPayrollEmail;
                        $trx->nik = $data0[$x][$_101];
                        $trx->nik_property = $this->getNikProperty($data0[$x][$_101]);
                        $trx->bulan = $data0[$x][$_102];
                        $trx->tahun = $data0[$x][$_103];
                        // 6=Insentif
                        // 9=Kompensasi KKWT
                        // 10=Rapel
                        $subject = '';
                        if(in_array(6, $arr_addsub)){
                            if(in_array(9, $arr_addsub)){
                                if(in_array(10, $arr_addsub)){
                                    // dd('6 9 10');
                                    $subject = 'Penambahan Insentif dan Kompensasi PKWT dan Rapel';
                                }else{
                                    // dd('6 9');
                                    $subject = 'Penambahan Insentif dan Kompensasi PKWT';
                                }
                            }else{
                                if(in_array(10, $arr_addsub)){
                                    // dd('6 10');
                                    $subject = 'Penambahan Insentif dan Rapel';
                                }else{
                                    // dd('6');
                                    $subject = 'Penambahan Insentif';
                                }
                            }
                        }else{
                            if(in_array(9, $arr_addsub)){
                                if(in_array(10, $arr_addsub)){
                                    // dd('9 10');
                                    $subject = 'Kompensasi PKWT dan Rapel';
                                }else{
                                    // dd('9');
                                    $subject = 'Kompensasi PKWT';
                                }
                            }else{
                                if(in_array(10, $arr_addsub)){
                                    // dd('10');
                                    $subject = 'Rapel';
                                }else{
                                    // NA
                                }
                            }
                        }
                        $trx->email = json_encode(['nik'=>$data0[$x][$_101], 'subject'=>$subject, 'body'=>$this->getBodyEmail($karyawan->nama, $data0[$x][$_102], $data0[$x][$_103], $arr_addsub, $karyawan->kode_kelamin), 'to'=>$karyawan->email, 'cc'=>implode(',', $email_hco)]);
                        $trx->id_addsub = implode(',', $arr_addsub);
                        // jika ada
                        if(count($arr_addsub) > 0){
                            $trx->save();
                        }
                        // membandingkan data 1 bulan sebelumnya untuk mengecek apakah ada Penyesuaian Upah
                        // ambil data bulan berjalan
                        $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                        $arr_id = [1,2,3,4,5];
                        $add = TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                        $total_tunjangan = $add->sum('angka');
                        $upah_present = $payroll[0]->gapok + $total_tunjangan;
                        // ambil data bulan lalu
                        $submonth = Carbon::createFromFormat('Y-m-d', "{$data0[$x][$_103]}-{$data0[$x][$_102]}-01")->subMonth();
                        $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$submonth->month, 'tahun'=>$submonth->year];
                        $payroll = TrxPayrollLog::where($where)->orderBy('id', 'asc')->get();
                        $add = TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                        $total_tunjangan = $add->sum('angka');
                        // $upah_past = $payroll[0]->gapok + $total_tunjangan;
                        $gapok_last = 0;
                        if(!empty($payroll[0]->gapok)){
                            $gapok_last = $payroll[0]->gapok;
                        }
                        $upah_past = $gapok_last + $total_tunjangan;
                        if($upah_present != $upah_past){
                            // insert new data
                            $trx = New TrxPayrollEmail;
                            $trx->nik = $data0[$x][$_101];
                            $trx->nik_property = $this->getNikProperty($data0[$x][$_101]);
                            $trx->bulan = $data0[$x][$_102];
                            $trx->tahun = $data0[$x][$_103];
                            $subject = 'Surat Pemberitahuan Penyesuaian Upah';
                            $arr_addsub = 0;
                            $trx->email = json_encode(['nik'=>$data0[$x][$_101], 'subject'=>$subject, 'body'=>$this->getBodyEmailPenyesuaianUpah($karyawan->nama, $data0[$x][$_102], $data0[$x][$_103], $arr_addsub, $karyawan->kode_kelamin), 'to'=>$karyawan->email, 'cc'=>implode(',', $email_hco)]);
                            $trx->id_addsub = $arr_addsub;
                            $trx->save();
                        }
                    }
                    // hanya outsource saja
                    elseif($data0[$x][$_100] == 'osc'){
                        // insert into bpjs
                        $data_bpjs = ['status'=>$data0[$x][$_100], 'nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'bpjsks'=>$data0[$x][$_204], 'bpjstk'=>$data0[$x][$_307]];
                        $this->insertBpjsOut($data_bpjs);
                    }
                }
                // // data potongan
                // $addsub = MtrPayrollAddsub::whereIn('id', $data1[0])->orderBy('id', 'asc')->get();
                // $arr = [];
                // foreach($addsub as $_addsub){
                //     if(in_array($_addsub->id, $data1[0])){
                //         $_arr = [];
                //         array_push($_arr, array_search($_addsub->id, $data1[0]), $_addsub->id, $_addsub->tipe);
                //     }
                //     array_push($arr, $_arr);
                // }
                // for ($x = 2; $x < count($data1); $x++) {
                //     foreach($arr as $_arr){
                //         $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_addsub'=>$_arr[1]];
                //         TrxPayrollAddsub::where($where)->delete();
                //         $trx = New TrxPayrollAddsub;
                //         $trx->nik = $data1[$x][$_101];
                //         $trx->bulan = $data1[$x][$_102];
                //         $trx->tahun = $data1[$x][$_103];
                //         // 0=index data
                //         $trx->angka = $data1[$x][$_arr[0]];
                //         // 1=id_addsub
                //         $trx->id_addsub = $_arr[1];
                //         // 2=tipe
                //         $trx->tipe = $_arr[2];
                //         $trx->created_by = auth()->user()->nik;
                //         $trx->updated_by = auth()->user()->nik;
                //         $trx->save();
                //     }
                // }
                return redirect('payroll')->with('msg', 'Data is saved successfully.');
            }
            // upload transaksi past
            elseif($jenis == 'trx_past'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                // data payroll dan penambahan
                $data0 = $trx[0];
                // data potongan
                // $data1 = $trx[1];
                // 100=Sumber
                // 101=NIP
                // 102=Bulan
                // 103=Tahun
                // 104=Gaji Pokok
                $_100 = array_search(100, $data0[0]);
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_104 = array_search(104, $data0[0]);
                // delete data existing
                $where = ['nik'=>$data0[2][$_101], 'bulan'=>$data0[2][$_102], 'tahun'=>$data0[2][$_103]];
                TrxPayrollLog::where($where)->delete();
                TrxPayrollAddsubLog::where($where)->delete();
                TrxPayrollBpjstkLog::where($where)->delete();
                TrxPayrollBpjskesLog::where($where)->delete();
                // dimulai dari baris ke-3
                // data payroll
                for ($x = 2; $x < count($data0); $x++) {
                    $where = ['nik' => $data0[$x][$_101]];
                    $karyawan = Karyawan::where($where)->first();
                    // $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                    // TrxPayroll::where($where)->delete();
                    $trx = New TrxPayrollLog;
                    $trx->status = $data0[$x][$_100];
                    $trx->nik = $data0[$x][$_101];
                    $trx->nik_property = $this->getNikProperty($data0[$x][$_101]);
                    $trx->bulan = $data0[$x][$_102];
                    $trx->tahun = $data0[$x][$_103];
                    $trx->gapok = $data0[$x][$_104];
                    $trx->findim_subdepartemen = $karyawan->_findim_subdepartemen->subdepartemen_id.'/'.$karyawan->_findim_subdepartemen->subdepartemen_desc;
                    $trx->findim_departemen = $karyawan->_findim_departemen->departemen_id.'/'.$karyawan->_findim_departemen->departemen_desc;
                    $trx->findim_divisi = $karyawan->_findim_divisi->divisi_id.'/'.$karyawan->_findim_divisi->divisi_desc;
                    $trx->findim_project = $karyawan->_findim_project->project_id.'/'.$karyawan->_findim_project->project_desc;
                    $trx->perusahaan = $karyawan->_perusahaan->nama;
                    $trx->divisi = $karyawan->_divisi->nama;
                    $trx->departemen = $karyawan->_departemen->nama;
                    $trx->subdepartemen = $karyawan->_subdepartemen->nama;
                    $trx->status_karyawan = $karyawan->_status->status_karyawan;
                    $trx->status_aktif = 'Aktif';
                    $trx->jabatan = $karyawan->_jabatan->jabatan;
                    $trx->level = $karyawan->_level->level;
                    $trx->grade = $karyawan->_grade->grade;
                    $trx->created_by = auth()->user()->nik;
                    $trx->updated_by = auth()->user()->nik;
                    $trx->save();
                }
                // data tambahan
                $addsub = MtrPayrollAddsub::whereIn('id', $data0[0])->orderBy('id', 'asc')->get();
                $arr = [];
                foreach($addsub as $_addsub){
                    if(in_array($_addsub->id, $data0[0])){
                        $_arr = [];
                        array_push($_arr, array_search($_addsub->id, $data0[0]), $_addsub->id, $_addsub->tipe);
                    }
                    array_push($arr, $_arr);
                }
                for ($x = 2; $x < count($data0); $x++) {
                    foreach($arr as $_arr){
                        $trx = New TrxPayrollAddsubLog;
                        $trx->status = $data0[$x][$_100];
                        $trx->nik = $data0[$x][$_101];
                        $trx->bulan = $data0[$x][$_102];
                        $trx->tahun = $data0[$x][$_103];
                        // 0=index data
                        $trx->angka = $data0[$x][$_arr[0]];
                        // 1=id_addsub
                        $trx->id_addsub = $_arr[1];
                        // 2=tipe
                        $trx->tipe = $_arr[2];
                        $trx->created_by = auth()->user()->nik;
                        $trx->updated_by = auth()->user()->nik;
                        $trx->save();
                    }
                    // insert into bpjs
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                    $payroll = TrxPayrollLog::where($where)->orderBy('id', 'asc')->get();
                    $this->insertBpjsPast($payroll);
                }
                return redirect('payroll/history/0')->with('msg', 'Data is saved successfully.');
            }
            // upload master
            elseif($jenis == 'mtr'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                $data0 = $trx[0];
                // 101=NIP
                // 102=Bulan
                // 103=Tahun
                // 104=Gaji Pokok
                // 105=Pasangan
                // 106=Anak
                // 107=Tambahan
                // 108=No KTP
                // 109=No KPJ
                // 110=No JKN
                // 111=Rekening Atas Nama
                // 112=No. Rekening
                // 113=Nama Bank
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_104 = array_search(104, $data0[0]);
                $_105 = array_search(105, $data0[0]);
                $_106 = array_search(106, $data0[0]);
                $_107 = array_search(107, $data0[0]);
                $_108 = array_search(108, $data0[0]);
                $_109 = array_search(109, $data0[0]);
                $_110 = array_search(110, $data0[0]);
                $_111 = array_search(111, $data0[0]);
                $_112 = array_search(112, $data0[0]);
                $_113 = array_search(113, $data0[0]);
                // dimulai dari baris ke-3
                // data payroll
                for ($x = 2; $x < count($data0); $x++) {
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                    MtrPayroll::where($where)->delete();
                    $trx = New MtrPayroll;
                    $trx->nik = $data0[$x][$_101];
                    $trx->bulan = $data0[$x][$_102];
                    $trx->tahun = $data0[$x][$_103];
                    $trx->gapok = $data0[$x][$_104];
                    $trx->pasangan = $data0[$x][$_105];
                    $trx->anak = $data0[$x][$_106];
                    $trx->tambahan = $data0[$x][$_107];
                    $trx->no_ktp = $data0[$x][$_108];
                    $trx->no_kpj = $data0[$x][$_109];
                    $trx->no_jkn = $data0[$x][$_110];
                    $trx->nama_rek = $data0[$x][$_111];
                    $trx->nama_bank = $data0[$x][$_112];
                    $trx->no_rek = $data0[$x][$_113];
                    $trx->save();
                }
                return redirect('/payroll/master/0')->with('msg', 'Data is saved successfully.');
            }
            // upload thr present
            elseif($jenis == 'thr'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                // data thr
                $data0 = $trx[0];
                // 101=NIP
                // 102=Bulan
                // 103=Tahun
                // 21=Tunjangan Hari Raya
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_21 = array_search(21, $data0[0]);
                // dimulai dari baris ke-3
                // data thr
                for ($x = 2; $x < count($data0); $x++) {
                    // 21
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_addsub'=>21];
                    $trx = TrxPayrollAddsub::where($where)->first();
                    $trx->angka = $data0[$x][$_21];
                    $trx->update();
                }
                return redirect('payroll')->with('msg', 'Data is saved successfully.');
            }
            // upload thr past
            elseif($jenis == 'thr_past'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                // data thr
                $data0 = $trx[0];
                // 101=NIP
                // 102=Bulan
                // 103=Tahun
                // 21=Tunjangan Hari Raya
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_21 = array_search(21, $data0[0]);
                // dimulai dari baris ke-3
                // data thr
                for ($x = 2; $x < count($data0); $x++) {
                    // 21
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_addsub'=>21];
                    $trx = TrxPayrollAddsubLog::where($where)->first();
                    $trx->angka = $data0[$x][$_21];
                    $trx->update();
                }
                return redirect('payroll/history/0')->with('msg', 'Data is saved successfully.');
            }
            // upload bpjstk
            elseif($jenis == 'bpjstk'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                // data bpjstk
                $data0 = $trx[0];
                // 101=NIP
                // 102=Bulan
                // 103=Tahun
                // 300=Upah Dilaporkan BPJS TK
                // 301=Iuran JKK
                // 302=Iuran JKM
                // 303=Iuran JP PRSHN
                // 304=Iuran JP TK
                // 305=Iuran JHT PRSHN
                // 306=Iuran JHT TK
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_300 = array_search(300, $data0[0]);
                $_301 = array_search(301, $data0[0]);
                $_302 = array_search(302, $data0[0]);
                $_303 = array_search(303, $data0[0]);
                $_304 = array_search(304, $data0[0]);
                $_305 = array_search(305, $data0[0]);
                $_306 = array_search(306, $data0[0]);
                // dimulai dari baris ke-3
                // data bpjstk
                for ($x = 2; $x < count($data0); $x++) {
                    // 300
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                    TrxPayrollBpjstk::where($where)->update(['thp_dilaporkan' => $data0[$x][$_300]]);
                    // 1
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>1];
                    TrxPayrollBpjstk::where($where)->update(['angka' => $data0[$x][$_301]]);
                    // 2
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>2];
                    TrxPayrollBpjstk::where($where)->update(['angka' => $data0[$x][$_302]]);
                    // 3
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>3];
                    TrxPayrollBpjstk::where($where)->update(['angka' => $data0[$x][$_303]]);
                    // 4
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>4];
                    TrxPayrollBpjstk::where($where)->update(['angka' => $data0[$x][$_304]]);
                    // 5
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>5];
                    TrxPayrollBpjstk::where($where)->update(['angka' => $data0[$x][$_305]]);
                    // 6
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>6];
                    TrxPayrollBpjstk::where($where)->update(['angka' => $data0[$x][$_306]]);
                }
                return redirect('payroll')->with('msg', 'Data is saved successfully.');
            }
            // upload bpjsks
            elseif($jenis == 'bpjsks'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                // data bpjsks
                $data0 = $trx[0];
                // 101=NIP
                // 102=Bulan
                // 103=Tahun
                // 200=Upah Dilaporkan BPJS KS
                // 201=Iuran Perusahaan
                // 202=Iuran Karyawan
                // 203=Iuran Karyawan Tambahan Anggota Keluarga
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_200 = array_search(200, $data0[0]);
                $_201 = array_search(201, $data0[0]);
                $_202 = array_search(202, $data0[0]);
                $_203 = array_search(203, $data0[0]);
                // dimulai dari baris ke-3
                // data bpjsks
                for ($x = 2; $x < count($data0); $x++) {
                    // 200
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                    TrxPayrollBpjskes::where($where)->update(['thp_dilaporkan' => $data0[$x][$_200]]);
                    // 1
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjskes'=>1];
                    TrxPayrollBpjskes::where($where)->update(['angka' => $data0[$x][$_201]]);
                    // 2
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjskes'=>2];
                    TrxPayrollBpjskes::where($where)->update(['angka' => $data0[$x][$_202]]);
                    // 3
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjskes'=>3];
                    TrxPayrollBpjskes::where($where)->update(['angka' => $data0[$x][$_203]]);
                }
                return redirect('payroll')->with('msg', 'Data is saved successfully.');
            }
            // upload bpjstk_past
            elseif($jenis == 'bpjstk_past'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                // data bpjstk_past
                $data0 = $trx[0];
                // 101=NIP
                // 102=Bulan
                // 103=Tahun
                // 300=Upah Dilaporkan BPJS TK
                // 301=Iuran JKK
                // 302=Iuran JKM
                // 303=Iuran JP PRSHN
                // 304=Iuran JP TK
                // 305=Iuran JHT PRSHN
                // 306=Iuran JHT TK
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_300 = array_search(300, $data0[0]);
                $_301 = array_search(301, $data0[0]);
                $_302 = array_search(302, $data0[0]);
                $_303 = array_search(303, $data0[0]);
                $_304 = array_search(304, $data0[0]);
                $_305 = array_search(305, $data0[0]);
                $_306 = array_search(306, $data0[0]);
                // dimulai dari baris ke-3
                // data bpjstk_past
                for ($x = 2; $x < count($data0); $x++) {
                    // 300
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                    TrxPayrollBpjstkLog::where($where)->update(['thp_dilaporkan' => $data0[$x][$_300]]);
                    // 1
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>1];
                    TrxPayrollBpjstkLog::where($where)->update(['angka' => $data0[$x][$_301]]);
                    // 2
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>2];
                    TrxPayrollBpjstkLog::where($where)->update(['angka' => $data0[$x][$_302]]);
                    // 3
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>3];
                    TrxPayrollBpjstkLog::where($where)->update(['angka' => $data0[$x][$_303]]);
                    // 4
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>4];
                    TrxPayrollBpjstkLog::where($where)->update(['angka' => $data0[$x][$_304]]);
                    // 5
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>5];
                    TrxPayrollBpjstkLog::where($where)->update(['angka' => $data0[$x][$_305]]);
                    // 6
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>6];
                    TrxPayrollBpjstkLog::where($where)->update(['angka' => $data0[$x][$_306]]);
                }
                return redirect('payroll/history/0')->with('msg', 'Data is saved successfully.');
            }
            // upload bpjsks_past
            elseif($jenis == 'bpjsks_past'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                // data bpjsks_past
                $data0 = $trx[0];
                // 101=NIP
                // 102=Bulan
                // 103=Tahun
                // 200=Upah Dilaporkan BPJS KS
                // 201=Iuran Perusahaan
                // 202=Iuran Karyawan
                // 203=Iuran Karyawan Tambahan Anggota Keluarga
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_200 = array_search(200, $data0[0]);
                $_201 = array_search(201, $data0[0]);
                $_202 = array_search(202, $data0[0]);
                $_203 = array_search(203, $data0[0]);
                // dimulai dari baris ke-3
                // data bpjsks_past
                for ($x = 2; $x < count($data0); $x++) {
                    // 200
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                    TrxPayrollBpjskesLog::where($where)->update(['thp_dilaporkan' => $data0[$x][$_200]]);
                    // 1
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjskes'=>1];
                    TrxPayrollBpjskesLog::where($where)->update(['angka' => $data0[$x][$_201]]);
                    // 2
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjskes'=>2];
                    TrxPayrollBpjskesLog::where($where)->update(['angka' => $data0[$x][$_202]]);
                    // 3
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjskes'=>3];
                    TrxPayrollBpjskesLog::where($where)->update(['angka' => $data0[$x][$_203]]);
                }
                return redirect('payroll/history/0')->with('msg', 'Data is saved successfully.');
            }
            // upload pajak
            elseif($jenis == 'pajak_present'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                // data pajak
                $data0 = $trx[0];
                // 101=NIP
                // 102=Bulan
                // 103=Tahun
                // 401=PPh21
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_401 = array_search(401, $data0[0]);
                // dimulai dari baris ke-3
                // data pajak
                for ($x = 2; $x < count($data0); $x++) {
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                    $trx = TrxPayroll::where($where)->first();
                    $trx->pajak = $data0[$x][$_401];
                    $trx->update();
                }
                return redirect('payroll')->with('msg', 'Data is saved successfully.');
            }
            // upload pajak
            elseif($jenis == 'pajak_past'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                // data pajak
                $data0 = $trx[0];
                // 101=NIP
                // 102=Bulan
                // 103=Tahun
                // 401=PPh21
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_401 = array_search(401, $data0[0]);
                // dimulai dari baris ke-3
                // data pajak
                for ($x = 2; $x < count($data0); $x++) {
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                    $trx = TrxPayrollLog::where($where)->first();
                    $trx->pajak = $data0[$x][$_401];
                    $trx->update();
                }
                return redirect('payroll/history/0')->with('msg', 'Data is saved successfully.');
            }
        }
        // 
        else{
            // update paid_at
            if($jenis == 'paid_at'){
                // dd('paid_at');
                // $paid_at = $request->paid_at;
                $trx = TrxPayroll::find($request->id);
                TrxPayroll::where(['bulan'=>$trx->bulan, 'tahun'=>$trx->tahun])->update(['paid_at' => $request->paid_at]);
                // dd($trx);
                return redirect('payroll')->with('msg', 'Data is saved successfully.');
            }
        }
    }
    
    public function master($id, Request $request)
    {
        // dd($id, $request->all());
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $id_data = $request->input('id_data');
        if(!empty($id_data)){
            $id_data = (new OpenDataController)->decryptWithKey($id_data);
        }
        $tipe = $request->input('tipe');
        $payroll = MtrPayroll::orderBy('id', 'asc')->get();
        $where = ['tipe'=>'add'];
        $add = MtrPayrollAddsub::where($where)->orderBy('id', 'asc')->get();
        $where = ['tipe'=>'sub'];
        $sub = MtrPayrollAddsub::where($where)->orderBy('id', 'asc')->get();
        $bpjstk = MtrPayrollBpjstk::orderBy('id', 'asc')->get();
        $bpjskes = MtrPayrollBpjskes::orderBy('id', 'asc')->get();
        $bpjsmax = MtrPayrollBpjsMax::orderBy('id', 'asc')->get();
        if(in_array($tipe, ['add', 'sub', 'edit', 'bpjstk', 'bpjsks', 'bpjsmx', 'edit_bpjstk', 'edit_bpjsks', 'edit_bpjsmx', 'karyawan', 'edit_karyawan', 'trxpayroll', 'edit_trxpayroll'])){
            $status = 'new';
            if($tipe == 'edit'){
                $status = 'edit';
                $addsub = MtrPayrollAddsub::find($id_data);
                return view('payroll.upsert_addsub', compact('addsub','status','id','tipe'));
            }
            elseif(in_array($tipe, ['add', 'sub'])){
                return view('payroll.upsert_addsub', compact('status','id','tipe'));
            }
            elseif(in_array($tipe, ['bpjstk', 'bpjsks', 'bpjsmx', 'edit_bpjstk', 'edit_bpjsks', 'edit_bpjsmx'])){$status = 'new';
                if(in_array($tipe, ['edit_bpjstk', 'edit_bpjsks', 'edit_bpjsmx'])){
                    $status = 'edit';
                    if(in_array($tipe, ['edit_bpjstk'])){
                        $bpjs = MtrPayrollBpjstk::find($id_data);
                    }elseif(in_array($tipe, ['edit_bpjsks'])){
                        $bpjs = MtrPayrollBpjskes::find($id_data);
                    }elseif(in_array($tipe, ['edit_bpjsmx'])){
                        $bpjs = MtrPayrollBpjsMax::find($id_data);
                    }
                    return view('payroll.upsert_bpjs', compact('bpjs','status','id','tipe'));
                }else{
                    return view('payroll.upsert_bpjs', compact('status','id','tipe'));
                }
            }
            elseif(in_array($tipe, ['karyawan', 'edit_karyawan'])){
                $karyawan = [];
                $where = ['is_aktif' => 1];
                $_karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
                if(in_array($tipe, ['edit_karyawan'])){
                    $status = 'edit';
                    $karyawan = MtrPayroll::find($id_data);
                }
                return view('payroll.upsert_karyawan', compact('_karyawan', 'karyawan', 'status','id','tipe'));
            }
            // trxpayroll
            elseif(in_array($tipe, ['trxpayroll', 'edit_trxpayroll'])){
                $trxpayroll = [];
                $add_trx = [];
                $sub_trx = [];
                $where = ['is_aktif' => 1];
                $_karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
                if(in_array($tipe, ['edit_trxpayroll'])){
                    $status = 'edit';
                    $trxpayroll = TrxPayroll::find($id_data);
                    $where = ['tipe'=>'add', 'nik'=>$trxpayroll->nik, 'bulan'=>$trxpayroll->bulan, 'tahun'=>$trxpayroll->tahun];
                    $add_trx = TrxPayrollAddsub::where($where)->orderBy('id', 'asc')->get();
                    $where = ['tipe'=>'sub', 'nik'=>$trxpayroll->nik, 'bulan'=>$trxpayroll->bulan, 'tahun'=>$trxpayroll->tahun];
                    $sub_trx = TrxPayrollAddsub::where($where)->orderBy('id', 'asc')->get();
                }
                return view('payroll.upsert_payroll', compact('_karyawan', 'trxpayroll', 'status','id','tipe', 'add', 'sub', 'add_trx', 'sub_trx'));
            }
        }else{
            return view('payroll.index_mtr', compact('payroll','add','sub','bpjstk','bpjskes','bpjsmax','id'));
        }
    }
    
    public function insertBpjs($payroll)
    {
        // dd($payroll);
        // $where = ['nik'=>$payroll[0]->nik, 'bulan'=>$payroll[0]->bulan, 'tahun'=>$payroll[0]->tahun];
        // TrxPayrollBpjstk::where($where)->delete();
        // TrxPayrollBpjskes::where($where)->delete();
        $arr_id = [1,2,3,4,5,6];
        $bpjstk = MtrPayrollBpjstk::whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
        $arr_id = [1,2,3];
        $bpjskes = MtrPayrollBpjskes::whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
        // 1=Upah Max BPJS TK
        // 2=Upah Max BPJS KS
        // 3=Anggota Keluarga BPJS KS
        $bpjsmax = MtrPayrollBpjsMax::orderBy('id', 'asc')->get();
        foreach ($payroll as $data){
            // TK
            foreach ($bpjstk as $_data){
                $trx = new TrxPayrollBpjstk;
                $trx->status = $data->status;
                $trx->nik = $data->nik;
                $trx->bulan = $data->bulan;
                $trx->tahun = $data->tahun;
                // 
                $where = ['nik'=>$data->nik, 'tipe'=>'add'];
                $arr_id = [1,2,3,4,5];
                $add = TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                $total_tunjangan = $add->sum('angka');
                // 
                $upah = $data->gapok + $total_tunjangan;
                $trx->upah = $upah;
                // 
                $trx->maksimal = $bpjsmax[0]->angka;
                // tidak digunakan, menjadi upload angka bpjstk
                // $trx->angka = $upah;
                // if($upah > $bpjsmax[0]->angka){
                //     $trx->angka = $bpjsmax[0]->angka;
                // }
                $trx->angka = 0;
                // 
                $trx->persen = $_data->persen;
                $trx->id_bpjstk = $_data->id;
                $trx->created_by = auth()->user()->nik;
                $trx->updated_by = auth()->user()->nik;
                $trx->save();
            }
            // KS
            foreach ($bpjskes as $_data){
                $trx = new TrxPayrollBpjskes;
                $trx->status = $data->status;
                $trx->nik = $data->nik;
                $trx->bulan = $data->bulan;
                $trx->tahun = $data->tahun;
                // 
                $where = ['nik'=>$data->nik, 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                $mtr = MtrPayroll::where($where)->orderBy('id', 'asc')->first();
                if(!empty($mtr)){
                    $trx->pasangan = $mtr->pasangan;
                    $trx->anak = $mtr->anak;
                    $trx->tambahan = $mtr->tambahan;
                }
                // 
                $where = ['nik'=>$data->nik, 'tipe'=>'add'];
                $arr_id = [1,2,3,4,5];
                $add = TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                $total_tunjangan = $add->sum('angka');
                // 
                $upah = $data->gapok + $total_tunjangan;
                $trx->upah = $upah;
                // 
                $trx->maksimal = $bpjsmax[1]->angka;
                // tidak digunakan, menjadi upload angka bpjstk
                // $trx->angka = $upah;
                // if($upah > $bpjsmax[1]->angka){
                //     $trx->angka = $bpjsmax[1]->angka;
                // }
                $trx->angka = 0;
                // 
                $trx->persen = $_data->persen;
                $trx->id_bpjskes = $_data->id;
                $trx->created_by = auth()->user()->nik;
                $trx->updated_by = auth()->user()->nik;
                $trx->save();
            }
        }
    }
    
    public function insertBpjsOut($data_bpjs)
    {
        // 7=BPJS Ketenagakerjaan
        $bpjstk = MtrPayrollBpjstk::find(7);
        // 4=BPJS Kesehatan
        $bpjskes = MtrPayrollBpjskes::find(4);
        // TK
        $trx = new TrxPayrollBpjstk;
        $trx->status = $data_bpjs['status'];
        $trx->nik = $data_bpjs['nik'];
        $trx->bulan = $data_bpjs['bulan'];
        $trx->tahun = $data_bpjs['tahun'];
        $trx->angka = $data_bpjs['bpjstk'];
        $trx->persen = $bpjstk->persen;
        $trx->id_bpjstk = $bpjstk->id;
        $trx->created_by = auth()->user()->nik;
        $trx->updated_by = auth()->user()->nik;
        $trx->save();
        // KS
        $trx = new TrxPayrollBpjskes;
        $trx->status = $data_bpjs['status'];
        $trx->nik = $data_bpjs['nik'];
        $trx->bulan = $data_bpjs['bulan'];
        $trx->tahun = $data_bpjs['tahun'];
        $trx->angka = $data_bpjs['bpjsks'];
        $trx->persen = $bpjskes->persen;
        $trx->id_bpjskes = $bpjskes->id;
        $trx->created_by = auth()->user()->nik;
        $trx->updated_by = auth()->user()->nik;
        $trx->save();
    }
    
    public function insertBpjsPast($payroll)
    {
        $bpjstk = MtrPayrollBpjstk::orderBy('id', 'asc')->get();
        $bpjskes = MtrPayrollBpjskes::orderBy('id', 'asc')->get();
        // 1=Upah Max BPJS TK
        // 2=Upah Max BPJS KS
        // 3=Anggota Keluarga BPJS KS
        $bpjsmax = MtrPayrollBpjsMax::orderBy('id', 'asc')->get();
        foreach ($payroll as $data){
            // TK
            foreach ($bpjstk as $_data){
                $trx = new TrxPayrollBpjstkLog;
                $trx->status = $data->status;
                $trx->nik = $data->nik;
                $trx->bulan = $data->bulan;
                $trx->tahun = $data->tahun;
                // 
                $where = ['nik'=>$data->nik, 'tipe'=>'add'];
                $arr_id = [1,2,3,4,5];
                $add = TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                $total_tunjangan = $add->sum('angka');
                // 
                $upah = $data->gapok + $total_tunjangan;
                $trx->upah = $upah;
                // 
                $trx->maksimal = $bpjsmax[0]->angka;
                // tidak digunakan, menjadi upload angka bpjstk
                // $trx->angka = $upah;
                // if($upah > $bpjsmax[0]->angka){
                //     $trx->angka = $bpjsmax[0]->angka;
                // }
                $trx->angka = 0;
                // 
                $trx->persen = $_data->persen;
                $trx->id_bpjstk = $_data->id;
                $trx->created_by = auth()->user()->nik;
                $trx->updated_by = auth()->user()->nik;
                $trx->save();
            }
            // KS
            foreach ($bpjskes as $_data){
                $trx = new TrxPayrollBpjskesLog;
                $trx->status = $data->status;
                $trx->nik = $data->nik;
                $trx->bulan = $data->bulan;
                $trx->tahun = $data->tahun;
                // 
                $where = ['nik'=>$data->nik, 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                $mtr = MtrPayroll::where($where)->orderBy('id', 'asc')->first();
                if(!empty($mtr)){
                    $trx->pasangan = $mtr->pasangan;
                    $trx->anak = $mtr->anak;
                    $trx->tambahan = $mtr->tambahan;
                }
                // 
                $where = ['nik'=>$data->nik, 'tipe'=>'add'];
                $arr_id = [1,2,3,4,5];
                $add = TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                $total_tunjangan = $add->sum('angka');
                // 
                $upah = $data->gapok + $total_tunjangan;
                $trx->upah = $upah;
                // 
                $trx->maksimal = $bpjsmax[1]->angka;
                // tidak digunakan, menjadi upload angka bpjstk
                // $trx->angka = $upah;
                // if($upah > $bpjsmax[1]->angka){
                //     $trx->angka = $bpjsmax[1]->angka;
                // }
                $trx->angka = 0;
                // 
                $trx->persen = $_data->persen;
                $trx->id_bpjskes = $_data->id;
                $trx->created_by = auth()->user()->nik;
                $trx->updated_by = auth()->user()->nik;
                $trx->save();
            }

        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $trx = TrxPayroll::get();
        // foreach($trx as $_trx){
        //     $nik_property = json_decode($_trx->nik_property, true);
        //     $_trx_ = TrxPayroll::find($_trx->id);
        //     $_trx_->perusahaan = $nik_property['perusahaan'];
        //     $_trx_->update();
        // }
        // 
        // $trx = TrxPayrollLog::get();
        // foreach($trx as $_trx){
        //     $nik_property = json_decode($_trx->nik_property, true);
        //     $_trx_ = TrxPayrollLog::find($_trx->id);
        //     $_trx_->perusahaan = $nik_property['perusahaan'];
        //     $_trx_->update();
        // }
        // dd($trx);
        // dd($request->all());
        // $month = '11';
        // $year = '2024';
        // dd(Carbon::createFromFormat('Y-m-d', "{$year}-{$month}-01")->subMonth()->startOfMonth()->format('Y-m-d'));
        // dd(Carbon::createFromFormat('Y-m-d', "{$year}-{$month}-01")->subMonth()->endOfMonth()->format('Y-m-d'));
        // $payroll = TrxPayroll::where(['status'=>'apm'])->orderByRaw('tahun desc, bulan desc')->get();
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $payroll = TrxPayroll::orderByRaw('tahun desc, bulan desc')->get();
        if(Gate::allows('hc_nkp')){
            $payroll = TrxPayroll::whereIn('nik',$arr_nik_nkp)->orderByRaw('tahun desc, bulan desc')->get();
        }
        if(in_array(auth()->user()->nik, explode(',', MtrUtility::find(36)->informasi))){
            $payroll = TrxPayroll::where(['status'=>'apm'])->orderByRaw('tahun desc, bulan desc')->get();
        }
        // 
        $arr_nik = [];
        $arr_findim_subdepartemen = [];
        $arr_findim_departemen = [];
        $arr_findim_divisi = [];
        $arr_findim_project = [];
        $arr_divisi = [];
        $arr_departemen = [];
        $arr_subdepartemen = [];
        $arr_status_karyawan = [];
        $arr_jabatan = [];
        $arr_level = [];
        foreach(($payroll->toArray()) as $data){
            array_push($arr_nik, $data['nik']);
            array_push($arr_findim_subdepartemen, explode('/', $data['findim_subdepartemen'])[0]);
            array_push($arr_findim_departemen, explode('/', $data['findim_departemen'])[0]);
            array_push($arr_findim_divisi, explode('/', $data['findim_divisi'])[0]);
            array_push($arr_findim_project, explode('/', $data['findim_project'])[0]);
            array_push($arr_divisi, $data['divisi']);
            array_push($arr_departemen, $data['departemen']);
            array_push($arr_subdepartemen, $data['subdepartemen']);
            array_push($arr_status_karyawan, $data['status_karyawan']);
            // array_push($arr_jabatan, $data['jabatan']);
            array_push($arr_level, $data['level']);
        }
        // 
        $karyawan = Karyawan::select('nama','nik')->whereIn('nik', $arr_nik)->orderBy('nama', 'asc')->get();
        $findim_subdepartemen = MtrFindimSubdepartemen::whereIn('subdepartemen_id', $arr_findim_subdepartemen)->orderBy('subdepartemen_id', 'asc')->get();
        $findim_departemen = MtrFindimDepartemen::whereIn('departemen_id', $arr_findim_departemen)->orderBy('departemen_id', 'asc')->get();
        $findim_divisi = MtrFindimDivisi::whereIn('divisi_id', $arr_findim_divisi)->orderBy('divisi_id', 'asc')->get();
        $findim_project = MtrFindimProject::whereIn('project_id', $arr_findim_project)->orderBy('project_id', 'asc')->get();
        $divisi = Divisi::whereIn('nama', $arr_divisi)->orderBy('nama', 'asc')->groupBy('nama')->get();
        $departemen = Departemen::whereIn('nama', $arr_departemen)->orderBy('nama', 'asc')->groupBy('nama')->get();
        $subdepartemen = SubDepartemen::whereIn('nama', $arr_subdepartemen)->orderBy('nama', 'asc')->groupBy('nama')->get();
        $status_karyawan = KaryawanStatus::whereIn('status_karyawan', $arr_status_karyawan)->orderBy('status_karyawan', 'asc')->groupBy('status_karyawan')->get();
        $jabatan = Jabatan::whereIn('jabatan', $arr_jabatan)->orderBy('jabatan', 'asc')->groupBy('jabatan')->get();
        $level = Level::whereIn('level', $arr_level)->orderBy('id', 'asc')->groupBy('level')->get();
        // filter parameter
        $bulan_tahun_dari = '';
        $bulan_tahun_sampai = '';
        $_arr_nik = [];
        $_arr_findim_subdepartemen = [];
        $_arr_findim_departemen = [];
        $_arr_findim_divisi = [];
        $_arr_findim_project = [];
        $_arr_divisi = [];
        $_arr_departemen = [];
        $_arr_subdepartemen = [];
        $_arr_status_karyawan = [];
        $_arr_jabatan = [];
        $_arr_level = [];
        // default only current month and year displayed
        $where = ['bulan'=>Carbon::now()->month, 'tahun'=>Carbon::now()->year];
        $payroll = TrxPayroll::where(['status'=>'apm'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
        $payroll_out = TrxPayroll::where(['status'=>'osc'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
        if(Gate::allows('hc_nkp')){
            $payroll = TrxPayroll::whereIn('nik',$arr_nik_nkp)->where(['status'=>'apm'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
            $payroll_out = TrxPayroll::whereIn('nik',$arr_nik_nkp)->where(['status'=>'osc'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
        }
        // start filter
        if($request->filter == true){
            $bulan_tahun_dari = $request->bulan_tahun_dari;
            $bulan_tahun_sampai = $request->bulan_tahun_sampai;
            // $_arr_nik = [$request->nik];
            $_arr_nik = $request->input('nik', []);
            $_arr_findim_subdepartemen = $request->input('findim_subdepartemen', []);
            $_arr_findim_departemen = $request->input('findim_departemen', []);
            $_arr_findim_divisi = $request->input('findim_divisi', []);
            $_arr_findim_project = $request->input('findim_project', []);
            $_arr_divisi = $request->input('divisi', []);
            $_arr_departemen = $request->input('departemen', []);
            $_arr_subdepartemen = $request->input('subdepartemen', []);
            $_arr_status_karyawan = $request->input('status_karyawan', []);
            // $_arr_jabatan = $request->input('jabatan', []);
            $_arr_level = $request->input('level', []);
            $payroll = TrxPayroll::where(['status'=>'apm'])->whereIn('nik', $_arr_nik)
            // ->whereIn('findim_subdepartemen', $_arr_findim_subdepartemen)
            // ->whereIn('findim_departemen', $_arr_findim_departemen)
            // ->whereIn('findim_divisi', $_arr_findim_divisi)
            // ->whereIn('findim_project', $_arr_findim_project)
            // ->whereIn('divisi', $_arr_divisi)
            // ->whereIn('departemen', $_arr_departemen)
            // ->whereIn('subdepartemen', $_arr_subdepartemen)
            ->whereIn('status_karyawan', $_arr_status_karyawan)
            // ->whereIn('jabatan', $_arr_jabatan)
            ->whereIn('level', $_arr_level)
            ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
            ->orderByRaw('tahun desc, bulan desc')->get();
            // 
            $payroll_out = TrxPayroll::where(['status'=>'osc'])->whereIn('nik', $_arr_nik)
            ->whereIn('status_karyawan', $_arr_status_karyawan)
            ->whereIn('level', $_arr_level)
            ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
            ->orderByRaw('tahun desc, bulan desc')->get();
            if(Gate::allows('hc_nkp')){
                $payroll = TrxPayroll::whereIn('nik',$arr_nik_nkp)->where(['status'=>'apm'])->whereIn('nik', $_arr_nik)
                // ->whereIn('findim_subdepartemen', $_arr_findim_subdepartemen)
                // ->whereIn('findim_departemen', $_arr_findim_departemen)
                // ->whereIn('findim_divisi', $_arr_findim_divisi)
                // ->whereIn('findim_project', $_arr_findim_project)
                // ->whereIn('divisi', $_arr_divisi)
                // ->whereIn('departemen', $_arr_departemen)
                // ->whereIn('subdepartemen', $_arr_subdepartemen)
                ->whereIn('status_karyawan', $_arr_status_karyawan)
                // ->whereIn('jabatan', $_arr_jabatan)
                ->whereIn('level', $_arr_level)
                ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
                ->orderByRaw('tahun desc, bulan desc')->get();
                // 
                $payroll_out = TrxPayroll::whereIn('nik',$arr_nik_nkp)->where(['status'=>'osc'])->whereIn('nik', $_arr_nik)
                ->whereIn('status_karyawan', $_arr_status_karyawan)
                ->whereIn('level', $_arr_level)
                ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
                ->orderByRaw('tahun desc, bulan desc')->get();

            }
            if(in_array('0', $_arr_nik)){
                // $payroll = TrxPayroll::where(['status'=>'apm'])->whereIn('findim_subdepartemen', $_arr_findim_subdepartemen)
                // ->whereIn('findim_departemen', $_arr_findim_departemen)
                // ->whereIn('findim_divisi', $_arr_findim_divisi)
                // ->whereIn('findim_project', $_arr_findim_project)
                // ->whereIn('divisi', $_arr_divisi)
                // ->whereIn('departemen', $_arr_departemen)
                // ->whereIn('subdepartemen', $_arr_subdepartemen)
                $payroll = TrxPayroll::where(['status'=>'apm'])->whereIn('status_karyawan', $_arr_status_karyawan)
                // ->whereIn('jabatan', $_arr_jabatan)
                ->whereIn('level', $_arr_level)
                ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
                ->orderByRaw('tahun desc, bulan desc')->get();
                // 
                $payroll_out = TrxPayroll::where(['status'=>'osc'])->whereIn('status_karyawan', $_arr_status_karyawan)
                ->whereIn('level', $_arr_level)
                ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
                ->orderByRaw('tahun desc, bulan desc')->get();
                if(Gate::allows('hc_nkp')){
                    $payroll = TrxPayroll::whereIn('nik',$arr_nik_nkp)->where(['status'=>'apm'])->whereIn('status_karyawan', $_arr_status_karyawan)
                    // ->whereIn('jabatan', $_arr_jabatan)
                    ->whereIn('level', $_arr_level)
                    ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
                    ->orderByRaw('tahun desc, bulan desc')->get();
                    // 
                    $payroll_out = TrxPayroll::whereIn('nik',$arr_nik_nkp)->where(['status'=>'osc'])->whereIn('status_karyawan', $_arr_status_karyawan)
                    ->whereIn('level', $_arr_level)
                    ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
                    ->orderByRaw('tahun desc, bulan desc')->get();
                }
            }
        }
        // end filter
        return view('payroll.index', compact('payroll', 'payroll_out', 'karyawan', 'bulan_tahun_dari', 'bulan_tahun_sampai', 'findim_subdepartemen', 'findim_departemen', 'findim_divisi', 'findim_project', 'divisi', 'departemen', 'subdepartemen', 'status_karyawan', 'jabatan', 'level', '_arr_nik', '_arr_findim_subdepartemen', '_arr_findim_departemen', '_arr_findim_divisi', '_arr_findim_project', '_arr_divisi', '_arr_departemen', '_arr_subdepartemen', '_arr_status_karyawan', '_arr_jabatan', '_arr_level'));
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
    public function show(Request $request, $id)
    {
        // dd($request->all(), $id);
        $bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $id = (new OpenDataController)->decryptWithKey($id);
        $payroll = null;
        $_view = $request->view;
        if($_view == 'pdf_h'){
            $payroll = TrxPayrollLog::find($id);
            // $view = \View::make('payroll.pdf_h', compact('payroll'));
        }elseif($_view == 'pdf'){
            $payroll = TrxPayroll::find($id);
            // $view = \View::make('payroll.pdf', compact('payroll'));
        }
        $pic_paraf = Karyawan::where(['nik' => MtrUtility::find(35)->informasi])->first();
        $pic_paraf = $pic_paraf->nama;
        $view = \View::make('payroll.pdf', compact('payroll', '_view', 'pic_paraf'));
        $password = Carbon::parse(Karyawan::where(['nik' => $payroll->nik])->first()->tanggal_lahir)->format("dmY");
        $filename = (new OpenDataController)->encryptWithKey($payroll->nik).' Slip Gaji '.$bulan[$payroll->bulan - 1].' '.$payroll->tahun.'.pdf';
        $html = $view->render();
        $pdf = new TCPDF;
        $pdf::SetTitle($filename);
        // $pdf::SetFont('times', '', 8, '', true);
        $pdf::SetFont('helvetica', '', 8, '', true);
        $pdf::AddPage('L', 'A4');
        $pdf::writeHTML($html, true, false, true, false, '');
        // $pdf::SetProtection(array('print', 'copy'), $password, $password);
        $pdf::SetProtection(array('print', 'copy'), $password, md5(123456), 0, null);
        // open browser
        // $pdf::Output(public_path($filename), 'I');
        // download pdf
        $pdf::Output(public_path($filename), 'D');
        return response()->download(public_path($filename));
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
    public function destroy(Request $request, $id)
    {
        // dd($request->all(), $id);
        $_id = $request->id;
        if(!empty($_id)){
            $_id = (new OpenDataController)->decryptWithKey($_id);
        }
        // dd($_id, $id);
        try {
            // karyawan
            if(in_array($id, [0])){
                $trx = MtrPayroll::find($_id);
                $trx->delete();
            }
            // add dan sub
            elseif(in_array($id, [1, 2])){
                $trx = MtrPayrollAddsub::find($_id);
                $trx->delete();
            }
            // bpjstk
            elseif(in_array($id, [3])){
                $trx = MtrPayrollBpjstk::find($_id);
                $trx->delete();
            }
            // bpjsks
            elseif(in_array($id, [4])){
                $trx = MtrPayrollBpjskes::find($_id);
                $trx->delete();
            }
            // bpjsmx
            elseif(in_array($id, [5])){
                $trx = MtrPayrollBpjsMax::find($_id);
                $trx->delete();
            }
            // payroll
            elseif(in_array($id, [6])){
                $trx = TrxPayroll::find($_id);
                $nik = $trx->nik;
                $trx->delete();
                $where = ['nik' => $nik];
                TrxPayrollAddsub::where($where)->delete();
                TrxPayrollBpjstk::where($where)->delete();
                TrxPayrollBpjskes::where($where)->delete();
                return redirect( 'payroll' )->with( 'msg', 'Data is deleted successfully.' );
            }
            return redirect( 'payroll/master/'.$id )->with( 'msg', 'Data is deleted successfully.' );
         } catch (\Exception $e) {
            // Log::error($e->errorInfo);
            return redirect()->route('error_db');
         }
    }

    public function getBodyEmail($nama, $bulan, $tahun, $addsub, $kode_kelamin)
    {
        $kelamin = '';
        // 2=Pria
        if($kode_kelamin == 2){
            $kelamin = 'Bapak';
        }
        // 3=Wanita
        elseif($kode_kelamin == 3){
            $kelamin = 'Ibu';
        }
        $nama_bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $body = '<p>Dear '.$kelamin.' '.$nama.'</p>';
        // 6=Insentif
        // 9=Kompensasi KKWT
        // 10=Rapel
        $str_addsub = '';
        if(in_array(6, $addsub)){
            if(in_array(9, $addsub)){
                if(in_array(10, $addsub)){
                    // dd('6 9 10');
                    $str_addsub = 'penambahan insentif dan penambahan kompensasi masa Perjanjian Kerja Karyawan (PKWT) yang telah berakhir pada akhir '.$nama_bulan[$bulan - 1].' '.$tahun.' dan rapel dalam penggajian bulan '.$nama_bulan[$bulan - 1].' '.$tahun;
                }else{
                    // dd('6 9');
                    $str_addsub = 'penambahan insentif dan penambahan kompensasi masa Perjanjian Kerja Karyawan (PKWT) yang telah berakhir pada akhir '.$nama_bulan[$bulan - 1].' '.$tahun.' dalam penggajian bulan '.$nama_bulan[$bulan - 1].' '.$tahun;
                }
            }else{
                if(in_array(10, $addsub)){
                    // dd('6 10');
                    $str_addsub = 'penambahan insentif dan rapel dalam penggajian bulan '.$nama_bulan[$bulan - 1].' '.$tahun;
                }else{
                    // dd('6');
                    $str_addsub = 'penambahan insentif dalam penggajian bulan '.$nama_bulan[$bulan - 1].' '.$tahun;
                }
            }
        }else{
            if(in_array(9, $addsub)){
                if(in_array(10, $addsub)){
                    // dd('9 10');
                    $str_addsub = 'penambahan kompensasi masa Perjanjian Kerja Karyawan (PKWT) yang telah berakhir pada akhir '.$nama_bulan[$bulan - 1].' '.$tahun.' dan rapel dalam penggajian bulan '.$nama_bulan[$bulan - 1].' '.$tahun;
                }else{
                    // dd('9');
                    $str_addsub = 'penambahan kompensasi masa Perjanjian Kerja Karyawan (PKWT) yang telah berakhir pada akhir '.$nama_bulan[$bulan - 1].' '.$tahun.' dalam penggajian bulan '.$nama_bulan[$bulan - 1].' '.$tahun;
                }
            }else{
                if(in_array(10, $addsub)){
                    // dd('10');
                    $str_addsub = 'rapel dalam penggajian bulan '.$nama_bulan[$bulan - 1].' '.$tahun;
                }else{
                    // NA
                }
            }
        }
        $body .= '<p>Kami informasikan bahwa terdapat '.$str_addsub.'. Informasi lebih detail terdapat dalam slip gaji yang dapat diunduh melalui menu Payroll Slip dalam web Matta (https://matta-reborn.alita.id).</p>';
        // if($addsub == 69){
        //     $body .= '<p>Kami informasikan bahwa terdapat '.$str_addsub.'. Informasi lebih detail terdapat dalam slip gaji yang dapat diunduh melalui menu Payroll Slip dalam web Matta (https://matta-reborn.alita.id).</p>';
        // }elseif($addsub == 6){
        //     $body .= '<p>Kami informasikan bahwa terdapat penambahan insentif dalam penggajian bulan '.$nama_bulan[$bulan - 1].' '.$tahun.'. Informasi lebih detail terdapat dalam slip gaji yang dapat diunduh melalui menu Payroll Slip dalam web Matta (https://matta-reborn.alita.id).</p>';
        // }elseif($addsub == 9){
        //     $body .= '<p>Kami informasikan bahwa terdapat penambahan kompensasi masa Perjanjian Kerja Karyawan (PKWT) yang telah berakhir pada akhir '.$nama_bulan[$bulan - 1].' '.$tahun.' dalam penggajian bulan '.$nama_bulan[$bulan - 1].' '.$tahun.'. Informasi lebih detail terdapat dalam slip gaji yang dapat diunduh melalui menu Payroll Slip dalam web Matta (https://matta-reborn.alita.id).</p>';
        // }
        $body .= '<p>Slip Gaji bersifat <strong style="color:red">PERSONAL & CONFIDENTIAL</strong>. Mohon untuk tidak diberitahukan kepada rekan kerja ataupun pihak lain dengan alasan apapun.</p>';
        $body .= '<p>Demikian kami sampaikan, terimakasih atas perhatian dan kerjasamanya.</p>';
        $body .= MtrUtility::find(47)->informasi;
        return $body;
    }

    public function getBodyEmailPenyesuaianUpah($nama, $bulan, $tahun, $addsub, $kode_kelamin)
    {
        $kelamin = '';
        // 2=Pria
        if($kode_kelamin == 2){
            $kelamin = 'Bapak';
        }
        // 3=Wanita
        elseif($kode_kelamin == 3){
            $kelamin = 'Ibu';
        }
        $redaksi = '';
        $nama_bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $body = '<p>Dear '.$kelamin.' '.$nama.'</p>';
        // $body .= '<p>Berdasarkan Surat Keputusan Direksi No. '.$no_sk_direksi.' tanggal '.$tgl_sk_direksi.' tentang Penyesuaian Upah Minimum tahun '.$tahun.' dan Struktur Upah, berikut kami sampaikan Surat Pemberitahuan perubahan Level, Grade dan/atau Gaji terhitung mulai bulan '.$nama_bulan[$bulan - 1].' '.$tahun.' dan Slip Gaji bulan '.$nama_bulan[$bulan - 1].' '.$tahun.'.</p>';
        $body .= '<p>Berdasarkan '.$redaksi.', berikut kami sampaikan Surat Pemberitahuan perubahan Level, Grade dan/atau Gaji terhitung mulai bulan '.$nama_bulan[$bulan - 1].' '.$tahun.' dan Slip Gaji bulan '.$nama_bulan[$bulan - 1].' '.$tahun.'.</p>';
        $body .= '<p>Surat Pemberitahuan dan Slip Gaji ini adalah bersifat <strong style="color:red">CONFIDENTIAL</strong> dan tidak untuk diberitahukan kepada rekan kerja ataupun Pihak Lain.</p>';
        $body .= '<p>Demikian kami sampaikan, terimakasih atas perhatian dan kerjasamanya.</p>';
        $body .= '<p><i style="color:blue">Notes : password berupa tanggal lahir dengan format DDMMYYYY.</i></p>';
        $body .= MtrUtility::find(47)->informasi;
        return $body;
    }

    public function getNikProperty($nik)
    {
        $where = ['nik'=>$nik];
        // $data = Karyawan::select('mtr_tbl_karyawan.nik', 'mtr_tbl_karyawan.nama', 'mtr_tbl_karyawan.email', 'mtr_tbl_karyawan.telepon1', 'mtr_tbl_perusahaan.nama as perusahaan', 'mtr_tbl_divisi.nama as divisi', 'mtr_tbl_departemen.nama as dep', 'mtr_tbl_sub_departemen.nama as sub_dep', 'mtr_tbl_level.level as level', 'mtr_tbl_jabatan.jabatan as jabatan', 'mtr_tbl_level.id as id_level', 'mtr_tbl_status_karyawan.status_karyawan as status_karyawan')
        //     ->leftJoin('mtr_tbl_perusahaan', 'mtr_tbl_perusahaan.id', '=', 'mtr_tbl_karyawan.kode_perusahaan')
        //     ->leftJoin('mtr_tbl_divisi', 'mtr_tbl_divisi.id', '=', 'mtr_tbl_karyawan.kode_divisi')
        //     ->leftJoin('mtr_tbl_departemen', 'mtr_tbl_departemen.id', '=', 'mtr_tbl_karyawan.kode_departemen')
        //     ->leftJoin('mtr_tbl_sub_departemen', 'mtr_tbl_sub_departemen.id', '=', 'mtr_tbl_karyawan.kode_sub_departemen')
        //     ->leftJoin('mtr_tbl_level', 'mtr_tbl_level.id', '=', 'mtr_tbl_karyawan.kode_level')
        //     ->leftJoin('mtr_tbl_jabatan', 'mtr_tbl_jabatan.id', '=', 'mtr_tbl_karyawan.kode_jabatan')
        //     ->leftJoin('mtr_tbl_status_karyawan', 'mtr_tbl_status_karyawan.id', '=', 'mtr_tbl_karyawan.kode_status')
        //     ->where($where)->first();
        $data = DB::table('mtr_tbl_karyawan as a')->select('a.nik', 'a.nama', 'a.email', 'a.telepon1', 'b.nama as perusahaan', 'c.nama as divisi', 'd.nama as dep', 'e.nama as sub_dep', 'f.level as level', 'g.jabatan as jabatan', 'f.id as id_level', 'h.status_karyawan as status_karyawan', DB::Raw("CONCAT(i.subdepartemen_id, '/', i.subdepartemen_desc) as findim_subdepartemen"), DB::Raw("CONCAT(j.departemen_id, '/', j.departemen_desc) as findim_departemen"), DB::Raw("CONCAT(k.divisi_id, '/', k.divisi_desc) as findim_divisi"), DB::Raw("CONCAT(l.project_id, '/', l.project_desc) as findim_project"))
            ->leftJoin('mtr_tbl_perusahaan as b', 'b.id', '=', 'a.kode_perusahaan')
            ->leftJoin('mtr_tbl_divisi as c', 'c.id', '=', 'a.kode_divisi')
            ->leftJoin('mtr_tbl_departemen as d', 'd.id', '=', 'a.kode_departemen')
            ->leftJoin('mtr_tbl_sub_departemen as e', 'e.id', '=', 'a.kode_sub_departemen')
            ->leftJoin('mtr_tbl_level as f', 'f.id', '=', 'a.kode_level')
            ->leftJoin('mtr_tbl_jabatan as g', 'g.id', '=', 'a.kode_jabatan')
            ->leftJoin('mtr_tbl_status_karyawan as h', 'h.id', '=', 'a.kode_status')
            ->leftJoin('mtr_tbl_findim_subdepartemen as i', 'i.id', '=', 'a.findim_subdepartemen')
            ->leftJoin('mtr_tbl_findim_departemen as j', 'j.id', '=', 'a.findim_departemen')
            ->leftJoin('mtr_tbl_findim_divisi as k', 'k.id', '=', 'a.findim_divisi')
            ->leftJoin('mtr_tbl_findim_project as l', 'l.id', '=', 'a.findim_project')
            ->where($where)->first();
        return json_encode($data);
    }

    public function download_xlsx(Request $request, $id)
    {
        if($id == 0){
            return Excel::download(new TrxPayrollEmailExport(), 'Surat Pemberitahuan Penyesuaian Upah.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }
    }

    public function upload_pemberitahuan_upah(Request $request, $id)
    {
        // dd($request->all(), $id);
        if($id == 0){
            if($request->hasFile('template')){
                $request->validate([
                    'template'=>'required|file|mimes:xls,xlsx|max:2048',
                ]);
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                $data0 = $trx[0];
                // dd($data0);
                // 1=#ID
                // 2=Tahun
                // 3=Bulan
                // 4=Nama
                // 5=NIP
                // 6=Email
                // 7=Subyek Email
                // 8=Redaksi
                $_1 = array_search(1, $data0[0]);
                $_2 = array_search(2, $data0[0]);
                $_3 = array_search(3, $data0[0]);
                $_4 = array_search(4, $data0[0]);
                $_5 = array_search(5, $data0[0]);
                $_6 = array_search(6, $data0[0]);
                $_7 = array_search(7, $data0[0]);
                $_8 = array_search(8, $data0[0]);
                // dimulai dari baris ke-3
                // reset nomor surat
                $i = 0;
                for ($x = 2; $x < count($data0); $x++) {
                    // hanya yang beredaksi yang diproses
                    if(!empty($data0[$x][$_8])){
                        $_id = $data0[$x][$_1];
                        $tahun = $data0[$x][$_2];
                        $bulan = $data0[$x][$_3];
                        $nik = $data0[$x][$_5];
                        $trx = TrxPayrollEmail::where(['id'=>$_id, 'tahun'=>$tahun, 'bulan'=>$bulan, 'nik'=>$nik])->first();
                        $redaksi = $data0[$x][$_8];
                        $trx->param1 = $redaksi;
                        // $filePath = public_path('assets/penyesuaian_upah').'/'.(new OpenDataController)->encryptWithKey($payroll->nik.'_'.$payroll->bulan.'_'.$payroll->tahun).'_Surat Pemberitahuan Penyesuaian Upah.pdf';
                        $filename = (new OpenDataController)->encryptWithKey($nik).'_Surat Pemberitahuan Penyesuaian Upah_'.$nik.'_'.$bulan.'_'.$tahun.'.pdf';
                        $trx->filename = $filename;
                        // nomor surat
                        // contoh: 009-LTR/SPU/ADM-APM/PC/I/2025
                        // revisi 009-LTR/SPU/ADM-APM/PC/I/2025
                        $romawi = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
                        $hitung = TrxPayrollEmailLog::where(['tahun'=>$tahun, 'id_addsub'=>'0'])->count();
                        $no_surat = str_pad($hitung + (++$i), 3, '0', STR_PAD_LEFT).'-LTR/SPU/ADM-APM/PC/'.$romawi[$bulan-1].'/'.$tahun;
                        $trx->no_surat = $no_surat;
                        // 
                        $nik_property = json_decode($trx->nik_property, true);
                        $email = json_decode($trx->email, true);
                        // 
                        $karyawan = Karyawan::where(['nik'=>$nik_property['nik']])->first();
                        $kelamin = '';
                        // 2=Pria
                        if($karyawan->kode_kelamin == 2){
                            $kelamin = 'Bapak';
                        }
                        // 3=Wanita
                        elseif($karyawan->kode_kelamin == 3){
                            $kelamin = 'Ibu';
                        }
                        $nama_bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                        // tidak digunakan diganti dengan attachment file
                        // Carbon::setLocale('id');
                        // $tgl_sk_direksi = Carbon::parse($tgl_sk_direksi)->translatedFormat('d F Y');
                        // $where = ['nik'=>$trx->nik, 'bulan'=>$trx->bulan, 'tahun'=>$trx->tahun];
                        // $payroll = TrxPayroll::where($where)->first();
                        // dd($payroll);
                        // $link = route('payroll.surat.upah', [(new OpenDataController)->encryptWithKey($payroll->id), 'view' => 'pdf']);
                        $body = '<p>Dear '.$kelamin.' '.$nik_property['nama'].'</p>';
                        // $body .= '<p>Berdasarkan Surat Keputusan Direksi No. '.$no_sk_direksi.' tanggal '.$tgl_sk_direksi.' tentang Penyesuaian Upah Minimum tahun '.$tahun.' dan Struktur Upah, berikut kami sampaikan Surat Pemberitahuan perubahan Level, Grade dan/atau Gaji terhitung mulai bulan '.$nama_bulan[$bulan - 1].' '.$tahun.' dan Slip Gaji bulan '.$nama_bulan[$bulan - 1].' '.$tahun.'.</p>';
                        $body .= '<p>Berdasarkan '.$redaksi.', berikut kami sampaikan Surat Pemberitahuan perubahan Level, Grade dan/atau Gaji terhitung mulai bulan '.$nama_bulan[$bulan - 1].' '.$tahun.' dan Slip Gaji bulan '.$nama_bulan[$bulan - 1].' '.$tahun.'.</p>';
                        $body .= '<p>Surat Pemberitahuan dan Slip Gaji ini adalah bersifat <strong style="color:red">CONFIDENTIAL</strong> dan tidak untuk diberitahukan kepada rekan kerja ataupun Pihak Lain.</p>';
                        // $body .= '<p>Klik link berikut untuk mendownload Surat Pemberitahuan Penyesuaian Upah ini.</p>';
                        // $body .= '<p><a href="'.$link.'">Download File</a>';
                        $body .= '<p>Demikian kami sampaikan, terimakasih atas perhatian dan kerjasamanya.</p>';
                        $body .= '<p><i style="color:blue">Notes : password berupa tanggal lahir dengan format DDMMYYYY.</i></p>';
                        $body .= MtrUtility::find(47)->informasi;
                        // 
                        $trx->email = json_encode(['nik'=>$nik_property['nik'], 'subject'=>$email['subject'], 'body'=>$body, 'to'=>$email['to'], 'cc'=>$email['cc']]);
                        $trx->update();
                        // menambahkan parameter
                        $request->merge(['view' => 'pdf', 'perihal' => 'pemberitahuan_upah', 'redaksi' => $redaksi, 'no_surat' => $no_surat, 'filename' => $filename, 'email_id' => $_id]);
                        // 
                        $this->slip_detail($request, (new OpenDataController)->encryptWithKey(TrxPayroll::where(['tahun'=>$tahun, 'bulan'=>$bulan, 'nik'=>$nik])->first()->id));
                        // start kirim email dan notifikasi ke reviewer1
                        $nik_reviewer = explode(',', MtrUtility::find(45)->informasi)[0];
                        $reviewer = Karyawan::where(['nik' => $nik_reviewer])->first();
                        // parameter yang dipassing ke sendemail
                        $mailData['title'] = $data0[$x][$_7];
                        $mailData['data'] = ['nama'=>$nik_property['nama'].'/'.$nik_property['nik'], 'jabatan'=>$nik_property['jabatan'], 'unit_kerja'=>$nik_property['divisi'].'/'.$nik_property['dep'].'/'.$nik_property['sub_dep'], 'divisi'=>$nik_property['divisi'], 'dep'=>$nik_property['dep'], 'sub_dep'=>$nik_property['sub_dep'], 'link'=> route('payroll.review', [(new OpenDataController)->encryptWithKey($trx->id), 'pic' => (new OpenDataController)->encryptWithKey($nik_reviewer), 'id_status' => 1 ])];
                        $mailData['filename'] = 'emails.penyesuaian_upah0';
                        $mailData['to'] = $reviewer->email;
                        $mailData['cc'] = $email['cc'];
                        $todolist = ['id_trx' => $trx->id, 'nik' => $nik_reviewer, 'id_status' => 1, 'pic' => 'reviewer1'];
                        $mailData['attachments'] = public_path('assets/penyesuaian_upah').'/'.$filename;
                        $mailData['namafile'] = $filename;
                        $this->insertToDoList($todolist, $mailData);
                        $this->sendEmail($mailData);
                        // end
                    }
                }
                return redirect('/payroll/send_email/0')->with('msg', 'Data is saved successfully.');
            }
        }
    }

    public function review(Request $request, $id){
        $pic = $request->pic;
        $id_status = $request->id_status;
        // dd($request->pic, $id, $request->id_status);
        $id = (new OpenDataController)->decryptWithKey($id);
        $pic = (new OpenDataController)->decryptWithKey($request->pic);
        // dd($request->all(), $id, $pic);
        // check apa ada di todolist
        $check = ToDoList::where(['id_trx'=>$id, 'id_status'=>$id_status, 'nik'=>$pic, 'filename'=>'emails.penyesuaian_upah0'])->exists();
        if($check){
            $trx = TrxPayrollEmail::find($id);
            $id = (new OpenDataController)->encryptWithKey($id);
            $pic = (new OpenDataController)->encryptWithKey($pic);
            $id_status = (new OpenDataController)->encryptWithKey($id_status);
            return view('emails.penyesuaian_upah1', compact('id', 'pic', 'id_status', 'trx'));
        }else{
            return view('emails.penyesuaian_upah_ok');
        }
    }

    public function review_post(Request $request){
        // dd($request->all());
        $id = (new OpenDataController)->decryptWithKey($request->id);
        $pic = (new OpenDataController)->decryptWithKey($request->pic);
        $id_status = (new OpenDataController)->decryptWithKey($request->id_status);
        // dd($id, $pic, $id_status);
        $data = TrxPayrollEmail::find($id);
        if(!empty($data)){
            // insert into trx_tbl_payroll_penyesuaian_upah_log
            $trx = New TrxPayrollPenyesuaianUpahLog;
            $trx->no_surat = $data->no_surat;
            $trx->nik = $data->nik;
            $trx->nik_property = $data->nik_property;
            $trx->pic = $pic;
            $trx->pic_property = $this->getNikProperty($pic);
            $trx->id_status = $id_status;
            $trx->bulan = $data->bulan;
            $trx->tahun = $data->tahun;
            // 
            $qrcode = $pic.'_'.$data->no_surat;
            $qr = QrCode::format('png')->size(500)->generate($qrcode);
            $qr_filename = (new OpenDataController)->encryptWithKey($qrcode).'.png';
            file_put_contents(public_path('assets/penyesuaian_upah').'/'.$qr_filename, $qr);
            $trx->qrcode = $qr_filename;
            // 
            $trx->save();
            // delete todolist
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$pic, 'id_status'=>$id_status, 'filename'=>'emails.penyesuaian_upah0']);
            if($id_status == 1){
                // start kirim email dan notifikasi ke reviewer2
                $nik_reviewer = explode(',', MtrUtility::find(45)->informasi)[1];
                $reviewer = Karyawan::where(['nik' => $nik_reviewer])->first();
                $email = json_decode($data->email, true);
                $nik_property = json_decode($data->nik_property, true);
                // parameter yang dipassing ke sendemail
                $mailData['title'] = $email['subject'];
                $mailData['data'] = ['nama'=>$nik_property['nama'].'/'.$nik_property['nik'], 'jabatan'=>$nik_property['jabatan'], 'unit_kerja'=>$nik_property['divisi'].'/'.$nik_property['dep'].'/'.$nik_property['sub_dep'], 'divisi'=>$nik_property['divisi'], 'dep'=>$nik_property['dep'], 'sub_dep'=>$nik_property['sub_dep'], 'link'=> route('payroll.review', [(new OpenDataController)->encryptWithKey($id), 'pic' => (new OpenDataController)->encryptWithKey($nik_reviewer), 'id_status' => 2 ])];
                $mailData['filename'] = 'emails.penyesuaian_upah0';
                $mailData['to'] = $reviewer->email;
                $mailData['cc'] = $email['cc'];
                $todolist = ['id_trx' => $id, 'nik' => $nik_reviewer, 'id_status' => 2, 'pic' => 'reviewer2'];
                $mailData['attachments'] = public_path('assets/penyesuaian_upah').'/'.$data->filename;
                $mailData['namafile'] = $data->filename;
                $this->insertToDoList($todolist, $mailData);
                $this->sendEmail($mailData);
                // end
            }
            // jika $id_status == 2 maka buat kembali file pdf yang sudah ditambahkan approval dari para reviewer
            elseif($id_status == 2){
                // menambahkan parameter
                $request->merge(['view' => 'pdf', 'perihal' => 'pemberitahuan_upah', 'redaksi' => $data->param1, 'no_surat' => $data->no_surat, 'filename' => $data->filename, 'email_id' => $id]);
                $this->slip_detail($request, (new OpenDataController)->encryptWithKey(TrxPayroll::where(['tahun'=>$data->tahun, 'bulan'=>$data->bulan, 'nik'=>$data->nik])->first()->id));
            }
            return view('emails.penyesuaian_upah_ok');
        }else{
            return view('emails.penyesuaian_upah_ok');
        }
    }
}
