@extends('layouts.simple.master')
@section('title', 'Validation Forms')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>@if($status == 'edit') Edit @else Add @endif</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item">Salary Structure</li>
<li class="breadcrumb-item active">@if($status == 'edit') Edit @else Add @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(session('error'))
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('error') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if($status == 'edit')
                    <form action="{{ route('salary_str.update', $trx->id) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @method('PATCH')
                    @else
                    <form action="{{ route('salary_str.store') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @endif
                    @csrf
                        <div class="row g-3 mb-2">
                            <div class="col-md-4">
                                <label class="form-label" for="id_grade">Level # Grade</label>
                                <select class="form-select" id="id_grade" name="id_grade" required="">
                                    <option selected="" value="">Pilih...</option>
                                    @foreach ($grade as $data)
                                    <option value="{{ $data->id }}" @if($status=='edit' ) @if($data->id == $trx->id_grade) selected @endif @endif @if(session('error')) @if($data->id == old('id_grade')) selected @endif @endif>{{ $data->level }} # {{ $data->grade }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="tingkat">Tingkat</label>
                                <select class="form-select" id="tingkat" name="tingkat" required="">
                                    <option selected="" value="">Pilih...</option>
                                    @foreach ($arr_tingkat as $data)
                                    <option value="{{ $data }}" @if($status=='edit') @if($data == $trx->tingkat) selected @endif @endif @if(session('error')) @if($data == old('tingkat')) selected @endif @endif>{{ $data }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="tahun">Tahun</label>
                                <input min=1 type="number" name="tahun" id="tahun" class="form-control numbers" required="" @if($status=='edit') value="{{ $trx->tahun }}" @endif @if(session('error')) value="{{ old('tahun') }}" @endif>
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-md-4">
                                <label class="form-label" for="gapok">Gaji Pokok</label>
                                <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="gapok" id="gapok" class="form-control numbers" required="" @if($status=='edit') value="{{ $trx->gapok }}" @endif @if(session('error')) value="{{ old('gapok') }}" @endif>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="t_makan">Tunjangan Makan</label>
                                <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="t_makan" id="t_makan" class="form-control numbers" required="" @if($status=='edit') value="{{ $trx->t_makan }}" @endif @if(session('error')) value="{{ old('t_makan') }}" @endif>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="t_transport">Tunjangan Transport</label>
                                <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="t_transport" id="t_transport" class="form-control numbers" required="" @if($status=='edit') value="{{ $trx->t_transport }}" @endif @if(session('error')) value="{{ old('t_transport') }}" @endif>
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-md-4">
                                <label class="form-label" for="t_jabatan">Tunjangan Jabatan</label>
                                <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="t_jabatan" id="t_jabatan" class="form-control numbers" required="" @if($status=='edit') value="{{ $trx->t_jabatan }}" @endif @if(session('error')) value="{{ old('t_jabatan') }}" @endif>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="t_telekomunikasi">Tunjangan Telekomunikasi</label>
                                <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="t_telekomunikasi" id="t_telekomunikasi" class="form-control numbers" required="" @if($status=='edit') value="{{ $trx->t_telekomunikasi }}" @endif @if(session('error')) value="{{ old('t_telekomunikasi') }}" @endif>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="t_lain">Tunjangan Lain-Lain</label>
                                <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="t_lain" id="t_lain" class="form-control numbers" required="" @if($status=='edit') value="{{ $trx->t_lain }}" @endif @if(session('error')) value="{{ old('t_lain') }}" @endif>
                            </div>
                        </div>
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ route('salary_str.index') }}" class="btn btn-warning" type="button">Cancel</a>
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