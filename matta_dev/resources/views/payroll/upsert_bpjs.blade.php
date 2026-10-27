@extends('layouts.simple.master')
@section('title', 'Payroll Master')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>@if(in_array($tipe, ['edit_bpjstk', 'edit_bpjsks', 'edit_bpjsmx'])) Edit @else Add @endif</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Payroll Master</li>
<li class="breadcrumb-item">@if(in_array($tipe, ['bpjstk', 'edit_bpjstk'])) BPJS TK @elseif(in_array($tipe, ['bpjsks', 'edit_bpjsks'])) BPJS Kesehatan @elseif(in_array($tipe, ['bpjsmx', 'edit_bpjsmx'])) BPJS Maksimal @endif</li>
<li class="breadcrumb-item active">@if(in_array($tipe, ['edit_bpjstk', 'edit_bpjsks', 'edit_bpjsmx'])) Edit @else Add @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if ($status == 'edit')
                    <form action="{{ route('payroll.upsert_master', ['id_data'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($bpjs->id), 'tipe'=>$tipe]) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @else
                    <form action="{{ route('payroll.upsert_master', ['tipe'=>$tipe]) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @endif
                    @csrf
                        <input type="hidden" id="_id" name="_id" value="{{ $id }}">
                        <div class="row g-3 mb-2">
                            <div class="col-md-4">
                                <label class="form-label" for="nama">Komponen</label>
                                <input name="nama" id="nama" class="form-control" type="text" required="" @if(in_array($tipe, ['edit_bpjstk', 'edit_bpjsks', 'edit_bpjsmx'])) value="{{ $bpjs->nama }}" @endif>
                            </div>
                            <div class="col-md-4">
                                @if(in_array($tipe, ['bpjstk', 'bpjsks', 'edit_bpjstk', 'edit_bpjsks']))
                                <label class="form-label" for="persen">Persen (%)</label>
                                <input min=0 type="number" name="persen" id="persen" step="0.01" class="form-control numbers" required="" @if(in_array($tipe, ['edit_bpjstk', 'edit_bpjsks'])) value="{{ $bpjs->persen }}" @endif>
                                @elseif(in_array($tipe, ['bpjsmx', 'edit_bpjsmx']))
                                <label class="form-label" for="angka">Angka</label>
                                <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="angka" id="angka" class="form-control numbers" required="" @if(in_array($tipe, ['edit_bpjsmx'])) value="{{ $bpjs->angka }}" @endif>
                                @endif
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