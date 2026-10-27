<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\WorkingDay;
use Carbon\Carbon;
use URL;

class TrxCiCoAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $nik = auth()->user()->nik;
        $where = ['nik' => $nik, 'is_aktif' => 1];
        $karyawan = Karyawan::where($where)->first();
        // 
        $arr = array();
        if($karyawan->_wg->is_reguler == 1){
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', URL::to('/').'/api/matta/show?id=absensi&nik='.$nik);
            $arr = json_decode($response->getBody()->getContents(), true)['data'];
            return view('cico.attendance', compact('arr', 'karyawan'));
        }else if($karyawan->_wg->is_reguler == 0){
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', URL::to('/').'/api/matta/show?id=group&nik='.$nik);
            $data = json_decode($response->getBody()->getContents(), true)['data'];
            return view('cico.attendance_group', compact('data', 'karyawan'));
        }
        // return response()->json( [ 'data' => $arr ], 200 );
        // return view('cico.attendance')->with('arr', json_decode($arr, true));
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
        $nik = auth()->user()->nik;
        $where = ['nik' => $nik, 'is_aktif' => 1];
        $karyawan = Karyawan::where($where)->first();
        // Regular
        if($karyawan->_wg->is_reguler == 1){
            $_att_status = '';
            $clockin = date("Y-m-d").' '.$karyawan->_wg->clockin;
            // $diff = Carbon::parse($clockin)->diffInSeconds(Carbon::now());
            if(Carbon::now() > Carbon::parse($clockin)){
                $_att_status = 'Late-in';
            }else{
                $_att_status = 'Book-in';
            }
            // 
            $att_type = $request->att_type;
            $att_data_src = 'Regular';
            $att_status = '';
            if($att_type == 'Clock-out'){
                $att_status = 'Book-in';
            }else if($att_type == 'Check-in'){
                $att_status = 'Book-in';
            }else if($att_type == 'Clock-in'){
                $att_status = $_att_status;
            }
            // 
            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', URL::to('/').'/api/matta', [
                'form_params' => [
                    'att_nik' => auth()->user()->nik,
                    'att_type' => $att_type,
                    'att_data_src' => $att_data_src,
                    'att_wd_ot_id' => 0,
                    'att_status' => $att_status
                ]
            ]);
            $arr = json_decode($response->getBody()->getContents(), true);
            if($arr['success']){
                return redirect( 'cico_att' )->with( 'msg', $arr['message'] );
            }
        }
        // Non Regular
        else if($karyawan->_wg->is_reguler == 0){
            $data = WorkingDay::find($request->id);
            $_att_status = '';
            $clockin = $data->clockin;
            // $diff = Carbon::parse($clockin)->diffInSeconds(Carbon::now());
            if(Carbon::now() > Carbon::parse($clockin)){
                $_att_status = 'Late-in';
            }else{
                $_att_status = 'Book-in';
            }
            // 
            $att_type = $request->att_type;
            $att_data_src = 'Regular';
            $att_status = '';
            if($att_type == 'Clock-out'){
                $att_status = 'Book-in';
            }else if($att_type == 'Check-in'){
                $att_status = 'Book-in';
            }else if($att_type == 'Clock-in'){
                $att_status = $_att_status;
            }
            // 
            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', URL::to('/').'/api/matta', [
                'form_params' => [
                    'att_nik' => auth()->user()->nik,
                    'att_type' => $att_type,
                    'att_data_src' => $att_data_src,
                    'att_wd_ot_id' => $request->id,
                    'att_status' => $att_status
                ]
            ]);
            $arr = json_decode($response->getBody()->getContents(), true);
            if($arr['success']){
                return redirect( 'cico_att' )->with( 'msg', $arr['message'] );
            }
        }
        // return response()->json( [ 'success' => true, 'message' => $request->all() ], 200 );
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
