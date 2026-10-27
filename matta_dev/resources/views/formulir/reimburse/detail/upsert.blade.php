@extends('layouts.simple.master')
@section('title', 'Validation Forms')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Edit</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir Reimbursement</li>
<li class="breadcrumb-item">Detail</li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('error'))
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        {!! session('error') !!}
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <form action="{{ route('reimburse.updateDetail', (new App\Http\Controllers\OpenDataController)->encryptWithKey($trxD->id)) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @method('POST')
                    @csrf
                        <input type="hidden" id="trxMId" name="trxMId" value="{{ (new App\Http\Controllers\OpenDataController)->encryptWithKey($trxD->id_reimburse) }}" >
                        <input type="hidden" id="_id_layanan" name="_id_layanan" value="{{ $trxD->id_layanan }}" >
                        <div class="row m-t-10">
                            <div class="col-md-4">
                                <label class="form-label" for="id_hubungan">Hubungan Dengan Karyawan</label>
                                <select class="form-select" id="id_hubungan" name="id_hubungan" required="">
                                    <option selected="" value="">Pilih...</option>
                                    @foreach ($hubungan as $data)
                                    <option value="{{ $data->id }}" @if($data->id == $trxD->id_hubungan) selected @endif >{{ $data->hubungan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="nama_pasien">Nama Pasien</label>
                                <input name="nama_pasien" id="nama_pasien" class="form-control" type="text" required="" value="{{$trxD->nama_pasien}}" >
                            </div>
                        </div>
                        <div class="row m-t-10">
                            <div class="col-md-4">
                                <label class="form-label" for="id_layanan">Layanan</label>
                                <select class="form-select" id="id_layanan" name="id_layanan" required="" disabled>
                                    <option selected="" value="">Pilih...</option>
                                    @foreach ($layanan as $data)
                                    <option value="{{ $data->id }}" @if($data->id == $trxD->id_layanan) selected @endif >{{ $data->layanan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="tanggal">Tanggal Layanan</label>
                                <input name="tanggal" id="tanggal" class="form-control" type="date" required="" value="{{$trxD->tanggal}}" >
                            </div>
                        </div>
                        <div class="row m-t-10">
                            <div class="col-md-4">
                                <label class="form-label" for="jumlah_klaim">Nominal Klaim</label>
                                {{-- <input min=0 type="number" name="jumlah_klaim" id="jumlah_klaim" class="form-control numbers" required="" value="{{$trxD->jumlah_klaim}}" > --}}
                                <input type="text" name="jumlah_klaim" id="jumlah_klaim" class="form-control numbers" value="{{ number_format($trxD->jumlah_klaim, 0, '.', ',') }}" required="">
                            </div>
                        </div>
                        <br />
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ route('reimburse.edit', (new App\Http\Controllers\OpenDataController)->encryptWithKey($trxD->id_reimburse)) }}" class="btn btn-warning" type="button">Cancel</a>
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
<script type="text/javascript">
// Format saat mengetik
$(document).on('input', '#jumlah_klaim', function () {
    let value = $(this).val().replace(/\D/g, '');
    $(this).val(new Intl.NumberFormat('en-US').format(value));
});

// Hapus koma sebelum submit agar value yang terkirim murni angka
$('#formAddNew').on('submit', function () {
    $('#jumlah_klaim').val($('#jumlah_klaim').val().replace(/,/g, ''));
});
</script>
@endsection