@extends('layouts.simple.master')
@section('title', 'Attendance Clock-in Clock-out')

@section('css')
<!--  -->
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Attendance Clock-in Clock-out</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Clock-in Clock-out</li>
<li class="breadcrumb-item active">Attendance</li>
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
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="40%">Server datetime</td>
                                <td width="60%">{{ date('d-m-Y', strtotime($arr[3]['tgl'])) }} {{ $arr[3]['jam'] }}</td>
                            </tr>
                            <tr>
                                <td>Type/WG</td>
                                <td>{{ $arr[0]['is_reguler'] }}/{{ $arr[0]['wg_id'] }}</td>
                            </tr>
                            <tr>
                                <td>Working hour</td>
                                <td>{{ $arr[0]['clockin'] }} sd {{ $arr[0]['clockout'] }}</td>
                            </tr>
                            <tr>
                                <td>Nama/NIP</td>
                                <td>{{ $karyawan->nama }}/{{ $karyawan->nik }}</td>
                            </tr>
                            <tr>
                                <td>Perusahaan / Direktorat / Divisi / Departemen</td>
                                <td>{{ $karyawan->_perusahaan->nama }} / {{ $karyawan->_divisi->nama }} / {{ $karyawan->_departemen->nama }} / {{ $karyawan->_subdepartemen->nama }}</td>
                            </tr>
                            @if ($arr[2]['n_clockin'] > 0)
                            <tr>
                                <td>Clock in</td>
                                <td>{{ date('d-m-Y H:i:s', strtotime($arr[5]['dtClockin'])) }}</td>
                            </tr>
                            @endif
                            @if ($arr[2]['n_clockout'] > 0)
                            <tr>
                                <td>Clock out</td>
                                <td>{{ date('d-m-Y H:i:s', strtotime($arr[5]['dtClockout'])) }}</td>
                            </tr>
                            @endif
                            @if ($arr[2]['n_checkin'] > 0)
                            <tr>
                                <td>Check in</td>
                                <td>
                                    @foreach ($arr[5]['dtCheckin'] as $data)
                                    {{ date('d-m-Y H:i:s', strtotime($data['att_timestamp'])) }}<br />
                                    @endforeach
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <br />
                    @if ($arr[4]['isClockin'])
                    <form action="{{ route('cico_att.store') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to Clock-in?');">
                        @csrf
                        <input name="att_type" id="att_type" class="form-control" type="hidden" value="Clock-in">
                        <button class="btn btn-success" type="submit"><i class="fa fa-sign-in" aria-hidden="true"></i> Clock In</button>
                    </form>
                    <br />
                    @endif
                    @if ($arr[4]['isClockout'])
                    <form action="{{ route('cico_att.store') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to Clock-out?');">
                        @csrf
                        <input name="att_type" id="att_type" class="form-control" type="hidden" value="Clock-out">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-sign-out" aria-hidden="true"></i> Clock Out</button>
                    </form>
                    <br />
                    @endif
                    @if ($arr[4]['isCheckin'])
                    <form action="{{ route('cico_att.store') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to Check-in?');">
                        @csrf
                        <input name="att_type" id="att_type" class="form-control" type="hidden" value="Check-in">
                        <button class="btn btn-warning" type="submit"><i class="fa fa-clock-o" aria-hidden="true"></i> Check In</button>
                    </form>
                    <br />
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