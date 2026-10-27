@extends('layouts.simple.master')
@section('title', 'Validation Forms')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>@if ($status == 'edit') Edit @else Add @endif</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Attendance</li>
<li class="breadcrumb-item">Download</li>
<li class="breadcrumb-item active">@if ($status == 'edit') Edit @else Add @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if ($status == 'edit')
                    <form action="{{ route('attendance_download.update', $trx->id) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @method('PATCH')
                        @else
                        <form action="{{ route('attendance_download.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @endif
                            @csrf
                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <label class="form-label" for="tanggal_mulai">Tanggal Mulai</label>
                                    <input class="form-control" name="tanggal_mulai" id="tanggal_mulai" type="date" required="" @if ($status=='edit' ) value="{{$trx->tanggal_mulai}}" @endif>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="tanggal_akhir">Tanggal Akhir</label>
                                    <input class="form-control" name="tanggal_akhir" id="tanggal_akhir" type="date" required="" @if ($status=='edit' ) value="{{$trx->tanggal_akhir}}" @endif>
                                </div>
                            </div>
                            <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ route('attendance_download.index') }}" class="btn btn-warning" type="button">Cancel</a>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/sweet-alert/app.js')}}"></script>
@endsection