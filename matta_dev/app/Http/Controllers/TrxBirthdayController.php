<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\TrxBirthday;
use App\Models\TrxBirthdayLog;
use App\Models\Karyawan;
use App\Mail\MattaRebornMail;
use App\Models\MtrUtility;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class TrxBirthdayController extends Controller
{
    // http://localhost/matta/birthday/scheduler/0
    // https://matta-reborn.alita.id/matta/birthday/scheduler/0
    public function scheduler($id){
        // dd($id);
        $this->getBirthday();
        $trx = TrxBirthday::orderBy('id', 'desc')->get();
        // $arr = [];
        foreach($trx as $_trx){
            $email = json_decode($_trx->email, true);
            // parameter yang dipassing ke sendemail
            $mailData['title'] = $email['subject'];
            $mailData['data'] = ['msg' => $email['body']];
            $mailData['filename'] = 'emails.birthday0';
            $mailData['to'] = $email['to'];
            $mailData['cc'] = $email['cc'];
            // array_push($arr, $mailData);
            $this->sendEmail($mailData);
        }
        return response()->json( [ 'success' => true ], 200 );
        // return response()->json( [ 'arr' => $arr ], 200 );
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
        // pindahkan ke trx_tbl_birthday_log
        $trx = TrxBirthday::orderBy('id', 'asc')->get();
        foreach ($trx as $_trx) {
            $data = new TrxBirthdayLog;
            $data->nik = $_trx->nik;
            $data->nik_property = $_trx->nik_property;
            $data->email = $_trx->email;
            $data->birthday = $_trx->birthday;
            $data->save();
            // delete trx_tbl_birthday
            TrxBirthday::find($_trx->id)->delete();
        }
        // hari ini
        $today = Carbon::today();
        // 3 hari lalu
        // $today = Carbon::now()->subDays(3);
        // 3 hari kemudian
        // $today = Carbon::now()->addDays(3);
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $trx_current = TrxBirthday::orderBy('id', 'desc')->get();
        $trx_history = TrxBirthdayLog::orderBy('id', 'desc')->get();
        if(Gate::allows('hc_nkp')){
            $trx_current = TrxBirthday::whereIn('nik',$arr_nik_nkp)->orderBy('id', 'desc')->get();
            $trx_history = TrxBirthdayLog::whereIn('nik',$arr_nik_nkp)->orderBy('id', 'desc')->get();
        }
        // dd($trx_current, $trx_history);
        return view('birthday.index', compact('trx_current', 'trx_history'));
    }
    
    public function send_email_exe(Request $request)
    {
        // dd($request->all());
        if($request->submit == 'execute'){
            $id = $request->input('id_current', []);
            if(count($id)==0){
                return redirect( 'birthday' )->with( 'msg', 'No Birthday Email is executed.' );
            }else{
                foreach($id as $_id){
                    $_trx = TrxBirthday::find($_id);
                    $email = json_decode($_trx->email, true);
                    // parameter yang dipassing ke sendemail
                    $mailData['title'] = $email['subject'];
                    $mailData['data'] = ['msg' => $email['body']];
                    $mailData['filename'] = 'emails.birthday0';
                    $mailData['to'] = $email['to'];
                    $mailData['cc'] = $email['cc'];
                    $this->sendEmail($mailData);
                    // insert into trx_tbl_birthday_log
                    $trx = New TrxBirthdayLog;
                    $trx->nik = $_trx->nik;
                    $trx->nik_property = $_trx->nik_property;
                    $trx->email = $_trx->email;
                    $trx->birthday = $_trx->birthday;
                    $trx->save();
                    // delete
                    TrxBirthday::find($_id)->delete();
                }
                return redirect( 'birthday' )->with( 'msg', 'Birthday Email is executed successfully.' );
            }
        }
        elseif($request->submit == 'delete'){
            $id = $request->input('id_current', []);
            if(count($id)==0){
                return redirect( 'birthday' )->with( 'msg', 'No Birthday Email is deleted.' );
            }else{
                foreach($id as $_id){
                    TrxBirthday::find($_id)->delete();
                }
                return redirect( 'birthday' )->with( 'msg', 'Birthday Email is deleted successfully.' );
            }
        }
    }

    public function sendEmail($mailData)
    {
        $ccEmails = explode(',', $mailData['cc']);
        Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
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
    }
}
