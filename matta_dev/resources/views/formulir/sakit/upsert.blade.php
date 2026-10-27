@extends('layouts.simple.master')
@section('title', 'Formulir Permohonan Izin Sakit')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>@if ($status == 'edit') View @else Add @endif</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Permohonan Izin Sakit</li>
<li class="breadcrumb-item active">@if ($status == 'edit') View @else Add @endif</li>
@endsection
<!-- https://makitweb.com/auto-populate-dropdown-with-jquery-ajax-in-laravel/ -->
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if ($status == 'edit')
                    <form action="{{ route('sakit.update', (new App\Http\Controllers\OpenDataController)->encryptWithKey($sakit->id)) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @method('PATCH')
                        @else
                        <form action="{{ route('sakit.store') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @endif
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="nik_karyawan">Karyawan</label>
                                    <select class="form-select js-example-basic-single col-sm-12" id="nik_karyawan" name="nik_karyawan" required="">
                                        <option selected="" disabled="" value="">Choose...</option>
                                        @foreach ($karyawan as $data)
                                        <option value="{{ $data->nik }}" @if ($status=='edit' ) @if($data->nik == $sakit->nik_karyawan) selected @endif @endif >{{ $data->nama }} - {{ $data->nik }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="tipe_sakit">Tipe Izin Sakit</label>
                                    <select class="form-select" id="tipe_sakit" name="tipe_sakit" required="">
                                        <option selected="" disabled="" value="">Choose...</option>
                                        @foreach ($tipe as $data)
                                        <option value="{{ $data->id }}" @if ($status=='edit' ) @if($data->id == $sakit->tipe_sakit) selected @endif @endif >{{ $data->tipe_sakit }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="jumlah_hari">Jumlah Hari</label>
                                    <input id="jumlah_hari" name="jumlah_hari" required="" class="form-control digits" type="number" placeholder="Jumlah Hari" @if ($status=='edit' ) value="{{$sakit->jumlah_hari}}" @endif>
                                </div>
                            </div>
                            <br />
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="jumlah_hari">Tanggal Mulai</label>
                                    <input class="form-control digits" name="tanggal_mulai" id="tanggal_mulai" type="date" type="text" required="" @if ($status=='edit' ) value="{{$sakit->tanggal_mulai}}" @endif>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="pic_pengganti">PIC Pengganti</label>
                                    <select class="js-example-basic-multiple col-sm-12" id="pic_pengganti[]" name="pic_pengganti[]" required="" multiple="multiple">
                                        <!-- <option selected="" disabled="" value="">Choose...</option> -->
                                        @foreach ($karyawan as $data)
                                        <option value="{{ $data->nik }}">{{ $data->nama }} - {{ $data->nik }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="jumlah_hari">Telepon</label>
                                    <input class="form-control m-input digits" type="tel" name="telepon" id="telepon" required="" @if ($status=='edit' ) value="{{$sakit->telepon}}" @endif>
                                </div>
                            </div>
                            <br />
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="alasan">Alasan</label>
                                    <textarea class="form-control" id="alasan" name="alasan" rows="4" required="">@if ($status == 'edit') {{$sakit->alasan}} @endif</textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="alamat">Alamat</label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="4" required="">@if ($status == 'edit') {{$sakit->alamat}} @endif</textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="keterangan">Keterangan</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="4">@if ($status == 'edit') {{$sakit->keterangan}} @endif</textarea>
                                </div>
                            </div>
                            <br />
                            @if ($status == 'create') <button class="btn btn-success" type="submit">Submit</button> | @endif<a href="{{ URL::previous() }}" class="btn btn-warning" type="button">Back</a>
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
    // 
</script>
@endsection