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
<li class="breadcrumb-item">Data Mapping</li>
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item active">@if ($status == 'edit') Edit @else Add @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if ($status == 'edit')
                    <form action="{{ route('mtrdataprojectadminpic.update', $trx->id) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @method('PATCH')
                    @else
                    <form action="{{ route('mtrdataprojectadminpic.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @endif
                    @csrf
                        <div class="row g-3">
                            <input type="hidden" id="project_id" name="project_id" value="{{ Crypt::encrypt($project_id) }}">
                            <div class="col-md-4">
                                <label class="form-label" for="admin">Admin</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="admin" name="admin" required="">
                                    <option selected="" value="">Pilih...</option>
                                    @foreach ($karyawan as $data)
                                    <option value="{{ $data->nik }}" @if ($status=='edit' ) @if($data->nik == $trx->admin) selected @endif @endif >{{ $data->nama }} / {{ $data->nik }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback txt-danger">Field is required.</div>
                            </div>
                        </div>
                        <br/>
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ route('mtrdataprojectadmin.show', Crypt::encrypt($project_id)) }}" class="btn btn-warning" type="button">Cancel</a>
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