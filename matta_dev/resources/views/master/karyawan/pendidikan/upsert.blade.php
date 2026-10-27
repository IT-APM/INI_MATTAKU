@extends('layouts.simple.master')
@section('title', 'Pendidikan')

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
<li class="breadcrumb-item active">Pendidikan</li>
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
                    <form action="{{ route('karyawanpendidikan.update', [$trx->id, 'nik'=>request()->nik, 'karyawan_id'=>request()->karyawan_id, 'src'=>request()->src]) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @method('PATCH')
                    @else
                    <form action="{{ route('karyawanpendidikan.store', ['nik'=>request()->nik, 'karyawan_id'=>request()->karyawan_id, 'src'=>request()->src]) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @endif
                    @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="id_jenjang">Jenjang Pendidikan</label>
                                <select class="form-select" id="id_jenjang" name="id_jenjang" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($pendidikanjenjang as $data)
                                    <option value="{{ $data->id }}" @if ($status=='edit' ) @if($data->id == $trx->id_jenjang) selected @endif @endif >{{ $data->jenjang }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="nama">Nama Sekolah/Kampus/Universitas</label>
                                <input name="nama" id="nama" class="form-control" type="text" required="" @if ($status=='edit' ) value="{{$trx->nama}}" @endif>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="jurusan_prodi">Jurusan/Program Studi</label>
                                <input name="jurusan_prodi" id="jurusan_prodi" class="form-control" type="text" @if ($status=='edit' ) value="{{$trx->jurusan_prodi}}" @endif>
                            </div>
                        </div>
                        <br />
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ url()->previous() }}" class="btn btn-warning" type="button">Cancel</a>
                    </form>
                    @if ($status=='edit' )
                    <br/>
                    <form action="{{ route('karyawanpendidikan.destroy', [$trx->id, 'nik'=>request()->nik, 'karyawan_id'=>request()->karyawan_id, 'src'=>request()->src]) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to delete this data?');">
                    @csrf
                    @method('DELETE')
                        <button class="btn btn-danger" type="submit">Delete</button>
                    </form>
                    @endif
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

@endsection