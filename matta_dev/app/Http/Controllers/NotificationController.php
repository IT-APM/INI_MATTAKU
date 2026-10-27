<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Overtime;
use App\Models\ToDoList;
use App\Models\MtrUtility;
use URL;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // start uji coba
        // $nik = ['RFIAL068','RFIAL113','01032411873'];
        // $karyawan = Karyawan::select('nik', 'nama', 'mobile_id')->whereIn('nik', $nik)->whereNotNull('mobile_id')->get();
        // foreach ($karyawan as $value) {
        //     try {
        //         $client = new \GuzzleHttp\Client();
        //         $response = $client->request('POST', URL::to('https://fcm.googleapis.com/v1/projects/matta-reborn-98e4c/messages:send'), [
        //             'headers' => [
        //                 'Authorization' => 'Bearer '.$this->getToken(),
        //                 'Content-Type' => 'application/json',
        //             ],
        //             'json' => [
        //                 'message' => [
        //                     'token' => $value->mobile_id,
        //                     'notification' => [
        //                         'title' => '',
        //                         'body' => '',
        //                     ],
        //                 ],
        //             ],
        //         ]);
        //     } catch (\GuzzleHttp\Exception\RequestException $e) {
        //         $where = ['mobile_id' => $value->mobile_id];
        //         $karyawan = Karyawan::where($where)->first();
        //         $karyawan->mobile_id = NULL;
        //         $karyawan->update();
        //         // dd($karyawan);
        //         if ($e->hasResponse()) {
        //             $response = $e->getResponse();
        //         }
        //     }
        // }
        // end uji coba
        // $this->notif_approval_ot();
        // $this->notif_cico_reg();
        // $this->notif_cico_ot();
        // $this->notif_formulir();
        // $this->notif_feed('Waspada Peningkatan Kasus COVID di Indonesia');
        // hadi
        // $to = 'dt9n_DktQ-GuZ4Zx0iOFCt:APA91bFsTqmsc0UE-ZDVF-K9bzPWruDJpSE1FWv0WSrnvfl0sT2p_R-EACYDuCGBjEXfR4fe7GnKsv4DAnzoStbjVY-_j_GrAonAFGY5Lx1vgf4xfvbM7HYwpVKbcXrgYNEAGgl5GxjM';
        // $client = new \GuzzleHttp\Client();
        // $response = $client->request('POST', URL::to('https://fcm.googleapis.com/v1/projects/matta-reborn-98e4c/messages:send'), [
        //     'headers' => [
        //         'Authorization' => 'Bearer '.$this->getToken(),
        //         'Content-Type' => 'application/json',
        //     ],
        //     'json' => [
        //         'message' => [
        //             'token' => $to,
        //             'notification' => [
        //                 'title' => 'MATTA REBORN',
        //                 'body' => 'Testing message from Hadi. Please ignore. Thanks. '.date('Y-m-d H:i:s'),
        //             ],
        //         ],
        //     ],
        // ]);
        return response()->json( [ 'success' => true ], 200 );
    }

    public function getToken()
    {   
        $_token = MtrUtility::find(30);
        $now = time();
        if($now > strtotime($_token->updated_at)){
            $token = json_decode(file_get_contents('C:/xampp/htdocs/matta/matta-reborn-98e4c-firebase-adminsdk-bej7z-18629c5910.json'),true);
            $client_email = $token['client_email'];
            $private_key = $token['private_key'];
            $header = json_encode(['alg'=>'RS256', 'typ'=>'JWT']);
            $exp = $now + 3600;
            $payload = json_encode([
                'iss'=>$client_email,
                'scope'=>'https://www.googleapis.com/auth/cloud-platform https://www.googleapis.com/auth/firebase.messaging',
                    'aud'=>'https://www.googleapis.com/oauth2/v4/token',
                    'exp'=>$exp,
                    'iat'=>$now
            ]);
            $base64UrlHeader = str_replace(['+','/','='],['-','_',''],base64_encode($header));
            $base64UrlPayload = str_replace(['+','/','='],['-','_',''],base64_encode($payload));
            $signatureInput = $base64UrlHeader.'.'.$base64UrlPayload;
            openssl_sign($signatureInput, $signature, $private_key, 'sha256');
            $base64UrlSignature = str_replace(['+','/','='],['-','_',''],base64_encode($signature));
            $jwt = $base64UrlHeader.'.'.$base64UrlPayload.'.'.$base64UrlSignature;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v4/token');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'grant_type'=>'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'=>$jwt
            ]));
            $response = curl_exec($ch);
            curl_close($ch);
            $responseData = json_decode($response, true);
            // dd($responseData);
            $_token->keterangan = $responseData['access_token'];
            $_token->updated_at = date('Y-m-d H:i:s', $exp);
            $_token->update();
            return $responseData['access_token'];
        }else{
            return $_token->keterangan;
        }
    }

    public function notif_feed($_title){
        // puput only
        // $where = ['is_aktif' => 1, 'nik' => '01032106835'];
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama', 'mobile_id')->where($where)->whereNotNull('mobile_id')->get();
        foreach ($karyawan as $value){
            $this->sendNotifFeed($value->nama, $value->mobile_id, $_title);
        }
    }

    public function notif_formulir()
    {
        $trx = ToDoList::orderBy('id', 'desc')->get();
        $arr = array();
        $no = 1;
        foreach ($trx as $value){
            $link = $value->link;
            $nama = $value->_pic->nama;
            $title = $value->title;
            $pengaju = $value->nama;
            $tags = get_meta_tags($link);
            // array_push($arr, ['no'=>$no++, 'status'=>$tags['status'], 'id'=>$value->id, 'nik'=>$value->nik, 'nama'=>$nama, 'link'=>$link]);
            if($tags['status']=='true'){
                // array_push($arr, ['no'=>$no++, 'id'=>$value->id, 'nik'=>$value->nik, 'nama'=>$nama, 'link'=>$link]);
                $this->deleteToDoList($value->id);
            }else{
                if(!empty($value->_pic->mobile_id)){
                    $to = $value->_pic->mobile_id;
                    array_push($arr, ['no'=>$no++, 'id'=>$value->id, 'nik'=>$value->nik, 'nama'=>$nama, 'link'=>$link]);
                    $this->sendNotifFormulir($nama, $title, $to, $pengaju);
                }
            }
        }
        // return response()->json( [ 'success' => true, 'arr'=>$arr ], 200 );
        Storage::put('notif/'.Carbon::now()->format('Ymd_His').'.formulir.txt', json_encode($arr));
        // Storage::put('notif/'.Carbon::now()->format('Ymd').'.formulir.txt', json_encode($arr));
        // return $arr;
    }

    public function deleteToDoList($id)
    {
        $trx = ToDoList::find($id);
        if (!empty($trx)) {
            $trx->delete();
        }
    }

    public function notif_cico_reg()
    {
        $sekarang = Carbon::now()->format('Y-m-d H:i:s');
        $result = array();
        $where = ['is_aktif' => 1];
        // $arr_wg_id = array(2, 10, 11, 12, 13, 14, 15, 16);
        $arr_wg_id = array(1);
        // reguler
        $karyawan = Karyawan::select('nik', 'nama', 'mobile_id')->where($where)->whereIn('wg_id', $arr_wg_id)->whereNotNull('mobile_id')->get();
        foreach ($karyawan as $value) {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', URL::to('/').'/api/matta/show?id=absensi&nik='.$value->nik);
            $arr = json_decode($response->getBody()->getContents(), true);
            if($arr['success'] && $arr['data'][4]['isClockin']){
                $cico = 'Clock In Reguler';
                $this->sendNotifCiCo($value->nama, $value->mobile_id, $cico);
                array_push($result, ['data'=>$value,'cico'=>$cico, 'datetime'=>$sekarang]);
            }else if($arr['success'] && $arr['data'][4]['isClockout']){
                $cico = 'Clock Out Reguler';
                $this->sendNotifCiCo($value->nama, $value->mobile_id, $cico);
                array_push($result, ['data'=>$value,'cico'=>$cico, 'datetime'=>$sekarang]);
            }
        }
        // non reguler
        $karyawan = Karyawan::select('nik', 'nama', 'mobile_id')->where($where)->whereNotIn('wg_id', $arr_wg_id)->whereNotNull('mobile_id')->get();
        foreach ($karyawan as $value) {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', URL::to('/').'/api/matta/show?id=group&nik='.$value->nik);
            $arr = json_decode($response->getBody()->getContents(), true);
            foreach ($arr['data'] as $_arr) {
                if($arr['success'] && $_arr[4]['isClockin']){
                    $cico = 'Clock In Non Reguler';
                    $this->sendNotifCiCo($value->nama, $value->mobile_id, $cico);
                    array_push($result, ['data'=>$value,'cico'=>$cico, 'datetime'=>$sekarang]);
                }else if($arr['success'] && $_arr[4]['isClockout']){
                    $cico = 'Clock Out Non Reguler';
                    $this->sendNotifCiCo($value->nama, $value->mobile_id, $cico);
                    array_push($result, ['data'=>$value,'cico'=>$cico, 'datetime'=>$sekarang]);
                }
            }
        }
        // return response()->json( [ 'success' => true, 'result'=>$result ], 200 );
        Storage::put('notif/reg/'.Carbon::now()->format('Ymd_His').'.reg.txt', json_encode($result));
        // Storage::put('notif/'.Carbon::now()->format('Ymd').'.reg.txt', json_encode($result));
    }

    public function notif_cico_ot()
    {
        $sekarang = Carbon::now()->format('Y-m-d H:i:s');
        $result = array();
        $where = ['ot_approver_status' => 'Approve'];
        $ot = Overtime::select('id', 'ot_maker_by')->where($where)->where('ot_start_clockin','<',$sekarang)->where('ot_end_clockin','>',$sekarang)->get();
        foreach ($ot as $value) {
            $where = ['is_aktif'=>1, 'nik'=>$value->ot_maker_by];
            $karyawan = Karyawan::select('nik', 'nama', 'mobile_id')->where($where)->whereNotNull('mobile_id')->first();
            // 
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', URL::to('/').'/api/ot/show?id=absensi_notif&nik='.$value->ot_maker_by.'&wd_id='.$value->id);
            $arr = json_decode($response->getBody()->getContents(), true);
            if($arr['success'] && $arr['data'][2]['isClockin']){
                $cico = 'Clock In Overtime';
                $this->sendNotifCiCo($karyawan->nama, $karyawan->mobile_id, $cico);
                array_push($result, ['data'=>$value,'cico'=>$cico, 'datetime'=>$sekarang]);
            }
        }
        
        // 
        $where = ['ot_approver_status' => 'Approve'];
        $ot = Overtime::select('id', 'ot_maker_by')->where($where)->where('ot_end_clockin','<',$sekarang)->where('ot_end_clockout','>',$sekarang)->get();
        foreach ($ot as $value) {
            $where = ['is_aktif'=>1, 'nik'=>$value->ot_maker_by];
            $karyawan = Karyawan::select('nik', 'nama', 'mobile_id')->where($where)->whereNotNull('mobile_id')->first();
            // 
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', URL::to('/').'/api/ot/show?id=absensi_notif&nik='.$value->ot_maker_by.'&wd_id='.$value->id);
            $arr = json_decode($response->getBody()->getContents(), true);
            if($arr['success'] && $arr['data'][2]['isClockout']){
                $cico = 'Clock Out Overtime';
                $this->sendNotifCiCo($karyawan->nama, $karyawan->mobile_id, $cico);
                array_push($result, ['data'=>$value,'cico'=>$cico, 'datetime'=>$sekarang]);
            }
        }
        Storage::put('notif/'.Carbon::now()->format('Ymd_His').'.ot.txt', json_encode($result));
        // Storage::put('notif/'.Carbon::now()->format('Ymd').'.ot.txt', json_encode($result));
    }

    public function notif_approval_ot()
    {
        $sekarang = Carbon::now()->format('Y-m-d H:i:s');
        $result = array();
        $where = ['ot_approver_status' => 'Waiting'];
        $ot = Overtime::select('id', 'ot_approver_by', 'ot_maker_property')->where($where)->where('ot_start_at','>',$sekarang)->get();
        foreach ($ot as $value) {
            $where = ['is_aktif'=>1, 'nik'=>$value->ot_approver_by];
            $karyawan = Karyawan::select('nik', 'nama', 'mobile_id')->where($where)->whereNotNull('mobile_id')->first();
            $pengaju = json_decode($value->ot_maker_property, true);
            $_pengaju = $pengaju['nama'].'/'.$pengaju['nik'];
            if($karyawan != null){
                $this->sendNotifProsesOT($karyawan->nama, $karyawan->mobile_id, $_pengaju);
                array_push($result, ['data'=>$value,'pengaju'=>$_pengaju, 'datetime'=>$sekarang]);
            }
        }
        // 
        Storage::put('notif/'.Carbon::now()->format('Ymd_His').'.app_ot.txt', json_encode($result));
        // Storage::put('notif/'.Carbon::now()->format('Ymd').'.app_ot.txt', json_encode($result));
    }

    public function sendNotifCiCo($nama, $to, $cico)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', URL::to('https://fcm.googleapis.com/v1/projects/matta-reborn-98e4c/messages:send'), [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->getToken(),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'message' => [
                        'token' => $to,
                        'notification' => [
                            'title' => 'MATTA REBORN',
                            'body' => 'Hai '.$nama.', Anda belum melakukan '.$cico.'. Silahkan untuk melakukan '.$cico.' dahulu. Terima kasih.',
                        ],
                    ],
                ],
            ]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $where = ['mobile_id' => $to];
            $karyawan = Karyawan::where($where)->first();
            $karyawan->mobile_id = NULL;
            $karyawan->update();
            // dd($karyawan);
            if ($e->hasResponse()) {
                $response = $e->getResponse();
            }
        }
    }

    public function sendNotifProsesOT($nama, $to, $pengaju)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', URL::to('https://fcm.googleapis.com/v1/projects/matta-reborn-98e4c/messages:send'), [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->getToken(),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'message' => [
                        'token' => $to,
                        'notification' => [
                            'title' => 'MATTA REBORN',
                            'body' => 'Hai '.$nama.', Ada pengajuan Overtime atas nama '.$pengaju.'. Silahkan untuk memproses pengajuan Overtime tersebut. Terima kasih.',
                        ],
                    ],
                ],
            ]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $where = ['mobile_id' => $to];
            $karyawan = Karyawan::where($where)->first();
            $karyawan->mobile_id = NULL;
            $karyawan->update();
            // dd($karyawan);
            if ($e->hasResponse()) {
                $response = $e->getResponse();
            }
        }
    }

    public function sendNotifFormulir($nama, $title, $to, $pengaju)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', URL::to('https://fcm.googleapis.com/v1/projects/matta-reborn-98e4c/messages:send'), [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->getToken(),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'message' => [
                        'token' => $to,
                        'notification' => [
                            'title' => 'MATTA REBORN',
                            'body' => 'Hai '.$nama.', Ada '.$title.' atas nama '.$pengaju.'. Silahkan untuk memproses '.$title.' tersebut. Terima kasih.',
                        ],
                    ],
                ],
            ]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $where = ['mobile_id' => $to];
            $karyawan = Karyawan::where($where)->first();
            $karyawan->mobile_id = NULL;
            $karyawan->update();
            // dd($karyawan);
            if ($e->hasResponse()) {
                $response = $e->getResponse();
            }
        }
    }

    public function sendNotifFeed($nama, $to, $_title)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', URL::to('https://fcm.googleapis.com/v1/projects/matta-reborn-98e4c/messages:send'), [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->getToken(),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'message' => [
                        'token' => $to,
                        'notification' => [
                            'title' => 'MATTA REBORN',
                            'body' => 'Hai '.$nama.', Ada Feed baru perihal '.$_title.' yang harus anda lihat. Terima kasih.',
                        ],
                    ],
                ],
            ]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $where = ['mobile_id' => $to];
            $karyawan = Karyawan::where($where)->first();
            $karyawan->mobile_id = NULL;
            $karyawan->update();
            // dd($karyawan);
            if ($e->hasResponse()) {
                $response = $e->getResponse();
            }
        }
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
