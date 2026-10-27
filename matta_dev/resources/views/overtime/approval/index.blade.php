@extends('layouts.simple.master')
@section('title', 'Overtime')

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
<h3>Data Overtime</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Data</li>
<li class="breadcrumb-item active">Overtime</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('msg'))
                    <div class="alert alert-success">{{Session::get('msg')}}</div>
                    @endif
                    @can('spv')
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <select class="form-select js-example-basic-single col-sm-12" id="ot_maker_by" name="ot_maker_by">
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
                        <form class="mt-2" action="{{ route('approval-index') }}" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label class="h6">Start Date</label>
                                    <br/>From <input class="form-control" name="date_from" id="date_from" type="date" required="" value="{{ $date_from }}">
                                    To <input class="form-control" name="date_to" id="date_to" type="date" required="" value="{{ $date_to }}">
                                </div>
                                <div class="col-sm-4">
                                    <label class="h6" for="ot_maker_by">Maker By</label>
                                    <br/><input name="ot_maker_by" id="ot_maker_by" class="form-control" type="text" value="{{ $ot_maker_by }}">
                                    <br/><label class="h6" for="ot_approver_by">Approver By</label>
                                    <br/><input name="ot_approver_by" id="ot_approver_by" class="form-control" type="text" value="{{ $ot_approver_by }}">
                                    <br/>
                                </div>
                                <div class="col-sm-4">
                                    <label class="h6">Status</label>&nbsp;<input type="checkbox" onClick="toggle_ot_approver_status(this)" /> Check All
                                    <label class="d-block" for="ot_approver_status">
                                        @foreach ($ot_approver_status as $data)
                                        <input class="checkbox_animated" id="ot_approver_status[]" name="ot_approver_status[]" type="checkbox" value="{{ $data }}" @if(in_array($data, $arr_ot_approver_status)) checked @endif> {{ $data }}<br/>
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
                    <br/><br/>
                    <div class="table-responsive">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Created at</th>
                                    <th>Created by</th>
                                    <th>Created NIP</th>
                                    <th>Approved by</th>
                                    <th>Approved NIP</th>
                                    <th>Status</th>
                                    <th>Start</th>
                                    <th>Finish</th>
                                    <th>Title</th>
                                    <!-- <th>Desc</th> -->
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ot as $data)
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data->ot_maker_at) ->format('d-m-Y H:i:s') }}</td>
                                    <td>{{ $data->_ot_maker_by->nama }}</td>
                                    <td>{{ $data->ot_maker_by }}</td>
                                    <td>{{ $data->_ot_approver_by->nama }}</td>
                                    <td>{{ $data->ot_approver_by }}</td>
                                    <td>
                                        @if($data->ot_approver_status == 'Approve')
                                        <span class="badge badge-success">{{ $data->ot_approver_status }}</span>
                                        @elseif($data->ot_approver_status == 'Waiting')
                                        <span class="badge badge-warning">{{ $data->ot_approver_status }}</span>
                                        @else
                                        <span class="badge badge-danger">{{ $data->ot_approver_status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($data->ot_start_at) ->format('d-m-Y H:i:s') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data->ot_end_at) ->format('d-m-Y H:i:s') }}</td>
                                    <td>{{ $data->ot_title }}</td>
                                    <!-- <td>{{ $data->ot_desc }}</td> -->
                                    <td>{{ $data->_ot_type_code->ot_type }}</td>
                                    <td>
                                        <a href="{{ route('approval-review', [$data->id]) }}" data-toggle="tooltip" title="Review"><i class="icon-eye"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Created at</th>
                                    <th>Created by</th>
                                    <th>Approved by</th>
                                    <th>Status</th>
                                    <th>Start</th>
                                    <th>Finish</th>
                                    <th>Title</th>
                                    <!-- <th>Desc</th> -->
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
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
    // var ot_maker_by = "{{ Crypt::encrypt(auth()->user()->nik) }}";
    @can('spv')
    if($('#ot_maker_by').val() != '0'){
        ot_maker_by = $('#ot_maker_by').val();
        location.href = "{{ route('overtime.create') }}"+"?ot_maker_by="+ot_maker_by;
    }
    else {
        alert('Pilih Karyawan!');
    }
    @endcan
}
function toggle_ot_approver_status(source) {
    checkboxes = document.getElementsByName('ot_approver_status[]');
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