@extends('layouts.simple.master')
@section('title', 'Overtime Reporting')

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
<h3>Overtime Reporting</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Reporting</li>
<li class="breadcrumb-item active">Overtime</li>
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
                    @can('staf_only')
                    {{-- <a href="{{ route('overtime.create') }}" class="btn btn-primary" type="button"><i class="fa fa-plus" aria-hidden="true"></i> Add new</a> |  --}}
                    <button class="btn btn-primary" type="button" onclick="PilihKaryawan()"><i class="fa fa-plus" aria-hidden="true"></i> Add new</button> |
                    @endcan
                    <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                    <div class="collapse" id="collapse_filter">
                        <form class="mt-2" action="{{ route('reporting-index') }}" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="h6">Start Date</label>
                                    <br/>From <input class="form-control" name="date_from" id="date_from" type="date" required="" value="{{ $date_from }}">
                                    To <input class="form-control" name="date_to" id="date_to" type="date" required="" value="{{ $date_to }}">
                                    {{-- <br/> --}}
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6" for="ot_maker_by">Maker By</label>
                                    <br/><input name="ot_maker_by" id="ot_maker_by" class="form-control" type="text" value="{{ $ot_maker_by }}">
                                    <br/><label class="h6" for="ot_approver_by">Approver By</label>
                                    <br/><input name="ot_approver_by" id="ot_approver_by" class="form-control" type="text" value="{{ $ot_approver_by }}">
                                    {{-- <br/> --}}
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Status</label>&nbsp;<input type="checkbox" onClick="toggle_ot_approver_status(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="ot_approver_status">
                                        @foreach ($ot_approver_status as $data)
                                        <input class="checkbox_animated" id="ot_approver_status[]" name="ot_approver_status[]" type="checkbox" value="{{ $data }}" @if(in_array($data, $arr_ot_approver_status)) checked @endif> {{ $data }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Company</label>&nbsp;<input type="checkbox" onClick="toggle_perusahaan(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="perusahaan">
                                        @foreach ($perusahaan as $data)
                                        <input class="checkbox_animated" id="perusahaan[]" name="perusahaan[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_perusahaan)) checked @endif> {{ $data->nama }}<br/>
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
                    <ul class="nav nav-tabs nav-primary mt-2" id="pills-warningtab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="rawdata-tab" data-bs-toggle="pill" href="#rawdata" role="tab" aria-controls="rawdata" aria-selected="false">Raw Data</a></li>
                        <li class="nav-item"><a class="nav-link" id="recapdata-tab" data-bs-toggle="pill" href="#recapdata" role="tab" aria-controls="recapdata" aria-selected="true">Recap</a></li>
                    </ul>
                    <div class="tab-content" id="pills-warningtabContent">
                        {{-- rawdata --}}
                        <div class="tab-pane fade show active" id="rawdata" role="tabpanel" aria-labelledby="rawdata-tab">
                            <div class="table-responsive mt-2">
                                <table class="display" id="raw_datatable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>#ID</th>
                                            <th>Adj</th>
                                            <th>Company</th>
                                            <th>Directorate</th>
                                            <th>Division</th>
                                            <th>Department</th>
                                            <th>Maker Name</th>
                                            <th>Maker NIP</th>
                                            <th>Approve Name</th>
                                            <th>Approve NIP</th>
                                            <th>Maker at</th>
                                            <th>Approve at</th>
                                            <th>Status</th>
                                            <th>Title</th>
                                            <th>Desc</th>
                                            <th>Type</th>
                                            <th>Start Plan</th>
                                            <th>Finish Plan</th>
                                            <th>Length Plan</th>
                                            <th>Start Real</th>
                                            <th>Finish Real</th>
                                            <th>Length Real</th>
                                            <th>Start Rev</th>
                                            <th>Finish Rev</th>
                                            <th>Length Rev</th>
                                            <th>Basic Sallary</th>
                                            <th>Rp Rev</th>
                                            <th>Rp Rev (Round)</th>
                                            <th>Length Adj</th>
                                            <!-- <th>Length Adj (Seconds)</th> -->
                                            <th>Rp Adj</th>
                                            <th>Rp Adj (Round)</th>
                                            <th>Employee Status</th>
                                            <th>Working Group</th>
                                            <th>Day</th>
                                            <!-- <th>Adj</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 0; @endphp
                                        @foreach ($ot as $data)
                                        @php
                                            $_data = json_decode($data->ot_maker_property, true);
                                            $_day = App\Http\Controllers\OvertimeController::isWeekDayWeekEndHoliday($data->ot_start_at, $data->ot_maker_by, $_data['is_reguler'], $_data['wg_id']);
                                            $_isHoliday = App\Http\Controllers\OvertimeController::isHoliday(\Carbon\Carbon::parse($data->ot_start_at)->format('Y-m-d'))['isHoliday'];
                                            $_isWeekend = App\Http\Controllers\OvertimeController::isWeekend(\Carbon\Carbon::parse($data->ot_start_at)->format('Y-m-d'))['isWeekend'];
                                            $_arr = json_decode($data->ot_freelance_property, true);
                                        @endphp
                                        <tr>
                                            <td>{{ ++$no }}</td>
                                            <td>{{ $data->id }}</td>
                                            <td><a href="{{ route('adj_ot', [$data->id]) }}" data-toggle="tooltip" title='Adjustment'> <i class="icon-pencil-alt"></i></a></td>
                                            <td>{{ $_data['perusahaan'] }}</td>
                                            <td>{{ $_data['divisi'] }}</td>
                                            <td>{{ $_data['dep'] }}</td>
                                            <td>{{ $_data['sub_dep'] }}</td>
                                            <td>{{ $data->_ot_maker_by->nama }}</td>
                                            <td>{{ $data->ot_maker_by }}</td>
                                            <td>{{ $data->_ot_approver_by->nama }}</td>
                                            <td>{{ $data->ot_approver_by }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->ot_maker_at)->format('d-m-Y H:i:s') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->ot_approver_at)->format('d-m-Y H:i:s') }}</td>
                                            <td>
                                                @if($data->ot_approver_status == 'Approve')
                                                <span class="badge badge-success">{{ $data->ot_approver_status }}</span>
                                                @elseif($data->ot_approver_status == 'Waiting')
                                                <span class="badge badge-warning">{{ $data->ot_approver_status }}</span>
                                                @else
                                                <span class="badge badge-danger">{{ $data->ot_approver_status }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $data->ot_title }}</td>
                                            <td>{{ $data->ot_desc }}</td>
                                            <td>{{ $data->_ot_type_code->ot_type }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->ot_start_at)->format('d-m-Y H:i:s') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->ot_end_at)->format('d-m-Y H:i:s') }}</td>
                                            {{-- <td>{{ \Carbon\CarbonInterval::seconds($data->ot_length)->cascade()->forHumans() }}</td> --}}
                                            {{-- <td>{{ gmdate('H:i:s', $data->ot_length) }}</td> --}}
                                            <td>{{ sprintf('%02d:%02d:%02d', floor($data->ot_length / 3600), floor(($data->ot_length % 3600) / 60), $data->ot_length % 60) }}</td>
                                            <td>@if(!empty($data->ot_start_at_real)) {{ \Carbon\Carbon::parse($data->ot_start_at_real)->format('d-m-Y H:i:s') }} @endif</td>
                                            <td>@if(!empty($data->ot_end_at_real)) {{ \Carbon\Carbon::parse($data->ot_end_at_real)->format('d-m-Y H:i:s') }} @endif</td>
                                            <td>@if(!empty($data->ot_length_real)) {{ $data->ot_length_real }} @endif</td>
                                            <td>@if(!empty($data->ot_start_at_rev)) {{ \Carbon\Carbon::parse($data->ot_start_at_rev)->format('d-m-Y H:i:s') }} @endif</td>
                                            <td>@if(!empty($data->ot_end_at_rev)) {{ \Carbon\Carbon::parse($data->ot_end_at_rev)->format('d-m-Y H:i:s') }} @endif</td>
                                            <td>@if(!empty($data->ot_length_rev)) {{ $data->ot_length_rev }} @endif</td>
                                            <td class="text-end">@if($_data['status_karyawan']=='Freelance') @if($_isHoliday||$_isWeekend) {{ number_format($_arr[1]['informasi']) }} @else {{ number_format($_arr[0]['informasi']) }} @endif @else {{ number_format($_data['gaji_pokok']) }} @endif</td>
                                            <td class="text-end">{{ number_format($data->ot_length_rp) }}</td>
                                            <td class="text-end">{{ number_format(round($data->ot_length_rp, -3)) }}</td>
                                            {{-- <td>@if(!empty($data->ot_length_adj)) {{ \Carbon\CarbonInterval::seconds($data->ot_length_adj)->cascade()->forHumans() }} @endif</td> --}}
                                            {{-- <td>@if(!empty($data->ot_length_adj)) {{ gmdate('H:i:s', $data->ot_length_adj) }} @endif</td> --}}
                                            <td>@if(!empty($data->ot_length_adj)) {{ sprintf('%02d:%02d:%02d', floor($data->ot_length_adj / 3600), floor(($data->ot_length_adj % 3600) / 60), $data->ot_length_adj % 60) }} @endif</td>
                                            <!-- <td>@if(!empty($data->ot_length_adj)) <a href="{{ route('adj_ot', [$data->id]) }}" data-toggle="tooltip" title='Adjustment'>{{ number_format($data->ot_length_adj) }} <i class="icon-pencil-alt"></i></a> @endif</td> -->
                                            <td class="text-end">{{ number_format($data->ot_length_rp_adj) }}</td>
                                            <td class="text-end">{{ number_format(round($data->ot_length_rp_adj, -3)) }}</td>
                                            <td>{{ $_data['status_karyawan'] }}</td>
                                            <td>{{ $_data['wg_name'] }}</td>
                                            <td>{!! $_day !!}</td>
                                            <!-- <td><a href="{{ route('adj_ot', [$data->id]) }}" data-toggle="tooltip" title='Adjustment'> <i class="icon-pencil-alt"></i></a></td> -->
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- recapdata --}}
                        @php $max = App\Models\MtrUtility::find(33)->informasi @endphp
                        <div class="tab-pane fade" id="recapdata" role="tabpanel" aria-labelledby="recapdata-tab">
                            <div class="table-responsive mt-2">
                                <table class="display" id="recap_datatable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Company</th>
                                            <th>Directorate</th>
                                            <th>Division</th>
                                            <th>Department</th>
                                            <th>Maker Name</th>
                                            <th>Maker NIP</th>
                                            <th>Approve Name</th>
                                            <th>Rp Adj (Round)</th>
                                            <th>Rp Max Value</th>
                                            <th>Employee Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 0; @endphp
                                        @foreach ($otrecap as $data)
                                        @php $_data = json_decode($data->ot_maker_property, true) @endphp
                                        <tr>
                                            <td>{{ ++$no }}</td>
                                            <td>{{ $_data['perusahaan'] }}</td>
                                            <td>{{ $_data['divisi'] }}</td>
                                            <td>{{ $_data['dep'] }}</td>
                                            <td>{{ $_data['sub_dep'] }}</td>
                                            <td>{{ $data->_ot_maker_by->nama }}</td>
                                            <td>{{ $data->ot_maker_by }}</td>
                                            <td>{{ $data->_ot_approver_by->nama }}</td>
                                            <td class="text-end">{{ number_format(round($data->ot_length_rp_adj, -3)) }}</td>
                                            <td class="text-end">@if($max > round($data->ot_length_rp_adj, -3)){{ number_format(round($data->ot_length_rp_adj, -3)) }}@else {{ number_format($max) }} @endif</td>
                                            <td>{{ $_data['status_karyawan'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{--  --}}
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
function PilihKaryawan() {
    var ot_maker_by = "{{ Crypt::encrypt(auth()->user()->nik) }}";
    location.href = "{{ route('overtime.create') }}"+"?ot_maker_by="+ot_maker_by;
}
function toggle_ot_approver_status(source) {
    checkboxes = document.getElementsByName('ot_approver_status[]');
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
// 
$('#raw_datatable').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
} );
// 
$('#recap_datatable').DataTable( {
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