@extends('layouts.simple.master')
@section('title', 'Payroll Master')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>@if(in_array($tipe, ['edit_karyawan'])) Edit @else Add @endif</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Payroll Master</li>
<li class="breadcrumb-item">Karyawan</li>
<li class="breadcrumb-item active">@if(in_array($tipe, ['edit_karyawan'])) Edit @else Add @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if ($status == 'edit')
                    <form class="needs-validation" novalidate="" action="{{ route('payroll.upsert_master', ['id_data'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->id), 'tipe'=>$tipe]) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @else
                    <form class="needs-validation" novalidate="" action="{{ route('payroll.upsert_master', ['tipe'=>$tipe]) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @endif
                    @csrf
                        <input type="hidden" id="_id" name="_id" value="{{ $id }}">
                        <div class="row g-3 mb-2">
                            <div class="col-md-4">
                                <label class="form-label" for="nik">Karyawan</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="nik" name="nik" required="">
                                    <option selected="" disabled="" value="">Choose...</option>
                                    @foreach ($_karyawan as $data)
                                    <option value="{{ $data->nik }}" @if(in_array($tipe, ['edit_karyawan'])) disabled="" @if($data->nik == $karyawan->nik) selected @endif @endif >{{ $data->nama }} - {{ $data->nik }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback txt-danger">This is required.</div>
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-md-4">
                                <label class="form-label" for="pasangan">Pasangan</label>
                                <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="pasangan" id="pasangan" class="form-control numbers" required="" @if(in_array($tipe, ['edit_karyawan'])) value="{{ $karyawan->pasangan }}" @endif>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="anak">Anak</label>
                                <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="anak" id="anak" class="form-control numbers" required="" @if(in_array($tipe, ['edit_karyawan'])) value="{{ $karyawan->anak }}" @endif>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="tambahan">Tambahan</label>
                                <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="tambahan" id="tambahan" class="form-control numbers" required="" @if(in_array($tipe, ['edit_karyawan'])) value="{{ $karyawan->tambahan }}" @endif>
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-md-4">
                                <label class="form-label" for="no_ktp">No KTP</label>
                                <input name="no_ktp" id="no_ktp" class="form-control" type="text" required="" @if(in_array($tipe, ['edit_karyawan'])) value="{{ $karyawan->no_ktp }}" @endif>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="no_kpj">No KPJ</label>
                                <input name="no_kpj" id="no_kpj" class="form-control" type="text" required="" @if(in_array($tipe, ['edit_karyawan'])) value="{{ $karyawan->no_kpj }}" @endif>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="no_jkn">No JKN</label>
                                <input name="no_jkn" id="no_jkn" class="form-control" type="text" required="" @if(in_array($tipe, ['edit_karyawan'])) value="{{ $karyawan->no_jkn }}" @endif>
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-md-4">
                                <label class="form-label" for="nama_rek">Rekening Atas Nama</label>
                                <input name="nama_rek" id="nama_rek" class="form-control" type="text" required="" @if(in_array($tipe, ['edit_karyawan'])) value="{{ $karyawan->nama_rek }}" @endif>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="no_rek">No. Rekening</label>
                                <input name="no_rek" id="no_rek" class="form-control" type="text" required="" @if(in_array($tipe, ['edit_karyawan'])) value="{{ $karyawan->no_rek }}" @endif>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="nama_bank">Nama Bank</label>
                                <input name="nama_bank" id="nama_bank" class="form-control" type="text" required="" @if(in_array($tipe, ['edit_karyawan'])) value="{{ $karyawan->nama_bank }}" @endif>
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
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script type="text/javascript">
    function use_number(node) {
      var empty_val = false;
      const value = node.value;
      if (node.value == '')
        empty_val = true;
      node.type = 'number';
      if (!empty_val)
        node.value = Number(value.replace(/,/g, '')); // or equivalent per locale
    }
    // 
    function use_text(node) {
      var empty_val = false;
      const value = Number(node.value);
      if (node.value == '')
        empty_val = true;
      node.type = 'text';
      if (!empty_val)
        node.value = value.toLocaleString('en');  // or other formatting
    }
</script>
@endsection