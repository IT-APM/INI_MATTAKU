@extends('layouts.simple.master')
@section('title', 'Formulir Permohonan Izin Sakit')

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
<h3>Formulir Permohonan Izin Sakit</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Permohonan Izin Sakit</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('msg'))
                    <!-- <div class="alert alert-success">{{Session::get('msg')}}</div> -->
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if (count($errors) > 0)
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h6>Upload image is ERROR</h6>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @can('hc')
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <select class="form-select js-example-basic-single col-sm-12" id="nik_karyawan" name="nik_karyawan">
                                <option value="0">Pilih Karyawan...</option>
                                @foreach ($karyawan as $data)
                                <option value="{{ Crypt::encrypt($data->nik) }}">{{ $data->nama }} - {{ $data->nik }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endcan
                    <button class="btn btn-primary" type="button" onclick="PilihKaryawan()"><i class="fa fa-plus" aria-hidden="true"></i> Add new</button> | <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                    <div class="collapse" id="collapse_filter">
                        <form class="mt-2" action="{{ route('sakit.index') }}" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label class="h6" for="nama">Karyawan</label>
                                    <br/><input name="nama" id="nama" class="form-control" type="text" value="{{ $nama }}">
                                </div>
                                <div class="col-sm-4">
                                    <label class="h6">Tanggal Izin Sakit</label>
                                    <input class="form-control" name="date_from" id="date_from" type="date" required="" value="{{ $date_from }}">sampai
                                    <br/><input class="form-control" name="date_to" id="date_to" type="date" required="" value="{{ $date_to }}">
                                </div>
                                <div class="col-sm-4">
                                    <label class="h6">Status Pengajuan</label>&nbsp;<input type="checkbox" onClick="toggle_status_sakit(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="status_sakit">
                                        @foreach ($status_sakit as $data)
                                        <input class="checkbox_animated" id="status_sakit[]" name="status_sakit[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_status_sakit)) checked @endif> {{ $data->status_sakit }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- <br/><br/> --}}
                    <div class="table-responsive">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#ID</th>
                                    <th>Action</th>
                                    <th>Periode</th>
                                    <th>Karyawan</th>
                                    <th>NIP</th>
                                    <th>PIC Pengganti</th>
                                    <th>Tanggal Izin Sakit</th>
                                    <th>Status</th>
                                    <th>Created at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trx as $data)
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $data->id }}</td>
                                    <td>
                                        <a href="{{ route('sakit.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('detail')]) }}" data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        &nbsp;<a href="{{ route('sakit.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')]) }}" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                    </td>
                                    <td>{{ $data->periode_sakit }}</td>
                                    <td>{{ $data->_karyawan->nama }}</td>
                                    <td>{{ $data->_karyawan->nik }}</td>
                                    <td>
                                        @foreach (explode(';', $data->pic_property) as $value)
                                        {{ json_decode($value, true)['nama'] }} <br />
                                        @endforeach
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d-m-Y') }}<br />s/d<br />{{ \Carbon\Carbon::parse($data->tanggal_akhir)->format('d-m-Y') }}</td>
                                    <td>{{ $data->_status->status_sakit }}</td>
                                    <td>{{ $data->created_at }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <!-- <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Tipe</th>
                                    <th>Periode</th>
                                    <th>Karyawan</th>
                                    <th>NIP</th>
                                    <th>PIC Pengganti</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Status</th>
                                    <th>Sisa Cuti</th>
                                    <th>Created at</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot> -->
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
    var nik_karyawan = "{{ Crypt::encrypt(auth()->user()->nik) }}";
    @can('hc')
    if($('#nik_karyawan').val() != '0'){
        nik_karyawan = $('#nik_karyawan').val();
    }
    @endcan
    location.href = "{{ route('sakit.create') }}"+"?nik_karyawan="+nik_karyawan;
}
// 
function toggle_status_sakit(source) {
    checkboxes = document.getElementsByName('status_sakit[]');
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