@extends('layouts.simple.master')
@section('title', 'Payroll Master')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>@if ($status == 'edit') Edit @else Add @endif</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Payroll Master</li>
<li class="breadcrumb-item">@if($tipe == 'add') Penambahan @else Pengurangan @endif</li>
<li class="breadcrumb-item active">@if ($status == 'edit') Edit @else Add @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if ($status == 'edit')
                    <form action="{{ route('payroll.upsert_master', ['id_data'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($addsub->id), 'tipe'=>'edit']) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @else
                    <form action="{{ route('payroll.upsert_master', ['tipe'=>$tipe]) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @endif
                    @csrf
                        <input type="hidden" id="_id" name="_id" value="{{ $id }}">
                        <div class="row g-3 mb-2">
                            <div class="col-md-4">
                                <label class="form-label" for="nama">Komponen</label>
                                <input name="nama" id="nama" class="form-control" type="text" required="" @if ($status=='edit' ) value="{{ $addsub->nama }}" @endif>
                            </div>
                            @php $arr_status=['apm','osc','apm_osc'] @endphp
                            <div class="col-md-2">
                                <label class="form-label" for="status">Sumber</label>
                                <select class="form-select" id="status" name="status" required="">
                                    <option selected="" value="">Pilih...</option>
                                    @foreach ($arr_status as $_status)
                                    <option value="{{ $_status }}" @if ($status=='edit' ) @if($addsub->status == $_status) selected @endif @endif >{{ $_status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="bulanan">Bulanan</label>
                                <div class="media-body text-start icon-state switch-outline">
                                    <label class="switch">
                                        <input name="bulanan" id="bulanan" type="checkbox" @if ($status=='edit' ) @if ($addsub->bulanan == 1) checked="" @endif @endif><span class="switch-state bg-success"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ route('payroll.master', $id) }}" class="btn btn-warning" type="button">Cancel</a>
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