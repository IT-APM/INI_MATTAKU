@extends('layouts.errors.master')
@section('title', 'Overtime')

@section('css')
@endsection

@section('style')
@endsection


@section('content')
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4><img src="{{ asset('assets/images/matta/form-icon-25.jpg') }}" height="30" alt="Alita" />&nbsp;Overtime</h4>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5>Diajukan Oleh</h5>
                </div>
                <div class="card-header">
                    <a href="{{ asset('assets/images/matta/karyawan/'.$data['nik'].'/'.$data['nik'].'.jpg') }}" target="_blank"><img class="img-thumbnail" width="20%" src="{{ asset('assets/images/matta/karyawan/'.$data['nik'].'/'.$data['nik'].'.jpg') }}" alt="user"></a>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="30%">Nama</td>
                                <td width="70%">{{ $data['nama'] }}</td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td>{{ $data['nik'] }}</td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td>{{ $data['jabatan'] }}</td>
                            </tr>
                            <tr>
                                <td>Direktorat / Divisi / Departemen</td>
                                <td>{{ $data['unit_kerja'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5>Review</h5>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="40%">Created at</td>
                                <td width="60%">{{Carbon\Carbon::parse($overtime->ot_maker_at) ->format('d-m-Y H:i:s')}}</td>
                            </tr>
                            <tr>
                                <td>Title</td>
                                <td>{{$overtime->ot_title}}</td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td>{{$overtime->ot_desc}}</td>
                            </tr>
                            <tr>
                                <td>Start</td>
                                <td>{{Carbon\Carbon::parse($overtime->ot_start_at) ->format('d-m-Y H:i:s')}}</td>
                            </tr>
                            <tr>
                                <td>Finish</td>
                                <td>{{Carbon\Carbon::parse($overtime->ot_end_at) ->format('d-m-Y H:i:s')}}</td>
                            </tr>
                            <tr>
                                <td>Type</td>
                                <td>{{ $overtime->_ot_type_code->ot_type }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if (Carbon\Carbon::parse($overtime->ot_start_at) >= Carbon\Carbon::parse(now()))
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography">
                <form id="myform" name="myform" method="get" enctype="multipart/form-data" action="{{ $linkAction }}" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @method('GET')
                    @csrf
                    <button type="submit" name="res" value="Approve" class="btn btn-success">Approve</button>
                    <button type="submit" name="res" value="Reject" class="btn btn-danger">Reject</button>
                </form>
                </div>
            </div>
        </div>
        @endif
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography text-center">
                    <h6>Terima kasih untuk partisipasi dan kerjasamanya untuk melakukan review Overtime ini.</h6>
                    <a href="https://alita.id/"><img src="{{ asset('assets/images/matta/Alita-New-Log.png') }}" height="40" alt="Alita" /></a><br />
                    <h6>&reg; PT Alita Praya Mitra - 2021</h6>
                </div>
            </div>
        </div>
        <!--  -->
    </div>
</div>
@endsection

@section('script')

@endsection