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
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item">Sertifikasi</li>
<li class="breadcrumb-item active">@if ($status == 'edit') Edit @else Add @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if ($status == 'edit')
                    <form action="{{ route('_sertifikasi_master.update', $sertifikasi_sub->id) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @method('PATCH')
                        @else
                        <form action="{{ route('_sertifikasi_master.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @endif
                            @csrf
                            <div class="row m-t-10">
                                <div class="col-md-4">
                                    <label class="form-label" for="id_sertifikasi">Jenis</label>
                                    <select class="form-select" id="id_sertifikasi" name="id_sertifikasi" required="">
                                        <option selected="" value="">Pilih...</option>
                                        @foreach ($sertifikasi as $data)
                                        <option value="{{ $data->id }}" @if ($status=='edit' ) @if($data->id == $sertifikasi_sub->id_sertifikasi) selected @endif @endif >{{ $data->sertifikasi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row m-t-10">
                                <div class="col-md-4">
                                    <label class="form-label" for="sub_sertifikasi">Sertifikasi</label>
                                    <input name="sub_sertifikasi" id="sub_sertifikasi" class="form-control" type="text" required="" @if ($status=='edit' ) value="{{$sertifikasi_sub->sub_sertifikasi}}" @endif>
                                </div>
                            </div>
                            <br />
                            <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ route('_sertifikasi_master.index') }}" class="btn btn-warning" type="button">Cancel</a>
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