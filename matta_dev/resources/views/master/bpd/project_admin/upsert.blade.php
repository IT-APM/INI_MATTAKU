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
<li class="breadcrumb-item">Project vs Admin</li>
<li class="breadcrumb-item active">@if ($status == 'edit') Edit @else Add @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if ($status == 'edit')
                    <form action="{{ route('mtrdataprojectadmin.update', $trx->id) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @method('PATCH')
                    @else
                    <form action="{{ route('mtrdataprojectadmin.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @endif
                    @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="project_id">Project ID</label>
                                <input name="project_id" id="project_id" class="form-control" type="text" required="" @if ($status=='edit' ) value="{{$trx->project_id}}" @endif>
                                <div class="invalid-feedback txt-danger">Field is required.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="name">Project Name</label>
                                <input name="name" id="name" class="form-control" type="text" required="" @if ($status=='edit' ) value="{{$trx->name}}" @endif>
                                <div class="invalid-feedback txt-danger">Field is required.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="divisi">Division</label>
                                <input name="divisi" id="divisi" class="form-control" type="text" @if ($status=='edit' ) value="{{$trx->divisi}}" @endif>
                            </div>
                        </div>
                        <br/>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="customer">Customer Name</label>
                                <input name="customer" id="customer" class="form-control" type="text" @if ($status=='edit' ) value="{{$trx->customer}}" @endif>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="group">Group</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="group" name="group" required="">
                                    <option selected="" value="">Pilih...</option>
                                    <option value="Corporate" @if ($status=='edit' ) @if('Corporate' == $trx->group) selected @endif @endif >Corporate</option>
                                    <option value="Project" @if ($status=='edit' ) @if('Project' == $trx->group) selected @endif @endif >Project</option>
                                    <option value="Project IOH" @if ($status=='edit' ) @if('Project IOH' == $trx->group) selected @endif @endif >Project IOH</option>
                                </select>
                                <div class="invalid-feedback txt-danger">Field is required.</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="coa_prefix">COA Prefix</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="coa_prefix" name="coa_prefix" required="">
                                    <option selected="" value="">Pilih...</option>
                                    <option value="5" @if ($status=='edit' ) @if('5' == $trx->coa_prefix) selected @endif @endif >5</option>
                                    <option value="6" @if ($status=='edit' ) @if('6' == $trx->coa_prefix) selected @endif @endif >6</option>
                                </select>
                                <div class="invalid-feedback txt-danger">Field is required.</div>
                            </div>
                            <div class="col-md-2">
								<label class="form-label" for="is_active">Aktif</label>
                                <div class="media-body text-start icon-state switch-outline">
                                    <label class="switch">
										<input name="is_active" id="is_active" type="checkbox" @if ($status=='edit' ) @if ($trx->is_active == 1) checked="" @endif @endif><span class="switch-state bg-success"></span>
									</label>
								</div>
                            </div>
                        </div>
                        <br />
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ route('mtrdataprojectadmin.index') }}" class="btn btn-warning" type="button">Cancel</a>
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