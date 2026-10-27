@extends('layouts.simple.master')

@section('title', 'Formulir Izin')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
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
<h3>Formulir Izin</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Izin</li>
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
                    <a href="{{ route('izin.create') }}" class="btn btn-primary" type="button"><i class="fa fa-plus" aria-hidden="true"></i> Add new</a> | <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> @can('hc') | <a href="{{ route('izin.recap', 0) }}" class="btn btn-success" type="button"><i class="fa fa-list" aria-hidden="true"></i> Recap</a> @endcan
                    <div class="collapse" id="collapse_filter">
                        <form class="mt-2" action="{{ route('izin.index') }}" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="h6 mt-2">Tanggal Izin</label>
                                    <br/><input class="form-control" name="date_from" id="date_from" type="date" required="" value="{{ $date_from }}">
                                    sampai <input class="form-control" name="date_to" id="date_to" type="date" required="" value="{{ $date_to }}">
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Status Pengajuan</label>&nbsp;<input type="checkbox" onClick="toggle_status(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="status">
                                        @foreach ($status as $data)
                                        <input class="checkbox_animated" id="status[]" name="status[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_status)) checked @endif> {{ $data->status }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Tipe Izin</label>&nbsp;<input type="checkbox" onClick="toggle_tipe(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="tipe">
                                        @foreach ($tipe as $data)
                                        <input class="checkbox_animated" id="tipe[]" name="tipe[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_tipe)) checked @endif> {{ $data->tipe }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Aktif/Non Aktif</label>&nbsp;<input type="checkbox" onClick="toggle_is_aktif(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="is_aktif">
                                        @foreach ($is_aktif as $data)
                                            <input class="checkbox_animated" id="is_aktif[]" name="is_aktif[]" type="checkbox" value="{{ $data['id'] }}" @if (in_array($data['id'], $arr_is_aktif)) checked @endif>
                                            {{ $data['is_aktif'] }}<br />
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive mt-2">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#ID</th>
                                    <th>Action</th>
                                    <th>Karyawan</th>
                                    <th>NIP Karyawan</th>
                                    <th>Direktorat</th>
                                    <th>Divisi</th>
                                    <th>Departemen</th>
                                    <th>Approval 1</th>
                                    <th>Approval 2</th>
                                    <th>Tipe</th>
                                    <th>Hari</th>
                                    <th>Tanggal</th>
                                    <th>Alasan</th>
                                    <th>Status</th>
                                    <th>Created at</th>
                                </tr>
                            </thead>
                            @php $no = 0; @endphp
                            <tbody>
                                @foreach ($trx as $data)
                                @php
                                    $karyawan_property = json_decode($data['karyawan_property'], true);
                                    $atasan_property = json_decode($data['atasan_property'], true);
                                    $layer2_property = json_decode($data['layer2_property'], true);
                                @endphp
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $data->id }}</td>
                                    <td>
                                        <a href="{{ route('izin.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('detail')]) }}" data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        &nbsp;<a href="{{ route('izin.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')]) }}" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                    </td>
                                    <td>{{ $karyawan_property['nama'] }}</td>
                                    <td>{{ $karyawan_property['nik'] }}</td>
                                    <td>{{ $karyawan_property['divisi'] }}</td>
                                    <td>{{ $karyawan_property['dep'] }}</td>
                                    <td>{{ $karyawan_property['sub_dep'] }}</td>
                                    <td>{{ $atasan_property['nama'] }}</td>
                                    <td>@if(!empty($data->layer2)){{ $layer2_property['nama'] }}@endif</td>
                                    <td>
                                        {{ $data->_tipe->tipe }}
                                        @if($data->tipe == 2)
                                        <br/>Jam {{ $data->jam_awal }} s/d {{ $data->jam_akhir }}
                                        @endif
                                    </td>
                                    {{-- <td>{{ App\Http\Controllers\TrxIjinController::getTanggalIndonesia($data->tanggal) }}</td> --}}
                                    @php
                                        $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                    @endphp
                                    <td>{{ $hari[\Carbon\Carbon::parse($data->tanggal)->dayOfWeek] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data->tanggal)->format('d-m-Y') }}</td>
                                    <td>{!! $data->alasan !!}</td>
                                    <td>{{ $data->_status->status }}</td>
                                    <td>{{ $data->created_at }}</td>
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
<script type="text/javascript">
    function toggle_is_aktif(source) {
        checkboxes = document.getElementsByName('is_aktif[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }
function toggle_status(source) {
    checkboxes = document.getElementsByName('status[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_tipe(source) {
    checkboxes = document.getElementsByName('tipe[]');
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