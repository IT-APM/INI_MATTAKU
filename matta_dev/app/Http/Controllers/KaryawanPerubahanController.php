<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\KaryawanBulanan;
use App\Models\ToDoList;
use App\Models\ToDoListLog;
use App\Models\MtrUtility;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;

class KaryawanPerubahanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 
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
        $id = Crypt::decrypt($id);
        // return response()->json( [ 'request' => $request->all(), 'id' => $id ], 200 );
        if(!ToDoList::where(['id_trx'=>$id, 'nik'=>$request->nik, 'pic'=>$request->pic, 'filename'=>$request->filename])->exists()){
            return view('emails.karyawan_bulanan_ok');
        }
        $trx = KaryawanBulanan::find($id);
        $layer1_property = json_decode($trx->layer1_property, true);
        $layer2_property = json_decode($trx->layer2_property, true);
        $karyawan = KaryawanBulanan::where(['layer1'=>$trx->layer1,'is_berubah'=>1])->get();
        if($request->pic == 'pic_hco'){
            $karyawan = KaryawanBulanan::where(['layer1'=>$trx->layer1,'is_berubah'=>2])->get();
        }
        $karyawan = $karyawan->sortBy(function ($data) {
            $nik_property = json_decode($data->nik_property, true);
            return $nik_property['nama'] ?? '';
        })->values();
        return view('emails.karyawan_bulanan', compact('trx', 'karyawan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $id = Crypt::decrypt($id);
        // return response()->json( [ 'id' => $id ], 200 );
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
        $id = Crypt::decrypt($id);
        // return response()->json( [ 'request' => $request->all(), 'id' => $id ], 200 );
        // id = 1 perubahan yang dilakukan oleh layer 1 per karyawan bawahannya
        if($id == 1){
            $trx = KaryawanBulanan::find($request->data_id);
            $trx->perubahan = $request->perubahan;
            $trx->update();
            return redirect($request->back_url);
        }
        // id = 2 layer 1 submit semua perubahan
        elseif($id == 2){
            KaryawanBulanan::where(['layer1'=>$request->layer1,'is_berubah'=>1])->update(['is_berubah' => 2]);
            $trx = KaryawanBulanan::find($request->data_id_all);
            $layer1_property = json_decode($trx->layer1_property, true);
            $layer2_property = json_decode($trx->layer2_property, true);
            // start
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Konfirmasi Data Karyawan';
            $mailData['filename'] = 'emails.karyawan_bulanan0';
            $pic_hco = explode('/', MtrUtility::find(54)->informasi);
            $mailData['to'] = $pic_hco[1];
            $karyawan = KaryawanBulanan::where(['layer1'=>$request->layer1,'is_berubah'=>2])->get();
            $karyawan = $karyawan->sortBy(function ($data) {
                $nik_property = json_decode($data->nik_property, true);
                return $nik_property['nama'] ?? '';
            })->values();
            $mailData['data'] = ['karyawan' => $karyawan, 'data' => $trx, 'nama'=>$layer1_property['nama'].'/'.$layer1_property['nik'], 'jabatan'=>$layer1_property['jabatan'], 'unit_kerja'=>$layer1_property['divisi'].'/'.$layer1_property['dep'].'/'.$layer1_property['sub_dep'], 'link'=> route('karyawanperubahan.show', [Crypt::encrypt($request->data_id_all), 'nik'=>$pic_hco[0], 'pic'=>'pic_hco', 'filename'=>$mailData['filename']]) ];
            // cc email ke hco
            $ccEmails = explode(',', MtrUtility::find(11)->informasi);
            array_push($ccEmails, $layer1_property['email']);
            // check apakah is_cc_layer2 TRUE maka cc ke layer2
            if($trx->is_cc_layer2){
                array_push($ccEmails, $layer2_property['email']);
            }
            Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
            // end
            $this->deleteToDoList(['id_trx'=>$request->data_id_all, 'filename'=>$mailData['filename'], 'pic'=>'layer1', 'nik'=>$request->layer1]);
            $todolist = ['id_trx' => $trx->id, 'nik' => $pic_hco[0], 'id_status' => 2, 'pic' => 'pic_hco'];
            $this->insertToDoList($todolist, $mailData);
            return view('emails.karyawan_bulanan_ok');
        }
        // id = 3 confirmed by pic_hco
        elseif($id == 3){
            KaryawanBulanan::where(['layer1'=>$request->layer1,'is_berubah'=>2])->update(['is_berubah' => 3]);
            $mailData['filename'] = 'emails.karyawan_bulanan0';
            $pic_hco = explode('/', MtrUtility::find(54)->informasi);
            $this->deleteToDoList(['id_trx'=>$request->data_id_all, 'filename'=>$mailData['filename'], 'pic'=>'pic_hco', 'nik'=>$pic_hco[0]]);
            return view('emails.karyawan_bulanan_ok');
        }
    }

    public function sendEmail($mailData)
    {
        $ccEmails = explode(',', MtrUtility::find(11)->informasi);
        Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
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
