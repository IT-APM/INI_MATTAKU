@extends('layouts.simple.master')
@section('title', 'Formulir Rekomendasi Karyawan')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Add</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Rekomendasi Karyawan</li>
<li class="breadcrumb-item active">Add</li>
@endsection
<!-- https://makitweb.com/auto-populate-dropdown-with-jquery-ajax-in-laravel/ -->
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Pengaju: {{ $pengaju }}</h5>
                </div>
                <div class="card-body">
                    @if(Session::has('error'))
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        {!! session('error') !!}
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <form action="{{ route('rekomendasi.store') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @csrf
                        <input type="hidden" id="nik_atasan" name="nik_atasan" value="{{ $nik_atasan }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="nik_karyawan">Karyawan</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="nik_karyawan" name="nik_karyawan" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($karyawan as $data)
                                    <option value="{{ $data->nik }}">{{ $data->nama }} - {{ $data->nik }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="terhitung_tanggal">Terhitung Tanggal</label>
                                <input class="form-control" name="terhitung_tanggal" id="terhitung_tanggal" type="date" required="">
                            </div>
                        </div>
                        <br />
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="jenis_pengajuan">Jenis Pengajuan</label>
                                <select class="form-select" id="jenis_pengajuan" name="jenis_pengajuan">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($pengajuan as $data)
                                    <option value="{{ $data->id }}">{{ $data->pengajuan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="dasar_pengajuan">Dasar Pengajuan</label>
                                <textarea class="form-control" id="dasar_pengajuan" name="dasar_pengajuan" rows="4" required=""></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="catatan_pengajuan">Catatan Pengajuan</label>
                                <textarea class="form-control" id="catatan_pengajuan" name="catatan_pengajuan" rows="4"></textarea>
                            </div>
                        </div>
                        <hr />
                        <!-- start div_id_2 Kenaikan jabatan -->
                        <div class="row g-3" id="div_id_2">
                            <div class="col-md-4">
                                <label class="form-label" for="pengajuan_id_2">Jenis Pengajuan</label>
                                <input name="pengajuan_id_2" id="pengajuan_id_2" class="form-control" type="text" required="" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="sebelum_id_2">Sebelum</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="sebelum_id_2" name="sebelum_id_2" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($jabatan as $data)
                                    <option value="{{ $data->jabatan }}">{{ $data->jabatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="setelah_id_2">Setelah</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="setelah_id_2" name="setelah_id_2" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($jabatan as $data)
                                    <option value="{{ $data->jabatan }}">{{ $data->jabatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <a id='del_div_id_2' class="pull-left btn btn-danger"><i class="fa fa-minus"></i> Delete Row</a>
                            </div>
                            <hr />
                        </div>
                        <!-- end div_id_2 -->
                        <!-- start div_id_3 Kenaikan level -->
                        <div class="row g-3" id="div_id_3">
                            <div class="col-md-4">
                                <label class="form-label" for="pengajuan_id_3">Jenis Pengajuan</label>
                                <input name="pengajuan_id_3" id="pengajuan_id_3" class="form-control" type="text" required="" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="sebelum_id_3">Sebelum</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="sebelum_id_3" name="sebelum_id_3" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($level as $data)
                                    <option value="{{ $data->level }}">{{ $data->level }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="setelah_id_3">Setelah</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="setelah_id_3" name="setelah_id_3" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($level as $data)
                                    <option value="{{ $data->level }}">{{ $data->level }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <a id='del_div_id_3' class="pull-left btn btn-danger"><i class="fa fa-minus"></i> Delete Row</a>
                            </div>
                            <hr />
                        </div>
                        <!-- end div_id_3 -->
                        <!-- start div_id_4 Status kepegawaian -->
                        <div class="row g-3" id="div_id_4">
                            <div class="col-md-4">
                                <label class="form-label" for="pengajuan_id_4">Jenis Pengajuan</label>
                                <input name="pengajuan_id_4" id="pengajuan_id_4" class="form-control" type="text" required="" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="sebelum_id_4">Sebelum</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="sebelum_id_4" name="sebelum_id_4" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($rencana as $data)
                                    <option value="{{ $data->rencana }}">{{ $data->rencana }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="setelah_id_4">Setelah</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="setelah_id_4" name="setelah_id_4" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($rencana as $data)
                                    <option value="{{ $data->rencana }}">{{ $data->rencana }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <a id='del_div_id_4' class="pull-left btn btn-danger"><i class="fa fa-minus"></i> Delete Row</a>
                            </div>
                            <hr />
                        </div>
                        <!-- end div_id_4 -->
                        <!-- start div_id_5 Insentif -->
                        <div class="row g-3" id="div_id_5">
                            <div class="col-md-4">
                                <label class="form-label" for="pengajuan_id_5">Jenis Pengajuan</label>
                                <input name="pengajuan_id_5" id="pengajuan_id_5" class="form-control" type="text" required="" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="sebelum_id_5">Nilai Insentif (Rupiah)</label>
                                <input type="number" name="sebelum_id_5" id="sebelum_id_5" class="form-control numbers" oninput="validity.valid||(value='');" required="">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="setelah_id_5">Durasi (bulan)</label>
                                <input type="number" name="setelah_id_5" id="setelah_id_5" class="form-control numbers" oninput="validity.valid||(value='');" required="">
                            </div>
                            <div class="col-md-12">
                                <a id='del_div_id_5' class="pull-left btn btn-danger"><i class="fa fa-minus"></i> Delete Row</a>
                            </div>
                            <hr />
                        </div>
                        <!-- end div_id_5 -->
                        <!-- start div_id_14 Nilai Prosentase Kenaikan Gaji -->
                        <div class="row g-3" id="div_id_14">
                            <div class="col-md-4">
                                <label class="form-label" for="pengajuan_id_14">Jenis Pengajuan</label>
                                <input name="pengajuan_id_14" id="pengajuan_id_14" class="form-control" type="text" required="" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="setelah_id_14">Nilai Prosentase Kenaikan Gaji</label>
                                <input type="number" name="setelah_id_14" id="setelah_id_14" oninput="validity.valid||(value='');" class="form-control numbers" required="">
                            </div>
                            <div class="col-md-12">
                                <a id='del_div_id_14' class="pull-left btn btn-danger"><i class="fa fa-minus"></i> Delete Row</a>
                            </div>
                            <hr />
                        </div>
                        <!-- end div_id_14 -->
                        <!-- start div_id_1000 Lainnya -->
                        <div class="row g-3" id="div_id_1000">
                            <div class="col-md-4">
                                <label class="form-label" for="pengajuan_id_1000">Nama Jenis Pengajuan</label>
                                <input name="pengajuan_id_1000" id="pengajuan_id_1000" class="form-control" type="text"
                                    required="">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="sebelum_id_1000">Sebelum</label>
                                <input type="text" name="sebelum_id_1000" id="sebelum_id_1000" class="form-control"
                                    required="">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="setelah_id_1000">Setelah</label>
                                <input type="text" name="setelah_id_1000" id="setelah_id_1000" class="form-control"
                                    required="">
                            </div>
                            <div class="col-md-12">
                                <a id='del_div_id_1000' class="pull-left btn btn-danger"><i class="fa fa-minus"></i>
                                    Delete Row</a>
                            </div>
                            <hr />
                        </div>
                        <!-- end div_id_1000 -->
                        <!-- <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="nik_reviewer2">Approval 2</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="nik_reviewer2"
                                    name="nik_reviewer2">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($approval as $data)
                                    <option value="{{ $data->nik }}">{{ $data->nama }} - {{ $data->nik }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="nik_reviewer3">Approval 3</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="nik_reviewer3"
                                    name="nik_reviewer3">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($approval as $data)
                                    <option value="{{ $data->nik }}">{{ $data->nama }} - {{ $data->nik }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br />
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fst-italic">Note: Approval 1 akan terisi secara otomatis, yaitu
                                    atasan langsung pengaju rekomendasi. Approal 2 dan 3 adalah opsional.</label>
                            </div>
                        </div> -->
                        <!-- <br />
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="dasar_pengajuan">Dasar Pengajuan</label>
                                <textarea class="form-control" id="dasar_pengajuan" name="dasar_pengajuan" rows="4"
                                    required=""></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="catatan_pengajuan">Catatan Pengajuan</label>
                                <textarea class="form-control" id="catatan_pengajuan" name="catatan_pengajuan"
                                    rows="4"></textarea>
                            </div>
                        </div> -->
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ URL::previous() }}" class="btn btn-warning" type="button">Back</a>
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
    // default semua hide dan disable=true
    pengajuan_id_2(0);
    pengajuan_id_3(0);
    pengajuan_id_4(0);
    pengajuan_id_5(0);
    pengajuan_id_14(0);
    pengajuan_id_1000(0);
    // 2 = Kenaikan jabatan
    $("#jenis_pengajuan").change(function() {
        var selectedValue = $(this).val();
        var selectedText = $(this).find("option:selected").text();
        if (selectedValue == 2) {
            pengajuan_id_2(1);
            $("#pengajuan_id_2").val(selectedText);
        }
    });
    $("#del_div_id_2").click(function() {
        $('#jenis_pengajuan').prop('selectedIndex', 0);
        pengajuan_id_2(0);
    });
    // 3 = Kenaikan level
    $("#jenis_pengajuan").change(function() {
        var selectedValue = $(this).val();
        var selectedText = $(this).find("option:selected").text();
        if (selectedValue == 3) {
            pengajuan_id_3(1);
            $("#pengajuan_id_3").val(selectedText);
        }
    });
    $("#del_div_id_3").click(function() {
        $('#jenis_pengajuan').prop('selectedIndex', 0);
        pengajuan_id_3(0);
    });
    // 4 = Status kepegawaian
    $("#jenis_pengajuan").change(function() {
        var selectedValue = $(this).val();
        var selectedText = $(this).find("option:selected").text();
        if (selectedValue == 4) {
            pengajuan_id_4(1);
            $("#pengajuan_id_4").val(selectedText);
        }
    });
    $("#del_div_id_4").click(function() {
        $('#jenis_pengajuan').prop('selectedIndex', 0);
        pengajuan_id_4(0);
    });
    // 5 = Insentif
    $("#jenis_pengajuan").change(function() {
        var selectedValue = $(this).val();
        var selectedText = $(this).find("option:selected").text();
        if (selectedValue == 5) {
            pengajuan_id_5(1);
            $("#pengajuan_id_5").val(selectedText);
        }
    });
    $("#del_div_id_5").click(function() {
        $('#jenis_pengajuan').prop('selectedIndex', 0);
        pengajuan_id_5(0);
    });
    // 14 = Nilai Prosentase Kenaikan Gaji
    $("#jenis_pengajuan").change(function() {
        var selectedValue = $(this).val();
        var selectedText = $(this).find("option:selected").text();
        if (selectedValue == 14) {
            pengajuan_id_14(1);
            $("#pengajuan_id_14").val(selectedText);
        }
    });
    $("#del_div_id_14").click(function() {
        $('#jenis_pengajuan').prop('selectedIndex', 0);
        pengajuan_id_14(0);
    });
    // 1000 = Lainnya
    $("#jenis_pengajuan").change(function() {
        var selectedValue = $(this).val();
        var selectedText = $(this).find("option:selected").text();
        if (selectedValue == 1000) {
            pengajuan_id_1000(1);
            // $("#pengajuan_id_1000").val(selectedText);
        }
    });
    $("#del_div_id_1000").click(function() {
        $('#jenis_pengajuan').prop('selectedIndex', 0);
        pengajuan_id_1000(0);
    });
    // 
    // 2 = Kenaikan jabatan
    function pengajuan_id_2(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            $('#div_id_2').show();
            $("#pengajuan_id_2").prop('disabled', false);
            $("#sebelum_id_2").prop('disabled', false);
            $("#setelah_id_2").prop('disabled', false);
        }
        // jika o maka non aktif
        else {
            $('#div_id_2').hide();
            $("#pengajuan_id_2").prop('disabled', true);
            $("#sebelum_id_2").prop('disabled', true);
            $("#setelah_id_2").prop('disabled', true);
        }
    }
    // 3 = Kenaikan level
    function pengajuan_id_3(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            $('#div_id_3').show();
            $("#pengajuan_id_3").prop('disabled', false);
            $("#sebelum_id_3").prop('disabled', false);
            $("#setelah_id_3").prop('disabled', false);
        }
        // jika o maka non aktif
        else {
            $('#div_id_3').hide();
            $("#pengajuan_id_3").prop('disabled', true);
            $("#sebelum_id_3").prop('disabled', true);
            $("#setelah_id_3").prop('disabled', true);
        }
    }
    // 4 = Status kepegawaian
    function pengajuan_id_4(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            $('#div_id_4').show();
            $("#pengajuan_id_4").prop('disabled', false);
            $("#sebelum_id_4").prop('disabled', false);
            $("#setelah_id_4").prop('disabled', false);
        }
        // jika o maka non aktif
        else {
            $('#div_id_4').hide();
            $("#pengajuan_id_4").prop('disabled', true);
            $("#sebelum_id_4").prop('disabled', true);
            $("#setelah_id_4").prop('disabled', true);
        }
    }
    // 5 = Insentif
    function pengajuan_id_5(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            $('#div_id_5').show();
            $("#pengajuan_id_5").prop('disabled', false);
            $("#sebelum_id_5").prop('disabled', false);
            $("#setelah_id_5").prop('disabled', false);
        }
        // jika 0 maka non aktif
        else {
            $('#div_id_5').hide();
            $("#pengajuan_id_5").prop('disabled', true);
            $("#sebelum_id_5").prop('disabled', true);
            $("#setelah_id_5").prop('disabled', true);
        }
    }
    // 14 = Nilai Prosentase Kenaikan Gaji
    function pengajuan_id_14(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            $('#div_id_14').show();
            $("#pengajuan_id_14").prop('disabled', false);
            $("#setelah_id_14").prop('disabled', false);
        }
        // jika 0 maka non aktif
        else {
            $('#div_id_14').hide();
            $("#pengajuan_id_14").prop('disabled', true);
            $("#setelah_id_14").prop('disabled', true);
        }
    }
    // 1000 = Lainnya
    function pengajuan_id_1000(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            $('#div_id_1000').show();
            $("#pengajuan_id_1000").prop('disabled', false);
            $("#sebelum_id_1000").prop('disabled', false);
            $("#setelah_id_1000").prop('disabled', false);
        }
        // jika 0 maka non aktif
        else {
            $('#div_id_1000').hide();
            $("#pengajuan_id_1000").prop('disabled', true);
            $("#sebelum_id_1000").prop('disabled', true);
            $("#setelah_id_1000").prop('disabled', true);
        }
    }
});
</script>
@endsection
<!-- https://stackoverflow.com/questions/18147749/bootstrap-table-contents-in-a-modal-pop-up -->
<!-- https://stackoverflow.com/questions/32890685/bootstrap-how-to-show-modal-box-using-option-from-select -->