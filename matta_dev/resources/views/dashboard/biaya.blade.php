@extends('layouts.simple.master')

@section('title', 'Home')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
@endsection

@section('style')
<style>
    /* button.dt-button {
    float:none;
    text-align:center;
    color: black;
    background: white;
    } */
</style>
@endsection

@section('breadcrumb-title')
    <h3>Dashboard</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Biaya Gaji</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Biaya Gaji</h5>
                        <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                        <div class="collapse" id="collapse_filter">
                            <form class="mt-2" action="{{ route('dashboard.biaya', 0) }}" method="get" enctype="multipart/form-data">
                                <div class="row mt-2">
                                    <div class="col-sm-3">
                                        <label class="h6" for="nama">Bulan-Tahun</label>
                                        <div>
                                            <input name="bulan_tahun_dari" id="bulan_tahun_dari" class="form-control digits" type="month" required="" value="{{ $bulan_tahun_dari }}">
                                            <div class="invalid-feedback txt-danger">Field is required.</div>
                                        </div>
                                        sampai
                                        <div>
                                            <input name="bulan_tahun_sampai" id="bulan_tahun_sampai" class="form-control digits" type="month" required="" value="{{ $bulan_tahun_sampai }}">
                                            <div class="invalid-feedback txt-danger">Field is required.</div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-3">
                                        <label class="h6">Tahun</label>&nbsp;<input type="checkbox" onClick="toggle_tahun(this)" /> Check All
                                        <label class="d-block" for="tahun">
                                            @foreach ($tahun as $data)
                                                <input class="checkbox_animated" id="tahun[]" name="tahun[]" type="checkbox" value="{{ $data['tahun'] }}" @if (in_array($data['tahun'], $arr_tahun)) checked @endif>
                                                {{ $data['tahun'] }}<br />
                                            @endforeach
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Bulan</label>&nbsp;<input type="checkbox" onClick="toggle_bulan(this)" /> Check All
                                        <label class="d-block" for="bulan">
                                            @foreach ($bulan as $data)
                                                <input class="checkbox_animated" id="bulan[]" name="bulan[]" type="checkbox" value="{{ $data['angka'] }}" @if (in_array($data['angka'], $arr_bulan)) checked @endif>
                                                {{ $data['bulan'] }}<br />
                                            @endforeach
                                        </label>
                                    </div> --}}
                                    <div class="col-sm-3">
                                        <label class="h6">Status Karyawan</label>&nbsp;<input type="checkbox" onClick="toggle_status_karyawan(this)" /> Check All
                                        <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                        <label class="d-block" for="status_karyawan">
                                            @foreach ($status_karyawan as $data)
                                                <input class="checkbox_animated" id="status_karyawan[]" name="status_karyawan[]" type="checkbox" value="{{ $data->status_karyawan }}" @if (in_array($data->status_karyawan, $arr_status_karyawan)) checked @endif>
                                                {{ $data->status_karyawan }}<br />
                                            @endforeach
                                        </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-3">
                                        <label class="h6">Perusahaan</label>&nbsp;<input type="checkbox" onClick="toggle_perusahaan(this)" /> Check All
                                        <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                        <label class="d-block" for="perusahaan">
                                            @foreach ($perusahaan as $data)
                                                <input class="checkbox_animated" id="perusahaan[]" name="perusahaan[]" type="checkbox" onClick="get_perusahaan()" value="{{ $data['id'] }}" @if (in_array($data['nama'], $arr_perusahaan)) checked @endif>
                                                {{ $data['nama'] }}<br />
                                            @endforeach
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Direktorat</label>&nbsp;<input type="checkbox" onClick="toggle_divisi(this)" /> Check All
                                        <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                        <label class="d-block" for="divisi">
                                            <div id="div_divisi">
                                                @foreach ($divisi as $data)
                                                    <input class="checkbox_animated" id="divisi[]" name="divisi[]" type="checkbox" onClick="get_divisi()" value="{{ $data['id'] }}" @if (in_array($data['nama'], $arr_divisi)) checked @endif>
                                                    {{ $data['nama'] }}<br />
                                                @endforeach
                                            </div>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Divisi</label>&nbsp;<input type="checkbox" onClick="toggle_departemen(this)" /> Check All
                                        <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                        <label class="d-block" for="departemen">
                                            <div id="div_departemen">
                                                @foreach ($departemen as $data)
                                                    <input class="checkbox_animated" id="departemen[]" name="departemen[]" type="checkbox" onClick="get_departemen()" value="{{ $data['id'] }}" @if (in_array($data['nama'], $arr_departemen)) checked @endif>
                                                    {{ $data['nama'] }}<br />
                                                @endforeach
                                            </div>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Departemen</label>&nbsp;<input type="checkbox" onClick="toggle_subdepartemen(this)" /> Check All
                                        <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                        <label class="d-block" for="subdepartemen">
                                            <div id="div_subdepartemen">
                                                @foreach ($subdepartemen as $data)
                                                    <input class="checkbox_animated" id="subdepartemen[]" name="subdepartemen[]" type="checkbox" value="{{ $data['id'] }}" @if (in_array($data['nama'], $arr_subdepartemen)) checked @endif>
                                                    {{ $data['nama'] }}<br />
                                                @endforeach
                                            </div>
                                        </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-12">
                                        <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="{{ route('dashboard.biaya', 0) }}" class="btn btn-info" type="button"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        {{--  --}}
                        @php
                            $biaya_gaji = 0;
                            $_biaya_gaji = 0;
                            $kompensasi_kkwt = 0;
                            $total_biaya_gaji = 0;
                            $group_by = [];
                        @endphp
                        @foreach ($payroll as $data)
                        @php
                            $where = ['nik'=>$data->nik, 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                            // status = apm
                            if($data->status == 'apm'){
                                $biaya_gaji += round($data->gapok, 0);
                                $_biaya_gaji = round($data->gapok, 0);
                                // 1=Tunjangan Makan
                                // 2=Tunjangan Transportasi
                                // 3=Tunjangan Jabatan
                                // 4=Tunjangan Telekomunikasi
                                // 5=Tunjangan Lainnya
                                // 6=Insentif
                                // 7=Kekurangan Upah
                                // 8=Sewa Laptop
                                // 21=Tunjangan Hari Raya
                                $arr_id = [1,2,3,4,5,6,7,8,21];
                                $add = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                $biaya_gaji += round($add->sum('angka'), 0);
                                $_biaya_gaji += round($add->sum('angka'), 0);
                                // 9=Kompensasi KKWT
                                $arr_id = [9];
                                $add = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                $kompensasi_kkwt += round($add->sum('angka'), 0);
                                $_biaya_gaji += round($add->sum('angka'), 0);
                                // 
                                $total_biaya_gaji = $biaya_gaji + $kompensasi_kkwt;
                            }
                            // status = osc
                            elseif($data->status == 'osc'){
                                $biaya_gaji += round($data->gapok, 0);
                                $_biaya_gaji = round($data->gapok, 0);
                                // 5=Tunjangan Lainnya
                                // 6=Insentif
                                // 7=Kekurangan Upah
                                // 8=Sewa Laptop
                                // 22=Tunjangan Telekomunikasi Tambahan
                                $arr_id = [5,6,7,8,22];
                                $add = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                $biaya_gaji += round($add->sum('angka'), 0);
                                $_biaya_gaji += round($add->sum('angka'), 0);
                                // 
                                $biaya_gaji += round($data->overtime, 0) + round($data->pajak, 0);
                                $_biaya_gaji += round($data->overtime, 0) + round($data->pajak, 0);
                                // bpjskes
                                $arr_id = [4];
                                $bpjskes = App\Models\TrxPayrollBpjskesLog::where($where)->whereIn('id_bpjskes', $arr_id)->orderBy('id_bpjskes', 'asc')->get();
                                $biaya_gaji += round($bpjskes->sum('angka'), 0);
                                $_biaya_gaji += round($bpjskes->sum('angka'), 0);
                                // bpjstk
                                $arr_id = [7];
                                $bpjstk = App\Models\TrxPayrollBpjstkLog::where($where)->whereIn('id_bpjstk', $arr_id)->orderBy('id_bpjstk', 'asc')->get();
                                $biaya_gaji += round($bpjstk->sum('angka'), 0);
                                $_biaya_gaji += round($bpjstk->sum('angka'), 0);
                                // 
                                $mgt_fee = $_biaya_gaji * $data->mgt_fee / 100;
                                $biaya_gaji += round($mgt_fee, 0);
                                $_biaya_gaji += round($mgt_fee, 0);
                                // 9=Kompensasi KKWT
                                $arr_id = [9];
                                $add = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                $angka_9 = $add->sum('angka');
                                $angka_9 += $angka_9 * $data->mgt_fee_kkwt / 100;
                                $kompensasi_kkwt += round($angka_9, 0);
                                $_biaya_gaji += round($angka_9, 0);
                                $total_biaya_gaji = $biaya_gaji + $kompensasi_kkwt;
                            }
                            // 
                            array_push($group_by, ['bulan_tahun'=>$data->bulan.'-'.$data->tahun, 'tahun'=>$data->tahun, 'perusahaan'=>$data->perusahaan, 'divisi'=>$data->divisi, 'departemen'=>$data->departemen, 'subdepartemen'=>$data->subdepartemen, 'status_karyawan'=>$data->status_karyawan, 'total_biaya_gaji'=>$_biaya_gaji]);
                        @endphp
                        @endforeach
                        @php
                            $data = collect($group_by)->groupBy('bulan_tahun');
                            // start yoy
                            $tahun = collect($group_by)->groupBy('tahun');
                            // $arr_tahun = [null,null,null];
                            $arr_tahun = [0,0,0];
                            $arr_bulan = [1,2,3,4,5,6,7,8,9,10,11,12];
                            $x = 0;
                            foreach($tahun as $key => $value){
                                // array_push($arr_tahun, $key);
                                $arr_tahun[$x] = $key;
                                $x++;
                            }
                            $nominal_0 = [null,null,null,null,null,null,null,null,null,null,null,null];
                            $nominal_1 = [null,null,null,null,null,null,null,null,null,null,null,null];
                            $nominal_2 = [null,null,null,null,null,null,null,null,null,null,null,null];
                            // $nominal_0 = [0,0,0,0,0,0,0,0,0,0,0,0];
                            // $nominal_1 = [0,0,0,0,0,0,0,0,0,0,0,0];
                            // $nominal_2 = [0,0,0,0,0,0,0,0,0,0,0,0];
                            $jumlah_data_0 = [null,null,null,null,null,null,null,null,null,null,null,null];
                            $jumlah_data_1 = [null,null,null,null,null,null,null,null,null,null,null,null];
                            $jumlah_data_2 = [null,null,null,null,null,null,null,null,null,null,null,null];
                            // $jumlah_data_0 = [0,0,0,0,0,0,0,0,0,0,0,0];
                            // $jumlah_data_1 = [0,0,0,0,0,0,0,0,0,0,0,0];
                            // $jumlah_data_2 = [0,0,0,0,0,0,0,0,0,0,0,0];
                            // dd($arr_tahun);
                            // end yoy
                            $pembagi = count($data);
                            if(count($data) == 0){
                                $pembagi = 1;
                            }
                            $waktu = [];
                            $jumlah_data = [];
                            $nominal = [];
                            foreach($data as $key => $value){
                                // dd($value);
                                array_push($waktu, $key);
                                array_push($jumlah_data, $value->count());
                                $_nominal = 0;
                                foreach($value as $_value){
                                    $_nominal += $_value['total_biaya_gaji'];
                                }
                                array_push($nominal, $_nominal);
                                if($arr_tahun[0] > 0 && $arr_tahun[1]){
                                    // start yoy
                                    // pisahkan bulan dan tahun
                                    $_key = explode('-', $key);
                                    for($x = 1; $x <= count($arr_bulan); $x++){
                                        // jika bulan dan tahunnya sama
                                        if($_key[0] == $x && $arr_tahun[0] == $_key[1]){
                                            $nominal_0[$x-1] = $_nominal;
                                            $jumlah_data_0[$x-1] = $value->count();
                                        }
                                        // jika $arr_tahun > 1
                                        if(count($arr_tahun) > 1){
                                            if($_key[0] == $x && $arr_tahun[1] == $_key[1]){
                                                $nominal_1[$x-1] = $_nominal;
                                                $jumlah_data_1[$x-1] = $value->count();
                                            }
                                        }
                                        // jika $arr_tahun > 2
                                        if(count($arr_tahun) > 2){
                                            if($_key[0] == $x && $arr_tahun[2] == $_key[1]){
                                                $nominal_2[$x-1] = $_nominal;
                                                $jumlah_data_2[$x-1] = $value->count();
                                            }
                                        }
                                    }
                                    // end yoy
                                }else{
                                    $arr_tahun = [0,0,0];
                                }
                            }
                        @endphp
                        {{--  --}}
                        <div class="row mt-2">
                            <div class="col-xl-3 col-sm-3">
                                <div class="card bg-warning">
                                    <div class="card-body">
                                        <div class="media faq-widgets">
                                            <div class="media-body text-center">
                                                <h5>Jumlah Karyawan</h5>
                                                <h5>@if(!empty($payroll)) {{ round($payroll->count() / $pembagi) }} @else 0 @endif</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-3">
                                <div class="card bg-warning">
                                    <div class="card-body">
                                        <div class="media faq-widgets">
                                            <div class="media-body text-center">
                                                {{-- Biaya Gaji =  Total Biaya Gaji (-) Kompensasi KKWT --}}
                                                <h5>Biaya Gaji</h5>
                                                <h5>@if(!empty($payroll)) {{ number_format($biaya_gaji, 0) }} @else 0 @endif</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-3">
                                <div class="card bg-warning">
                                    <div class="card-body">
                                        <div class="media faq-widgets">
                                            <div class="media-body text-center">
                                                {{-- apm => Kompensasi KKWT = Kompensasi KKWT --}}
                                                {{-- osc => Kompensasi KKWT = Total Tagihan Kompensasi KKWT Outsource --}}
                                                <h5>Kompensasi KKWT</h5>
                                                <h5>@if(!empty($payroll)) {{ number_format($kompensasi_kkwt, 0) }} @else 0 @endif</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-3">
                                <div class="card bg-warning">
                                    <div class="card-body">
                                        <div class="media faq-widgets">
                                            <div class="media-body text-center">
                                                {{-- apm => Total Biaya Gaji = Total Upah --}}
                                                {{-- osc => Total Biaya Gaji = Total Biaya Outsource --}}
                                                <h5>Total Biaya Gaji</h5>
                                                <h5>@if(!empty($payroll)) {{ number_format($total_biaya_gaji, 0) }} @else 0 @endif</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--  --}}
                        <div class="row">
                            <div class="col-xl-6 col-sm-6">
                                <div class="table-responsive">
                                    <table class="display nowrap" id="_datatable1">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Perusahaan</th>
                                                <th>Total Biaya Gaji</th>
                                                <th>Karyawan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no=0;
                                                $data = App\Models\TrxPayrollLog::select('perusahaan')->whereIn('perusahaan', $arr_perusahaan)->orderBy('perusahaan', 'asc')->groupBy('perusahaan')->get();
                                                $grouped = collect($group_by)->groupBy('perusahaan');
                                                // dd($data, $grouped);
                                            @endphp
                                            @foreach ($data as $_data)
                                            @php
                                                $total_biaya_gaji = $grouped->get($_data->perusahaan);
                                                $jumlah = 0;
                                                if(!empty($total_biaya_gaji)){
                                                    $total_biaya_gaji = $total_biaya_gaji->sum('total_biaya_gaji');
                                                    $jumlah = $grouped->get($_data->perusahaan)->count();
                                                }
                                            @endphp
                                            @if($jumlah>0)
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $_data->perusahaan }}</td>
                                                <td class="text-end">{{ number_format($total_biaya_gaji, 0) }}</td>
                                                <td class="text-end">{{ number_format(round($jumlah / $pembagi), 0) }}</td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{--  --}}
                            <div class="col-xl-6 col-sm-6">
                                <div class="table-responsive">
                                    <table class="display nowrap" id="_datatable2">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Direktorat</th>
                                                <th>Total Biaya Gaji</th>
                                                <th>Karyawan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no=0;
                                                $data = App\Models\TrxPayrollLog::select('divisi')->whereIn('divisi', $arr_divisi)->orderBy('divisi', 'asc')->groupBy('divisi')->get();
                                                $grouped = collect($group_by)->groupBy('divisi');
                                            @endphp
                                            @foreach ($data as $_data)
                                            @php
                                                $total_biaya_gaji = $grouped->get($_data->divisi);
                                                $jumlah = 0;
                                                if(!empty($total_biaya_gaji)){
                                                    $total_biaya_gaji = $total_biaya_gaji->sum('total_biaya_gaji');
                                                    $jumlah = $grouped->get($_data->divisi)->count();
                                                }
                                            @endphp
                                            @if($jumlah>0)
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $_data->divisi }}</td>
                                                <td class="text-end">{{ number_format($total_biaya_gaji, 0) }}</td>
                                                <td class="text-end">{{ number_format(round($jumlah / $pembagi), 0) }}</td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{--  --}}
                            <hr class="mt-2 mb-2" />
                            {{--  --}}
                            <div class="col-xl-6 col-sm-6">
                                <div class="table-responsive">
                                    <table class="display nowrap" id="_datatable3">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Divisi</th>
                                                <th>Total Biaya Gaji</th>
                                                <th>Karyawan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no=0;
                                                $data = App\Models\TrxPayrollLog::select('departemen')->whereIn('departemen', $arr_departemen)->orderBy('departemen', 'asc')->groupBy('departemen')->get();
                                                $grouped = collect($group_by)->groupBy('departemen');
                                            @endphp
                                            @foreach ($data as $_data)
                                            @php
                                                $total_biaya_gaji = $grouped->get($_data->departemen);
                                                $jumlah = 0;
                                                if(!empty($total_biaya_gaji)){
                                                    $total_biaya_gaji = $total_biaya_gaji->sum('total_biaya_gaji');
                                                    $jumlah = $grouped->get($_data->departemen)->count();
                                                }
                                            @endphp
                                            @if($jumlah>0)
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $_data->departemen }}</td>
                                                <td class="text-end">{{ number_format($total_biaya_gaji, 0) }}</td>
                                                <td class="text-end">{{ number_format(round($jumlah / $pembagi), 0) }}</td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{--  --}}
                            <div class="col-xl-6 col-sm-6">
                                <div class="table-responsive">
                                    <table class="display nowrap" id="_datatable4">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Departemen</th>
                                                <th>Total Biaya Gaji</th>
                                                <th>Karyawan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no=0;
                                                $data = App\Models\TrxPayrollLog::select('subdepartemen')->whereIn('subdepartemen', $arr_subdepartemen)->orderBy('subdepartemen', 'asc')->groupBy('subdepartemen')->get();
                                                $grouped = collect($group_by)->groupBy('subdepartemen');
                                            @endphp
                                            @foreach ($data as $_data)
                                            @php
                                                $total_biaya_gaji = $grouped->get($_data->subdepartemen);
                                                $jumlah = 0;
                                                if(!empty($total_biaya_gaji)){
                                                    $total_biaya_gaji = $total_biaya_gaji->sum('total_biaya_gaji');
                                                    $jumlah = $grouped->get($_data->subdepartemen)->count();
                                                }
                                            @endphp
                                            @if($jumlah>0)
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $_data->subdepartemen }}</td>
                                                <td class="text-end">{{ number_format($total_biaya_gaji, 0) }}</td>
                                                <td class="text-end">{{ number_format(round($jumlah / $pembagi), 0) }}</td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{--  --}}
                            <hr class="mt-2 mb-2" />
                            {{--  --}}
                            <div class="col-xl-6 col-sm-6">
                                <div class="table-responsive">
                                    <table class="display nowrap" id="_datatable5">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Status Kerja</th>
                                                <th>Total Biaya Gaji</th>
                                                <th>Karyawan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no=0;
                                                $data = App\Models\TrxPayrollLog::select('status_karyawan')->whereIn('status_karyawan', $arr_status_karyawan)->orderBy('status_karyawan', 'asc')->groupBy('status_karyawan')->get();
                                                $grouped = collect($group_by)->groupBy('status_karyawan');
                                                // dd($status_karyawan);
                                            @endphp
                                            @foreach ($data as $_data)
                                            @php
                                                $total_biaya_gaji = $grouped->get($_data->status_karyawan);
                                                $jumlah = 0;
                                                if(!empty($total_biaya_gaji)){
                                                    $total_biaya_gaji = $total_biaya_gaji->sum('total_biaya_gaji');
                                                    $jumlah = $grouped->get($_data->status_karyawan)->count();
                                                }
                                            @endphp
                                            @if($jumlah>0)
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $_data->status_karyawan }}</td>
                                                <td class="text-end">{{ number_format($total_biaya_gaji, 0) }}</td>
                                                <td class="text-end">{{ number_format(round($jumlah / $pembagi), 0) }}</td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-xl-6 col-sm-6">&nbsp;</div>
                            {{--  --}}
                            <hr class="mt-2 mb-2" />
                            {{--  --}}
                            <div class="col-xl-6 col-sm-6">
                                <div class="card">
                                    <h5 class="text-center mt-2">Total Biaya Gaji</h5>
                                    <div id="chart_total_biaya_gaji"></div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-sm-6">
                                <div class="card">
                                    <h5 class="text-center mt-2">Jumlah Karyawan</h5>
                                    <div id="chart_jumlah_karyawan"></div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-sm-6">
                                <div class="card">
                                    <h5 class="text-center mt-2">Total Biaya Gaji (Year on Year)</h5>
                                    <div id="chart_yoy_total_biaya_gaji"></div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-sm-6">
                                <div class="card">
                                    <h5 class="text-center mt-2">Jumlah Karyawan (Year on Year)</h5>
                                    <div id="chart_yoy_jumlah_karyawan"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/jszip.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.print.min.js') }}"></script>
