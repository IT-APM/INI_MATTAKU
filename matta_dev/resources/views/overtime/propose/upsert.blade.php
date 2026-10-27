@extends('layouts.simple.master')
@section('title', 'Overtime Forms')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>@if ($status == 'edit') View @else Add @endif</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Overtime</li>
<li class="breadcrumb-item active">@if ($status == 'edit') View @else Add @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-8">
            <div class="card">
                    @if(Session::has('error'))
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <!-- <img class="img-50" src="{{asset('assets/images/other-images/sad.png')}}" alt="error"> -->
                        <h4>ERROR:</h4>
                        @if (session('error')['message'][0]['status']) <h5>
                            {{ session('error')['message'][0]['message'] }} - OK</h5>
                        @else <h5>{{ session('error')['message'][0]['message'] }} - NOK</h5> @endif
                        @if (session('error')['message'][1]['status']) <h5>
                            {{ session('error')['message'][1]['message'] }} - OK</h5>
                        @else <h5>{{ session('error')['message'][1]['message'] }} - NOK</h5> @endif
                        @if (session('error')['message'][2]['status']) <h5>
                            {{ session('error')['message'][2]['message'] }} - OK</h5>
                        @else <h5>{{ session('error')['message'][2]['message'] }} - NOK</h5> @endif
                        @if (session('error')['message'][3]['status']) <h5>
                            {{ session('error')['message'][3]['message'] }} - OK</h5>
                        @else <h5>{{ session('error')['message'][3]['message'] }} - NOK</h5> @endif
                        @if (session('error')['message'][4]['status']) <h5>
                            {{ session('error')['message'][4]['message'] }} - OK<br/>
                            {{ session('error')['message'][4]['quota'] }}</h5>
                        @else <h5>{{ session('error')['message'][4]['message'] }} - NOK<br/>
                        {{ session('error')['message'][4]['quota'] }}</h5> @endif
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                <div class="card-body">
                    @if ($status == 'edit')
                    <form action="{{ route('overtime.update', $overtime->id) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @method('PATCH')
                        @else
                        <form action="{{ route('overtime.store') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @endif
                            @csrf
                            <div class="row g-3">
                                @if ($status=='edit' ) 
                                <div class="col-md-6">
                                    <label class="form-label" for="ot_maker_by">Maker</label>
                                    <input name="ot_maker_by" id="ot_maker_by" class="form-control" type="text" required="" value="{{$overtime->ot_maker_by}}" >
                                    <!-- <div class="valid-feedback">Looks good!</div> -->
                                </div>
                                @else
                                <div class="col-md-6">
                                    <input type="hidden" id="ot_maker_by" name="ot_maker_by" value="{{ $ot_maker_by }}">
                                    <label class="form-label" for="ot_maker_by">Maker</label>
                                    {{-- <h6>{{ auth()->user()->name }} / {{ auth()->user()->nik }}</h6> --}}
                                    <h6>{{ $pengaju }}</h6>
                                </div>
                                @endif
                                <div class="col-md-6">
                                    <label class="form-label" for="ot_start_at">Start</label>
                                    <input class="form-control digits" name="ot_start_at" id="ot_start_at" type="datetime-local" type="text" required="" @if ($status=='edit' ) value="{{$overtime->ot_start_at}}" @endif>
                                    <!-- <div class="valid-feedback">Looks good!</div> -->
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="ot_title">Title</label>
                                    <input name="ot_title" id="ot_title" class="form-control" type="text" required="" @if ($status=='edit' ) value="{{$overtime->ot_title}}" @endif>
                                    <!-- <div class="valid-feedback">Looks good!</div> -->
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="ot_end_at">Finish</label>
                                    <input class="form-control digits" name="ot_end_at" id="ot_end_at" type="datetime-local" type="text" required="" @if ($status=='edit' ) value="{{$overtime->ot_end_at}}" @endif>
                                    <!-- <div class="valid-feedback">Looks good!</div> -->
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="ot_desc">Description</label>
                                    <textarea class="form-control" id="ot_desc" name="ot_desc" rows="3" required="">@if ($status == 'edit') {{$overtime->ot_desc}} @endif</textarea>
                                    <!-- <div class="valid-feedback">Looks good!</div> -->
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="ot_type_code">Type</label>
                                    <select class="form-select" id="ot_type_code" name="ot_type_code" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        @foreach ($type_code as $data)
                                        <option value="{{ $data->ot_type }}" @if ($status=='edit' ) @if($data->id == $overtime->ot_type_code) selected @endif @endif >{{ $data->ot_type }}</option>
                                        @endforeach
                                    </select>
                                    <!-- <div class="valid-feedback">Looks good!</div> -->
                                </div>
                            </div>
                            <br />
                            @if ($status == 'create') <button class="btn btn-success" type="submit">Submit</button> | @endif<a href="{{ route('overtime.index') }}" class="btn btn-warning" type="button">Back</a>
                        </form>
                </div>
            </div>
        </div>
        <!--  -->
        @if ($status == 'edit')
        <div class="col-sm-4 notification box-col-4">
            <div class="card height-equal">
                <div class="card-header card-no-border">
                    <div class="header-top">
                        <h5 class="m-0">History</h5>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <ul>
                        @foreach ($history as $_history)
                        <li class="d-flex">
                            @if ($loop->first)
                            <div class="activity-dot-success"></div>
                            @elseif ($loop->last)
                            <div class="activity-dot-warning"></div>
                            @else
                            <div class="activity-dot-primary"></div>
                            @endif
                            <div class="ms-3">
                                <p class="d-flex justify-content-between mb-2"><span class="date-content light-background">{{ $_history->created_at }}</span></p>
                                <h6>{{ $_history->ot_approver_status }}<span class="dot-notification"></span></h6>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
        <!--  -->
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/sweet-alert/app.js')}}"></script>
@endsection