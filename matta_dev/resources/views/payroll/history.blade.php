@extends('layouts.simple.master')

@section('title', 'Payroll')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
<style>
    button.dt-button {
    float:none;
    text-align:center;
    color: black;
    background: white;
    }
</style>
@endsection

@section('breadcrumb-title')
<h3>Payroll History</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Payroll</li>
<li class="breadcrumb-item active">Payroll History</li>
@endsection
@php
$bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
@endphp
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('msg'))
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if (count($errors) > 0)
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h6>Upload File is ERROR</h6>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                        </div>
                    </div>
                    <div class="collapse mb-2" id="collapse_filter">
                        <form action="{{ route('payroll.history', 0) }}" method="get" enctype="multipart/form-data" class="needs-validation" novalidate="">
                            <div class="row">
                                <div class="col-sm-2">
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
                                <div class="col-sm-4">
                                    <div>
                                        <label class="h6 mt-2" for="nik">Karyawan</label>
                                        <select class="form-select js-example-basic-single col-sm-12" id="nik[]" name="nik[]" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            <option value="0" @if(in_array('0', $_arr_nik)) selected @endif>Semua Karyawan</option>
                                            @foreach ($karyawan as $data)
                                            <option value="{{ $data->nik }}" @if(in_array($data->nik, $_arr_nik)) selected @endif>{{ $data->nama }} - {{ $data->nik }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback txt-danger">Field is required.</div>
                                    </div>
                                    {{-- <label class="h6 mt-2">Karyawan</label>&nbsp;<input type="checkbox" onClick="toggle_nik(this)" />&nbsp;Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                        <label class="d-block" for="nik">
                                            @foreach ($karyawan as $data)
                                            <input class="checkbox_animated" id="nik[]" name="nik[]" type="checkbox" value="{{ $data->nik }}" @if(in_array($data->nik, $_arr_nik)) checked @endif>{{ $data->nama }} - {{ $data->nik }}<br/>
                                            @endforeach
                                        </label>
                                    </div> --}}
                                </div>
                                <div class="col-sm-2">
                                    <label class="h6 mt-2">Status</label>&nbsp;<input type="checkbox" onClick="toggle_status_karyawan(this)" />&nbsp;Check All
                                    <label class="d-block" for="status_karyawan">
                                        @foreach ($status_karyawan as $data)
                                        <input class="checkbox_animated" id="status_karyawan[]" name="status_karyawan[]" type="checkbox" value="{{ $data->status_karyawan }}" @if(in_array($data->status_karyawan, $_arr_status_karyawan)) checked @endif> {{ $data->status_karyawan }}<br/>
                                        @endforeach
                                    </label>
                                </div>
                                <div class="col-sm-4">
                                    <label class="h6 mt-2">Level</label>&nbsp;<input type="checkbox" onClick="toggle_level(this)" />&nbsp;Check All
                                    <label class="d-block" for="level">
                                        @foreach ($level as $data)
                                        <input class="checkbox_animated" id="level[]" name="level[]" type="checkbox" value="{{ $data->level }}" @if(in_array($data->level, $_arr_level)) checked @endif> {{ $data->level }}<br/>
                                        @endforeach
                                    </label>
                                    {{-- <label class="h6 mt-2">Jabatan</label>&nbsp;<input type="checkbox" onClick="toggle_jabatan(this)" />&nbsp;Check All
                                    <label class="d-block" for="jabatan">
                                        @foreach ($jabatan as $data)
                                        <input class="checkbox_animated" id="jabatan[]" name="jabatan[]" type="checkbox" value="{{ $data->jabatan }}" @if(in_array($data->jabatan, $_arr_jabatan)) checked @endif> {{ $data->jabatan }}<br/>
                                        @endforeach
                                    </label> --}}
                                </div>
                                {{-- <div class="col-sm-4">
                                    <label class="h6">Findim</label>
                                    <br/>
                                    <label class="form-label">Divisi</label>&nbsp;<input type="checkbox" onClick="toggle_findim_divisi(this)" />&nbsp;Check All
                                    <label class="d-block" for="findim_divisi">
                                        @foreach ($findim_divisi as $data)
                                        <input class="checkbox_animated" id="findim_divisi[]" name="findim_divisi[]" type="checkbox" value="{{ $data->divisi_id }}/{{ $data->divisi_desc }}" @if(in_array($data->divisi_id.'/'.$data->divisi_desc, $_arr_findim_divisi)) checked @endif> {{ $data->divisi_id }}/{{ $data->divisi_desc }}<br/>
                                        @endforeach
                                    </label>
                                    <label class="form-label">Departemen</label>&nbsp;<input type="checkbox" onClick="toggle_findim_departemen(this)" />&nbsp;Check All
                                    <label class="d-block" for="findim_departemen">
                                        @foreach ($findim_departemen as $data)
                                        <input class="checkbox_animated" id="findim_departemen[]" name="findim_departemen[]" type="checkbox" value="{{ $data->departemen_id }}/{{ $data->departemen_desc }}" @if(in_array($data->departemen_id.'/'.$data->departemen_desc, $_arr_findim_departemen)) checked @endif> {{ $data->departemen_id }}/{{ $data->departemen_desc }}<br/>
                                        @endforeach
                                    </label>
                                    <label class="form-label">Sub Departemen</label>&nbsp;<input type="checkbox" onClick="toggle_findim_subdepartemen(this)" />&nbsp;Check All
                                    <label class="d-block" for="findim_subdepartemen">
                                        @foreach ($findim_subdepartemen as $data)
                                        <input class="checkbox_animated" id="findim_subdepartemen[]" name="findim_subdepartemen[]" type="checkbox" value="{{ $data->subdepartemen_id }}/{{ $data->subdepartemen_desc }}" @if(in_array($data->subdepartemen_id.'/'.$data->subdepartemen_desc, $_arr_findim_subdepartemen)) checked @endif> {{ $data->subdepartemen_id }}/{{ $data->subdepartemen_desc }}<br/>
                                        @endforeach
                                    </label>
                                    <label class="form-label">Project</label>&nbsp;<input type="checkbox" onClick="toggle_findim_project(this)" />&nbsp;Check All
                                    <label class="d-block" for="findim_project">
                                        @foreach ($findim_project as $data)
                                        <input class="checkbox_animated" id="findim_project[]" name="findim_project[]" type="checkbox" value="{{ $data->project_id }}/{{ $data->project_desc }}" @if(in_array($data->project_id.'/'.$data->project_desc, $_arr_findim_project)) checked @endif> {{ $data->project_id }}/{{ $data->project_desc }}<br/>
                                        @endforeach
                                    </label>
                                </div>
                                <div class="col-sm-4">
                                    <label class="h6">Direktorat</label>&nbsp;<input type="checkbox" onClick="toggle_divisi(this)" />&nbsp;Check All
                                    <label class="d-block" for="divisi">
                                        @foreach ($divisi as $data)
                                        <input class="checkbox_animated" id="divisi[]" name="divisi[]" type="checkbox" value="{{ $data->nama }}" @if(in_array($data->nama, $_arr_divisi)) checked @endif> {{ $data->nama }}<br/>
                                        @endforeach
                                    </label>
                                    <label class="h6">Divisi</label>&nbsp;<input type="checkbox" onClick="toggle_departemen(this)" />&nbsp;Check All
                                    <label class="d-block" for="departemen">
                                        @foreach ($departemen as $data)
                                        <input class="checkbox_animated" id="departemen[]" name="departemen[]" type="checkbox" value="{{ $data->nama }}" @if(in_array($data->nama, $_arr_departemen)) checked @endif> {{ $data->nama }}<br/>
                                        @endforeach
                                    </label>
                                    <label class="h6">Departemen</label>&nbsp;<input type="checkbox" onClick="toggle_subdepartemen(this)" />&nbsp;Check All
                                    <label class="d-block" for="subdepartemen">
                                        @foreach ($subdepartemen as $data)
                                        <input class="checkbox_animated" id="subdepartemen[]" name="subdepartemen[]" type="checkbox" value="{{ $data->nama }}" @if(in_array($data->nama, $_arr_subdepartemen)) checked @endif> {{ $data->nama }}<br/>
                                        @endforeach
                                    </label>
                                </div> --}}
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="{{ route('payroll.history', 0) }}" class="btn btn-info" type="button"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    @if(!Gate::allows('hc_nkp'))
                    <h4>Alita</h4>
                    @can('payroll')
                    <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
                        {{-- @if(!Session::has('tab')) active @endif --}}
                        <li class="nav-item"><a class="nav-link active" id="pills-semua-tab" data-bs-toggle="pill" href="#pills-semua" role="tab" aria-controls="pills-semua" aria-selected="true">Upah</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-potongan-tab" data-bs-toggle="pill" href="#pills-potongan" role="tab" aria-controls="pills-potongan" aria-selected="true">Potongan</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-bpjstk-tab" data-bs-toggle="pill" href="#pills-bpjstk" role="tab" aria-controls="pills-bpjstk" aria-selected="true">BPJS TK</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-bpjskes-tab" data-bs-toggle="pill" href="#pills-bpjskes" role="tab" aria-controls="pills-bpjskes" aria-selected="true">BPJS Kesehatan</a></li>
                        {{-- @if(Session::has('tab') && Session::has('tab')=='kerja') active @endif --}}
                        <li class="nav-item"><a class="nav-link" id="pills-kerja-tab" data-bs-toggle="pill" href="#pills-kerja" role="tab" aria-controls="pills-kerja" aria-selected="true">PPh21</a></li>
                    </ul>
                    @endcan
                    @can('payroll_fin')
                    <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="pills-kerja-tab" data-bs-toggle="pill" href="#pills-kerja" role="tab" aria-controls="pills-kerja" aria-selected="true">PPh21</a></li>
                    </ul>
                    @endcan
                    <div class="tab-content" id="pills-warningtabContent">
                        {{-- Upah --}}
                        {{-- @if(!Session::has('tab')) show active @endif --}}
                        <div class="tab-pane fade @can('payroll') show active @endcan" id="pills-semua" role="tabpanel" aria-labelledby="pills-semua-tab">
                            <p class="mb-0 m-t-10">
                                <div class="table-responsive">
                                    <table class="display nowrap" id="_datatable1">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tahun</th>
                                                <th>Bulan</th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>Gaji Pokok</th>
                                                @php
                                                    $where = ['tipe'=>'add'];
                                                    $arr_id = [1,2,3,4,5];
                                                    $add = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                                @endphp
                                                @foreach ($add as $data)
                                                <th>{{ $data->nama }}</th>
                                                @endforeach
                                                <th>Total Tunjangan</th>
                                                <th>Upah</th>
                                                <th>Perubahan Upah</th>
                                                @php
                                                    $where = ['tipe'=>'add'];
                                                    $arr_id = [6,7,8,9,21];
                                                    $add = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                                @endphp
                                                @foreach ($add as $data)
                                                <th>{{ $data->nama }}</th>
                                                @endforeach
                                                <th>Total Upah</th>
                                                <th>Dibayarkan Tanggal</th>
                                                <th>Slip Gaji</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no=0; @endphp
                                            @foreach ($payroll as $data)
                                            @php
                                                $where = ['nik'=>$data->nik, 'tipe'=>'add', 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $arr_id = [1,2,3,4,5];
                                                $add = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $total_tunjangan = $add->sum('angka');
                                                $arr_id = [6,7,8,9,21];
                                                $_add = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $_total_tunjangan = $_add->sum('angka');
                                                // membandingkan data 1 bulan sebelumnya untuk mengecek apakah ada perubahan gapok + total_tunjangan
                                                $submonth = Carbon\Carbon::createFromFormat('Y-m-d', "{$data->tahun}-{$data->bulan}-01")->subMonth();
                                                $where = ['nik'=>$data->nik, 'bulan'=>$submonth->month, 'tahun'=>$submonth->year];
                                                $gapok_submonth = 0;
                                                $gapok_sub = App\Models\TrxPayrollLog::where($where)->first();
                                                if(!empty($gapok_sub)){
                                                    $gapok_submonth = $gapok_sub->gapok;
                                                }
                                                $where = ['nik'=>$data->nik, 'tipe'=>'add', 'bulan'=>$submonth->month, 'tahun'=>$submonth->year];
                                                $arr_id = [1,2,3,4,5];
                                                $add_submonth = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $total_tunjangan_submonth = 0;
                                                if(!empty($add_submonth)){
                                                    $total_tunjangan_submonth = $add_submonth->sum('angka');
                                                }
                                                $selisih = ($data->gapok + $total_tunjangan) - ($gapok_submonth + $total_tunjangan_submonth);
                                                // 
                                            @endphp
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $data->tahun }}</td>
                                                <td>{{ $bulan[$data->bulan - 1] }}</td>
                                                <td>{{ $data->_nik->nama }}</td>
                                                <td>{{ $data->nik }}</td>
                                                <td class="text-end">{{ number_format($data->gapok, 0) }}</td>
                                                {{--  --}}
                                                @foreach ($add as $_data)
                                                <td class="text-end">{{ number_format($_data->angka, 0) }}</td>
                                                @endforeach
                                                {{-- total_tunjangan --}}
                                                <td class="text-end">{{ number_format($total_tunjangan, 0) }}</td>
                                                {{-- upah --}}
                                                <td class="text-end">{{ number_format($data->gapok + $total_tunjangan, 0) }}</td>
                                                {{-- perubahan upah --}}
                                                <td class="text-end">
                                                    @if($selisih > 0)
                                                        <span class="font-success f-12 f-w-500"><i class="icon-arrow-up"></i></span> <span class="badge badge-success" style="font-size:12px;">{{ number_format($selisih, 0) }}</span>
                                                    @elseif($selisih < 0)
                                                        <span class="font-danger f-12 f-w-500"><i class="icon-arrow-down"></i></span> <span class="badge badge-danger" style="font-size:12px;">{{ number_format($selisih, 0) }}</span>
                                                    @else
                                                        <span class="badge badge-primary" style="font-size:12px;">{{ number_format($selisih, 0) }}</span>
                                                    @endif
                                                </td>
                                                {{--  --}}
                                                @foreach ($_add as $_data)
                                                <td class="text-end">{{ number_format($_data->angka, 0) }}</td>
                                                @endforeach
                                                {{-- total upah --}}
                                                <td class="text-end">{{ number_format($data->gapok + $total_tunjangan + $_total_tunjangan, 0) }}</td>
                                                <td>@if(!empty($data->paid_at)){{ Carbon\Carbon::parse($data->paid_at)->format("d-m-Y") }}@endif</td>
                                                <td>@if((!empty($data->paid_at)) && (Carbon\Carbon::parse($data->paid_at)->isPast() || Carbon\Carbon::parse($data->paid_at)->isToday()))<a href="{{ route('payroll.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => 'pdf_h']) }}" data-toggle="tooltip" title="Slip Gaji" target="_blank"><i class="fa fa-file-pdf-o"></i></a>@endif</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>Rekapitulasi</th>
                                                @for ($x = 5; $x <= 12; $x++)
                                                <th id="semua_{{$x}}" class="text-end">0</th>
                                                @endfor
                                                <th>&nbsp;</th>
                                                @for ($x = 14; $x <= 19; $x++)
                                                <th id="semua_{{$x}}" class="text-end">0</th>
                                                @endfor
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </p>
                        </div>
                        {{-- potongan --}}
                        <div class="tab-pane fade" id="pills-potongan" role="tabpanel" aria-labelledby="pills-potongan-tab">
                            <p class="mb-0 m-t-10">
                                <div class="table-responsive">
                                    <table class="display nowrap" id="_datatable2">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tahun</th>
                                                <th>Bulan</th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>Total Upah</th>
                                                @php
                                                    $where = ['tipe'=>'sub'];
                                                    $arr_id = [13,14,15,16,17,18,19,20];
                                                    $sub = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                                    $where = ['tipe'=>'add'];
                                                    $arr_id = [10,11];
                                                    $add = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                                @endphp
                                                @foreach ($sub as $data)
                                                <th>{{ $data->nama }}</th>
                                                @endforeach
                                                <th>Jumlah Potongan</th>
                                                @foreach ($add as $data)
                                                <th>{{ $data->nama }}</th>
                                                @endforeach
                                                <th>THP Ditransfer</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no=0; @endphp
                                            @foreach ($payroll as $data)
                                            @php
                                                // 
                                                $where = ['nik'=>$data->nik, 'tipe'=>'add', 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $arr_id = [1,2,3,4,5];
                                                $add = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $total_tunjangan = $add->sum('angka');
                                                $arr_id = [6,7,8,9,21];
                                                $_add = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $_total_tunjangan = $_add->sum('angka');
                                                // 
                                                $where = ['nik'=>$data->nik, 'tipe'=>'sub', 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $arr_id = [13,14,15,16,17,18,19,20];
                                                $sub = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $total_potongan = $sub->sum('angka');
                                                $where = ['nik'=>$data->nik, 'tipe'=>'add', 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $arr_id = [10,11];
                                                $add = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $total_add = $add->sum('angka');
                                            @endphp
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $data->tahun }}</td>
                                                <td>{{ $bulan[$data->bulan - 1] }}</td>
                                                <td>{{ $data->_nik->nama }}</td>
                                                <td>{{ $data->nik }}</td>
                                                {{-- total upah --}}
                                                <td class="text-end">{{ number_format($data->gapok + $total_tunjangan + $_total_tunjangan, 0) }}</td>
                                                @foreach ($sub as $_data)
                                                <td class="text-end">{{ number_format($_data->angka, 0) }}</td>
                                                @endforeach
                                                {{-- total_potongan --}}
                                                <td class="text-end">{{ number_format($total_potongan, 0) }}</td>
                                                @foreach ($add as $_data)
                                                <td class="text-end">{{ number_format($_data->angka, 0) }}</td>
                                                @endforeach
                                                {{-- thp ditransfer --}}
                                                <td class="text-end">{{ number_format($data->gapok + $total_tunjangan + $_total_tunjangan - $total_potongan + $total_add, 0) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>Rekapitulasi</th>
                                                @for ($x = 5; $x <= 17; $x++)
                                                <th id="potongan_{{$x}}" class="text-end">0</th>
                                                @endfor
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </p>
                        </div>
                        {{-- bpjstk --}}
                        <div class="tab-pane fade" id="pills-bpjstk" role="tabpanel" aria-labelledby="pills-bpjstk-tab">
                            <p class="mb-0 m-t-10">
                                <div class="row g-3 mb-2">
                                    <form action="{{ route('payroll.upload_payroll') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to upload this data?');">
                                    @csrf
                                    <input type="hidden" id="jenis" name="jenis" value="bpjstk_past">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <input name="template" id="template" class="form-control" type="file" required="">
                                            <label class="form-label">Maksimal 2 MB dengan format xls/xlsx</label>
                                            <br/><a href="{{ asset('assets/template/template_upload_bpjstk_v1.xlsx') }}" target="_blank"><i class="icon-file"></i>&nbsp;Download template</a>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                                <div class="table-responsive">
                                    <table class="display nowrap" id="_datatable3">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tahun</th>
                                                <th>Bulan</th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>Upah</th>
                                                <th>Upah Dilaporkan BPJS TK</th>
                                                <th>Upah JP Maksimum</th>
                                                @php
                                                    $arr_id = [1,2,3,4,5,6];
                                                    $arr = App\Models\MtrPayrollBpjstk::whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                                @endphp
                                                @foreach ($arr as $_arr)
                                                <th>{{ $_arr->nama }}<br/>{{ $_arr->persen }}%</th>
                                                @endforeach
                                                <th>Total Iuran</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no=0; @endphp
                                            @foreach ($payroll as $data)
                                            @php
                                                $where = ['nik'=>$data->nik, 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $bpjstk = App\Models\TrxPayrollBpjstkLog::where($where)->orderBy('id_bpjstk', 'asc')->get();
                                                $potongan = 0;
                                            @endphp
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $data->tahun }}</td>
                                                <td>{{ $bulan[$data->bulan - 1] }}</td>
                                                <td>{{ $data->_nik->nama }}</td>
                                                <td>{{ $data->nik }}</td>
                                                <td class="text-end">{{ number_format($bpjstk[0]->upah, 0) }}</td>
                                                <td class="text-end">{{ number_format($bpjstk[0]->thp_dilaporkan, 0) }}</td>
                                                <td class="text-end">@if($bpjstk[0]->thp_dilaporkan > $bpjstk[0]->maksimal){{ number_format($bpjstk[0]->maksimal, 0) }}@else{{ number_format($bpjstk[0]->thp_dilaporkan, 0) }}@endif</td>
                                                @foreach ($bpjstk as $_data)
                                                @php
                                                    // tidak digunakan, menjadi upload angka bpjstk
                                                    // $angka = round($_data->angka*$_data->persen/100, 0);
                                                    $angka = $_data->angka;
                                                    $potongan += $angka;
                                                @endphp
                                                <td class="text-end">{{ number_format($angka, 0) }}</td>
                                                @endforeach
                                                <td class="text-end">{{ number_format($potongan, 0) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>Rekapitulasi</th>
                                                @for ($x = 5; $x <= 14; $x++)
                                                <th id="bpjstk_{{$x}}" class="text-end">0</th>
                                                @endfor
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </p>
                        </div>
                        {{-- bpjsks --}}
                        <div class="tab-pane fade" id="pills-bpjskes" role="tabpanel" aria-labelledby="pills-bpjskes-tab">
                            <p class="mb-0 m-t-10">
                                <div class="row g-3 mb-2">
                                    <form action="{{ route('payroll.upload_payroll') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to upload this data?');">
                                    @csrf
                                    <input type="hidden" id="jenis" name="jenis" value="bpjsks_past">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <input name="template" id="template" class="form-control" type="file" required="">
                                            <label class="form-label">Maksimal 2 MB dengan format xls/xlsx</label>
                                            <br/><a href="{{ asset('assets/template/template_upload_bpjsks_v1.xlsx') }}" target="_blank"><i class="icon-file"></i>&nbsp;Download template</a>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                                <div class="table-responsive">
                                    <table class="display nowrap" id="_datatable4">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tahun</th>
                                                <th>Bulan</th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>Upah</th>
                                                <th>Upah Dilaporkan BPJS KS</th>
                                                <th>Upah Maksimum</th>
                                                @php
                                                    $arr_id = [1,2,3];
                                                    $arr = App\Models\MtrPayrollBpjskes::whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                                @endphp
                                                @foreach ($arr as $_arr)
                                                <th>{{ $_arr->nama }}<br/>{{ $_arr->persen }}%</th>
                                                @endforeach
                                                <th>Total Iuran</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no=0; @endphp
                                            @foreach ($payroll as $data)
                                            @php
                                                $where = ['nik'=>$data->nik, 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $bpjskes = App\Models\TrxPayrollBpjskesLog::where($where)->orderBy('id_bpjskes', 'asc')->get();
                                                $potongan = 0;
                                            @endphp
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $data->tahun }}</td>
                                                <td>{{ $bulan[$data->bulan - 1] }}</td>
                                                <td>{{ $data->_nik->nama }}</td>
                                                <td>{{ $data->nik }}</td>
                                                <td class="text-end">{{ number_format($bpjskes[0]->upah, 0) }}</td>
                                                <td class="text-end">{{ number_format($bpjskes[0]->thp_dilaporkan, 0) }}</td>
                                                <td class="text-end">@if($bpjskes[0]->thp_dilaporkan > $bpjskes[0]->maksimal){{ number_format($bpjskes[0]->maksimal, 0) }}@else{{ number_format($bpjskes[0]->thp_dilaporkan, 0) }}@endif</td>
                                                @foreach ($bpjskes as $_data)
                                                @php
                                                    // tidak digunakan, menjadi upload angka bpjsks
                                                    // $angka = round($_data->angka*$_data->persen/100, 0);
                                                    $angka = $_data->angka;
                                                    $potongan += $angka;
                                                @endphp
                                                <td class="text-end">{{ number_format($angka, 0) }}</td>
                                                @endforeach
                                                <td class="text-end">{{ number_format($potongan, 0) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>Rekapitulasi</th>
                                                @for ($x = 5; $x <= 11; $x++)
                                                <th id="bpjsks_{{$x}}" class="text-end">0</th>
                                                @endfor
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </p>
                        </div>
                        {{-- PPh21 --}}
                        {{-- @if(Session::has('tab') && Session::has('tab')=='kerja') show active @endif --}}
                        <div class="tab-pane fade @can('payroll_fin') show active @endcan" id="pills-kerja" role="tabpanel" aria-labelledby="pills-kerja-tab">
                            <p class="mb-0 m-t-10">
                                <div class="row g-3 mb-2">
                                    <form action="{{ route('payroll.upload_payroll') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to upload this data?');">
                                    @csrf
                                    <input type="hidden" id="jenis" name="jenis" value="pajak_past">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <input name="template" id="template" class="form-control" type="file" required="">
                                            <label class="form-label">Maksimal 2 MB dengan format xls/xlsx</label>
                                            <br/><a href="{{ asset('assets/template/template_upload_pajak_v1.xlsx') }}" target="_blank"><i class="icon-file"></i>&nbsp;Download template</a>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                                <div class="table-responsive">
                                    <table class="display nowrap" id="_datatable5">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tahun</th>
                                                <th>Bulan</th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>Findim Sub Departemen</th>
                                                <th>Findim Departemen</th>
                                                <th>Findim Divisi</th>
                                                <th>Project</th>
                                                <th>Direktorat</th>
                                                <th>Divisi</th>
                                                <th>Departemen</th>
                                                <th>Status Karyawan</th>
                                                <th>Status Aktif</th>
                                                <th>Jabatan</th>
                                                @can('payroll')
                                                <th>Level</th>
                                                <th>Grade</th>
                                                <th>Gaji Pokok</th>
                                                @endcan
                                                @php
                                                    $where = ['tipe'=>'add'];
                                                    $arr_id = [1,2,3,4,5];
                                                    $add = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                                @endphp
                                                @foreach ($add as $data)
                                                @can('payroll')
                                                <th>{{ $data->nama }}</th>
                                                @endcan
                                                @endforeach
                                                @can('payroll')
                                                <th>Total Tunjangan</th>
                                                @endcan
                                                <th>Upah</th>
                                                @php
                                                    $where = ['tipe'=>'add'];
                                                    $arr_id = [6,7,8,9,21];
                                                    $add = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                                @endphp
                                                @foreach ($add as $data)
                                                <th>{{ $data->nama }}</th>
                                                @endforeach
                                                <th>Total Upah</th>
                                                @can('payroll')
                                                <th>No KPJ (BPJSTK)</th>
                                                <th>No JKN (BPJSKS)</th>
                                                <th>Upah Dilaporkan BPJS TK</th>
                                                <th>Upah Dilaporkan BPJS KS</th>
                                                <th>Iuran BPJSTK</th>
                                                <th>Iuran BPJSKS</th>
                                                @endcan
                                                <th>Total Iuran BPJS/ASTEK</th>
                                                @can('payroll')
                                                <th>Kehamilan</th>
                                                <th>Persalinan</th>
                                                <th>Kacamata</th>
                                                <th>Medical Reimburse</th>
                                                <th>Ekses</th>
                                                @endcan
                                                <th>Total Medical Cost</th>
                                                <th>Overtime</th>
                                                <th>PPh21</th>
                                                @can('payroll')
                                                <th>Action</th>
                                                @endcan
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no=0; @endphp
                                            @foreach ($payroll as $data)
                                            @php
                                                $nik_property = json_decode($data->nik_property, true);
                                                $where = ['nik'=>$data->nik, 'tipe'=>'add', 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $arr_id = [1,2,3,4,5];
                                                $add = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $total_tunjangan = $add->sum('angka');
                                                $arr_id = [6,7,8,9,21];
                                                $_add = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $_total_tunjangan = $_add->sum('angka');
                                                $where = ['nik'=>$data->nik];
                                                $mtr = App\Models\MtrPayroll::where($where)->first();
                                                $where = ['nik'=>$data->nik, 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $bpjstk = App\Models\TrxPayrollBpjstkLog::where($where)->orderBy('id', 'asc')->get();
                                                $bpjsks = App\Models\TrxPayrollBpjskesLog::where($where)->orderBy('id', 'asc')->get();
                                                $pot_bpjstk = 0;
                                                $pot_bpjsks = 0;
                                                // 
                                                // $date_from = '2024-10-01';
                                                // $date_to = '2024-10-31';
                                                // kesehatan data bulan berjalan
                                                // $date_from = Carbon\Carbon::createFromFormat('Y-m-d', "{$data->tahun}-{$data->bulan}-01")->startOfMonth()->format('Y-m-d');
                                                // $date_to = Carbon\Carbon::createFromFormat('Y-m-d', "{$data->tahun}-{$data->bulan}-01")->endOfMonth()->format('Y-m-d');
                                                $total_kesehatan = 0;
                                                // 4 = Kehamilan
                                                // $kehamilan = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$data->nik."' AND DATE(b.created_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (4)"))[0]->jumlah_klaim;
                                                $kehamilan = $data->kehamilan;
                                                $total_kesehatan += $kehamilan;
                                                // 5,6 = Persalinan-Normal dan Persalinan-Caesar
                                                // $persalinan = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$data->nik."' AND DATE(b.created_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (5,6)"))[0]->jumlah_klaim;
                                                $persalinan = $data->persalinan;
                                                $total_kesehatan += $persalinan;
                                                // 2,3 = Kacamata-Frame dan Kacamata-Lensa
                                                // $kacamata = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$data->nik."' AND DATE(b.created_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (2,3)"))[0]->jumlah_klaim;
                                                $kacamata = $data->kacamata;
                                                $total_kesehatan += $kacamata;
                                                // 1 = Kesehatan
                                                // $kesehatan = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$data->nik."' AND DATE(b.created_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (1)"))[0]->jumlah_klaim;
                                                $kesehatan = $data->kesehatan;
                                                $total_kesehatan += $kesehatan;
                                                // 7=Ekses
                                                $ekses = $data->ekses;
                                                $total_kesehatan += $ekses;
                                                // overtime data bulan lalu
                                                // $date_from = Carbon\Carbon::createFromFormat('Y-m-d', "{$data->tahun}-{$data->bulan}-01")->subMonth()->startOfMonth()->format('Y-m-d');
                                                // $date_to = Carbon\Carbon::createFromFormat('Y-m-d', "{$data->tahun}-{$data->bulan}-01")->subMonth()->endOfMonth()->format('Y-m-d');
                                                // $where = ['ot_maker_by'=>$data->nik, 'ot_approver_status'=>'Approve'];
                                                // $overtime = App\Models\Overtime::select(DB::raw('SUM(ROUND(ot_length_rp_adj,-3)) as ot_length_rp_adj'))->where($where)->whereBetween(DB::raw('DATE(ot_start_at)'), [$date_from, $date_to])->orderBy( 'ot_start_at', 'desc' )->groupBy('ot_maker_by')->get();
                                                // $total_overtime = $overtime->sum('ot_length_rp_adj');
                                                $total_overtime = $data->overtime;
                                                // dd($total_overtime);
                                            @endphp
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $data->tahun }}</td>
                                                <td>{{ $bulan[$data->bulan - 1] }}</td>
                                                <td>{{ $data->_nik->nama }}</td>
                                                <td>{{ $data->nik }}</td>
                                                <td>{{ $data->findim_subdepartemen }}</td>
                                                <td>{{ $data->findim_departemen }}</td>
                                                <td>{{ $data->findim_divisi }}</td>
                                                <td>{{ $data->findim_project }}</td>
                                                <td>{{ $data->divisi }}</td>
                                                <td>{{ $data->departemen }}</td>
                                                <td>{{ $data->subdepartemen }}</td>
                                                <td>{{ $data->status_karyawan }}</td>
                                                {{-- <td>{{ $data->status_aktif }}</td> --}}
                                                <td>@if(App\Models\Karyawan::where(['nik'=>$data->nik, 'is_aktif'=>1])->exists()) Aktif @else Non Aktif @endif</td>
                                                <td>{{ $data->jabatan }}</td>
                                                @can('payroll')
                                                <td>{{ $data->level }}</td>
                                                <td>{{ $data->grade }}</td>
                                                <td class="text-end">{{ number_format($data->gapok, 0) }}</td>
                                                @endcan
                                                {{--  --}}
                                                @foreach ($add as $_data)
                                                @can('payroll')
                                                <td class="text-end">{{ number_format($_data->angka, 0) }}</td>
                                                @endcan
                                                @endforeach
                                                @can('payroll')
                                                {{-- total_tunjangan --}}
                                                <td class="text-end">{{ number_format($total_tunjangan, 0) }}</td>
                                                @endcan
                                                {{-- upah --}}
                                                <td class="text-end">{{ number_format($data->gapok + $total_tunjangan, 0) }}</td>
                                                {{--  --}}
                                                @foreach ($_add as $_data)
                                                <td class="text-end">{{ number_format($_data->angka, 0) }}</td>
                                                @endforeach
                                                {{-- total upah --}}
                                                <td class="text-end">{{ number_format($data->gapok + $total_tunjangan + $_total_tunjangan, 0) }}</td>
                                                @can('payroll')
                                                <td>@if(!empty($mtr->no_kpj)){{ $mtr->no_kpj }}@endif</td>
                                                <td>@if(!empty($mtr->no_jkn)){{ $mtr->no_jkn }}@endif</td>
                                                {{-- <td class="text-end">{{ number_format($bpjstk[0]->angka, 0) }}</td> --}}
                                                {{-- <td class="text-end">@if($bpjstk[0]->upah > $bpjstk[0]->maksimal){{ number_format($bpjstk[0]->maksimal, 0) }}@else{{ number_format($bpjstk[0]->upah, 0) }}@endif</td> --}}
                                                <td class="text-end">{{ number_format($bpjstk[0]->thp_dilaporkan, 0) }}</td>
                                                {{-- <td class="text-end">{{ number_format($bpjsks[0]->angka, 0) }}</td> --}}
                                                {{-- <td class="text-end">@if($bpjsks[0]->upah > $bpjsks[0]->maksimal){{ number_format($bpjsks[0]->maksimal, 0) }}@else{{ number_format($bpjsks[0]->upah, 0) }}@endif</td> --}}
                                                <td class="text-end">{{ number_format($bpjsks[0]->thp_dilaporkan, 0) }}</td>
                                                @endcan
                                                {{--  --}}
                                                @foreach ($bpjstk as $_data)
                                                @php
                                                    // tidak digunakan, menjadi upload angka bpjstk
                                                    // $angka = round($_data->angka*$_data->persen/100, 0);
                                                    $angka = $_data->angka;
                                                    $pot_bpjstk += $angka;
                                                @endphp
                                                @endforeach
                                                @can('payroll')
                                                <td class="text-end">{{ number_format($pot_bpjstk, 0) }}</td>
                                                @endcan
                                                {{--  --}}
                                                @foreach ($bpjsks as $_data)
                                                @php
                                                    // tidak digunakan, menjadi upload angka bpjsks
                                                    // $angka = round($_data->angka*$_data->persen/100, 0);
                                                    $angka = $_data->angka;
                                                    $pot_bpjsks += $angka;
                                                @endphp
                                                @endforeach
                                                @can('payroll')
                                                <td class="text-end">{{ number_format($pot_bpjsks, 0) }}</td>
                                                @endcan
                                                {{--  --}}
                                                <td class="text-end">{{ number_format($pot_bpjstk + $pot_bpjsks, 0) }}</td>
                                                @can('payroll')
                                                <td class="text-end">{{ number_format($kehamilan, 0) }}</td>
                                                <td class="text-end">{{ number_format($persalinan, 0) }}</td>
                                                <td class="text-end">{{ number_format($kacamata, 0) }}</td>
                                                <td class="text-end">{{ number_format($kesehatan, 0) }}</td>
                                                <td class="text-end">{{ number_format($ekses, 0) }}</td>
                                                @endcan
                                                <td class="text-end">{{ number_format($total_kesehatan, 0) }}</td>
                                                <td class="text-end">{{ number_format($total_overtime, 0) }}</td>
                                                <td class="text-end">{{ number_format($data->pajak, 0) }}</td>
                                                @can('payroll')
                                                <td><a href="{{ route('payroll.edit_payroll', [Crypt::encrypt($data->id), 'back_url'=>URL::full(), 'type'=>'overtime_past']) }}" data-toggle="tooltip" title='Adjustment'> <i class="icon-pencil-alt"></i></a></td>
                                                @endcan
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            @can('payroll')
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>Rekapitulasi</th>
                                                @for ($x = 17; $x <= 30; $x++)
                                                <th id="kertas_{{$x}}" class="text-end">0</th>
                                                @endfor
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                @for ($x = 33; $x <= 45; $x++)
                                                <th id="kertas_{{$x}}" class="text-end">0</th>
                                                @endfor
                                                <th>&nbsp;</th>
                                            </tr>
                                            @endcan
                                            {{--  --}}
                                            @can('payroll_fin')
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>Rekapitulasi</th>
                                                @for ($x = 15; $x <= 25; $x++)
                                                <th id="kertas_{{$x}}" class="text-end">0</th>
                                                @endfor
                                            </tr>
                                            @endcan
                                        </tfoot>
                                    </table>
                                </div>
                            </p>
                        </div>
                        {{--  --}}
                    </div>
                    <hr class="mt-4 mb-2" />
                    @endif
                    @can('payroll')
                    <h4>Out Source</h4>
                    <p class="mb-0 m-t-10">
                        <div class="table-responsive">
                            <table class="display nowrap" id="_datatable6">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun</th>
                                        <th>Bulan</th>
                                        <th>Nama</th>
                                        <th>NIP</th>
                                        <th>Gaji Pokok</th>
                                        {{-- 5=Tunjangan Lainnya --}}
                                        @php
                                            $add = App\Models\MtrPayrollAddsub::find(5);
                                        @endphp
                                        <th>{{ $add->nama }}</th>
                                        <th>Total Upah</th>
                                        {{-- 22=Tunjangan Telekomunikasi Tambahan --}}
                                        @php
                                            $add = App\Models\MtrPayrollAddsub::find(22);
                                        @endphp
                                        <th>{{ $add->nama }}</th>
                                        {{-- 6=Insentif --}}
                                        {{-- 7=Kekurangan Upah --}}
                                        {{-- 8=Sewa Laptop --}}
                                        @php
                                            $where = ['tipe'=>'add'];
                                            $arr_id = [6,7,8];
                                            $add = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                        @endphp
                                        @foreach ($add as $data)
                                        <th>{{ $data->nama }}</th>
                                        @endforeach
                                        {{-- 12=Overtime --}}
                                        @php
                                            $add = App\Models\MtrPayrollAddsub::find(12);
                                        @endphp
                                        <th>{{ $add->nama }}</th>
                                        <th>PPh 21</th>
                                        {{-- 4=BPJS Kesehatan --}}
                                        @php
                                            $add = App\Models\MtrPayrollBpjskes::find(4);
                                        @endphp
                                        <th>{{ $add->nama }}</th>
                                        {{-- 7=BPJS Ketenagakerjaan --}}
                                        @php
                                            $add = App\Models\MtrPayrollBpjstk::find(7);
                                        @endphp
                                        <th>{{ $add->nama }}</th>
                                        <th>Total Biaya Tenaga Kerja</th>
                                        {{-- 4=Management Fee(%) --}}
                                        @php
                                            $add = App\Models\MtrPayrollBpjsMax::find(4);
                                        @endphp
                                        <th>{{ $add->nama }}</th>
                                        <th>Management Fee (Rp)</th>
                                        <th>Total Tagihan Upah Outsource</th>
                                        {{-- 9=Kompensasi KKWT --}}
                                        @php
                                            $add = App\Models\MtrPayrollAddsub::find(9);
                                        @endphp
                                        <th>{{ $add->nama }}</th>
                                        {{-- 5=Management Fee Kompensasi KKWT(%) --}}
                                        @php
                                            $add = App\Models\MtrPayrollBpjsMax::find(5);
                                        @endphp
                                        <th>{{ $add->nama }}</th>
                                        <th>Management Fee Kompensasi KKWT (Rp)</th>
                                        <th>Total Tagihan Kompensasi KKWT Outsource</th>
                                        <th>Total Biaya Outsource</th>
                                        <th>Medical Reimbursement</th>
                                        @can('payroll')
                                        <th>Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no=0; @endphp
                                    @foreach ($payroll_out as $data)
                                    <tr>
                                        <td>{{ ++$no }}</td>
                                        <td>{{ $data->tahun }}</td>
                                        <td>{{ $bulan[$data->bulan - 1] }}</td>
                                        <td>{{ $data->_nik->nama }}</td>
                                        <td>{{ $data->nik }}</td>
                                        <td class="text-end">{{ number_format($data->gapok, 0) }}</td>
                                        {{-- 5=Tunjangan Lainnya --}}
                                        @php
                                            $where = ['nik'=>$data->nik, 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                            $arr_id = [5];
                                            $add = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                            $total_tunjangan = $add->sum('angka');
                                            $total_upah = round($data->gapok, 0) + round($total_tunjangan, 0);
                                            $total_biaya = $total_upah;
                                        @endphp
                                        <td class="text-end">{{ number_format($total_tunjangan, 0) }}</td>
                                        <td class="text-end">{{ number_format($total_upah, 0) }}</td>
                                        {{-- 22=Tunjangan Telekomunikasi Tambahan --}}
                                        @php
                                            $arr_id = [22];
                                            $add = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                            $angka_22 = round($add->sum('angka'), 0);
                                            $total_biaya += $angka_22;
                                        @endphp
                                        <td class="text-end">{{ number_format($angka_22, 0) }}</td>
                                        {{-- 6=Insentif --}}
                                        {{-- 7=Kekurangan Upah --}}
                                        {{-- 8=Sewa Laptop --}}
                                        @php
                                            $arr_id = [6,7,8];
                                            $add = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                            $total_biaya += round($add->sum('angka'), 0);
                                        @endphp
                                        @foreach ($add as $_data)
                                        <td class="text-end">{{ number_format($_data->angka, 0) }}</td>
                                        @endforeach
                                        <td class="text-end">{{ number_format($data->overtime, 0) }}</td>
                                        <td class="text-end">{{ number_format($data->pajak, 0) }}</td>
                                        {{-- bpjskes --}}
                                        @php
                                            $arr_id = [4];
                                            $bpjskes = App\Models\TrxPayrollBpjskesLog::where($where)->whereIn('id_bpjskes', $arr_id)->orderBy('id_bpjskes', 'asc')->get();
                                            $bpjskes = round($bpjskes->sum('angka'), 0);
                                            $total_biaya += round($data->overtime, 0) + round($data->pajak, 0) + $bpjskes;
                                        @endphp
                                        <td class="text-end">{{ number_format($bpjskes, 0) }}</td>
                                        {{-- bpjstk --}}
                                        @php
                                            $arr_id = [7];
                                            $bpjstk = App\Models\TrxPayrollBpjstkLog::where($where)->whereIn('id_bpjstk', $arr_id)->orderBy('id_bpjstk', 'asc')->get();
                                            $bpjstk = round($bpjstk->sum('angka'), 0);
                                            $total_biaya += $bpjstk;
                                        @endphp
                                        <td class="text-end">{{ number_format($bpjstk, 0) }}</td>
                                        {{-- Total Biaya Tenaga Kerja --}}
                                        <td class="text-end">{{ number_format($total_biaya, 0) }}</td>
                                        {{-- mgt_fee --}}
                                        <td class="text-end">{{ number_format($data->mgt_fee, 2) }} %</td>
                                        @php
                                            $mgt_fee = round($total_biaya * $data->mgt_fee / 100, 0);
                                            $total_biaya += $mgt_fee;
                                        @endphp
                                        <td class="text-end">{{ number_format($mgt_fee, 0) }}</td>
                                        {{-- Total Tagihan Upah Outsource --}}
                                        <td class="text-end">{{ number_format($total_biaya, 0) }}</td>
                                        {{-- 9=Kompensasi KKWT --}}
                                        @php
                                            $arr_id = [9];
                                            $add = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                            $angka_9 = round($add->sum('angka'), 0);
                                        @endphp
                                        <td class="text-end">{{ number_format($angka_9, 0) }}</td>
                                        {{-- mgt_fee_kkwt --}}
                                        <td class="text-end">{{ number_format($data->mgt_fee_kkwt, 2) }} %</td>
                                        @php
                                            $mgt_fee_kkwt = round($angka_9 * $data->mgt_fee_kkwt / 100, 0);
                                        @endphp
                                        <td class="text-end">{{ number_format($mgt_fee_kkwt, 0) }}</td>
                                        @php
                                            $mgt_fee_kkwt += $angka_9;
                                        @endphp
                                        <td class="text-end">{{ number_format($mgt_fee_kkwt, 0) }}</td>
                                        @php
                                            $total_biaya += round($mgt_fee_kkwt, 0);
                                        @endphp
                                        {{-- Total Biaya Outsource --}}
                                        <td class="text-end">{{ number_format($total_biaya, 0) }}</td>
                                        <td class="text-end">{{ number_format($data->kesehatan, 0) }}</td>
                                        @can('payroll')
                                        <td><a href="{{ route('payroll.edit_payroll', [Crypt::encrypt($data->id), 'back_url'=>URL::full(), 'type'=>'overtime_past']) }}" data-toggle="tooltip" title='Adjustment'> <i class="icon-pencil-alt"></i></a></td>
                                        @endcan
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>Rekapitulasi</th>
                                        @for ($x = 5; $x <= 16; $x++)
                                        <th id="outsource_{{$x}}" class="text-end">0</th>
                                        @endfor
                                        <th>&nbsp;</th>
                                        @for ($x = 18; $x <= 20; $x++)
                                        <th id="outsource_{{$x}}" class="text-end">0</th>
                                        @endfor
                                        <th>&nbsp;</th>
                                        @for ($x = 22; $x <= 25; $x++)
                                        <th id="outsource_{{$x}}" class="text-end">0</th>
                                        @endfor
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </p>
                    @endcan
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
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script type="text/javascript">
// semua
$('#_datatable1').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]],
    footerCallback: function (row, data, start, end, display) {
        nfObject = new Intl.NumberFormat('en-US');
        let api = this.api();
        let intVal = function (i) {
            return typeof i === 'string'
                ? i.replace(/[\$,]/g, '') * 1
                : typeof i === 'number'
                ? i
                : 0;
        };
        @for ($x = 5; $x <= 12; $x++)
        semua_{{$x}} = api.column({{$x}}).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        page_semua_{{$x}} = api.column({{$x}}, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column({{$x}}).footer().innerHTML = nfObject.format(page_semua_{{$x}}) + '<br/>Total: ' + nfObject.format(semua_{{$x}});
        @endfor
        @for ($x = 14; $x <= 19; $x++)
        semua_{{$x}} = api.column({{$x}}).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        page_semua_{{$x}} = api.column({{$x}}, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column({{$x}}).footer().innerHTML = nfObject.format(page_semua_{{$x}}) + '<br/>Total: ' + nfObject.format(semua_{{$x}});
        @endfor
    },
});
// potongan
$('#_datatable2').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]],
    footerCallback: function (row, data, start, end, display) {
        nfObject = new Intl.NumberFormat('en-US');
        let api = this.api();
        let intVal = function (i) {
            return typeof i === 'string'
                ? i.replace(/[\$,]/g, '') * 1
                : typeof i === 'number'
                ? i
                : 0;
        };
        @for ($x = 5; $x <= 17; $x++)
        potongan_{{$x}} = api.column({{$x}}).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        page_potongan_{{$x}} = api.column({{$x}}, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column({{$x}}).footer().innerHTML = nfObject.format(page_potongan_{{$x}}) + '<br/>Total: ' + nfObject.format(potongan_{{$x}});
        @endfor
    },
});
// bpjstk
$('#_datatable3').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]],
    footerCallback: function (row, data, start, end, display) {
        nfObject = new Intl.NumberFormat('en-US');
        let api = this.api();
        let intVal = function (i) {
            return typeof i === 'string'
                ? i.replace(/[\$,]/g, '') * 1
                : typeof i === 'number'
                ? i
                : 0;
        };
        @for ($x = 5; $x <= 14; $x++)
        bpjstk_{{$x}} = api.column({{$x}}).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        page_bpjstk_{{$x}} = api.column({{$x}}, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column({{$x}}).footer().innerHTML = nfObject.format(page_bpjstk_{{$x}}) + '<br/>Total: ' + nfObject.format(bpjstk_{{$x}});
        @endfor
    },
});
// bpjskes
$('#_datatable4').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]],
    footerCallback: function (row, data, start, end, display) {
        nfObject = new Intl.NumberFormat('en-US');
        let api = this.api();
        let intVal = function (i) {
            return typeof i === 'string'
                ? i.replace(/[\$,]/g, '') * 1
                : typeof i === 'number'
                ? i
                : 0;
        };
        @for ($x = 5; $x <= 11; $x++)
        bpjsks_{{$x}} = api.column({{$x}}).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        page_bpjsks_{{$x}} = api.column({{$x}}, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column({{$x}}).footer().innerHTML = nfObject.format(page_bpjsks_{{$x}}) + '<br/>Total: ' + nfObject.format(bpjsks_{{$x}});
        @endfor
    },
});
// PPh21
$('#_datatable5').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]],
    footerCallback: function (row, data, start, end, display) {
        nfObject = new Intl.NumberFormat('en-US');
        let api = this.api();
        let intVal = function (i) {
            return typeof i === 'string'
                ? i.replace(/[\$,]/g, '') * 1
                : typeof i === 'number'
                ? i
                : 0;
        };
        // 
        @can('payroll')
        @for ($x = 17; $x <= 30; $x++)
        kertas_{{$x}} = api.column({{$x}}).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        page_kertas_{{$x}} = api.column({{$x}}, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column({{$x}}).footer().innerHTML = nfObject.format(page_kertas_{{$x}}) + '<br/>Total: ' + nfObject.format(kertas_{{$x}});
        @endfor
        // 
        @for ($x = 33; $x <= 45; $x++)
        kertas_{{$x}} = api.column({{$x}}).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        page_kertas_{{$x}} = api.column({{$x}}, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column({{$x}}).footer().innerHTML = nfObject.format(page_kertas_{{$x}}) + '<br/>Total: ' + nfObject.format(kertas_{{$x}});
        @endfor
        @endcan
        // 
        @can('payroll_fin')
        @for ($x = 15; $x <= 25; $x++)
        kertas_{{$x}} = api.column({{$x}}).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        page_kertas_{{$x}} = api.column({{$x}}, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column({{$x}}).footer().innerHTML = nfObject.format(page_kertas_{{$x}}) + '<br/>Total: ' + nfObject.format(kertas_{{$x}});
        @endfor
        @endcan
    },
});
// outsource
$('#_datatable6').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]],
    footerCallback: function (row, data, start, end, display) {
        nfObject = new Intl.NumberFormat('en-US');
        let api = this.api();
        let intVal = function (i) {
            return typeof i === 'string'
                ? i.replace(/[\$,]/g, '') * 1
                : typeof i === 'number'
                ? i
                : 0;
        };
        @for ($x = 5; $x <= 16; $x++)
        outsource_{{$x}} = api.column({{$x}}).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        page_outsource_{{$x}} = api.column({{$x}}, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column({{$x}}).footer().innerHTML = nfObject.format(page_outsource_{{$x}}) + '<br/>Total: ' + nfObject.format(outsource_{{$x}});
        @endfor
        // 
        @for ($x = 18; $x <= 20; $x++)
        outsource_{{$x}} = api.column({{$x}}).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        page_outsource_{{$x}} = api.column({{$x}}, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column({{$x}}).footer().innerHTML = nfObject.format(page_outsource_{{$x}}) + '<br/>Total: ' + nfObject.format(outsource_{{$x}});
        @endfor
        // 
        @for ($x = 22; $x <= 25; $x++)
        outsource_{{$x}} = api.column({{$x}}).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        page_outsource_{{$x}} = api.column({{$x}}, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column({{$x}}).footer().innerHTML = nfObject.format(page_outsource_{{$x}}) + '<br/>Total: ' + nfObject.format(outsource_{{$x}});
        @endfor
    },
});
// 
function toggle_findim_subdepartemen(source) {
    checkboxes = document.getElementsByName('findim_subdepartemen[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_findim_departemen(source) {
    checkboxes = document.getElementsByName('findim_departemen[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_findim_divisi(source) {
    checkboxes = document.getElementsByName('findim_divisi[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_findim_project(source) {
    checkboxes = document.getElementsByName('findim_project[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_divisi(source) {
    checkboxes = document.getElementsByName('divisi[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_departemen(source) {
    checkboxes = document.getElementsByName('departemen[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_subdepartemen(source) {
    checkboxes = document.getElementsByName('subdepartemen[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_status_karyawan(source) {
    checkboxes = document.getElementsByName('status_karyawan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_jabatan(source) {
    checkboxes = document.getElementsByName('jabatan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_level(source) {
    checkboxes = document.getElementsByName('level[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_nik(source) {
    checkboxes = document.getElementsByName('nik[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
</script>
@endsection