@extends('layouts.simple.master')
@section('title', 'Formulir Penilaian Karyawan')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>@if ($status == 'edit') View @else Add @endif</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Penilaian Karyawan</li>
<li class="breadcrumb-item active">@if ($status == 'edit') View @else Add @endif</li>
@endsection
<!-- https://makitweb.com/auto-populate-dropdown-with-jquery-ajax-in-laravel/ -->
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if ($status == 'edit')
                    <form action="{{ route('penilaian.update', (new App\Http\Controllers\OpenDataController)->encryptWithKey($penilaian->id)) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @method('PATCH')
                        @else
                        <form action="{{ route('penilaian.store') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @endif
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="nik_karyawan">Karyawan</label>
                                    <select class="form-select js-example-basic-single col-sm-12" id="nik_karyawan" name="nik_karyawan" required="">
                                        <option selected="" disabled="" value="">Choose...</option>
                                        @foreach ($karyawan as $data)
                                        <option value="{{ $data->nik }}" @if ($status=='edit' ) @if($data->nik == $penilaian->nik_karyawan) selected @endif @endif >{{ $data->nama }} - {{ $data->nik }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br />
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="id_tipe">Tipe Penilaian</label>
                                    <select class="form-select" id="id_tipe" name="id_tipe" required="">
                                        <option selected="" disabled="" value="">Choose...</option>
                                        @foreach ($tipe as $data)
                                        <option value="{{ $data->id }}" @if ($status=='edit' ) @if($data->id == $penilaian->id_tipe) selected @endif @endif >{{ $data->tipe_penilaian }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br />
                            <div class="row g-3">
                                <div class="col-md-4" id="div_penilai_1">
                                    <label class="form-label" for="penilai_1">Penilai 1</label>
                                    <select class="form-select js-example-basic-single col-sm-12" id="penilai_1" name="penilai_1" required="">
                                        <option selected="" disabled="" value="">Choose...</option>
                                        <!-- @foreach ($karyawan as $data)
                                        <option value="{{ $data->nik }}" @if ($status == 'edit') @if($data->nik == $penilaian->nik_penilai_1) selected @endif @endif >{{ $data->nama }} - {{ $data->nik }}</option>
                                        @endforeach -->
                                    </select>
                                    <br />
                                </div>
                                <div class="col-md-4" id="div_penilai_2">
                                    <label class="form-label" for="penilai_2">Penilai 2</label>
                                    <select class="form-select js-example-basic-single col-sm-12" id="penilai_2" name="penilai_2">
                                        <option selected="" value="">Choose...</option>
                                        <!-- @foreach ($karyawan as $data)
                                        <option value="{{ $data->nik }}" @if ($status == 'edit') @if($data->nik == $penilaian->nik_penilai_2) selected @endif @endif >{{ $data->nama }} - {{ $data->nik }}</option>
                                        @endforeach -->
                                    </select>
                                    <br />
                                </div>
                                <div class="col-md-4" id="div_penilai_3">
                                    <label class="form-label" for="penilai_3">Penilai 3</label>
                                    <select class="form-select js-example-basic-single col-sm-12" id="penilai_3" name="penilai_3">
                                        <option selected="" value="">Choose...</option>
                                        <!-- @foreach ($karyawan as $data)
                                        <option value="{{ $data->nik }}" @if ($status == 'edit') @if($data->nik == $penilaian->nik_penilai_3) selected @endif @endif >{{ $data->nama }} - {{ $data->nik }}</option>
                                        @endforeach -->
                                    </select>
                                </div>
                            </div>
                            <br />
                            @if ($status == 'create') <button class="btn btn-success" type="submit">Submit</button> | @endif<a href="{{ route('penilaian.index') }}" class="btn btn-warning" type="button">Back</a>
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
    $(document).ready(function() {
        // var _url = window.location.origin;
        $("#penilai_1").prop('disabled', true);
        $("#penilai_2").prop('disabled', true);
        $("#penilai_3").prop('disabled', true);
        $('#div_penilai_1').hide();
        $('#div_penilai_2').hide();
        $('#div_penilai_3').hide();
        $("#nik_karyawan").change(function() {
            $('#id_tipe').prop('selectedIndex', 0);
        });
        $("#id_tipe").change(function() {
            var selectedText = $(this).find("option:selected").text();
            var selectedValue = $(this).val();
            var id = $('#nik_karyawan').val();
            // alert("Selected Text: " + selectedText + " Value: " + selectedValue);
            if (selectedValue == 1 || selectedValue == 4 || selectedValue == 5) {
                $("#div_penilai_1").show();
                $('#div_penilai_2').hide();
                $('#div_penilai_3').hide();
                $("#penilai_1").prop('disabled', false);
                $("#penilai_2").prop('disabled', true);
                $("#penilai_3").prop('disabled', true);
                // Empty the dropdown
                $('#penilai_1').find('option').not(':first').remove();
                // AJAX request 
                $.ajax({
                    // url: _url + '/matta/penilaian/layer/' + id,
                    url: "{{ route('penilaian_layer') }}" + "/" + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var layer1 = response['layer'].layer1;
                        var len = 0;
                        if (response['karyawan'] != null) {
                            len = response['karyawan'].length;
                        }
                        if (len > 0) {
                            // Read data and create <option >
                            for (var i = 0; i < len; i++) {
                                var nik = response['karyawan'][i].nik;
                                var nama = response['karyawan'][i].nama;
                                if (layer1 == nik) {
                                    var option = "<option value='" + nik + "' selected>" + nama + " - " + nik + "</option>";
                                } else {
                                    var option = "<option value='" + nik + "'>" + nama + " - " + nik + "</option>";
                                }
                                $("#penilai_1").append(option);
                            }
                        }
                    }
                });
            } else {
                $("#penilai_1").prop('disabled', false);
                $("#penilai_2").prop('disabled', false);
                $("#penilai_3").prop('disabled', false);
                $('#div_penilai_1').show();
                $('#div_penilai_2').show();
                $('#div_penilai_3').show();
                // penilai 1
                $('#penilai_1').find('option').not(':first').remove();
                $.ajax({
                    // url: _url + '/matta/penilaian/layer/' + id,
                    url: "{{ route('penilaian_layer') }}" + "/" + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var layer = response['layer'].layer1;
                        var len = 0;
                        if (response['karyawan'] != null) {
                            len = response['karyawan'].length;
                        }
                        if (len > 0) {
                            // Read data and create <option >
                            for (var i = 0; i < len; i++) {
                                var nik = response['karyawan'][i].nik;
                                var nama = response['karyawan'][i].nama;
                                if (layer == nik) {
                                    var option = "<option value='" + nik + "' selected>" + nama + " - " + nik + "</option>";
                                } else {
                                    var option = "<option value='" + nik + "'>" + nama + " - " + nik + "</option>";
                                }
                                $("#penilai_1").append(option);
                            }
                        }
                    }
                });
                // penilai 2
                $('#penilai_2').find('option').not(':first').remove();
                $.ajax({
                    // url: _url + '/matta/penilaian/layer/' + id,
                    url: "{{ route('penilaian_layer') }}" + "/" + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var layer = response['layer'].layer2;
                        var len = 0;
                        if (response['karyawan'] != null) {
                            len = response['karyawan'].length;
                        }
                        if (len > 0) {
                            // Read data and create <option >
                            for (var i = 0; i < len; i++) {
                                var nik = response['karyawan'][i].nik;
                                var nama = response['karyawan'][i].nama;
                                if (layer == nik) {
                                    var option = "<option value='" + nik + "' selected>" + nama + " - " + nik + "</option>";
                                } else {
                                    var option = "<option value='" + nik + "'>" + nama + " - " + nik + "</option>";
                                }
                                $("#penilai_2").append(option);
                            }
                        }
                    }
                });
                // penilai 3
                $('#penilai_3').find('option').not(':first').remove();
                $.ajax({
                    // url: _url + '/matta/penilaian/layer/' + id,
                    url: "{{ route('penilaian_layer') }}" + "/" + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var layer = response['layer'].layer3;
                        var len = 0;
                        if (response['karyawan'] != null) {
                            len = response['karyawan'].length;
                        }
                        if (len > 0) {
                            // Read data and create <option >
                            for (var i = 0; i < len; i++) {
                                var nik = response['karyawan'][i].nik;
                                var nama = response['karyawan'][i].nama;
                                if (layer == nik) {
                                    var option = "<option value='" + nik + "' selected>" + nama + " - " + nik + "</option>";
                                } else {
                                    var option = "<option value='" + nik + "'>" + nama + " - " + nik + "</option>";
                                }
                                $("#penilai_3").append(option);
                            }
                        }
                    }
                });
            }
        });
    });
</script>
@endsection