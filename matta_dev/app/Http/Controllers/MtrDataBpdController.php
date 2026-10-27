<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
// 
use App\Models\Propinsi;
use App\Models\PropinsiKota;
use App\Models\Negara;
use App\Models\Karyawan;
use App\Models\BpdBiaya;
use App\Models\BpdCoa;
use App\Models\BpdKelompok;
use App\Models\BpdNikPeran;
use App\Models\BpdNikPC;
use App\Models\BpdNonProyekBiayaDalamNegeri;
use App\Models\BpdNonProyekBiayaLuarNegeri;
use App\Models\BpdPeran;
use App\Models\BpdProyek;
use App\Models\BpdProyekAdmin;
use App\Models\BpdProyekUangSaku;
use App\Models\BpdProyekUangSakuKelompok;
use App\Models\BpdStatus;
// 
use App\Models\TrxBpdDetailBiaya;
use App\Models\TrxBpdDetailInfo;
use App\Models\TrxBpdLog;
use App\Models\TrxBpdMaster;
use App\Models\ToDoList;
use App\Models\ToDoListLog;
// 
use App\Models\MtrUtility;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
// 
use Carbon\Carbon;
use DB;
use Elibyy\TCPDF\Facades\TCPDF;
use Excel;
// 

class MtrDataBpdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $no = 0;
        $trx = BpdNikPeran::orderBy( 'id', 'asc' )->get();
        if(Gate::allows('hc_nkp')){
            $trx = BpdNikPeran::whereIn('nik',$arr_nik_nkp)->orderBy( 'id', 'asc' )->get();
        }
        return view( 'master.bpd.nip_admin.index', compact( 'trx', 'no' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $status = 'create';
        $where = ['is_aktif' => 1];
        $admin = Karyawan::where($where)->orderBy( 'nama', 'asc' )->get();
        $karyawan = Karyawan::where($where)->orderBy( 'nama', 'asc' )->get();
        if(Gate::allows('hc_nkp')){
            $karyawan = Karyawan::whereIn('nik',$arr_nik_nkp)->where($where)->orderBy( 'nama', 'asc' )->get();
        }
        return view( 'master.bpd.nip_admin.upsert', compact( 'status','karyawan', 'admin' ) );
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
        if($request->hasFile('lampiran')){
            $request->validate([
                'lampiran' => 'required|file|mimes:xls,xlsx|max:2048',
            ]);
            // BpdNikPeran::truncate();
            // 0 = NIP
            // 1 = Nama
            // 2 = Business Travel Group
            // 3 = NIP PIC BPD
            // 4 = NAMA PIC BPD
            $path = $request->file('lampiran')->getPathname();
            $trx = Excel::toArray([], $path);
            $data = $trx[0];
            for ($x = 0; $x < count($data); $x++) {
                // dd($data[2][2]);
                $nik_admin = explode(',', $data[$x][3]);
                foreach ($nik_admin as $_nik_admin){
                    $trx = New BpdNikPeran;
                    $trx->nik = $data[$x][0];
                    $id_peran = 0;
                    if($data[$x][2] == 'Leader'){
                        $id_peran = 1;
                    }else if($data[$x][2] == 'Member'){
                        $id_peran = 2;
                    }else if($data[$x][2] == 'Management'){
                        $id_peran = 3;
                    }
                    $trx->id_peran = $id_peran;
                    $trx->is_active = 1;
                    $trx->admin = trim($_nik_admin);
                    // 0 = exclude header
                    if($x <> 0){
                        $trx->save();
                    }
                }
            }
            // // start
            // $trx = $this->csvToArray($path);
            // // dd(array_values($trx[2])[3]);
            // foreach ($trx as $_trx) {
            //     $nik_admin = explode(',', array_values($_trx)[3]);
            //     foreach ($nik_admin as $_nik_admin){
            //         $trx = New BpdNikPeran;
            //         $trx->nik = array_values($_trx)[0];
            //         $id_peran = 0;
            //         if(array_values($_trx)[2] == 'Leader'){
            //             $id_peran = 1;
            //         }else if(array_values($_trx)[2] == 'Member'){
            //             $id_peran = 2;
            //         }else if(array_values($_trx)[2] == 'Management'){
            //             $id_peran = 3;
            //         }
            //         $trx->id_peran = $id_peran;
            //         $trx->is_active = 1;
            //         $trx->admin = trim($_nik_admin);
            //         $trx->save();
            //     }
            // }
            // // finish
        }else{
            $trx = New BpdNikPeran;
            $trx->nik = $request->nik;
            $trx->admin = $request->admin;
            $trx->id_peran = $request->id_peran;
            if($request->is_active == 'on'){
                $trx->is_active = 1;
            }else{
                $trx->is_active = 0;
            }
            $trx->save();
        }
        return redirect( 'mtrdatabpd' )->with( 'msg', 'Data is saved successfully.' );
    }

    public function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;
        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
                    // $data[] = array($row);
            }
            fclose($handle);
        }
        return $data;
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
        $id = Crypt::decrypt($id);
        $status = 'edit';
        $trx = BpdNikPeran::find($id);
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $where = ['is_aktif' => 1];
        $admin = Karyawan::where($where)->orderBy( 'nama', 'asc' )->get();
        $karyawan = Karyawan::where($where)->orderBy( 'nama', 'asc' )->get();
        if(Gate::allows('hc_nkp')){
            $karyawan = Karyawan::whereIn('nik',$arr_nik_nkp)->where($where)->orderBy( 'nama', 'asc' )->get();
        }
        return view( 'master.bpd.nip_admin.upsert', compact( 'trx', 'status', 'karyawan', 'admin' ) );
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
        $trx = BpdNikPeran::find($id);
        $trx->nik = $request->nik;
        $trx->admin = $request->admin;
        $trx->id_peran = $request->id_peran;
        if($request->is_active == 'on'){
            $trx->is_active = 1;
        }else{
            $trx->is_active = 0;
        }
        $trx->update();
        return redirect( 'mtrdatabpd' )->with( 'msg', 'Data is saved successfully.' );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $trx = BpdNikPeran::find($id);
        $trx->delete();
        return redirect( 'mtrdatabpd' )->with( 'msg', 'Data is deleted successfully.' );
    }
}
