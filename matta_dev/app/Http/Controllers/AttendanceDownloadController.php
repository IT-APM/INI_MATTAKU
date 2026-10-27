<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\AttendanceDownload;
use App\Models\MtrUtility;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Gate;

class AttendanceDownloadController extends Controller
{

    public function sendEmail($mailData)
    {
        $address = explode(',', MtrUtility::find(48)->informasi);
        Mail::to($mailData['to'])->cc($address[1])->send(new MattaRebornMail($mailData));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $no = 0;
        $trx = AttendanceDownload::orderBy('id', 'desc')->get();
        return view('attendance.download.index', compact('trx', 'no'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status = 'create';
        return view('attendance.download.upsert', compact('status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $trx = New AttendanceDownload;
        $trx->tanggal_mulai = $request->tanggal_mulai;
        $trx->tanggal_akhir = $request->tanggal_akhir;
        $trx->save();
        // start
        $mailData['title'] = get_class(request()->route()->getController());
        $mailData['filename'] = 'emails.attendance_download';
        $mailData['data'] = ['title' => $request->tanggal_mulai.' sd '.$request->tanggal_akhir, 'tanggal' => $mailData['title']];
        $address = explode(',', MtrUtility::find(48)->informasi);
        $mailData['to'] = $address[0];
        $this->sendEmail($mailData);
        // end
        return redirect('attendance_download')->with('msg', 'Data is saved successfully.');
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
        // $status = 'edit';
        // $trx = AttendanceDownload::find($id);
        // return view('attendance.download.upsert', compact('trx', 'status'));
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
        // $trx = AttendanceDownload::find($id);
        // $trx->tanggal_mulai = $request->tanggal_mulai;
        // $trx->tanggal_akhir = $request->tanggal_akhir;
        // $trx->save();
        // return redirect('attendance_download')->with('msg', 'Data is saved successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $trx = AttendanceDownload::find($id);
        $trx->delete();
        return redirect('attendance_download')->with( 'msg', 'Data is deleted successfully.' );
    }
}
