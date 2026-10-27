<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Karyawan;
use App\Models\User;
use App\Models\UsersPwd;
// 
use App\Models\McuPesertaSendemail;
// 
use App\Models\MtrUtility;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'nik' => ['required'],
            'password' => ['required'],
        ]);
        // return response()->json(['credentials' => $credentials]);
        if (Auth::attempt($credentials)) {
            $where = ['nik' => $request->nik, 'is_aktif' => 1];
            if(Karyawan::where($where)->exists()){
                $check = UsersPwd::where(['nik'=>$request->nik])->orderBy('id', 'desc')->first();
                if(Hash::check($request->password, $check->password) && $check->created_at->copy()->addMonths(3)->isPast()){
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->to('authentication/login-one-reset')->with('msg_error', 'For personal data and information security, MATTA may require you to change the password regularly.');
                }else{
                $request->session()->regenerate();
                // return redirect()->intended('dashboard/index');
                return redirect()->intended('todolist');
                }
            }else{
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->with('msg_error', 'Your account is Non Active.<br/>Please check again!');
            }
        }
        return back()->with('msg_error', 'Your NIP and/or Password is wrong.<br/>Please check again!');
    }

    public function resetpwd(Request $request)
    {
        $where = ['nik' => $request->nik, 'is_aktif' => 1];
        if(Karyawan::where($where)->exists()){
            $karyawan = Karyawan::where($where)->first();
            // start
            $mailData['title'] = 'Reset Password';
            $mailData['filename'] = 'emails.reset0';
            $mailData['to'] = MtrUtility::find(1)->informasi;
            if($request->email == 'own'){
                $mailData['to'] = $karyawan->email;
            }
            $mailData['data'] = ['nama' => $karyawan->nama, 'nik' => $karyawan->nik, 'jabatan' => $karyawan->_jabatan->jabatan, 'unit_kerja' => $karyawan->_divisi->nama . '/' . $karyawan->_departemen->nama . '/' . $karyawan->_subdepartemen->nama, 'link' => route('resetview', ['nik' => $karyawan->nik, 'pwd' => $karyawan->password, 'email' => $mailData['to']])];
            $this->sendEmail($mailData);
            return back()->with('msg_success', 'Please check email you have selected!');
            // finish
        }else{
            return back()->with('msg_error', 'Your NIP is wrong or may Non Active.<br/>Please check again!');
        }
    }

    public function resetview(Request $request)
    {
        // return response()->json( [ 'data' => $request->all() ] );
        // $where = ['nik' => $request->nik, 'password' => $request->pwd];
        // $karyawan = Karyawan::where($where)->first();
        $nik = $request->nik;
        $pwd = $request->pwd;
        $email = $request->email;
        return view('authentication.login-one-reset-view', compact('nik', 'pwd', 'email'));
        // return response()->json( [ 'data' => $karyawan ] );
    }

    public function resetaction(Request $request)
    {
        // dd($request->all());
        $check = UsersPwd::where(['nik'=>$request->nik])->orderBy('id', 'desc')->first();
        if(Hash::check($request->password, $check->password) && $check->created_at->copy()->addMonths(3)->isPast()){
            return back()->with('msg_error', 'For personal data and information security, MATTA may require you to change the password regularly.');
        }else{
            $where = ['nik' => $request->nik, 'password' => $request->pwd];
            $karyawan = Karyawan::where($where)->first();
            // 
            $new_password = Hash::make($request->password);
            $karyawan->password = $new_password;
            $karyawan->update();
            // 
            $where = ['nik' => $request->nik, 'password' => $request->pwd];
            $user = User::where($where)->first();
            $user->password = $new_password;
            $user->update();
            // auto login setelah reset
            $request->session()->regenerate();
            Auth::login($user);
            // 
            $user = new UsersPwd;
            $user->nik = $request->nik;
            $user->password = $new_password;
            $user->save();
            // start
            $mailData['title'] = 'New Password';
            $mailData['filename'] = 'emails.reset1';
            $mailData['data'] = ['nama' => $karyawan->nama, 'nik' => $karyawan->nik, 'jabatan' => $karyawan->_jabatan->jabatan, 'unit_kerja' => $karyawan->_divisi->nama . '/' . $karyawan->_departemen->nama . '/' . $karyawan->_subdepartemen->nama, 'new_password' => $request->password];
            $mailData['to'] = $request->email;
            $this->sendEmail($mailData);
            // finish
            // tidak digunakan
            // return back()->with('msg_success', 'Your password has been changed.<br/>Please check email to see your new password.');
            // auto login setelah reset
            return redirect()->intended('todolist');
            // return view('authentication.login-one')->with('msg_success', ['Your password has been changed. Please check email to see your new password.']);
        }
    }

    public function sendEmail($mailData)
    {
        $ccEmails = explode(',', MtrUtility::find(11)->informasi);
        // Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
        Mail::to($mailData['to'])->bcc($ccEmails)->send(new MattaRebornMail($mailData));
        // if($mailData['title'] == 'New Password'){
        //     Mail::to($mailData['to'])->bcc($ccEmails)->send(new MattaRebornMail($mailData));
        // }else{
        //     Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
        // }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // start insert nip baru
        // $result = array();
        // $niks = ['P0623052','P0623053','P0623054','P0623055'];
        // foreach (Karyawan::all() as $value) {
        //     if (in_array($value->nik, $niks)){
        //         $data = Karyawan::find($value->id);
        //         $data->password = Hash::make($data->password);
        //         $data->update();
        //         // 
        //         $where = ['nik' => $value->nik];
        //         $user = User::where($where)->first();
        //         $user->password = $data->password;
        //         $user->update();
        //         // 
        //         $arr['nik'] = $value->nik;
        //         $arr['nama'] = $value->nama;
        //         array_push($result, $arr);
        //     }
        // }
        // return response()->json( [ 'data' => $result ] );
        // end insert nip baru
        // start
        // $arr = array();
        // $where = ['is_email' => 0];
        // $data = McuPesertaSendemail::where($where)->limit(5)->get();
        // foreach ($data as $value) {
        //     $mailData['title'] = 'Hasil MCU '.$value->nama;
        //     $mailData['filename'] = 'emails.mcu0';
        //     // $mailData['to'] = MtrUtility::find(1)->informasi;
        //     // $mailData['to'] = 'abdulhadi@tigaluhursejahtera.com';
        //     $mailData['to'] = $value->email;
        //     $mailData['file_pdf'] = 'mcu_'.$value->id.'.pdf';
        //     $mailData['data'] = ['nama' => $value->nama];
        //     $this->sendEmailMcu($mailData);
        //     $_data = McuPesertaSendemail::find($value->id);
        //     $_data->is_email = 1;
        //     $_data->update();
        //     array_push($arr, $mailData);
        // }
        // // 
        // return response()->json( [ 'arr' => $arr ] );
    }

    public function sendEmailMcu($mailData)
    {
        // $ccEmails = explode(',', MtrUtility::find(11)->informasi);
        // $ccEmails = ['hadidisini@yahoo.com','busdev@tigaluhursejahtera.com'];
        // $ccEmails = ['busdev@tigaluhursejahtera.com'];
        $bccEmails = ['tri.destiani@alibaba-inc.com','safira.nurdianti@alibaba-inc.com','strategicpartnership@gooddoctor.co.id','busdev@tigaluhursejahtera.com'];
        // $bccEmails = ['busdev@tigaluhursejahtera.com','abdulhadi@tigaluhursejahtera.com'];
        Mail::to($mailData['to'])->bcc($bccEmails)->send(new MattaRebornMail($mailData));
        // Mail::to($mailData['to'])->cc($ccEmails)->bcc($bccEmails)->send(new MattaRebornMail($mailData));
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