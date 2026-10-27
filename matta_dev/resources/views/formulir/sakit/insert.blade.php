@extends('layouts.simple.master')
@section('title', 'Formulir Permohonan Izin Sakit')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Add</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Permohonan Izin Sakit</li>
<li class="breadcrumb-item active">Add</li>
@endsection
<!-- https://makitweb.com/auto-populate-dropdown-with-jquery-ajax-in-laravel/ -->
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if (count($errors) > 0)
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h6>Upload image is ERROR</h6>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    {{-- @if(Session::has('error'))
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <!-- <img class="img-50" src="{{asset('assets/images/other-images/sad.png')}}" alt="error"> -->
                        <h4>ERROR:</h4>
                        @if (session('error')[0]['status']) <h5>
                            {{ session('error')[0]['message'] }} - OK</h5>
                        @else <h5>{{ session('error')[0]['message'] }} - NOK</h5> @endif
                        @if (session('error')[1]['status']) <h5>
                            {{ session('error')[1]['message'] }} - OK</h5>
                        @else <h5>{{ session('error')[1]['message'] }} - NOK</h5> @endif --}}
                        {{-- @if (session('error')[2]['status']) <h5>
                            {{ session('error')[2]['message'] }} - OK</h5>
                        @else <h5>{{ session('error')[2]['message'] }} - NOK</h5> @endif
                        @if (session('error')[3]['status']) <h5>
                            {{ session('error')[3]['message'] }} - OK</h5>
                        @else <h5>{{ session('error')[3]['message'] }} - NOK</h5> @endif
                        @if (session('error')[4]['status']) <h5>
                            {{ session('error')[4]['message'] }} - OK</h5>
                        @else <h5>{{ session('error')[4]['message'] }} - NOK</h5> @endif
                        @if (session('error')[5]['status']) <h5>
                            {{ session('error')[5]['message'] }} - OK</h5>
                        @else <h5>{{ session('error')[5]['message'] }} - NOK</h5> @endif --}}
                        {{-- <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif --}}
                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="form-label" for="pengaju">Pengaju</label>
                            <input class="form-control" name="pengaju" id="pengaju" type="text" required="" readonly="" value="{{ $pengaju }}">
                        </div>
                    </div>
                    <form action="{{ route('sakit.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @csrf
                        <input type="hidden" id="nik_karyawan" name="nik_karyawan" value="{{ $nik_karyawan }}">
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <label class="form-label" for="tanggal_mulai">Tanggal Mulai</label>
                                <input class="form-control" name="tanggal_mulai" id="tanggal_mulai" type="date" required="">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="tanggal_akhir">Tanggal Akhir</label>
                                <input class="form-control" name="tanggal_akhir" id="tanggal_akhir" type="date" required="">
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <label class="form-label" for="pic_pengganti">PIC Pengganti</label>
                                <select class="js-example-basic-multiple col-sm-12" id="pic_pengganti[]" name="pic_pengganti[]" required="" multiple="multiple">
                                    <!-- <option selected="" disabled="" value="">Pilih...</option> -->
                                    @foreach ($karyawan as $data)
                                    <option value="{{ $data->nik }}">{{ $data->nama }} - {{ $data->nik }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback txt-danger">PIC Pengganti is required.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="telepon">Telepon Pengaju</label>
                                <input class="form-control m-input digits" type="tel" name="telepon" id="telepon" required="">
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <label class="form-label" for="alasan">Alasan</label>
                                <textarea class="form-control" id="alasan" name="alasan" rows="4" required=""></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="alamat">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="4" required=""></textarea>
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <label class="form-label" for="keterangan">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="4"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lampiran<br/>*Maksimal 2MB dengan format pdf atau gambar(jpg, jpeg dan png)</label>
                                <input name="lampiran" id="lampiran" class="form-control" type="file" required="">
                            </div>
                        </div>
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ URL::previous() }}" class="btn btn-warning" type="button">Back</a>
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
    $("#jumlah_hari").prop('disabled', true);
    $('#div_jumlah_hari').hide();
    $("#tipe_sakit").change(function() {
        var selectedValue = $(this).val();
        if (selectedValue == 0) {
            $("#jumlah_hari").prop('disabled', false);
            $('#div_jumlah_hari').show();
        } else {
            $("#jumlah_hari").prop('disabled', true);
            $('#div_jumlah_hari').hide();
        }
    });
});
</script>
@endsection