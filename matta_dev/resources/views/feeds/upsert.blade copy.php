@extends('layouts.simple.master')
@section('title', 'Validation Forms')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/dropzone.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>@if ($status == 'edit') Edit @else Add @endif</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item">Feeds</li>
<li class="breadcrumb-item active">@if ($status == 'edit') Edit @else Add @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if ($status == 'edit')
                    <form form action="{{ route('feeds.update', $feed->id) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="">
                        @method('PATCH')
                        @else
                        <form form action="{{ route('feeds.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="">
                            @endif
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="validationCustom01">Title</label>
                                    <input name="nama" id="nama" class="form-control" id="validationCustom01" type="text" required="" @if ($status=='edit' ) value="{{$feeds->title}}" @endif>
                                    <div class="valid-feedback">Looks good!</div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="email-wrapper">
                                        <div class="theme-form">
                                            <div class="mb-3">
                                                <label>Content:</label>
                                                <textarea id="content" name="content" cols="10" rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="validationCustom01">Active</label>
                                    <div class="media-body text-start icon-state">
                                        <label class="switch">
                                            <input type="checkbox"><span class="switch-state"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="validationCustom01">Notif</label>
                                    <div class="media-body text-start icon-state">
                                        <label class="switch">
                                            <input type="checkbox"><span class="switch-state"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ route('feeds.index') }}" class="btn btn-warning" type="button">Cancel</a>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script src="{{asset('assets/js/editor/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('assets/js/editor/ckeditor/adapters/jquery.js')}}"></script>
<script src="{{asset('assets/js/dropzone/dropzone.js')}}"></script>
<script src="{{asset('assets/js/dropzone/dropzone-script.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script src="{{asset('assets/js/email-app.js')}}"></script>
<script src="{{asset('assets/js/form-validation-custom.js')}}"></script>
@endsection