@extends('layouts.simple.master')
@section('title', 'Formulir Karyawan Non Aktif')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Auto Formulir Karyawan Non Aktif</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Auto Formulir Karyawan Non Aktif</li>
<li class="breadcrumb-item active">Add new</li>
@endsection
<!-- https://makitweb.com/auto-populate-dropdown-with-jquery-ajax-in-laravel/ -->
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('nonaktif_auto.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <label class="form-label" for="nik_karyawan">Karyawan</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="nik_karyawan" name="nik_karyawan" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($karyawan as $data)
                                    <option value="{{ $data->nik }}">{{ $data->nama }} / {{ $data->nik }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback txt-danger">Karyawan is required.</div>
                            </div>
                        </div>
                        <!-- <div class="row mb-3">
                            <div class="col-sm-4">
                                <label class="form-label">Perusahaan</label>
                                <input class="form-control" value="" id="kode_perusahaan" name="kode_perusahaan">
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label">Atasan</label>
                                <input class="form-control" value="" id="layer1" name="layer1">
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label">Tanggal berakhir</label>
                                <input class="form-control" value="" id="tanggal_berakhir" name="tanggal_berakhir">
                            </div>
                        </div> -->
                        <br />
                        <button class="btn btn-success" type="submit" name="submit" id="submit" value="store">Submit</button> | <a href="{{ URL::previous() }}" class="btn btn-warning" type="button">Back</a>
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
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $("#nik_karyawan").change(function() {
        var value = $(this).val();
        // $("#layer1").prop("value", value);
    });
});
</script>
@endsection