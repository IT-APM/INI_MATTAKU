@extends('layouts.simple.master')
@section('title', 'Overtime Clock-in Clock-out')

@section('css')
<!--  -->
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Overtime Clock-in Clock-out</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Clock-in Clock-out</li>
<li class="breadcrumb-item active">Overtime</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <!-- <div class="card-body text-center my-auto"> -->
                <div class="card-body">
                    @if(Session::has('msg'))
                    <!-- <div class="alert alert-success">{{Session::get('msg')}}</div> -->
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if(!empty($data))
                    @foreach ($data as $arr)
                        @if($arr['ot_approver_status'] == 'Approve')
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td width="40%">Nama/NIP</td>
                                    <td width="60%">{{ $karyawan->nama }}/{{ $karyawan->nik }}</td>
                                </tr>
                                <tr>
                                    <td>Overtime</td>
                                    <td>{{ $arr['ot_title'] }}</td>
                                </tr>
                                <tr>
                                    <td>Description</td>
                                    <td>{{ $arr['ot_desc'] }}</td>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td>{{ $arr['ot_type'] }}</td>
                                </tr>
                                <tr>
                                    <td>Start datetime</td>
                                    <td>{{ date('d-m-Y H:i:s', strtotime($arr['ot_start_at'])) }}</td>
                                </tr>
                                <tr>
                                    <td>End datetime</td>
                                    <td>{{ date('d-m-Y H:i:s', strtotime($arr['ot_end_at'])) }}</td>
                                </tr>
                                <tr>
                                    <td>Durasi</td>
                                    <td>{{ Carbon\CarbonInterval::seconds($arr['ot_length'])->cascade()->forHumans() }}</td>
                                </tr>
                                @if ($arr['n_clockin'] > 0)
                                <tr>
                                    <td>Clock in</td>
                                    <td>{{ date('d-m-Y H:i:s', strtotime($arr['dtClockin'])) }}</td>
                                </tr>
                                @endif
                                @if ($arr['n_clockout'] > 0)
                                <tr>
                                    <td>Clock out</td>
                                    <td>{{ date('d-m-Y H:i:s', strtotime($arr['dtClockout'])) }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <br />
                                @if ($arr['isClockin'])
                                <form action="{{ route('cico_ot.store') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to Clock-in?');">
                                    @csrf
                                    <input name="id" id="id" class="form-control" type="hidden" value="{{ $arr['id'] }}">
                                    <input name="att_type" id="att_type" class="form-control" type="hidden" value="Clock-in">
                                    <button class="btn btn-success" type="submit"><i class="fa fa-sign-in" aria-hidden="true"></i> Clock In</button>
                                </form>
                                <br />
                                @endif
                                @if ($arr['isClockout'])
                                <form action="{{ route('cico_ot.store') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to Clock-out?');">
                                    @csrf
                                    <input name="id" id="id" class="form-control" type="hidden" value="{{ $arr['id'] }}">
                                    <input name="att_type" id="att_type" class="form-control" type="hidden" value="Clock-out">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-sign-out" aria-hidden="true"></i> Clock Out</button>
                                </form>
                                <br />
                                @endif
                        <hr style="height:3px;border-width:0;color:orange" />
                        @endif
                    @endforeach
                    @else
                        <h4 class="text-center">No data available</h4>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!--  -->
@endsection