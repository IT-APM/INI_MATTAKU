@extends('layouts.simple.master')
@section('title', 'Attendance')

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
<h3>Attendance Duration</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Attendance</li>
<li class="breadcrumb-item active">Attendance Duration</li>
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
                            <form class="mt-2" action="{{ route('attendance.index') }}" method="get" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label class="h6">Date</label>
                                        <br/>From <input class="form-control" name="date_from" id="date_from" type="date" required="" value="{{ $date_from }}">
                                        To <input class="form-control" name="date_to" id="date_to" type="date" required="" value="{{ $date_to }}">
                                        <br/>
                                        <label class="h6">Working Group</label>&nbsp;<input type="checkbox" onClick="toggle_wg_id(this)" /> Check All
                                        <label class="d-block" for="wg_id">
                                            @foreach ($wg_id as $data)
                                            <input class="checkbox_animated" id="wg_id[]" name="wg_id[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_wg_id)) checked @endif> {{ $data->wg_name }}<br/>
                                            @endforeach
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="h6">Action</label>&nbsp;<input type="checkbox" onClick="toggle_att_type(this)" /> Check All
                                        <label class="d-block" for="att_type">
                                            @foreach ($att_type as $data)
                                            <input class="checkbox_animated" id="att_type[]" name="att_type[]" type="checkbox" value="{{ $data }}" @if(in_array($data, $arr_att_type)) checked @endif> {{ $data }}<br/>
                                            @endforeach
                                        </label>
                                        <label class="h6">Status</label>&nbsp;<input type="checkbox" onClick="toggle_att_status(this)" /> Check All
                                        <label class="d-block" for="att_status">
                                            @foreach ($att_status as $data)
                                            <input class="checkbox_animated" id="att_status[]" name="att_status[]" type="checkbox" value="{{ $data }}" @if(in_array($data, $arr_att_status)) checked @endif> {{ $data }}<br/>
                                            @endforeach
                                        </label>
                                        <label class="h6">Type</label>&nbsp;<input type="checkbox" onClick="toggle_att_data_src(this)" /> Check All
                                        <label class="d-block" for="att_data_src">
                                            @foreach ($att_data_src as $data)
                                            <input class="checkbox_animated" id="att_data_src[]" name="att_data_src[]" type="checkbox" value="{{ $data }}" @if(in_array($data, $arr_att_data_src)) checked @endif> {{ $data }}<br/>
                                            @endforeach
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="h6">Company</label>&nbsp;<input type="checkbox" onClick="toggle_perusahaan(this)" /> Check All
                                        <label class="d-block" for="perusahaan">
                                            @foreach ($perusahaan as $data)
                                            <input class="checkbox_animated" id="perusahaan[]" name="perusahaan[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_perusahaan)) checked @endif> {{ $data->nama }}<br/>
                                            @endforeach
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
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
                                        <th>Tanggal</th>
                                        <th>Timestamp</th>
                                        <th>Nama</th>
                                        <th>NIP</th>
                                        <th>Perusahaan</th>
                                        <th>Direktorat</th>
                                        <th>Divisi</th>
                                        <th>Departemen</th>
                                        <th>Working Group</th>
                                        <th>Action</th>
                                        <th>Longitude</th>
                                        <th>Latitude</th>
                                        <th>Address</th>
                                        <th>Map</th>
                                        <th>Status</th>
                                        <th>Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($att as $data)
                                    <tr>
                                       <td>{{ ++$no }}</td>
                                       <td>{{ \Carbon\Carbon::parse($data->att_date) ->format('d-m-Y') }}</td>
                                       <td>{{ \Carbon\Carbon::parse($data->att_timestamp) ->format('d-m-Y H:i:s') }}</td>
                                       <td>{{ $data->_att_nik->nama }}</td>
                                       <td>{{ $data->att_nik }}</td>
                                       <td>{{ $data->_att_nik->_perusahaan->nama }}</td>
                                       <td>{{ $data->_att_nik->_divisi->nama }}</td>
                                       <td>{{ $data->_att_nik->_departemen->nama }}</td>
                                       <td>{{ $data->_att_nik->_subdepartemen->nama }}</td>
                                       <td>{{ $data->_att_nik->_wg->wg_name }}</td>
                                       <td>
                                            @if($data->att_type == 'Clock-in')
                                                <span class="badge rounded-pill badge-light-primary">{{ $data->att_type }}</span>
                                            @elseif ($data->att_type == 'Clock-out')
                                                <span class="badge rounded-pill badge-light-success">{{ $data->att_type }}</span>
                                            @else
                                                <span class="badge rounded-pill badge-light-warning">{{ $data->att_type }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $data->att_long }}</td>
                                        <td>{{ $data->att_lat }}</td>
                                        <td>{{ $data->att_address }}</td>
                                        <td>@if(!empty($data->att_long) && !empty($data->att_lat))<a href="https://www.google.com/maps/place/{{ $data->att_lat }},{{ $data->att_long }}" target="_blank"><i class="fa fa-map-marker" style="font-size:25px;color:red;"></i></a>@endif</td>
                                        <td>
                                            @if($data->att_status == 'Book-in' || $data->att_status == 'Book-out')
                                                <span class="badge badge-success">{{ $data->att_status }}</span>
                                            @elseif ($data->att_status == 'Leave')
                                                <span class="badge badge-warning">{{ $data->att_status }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ $data->att_status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($data->att_data_src == 'Regular')
                                                <span class="badge badge-success">{{ $data->att_data_src }}</span>
                                            @elseif ($data->att_data_src == 'Overtime')
                                                <span class="badge badge-primary">{{ $data->att_data_src }}</span>
                                            @else
                                                <span class="badge badge-warning">{{ $data->att_data_src }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <!-- <tfoot>
                                    <tr>
                                        <th>No  </th>
                                        <th>Timestamp</th>
                                        <th>Nama</th>
                                        <th>Action</th>
                                        <th>Longitude</th>
                                        <th>Latitude</th>
                                        <th>Status</th>
                                        <th>Type</th>
                                    </tr>
                                </tfoot> -->
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
function toggle_att_type(source) {
    checkboxes = document.getElementsByName('att_type[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_att_status(source) {
    checkboxes = document.getElementsByName('att_status[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_att_data_src(source) {
    checkboxes = document.getElementsByName('att_data_src[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_perusahaan(source) {
    checkboxes = document.getElementsByName('perusahaan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_wg_id(source) {
    checkboxes = document.getElementsByName('wg_id[]');
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
