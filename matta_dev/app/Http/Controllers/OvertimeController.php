<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Overtime;
use App\Models\OvertimeLog;
use App\Models\OvertimeHistory;
use App\Models\OvertimeType;
use App\Models\Karyawan;
use App\Models\Perusahaan;
use Carbon\Carbon;
use DB;
use App\Models\Holiday;
use App\Models\WorkingDay;
// 
use App\Models\MtrUtility;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;
use URL;
use Illuminate\Support\Facades\Crypt;

class OvertimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function dashboard(Request $request, $id)
    {
        // nkp
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        // initial
        // $nominal_perusahaan_tahun = [];
        $trx = $this->getOvertimeByGroup('perusahaan');
        $nominal_perusahaan_tahun = $trx->groupBy(function ($item) {
            return Carbon::createFromFormat('Y-m', $item->bulan)->format('Y');
        })->map(function ($items, $tahun) {
            $data = [];
            foreach ($items as $item) {
                if (!isset($data[$item->group_key])) {
                    $data[$item->group_key] = [
                        'jumlah' => 0,
                        'detail' => [],
                    ];
                }
                $data[$item->group_key]['jumlah'] += $item->nominal;
                $data[$item->group_key]['detail'][] = [
                    'nik'  => json_decode($item->ot_maker_property, true)['nik'],
                    'nama' => json_decode($item->ot_maker_property, true)['nama'],
                    'bulan' => Carbon::createFromFormat('Y-m', $item->bulan)->format('M Y'),
                    'nominal' => $item->nominal,
                ];
            }
            return [$tahun => $data];
        })->values()->collapse()->toArray();
        // $nominal_perusahaan_bulan = [];
        $trx = $this->getOvertimeByGroup('perusahaan');
        $nominal_perusahaan_bulan = $this->formatOvertimeGroup($trx);
        // $nominal_status_bulan = [];
        $trx = $this->getOvertimeByGroup('status_karyawan');
        $nominal_status_bulan = $this->formatOvertimeGroup($trx);
        // $nominal_divisi_bulan = [];
        $trx = $this->getOvertimeByGroup('dep');
        $nominal_divisi_bulan = $this->formatOvertimeGroup($trx);
        // $jumlah_status_bulan = [];
        $trx = Overtime::where('ot_length_rp_adj', '>', 0)
        ->whereYear('ot_start_at', date('Y'))
        ->select(
            DB::raw('DATE_FORMAT(ot_start_at, "%Y-%m") as bulan'),
            DB::raw('JSON_UNQUOTE(JSON_EXTRACT(ot_maker_property, "$.status_karyawan")) as group_key'),
            DB::raw('COUNT(DISTINCT JSON_UNQUOTE(JSON_EXTRACT(ot_maker_property, "$.nik"))) as jumlah_karyawan')
        )
        ->groupBy(
            DB::raw('DATE_FORMAT(ot_start_at, "%Y-%m")'),
            DB::raw('JSON_UNQUOTE(JSON_EXTRACT(ot_maker_property, "$.status_karyawan"))')
        )
        ->orderBy('bulan')
        ->orderBy('group_key')
        ->get();
        // return response()->json( [ 'trx' => $trx ], 200 );
        $jumlah_status_bulan = $trx->groupBy('bulan')->map(function ($items, $bulan) {
            $bulanFormat = Carbon::createFromFormat('Y-m', $bulan)->format('M Y');
            $data = [];
            foreach ($items as $item) {
                $data[$item->group_key] = [
                    'jumlah' => $item->jumlah_karyawan,
                    'detail' => [],
                ];
                // $data[$item->group_key]['detail'][] = [
                //     'nik'  => json_decode($item->ot_maker_property, true)['nik'],
                //     'nama' => json_decode($item->ot_maker_property, true)['nama'],
                //     'bulan' => Carbon::createFromFormat('Y-m', $item->bulan)->format('M Y'),
                //     'nominal' => $item->nominal,
                // ];
            }
            return [$bulanFormat => $data];
        })->values()->collapse()->toArray();
        // $jumlah_perusahaan_status_bulan = [];
        $trx = Overtime::where('ot_length_rp_adj', '>', 0)
        ->whereYear('ot_start_at', date('Y'))
        ->select(
            DB::raw('DATE_FORMAT(ot_start_at, "%Y-%m") as bulan'),
            DB::raw('JSON_UNQUOTE(JSON_EXTRACT(ot_maker_property, "$.perusahaan")) as perusahaan'),
            DB::raw('JSON_UNQUOTE(JSON_EXTRACT(ot_maker_property, "$.status_karyawan")) as status_karyawan'),
            DB::raw('COUNT(DISTINCT JSON_UNQUOTE(JSON_EXTRACT(ot_maker_property, "$.nik"))) as jumlah_karyawan')
        )
        ->groupBy(
            DB::raw('DATE_FORMAT(ot_start_at, "%Y-%m")'),
            DB::raw('JSON_UNQUOTE(JSON_EXTRACT(ot_maker_property, "$.perusahaan"))'),
            DB::raw('JSON_UNQUOTE(JSON_EXTRACT(ot_maker_property, "$.status_karyawan"))')
        )
        ->orderBy('bulan')
        ->orderBy('perusahaan')
        ->orderBy('status_karyawan')
        ->get();
        $jumlah_perusahaan_status_bulan = $trx->groupBy('bulan')->map(function ($items, $bulan) {
            $bulanFormat = Carbon::createFromFormat('Y-m', $bulan)->format('M Y');
            $data = [];
            foreach ($items as $item) {
                if (!isset($data[$item->perusahaan])) {
                    $data[$item->perusahaan] = [];
                }
                $data[$item->perusahaan][$item->status_karyawan] = [
                    'jumlah' => $item->jumlah_karyawan,
                    'detail' => [],
                ];
            }
            return [$bulanFormat => $data];
        })->values()->collapse()->toArray();
        // return response()->json( [ 'jumlah_perusahaan_status_bulan' => $jumlah_perusahaan_status_bulan ], 200 );
        $lable = ['Data Over Time ', 'Nominal Overtime Per Perusahaan', 'Nominal Overtime per Status Karyawan', 'Nominal Overtime Per Divisi', 'Jumlah Karyawan Overtime Per Status Karyawan', 'Jumlah Karyawan Overtime Per Perusahaan Per Status Karyawan'];
        // $bulan_tahun_dari = '';
        // $bulan_tahun_sampai = '';
        $bulan_tahun_dari = Carbon::now()->startOfYear()->format('Y-m');
        $bulan_tahun_sampai = Carbon::now()->format('Y-m');
        // 
        $arr = Overtime::select('ot_maker_property')->where('ot_length_rp_adj', '>', 0)->get();
        $arr_perusahaan = array();
        $perusahaan = $arr->map(fn($item) => json_decode($item->ot_maker_property, true)['perusahaan'])
        // ->map(fn($item) => strtoupper(str_replace('.', '', json_decode($item->ot_maker_property, true)['perusahaan'])))
        ->filter()
        ->unique()
        ->sort()
        ->values()->toArray();
        // 
        $arr_direktorat = array();
        $direktorat = $arr->map(fn($item) => json_decode($item->ot_maker_property, true)['divisi'])
        // ->map(fn($item) => strtoupper(str_replace('.', '', json_decode($item->ot_maker_property, true)['divisi'])))
        ->filter()
        ->unique()
        ->sort()
        ->values()->toArray();
        // 
        $arr_divisi = array();
        $divisi = $arr->map(fn($item) => json_decode($item->ot_maker_property, true)['dep'])
        // ->map(fn($item) => strtoupper(str_replace('.', '', json_decode($item->ot_maker_property, true)['dep'])))
        ->filter()
        ->unique()
        ->sort()
        ->values()->toArray();
        // 
        $arr_status_karyawan = array();
        $status_karyawan = $arr->map(fn($item) => json_decode($item->ot_maker_property, true)['status_karyawan'])
        // ->map(fn($item) => strtoupper(str_replace('.', '', json_decode($item->ot_maker_property, true)['status_karyawan'])))
        ->filter()
        ->unique()
        ->sort()
        ->values()->toArray();
        // dd($perusahaan, $direktorat, $divisi, $status_karyawan);
        // start filter
        if($request->filter == true && $id == 1){
            $bulan_tahun_dari = $request->bulan_tahun_dari;
            $bulan_tahun_sampai = $request->bulan_tahun_sampai;
            $_perusahaan = $request->input('perusahaan', []);
            $_direktorat = $request->input('direktorat', []);
            $_divisi = $request->input('divisi', []);
            $_status_karyawan = $request->input('status_karyawan', []);
            if(count($_perusahaan) > 0){
                $arr_perusahaan = $_perusahaan;
            }
            else{
                $arr_perusahaan = array();
            }
            if(count($_direktorat) > 0){
                $arr_direktorat = $_direktorat;
            }
            else{
                $arr_direktorat = array();
            }
            if(count($_divisi) > 0){
                $arr_divisi = $_divisi;
            }
            else{
                $arr_divisi = array();
            }
            if(count($_status_karyawan) > 0){
                $arr_status_karyawan = $_status_karyawan;
            }
            else{
                $arr_status_karyawan = array();
            }
            // $nominal_perusahaan_tahun = [];
            $trx = $this->getOvertimeByGroup('perusahaan', $bulan_tahun_dari, $bulan_tahun_sampai);
            $nominal_perusahaan_tahun = $trx->groupBy(function ($item) {
                return Carbon::createFromFormat('Y-m', $item->bulan)->format('Y');
            })->map(function ($items, $tahun) {
                $data = [];
                foreach ($items as $item) {
                    if (!isset($data[$item->group_key])) {
                        $data[$item->group_key] = [
                            'jumlah' => 0,
                            'detail' => [],
                        ];
                    }
                    $data[$item->group_key]['jumlah'] += $item->nominal;
                }
                return [$tahun => $data];
            })->values()->collapse()->toArray();
            // $nominal_perusahaan_bulan = [];
            $trx = $this->getOvertimeByGroup('perusahaan', $bulan_tahun_dari, $bulan_tahun_sampai);
            $nominal_perusahaan_bulan = $this->formatOvertimeGroup($trx);
            // $nominal_status_bulan = [];
            $trx = $this->getOvertimeByGroup('status_karyawan', $bulan_tahun_dari, $bulan_tahun_sampai);
            $nominal_status_bulan = $this->formatOvertimeGroup($trx);
            // $nominal_divisi_bulan = [];
            $trx = $this->getOvertimeByGroup('dep', $bulan_tahun_dari, $bulan_tahun_sampai);
            $nominal_divisi_bulan = $this->formatOvertimeGroup($trx);
            // $jumlah_status_bulan = [];
            $trx = Overtime::where('ot_length_rp_adj', '>', 0)
            ->whereBetween('ot_start_at', [
                Carbon::createFromFormat('Y-m', $bulan_tahun_dari)->startOfMonth(),
                Carbon::createFromFormat('Y-m', $bulan_tahun_sampai)->endOfMonth(),
            ])
            ->select(
                DB::raw('DATE_FORMAT(ot_start_at, "%Y-%m") as bulan'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(ot_maker_property, "$.status_karyawan")) as group_key'),
                DB::raw('COUNT(DISTINCT JSON_UNQUOTE(JSON_EXTRACT(ot_maker_property, "$.nik"))) as jumlah_karyawan')
            )
            ->groupBy(
                DB::raw('DATE_FORMAT(ot_start_at, "%Y-%m")'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(ot_maker_property, "$.status_karyawan"))')
            )
            ->orderBy('bulan')
            ->orderBy('group_key')
            ->get();
            $jumlah_status_bulan = $trx->groupBy('bulan')->map(function ($items, $bulan) {
                $bulanFormat = Carbon::createFromFormat('Y-m', $bulan)->format('M Y');
                $data = [];
                foreach ($items as $item) {
                    $data[$item->group_key] = [
                        'jumlah' => $item->jumlah_karyawan,
                        'detail' => [],
                    ];
                }
                return [$bulanFormat => $data];
            })->values()->collapse()->toArray();
            // $jumlah_perusahaan_status_bulan = [];
            $trx = Overtime::where('ot_length_rp_adj', '>', 0)
            ->whereBetween('ot_start_at', [
                Carbon::createFromFormat('Y-m', $bulan_tahun_dari)->startOfMonth(),
                Carbon::createFromFormat('Y-m', $bulan_tahun_sampai)->endOfMonth(),
            ])
            ->select(
                DB::raw('DATE_FORMAT(ot_start_at, "%Y-%m") as bulan'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(ot_maker_property, "$.perusahaan")) as perusahaan'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(ot_maker_property, "$.status_karyawan")) as status_karyawan'),
                DB::raw('COUNT(DISTINCT JSON_UNQUOTE(JSON_EXTRACT(ot_maker_property, "$.nik"))) as jumlah_karyawan')
            )
            ->groupBy(
                DB::raw('DATE_FORMAT(ot_start_at, "%Y-%m")'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(ot_maker_property, "$.perusahaan"))'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(ot_maker_property, "$.status_karyawan"))')
            )
            ->orderBy('bulan')
            ->orderBy('perusahaan')
            ->orderBy('status_karyawan')
            ->get();
            $jumlah_perusahaan_status_bulan = $trx->groupBy('bulan')->map(function ($items, $bulan) {
                $bulanFormat = Carbon::createFromFormat('Y-m', $bulan)->format('M Y');
                $data = [];
                foreach ($items as $item) {
                    if (!isset($data[$item->perusahaan])) {
                        $data[$item->perusahaan] = [];
                    }
                    $data[$item->perusahaan][$item->status_karyawan] = [
                        'jumlah' => $item->jumlah_karyawan,
                        'detail' => [],
                    ];
                }
                return [$bulanFormat => $data];
            })->values()->collapse()->toArray();
        }
        else{
            // 
        }
        // end filter
        return view('overtime.dashboard', compact('id', 'lable', 'bulan_tahun_dari', 'bulan_tahun_sampai', 'nominal_perusahaan_tahun', 'nominal_perusahaan_bulan', 'nominal_status_bulan', 'nominal_divisi_bulan', 'jumlah_status_bulan', 'jumlah_perusahaan_status_bulan', 'perusahaan', 'arr_perusahaan', 'direktorat', 'arr_direktorat', 'divisi', 'arr_divisi', 'status_karyawan', 'arr_status_karyawan'));
    }

    public function getOvertimeByGroup(string $groupKey, $dari = null, $sampai = null)
    {
        $maxNominal = MtrUtility::find(33)->informasi;
        $query = Overtime::where('ot_length_rp_adj', '>', 0);
        if ($dari && $sampai) {
            $query->whereBetween('ot_start_at', [
                Carbon::createFromFormat('Y-m', $dari)->startOfMonth(),
                Carbon::createFromFormat('Y-m', $sampai)->endOfMonth(),
            ]);
        } else {
            $dari   = date('Y') . '-01';
            $sampai = date('Y') . '-12';
            $query->whereBetween('ot_start_at', [
                Carbon::createFromFormat('Y-m', $dari)->startOfMonth(),
                Carbon::createFromFormat('Y-m', $sampai)->endOfMonth(),
            ]);
        }
        return $query->select(
                'ot_maker_by',
                'ot_maker_property',
                DB::raw('DATE_FORMAT(ot_start_at, "%Y-%m") as bulan'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(ot_maker_property, "$.' . $groupKey . '")) as group_key'),
                // DB::raw('SUM(ot_length_rp_adj) as nominal')
                // DB::raw('SUM(ROUND(ot_length_rp_adj, -3)) as nominal')
                DB::raw('CASE WHEN SUM(ROUND(ot_length_rp_adj, -3)) > ' . $maxNominal . ' THEN ' . $maxNominal . ' ELSE SUM(ROUND(ot_length_rp_adj, -3)) END as nominal')
                // DB::raw('ROUND(ot_length_rp_adj, -3) as nominal')
            )
            ->groupBy(
                'ot_maker_by',
                DB::raw('DATE_FORMAT(ot_start_at, "%Y-%m")'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(ot_maker_property, "$.' . $groupKey . '"))')
            )
            ->orderBy('bulan')
            ->orderBy('group_key')
            ->get();
    }

    public function formatOvertimeGroup($trx)
    {
        return $trx->groupBy('bulan')->map(function ($items, $bulan) {
            $bulanFormat = Carbon::createFromFormat('Y-m', $bulan)->format('M Y');
            $data = [];
            foreach ($items as $item) {
                if (!isset($data[$item->group_key])) {
                    $data[$item->group_key] = [
                        'jumlah' => 0,
                        'detail' => [],
                    ];
                }
                $data[$item->group_key]['jumlah'] += $item->nominal;
                $data[$item->group_key]['detail'][] = [
                    'nik'  => json_decode($item->ot_maker_property, true)['nik'],
                    'nama' => json_decode($item->ot_maker_property, true)['nama'],
                    'bulan' => Carbon::createFromFormat('Y-m', $item->bulan)->format('M Y'),
                    'nominal' => $item->nominal,
                ];
            }
            return [$bulanFormat => $data];
        })->values()->collapse()->toArray();
    }

    // public function formatOvertimeGroup($trx)
    // {
    //     return $trx->groupBy('bulan')->map(function ($items, $bulan) {
    //         $bulanFormat = Carbon::createFromFormat('Y-m', $bulan)->format('M Y');

    //         $data = [];
    //         foreach ($items as $item) {
    //             $data[$item->group_key] = [
    //                 'jumlah' => $item->nominal,
    //                 'detail' => [],
    //             ];
    //         }
    //         return [$bulanFormat => $data];
    //     })->values()->collapse()->toArray();
    // }


    public function view(Request $request)
    {
        //
        // $ot = Overtime::orderBy( 'ot_maker_at', 'desc' )->paginate( 50 );
        $this->authorize('spv');
        $no = 0;
        $tahun = Carbon::parse(now())->format('Y');
        $bulan = Carbon::parse(now())->format('m');
        // $ot = Overtime::orderBy( 'ot_maker_at', 'desc' )->limit(100)->get();
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $ot = Overtime::whereYear('ot_start_at', $tahun)->whereMonth('ot_start_at', $bulan)->orderBy( 'ot_maker_at', 'desc' )->get();
        if(Gate::allows('hc_nkp')){
            $ot = Overtime::whereIn('ot_maker_by',$arr_nik_nkp)->whereYear('ot_start_at', $tahun)->whereMonth('ot_start_at', $bulan)->orderBy( 'ot_maker_at', 'desc' )->get();
        }
        if(auth()->user()->userrole == 'nhc'){
            $where = ['ot_approver_by' => auth()->user()->nik];
            // $ot = Overtime::where($where)->orderBy( 'ot_maker_at', 'desc' )->limit(100)->get();
            $ot = Overtime::where($where)->whereYear('ot_start_at', $tahun)->whereMonth('ot_start_at', $bulan)->orderBy( 'ot_maker_at', 'desc' )->get();
        }
        // 
        //passing parameter ke formulir page
        // hanya karyawan bawahan langsung saja
        $where = ['is_aktif' => 1, 'layer1' => auth()->user()->nik];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        // filter parameter
        $_arr_ot_approver_status = Overtime::distinct()->orderBy( 'ot_approver_status', 'asc' )->get(['ot_approver_status']);
        $arr_ot_approver_status = array();
        foreach ($_arr_ot_approver_status as $data){
            if(!in_array($data->ot_approver_status, $arr_ot_approver_status)){
                array_push($arr_ot_approver_status, $data->ot_approver_status);
            }
        }
        $ot_approver_status = $arr_ot_approver_status;
        $date_from = '';
        $date_to = '';
        $ot_maker_by = '';
        $ot_approver_by = '';
        // start filter
        if($request->filter == true){
            $date_from = $request->date_from;
            $date_to = $request->date_to;
            $ot_maker_by = $request->ot_maker_by;
            $ot_approver_by = $request->ot_approver_by;
            $_ot_approver_status = $request->input('ot_approver_status', []);
            if(count($_ot_approver_status) > 0){
                $arr_ot_approver_status = $_ot_approver_status;
            }
            else{
                $arr_ot_approver_status = array();
            }
            $_ot_maker_by = Karyawan::select('nik')->where('nama','LIKE','%'.$ot_maker_by.'%')->get();
            $_ot_approver_by = Karyawan::select('nik')->where('nama','LIKE','%'.$ot_approver_by.'%')->get();
            $ot = Overtime::whereIn('ot_approver_status', $arr_ot_approver_status)->whereIn('ot_maker_by', $_ot_maker_by)->whereIn('ot_approver_by', $_ot_approver_by)->whereBetween(DB::raw('DATE(ot_start_at)'), [$date_from, $date_to])->orderBy( 'ot_maker_at', 'desc' )->get();
            if(Gate::allows('hc_nkp')){
                $ot = Overtime::whereIn('ot_maker_by',$arr_nik_nkp)->whereIn('ot_approver_status', $arr_ot_approver_status)->whereIn('ot_maker_by', $_ot_maker_by)->whereIn('ot_approver_by', $_ot_approver_by)->whereBetween(DB::raw('DATE(ot_start_at)'), [$date_from, $date_to])->orderBy( 'ot_maker_at', 'desc' )->get();
            }
            if(auth()->user()->userrole == 'nhc'){
                $where = ['ot_approver_by' => auth()->user()->nik];
                $ot = Overtime::whereIn('ot_approver_status', $arr_ot_approver_status)->whereIn('ot_maker_by', $_ot_maker_by)->whereIn('ot_approver_by', $_ot_approver_by)->where($where)->whereBetween(DB::raw('DATE(ot_start_at)'), [$date_from, $date_to])->orderBy( 'ot_maker_at', 'desc' )->get();
            }
        }else{
            $arr_ot_approver_status = array();
        }
        // end filter
        return view( 'overtime.approval.index', compact( 'ot', 'no', 'ot_approver_status', 'arr_ot_approver_status', 'date_from', 'date_to', 'ot_maker_by', 'ot_approver_by', 'karyawan' ) );
        // return view( 'overtime.approval.index', compact( 'ot', 'no' ) );
    }

    public function review($id)
    {
        //
        $type_code = OvertimeType::orderBy( 'ot_type', 'asc' )->get();
        $status = 'edit';
        $overtime = Overtime::find($id);
        if(auth()->user()->nik <> $overtime->ot_approver_by){
            return redirect()->route('error-403');
        }
        $karyawan = Karyawan::where('nik', $overtime->ot_maker_by)->first();
        $history = OvertimeLog::where('id_ot', $id)->orderBy( 'id', 'desc' )->get();
        // return response()->json( [ 'OT log' => $log ], 200 );
        return view( 'overtime.approval.review', compact( 'type_code', 'overtime', 'status', 'karyawan','history' ) );
    }

    public function review_todolist($id)
    {
        //
        $linkAction = route('approval-appject-todolist', $id);
        $overtime = Overtime::find($id);
        $karyawan = json_decode($overtime->ot_maker_property, true);
        $data = ['nik' => $karyawan['nik'], 'nama' => $karyawan['nama'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep']];
        if ($overtime->ot_approver_status == 'Waiting') {
            return view('emails.overtime', compact('overtime', 'data', 'linkAction'));
        } else {
            return view('emails.overtime_ok');
        }
    }

    public function appject(Request $request, $id)
    {
        $this->authorize('spv');
        //
        // tidak digunakan, karena sudah menggunakan rest-api
        // start
        // $now = date("Y-m-d H:i:s");
        // $ot = Overtime::find($id);
        // $ot->ot_approver_status = $request->res;
        // $ot->ot_approver_at = $now;
        // $ot->update();
        // return redirect( 'approval-index' )->with( 'msg', 'Data is saved successfully.' );
        // end
        $client = new \GuzzleHttp\Client();
        $response = $client->request('PATCH', URL::to('/').'/api/ot/'.$id, [
            'form_params' => [
                'ot_approver_status' => $request->res
            ]
        ]);
        $arr = json_decode($response->getBody()->getContents(), true);
        return redirect( 'approval-index' )->with( 'msg', $arr['message'] );
    }

    public function appject_todolist(Request $request, $id)
    {
        //
        $client = new \GuzzleHttp\Client();
        $response = $client->request('PATCH', URL::to('/').'/api/ot/'.$id, [
            'form_params' => [
                'ot_approver_status' => $request->res
            ]
        ]);
        return view('emails.overtime_ok');
        // $arr = json_decode($response->getBody()->getContents(), true);
        // return redirect( 'approval-index' )->with( 'msg', $arr['message'] );
    }

    public function getNikProperty($nik)
    {
        // $where = ['nik' => $nik, 'is_aktif' => 1];
        $where = ['nik' => $nik];
        $data = Karyawan::select('mtr_tbl_karyawan.nik', 'mtr_tbl_karyawan.nama', 'mtr_tbl_karyawan.email', 'mtr_tbl_karyawan.telepon1', 
        'mtr_tbl_perusahaan.nama as perusahaan', 'mtr_tbl_divisi.nama as divisi', 'mtr_tbl_departemen.nama as dep', 'mtr_tbl_sub_departemen.nama as sub_dep', 
        'mtr_tbl_level.level as level', 'mtr_tbl_jabatan.jabatan as jabatan', 'mtr_tbl_level.id as id_level', 'mtr_tbl_working_group.wg_name as wg_name', 
        'mtr_tbl_working_group.id as wg_id', 'mtr_tbl_working_group.is_reguler as is_reguler',
        'mtr_tbl_status_karyawan.status_karyawan as status_karyawan', 'mtr_tbl_karyawan.gaji_pokok as gaji_pokok')
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

    public function sendEmail($mailData, $ke_cc)
    {
        $ccEmails = explode(',', MtrUtility::find(11)->informasi);
        array_push($ccEmails, $ke_cc);
        Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
    }


    public function index(Request $request)
    {
        //
        // $ot = Overtime::orderBy( 'ot_maker_at', 'desc' )->paginate( 50 );
        $no = 0;
        $tahun = Carbon::parse(now())->format('Y');
        $bulan = Carbon::parse(now())->format('m');
        // $ot = Overtime::orderBy( 'ot_maker_at', 'desc' )->limit(100)->get();
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $ot = Overtime::whereYear('ot_start_at', $tahun)->whereMonth('ot_start_at', $bulan)->orderBy( 'ot_maker_at', 'desc' )->get();
        if(Gate::allows('hc_nkp')){
            $ot = Overtime::whereIn('ot_maker_by',$arr_nik_nkp)->whereYear('ot_start_at', $tahun)->whereMonth('ot_start_at', $bulan)->orderBy( 'ot_maker_at', 'desc' )->get();
        }
        if(auth()->user()->userrole == 'nhc'){
            $where = ['ot_maker_by' => auth()->user()->nik];
            // $ot = Overtime::where($where)->orderBy( 'ot_maker_at', 'desc' )->limit(100)->get();
            $ot = Overtime::where($where)->whereYear('ot_start_at', $tahun)->whereMonth('ot_start_at', $bulan)->orderBy( 'ot_maker_at', 'desc' )->get();
        }
        // filter parameter
        $_arr_ot_approver_status = Overtime::distinct()->orderBy( 'ot_approver_status', 'asc' )->get(['ot_approver_status']);
        $arr_ot_approver_status = array();
        foreach ($_arr_ot_approver_status as $data){
            if(!in_array($data->ot_approver_status, $arr_ot_approver_status)){
                array_push($arr_ot_approver_status, $data->ot_approver_status);
            }
        }
        $ot_approver_status = $arr_ot_approver_status;
        $date_from = '';
        $date_to = '';
        $ot_maker_by = '';
        $ot_approver_by = '';
        // start filter
        if($request->filter == true){
            $date_from = $request->date_from;
            $date_to = $request->date_to;
            $ot_maker_by = $request->ot_maker_by;
            $ot_approver_by = $request->ot_approver_by;
            $_ot_approver_status = $request->input('ot_approver_status', []);
            if(count($_ot_approver_status) > 0){
                $arr_ot_approver_status = $_ot_approver_status;
            }
            else{
                $arr_ot_approver_status = array();
            }
            $_ot_maker_by = Karyawan::select('nik')->where('nama','LIKE','%'.$ot_maker_by.'%')->get();
            $_ot_approver_by = Karyawan::select('nik')->where('nama','LIKE','%'.$ot_approver_by.'%')->get();
            $ot = Overtime::whereIn('ot_approver_status', $arr_ot_approver_status)->whereIn('ot_maker_by', $_ot_maker_by)->whereIn('ot_approver_by', $_ot_approver_by)->whereBetween(DB::raw('DATE(ot_start_at)'), [$date_from, $date_to])->orderBy( 'ot_maker_at', 'desc' )->get();
            if(Gate::allows('hc_nkp')){
                $ot = Overtime::whereIn('ot_maker_by',$arr_nik_nkp)->whereIn('ot_approver_status', $arr_ot_approver_status)->whereIn('ot_maker_by', $_ot_maker_by)->whereIn('ot_approver_by', $_ot_approver_by)->whereBetween(DB::raw('DATE(ot_start_at)'), [$date_from, $date_to])->orderBy( 'ot_maker_at', 'desc' )->get();
            }
            if(auth()->user()->userrole == 'nhc'){
                $where = ['ot_maker_by' => auth()->user()->nik];
                $ot = Overtime::whereIn('ot_approver_status', $arr_ot_approver_status)->whereIn('ot_maker_by', $_ot_maker_by)->whereIn('ot_approver_by', $_ot_approver_by)->where($where)->whereBetween(DB::raw('DATE(ot_start_at)'), [$date_from, $date_to])->orderBy( 'ot_maker_at', 'desc' )->get();
            }
        }else{
            $arr_ot_approver_status = array();
        }
        // end filter
        return view( 'overtime.propose.index', compact( 'ot', 'no', 'ot_approver_status', 'arr_ot_approver_status', 'date_from', 'date_to', 'ot_maker_by', 'ot_approver_by' ) );
        // return view( 'overtime.propose.index', compact( 'ot', 'no' ) );
    }
    
    public function reporting(Request $request)
    {
        // $trx = Overtime::whereDate('ot_start_at', '>=', '2026-01-01')->orderBy('ot_start_at', 'ASC')->get();
        // // dd($trx);
        // $no = 0;
        // foreach($trx as $_trx){
        //     // update gapok pada nik property
        //     $data = Overtime::find($_trx->id);
        //     $data->ot_maker_property = $this->getNikProperty($_trx->ot_maker_by);
        //     $data->update();
        //     if(!empty($_trx->ot_length_rev)){
        //         // update ot salary
        //         $waktu = explode(':', $_trx->ot_length_rev);
        //         $ot_length_adj = ($waktu[0] * 3600) + ($waktu[1] * 60) + $waktu[2];
        //         $id = $_trx->id;
        //         $ot = Overtime::find($id);
        //         // jika di history belum ada maka insert before
        //         if(OvertimeHistory::where('id_ot', $id)->doesntExist()){
        //             $this->save_ot_history($ot);
        //         }
        //         $arr = json_decode($ot->ot_maker_property, true);
        //         $ot->ot_length_adj = $ot_length_adj;
        //         $ot_length_rp_adj = $this->getOTSalary($ot->ot_start_at, $ot->ot_maker_by, $arr['is_reguler'], $arr['wg_id'], $arr['gaji_pokok'], $ot_length_adj, $arr['status_karyawan'], $ot->ot_freelance_property);
        //         $ot->ot_length_rp_adj = $ot_length_rp_adj;
        //         $ot->update();
        //         $ot = Overtime::find($id);
        //         // insert after
        //         $this->save_ot_history($ot);
        //         $no++;
        //     }
        // }
        // // dd($no);
        $this->authorize('spv');
        $tahun = Carbon::parse(now())->format('Y');
        $bulan = Carbon::parse(now())->format('m');
        $where = ['ot_approver_status' => 'Approve'];
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $ot = Overtime::where($where)->whereYear('ot_start_at', $tahun)->whereMonth('ot_start_at', $bulan)->orderBy( 'ot_start_at', 'desc' )->get();
        $otrecap = Overtime::select('ot_maker_property', 'ot_maker_by', 'ot_approver_by', DB::raw('SUM(ROUND(ot_length_rp_adj,-3)) as ot_length_rp_adj'))->where($where)->whereYear('ot_start_at', $tahun)->whereMonth('ot_start_at', $bulan)->orderBy( 'ot_start_at', 'desc' )->groupBy('ot_maker_by')->get();
        if(Gate::allows('hc_nkp')){
            $ot = Overtime::whereIn('ot_maker_by',$arr_nik_nkp)->where($where)->whereYear('ot_start_at', $tahun)->whereMonth('ot_start_at', $bulan)->orderBy( 'ot_start_at', 'desc' )->get();
            $otrecap = Overtime::select('ot_maker_property', 'ot_maker_by', 'ot_approver_by', DB::raw('SUM(ROUND(ot_length_rp_adj,-3)) as ot_length_rp_adj'))->whereIn('ot_maker_by',$arr_nik_nkp)->where($where)->whereYear('ot_start_at', $tahun)->whereMonth('ot_start_at', $bulan)->orderBy( 'ot_start_at', 'desc' )->groupBy('ot_maker_by')->get();
        }
        // $ot = Overtime::where($where)->whereYear('ot_start_at', $tahun)->whereMonth('ot_start_at', $bulan)->orderBy( 'ot_start_at', 'desc' )->limit(100)->get();
        if(auth()->user()->userrole == 'nhc'){
            $where = ['ot_approver_status' => 'Approve', 'ot_approver_by' => auth()->user()->nik];
            // $ot = Overtime::where($where)->orderBy( 'ot_maker_at', 'desc' )->limit(100)->get();
            $ot = Overtime::where($where)->whereYear('ot_start_at', $tahun)->whereMonth('ot_start_at', $bulan)->orderBy( 'ot_start_at', 'desc' )->get();
            $otrecap = Overtime::select('ot_maker_property', 'ot_maker_by', 'ot_approver_by', DB::raw('SUM(ROUND(ot_length_rp_adj,-3)) as ot_length_rp_adj'))->where($where)->whereYear('ot_start_at', $tahun)->whereMonth('ot_start_at', $bulan)->orderBy( 'ot_start_at', 'desc' )->groupBy('ot_maker_by')->get();
        }
        // $ot = Overtime::where($where)->orderBy( 'ot_start_at', 'desc' )->limit(100)->get();
        // $ot = Overtime::where($where)->whereYear('ot_start_at', $tahun)->whereMonth('ot_start_at', $bulan)->orderBy( 'ot_start_at', 'desc' )->limit(100)->get();
        // start update field ot_maker_property dan ot_approver_property
        // dijalankan hanya 1 kali saja
        // $tahun = Carbon::parse(now())->format('Y');
        // $ot = Overtime::whereYear('ot_start_at', $tahun)->whereMonth('ot_start_at', $bulan)->orderBy( 'id', 'desc' )->get();
        // foreach ($ot as $value){
        //     $_ot = Overtime::find( $value->id );
        //     $_ot->ot_maker_property = $this->getNikProperty($value->ot_maker_by);
        //     $_ot->ot_approver_property = $this->getNikProperty($value->ot_approver_by);
        //     $arr = json_decode($_ot->ot_maker_property, true);
        //     $ot_salary = $this->getOTSalary($value->ot_start_at, $value->ot_maker_by, $arr['is_reguler'], $arr['wg_id'], $arr['gaji_pokok'], $value->ot_length_rev);
        //     $_ot->ot_length_rp = $ot_salary;
        //     $_ot->ot_length_rp_adj = $ot_salary;
        //     // $ot_salary = $this->getOTSalary('2023-06-18 19:10:00','RFIAL049',1,1,3111000,32400);
        //     // $ot_salary = $this->getOTSalary($waktu,$nik,$is_reguler,$wg_id,$gaji_pokok,$ot_length);
        //     $_ot->update();
        // }
        // end
        // return response()->json( [ 'OT salary' => $ot_salary ], 200 );
        
        // filter parameter
        $_status = ['Approve', 'Reject'];
        $_arr_ot_approver_status = Overtime::whereIn('ot_approver_status', $_status)->distinct()->orderBy( 'ot_approver_status', 'asc' )->get(['ot_approver_status']);
        // $perusahaan = Perusahaan::orderBy( 'nama', 'asc' )->get();
        // 0=NA
        // 3=SMS Project
        $perusahaan = Perusahaan::whereNotIn('id', [0,3])->orderBy( 'nama', 'asc' )->get();
        $arr_ot_approver_status = array();
        $arr_perusahaan = array();
        foreach ($_arr_ot_approver_status as $data){
            if(!in_array($data->ot_approver_status, $arr_ot_approver_status)){
                array_push($arr_ot_approver_status, $data->ot_approver_status);
            }
        }
        foreach ($perusahaan as $data){
            array_push($arr_perusahaan, $data->id);
        }
        $ot_approver_status = $arr_ot_approver_status;
        $date_from = '';
        $date_to = '';
        $ot_maker_by = '';
        $ot_approver_by = '';
        // start filter
        if($request->filter == true){
            $date_from = $request->date_from;
            $date_to = $request->date_to;
            $ot_maker_by = $request->ot_maker_by;
            $ot_approver_by = $request->ot_approver_by;
            $_ot_approver_status = $request->input('ot_approver_status', []);
            $_perusahaan = $request->input('perusahaan', []);
            if(count($_ot_approver_status) > 0){
                $arr_ot_approver_status = $_ot_approver_status;
            }
            else{
                $arr_ot_approver_status = array();
            }
            if(count($_perusahaan) > 0){
                $arr_perusahaan = $_perusahaan;
            }
            else{
                $arr_perusahaan = array();
            }
            $_ot_maker_by_ = Karyawan::select('nik')->whereIn('kode_perusahaan', $arr_perusahaan)->get();
            $_ot_maker_by = Karyawan::select('nik')->where('nama','LIKE','%'.$ot_maker_by.'%')->get();
            $_ot_approver_by = Karyawan::select('nik')->where('nama','LIKE','%'.$ot_approver_by.'%')->get();
            $ot = Overtime::whereIn('ot_maker_by', $_ot_maker_by_)->whereIn('ot_approver_status', $_status)->whereIn('ot_approver_status', $arr_ot_approver_status)->whereIn('ot_maker_by', $_ot_maker_by)->whereIn('ot_approver_by', $_ot_approver_by)->whereBetween(DB::raw('DATE(ot_start_at)'), [$date_from, $date_to])->orderBy( 'ot_maker_at', 'desc' )->get();
            $otrecap = Overtime::select('ot_maker_property', 'ot_maker_by', 'ot_approver_by', DB::raw('SUM(ROUND(ot_length_rp_adj,-3)) as ot_length_rp_adj'))->whereIn('ot_maker_by', $_ot_maker_by_)->whereIn('ot_approver_status', $_status)->whereIn('ot_approver_status', $arr_ot_approver_status)->whereIn('ot_maker_by', $_ot_maker_by)->whereIn('ot_approver_by', $_ot_approver_by)->whereBetween(DB::raw('DATE(ot_start_at)'), [$date_from, $date_to])->groupBy('ot_maker_by')->get();
            if(Gate::allows('hc_nkp')){
                $ot = Overtime::whereIn('ot_maker_by',$arr_nik_nkp)->whereIn('ot_maker_by', $_ot_maker_by_)->whereIn('ot_approver_status', $_status)->whereIn('ot_approver_status', $arr_ot_approver_status)->whereIn('ot_maker_by', $_ot_maker_by)->whereIn('ot_approver_by', $_ot_approver_by)->whereBetween(DB::raw('DATE(ot_start_at)'), [$date_from, $date_to])->orderBy( 'ot_maker_at', 'desc' )->get();
                $otrecap = Overtime::select('ot_maker_property', 'ot_maker_by', 'ot_approver_by', DB::raw('SUM(ROUND(ot_length_rp_adj,-3)) as ot_length_rp_adj'))->whereIn('ot_maker_by',$arr_nik_nkp)->whereIn('ot_maker_by', $_ot_maker_by_)->whereIn('ot_approver_status', $_status)->whereIn('ot_approver_status', $arr_ot_approver_status)->whereIn('ot_maker_by', $_ot_maker_by)->whereIn('ot_approver_by', $_ot_approver_by)->whereBetween(DB::raw('DATE(ot_start_at)'), [$date_from, $date_to])->groupBy('ot_maker_by')->get();
            }
            if(auth()->user()->userrole == 'nhc'){
                $where = ['ot_approver_by' => auth()->user()->nik];
                $ot = Overtime::whereIn('ot_maker_by', $_ot_maker_by_)->whereIn('ot_approver_status', $_status)->whereIn('ot_approver_status', $arr_ot_approver_status)->whereIn('ot_maker_by', $_ot_maker_by)->whereIn('ot_approver_by', $_ot_approver_by)->where($where)->whereBetween(DB::raw('DATE(ot_start_at)'), [$date_from, $date_to])->orderBy( 'ot_maker_at', 'desc' )->get();
                $otrecap = Overtime::select('ot_maker_property', 'ot_maker_by', 'ot_approver_by', DB::raw('SUM(ROUND(ot_length_rp_adj,-3)) as ot_length_rp_adj'))->whereIn('ot_maker_by', $_ot_maker_by_)->whereIn('ot_approver_status', $_status)->whereIn('ot_approver_status', $arr_ot_approver_status)->whereIn('ot_maker_by', $_ot_maker_by)->whereIn('ot_approver_by', $_ot_approver_by)->where($where)->whereBetween(DB::raw('DATE(ot_start_at)'), [$date_from, $date_to])->groupBy('ot_maker_by')->get();
            
            }
        }else{
            $arr_ot_approver_status = array();
            $arr_perusahaan = array();
        }
        // end filter
        return view( 'overtime.reporting.index', compact( 'ot', 'otrecap', 'ot_approver_status', 'perusahaan', 'arr_ot_approver_status', 'arr_perusahaan', 'date_from', 'date_to', 'ot_maker_by', 'ot_approver_by' ) );
        // return view( 'overtime.reporting.index', compact( 'ot', 'no' ) );
    }
    
    public function adj_ot($id)
    {
        $this->authorize('spv');
        $data = Overtime::find($id);
        if(!(auth()->user()->nik == $data->ot_approver_by || auth()->user()->userrole == 'hc')){
            return redirect()->route('error-403');
        }
        $init = $data->ot_length_adj;
        $hours = floor($init / 3600);
        $minutes = floor(($init / 60) % 60);
        $seconds = $init % 60;
        $duration = ['hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$seconds];
        return view( 'overtime.reporting.view', compact( 'data', 'duration' ) );
    }
    
    public static function getOTSalary($waktu,$nik,$is_reguler,$wg_id,$gaji_pokok,$ot_length,$status_karyawan,$ot_freelance_property)
    {
        $tanggal = Carbon::parse($waktu)->format('Y-m-d');
        $arr = json_decode($ot_freelance_property, true);
        // reguler
        if($is_reguler == 1){
            $isHoliday = static::isHoliday($tanggal)['isHoliday'];
            if($isHoliday){
                // CM 390, Menambahkan Perhitungan OT untuk anak Magang (Perhitungan sama dengan perhitungan OT Freelance)
                // Weekend
                if($status_karyawan == 'Freelance' || $status_karyawan == 'Magang'){
                    return $arr[1]['informasi']*$ot_length/3600;
                }else{
                    return static::getOTSalaryWeekend($gaji_pokok,$ot_length);
                }
            }else{
                $isWeekend = static::isWeekend($tanggal)['isWeekend'];
                if($isWeekend){
                    // Weekend
                    if($status_karyawan == 'Freelance' || $status_karyawan == 'Magang'){
                        return $arr[1]['informasi']*$ot_length/3600;
                    }else{
                        return static::getOTSalaryWeekend($gaji_pokok,$ot_length);
                    }
                }else{
                    // Weekday
                    if($status_karyawan == 'Freelance' || $status_karyawan == 'Magang'){
                        return $arr[0]['informasi']*$ot_length/3600;
                    }else{
                        return static::getOTSalaryWeekday($gaji_pokok,$ot_length);
                    }
                }
            }
        }
        // non reguler
        else{
            $isWorkingDay = static::isWorkingDay($nik, $tanggal, $wg_id)['isWorkingDay'];
            if($isWorkingDay){
                // Weekday
                return static::getOTSalaryWeekday($gaji_pokok,$ot_length);
            }else{
                // Weekend
                return static::getOTSalaryWeekend($gaji_pokok,$ot_length);
            }
        }
    }

    public static function getOTSalaryWeekday($gaji_pokok,$ot_length)
    {
        $koef = 173;
        $hours = floor($ot_length / 3600);
        $seconds = $ot_length - ($hours * 3600);
        // kurang atau sama dengan 1 jam
        if($ot_length <= 3600){
            // perhitungan 1 jam pertama 1 x 1/173 x 1.5 x gaji_pokok
            $amount = $ot_length / 3600 / $koef * 1.5 * $gaji_pokok;
            return round($amount, 2);
        }
        // lebih dari 1 jam
        else{
            // perhitungan 1 jam pertama 1 x 1/173 x 1.5 x gaji_pokok
            $amount = 3600 / 3600 / $koef * 1.5 * $gaji_pokok;
            // perhitungan sisa jam berikutnya sisa_jam x 1/173 x 2 x gaji_pokok
            $amount += ($ot_length - 3600) / 3600 / $koef * 2 * $gaji_pokok;
            return round($amount, 2);
        }
    }

    public static function getOTSalaryWeekend($gaji_pokok,$ot_length)
    {
        $koef = 173;
        $hours = floor($ot_length / 3600);
        $seconds = $ot_length - ($hours * 3600);
        // kurang atau sama dengan 8 jam
        if($ot_length <= 3600 * 8){
            // perhitungan 8 jam pertama 8 x 1/173 x 2 x gaji_pokok
            $amount = $ot_length / 3600 / $koef * 2 * $gaji_pokok;
            return round($amount, 2);
        }
        // lebih dari 8 jam dan kurang sama dengan 9 jam
        elseif($ot_length <= 3600 * 9){
            // perhitungan 8 jam pertama 8 x 1/173 x 2 x gaji_pokok
            $amount = 3600 * 8 / 3600 / $koef * 2 * $gaji_pokok;
            // perhitungan 1 jam berikutnya 1 x 1/173 x 3 x gaji_pokok
            $amount += ($ot_length - (3600 * 8)) / 3600 / $koef * 3 * $gaji_pokok;
            return round($amount, 2);
        }
        // lebih dari 9 jam
        else{
            // perhitungan 8 jam pertama 8 x 1/173 x 2 x gaji_pokok
            $amount = 3600 * 8 / 3600 / $koef * 2 * $gaji_pokok;
            // perhitungan 1 jam berikutnya 1 x 1/173 x 3 x gaji_pokok
            $amount += 3600 * 1 / 3600 / $koef * 3 * $gaji_pokok;
            // perhitungan sisa jam berikutnya sisa_jam x 1/173 x 4 x gaji_pokok
            $amount += ($ot_length - (3600 * 9)) / 3600 / $koef * 4 * $gaji_pokok;
            return round($amount, 2);
        }
    }
    
    public static function isWeekDayWeekEndHoliday($waktu,$nik,$is_reguler,$wg_id)
    {
        $tanggal = Carbon::parse($waktu)->format('Y-m-d');
        // $tanggal = '2023-06-02';
        if($is_reguler == 1){
            return static::isWeekend($tanggal)['msg'].'<br/>'.static::isHoliday($tanggal)['msg'];
        }else{
            // return $wg_id;
            return static::isWorkingDay($nik, $tanggal, $wg_id) ['msg'];
        }
    }

    // cek apakah hari ini adalah hari libur nasional
    public static function isHoliday($tanggal) 
    {
        $where = ['tanggal' => $tanggal];
        $data = Holiday::where($where)->first();
        if(!empty($data)){
            return ['isHoliday'=> true, 'msg'=> '<span class="badge badge-danger">'.$data->libur.'</span>'];
            // return true;
        }else{
            return ['isHoliday'=> false, 'msg'=> ''];
            // return false;
        }
    }

    // cek apakah hari ini sabtu minggu
    public static function isWeekend($tanggal) 
    {
        $weekDay = date('w', strtotime($tanggal));
        if(($weekDay == 0 || $weekDay == 6)){
            return ['isWeekend'=> true, 'msg'=> '<span class="badge badge-danger">Weekend</span>'];
            // return true;
        }else{
            return ['isWeekend'=> false, 'msg'=> '<span class="badge badge-success">Weekday</span>'];
            // return false;
        }
        // return ($weekDay == 0 || $weekDay == 6);
    }

    // cek apakah hari ini adalah working day non reguler
    public static function isWorkingDay($nik, $tanggal, $wg_id) 
    {
        $cek = false;
        $where = [ 'nik' => $nik, 'working_date' => $tanggal, 'wg_id' => $wg_id ];
        $check = WorkingDay::where($where)->exists();
        if($check){
            // khusus group security jika OT di hari libur nasional dan jatuh di jadwal kerjanya maka dihitung Weekend
            if($wg_id == 2){
                $isHoliday = static::isHoliday($tanggal)['isHoliday'];
                if($isHoliday){
                    // Weekend
                    return ['isWorkingDay'=> false, 'msg'=> '<span class="badge badge-danger">Weekend</span>'];
                }else{
                    // Weekday
                    return ['isWorkingDay'=> true, 'msg'=> '<span class="badge badge-success">Weekday</span>'];
                }
            }else{
                return ['isWorkingDay'=> true, 'msg'=> '<span class="badge badge-success">Weekday</span>'];
            }
        }else{
            return ['isWorkingDay'=> false, 'msg'=> '<span class="badge badge-danger">Weekend</span>'];
        }
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // $this->authorize('staf_only');
        //
        $type_code = OvertimeType::orderBy( 'ot_type', 'asc' )->get();
        //passing parameter ke formulir page
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        $status = 'create';
        // $ot_maker_by = $request->ot_maker_by;
        $ot_maker_by = Crypt::decrypt($request->ot_maker_by);
        $where = ['nik' => $ot_maker_by];
        $pengaju = Karyawan::select('nik', 'nama')->where($where)->first();
        $pengaju = $pengaju->nama.' / '.$pengaju->nik;
        return view( 'overtime.propose.upsert', compact( 'type_code', 'status', 'pengaju', 'ot_maker_by' ) );
        // return view('formulir.sakit.insert', compact('karyawan', 'status', 'nik_karyawan', 'pengaju'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // tidak digunakan, karena sudah menggunakan rest-api
        // start
        // $now = date("Y-m-d H:i:s");
        // $ot_maker_by = auth()->user()->nik;
        // // 
        // $ot_approver_by = Karyawan::where('nik', $ot_maker_by)->first()->layer1;
        // $ot = New Overtime;
        // $ot->ot_maker_at = $now;
        // $ot->ot_maker_by = $ot_maker_by;
        // $ot->ot_approver_by = $ot_approver_by;
        // $ot->ot_maker_status = 'Waiting';
        // $ot->ot_approver_status = 'Waiting';
        // $ot->ot_start_at = $request->ot_start_at;
        // $ot->ot_end_at = $request->ot_end_at;
        // // 
        // $diff = Carbon::parse($request->ot_start_at)->diffInSeconds(Carbon::parse($request->ot_end_at));
        // $ot->ot_length = $diff;
        // $ot->ot_end_clockin = $request->ot_end_at;
        // $ot->ot_start_clockout = $request->ot_end_at;
        // // 
        // $ot->ot_start_clockin = date('Y-m-d H:i:s', strtotime($request->ot_start_at. ' - 1 hours'));
        // $ot->ot_end_clockout = date('Y-m-d H:i:s', strtotime($request->ot_end_at. ' + 3 hours'));
        // $ot->ot_title = $request->ot_title;
        // $ot->ot_desc = $request->ot_desc;
        // $ot->ot_type_code = $request->ot_type_code;
        // $ot->save();
        // end
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', URL::to('/').'/api/ot', [
            'form_params' => [
                // 'ot_maker_by' => auth()->user()->nik,
                'ot_maker_by' => $request->ot_maker_by,
                'ot_start_at' => Carbon::parse($request->ot_start_at)->format('Y-m-d H:i:s'),
                'ot_end_at' => Carbon::parse($request->ot_end_at)->format('Y-m-d H:i:s'),
                'ot_title' => $request->ot_title,
                'ot_desc' => $request->ot_desc,
                'ot_type' => $request->ot_type_code
            ]
        ]);
        $arr = json_decode($response->getBody()->getContents(), true);
        if($arr['success']){
            return redirect( 'overtime' )->with( 'msg', 'Data is saved successfully.' );
        }else{
            return redirect()->back()->with('error', $arr);
        }
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
        $type_code = OvertimeType::orderBy( 'ot_type', 'asc' )->get();
        $status = 'edit';
        $overtime = Overtime::find($id);
        if(auth()->user()->nik <> $overtime->ot_maker_by){
            return redirect()->route('error-403');
        }
        $history = OvertimeLog::where('id_ot', $id)->orderBy( 'id', 'desc' )->get();
        return view( 'overtime.propose.upsert', compact( 'type_code', 'overtime', 'status', 'history' ) );
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
        // dd($request->all(), $id);
        // return response()->json( [ 'msg' => $request->all(), 'id'=>$id ] );
        // return redirect( 'adj_ot',  [$id] )->with( ['msg', 'Data is saved successfully.'] );
        // return redirect()->route('adj_ot', $id)->with( ['msg', 'Data is saved successfully.'] );
        // $this->authorize('hc');
        $ot_length_adj = ($request->hours * 3600) + ($request->minutes * 60) + $request->seconds;
        $ot = Overtime::find($id);
        // jika di history belum ada maka insert before
        if(OvertimeHistory::where('id_ot', $id)->doesntExist()){
            $this->save_ot_history($ot);
        }
        $arr = json_decode($ot->ot_maker_property, true);
        $ot->ot_length_adj = $ot_length_adj;
        $ot_length_rp_adj = $this->getOTSalary($ot->ot_start_at, $ot->ot_maker_by, $arr['is_reguler'], $arr['wg_id'], $arr['gaji_pokok'], $ot_length_adj, $arr['status_karyawan'], $ot->ot_freelance_property);
        $ot->ot_length_rp_adj = $ot_length_rp_adj;
        $ot->update();
        $ot = Overtime::find($id);
        // insert after
        $this->save_ot_history($ot);
        // 
        // $init = $data->ot_length_adj;
        // $hours = floor($init / 3600);
        // $minutes = floor(($init / 60) % 60);
        // $seconds = $init % 60;
        // $duration = ['hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$seconds];
        // return view( 'overtime.reporting.view', compact( 'data', 'duration' ) )->with( 'msg', 'Data is saved successfully.' );
        // return view( 'overtime.reporting.view', compact( 'data', 'duration' ) );
        // return redirect( 'adj_ot',  $id)->with( 'msg', 'Data is saved successfully.' );
        return redirect()->back()->with( 'msg', 'Data is saved successfully.' );
    }
    
    public function save_ot_history($ot)
    {
        $nik = auth()->user()->nik;
        $ot_history = new OvertimeHistory;
        $ot_history->ot_maker_at = $ot->ot_maker_at;
        $ot_history->ot_approver_at = $ot->ot_approver_at;
        $ot_history->ot_maker_by = $ot->ot_maker_by;
        $ot_history->ot_maker_property = $ot->ot_maker_property;
        $ot_history->ot_freelance_property = $ot->ot_freelance_property;
        $ot_history->ot_approver_by = $ot->ot_approver_by;
        $ot_history->ot_approver_property = $ot->ot_approver_property;
        $ot_history->ot_maker_status = $ot->ot_maker_status;
        $ot_history->ot_approver_status = $ot->ot_approver_status;
        $ot_history->ot_start_at = $ot->ot_start_at;
        $ot_history->ot_end_at = $ot->ot_end_at;
        $ot_history->ot_length = $ot->ot_length;
        $ot_history->ot_start_clockin = $ot->ot_start_clockin;
        $ot_history->ot_end_clockin = $ot->ot_end_clockin;
        $ot_history->ot_start_clockout = $ot->ot_start_clockout;
        $ot_history->ot_end_clockout = $ot->ot_end_clockout;
        $ot_history->ot_title = $ot->ot_title;
        $ot_history->ot_desc = $ot->ot_desc;
        $ot_history->ot_type_code = $ot->ot_type_code;
        $ot_history->ot_start_at_real = $ot->ot_start_at_real;
        $ot_history->ot_end_at_real = $ot->ot_end_at_real;
        $ot_history->ot_length_real = $ot->ot_length_real;
        $ot_history->ot_start_at_rev = $ot->ot_start_at_rev;
        $ot_history->ot_end_at_rev = $ot->ot_end_at_rev;
        $ot_history->ot_length_rev = $ot->ot_length_rev;
        $ot_history->ot_length_adj = $ot->ot_length_adj;
        $ot_history->ot_length_rp = $ot->ot_length_rp;
        $ot_history->ot_length_rp_adj = $ot->ot_length_rp_adj;
        $ot_history->created_at = $ot->created_at;
        $ot_history->updated_at = $ot->updated_at;
        $ot_history->id_ot = $ot->id;
        $ot_history->ot_edit_by = $nik;
        $ot_history->ot_edit_property = $this->getNikProperty($nik);
        $ot_history->save();
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