<script type="text/javascript">
// 
$('#_datatable1').DataTable({
	dom: 'lfrtip',
    // dom: 'rtip',
});
// 
$('#_datatable2').DataTable({
	dom: 'lfrtip',
});
// 
$('#_datatable3').DataTable({
	dom: 'lfrtip',
});
// 
$('#_datatable4').DataTable({
	dom: 'lfrtip',
});
// 
$('#_datatable5').DataTable({
	dom: 'lfrtip',
});
// 
function get_perusahaan() {
    getDivisi();
}
// 
function get_divisi() {
    getDepartemen();
}
// 
function get_departemen() {
    getSubDepartemen();
}
// 
function toggle_tahun(source) {
    checkboxes = document.getElementsByName('tahun[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_bulan(source) {
    checkboxes = document.getElementsByName('bulan[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function toggle_perusahaan(source) {
    checkboxes = document.getElementsByName('perusahaan[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
    getDivisi();
}
// 
function toggle_divisi(source) {
    checkboxes = document.getElementsByName('divisi[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
    getDepartemen();
}
// 
function toggle_departemen(source) {
    checkboxes = document.getElementsByName('departemen[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
    getSubDepartemen();
}
// 
function toggle_subdepartemen(source) {
    checkboxes = document.getElementsByName('subdepartemen[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function toggle_status_karyawan(source) {
    checkboxes = document.getElementsByName('status_karyawan[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function getDivisi() {
    var _perusahaan = [];
    $('input[id="perusahaan[]"]:checked').each(function() {
        _perusahaan.push($(this).val());
    });
    $('#div_divisi').empty();
    $('#div_departemen').empty();
    $('#div_subdepartemen').empty();
    // AJAX request 
    $.ajax({
        url: "{{ route('payroll.get_divisi',0) }}" + "?perusahaan=" + _perusahaan,
        type: 'get',
        dataType: 'json',
        success: function(response) {
            var len = 0;
            if (response != null) {
                len = response.length;
            }
            if (len > 0) {
                for (var i = 0; i < len; i++) {
                    var id = response[i]['id'];
                    var nama = response[i]['nama'];
                    var option = "<input class='checkbox_animated' id='divisi[]' name='divisi[]' type='checkbox' onClick='get_divisi()' value='" + id + "'> " + nama + "<br/>";
                    $("#div_divisi").append(option);
                }
            }
        }
    });
}
// 
function getDepartemen() {
    var _perusahaan = [];
    $('input[id="perusahaan[]"]:checked').each(function() {
        _perusahaan.push($(this).val());
    });
    var _divisi = [];
    $('input[id="divisi[]"]:checked').each(function() {
        _divisi.push($(this).val());
    });
    $('#div_departemen').empty();
    $('#div_subdepartemen').empty();
    $.ajax({
        url: "{{ route('payroll.get_departemen',0) }}" + "?perusahaan=" + _perusahaan + "&divisi=" + _divisi,
        type: 'get',
        dataType: 'json',
        success: function(response) {
            var len = 0;
            if (response != null) {
                len = response.length;
            }
            if (len > 0) {
                for (var i = 0; i < len; i++) {
                    var id = response[i]['id'];
                    var nama = response[i]['nama'];
                    var option = "<input class='checkbox_animated' id='departemen[]' name='departemen[]' type='checkbox' onClick='get_departemen()' value='" + id + "'> " + nama + "<br/>";
                    $("#div_departemen").append(option);
                }
            }
        }
    });
}
// 
function getSubDepartemen() {
    var _perusahaan = [];
    $('input[id="perusahaan[]"]:checked').each(function() {
        _perusahaan.push($(this).val());
    });
    var _divisi = [];
    $('input[id="divisi[]"]:checked').each(function() {
        _divisi.push($(this).val());
    });
    var _departemen = [];
    $('input[id="departemen[]"]:checked').each(function() {
        _departemen.push($(this).val());
    });
    $('#div_subdepartemen').empty();
    $.ajax({
        url: "{{ route('payroll.get_subdepartemen',0) }}" + "?perusahaan=" + _perusahaan + "&divisi=" + _divisi + "&departemen=" + _departemen,
        type: 'get',
        dataType: 'json',
        success: function(response) {
            var len = 0;
            if (response != null) {
                len = response.length;
            }
            if (len > 0) {
                for (var i = 0; i < len; i++) {
                    var id = response[i]['id'];
                    var nama = response[i]['nama'];
                    var option = "<input class='checkbox_animated' id='subdepartemen[]' name='subdepartemen[]' type='checkbox' value='" + id + "'> " + nama + "<br/>";
                    $("#div_subdepartemen").append(option);
                }
            }
        }
    });
}
// 
$(document).ready(function() {
    // Total Biaya Gaji
    var options = {
        series: [{
            name: 'Biaya Gaji',
            data: {{ \Illuminate\Support\Js::from($nominal) }}
        }],
        chart: {
            type: 'line',
            height: 350,
        },
        stroke: {
          curve: 'smooth'
        },
        // plotOptions: {
        //     bar: {
        //         borderRadius: 4,
        //         columnWidth: '50%',
        //         borderRadiusApplication: 'end',
        //         horizontal: false,
        //         dataLabels: {
        //             position: 'top', // top, center, bottom
        //         },
        //     }
        // },
        colors: ['#f57b20'],
        // dataLabels: {
        //     enabled: true,
        //     offsetY: -20,
        //     style: {
        //         colors: ["#f57b20"]
        //     },
        //     formatter: function(val) {
        //         return val;
        //     },
        // },
        xaxis: {
            categories: {{ \Illuminate\Support\Js::from($waktu) }},
            // labels: {
            //     show: true,          // Ensure labels are shown
            //     rotate: -45,         // Rotate labels if they overlap
                // style: {
                //     fontSize: '14px',
                //     colors: ['#333']   // Optional: color of the labels
                // }
            // },
            // title: {
            //     text: "Bulan-Tahun"
            // },
            // title: {
            //     text: 'Bulan-Tahun',
            //     offsetY: +85,
            //     style: {
            //         fontSize: '14px',
            //         fontWeight: 'bold',
            //     },
            // }
        },
        yaxis: {
            title: {
                text: 'Biaya Gaji',
            },
            labels: {
                formatter: function (value) {
                    return value.toLocaleString();
                }
            },
            // min: 0,
            // max: 2000000000
        },
    };
    new ApexCharts(document.querySelector("#chart_total_biaya_gaji"), options).render();
    // Jumlah Karyawan
    var options = {
        series: [{
            name: 'Jumlah',
            data: {{ \Illuminate\Support\Js::from($jumlah_data) }}
        }],
        chart: {
            type: 'line',
            height: 350,
        },
        stroke: {
          curve: 'smooth'
        },
        colors: ['#f57b20'],
        xaxis: {
            categories: {{ \Illuminate\Support\Js::from($waktu) }},
        },
        yaxis: {
            title: {
                text: 'Jumlah',
            },
            labels: {
                formatter: function (value) {
                    return value.toLocaleString();
                }
            },
            // min: 0,
            // max: 2000000000
        },
    };
    new ApexCharts(document.querySelector("#chart_jumlah_karyawan"), options).render();
    // 
    @if($arr_tahun[0] > 0 && $arr_tahun[1] > 0 && $arr_tahun[2] > 0)
    // YoY Total Biaya Gaji
    var options = {
        series: [{
            name: {{ \Illuminate\Support\Js::from($arr_tahun[0]) }},
            data: {{ \Illuminate\Support\Js::from($nominal_0) }}
        },{
            name: {{ \Illuminate\Support\Js::from($arr_tahun[1]) }},
            data: {{ \Illuminate\Support\Js::from($nominal_1) }}
        },{
            name: {{ \Illuminate\Support\Js::from($arr_tahun[2]) }},
            data: {{ \Illuminate\Support\Js::from($nominal_2) }}
        }],
        chart: {
            type: 'line',
            height: 350,
        },
        stroke: {
          curve: 'smooth'
        },
        colors: ['#f57b20', '#4e71ff', '#6de1d2'],
        xaxis: {
            categories: {{ \Illuminate\Support\Js::from($arr_bulan) }},
        },
        yaxis: {
            title: {
                text: 'Biaya Gaji',
            },
            labels: {
                formatter: function (value) {
                    return value.toLocaleString();
                }
            },
        },
    };
    new ApexCharts(document.querySelector("#chart_yoy_total_biaya_gaji"), options).render();
    // YoY Jumlah Karyawan
    var options = {
        series: [{
            name: {{ \Illuminate\Support\Js::from($arr_tahun[0]) }},
            data: {{ \Illuminate\Support\Js::from($jumlah_data_0) }}
        },{
            name: {{ \Illuminate\Support\Js::from($arr_tahun[1]) }},
            data: {{ \Illuminate\Support\Js::from($jumlah_data_1) }}
        },{
            name: {{ \Illuminate\Support\Js::from($arr_tahun[2]) }},
            data: {{ \Illuminate\Support\Js::from($jumlah_data_2) }}
        }],
        chart: {
            type: 'line',
            height: 350,
        },
        stroke: {
          curve: 'smooth'
        },
        colors: ['#f57b20', '#4e71ff', '#6de1d2'],
        xaxis: {
            categories: {{ \Illuminate\Support\Js::from($arr_bulan) }},
        },
        yaxis: {
            title: {
                text: 'Jumlah',
            },
            labels: {
                formatter: function (value) {
                    return value.toLocaleString();
                }
            },
        },
    };
    new ApexCharts(document.querySelector("#chart_yoy_jumlah_karyawan"), options).render();
    @else
    // YoY Total Biaya Gaji
    var options = {
        series: [{
            name: {{ \Illuminate\Support\Js::from($arr_tahun[0]) }},
            data: {{ \Illuminate\Support\Js::from($nominal_0) }}
        },{
            name: {{ \Illuminate\Support\Js::from($arr_tahun[1]) }},
            data: {{ \Illuminate\Support\Js::from($nominal_1) }}
        }],
        chart: {
            type: 'line',
            height: 350,
        },
        stroke: {
          curve: 'smooth'
        },
        colors: ['#f57b20', '#4e71ff'],
        xaxis: {
            categories: {{ \Illuminate\Support\Js::from($arr_bulan) }},
        },
        yaxis: {
            title: {
                text: 'Biaya Gaji',
            },
            labels: {
                formatter: function (value) {
                    return value.toLocaleString();
                }
            },
        },
    };
    new ApexCharts(document.querySelector("#chart_yoy_total_biaya_gaji"), options).render();
    // YoY Jumlah Karyawan
    var options = {
        series: [{
            name: {{ \Illuminate\Support\Js::from($arr_tahun[0]) }},
            data: {{ \Illuminate\Support\Js::from($jumlah_data_0) }}
        },{
            name: {{ \Illuminate\Support\Js::from($arr_tahun[1]) }},
            data: {{ \Illuminate\Support\Js::from($jumlah_data_1) }}
        }],
        chart: {
            type: 'line',
            height: 350,
        },
        stroke: {
          curve: 'smooth'
        },
        colors: ['#f57b20', '#4e71ff'],
        xaxis: {
            categories: {{ \Illuminate\Support\Js::from($arr_bulan) }},
        },
        yaxis: {
            title: {
                text: 'Jumlah',
            },
            labels: {
                formatter: function (value) {
                    return value.toLocaleString();
                }
            },
        },
    };
    new ApexCharts(document.querySelector("#chart_yoy_jumlah_karyawan"), options).render();
    @endif
});
</script>
<script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/chart-custom.js') }}"></script>
@endsection
