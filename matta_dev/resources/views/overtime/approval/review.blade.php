@extends('layouts.simple.master')
@section('title', 'Overtime Forms')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Review</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Overtime</li>
<li class="breadcrumb-item active">Review</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-8">
            <div class="card">
                <div class="card-body">
                    <h5>Detail</h5>
                    <form form action="{{ route('approval-appject', $overtime->id) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="ot_maker_at">Created at</label>
                                <input class="form-control digits" name="ot_maker_at" id="ot_maker_at" type="datetime-local" type="text" required="" @if ($status=='edit' ) value="{{$overtime->ot_maker_at}}" @endif>
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="ot_maker_by">Maker: {{$karyawan->nama}}</label>
                                <input name="ot_maker_by" id="ot_maker_by" class="form-control" type="text" required="" @if ($status=='edit' ) value="{{$overtime->ot_maker_by}}" @endif>
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="ot_start_at">Start</label>
                                <input class="form-control digits" name="ot_start_at" id="ot_start_at" type="datetime-local" type="text" required="" @if ($status=='edit' ) value="{{$overtime->ot_start_at}}" @endif>
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="ot_title">Title</label>
                                <input name="ot_title" id="ot_title" class="form-control" type="text" required="" @if ($status=='edit' ) value="{{$overtime->ot_title}}" @endif>
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="ot_end_at">Finish</label>
                                <input class="form-control digits" name="ot_end_at" id="ot_end_at" type="datetime-local" type="text" required="" @if ($status=='edit' ) value="{{$overtime->ot_end_at}}" @endif>
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="ot_desc">Description</label>
                                <textarea class="form-control" id="ot_desc" name="ot_desc" rows="3" required="">@if ($status == 'edit') {{$overtime->ot_desc}} @endif</textarea>
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="ot_type_code">Type</label>
                                <select class="form-select" id="ot_type_code" name="ot_type_code" required="">
                                    <option selected="" disabled="" value="">Choose...</option>
                                    @foreach ($type_code as $data)
                                    <option value="{{ $data->id }}" @if ($status=='edit' ) @if($data->id == $overtime->ot_type_code) selected @endif @endif >{{ $data->ot_type }}</option>
                                    @endforeach
                                </select>
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                        </div>
                        <br />
                        <!-- ($overtime->ot_approver_status == 'Waiting') -->
                        <!-- <a href="{{ route('approval-appject', [$overtime->id,'res=Terminate']) }}" class="btn btn-primary" type="button">Terminate</a> |  -->
                        {{-- @if (Carbon\Carbon::parse($overtime->ot_start_at)->format('m') >= Carbon\Carbon::parse(now())->format('m')) --}}
                        @if (Carbon\Carbon::parse($overtime->ot_start_at) >= Carbon\Carbon::parse(now()))
                        <a href="{{ route('approval-appject', [$overtime->id,'res=Approve']) }}" class="btn btn-success" type="button">Approve</a> | <a href="{{ route('approval-appject', [$overtime->id,'res=Reject']) }}" class="btn btn-danger" type="button">Reject</a> | <a href="{{ route('approval-appject', [$overtime->id,'res=Expire']) }}" class="btn btn-info" type="button">Expire</a> |
                        @endif
                        <a href="{{ route('approval-index') }}" class="btn btn-warning" type="button">Back</a><br />
                    </form>
                </div>
            </div>
        </div>
        <!--  -->
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
        <!--  -->
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/sweet-alert/app.js')}}"></script>
@endsection