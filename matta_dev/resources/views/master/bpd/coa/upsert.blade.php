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
<li class="breadcrumb-item">COA</li>
<li class="breadcrumb-item active">@if ($status == 'edit') Edit @else Add @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if ($status == 'edit')
                    <form action="{{ route('mtrdatacoabpd.update', $trx->id) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @method('PATCH')
                    @else
                    <form action="{{ route('mtrdatacoabpd.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @endif
                    @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="account">Account</label>
                                <input name="account" id="account" class="form-control" type="text" required="" @if ($status=='edit' ) value="{{$trx->account}}" @endif>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="name">Name</label>
                                <input name="name" id="name" class="form-control" type="text" required="" @if ($status=='edit' ) value="{{$trx->name}}" @endif>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="type">Type</label>
                                <input name="type" id="type" class="form-control" type="text" @if ($status=='edit' ) value="{{$trx->type}}" @endif>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="category">Category</label>
                                <input name="category" id="category" class="form-control" type="text" @if ($status=='edit' ) value="{{$trx->category}}" @endif>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="divisi">Division</label>
                                <select class="form-select" id="divisi" name="divisi" required="">
                                    <option selected="" value="">Pilih...</option>
                                    <option value="Project" @if ($status=='edit' ) @if("Project" == $trx->divisi) selected @endif @endif >Project</option>
                                    <option value="Support" @if ($status=='edit' ) @if("Support" == $trx->divisi) selected @endif @endif >Support</option>
                                </select>
                            </div>
                        </div>
                        <br />
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ route('mtrdatacoabpd.index') }}" class="btn btn-warning" type="button">Cancel</a>
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