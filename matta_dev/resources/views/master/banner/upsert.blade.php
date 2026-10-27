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
<li class="breadcrumb-item">Banner</li>
<li class="breadcrumb-item active">@if ($status == 'edit') Edit @else Add @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                @if (count($errors) > 0)
                <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                    <h6>Upload image is ERROR</h6>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <div class="card-body">
                    <form action="{{ route('banner.update', $banner->id) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @method('PATCH')
                    @csrf
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label" for="link">URL/Link</label>
                                <input name="link" id="link" class="form-control" type="text" required="" @if ($status=='edit' ) value="{{$banner->link}}" @endif>
                            </div>
                        </div>
                        <br/>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <a href="{{ $banner->image }}" target="_blank"><img class="img-thumbnail" src="{{ $banner->image }}" alt="img" width="500"></a>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="file" name="image" id="image" class="form-control" required="">
                            </div>
                        </div>
                        <br/>
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ route('banner.index') }}" class="btn btn-warning" type="button">Cancel</a>
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