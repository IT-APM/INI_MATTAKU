@extends('layouts.simple.master')

@section('title', 'Formulir Rekomendasi Karyawan')

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
<h3>Formulir Rekomendasi Karyawan</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Rekomendasi Karyawan</li>
@endsection

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
                    @can('hc')
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <select class="form-select js-example-basic-single col-sm-12" id="nik_atasan" name="nik_atasan">
                                <option value="0">Pilih Layer 1...</option>
                                @foreach ($layer1 as $data)
                                <option value="{{ Crypt::encrypt($data->nik) }}">{{ $data->nama }} - {{ $data->nik }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endcan
                    {{-- before --}}
                    {{-- <a href="{{ route('rekomendasi.create') }}" class="btn btn-primary" type="button"><i class="fa fa-plus" aria-hidden="true"></i> Add new</a> --}}
                    {{-- after --}}
                    <a class="btn btn-primary" type="button" onclick="pilih_atasan()"><i class="fa fa-plus" aria-hidden="true"></i> Add new</a> | <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                    <div class="collapse" id="collapse_filter">
                        <form class="mt-2" action="{{ route('rekomendasi.index') }}" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="h6">Tanggal</label>
                                    <input class="form-control" name="tanggal_mulai" id="tanggal_mulai" type="date" required="" value="{{ $tanggal_mulai }}">
                                    sampai
                                    <input class="form-control" name="tanggal_akhir" id="tanggal_akhir" type="date" required="" value="{{ $tanggal_akhir }}">
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Status Pengajuan</label>&nbsp;<input type="checkbox" onClick="toggle_status_rekomendasi(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="status_rekomendasi">
                                        @foreach ($status_rekomendasi as $data)
                                        <input class="checkbox_animated" id="status_rekomendasi[]" name="status_rekomendasi[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_status_rekomendasi)) checked @endif> {{ $data->status_pengajuan }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                    {{-- <label class="h6">Nama Pengaju</label>&nbsp;<input type="checkbox" onClick="toggle_nik_atasan(this)" /> Check All
                                    <label class="d-block" for="nik_atasan">
                                        @foreach ($nik_atasan as $data)
                                        <input class="checkbox_animated" id="nik_atasan[]" name="nik_atasan[]" type="checkbox" value="{{ $data->nik }}" @if(in_array($data->nik, $arr_nik_atasan)) checked @endif> {{ $data->nama }}<br/>
                                        @endforeach
                                    </label>
                                    <label class="h6">Nama Karyawan</label>&nbsp;<input type="checkbox" onClick="toggle_nik_karyawan(this)" /> Check All
                                    <label class="d-block" for="nik_karyawan">
                                        @foreach ($nik_karyawan as $data)
                                        <input class="checkbox_animated" id="nik_karyawan[]" name="nik_karyawan[]" type="checkbox" value="{{ $data->nik }}" @if(in_array($data->nik, $arr_nik_karyawan)) checked @endif> {{ $data->nama }}<br/>
                                        @endforeach
                                    </label> --}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
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
                                    <th>Action</th>
                                    <th>Nama Karyawan</th>
                                    <th>NIP Karyawan</th>
                                    <th>Nama Pengaju</th>
                                    <th>NIP Pengaju</th>
                                    <th>Direktorat Pengaju</th>
                                    <th>Divisi Pengaju</th>
                                    <th>Departemen Pengaju</th>
                                    <th>Approval 1</th>
                                    <th>Approval 2</th>
                                    <th>Approval 3</th>
                                    <th>Terhitung Tanggal</th>
                                    <th>Status</th>
                                    <th>Created at</th>
                                    <th>Closed at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trx as $data)
                                <tr>
                                    @php
                                    $atasan_property = json_decode($data['atasan_property'], true);
                                    @endphp
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $data->id }}</td>
                                    <td>
                                        <a href="{{ route('rekomendasi.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('detail')]) }}" data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        &nbsp;<a href="{{ route('rekomendasi.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')]) }}" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        @can('hc')
                                        &nbsp;<a href="{{ route('rekomendasi.edit', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)]) }}" data-toggle="tooltip" title='Update'><i class="icon-pencil-alt"></i></a>
                                        @endcan

                                    </td>
                                    <td>{{ $data->_karyawan->nama }}</td>
                                    <td>{{ $data->_karyawan->nik }}</td>
                                    <td>{{ $data->_atasan->nama }}</td>
                                    <td>{{ $data->_atasan->nik }}</td>
                                    <td>{{ $atasan_property['divisi'] }}</td>
                                    <td>{{ $atasan_property['dep'] }}</td>
                                    <td>{{ $atasan_property['sub_dep'] }}</td>
                                    <td>{{ $data->_reviewer1->nama }}</td>
                                    <td>@if (!empty($data->nik_reviewer2)) {{ $data->_reviewer2->nama }} @endif</td>
                                    <td>@if (!empty($data->nik_reviewer3)) {{ $data->_reviewer3->nama }} @endif</td>
                                    <td>@if(!empty($data->terhitung_tanggal)) {{ \Carbon\Carbon::parse($data->terhitung_tanggal)->format('d-m-Y') }} @endif</td>
                                    <td>{{ $data->_status->status_pengajuan }}</td>
                                    <td>{{ $data->created_at }}</td>
                                    <td>@if ($data->status_rekomendasi==5 || $data->status_rekomendasi==10) {{ $data->updated_at }} @endif</td>
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
function pilih_atasan() {
    var nik_atasan = "{{ Crypt::encrypt(auth()->user()->nik) }}";
    @can('hc')
    if($('#nik_atasan').val() != '0'){
        nik_atasan = $('#nik_atasan').val();
    }
    @endcan
    location.href = "{{ route('rekomendasi.create') }}"+"?nik_atasan="+nik_atasan;
}
function toggle_status_rekomendasi(source) {
    checkboxes = document.getElementsByName('status_rekomendasi[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_nik_atasan(source) {
    checkboxes = document.getElementsByName('nik_atasan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_nik_karyawan(source) {
    checkboxes = document.getElementsByName('nik_karyawan[]');
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