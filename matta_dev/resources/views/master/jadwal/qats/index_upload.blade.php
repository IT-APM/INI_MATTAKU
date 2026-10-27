@extends('layouts.simple.master')
@section('title', 'Jadwal')

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
<h3>Jadwal QA TS Group</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Jadwal</li>
<li class="breadcrumb-item active">QA TS Group</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- State saving Starts-->
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
                        <div class="col-sm-6 mt-2">
                            <h6>Upload Jadwal</h6>
                            <form action="{{ route('working.upload_jadwal', 15) }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to upload this data?');">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <input name="template" id="template" class="form-control" type="file" required="">
                                    <label class="form-label">Maksimal 2 MB dengan format xls/xlsx</label>
                                    <br/><a href="{{ asset('assets/template/template_qats_group_v1.xlsx') }}" target="_blank"><i class="icon-file"></i>&nbsp;Download Template</a>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload</button>
                                </div>
                            </div>
                            </form>
                        </div>
                        <button class="btn btn-warning mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                        <div class="collapse" id="collapse_filter">
                            <form class="mt-2" action="{{ route('working.show', 15) }}" method="get" enctype="multipart/form-data">
                                <input type="hidden" id="req" name="req" value="view">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label class="h6">Tanggal</label>
                                        <br/>Dari <input class="form-control" name="date_from" id="date_from" type="date" required="" value="{{ $date_from }}">
                                        Sampai <input class="form-control" name="date_to" id="date_to" type="date" required="" value="{{ $date_to }}">
                                        <br/>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="h6">Nama Karyawan</label>&nbsp;<input type="checkbox" onClick="toggle_nik_karyawan(this)" /> Check All
                                        <label class="d-block" for="nik_karyawan">
                                            @foreach ($nik_karyawan as $data)
                                            <input class="checkbox_animated" id="nik_karyawan[]" name="nik_karyawan[]" type="checkbox" value="{{ $data->nik }}" @if(in_array($data->nik, $arr_nik_karyawan)) checked @endif> {{ $data->nama }}/{{ $data->nik }}<br/>
                                            @endforeach
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="{{ route('working.show', [15, 'req'=>'view']) }}" class="btn btn-info"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive mt-2">
                            <table class="display" id="_datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama/NIP</th>
                                        <th>Tanggal</th>
                                        <th>Clock in</th>
                                        <th>Clock out</th>
                                        <th>Mulai Clock in</th>
                                        <th>Akhir Clock in</th>
                                        <th>Mulai Clock out</th>
                                        <th>Akhir Clock out</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php $no=0; @endphp
                                @foreach ($trx as $data)
                                    <tr>
                                       <td>{{ ++$no }}</td>
                                       <td>{{ $data->_nik->nama }} / {{ $data->nik }}</td>
                                       <td>{{ \Carbon\Carbon::parse($data->working_date)->format('d-m-Y') }}</td>
                                       <td>{{ \Carbon\Carbon::parse($data->clockin)->format('d-m-Y H:i:s') }}</td>
                                       <td>{{ \Carbon\Carbon::parse($data->clockout)->format('d-m-Y H:i:s') }}</td>
                                       <td>{{ \Carbon\Carbon::parse($data->start_clockin)->format('d-m-Y H:i:s') }}</td>
                                       <td>{{ \Carbon\Carbon::parse($data->end_clockin)->format('d-m-Y H:i:s') }}</td>
                                       <td>{{ \Carbon\Carbon::parse($data->start_clockout)->format('d-m-Y H:i:s') }}</td>
                                       <td>{{ \Carbon\Carbon::parse($data->end_clockout)->format('d-m-Y H:i:s') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- State saving Ends-->
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
function toggle_nik_karyawan(source) {
    checkboxes = document.getElementsByName('nik_karyawan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
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
