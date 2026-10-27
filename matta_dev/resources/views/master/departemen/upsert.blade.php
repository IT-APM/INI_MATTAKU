@extends('layouts.simple.master')
@section('title', 'Validation Forms')

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
<li class="breadcrumb-item">Divisi</li>
<li class="breadcrumb-item active">@if ($status == 'edit') Edit @else Add @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                        @if ($status == 'edit')
                        <form action="{{ route('departemen.update', $departemen->id) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @method('PATCH')
                        @else
                        <form action="{{ route('departemen.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @endif
                            @csrf
                            <div class="row m-t-10">
                                <div class="col-md-4">
                                    <label class="form-label" for="kode_perusahaan">Perusahaan</label>
                                    <select class="form-select" id="kode_perusahaan" name="kode_perusahaan" required="">
                                        <option selected="" value="">Pilih...</option>
                                        @foreach ($perusahaan as $data)
                                        <option value="{{ $data->id }}" @if ($status=='edit' ) @if($data->id == $departemen->kode_perusahaan) selected @endif @endif >{{ $data->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row m-t-10">
                                <div class="col-md-4">
                                    <label class="form-label" for="kode_divisi">Direktorat</label>
                                    <select class="form-select" id="kode_divisi" name="kode_divisi" required="">
                                        <option selected="" value="">Pilih...</option>
                                        @if ($status=='edit' ) 
                                        @foreach ($divisi as $data)
                                        <option value="{{ $data->id }}" @if($data->id == $departemen->kode_divisi) selected @endif >{{ $data->nama }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row m-t-10">
                                <div class="col-md-4">
                                    <label class="form-label" for="nama">Divisi</label>
                                    <input name="nama" id="nama" class="form-control" type="text" required="" @if ($status=='edit' ) value="{{$departemen->nama}}" @endif>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="is_active">Aktif</label>
                                    <div class="media-body text-start icon-state switch-outline">
                                        <label class="switch">
                                            <input name="is_active" id="is_active" type="checkbox" @if ($status=='edit' ) @if ($departemen->is_active == 1) checked="" @endif @endif><span class="switch-state bg-success"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ route('departemen.index') }}" class="btn btn-warning" type="button">Cancel</a>
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
$(document).ready(function() {
    var _kode_perusahaan = 0;
    var _kode_divisi = 0;
    // 
    $("#kode_perusahaan").change(function() {
        var _kode_perusahaan = $(this).val();
        $('#kode_divisi').find('option').not(':first').remove();
        // AJAX request 
        $.ajax({
            url: "{{ route('get_divisi') }}"+"/"+_kode_perusahaan,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                var len = 0;
                if (response != null) {
                    len = response.length;
                }
                if (len > 0) {
                    for (var i = 0; i < len; i++) {
                        var id = response[i]['id'];
                        var nama = response[i]['nama'];
                        var option = "<option value='" + id + "'>" + nama + "</option>";
                        $("#kode_divisi").append(option);
                    }
                }
            }
        });
    });
    // 
});
</script>
@endsection