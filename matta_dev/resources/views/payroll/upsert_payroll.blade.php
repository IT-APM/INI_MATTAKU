@extends('layouts.simple.master')
@section('title', 'Payroll Master')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>@if(in_array($tipe, ['edit_trxpayroll'])) Edit @else Add @endif</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Payroll</li>
<li class="breadcrumb-item">Transaction</li>
<li class="breadcrumb-item active">@if(in_array($tipe, ['edit_trxpayroll'])) Edit @else Add @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if ($status == 'edit')
                    <form class="needs-validation" novalidate="" action="{{ route('payroll.upsert_master', ['id_data'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($trxpayroll->id), 'tipe'=>$tipe]) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @else
                    <form class="needs-validation" novalidate="" action="{{ route('payroll.upsert_master', ['tipe'=>$tipe]) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @endif
                    @csrf
                        <input type="hidden" id="_id" name="_id" value="{{ $id }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label" for="nik">Karyawan</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="nik" name="nik" required="">
                                    <option selected="" disabled="" value="">Choose...</option>
                                    @foreach ($_karyawan as $data)
                                    <option value="{{ $data->nik }}" @if(in_array($tipe, ['edit_trxpayroll'])) disabled="" @if($data->nik == $trxpayroll->nik) selected @endif @endif >{{ $data->nama }} - {{ $data->nik }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback txt-danger">This is required.</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="bulan">Bulan</label>
                                <input min=1 type="number" name="bulan" id="bulan" class="form-control numbers" required="" @if(in_array($tipe, ['edit_trxpayroll'])) disabled="" value="{{ $trxpayroll->bulan }}" @endif>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="tahun">Tahun</label>
                                <input min=1 type="number" name="tahun" id="tahun" class="form-control numbers" required="" @if(in_array($tipe, ['edit_trxpayroll'])) disabled="" value="{{ $trxpayroll->tahun }}" @endif>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="gapok">Gaji Pokok</label>
                                <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="gapok" id="gapok" class="form-control numbers" required="" @if(in_array($tipe, ['edit_trxpayroll'])) value="{{ $trxpayroll->gapok }}" @endif>
                            </div>
                        </div>
                        <hr>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="row">
                                    <h5>Penambahan</h5>
                                    <div class="col-md-6">
                                        <label class="form-label">Komponen</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Angka</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <h5>Pengurangan</h5>
                                    <div class="col-md-6">
                                        <label class="form-label">Komponen</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Angka</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {{-- add --}}
                            <div class="col-md-6">
                                @forelse($add_trx as $_add_trx)
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <select class="form-select js-example-basic-single col-sm-12" id="add_id" name="add_id" required="">
                                            <option selected="" disabled="" value="">Choose...</option>
                                            @foreach ($add as $data)
                                            <option disabled="" value="{{ $data->id }}" @if(in_array($tipe, ['edit_trxpayroll'])) @if($data->id == $_add_trx->id_addsub) selected @endif @endif >{{ $data->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="add_angka_{{ $_add_trx->id }}" id="add_angka_{{ $_add_trx->id }}" class="form-control numbers" required="" @if(in_array($tipe, ['edit_trxpayroll'])) value="{{ $_add_trx->angka }}" @endif>
                                    </div>
                                </div>
                                @empty
                                {{-- jika tidak ada --}}
                                @foreach ($add as $data)
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <select class="form-select js-example-basic-single col-sm-12" id="add_id" name="add_id" required="">
                                            <option selected="" disabled="" value="">Choose...</option>
                                            <option disabled="" value="{{ $data->id }}" @if($data->id == $data->id) selected @endif>{{ $data->nama }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="add_angka_{{ $data->id }}" id="add_angka_{{ $data->id }}" class="form-control numbers" required="">
                                    </div>
                                </div>
                                @endforeach
                                @endforelse
                            </div>
                            {{-- sub --}}
                            <div class="col-md-6">
                                @forelse($sub_trx as $_sub_trx)
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <select class="form-select js-example-basic-single col-sm-12" id="sub_id" name="sub_id" required="">
                                            <option selected="" disabled="" value="">Choose...</option>
                                            @foreach ($sub as $data)
                                            <option disabled="" value="{{ $data->id }}" @if(in_array($tipe, ['edit_trxpayroll'])) @if($data->id == $_sub_trx->id_addsub) selected @endif @endif >{{ $data->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="sub_angka_{{ $_sub_trx->id }}" id="sub_angka_{{ $_sub_trx->id }}" class="form-control numbers" required="" @if(in_array($tipe, ['edit_trxpayroll'])) value="{{ $_sub_trx->angka }}" @endif>
                                    </div>
                                </div>
                                @empty
                                {{-- jika tidak ada --}}
                                @foreach ($sub as $data)
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <select class="form-select js-example-basic-single col-sm-12" id="sub_id" name="sub_id" required="">
                                            <option selected="" disabled="" value="">Choose...</option>
                                            <option disabled="" value="{{ $data->id }}" @if($data->id == $data->id) selected @endif>{{ $data->nama }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="sub_angka_{{ $data->id }}" id="sub_angka_{{ $data->id }}" class="form-control numbers" required="">
                                    </div>
                                </div>
                                @endforeach
                                @endforelse
                            </div>
                        </div>
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ route('payroll.index') }}" class="btn btn-warning" type="button">Cancel</a>
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