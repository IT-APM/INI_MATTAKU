@extends('layouts.simple.master')
@section('title', 'Copy Data Karyawan')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Copy Data</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Karyawan</li>
<li class="breadcrumb-item active">Copy Data</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('karyawancopy.store') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to copy this data?');">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="nik_karyawan_lama">NIP lama</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="nik_karyawan_lama" name="nik_karyawan_lama" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($karyawan as $data)
                                    <option value="{{ $data->nik }}">{{ $data->nama }} - {{ $data->nik }}</option>
                                    @endforeach
                                </select>
                                <br/><br/>
                                <label class="form-label" for="nik_karyawan_baru">NIP baru</label>
                                <input name="nik_karyawan_baru" id="nik_karyawan_baru" class="form-control" type="text" required="">
                            </div>
                        </div>
                        <br />
                        <button class="btn btn-success" type="submit">Copy</button> | <a href="{{ route('karyawan.index') }}" class="btn btn-warning" type="button">Back</a>
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