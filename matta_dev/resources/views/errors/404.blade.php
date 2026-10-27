@extends('layouts.errors.master')
@section('title', 'Error 404')

@section('css')
@endsection

@section('style')
@endsection


@section('content')
<div class="page-wrapper compact-wrapper" id="pageWrapper">
  <!-- error-404 start-->
  <div class="error-wrapper">
    <div class="container"><img class="img-100" src="{{asset('assets/images/other-images/sad.png')}}" alt="">
      <div class="error-heading">
        <h2 class="headline font-danger">404</h2>
      </div>
      <div class="col-md-8 offset-md-2">
        {{-- <p class="sub-content">The page you are attempting to reach is currently not available.<br/>This may be because This Action Is Unauthorized.</p> --}}
        <p class="sub-content">Not Found<br/>The requested resource could not be found.</p>
      </div>
      {{-- <div><a class="btn btn-danger-gradien btn-lg" href="{{ URL::previous() }}">BACK</a></div> --}}
    </div>
  </div>
  <!-- error-404 end      -->
</div>
@endsection

@section('script')

@endsection