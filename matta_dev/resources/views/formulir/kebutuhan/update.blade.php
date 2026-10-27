@extends('layouts.simple.master')
@section('title', 'Formulir Kebutuhan Karyawan')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Update</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Kebutuhan Karyawan</li>
<li class="breadcrumb-item active">Update</li>
@endsection
<!-- https://makitweb.com/auto-populate-dropdown-with-jquery-ajax-in-laravel/ -->
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-7">
            <div class="card">
                <div class="card-header">
                    <h5>Para Pihak</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('kebutuhan.update', (new App\Http\Controllers\OpenDataController)->encryptWithKey($trxM->id)) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @method('PATCH')
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="card-body pt-0">
                                    <div class="appointment-table table-responsive">
                                        <table class="table table-bordernone">
                                            <tbody>
                                                <!-- pengaju -->
                                                <tr>
                                                    <td>
                                                        <a href="{{ asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg') }}" target="_blank"><img class="img-thumbnail" src="{{ asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg') }}" alt="user"></a>
                                                    </td>
                                                    <td class="img-content-box">
                                                        <h6 class="m-10">Karyawan</h6>
                                                        <span class="f-light m-10">{{ $pengaju['nama'] }}<br/>{{ $pengaju['jabatan'] }}<br/>{{ $pengaju['divisi'] }} / {{ $pengaju['dep'] }} / {{ $pengaju['sub_dep'] }}</span>
                                                    </td>
                                                </tr>
                                                <!-- layer1 -->
                                                <tr>
                                                    <td>
                                                        <a href="{{ asset('assets/images/matta/karyawan/'.$layer1['nik'].'/'.$layer1['nik'].'.jpg') }}" target="_blank"><img class="img-thumbnail" src="{{ asset('assets/images/matta/karyawan/'.$layer1['nik'].'/'.$layer1['nik'].'.jpg') }}" alt="user"></a>
                                                    </td>
                                                    <td class="img-content-box">
                                                        <h6 class="m-10">Layer 1</h6>
                                                        <span class="f-light m-10">{{ $layer1['nama'] }}<br/>{{ $layer1['jabatan'] }}<br/>{{ $layer1['divisi'] }} / {{ $layer1['dep'] }} / {{ $layer1['sub_dep'] }}</span>
                                                    </td>
                                                </tr>
                                                <!-- layer2 -->
                                                <tr>
                                                    <td>
                                                        <a href="{{ asset('assets/images/matta/karyawan/'.$layer2['nik'].'/'.$layer2['nik'].'.jpg') }}" target="_blank"><img class="img-thumbnail" src="{{ asset('assets/images/matta/karyawan/'.$layer2['nik'].'/'.$layer2['nik'].'.jpg') }}" alt="user"></a>
                                                    </td>
                                                    <td class="img-content-box">
                                                        <h6 class="m-10">Layer 2</h6>
                                                        <span class="f-light m-10">{{ $layer2['nama'] }}<br/>{{ $layer2['jabatan'] }}<br/>{{ $layer2['divisi'] }} / {{ $layer2['dep'] }} / {{ $layer2['sub_dep'] }}</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label" for="keterangan_lain">Keterangan Lain</label>
                                <textarea class="form-control" id="keterangan_lain" name="keterangan_lain" rows="4" >{!! $trxM->keterangan_lain !!}</textarea>
                            </div>
                        </div>
                        {{-- <br/> --}}
                        <div class="row g-3 mt-2">
                            <div class="col-md-12">
                                <label class="form-label" for="status_pengajuan">Status Pengajuan</label>
                                <select class="form-select" id="status_pengajuan" name="status_pengajuan" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($status_pengajuan as $data)
                                    <option value="{{ $data->id }}" @if($data->id == $trxM->status_pengajuan) selected @endif >{{ $data->status_pengajuan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br/>
                        <button class="btn btn-success" type="submit">Update</button> | <a href="{{ route('kebutuhan.index') }}" class="btn btn-warning" type="button">Back</a>
                    </form>
                </div>
            </div>
        </div>
        {{-- <div class="col-sm-5">
            <div class="card">
                <div class="card-header">
                    <h5>Edit Atribute</h5>
                </div>
                <form action="{{ route('kebutuhan.post_atribute', (new App\Http\Controllers\OpenDataController)->encryptWithKey($trxM->id)) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                @method('POST')
                @csrf
                <div class="card-body">
                    <div class="row mt-2">
                        <label class="form-label" for="id_pekerjaan">Nama Jabatan*</label>
                        <select class="form-select col-sm-12 js-example-basic-single" id="id_pekerjaan" name="id_pekerjaan" required="">
                            <option selected="" disabled="" value="">Pilih...</option>
                            @foreach ($pekerjaan as $data)
                            <option value="{{ $data->id }}" @if($data->id == $trxM->id_pekerjaan) selected @endif >{{ $data->pekerjaan }} ({{ $data->_bidang->bidang }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row mt-2">
                        <label class="form-label" for="id_level">Level*</label>
                        <select class="form-select col-sm-12 js-example-basic-single" id="id_level" name="id_level" required="">
                            <option selected="" disabled="" value="">Pilih...</option>
                            @foreach ($level as $data)
                            <option value="{{ $data->id }}" @if($data->id == $trxM->id_level) selected @endif >{{ $data->level }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-success mt-2" type="submit">Update</button>
                </div>
                </form>
            </div>
        </div> --}}
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