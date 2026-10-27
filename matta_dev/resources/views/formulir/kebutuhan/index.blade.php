@extends('layouts.simple.master')

@section('title', 'Formulir Kebutuhan Karyawan')

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
<h3>{{ __('lang.Formulir Kebutuhan Karyawan') }}</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">{{ __('lang.Formulir') }}</li>
<li class="breadcrumb-item active">{{ __('lang.Kebutuhan Karyawan') }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('error'))
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('error') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if(Session::has('msg'))
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @php
                        // $check = App\Models\MtrUtility::
                        $check = in_array(auth()->user()->nik, explode(',', App\Models\MtrUtility::find(43)->informasi));
                        // dd($check);
                    @endphp
                    @if($check)
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <select class="form-select js-example-basic-single col-sm-12" id="nik_pengaju" name="nik_pengaju">
                                <option value="0">Pilih Karyawan...</option>
                                @foreach ($karyawan as $data)
                                <option value="{{ Crypt::encrypt($data->nik) }}">{{ $data->nama }} - {{ $data->nik }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                    {{-- <a href="{{ route('kebutuhan.create') }}" class="btn btn-primary" type="button"><i class="fa fa-plus" aria-hidden="true"></i> {{ __('lang.Tambah Baru') }}</a> | <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> {{ __('lang.Saring') }}</button> --}}
                    <button class="btn btn-primary" type="button" onclick="PilihKaryawan()"><i class="fa fa-plus" aria-hidden="true"></i> {{ __('lang.Tambah Baru') }}</button> | <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> {{ __('lang.Saring') }}</button>
                    <div class="collapse" id="collapse_filter">
                        <form class="mt-2" action="{{ route('kebutuhan.index') }}" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="h6">Tanggal</label>
                                    <input class="form-control" name="tanggal_mulai" id="tanggal_mulai" type="date" required="" value="{{ $tanggal_mulai }}">
                                    sampai
                                    <input class="form-control" name="tanggal_akhir" id="tanggal_akhir" type="date" required="" value="{{ $tanggal_akhir }}">
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">{{ __('lang.Status Pengajuan') }}</label>&nbsp;<input type="checkbox" onClick="toggle_status_pengajuan(this)" /> {{ __('lang.Pilih Semua') }}
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="status_pengajuan">
                                        @foreach ($status_pengajuan as $data)
                                        <input class="checkbox_animated" id="status_pengajuan[]" name="status_pengajuan[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_status_pengajuan)) checked @endif> {{ $data->status_pengajuan }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">{{ __('lang.Area Kerja') }}</label>&nbsp;<input type="checkbox" onClick="toggle_id_area(this)" /> {{ __('lang.Pilih Semua') }}
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="id_area">
                                        @foreach ($id_area as $data)
                                        <input class="checkbox_animated" id="id_area[]" name="id_area[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_id_area)) checked @endif> {{ $data->propinsi }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">{{ __('lang.Jenis Permintaan') }}</label>&nbsp;<input type="checkbox" onClick="toggle_id_jenis_permintaan(this)" /> {{ __('lang.Pilih Semua') }}
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="id_jenis_permintaan">
                                        @foreach ($id_jenis_permintaan as $data)
                                        <input class="checkbox_animated" id="id_jenis_permintaan[]" name="id_jenis_permintaan[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_id_jenis_permintaan)) checked @endif> {{ $data->jenis }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> {{ __('lang.Saring') }}</button> | <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> {{ __('lang.Atur Ulang') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br/><br/>
                    <div class="table-responsive">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#ID</th>
                                    <th>{{ __('lang.Aksi') }}</th>
                                    <th>{{ __('lang.Area Kerja') }}</th>
                                    <th>{{ __('lang.Area Kerja Kota') }}</th>
                                    <th>{{ __('lang.Pengaju') }}</th>
                                    <th>{{ __('lang.NIP') }}</th>
                                    <th>Direktorat</th>
                                    <th>Divisi</th>
                                    <th>Departemen</th>
                                    <th>{{ __('lang.Nama Jabatan') }}</th>
                                    <th>Atasan Langsung Kandidat</th>
                                    <th>{{ __('lang.Status Kerja') }}</th>
                                    <th>{{ __('lang.Tanggal Mulai Bekerja') }}</th>
                                    <th>{{ __('lang.Jenis Permintaan') }}</th>
                                    <th>{{ __('lang.Jumlah (Orang)') }}</th>
                                    <th>{{ __('lang.Jenis Kelamin') }}</th>
                                    <th>{{ __('lang.Status Pengajuan') }}</th>
                                    <th>{{ __('lang.Waktu Pembuatan') }}</th>
                                    @can('hc')
                                    <th>{{ __('lang.Waktu Selesai') }}</th>
                                    <th>SLA (hari)</th>
                                    <th>SLA Status</th>
                                    <th>Tanggal SLA</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trx as $data)
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $data->id }}</td>
                                    <td>
                                        <a href="{{ route('kebutuhan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('detail')]) }}" data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        &nbsp;<a href="{{ route('kebutuhan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')]) }}" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        @can('hc')
                                        <br /><a href="{{ route('kebutuhan.edit', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)]) }}" data-toggle="tooltip" title='Update'><i class="icon-pencil-alt"></i></a>
                                        &nbsp;<a href="{{ route('kebutuhan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('doc')]) }}" data-toggle="tooltip" title="Document"><i class="fa fa-file-text-o"></i></a>
                                        &nbsp;<a href="{{ route('kebutuhan.export_word', (new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)) }}" data-toggle="tooltip" title="Uraian"><i style="color:orange" class="fa fa-file-text-o"></i></a>
                                        @endcan

                                    </td>
                                    @php
                                        $pengaju_property = json_decode($data->pengaju_property, true);
                                        $atasan_property = $data->atasan_property;
                                        $atasan_property = json_decode($atasan_property, true);
                                    @endphp
                                    <td>{{ $data->_area->propinsi }}</td>
                                    <td>@if (!empty($data->id_kota)) {{ $data->_kota->kota }} @endif</td>
                                    <td>{{ $pengaju_property['nama'] }}</td>
                                    <td>{{ $data->pengaju }}</td>
                                    <td>{{ $pengaju_property['divisi'] }}</td>
                                    <td>{{ $pengaju_property['dep'] }}</td>
                                    <td>{{ $pengaju_property['sub_dep'] }}</td>
                                    <td>{{ $data->_pekerjaan->pekerjaan }}</td>
                                    <td>@if(!empty($atasan_property)) {{ $atasan_property['nama'] }}/{{ $atasan_property['nik'] }} @endif</td>
                                    <td>{{ $data->_status_kerja->status_kerja }}</td>
                                    <td>{{ Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggal)->format('d-m-Y') }}</td>
                                    <td>{{ $data->_jenis_permintaan->jenis }}</td>
                                    <td>
                                        @php
                                        $is_history = App\Models\TrxKebutuhanMasterEdit::where(['id_kebutuhan' => $data->id])->exists();
                                        @endphp
                                        {{-- 3=Reject Checked by HC
                                        10=Done by HC
                                        28=Terminate Cancel by HC --}}
                                        @can('hc')@if(!in_array($data->status_pengajuan, [3,10,28]))<a href="{{ route('kebutuhan.edit_kolom', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'field'=>'jumlah']) }}" data-toggle="tooltip" title='Edit'><span class="badge @if($is_history) badge-warning @else badge-success @endif" style="font-size: 11px;">@endcan {{ $data->jumlah }} @can('hc')</span></a>@endif @endcan
                                        
                                    </td>
                                    <td>{{ $data->_kelamin->kelamin }}</td>
                                    <td>{{ $data->_status_pengajuan->status_pengajuan }}</td>
                                    <td>{{ $data->created_at }}</td>
                                    {{--  --}}
                                    @can('hc')
                                    <td>@if ($data->status_pengajuan==10) {{ $data->updated_at }} @endif</td>
                                    @php
                                        $sla_start = '';
                                        $sla_end = '';
                                        // tidak digunakan
                                        // 2=Posting
                                        // $sla = App\Models\TrxRecruitment::where(['id_kebutuhan'=>$data->id, 'id_status'=>2])->first();
                                        $kandidat = App\Models\TrxRecruitmentIdentitas::where(['id_kebutuhan'=>$data->id])->get();
                                        $id_identitas = [];
                                        foreach($kandidat as $_kandidat){
                                            array_push($id_identitas, $_kandidat->id);
                                        }
                                        // 24=Join
                                        $sla = App\Models\TrxRecruitmentLog::whereIn('id_identitas',$id_identitas)->where(['id_status'=>24])->orderBy('created_at', 'desc')->get();
                                        if($sla->count() >= $data->jumlah){
                                            $sla_end = Carbon\Carbon::parse($sla[0]->created_at)->format('Y-m-d');
                                        }
                                        // merujuk ke CM 507
                                        // Default : SLA Add employee dimulai dari Approval By L2 ke Done By HC agar muncul Status SLA
                                        // 9=Approved by L2 to HC
                                        $sla = App\Models\TrxKebutuhanLog::where(['id_trx'=>$data->id, 'id_status'=>9])->first();
                                        if(!empty($sla->created_at)){
                                            $sla_start = Carbon\Carbon::parse($sla->created_at)->format('Y-m-d');
                                        }
                                        // 10=Done by HC
                                        if(empty($sla_end)){
                                            $sla = App\Models\TrxKebutuhanLog::where(['id_trx'=>$data->id, 'id_status'=>10])->first();
                                            if(!empty($sla->created_at)){
                                                $sla_end = Carbon\Carbon::parse($sla->created_at)->format('Y-m-d');
                                            }
                                        }
                                        // SELECT `id`,`status_pengajuan` FROM `mtr_tbl_kebutuhan_status` WHERE `id` IN (3,5,7,12,13,17,20,24,26,27,28);
                                        // #Cancel=Orange
                                        $cancel = App\Models\TrxKebutuhanLog::where(['id_trx'=>$data->id])->whereIn('id_status', [3,5,7,12,13,17,20,24,26,27,28])->exists();
                                    @endphp
                                    <td>
                                        @if(!empty($sla_start) && !empty($sla_end))
                                            @php
                                                // $sla_start = Carbon::parse($sla_start)->addDay();
                                                $period = Carbon\CarbonPeriod::create($sla_start, $sla_end);
                                                $workingDays = 0;
                                                $holidays = App\Models\Holiday::whereBetween('tanggal', [$sla_start, $sla_end])->pluck('tanggal')->toArray();
                                                // $arr_days = [];
                                                foreach ($period as $date) {
                                                    // Cek jika hari Sabtu (6) atau Minggu (0)
                                                    if ($date->isWeekend()) {
                                                        continue;
                                                    }
                                                    // Cek jika tanggal ada di daftar hari libur
                                                    if (in_array($date->format('Y-m-d'), $holidays)) {
                                                        continue;
                                                    }
                                                    // array_push($arr_days, $date);
                                                    $workingDays++;
                                                }
                                            @endphp
                                            {{-- {{ Carbon\Carbon::parse($sla_start)->diffInDays(Carbon\Carbon::parse($sla_end), false) }}<br/> --}}
                                            {{ $workingDays }}
                                            {{-- @foreach($arr_days as $_arr_days)
                                                {{ Carbon\Carbon::parse($_arr_days)->format('d-m-Y') }}<br/>
                                            @endforeach --}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($sla_start) && !empty($sla_end))
                                            @if($workingDays <= 14)
                                            <span class="badge badge-success" style="font-size: 11px;">Ontime</span>
                                            @else
                                            <span class="badge badge-danger" style="font-size: 11px;">Overdue</span>
                                            @endif
                                        @elseif($cancel)
                                            <span class="badge badge-warning" style="font-size: 11px;">Cancel</span>
                                        @else
                                            <span class="badge badge-primary" style="font-size: 11px;">On Progress</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($sla_start) && !empty($sla_end))
                                            {{ Carbon\Carbon::parse($sla_start)->format('d-m-Y') }} sd {{ Carbon\Carbon::parse($sla_end)->format('d-m-Y') }}
                                        @endif
                                    </td>
                                    @endcan
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script type="text/javascript">
function PilihKaryawan() {
    var nik_pengaju = "{{ Crypt::encrypt(auth()->user()->nik) }}";
    @if($check)
    if($('#nik_pengaju').val() != '0'){
        nik_pengaju = $('#nik_pengaju').val();
    }
    @endif
    location.href = "{{ route('kebutuhan.create') }}"+"?nik_pengaju="+nik_pengaju;
}
// 
function toggle_status_pengajuan(source) {
    checkboxes = document.getElementsByName('status_pengajuan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_id_area(source) {
    checkboxes = document.getElementsByName('id_area[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_id_jenis_permintaan(source) {
    checkboxes = document.getElementsByName('id_jenis_permintaan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
$('#_datatable').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
} );
</script>
@endsection