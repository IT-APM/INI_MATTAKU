<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Feeds;
use App\Models\Karyawan;
use URL;

class FeedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function view()
    {
        //
        $no = 0;
        $feed = Feeds::orderBy( 'id', 'desc' )->get();
        return view( 'feeds.view', compact( 'feed', 'no' ) );
    }

    public static function getKaryawan( $nik ) {
        $kary = Karyawan::select("nama")->where('nik', $nik)->first();
        $nama = json_decode($kary, true);
        if(isset($nama)){
            return $nama['nama'];
        }else{
            return "-";
        }
    }

    public function index()
    {
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $no = 0;
        $feed = Feeds::orderBy( 'id', 'desc' )->get();
        return view( 'feeds.index', compact( 'feed', 'no' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $status = 'create';
        return view( 'feeds.upsert', compact( 'status' ) );
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
        $feed = New Feeds;
        $feed->title = $request->title;
        $feed->content = $request->content;
        if($request->is_active == 'on'){
            $feed->is_active = 1;
        }else{
            $feed->is_active = 0;
        }
        if($request->is_notif == 'on'){
            $feed->is_notif = 1;
        }else{
            $feed->is_notif = 0;
        }
        // echo 'is_active = '.$request->is_active;die;
        // 
        // $feed->created_by = '01031908799';
        // $feed->updated_by = '01031908799';
        $feed->created_by = auth()->user()->nik;
        $feed->updated_by = auth()->user()->nik;
        $feed->save();
        if($feed->is_active==1 && $feed->is_notif==1){
            $this->notif_feed($feed->title);
        }
        return redirect( 'feeds' )->with( 'msg', 'Data is saved successfully.' );
    }

    public function ImageUploadStore(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg|max:2048',
        ]);
        // $nik = $request->nik;
        $imageName = date('YmdHis').'.'.$request->image->extension();
        $request->image->move(public_path('feeds/images/'), $imageName);
        // return response()->json( [ 'data' => public_path('images'), 'imageName' => $imageName, 'imageName' => $imageName ], 200 );
        return back()->with('success','You have successfully upload image.')->with('image',$imageName); 
    }

    public function RemoveImage(Request $request)
    {
        // return response()->json( [ 'request' => $request->image ], 200 );
        $imageName = $request->image;
        if(\File::exists(public_path('feeds/images/'.$imageName))){
            \File::delete(public_path('feeds/images/'.$imageName));
        }
        return back()->with('success','You have successfully delete image.')->with('image', $imageName); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $no = 0;
        $feed = Feeds::orderBy( 'id', 'desc' )->get();
        $path = public_path('feeds/images/');
        $images = \File::allFiles($path);
        // return response()->json( [ 'path' => $path, 'images' => $images[0]->getFilename() ], 200 );
        return view( 'feeds.images.index', compact( 'no', 'images' ) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $status = 'edit';
        $feed = Feeds::find( $id );
        return view( 'feeds.upsert', compact( 'feed', 'status' ) );
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
        $feed = Feeds::find( $id );
        $feed->title = $request->title;
        $feed->content = $request->content;
        if($request->is_active == 'on'){
            $feed->is_active = 1;
        }else{
            $feed->is_active = 0;
        }
        if($request->is_notif == 'on'){
            $feed->is_notif = 1;
        }else{
            $feed->is_notif = 0;
        }
        // echo 'is_active = '.$request->is_active;die;
        // $feed->created_by = '01031908799';
        // $feed->updated_by = '01031908799';
        $feed->created_by = auth()->user()->nik;
        $feed->updated_by = auth()->user()->nik;
        $feed->update();
        return redirect( 'feeds' )->with( 'msg', 'Data is saved successfully.' );
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
        $feed = Feeds::find( $id );
        $feed->delete();
        return redirect( 'feeds' )->with( 'msg', 'Data is deleted successfully.' );
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

    public function sendNotifFeed($nama, $to, $_title)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', URL::to('https://fcm.googleapis.com/v1/projects/matta-reborn-98e4c/messages:send'), [
                'headers' => [
                    'Authorization' => 'Bearer '.(new NotificationController)->getToken(),
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
}
