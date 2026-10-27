@extends('layouts.simple.master')
@section('title', 'Data Master Pekerjaan')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>@if ($status == 'edit') Edit @else Add @endif</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item">Pekerjaan</li>
<li class="breadcrumb-item active">@if ($status == 'edit') Edit @else Add @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if ($status == 'edit')
                    <form action="{{ route('pekerjaan_master.update', $trx->id) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @method('PATCH')
                        @else
                        <form action="{{ route('pekerjaan_master.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @endif
                            @csrf
                            <div class="row pt-0">
                                <div class="col-md-4">
                                    <label class="form-label" for="id_bidang">Bidang Pekerjaan</label>
                                    <select class="form-select" id="id_bidang" name="id_bidang" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        @foreach ($bidang as $data)
                                        <option value="{{ $data->id }}" @if ($status=='edit' ) @if($data->id ==
                                            $trx->id_bidang) selected @endif @endif >{{ $data->bidang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="pekerjaan">Pekerjaan</label>
                                    <input name="pekerjaan" id="pekerjaan" class="form-control" type="text" required=""
                                        @if ($status=='edit' ) value="{{$trx->pekerjaan}}" @endif>
                                </div>
                            </div>
                            <div class="row pt-0">
                                <div class="col-md-8">
                                    <label class="form-label" for="keterangan">Keterangan</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan"
                                        rows="4">@if ($status == 'edit') {{ $trx->keterangan }} @endif</textarea>
                                </div>
                            </div>
                            {{--
                            <div class="row pt-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="tugas">Tugas-tugas</label>
                                    <textarea class="form-control" id="tugas" name="tugas" rows="4"
                                        required="">@if ($status == 'edit') {{ $trx->tugas }} @endif</textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="wewenang">Wewenang</label>
                                    <textarea class="form-control" id="wewenang" name="wewenang" rows="4"
                                        required="">@if ($status == 'edit') {{ $trx->wewenang }} @endif</textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="tanggung_jawab">Tanggung jawab</label>
                                    <textarea class="form-control" id="tanggung_jawab" name="tanggung_jawab" rows="4"
                                        required="">@if ($status == 'edit') {{ $trx->tanggung_jawab }} @endif</textarea>
                                </div>
                            </div>
                            --}}
                            <div class="row pt-3">
                                <div class="col-md-4">
                                    <button class="btn btn-success" type="submit">Submit</button>
                                    | <a href="{{ route('pekerjaan_master.index') }}" class="btn btn-warning" type="button">Cancel</a>
                                </div>
                            </div>
                        </form>
                        
                            <div class="row pt-2">
                                <div class="col-md-4">
                                    @if ($status == 'edit')
                                    <form action="{{ route('pekerjaan_master.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Are you sure want to delete this data?');">
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
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/sweet-alert/app.js')}}"></script>
<script type="text/javascript">

</script>
@endsection