@extends('layouts.simple.master')
@section('title', 'FinDim Division')

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
<li class="breadcrumb-item">FinDim Division</li>
<li class="breadcrumb-item active">@if ($status == 'edit') Edit @else Add @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if ($status == 'edit')
                    <form action="{{ route('findim_divisi.update', $findim_divisi->id) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @method('PATCH')
                        @else
                        <form action="{{ route('findim_divisi.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @endif
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="divisi_id">No Division</label>
                                    <input name="divisi_id" id="divisi_id" class="form-control" type="text" required="" @if ($status=='edit' ) value="{{$findim_divisi->divisi_id}}" @endif>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="divisi_desc">Division Description</label>
                                    <input name="divisi_desc" id="divisi_desc" class="form-control" type="text" required="" @if ($status=='edit' ) value="{{$findim_divisi->divisi_desc}}" @endif>
                                </div>
                            </div>
                            <br />
                            <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ route('findim_divisi.index') }}" class="btn btn-warning" type="button">Cancel</a>
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