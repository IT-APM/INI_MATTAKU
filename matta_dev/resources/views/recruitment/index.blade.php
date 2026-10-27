@extends('layouts.simple.master')

@section('title', 'Recruitment')

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
<h3>List Of Recruitment</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Recruitment</li>
<li class="breadcrumb-item active">List Of Recruitment</li>
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
                    <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                    <div class="collapse" id="collapse_filter">
                        <form class="mt-2" action="{{ route('recruitment.index') }}" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-4">
                                    {{-- <label class="h6">Pengaju</label>&nbsp;<input type="checkbox" onClick="toggle_pengaju(this)" /> Check All
                                    <label class="d-block" for="pengaju">
                                        @foreach ($pengaju as $data)
                                        <input class="checkbox_animated" id="pengaju[]" name="pengaju[]" type="checkbox" value="{{ $data->nik }}" @if(in_array($data->nik, $arr_pengaju)) checked @endif> {{ $data->nama }} / {{ $data->nik }}<br/>
                                        @endforeach
                                    </label> --}}
                                    <label class="h6" for="pengaju">Pengaju</label>
                                    <select class="form-select js-example-basic-single col-sm-12" id="pengaju" name="pengaju" required="">
                                        {{-- <option selected="" disabled="" value="">Pilih...</option> --}}
                                        <option value="0" @if(in_array('0', $arr_pengaju)) selected @endif>Semua</option>
                                        @foreach ($pengaju as $data)
                                        <option value="{{ $data->nik }}" @if(in_array($data->nik, $arr_pengaju)) selected @endif>{{ $data->nama }} / {{ $data->nik }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback txt-danger">Field is required.</div>
                                </div>
                                <div class="col-sm-4">
                                    {{-- <label class="h6">Nama Jabatan</label>&nbsp;<input type="checkbox" onClick="toggle_id_pekerjaan(this)" /> Check All
                                    <label class="d-block" for="id_pekerjaan">
                                        @foreach ($pekerjaan as $data)
                                        <input class="checkbox_animated" id="id_pekerjaan[]" name="id_pekerjaan[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_id_pekerjaan)) checked @endif> {{ $data->pekerjaan }}<br/>
                                        @endforeach
                                    </label> --}}
                                    <label class="h6" for="id_pekerjaan">Nama Jabatan</label>
                                    <select class="form-select js-example-basic-single col-sm-12" id="id_pekerjaan" name="id_pekerjaan" required="">
                                        {{-- <option selected="" disabled="" value="">Pilih...</option> --}}
                                        <option value="0" @if(in_array('0', $arr_id_pekerjaan)) selected @endif>Semua</option>
                                        @foreach ($pekerjaan as $data)
                                        <option value="{{ $data->id }}" @if(in_array($data->id, $arr_id_pekerjaan)) selected @endif>{{ $data->pekerjaan }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback txt-danger">Field is required.</div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="h6">Jenis</label>&nbsp;<input type="checkbox" onClick="toggle_id_status_kerja(this)" /> Check All
                                    <label class="d-block" for="id_status_kerja">
                                        @foreach ($status_kerja as $data)
                                        <input class="checkbox_animated" id="id_status_kerja[]" name="id_status_kerja[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_id_status_kerja)) checked @endif> {{ $data->status_kerja }}<br/>
                                        @endforeach
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="{{ route('recruitment.index') }}" class="btn btn-info" type="button"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#ID</th>
                                    <th>Action</th>
                                    <th>Area Kerja</th>
                                    <th>Area Kerja Kota</th>
                                    <th>Pengaju</th>
                                    <th>NIP</th>
                                    <th>Nama Jabatan</th>
                                    <th>Atasan Langsung Kandidat</th>
                                    <th>Jenis</th>
                                    <th>Tanggal Mulai Kerja</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no=0; @endphp
                                @foreach ($trx as $data)
                                @php
                                    $trxM = App\Models\TrxKebutuhanMaster::find($data->id);
                                    $atasan_property = $trxM->atasan_property;
                                    $atasan_property = json_decode($atasan_property, true);
                                    // 
                                    $where = ['id_kebutuhan' => $data->id];
                                    $recruitment = App\Models\TrxRecruitment::where($where)->orderBy('id', 'desc')->first();
                                @endphp
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $data->id }}</td>
                                    <td>
                                        <a href="{{ route('recruitment.show', [Crypt::encrypt(array($data->id, 1))]) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a>
                                        &nbsp;<a href="{{ route('kebutuhan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')]) }}" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        @if(!empty($recruitment))<br/><a href="{{ route('recruitment.apply', [Crypt::encrypt(array($recruitment->id, 'apply'))]) }}" data-toggle="tooltip" title="Apply" target="_blank"><i class="icon-eye"></i></a>@endif
                                    </td>
                                    <td>{{ $data->_area->propinsi }}</td>
                                    <td>@if (!empty($data->id_kota)) {{ $data->_kota->kota }} @endif</td>
                                    <td>{{ $data->_pengaju->nama }}</td>
                                    <td>{{ $data->_pengaju->nik }}</td>
                                    <td>{{ $data->_pekerjaan->pekerjaan }}</td>
                                    <td>@if(!empty($atasan_property)) {{ $atasan_property['nama'] }}/{{ $atasan_property['nik'] }} @endif</td>
                                    <td>{{ $data->_status_kerja->status_kerja }}</td>
                                    <td>{{ Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggal)->format('d-m-Y') }}</td>
                                    <td>
                                        @if(!empty($recruitment))
                                        <a class="btn btn-success" href="{{ route('recruitment.show', [Crypt::encrypt(array($data->id, $recruitment->id_status))]) }}" data-toggle="tooltip" title="{{ $recruitment->_status->status }}" target="_blank">{{ $recruitment->_status->status }}</a>
                                        @else
                                        <a class="btn btn-info" href="{{ route('recruitment.show', [Crypt::encrypt(array($data->id, 1))]) }}" data-toggle="tooltip" title="Process">Process</a>
                                        @endif
                                    </td>
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
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script type="text/javascript">
// 
$('#_datatable').DataTable({
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
});
function toggle_pengaju(source) {
    checkboxes = document.getElementsByName('pengaju[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_id_pekerjaan(source) {
    checkboxes = document.getElementsByName('id_pekerjaan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_id_status_kerja(source) {
    checkboxes = document.getElementsByName('id_status_kerja[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
</script>
@endsection