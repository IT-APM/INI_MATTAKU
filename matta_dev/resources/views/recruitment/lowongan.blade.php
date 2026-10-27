@extends('layouts.simple.master')
@section('title', 'Posting')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select2.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dropzone.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Posting</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Recruitment</li>
<li class="breadcrumb-item active">Posting</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body add-post">
                    @if(Session::has('error'))
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h5>ERROR: {{ session('error') }}</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
					<div class="col-sm-12">
						<div class="mb-3">
							<h5>Position</h5>
							<input class="form-control" type="text" value="{{ $trx->_pekerjaan->pekerjaan }}">
						</div>
					</div>
                    <form action="{{ route('recruitment.store') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @csrf
                        <input type="hidden" id="id_kebutuhan" name="id_kebutuhan" value="{{ Crypt::encrypt($trx->id) }}">
						<div class="row mb-3">
                            <div class="theme-form">
                                <h5>Content</h5>
                                <textarea class="form-control" id="text-box" name="job_description" cols="10" rows="5" required="">@if(!empty($_trx)){!! $_trx->job_description !!}@else {{ $default }} @endif</textarea>
                            </div>
						</div>
						<button class="btn btn-primary" type="submit">Post</button>
						<a href="{{ route('recruitment.index') }}" class="btn btn-light" type="button">Discard</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/editor/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('assets/js/editor/ckeditor/adapters/jquery.js') }}"></script>
<script src="{{ asset('assets/js/dropzone/dropzone.js') }}"></script>
<script src="{{ asset('assets/js/dropzone/dropzone-script.js') }}"></script>
<script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
<script src="{{ asset('assets/js/email-app.js') }}"></script>
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
@endsection
