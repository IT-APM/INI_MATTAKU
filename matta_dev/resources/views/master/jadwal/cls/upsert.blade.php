@extends('layouts.simple.master')
@section('title', 'Add new')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>@if ($status == 'edit') Edit @else Add @endif</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Jadwal</li>
<li class="breadcrumb-item">Grup CLS</li>
<li class="breadcrumb-item active">@if ($status == 'edit') Edit @else Add @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if ($status == 'edit')
                    <form action="{{ route('jadwalcls.update', $trx->id) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
					@method('PATCH')
					@else
					<form action="{{ route('jadwalcls.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
					@endif
					@csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="nik">Karyawan</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="nik" name="nik" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($karyawan as $data)
                                    <option value="{{ $data->nik }}" @if ($status=='edit' ) @if($data->nik == $trx->nik) selected @endif @endif>{{ $data->nama }} - {{ $data->nik }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br />
                            <div class="col-md-4">
                                <label class="form-label" for="id_shift">Shift</label>
                                <select class="form-select" id="id_shift" name="id_shift" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($shift as $data)
                                    <option value="{{ $data->id }}" @if ($status=='edit' ) @if($data->id == $trx->id_shift) selected @endif @endif>{{ $data->shift }} => {{ $data->clock_in }} sd {{ $data->clock_out }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br />
                        <!-- <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="tanggal_awal">Tanggal Awal</label>
                                <input class="form-control" name="tanggal_awal" id="tanggal_awal" type="date" required="" @if ($status=='edit' ) value="{{$trx->tanggal_awal}}" @endif>
                            </div>
                            <br />
                            <div class="col-md-4">
                                <label class="form-label" for="tanggal_akhir">Tanggal Akhir</label>
                                <input class="form-control" name="tanggal_akhir" id="tanggal_akhir" type="date" required="" @if ($status=='edit' ) value="{{$trx->tanggal_akhir}}" @endif>
                            </div>
                        </div> -->
                        <br />
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ route('jadwalcls.show', 0) }}" class="btn btn-warning" type="button">Cancel</a>
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