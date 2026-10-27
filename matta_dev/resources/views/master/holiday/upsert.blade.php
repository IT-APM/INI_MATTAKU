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
<li class="breadcrumb-item">Hari Libur Nasional</li>
<li class="breadcrumb-item active">@if ($status == 'edit') Edit @else Add @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if ($status == 'edit')
                    <form action="{{ route('holiday.update', $holiday->id) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @method('PATCH')
                        @else
                        <form action="{{ route('holiday.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @endif
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="tanggal">Tanggal</label>
                                    <input class="form-control" name="tanggal" id="tanggal" type="date" required="" @if ($status=='edit' ) value="{{$holiday->tanggal}}" @endif>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="libur">Hari Libur Nasional</label>
                                    <input name="libur" id="libur" class="form-control" type="text" required="" @if ($status=='edit' ) value="{{$holiday->libur}}" @endif>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" for="is_cuti">Cuti Bersama</label>
                                    <div class="media-body text-start icon-state switch-outline">
                                        <label class="switch">
                                            <input name="is_cuti" id="is_cuti" type="checkbox" @if ($status=='edit' ) @if ($holiday->is_cuti) checked="" @endif @endif><span class="switch-state bg-success"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ route('holiday.index') }}" class="btn btn-warning" type="button">Cancel</a>
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