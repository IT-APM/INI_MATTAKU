@extends('layouts.simple.master')
@section('title', 'Error DB')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Error</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Error</li>
<li class="breadcrumb-item active">DB</li>
@endsection

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-body">
          <!-- <div class="page-wrapper compact-wrapper" id="pageWrapper"> -->
          <!-- error-404 start-->
          <!-- <div class="error-wrapper"> -->
          <div class="container text-center">
            <img class="img-100" src="{{asset('assets/images/other-images/sad.png')}}" alt="">
            <h1 class="headline font-danger">Error DB</h1>
            <!-- <div class="col-md-8 offset-md-2"> -->
            <h4 class="sub-content font-danger">Please contact your system administrator!</h4>
            <!-- </div> -->
            <div><a class="btn btn-danger-gradien btn-lg" href="{{ URL::previous() }}">BACK</a></div>
          </div>
          <!-- </div> -->
          <!-- error-404 end      -->
          <!-- </div> -->
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
@endsection