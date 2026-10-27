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
                    <div class="table-responsive mt-2">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#ID</th>
                                    <th>Karyawan</th>
                                    <th>NIP Karyawan</th>
                                    <th>Direktorat</th>
                                    <th>Divisi</th>
                                    <th>Departemen</th>
                                    <th>Approval 1</th>
                                    <th>Approval 2</th>
                                    <th>Tipe</th>
                                    <th>Hari, Tanggal</th>
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
                                    <td>{{ $karyawan_property['nama'] }}</td>
                                    <td>{{ $karyawan_property['nik'] }}</td>
                                    <td>{{ $karyawan_property['divisi'] }}</td>
                                    <td>{{ $karyawan_property['dep'] }}</td>
                                    <td>{{ $karyawan_property['sub_dep'] }}</td>
                                    <td>{{ $atasan_property['nama'] }}</td>
                                    <td>@if(!empty($data->layer2)){{ $layer2_property['nama'] }}@endif</td>
                                    <td>
                                        {{ $data->_tipe->tipe }}
                                        @if($data->tipe == 1 || $data->tipe == 2)
                                        <br/>Jam {{ $data->jam_awal }} s/d {{ $data->jam_akhir }}
                                        @endif
                                    </td>
                                    <td>{{ App\Http\Controllers\TrxIjinController::getTanggalIndonesia($data->tanggal) }}</td>
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