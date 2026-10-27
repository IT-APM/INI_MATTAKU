@extends('layouts.simple.master')
@section('title', 'Formulir Permohonan Izin Sakit')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Formulir Permohonan Izin Sakit</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Permohonan Izin Sakit</li>
@endsection


@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <img class="img-100" src="{{asset('assets/images/other-images/sad.png')}}" alt="">
                        <div class="error-heading">
                            <h2 class="headline font-danger">Error</h2>
                            @if ($arr[0]['status']) <h5 class="headline font-success">{{ $arr[0]['message'] }} - OK</h5>
                            @else <h5 class="headline font-danger">{{ $arr[0]['message'] }} - NOK</h5> @endif
                            @if ($arr[1]['status']) <h5 class="headline font-success">{{ $arr[1]['message'] }} - OK</h5>
                            @else <h5 class="headline font-danger">{{ $arr[1]['message'] }} - NOK</h5> @endif
                            @if ($arr[2]['status']) <h5 class="headline font-success">{{ $arr[2]['message'] }} - OK</h5>
                            @else <h5 class="headline font-danger">{{ $arr[2]['message'] }} - NOK</h5> @endif
                            @if ($arr[3]['status']) <h5 class="headline font-success">{{ $arr[3]['message'] }} - OK</h5>
                            @else <h5 class="headline font-danger">{{ $arr[3]['message'] }} - NOK</h5> @endif
                            @if ($arr[4]['status']) <h5 class="headline font-success">{{ $arr[4]['message'] }} - OK</h5>
                            @else <h5 class="headline font-danger">{{ $arr[4]['message'] }} - NOK</h5> @endif
                        </div>
                        <div class="col-md-12">
                            <!-- <p class="sub-content">Please contact your system administrator!</p> -->
                        </div>
                        <div><a class="btn btn-danger-gradien btn-lg" href="javascript:history.back()">BACK</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
@endsection