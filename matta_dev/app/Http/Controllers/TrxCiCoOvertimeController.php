<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Overtime;
use Carbon\Carbon;
use URL;

class TrxCiCoOvertimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // backdoor file app\Http\Controllers\Api\MattaApi.php
        // ketemu bug return ['isHoliday'=> false, 'msg'=> ''];
        // menjadi return ['isHoliday'=> false, 'is_cuti'=> 0, 'msg'=> ''];
        // SELECT * FROM `trx_tbl_overtime` WHERE `ot_length_adj` IS NOT NULL AND `ot_length_rp`=0;
        // $where = ['ot_length_rp' => 0];
        // $overtime = Overtime::select('id','ot_maker_by')->whereNotNull('ot_length_adj')->where($where)->get();
        // foreach($overtime as $_overtime){
        //     $client = new \GuzzleHttp\Client();
        //     $response = $client->request('POST', URL::to('/').'/api/matta', [
        //         'form_params' => [
        //             'att_nik' => $_overtime->ot_maker_by,
        //             'att_type' => 'Clock-out',
        //             'att_data_src' => 'Overtime',
        //             'att_wd_ot_id' => $_overtime->id,
        //             'att_status' => 'Book-in'
        //         ]
        //     ]);
        // }
        //
        $nik = auth()->user()->nik;
        $where = ['nik' => $nik, 'is_aktif' => 1];
        $karyawan = Karyawan::where($where)->first();
        // 
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', URL::to('/').'/api/ot/show?id=maker&src=ot_maker_by&nik='.$nik);
        $arr = json_decode($response->getBody()->getContents(), true)['data'];
        $data = array();
        foreach($arr as $value){
            $_arr = array();
            $cico = $this->getCICO($nik, $value['id']);
            $_arr['id'] = $value['id'];
            $_arr['ot_title'] = $value['ot_title'];
            $_arr['ot_desc'] = $value['ot_desc'];
            $_arr['ot_type'] = $value['ot_type'];
            $_arr['ot_start_at'] = $value['ot_start_at'];
            $_arr['ot_end_at'] = $value['ot_end_at'];
            $_arr['ot_length'] = $value['ot_length'];
            $_arr['ot_approver_status'] = $value['ot_approver_status'];
            // 
            $_arr['n_clockin'] = $cico[0]['n_clockin'];
            $_arr['n_clockout'] = $cico[0]['n_clockout'];
            $_arr['tgl'] = $cico[1]['tgl'];
            $_arr['jam'] = $cico[1]['jam'];
            $_arr['isClockin'] = $cico[2]['isClockin'];
            $_arr['isClockout'] = $cico[2]['isClockout'];
            $_arr['dtClockin'] = $cico[3]['dtClockin'];
            $_arr['dtClockout'] = $cico[3]['dtClockout'];
            array_push($data, $_arr);
        }
        // return response()->json( [ 'data' => $data ], 200 );
        return view('cico.overtime', compact('data', 'karyawan'));
    }

    public function getCICO($nik, $wd_id)
    {
        // 
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', URL::to('/').'/api/ot/show?id=absensi&nik='.$nik.'&wd_id='.$wd_id);
        $data = json_decode($response->getBody()->getContents(), true)['data'];
        // dd($data[0]);
        return $data;

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
        $data = Overtime::find($request->id);
        $_att_status = '';
        $clockin = $data->ot_start_at;
        if(Carbon::now() > Carbon::parse($clockin)){
            $_att_status = 'Late-in';
        }else{
            $_att_status = 'Book-in';
        }
        // 
        $att_type = $request->att_type;
        $att_data_src = 'Overtime';
        $att_status = '';
        if($att_type == 'Clock-out'){
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
            return redirect( 'cico_ot' )->with( 'msg', $arr['message'] );
        }
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
