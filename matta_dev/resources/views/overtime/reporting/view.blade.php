@extends('layouts.simple.master')

@section('title', 'Overtime Adjustment')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Adjustment</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Overtime</li>
<li class="breadcrumb-item active">Adjustment</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            <div class="row">
                @php
                $_data = json_decode($data->ot_maker_property, true);
                $_day = App\Http\Controllers\OvertimeController::isWeekDayWeekEndHoliday($data->ot_start_at, $data->ot_maker_by, $_data['is_reguler'], $_data['wg_id']);
                $_isHoliday = App\Http\Controllers\OvertimeController::isHoliday(\Carbon\Carbon::parse($data->ot_start_at)->format('Y-m-d'))['isHoliday'];
                $_isWeekend = App\Http\Controllers\OvertimeController::isWeekend(\Carbon\Carbon::parse($data->ot_start_at)->format('Y-m-d'))['isWeekend'];
                $_arr = json_decode($data->ot_freelance_property, true);
                @endphp
                <!--  -->
                @if(Session::has('msg'))
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Company</td>
                                        <td width="60%">{{ $_data['perusahaan'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Division</td>
                                        <td>{{ $_data['divisi'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Department</td>
                                        <td>{{ $_data['dep'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Sub Department</td>
                                        <td>{{ $_data['sub_dep'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Maker by</td>
                                        <td width="60%">{{ $data->_ot_maker_by->nama }} / {{ $data->ot_maker_by }}</td>
                                    </tr>
                                    <tr>
                                        <td>Approve by</td>
                                        <td>{{ $data->_ot_approver_by->nama }} / {{ $data->ot_approver_by }}</td>
                                    </tr>
                                    <tr>
                                        <td>Maker at</td>
                                        <td>{{ \Carbon\Carbon::parse($data->ot_maker_at)->format('d-m-Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Approve at</td>
                                        <td>{{ \Carbon\Carbon::parse($data->ot_approver_at)->format('d-m-Y H:i:s') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Status</td>
                                        <td width="60%">
                                            @if($data->ot_approver_status == 'Approve')
                                            <span class="badge badge-success">{{ $data->ot_approver_status }}</span>
                                            @elseif($data->ot_approver_status == 'Waiting')
                                            <span class="badge badge-warning">{{ $data->ot_approver_status }}</span>
                                            @else
                                            <span class="badge badge-danger">{{ $data->ot_approver_status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Title</td>
                                        <td>{{ $data->ot_title }}</td>
                                    </tr>
                                    <tr>
                                        <td>Desc</td>
                                        <td>{{ $data->ot_desc }}</td>
                                    </tr>
                                    <tr>
                                        <td>Type</td>
                                        <td>{{ $data->_ot_type_code->ot_type }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Start Plan</td>
                                        <td width="60%">{{ \Carbon\Carbon::parse($data->ot_start_at)->format('d-m-Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Finish Plan</td>
                                        <td>{{ \Carbon\Carbon::parse($data->ot_end_at)->format('d-m-Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Length Plan</td>
                                        <!-- <td>{{ \Carbon\CarbonInterval::seconds($data->ot_length)->cascade()->forHumans() }}</td> -->
                                        <td>{{ gmdate('H:i:s', $data->ot_length) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Start Real</td>
                                        <td width="60%">
                                        @if(!empty($data->ot_start_at_real)) {{ \Carbon\Carbon::parse($data->ot_start_at_real)->format('d-m-Y H:i:s') }} @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Finish Real</td>
                                        <td>
                                        @if(!empty($data->ot_end_at_real)) {{ \Carbon\Carbon::parse($data->ot_end_at_real)->format('d-m-Y H:i:s') }} @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Length Real</td>
                                        <td>
                                        @if(!empty($data->ot_length_real)) {{ $data->ot_length_real }} @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Start Rev</td>
                                        <td width="60%">
                                        @if(!empty($data->ot_start_at_rev)) {{ \Carbon\Carbon::parse($data->ot_start_at_rev)->format('d-m-Y H:i:s') }} @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Finish Rev</td>
                                        <td>
                                        @if(!empty($data->ot_end_at_rev)) {{ \Carbon\Carbon::parse($data->ot_end_at_rev)->format('d-m-Y H:i:s') }} @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Length Rev</td>
                                        <td>
                                        @if(!empty($data->ot_length_rev)) {{ $data->ot_length_rev }} @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Basic Sallary</td>
                                        <!-- <td width="60%">Rp. {{ number_format($_data['gaji_pokok']) }}</td> -->
                                        <td width="60%">@if($_data['status_karyawan']=='Freelance') @if($_isHoliday||$_isWeekend) Rp. {{ number_format($_arr[1]['informasi']) }} @else Rp. {{ number_format($_arr[0]['informasi']) }} @endif @else Rp. {{ number_format($_data['gaji_pokok']) }} @endif</td>
                                    </tr>
                                    <tr>
                                        <td>Rp Rev</td>
                                        <td>Rp. {{ number_format($data->ot_length_rp) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Rp Rev (Round)</td>
                                        <td>Rp. {{ number_format(round($data->ot_length_rp, -3)) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body typography">
                            <form action="{{ route('overtime.update', $data->id) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @method('PATCH')
                            @csrf
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Length Adj</td>
                                        <td width="60%">
                                        <!-- @if(!empty($data->ot_length_adj)) {{ \Carbon\CarbonInterval::seconds($data->ot_length_adj)->cascade()->forHumans() }} @endif -->
                                        @if(!empty($data->ot_length_adj)) {{ gmdate('H:i:s', $data->ot_length_adj) }} @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Rp Adj</td>
                                        <td>Rp. {{ number_format($data->ot_length_rp_adj) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Rp Adj (Round)</td>
                                        <td>Rp. {{ number_format(round($data->ot_length_rp_adj, -3)) }}</td>
                                    </tr>
                                    <!-- <tr>
                                        <td>Length Adj in seconds</td>
                                        <td>@if(!empty($data->ot_length_adj)) {{ number_format($data->ot_length_adj) }} @endif
                                    </tr> -->
                                    <tr>
                                        <td>Hours</td>
                                        <td><input type="number" min="0" max="23" name="hours" id="hours" class="form-control numbers" required="" value="{{ $duration['hours'] }}"></td>
                                    </tr>
                                    <tr>
                                        <td>Minutes</td>
                                        <td><input type="number" min="0" max="60" name="minutes" id="minutes" class="form-control numbers" required="" value="{{ $duration['minutes'] }}"></td>
                                    </tr>
                                    <tr>
                                        <td>Seconds</td>
                                        <td><input type="number" min="0" max="60" name="seconds" id="seconds" class="form-control numbers" required="" value="{{ $duration['seconds'] }}"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <br/>
                            @if(!empty($data->ot_length_adj)) <button class="btn btn-success" type="submit">Submit</button> | @endif <a href="{{ route('reporting-index') }}" class="btn btn-warning" type="button">Back</a>
                            </form>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Employee Status</td>
                                        <td width="60%">{{ $_data['status_karyawan'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Working Group</td>
                                        <td>{{ $_data['wg_name'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Day</td>
                                        <td>{!! $_day !!}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body typography">
                            <h6>History</h6>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Length Adj</th>
                                        <th>Rp Adj</th>
                                        <th>Rp Adj (Round)</th>
                                        <th>Edit By</th>
                                        <th>Edit At</th>
                                    </tr>
                                </thead>
                                @php
                                    // dd($data->id);
                                    $ot_history = App\Models\OvertimeHistory::where(['id_ot' => $data->id])->orderBy( 'updated_at', 'desc' )->get();
                                @endphp
                                <tbody>
                                    @foreach ($ot_history as $_data)
                                    <tr>
                                        <td>@if(!empty($_data->ot_length_adj)) {{ gmdate('H:i:s', $_data->ot_length_adj) }} @endif</td>
                                        <td>Rp. {{ number_format($_data->ot_length_rp_adj) }}</td>
                                        <td>Rp. {{ number_format(round($_data->ot_length_rp_adj, -3)) }}</td>
                                        <td>@if(!empty($_data->ot_edit_property)) {{ json_decode($_data->ot_edit_property, true)['nama'] }}/{{ json_decode($_data->ot_edit_property, true)['nik'] }} @endif</td>
                                        <td>@if(!empty($_data->updated_at)) {{ \Carbon\Carbon::parse($_data->updated_at)->format('d-m-Y H:i:s') }} @endif</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-12">
                    <div class="appointment">
                        <a href="{{ URL::previous() }}" class="btn btn-warning" type="button">Back</a>
                        <p>&nbsp;</p>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
@endsection