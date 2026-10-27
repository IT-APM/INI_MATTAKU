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
<li class="breadcrumb-item">Pelatihan</li>
<li class="breadcrumb-item active">@if ($status == 'edit') Edit @else Add @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if ($status == 'edit')
                    <form action="{{ route('_pelatihan_master.update', $pelatihan_sub->id) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @method('PATCH')
                        @else
                        <form action="{{ route('_pelatihan_master.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @endif
                            @csrf
                            <div class="row m-t-10">
                                <div class="col-md-4">
                                    <label class="form-label" for="id_pelatihan">Jenis</label>
                                    <select class="form-select" id="id_pelatihan" name="id_pelatihan" required="">
                                        <option selected="" value="">Pilih...</option>
                                        @foreach ($pelatihan as $data)
                                        <option value="{{ $data->id }}" @if ($status=='edit' ) @if($data->id == $pelatihan_sub->id_pelatihan) selected @endif @endif >{{ $data->pelatihan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row m-t-10">
                                <div class="col-md-4">
                                    <label class="form-label" for="sub_pelatihan">Pelatihan</label>
                                    <input name="sub_pelatihan" id="sub_pelatihan" class="form-control" type="text" required="" @if ($status=='edit' ) value="{{$pelatihan_sub->sub_pelatihan}}" @endif>
                                </div>
                            </div>
                            <br />
                            <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ route('_pelatihan_master.index') }}" class="btn btn-warning" type="button">Cancel</a>
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