@extends('layouts.simple.master')
@section('title', 'Add Post')

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
				<div class="card-body add-post">
					@if(Session::has('msg'))
					<div class="alert alert-success">{{Session::get('msg')}}</div>
					@endif
					<!-- <form class="row needs-validation" novalidate=""> -->
					@if ($status == 'edit')
					<form action="{{ route('feeds.update', $feed->id) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
						@method('PATCH')
						@else
						<form action="{{ route('feeds.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
							@endif
							@csrf
							<div class="col-sm-12">
								<div class="mb-3">
									<label for="title">Title</label>
									<input class="form-control" name="title" id="title" type="text" placeholder="Title" required="" @if ($status=='edit' ) value="{{$feed->title}}" @endif>
									<div class="valid-feedback">Looks good!</div>
								</div>
								<div class="mb-3">
									<label class="form-label" for="is_active">Active</label>
									<div class="media-body text-start icon-state">
										<label class="switch">
											<input name="is_active" id="is_active" type="checkbox" @if ($status=='edit' ) @if ($feed->is_active == 1) checked="" @endif @endif><span class="switch-state"></span>
										</label>
									</div>
								</div>
								<div class="mb-3">
									<label class="form-label" for="is_notif">Notif</label>
									<div class="media-body text-start icon-state">
										<label class="switch">
											<input name="is_notif" id="is_notif" type="checkbox" @if ($status=='edit' ) @if ($feed->is_notif == 1) checked="" @endif @endif><span class="switch-state"></span>
										</label>
									</div>
								</div>
								<div class="email-wrapper">
									<div class="theme-form">
										<div class="mb-3">
											<label>Content</label>
											<textarea class="form-control" id="text-box" name="content" cols="10" rows="2">@if ($status == 'edit') {!! $feed->content !!} @endif</textarea>
										</div>
									</div>
								</div>
							</div>
							<div class="btn-showcase text-end">
								<button class="btn btn-primary" type="submit">Post</button>
								<a href="{{ route('feeds.index') }}" class="btn btn-light" type="button">Discard</a>
							</div>
						</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/editor/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('assets/js/editor/ckeditor/adapters/jquery.js')}}"></script>
<script src="{{asset('assets/js/dropzone/dropzone.js')}}"></script>
<script src="{{asset('assets/js/dropzone/dropzone-script.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script src="{{asset('assets/js/email-app.js')}}"></script>
<script src="{{asset('assets/js/form-validation-custom.js')}}"></script>
@endsection