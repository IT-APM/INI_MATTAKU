@extends('layouts.simple.master')
@section('title', 'Validation Forms')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>@if ($status == 'edit') Edit @else Add @endif</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">BOD</li>
<li class="breadcrumb-item">Line Approval</li>
<li class="breadcrumb-item active">@if ($status == 'edit') Edit @else Add @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if ($status == 'edit')
                    <form action="{{ route('mtrdatabodbpd.update', $trx->id) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @method('PATCH')
                    @else
                    <form action="{{ route('mtrdatabodbpd.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @endif
                    @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="nik">BOD</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="nik" name="nik" required="">
                                    <option selected="" value="">Pilih...</option>
                                    @foreach ($karyawan as $data)
                                    <option value="{{ $data->nik }}" @if ($status=='edit' ) @if($data->nik == $trx->nik) selected @endif @endif >{{ $data->nama }} / {{ $data->nik }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback txt-danger">Field is required.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="layer1">Layer 1</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="layer1" name="layer1" required="">
                                    <option selected="" value="">Pilih...</option>
                                    @foreach ($karyawan as $data)
                                    <option value="{{ $data->nik }}" @if ($status=='edit' ) @if($data->nik == $trx->layer1) selected @endif @endif >{{ $data->nama }} / {{ $data->nik }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback txt-danger">Field is required.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="layer2">Layer 2</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="layer2" name="layer2" required="">
                                    <option selected="" value="">Pilih...</option>
                                    @foreach ($karyawan as $data)
                                    <option value="{{ $data->nik }}" @if ($status=='edit' ) @if($data->nik == $trx->layer2) selected @endif @endif >{{ $data->nama }} / {{ $data->nik }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback txt-danger">Field is required.</div>
                            </div>
                        </div>
                        <br />
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ route('mtrdatabodbpd.index') }}" class="btn btn-warning" type="button">Cancel</a>
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
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
@endsection