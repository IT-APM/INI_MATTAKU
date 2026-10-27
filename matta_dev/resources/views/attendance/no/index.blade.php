@extends('layouts.simple.master')
@section('title', 'No Attendance')

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
<h3>No Attendance Log</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">No Attendance</li>
<li class="breadcrumb-item active">Log</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- State saving Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                        <div class="collapse" id="collapse_filter">
                            <form class="m-4" action="{{ route('noattendance.index') }}" method="get" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label class="h6">Date</label>
                                        <br/>From <input class="form-control" name="date_from" id="date_from" type="date" required="" value="{{ $date_from }}">
                                        To <input class="form-control" name="date_to" id="date_to" type="date" required="" value="{{ $date_to }}">
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="h6">Aktif/Non Aktif</label>&nbsp;<input type="checkbox" onClick="toggle_is_aktif(this)" /> Check All
                                        <label class="d-block" for="is_aktif">
                                            @foreach ($is_aktif as $data)
                                            <input class="checkbox_animated" id="is_aktif[]" name="is_aktif[]" type="checkbox" value="{{ $data['id'] }}" @if(in_array($data['id'], $arr_is_aktif)) checked @endif> {{ $data['is_aktif'] }}<br/>
                                            @endforeach
                                        </label>
                                    </div>
                                </div>
                                <br/>
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
                                        <th>Tanggal Awal</th>
                                        <th>Tanggal Akhir</th>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>NIP Layer 1</th>
                                        <th>Nama Layer 1</th>
                                        <th>Email Layer 1</th>
                                        <th>Perusahaan</th>
                                        <th>Direktorat</th>
                                        <th>Divisi</th>
                                        <th>Departemen</th>
                                        <th>Aktif/Non Aktif</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($trx as $data)
                                    <tr>
                                       <td>{{ ++$no }}</td>
                                       <td>{{ \Carbon\Carbon::parse($date_from) ->format('d-m-Y') }}</td>
                                       <td>{{ \Carbon\Carbon::parse($date_to) ->format('d-m-Y') }}</td>
                                       <td>{{ $data->nik }}</td>
                                       <td>{{ $data->nama }}</td>
                                       <td>{{ $data->email }}</td>
                                       <td>@if (!empty($data->_layer1)) {{ $data->layer1 }} @endif</td>
                                       <td>@if (!empty($data->_layer1)) {{ $data->_layer1->nama }} @endif</td>
                                       <td>@if (!empty($data->_layer1)) {{ $data->_layer1->email }} @endif</td>
                                       <td>{{ $data->_perusahaan->nama }}</td>
                                       <td>{{ $data->_divisi->nama }}</td>
                                       <td>{{ $data->_departemen->nama }}</td>
                                       <td>{{ $data->_subdepartemen->nama }}</td>
                                       <td>
                                            @if($data->is_aktif == 1)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-danger">Non Aktif</span>
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
function toggle_is_aktif(source) {
    checkboxes = document.getElementsByName('is_aktif[]');
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
