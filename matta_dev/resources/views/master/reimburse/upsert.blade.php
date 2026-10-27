@extends('layouts.simple.master')
@section('title', 'Plafon Kesehatan')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>@if ($status == 'edit') Edit @else Add @endif</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item active">Plafon Kesehatan</li>
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
                    @if ($status == 'edit')
                    <form action="{{ route('plafon.update', $plafon->id) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @method('PATCH')
                        @else
                        <form action="{{ route('plafon.store') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @endif
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="nik_karyawan">Karyawan</label>
                                    <select class="form-select js-example-basic-single col-sm-12" id="nik_karyawan" name="nik_karyawan" required="" @if ($status=='edit' ) disabled="true" @endif>
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        @foreach ($karyawan as $data)
                                        <option value="{{ $data->nik }}" @if ($status=='edit' ) @if($data->nik == $plafon->nik_karyawan) selected @endif @endif >{{ $data->nama }} - {{ $data->nik }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="periode">Periode</label>
                                    <input type="number" min="0" name="periode" id="periode" class="form-control numbers" required="" @if ($status=='edit' ) value="{{$plafon->periode}}" disabled="true" @endif >
                                </div>
                            </div>
                            <br />
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="kesehatan">Plafon kesehatan</label>
                                    <input type="number" min="0" name="kesehatan" id="kesehatan" class="form-control numbers" required="" @if ($status=='edit' ) value="{{$plafon->kesehatan}}" @endif oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="lensa">Plafon lensa</label>
                                    <input type="number" min="0" name="lensa" id="lensa" class="form-control numbers" required="" @if ($status=='edit' ) value="{{$plafon->lensa}}" @endif oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="frame">Plafon frame</label>
                                    <input type="number" min="0" name="frame" id="frame" class="form-control numbers" required="" @if ($status=='edit' ) value="{{$plafon->frame}}" @endif oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)">
                                </div>
                            </div>
                            <br />
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="kehamilan">Plafon kehamilan</label>
                                    <input type="number" min="0" name="kehamilan" id="kehamilan" class="form-control numbers" required="" @if ($status=='edit' ) value="{{$plafon->kehamilan}}" @endif oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="persalinan_normal">Plafon persalinan normal</label>
                                    <input type="number" min="0" name="persalinan_normal" id="persalinan_normal" class="form-control numbers" required="" @if ($status=='edit' ) value="{{$plafon->persalinan_normal}}" @endif oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="persalinan_caesar">Plafon persalinan caesar</label>
                                    <input type="number" min="0" name="persalinan_caesar" id="persalinan_caesar" class="form-control numbers" required="" @if ($status=='edit' ) value="{{$plafon->persalinan_caesar}}" @endif oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)">
                                </div>
                            </div>
                            <br />
                            <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ route('plafon.index') }}" class="btn btn-warning" type="button">Cancel</a>
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
//   if(value==0){
//     alert('Jumlah tidak boleh 0!');
//   }
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