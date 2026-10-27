@extends('layouts.authentication.master')
@section('title', 'Absensi Kesehatan')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                <form action="{{ route('covid.update', $id) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @method('PATCH')
                    @csrf
                    <!-- Ring 1 -->
                    <div class="card-body typography">
                        <div class="row ">
                            <div class="col-md-12">
                                <label class="form-check-label" for="ring1">
                                    <h6>Ring 1 - Karyawan<br/>Apakah anda dalam keadaan sehat hari ini?</h6>
                                </label>
                                <select class="form-select" id="ring1" name="ring1" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    <option value="Sehat">Sehat</option>
                                    <option value="Sakit">Sakit</option>
                                </select>
                            </div>
                            <div class="col-md-12" id="div_ring1">
                                <div class="table-responsive" id="div_ring1">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    Sebutkan Sakitnya <input name="sakit_ring1" id="sakit_ring1" class="form-control" type="text" required=""><br/>
                                                    Sejak Tanggal <input class="form-control" name="tanggal_ring1" id="tanggal_ring1" type="date" required="">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <!-- Ring 2 -->
                        <div class="row ">
                            <div class="col-md-12">
                                <label class="form-check-label" for="ring2">
                                    <h6>Ring 2 (Orang serumah sedang dalam keadaan sakit)<br/>Orang serumah (Suami/Istri, Anak, Orang Tua, Kakak/Adik, Pembantu, dll)<br/>Apakah ada yang sakit?</h6>
                                </label>
                                <select class="form-select" id="ring2" name="ring2" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                            <div class="col-md-12" id="div_ring2">
                                <div class="row ">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table" id="trx_ringdua">
                                                <tbody>
                                                    <tr id="ringdua0">
                                                        <td>
                                                            Sebutkan siapa yang sakit? <select class="form-select" id="siapa_ring2[]" name="siapa_ring2[]" required="">
                                                                <option selected="" disabled="" value="">Pilih...</option>
                                                                <option value="Suami/Istri">Suami/Istri</option>
                                                                <option value="Anak 1">Anak 1</option>
                                                                <option value="Anak 2">Anak 2</option>
                                                                <option value="Anak 3">Anak 3</option>
                                                                <option value="Orang Tua">Orang Tua</option>
                                                                <option value="Kakak/Adik">Kakak/Adik</option>
                                                                <option value="Pembantu">Pembantu</option>
                                                                <option value="Lainnya">Lainnya</option>
                                                            </select><br/>
                                                            Sebutkan Sakitnya <input name="sakit_ring2[]" id="sakit_ring2[]" class="form-control" type="text" required=""><br/>
                                                            Sejak Tanggal <input class="form-control" name="tanggal_ring2[]" id="tanggal_ring2[]" type="date" required="">
                                                        </td>
                                                    </tr>
                                                    <tr id="ringdua1"></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-12">
                                            <button id="add_row_ring2" class="btn btn-primary pull-left"><i class="fa fa-plus"></i> Add Row</button>
                                            <button id='delete_row_ring2' class="pull-right btn btn-danger"><i class="fa fa-minus"></i> Delete Row</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <!-- Ring 3 -->
                        <div class="row ">
                            <div class="col-md-12">
                                <label class="form-check-label" for="ring3">
                                    <h6>Ring 3 (Lingkungan tempat tinggal)<br/>Tetangga Kiri, Kanan, Depan, Belakang dan lingkungan sekitar<br/>Apakah ada yang sakit?</h6>
                                </label>
                                <select class="form-select" id="ring3" name="ring3" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                            <div class="col-md-12" id="div_ring3">
                                <div class="row ">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table" id="trx_ringtiga">
                                                <tbody>
                                                    <tr id="ringtiga0">
                                                        <td>
                                                            Sebutkan siapa yang sakit? <input name="siapa_ring3[]" id="siapa_ring3[]" class="form-control" type="text" required=""><br/>
                                                            Sebutkan Sakitnya <input name="sakit_ring3[]" id="sakit_ring3[]" class="form-control" type="text" required=""><br/>
                                                            Sejak Tanggal <input class="form-control" name="tanggal_ring3[]" id="tanggal_ring3[]" type="date" required="">
                                                        </td>
                                                    </tr>
                                                    <tr id="ringtiga1"></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-12">
                                            <button id="add_row_ring3" class="btn btn-primary pull-left"><i class="fa fa-plus"></i> Add Row</button>
                                            <button id='delete_row_ring3' class="pull-right btn btn-danger"><i class="fa fa-minus"></i> Delete Row</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row ">
                            <div class="col-md-12">
                                <button class="btn btn-success" type="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!--  -->
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/sweet-alert/app.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<!--  -->
<!-- source: https://blog.quickadminpanel.com/master-detail-form-in-laravel-jquery-create-order-with-products/ -->
<script type="text/javascript">
$(document).ready(function() {
    // 
    div_ring1(0);
    div_ring2(0);
    div_ring3(0);
    // 
    $("#ring1").change(function() {
        var selectedValue = $(this).val();
        if (selectedValue == 'Sakit') {
            div_ring1(1);
        } else {
            div_ring1(0);
        }
    });
    // 
    $("#ring2").change(function() {
        var selectedValue = $(this).val();
        if (selectedValue == 'Ya') {
            div_ring2(1);
        } else {
            div_ring2(0);
        }
    });
    // 
    $("#ring3").change(function() {
        var selectedValue = $(this).val();
        if (selectedValue == 'Ya') {
            div_ring3(1);
        } else {
            div_ring3(0);
        }
    });
    // 
    function div_ring1(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            $('#div_ring1').show();
            $("#sakit_ring1").prop('disabled', false);
            $("#tanggal_ring1").prop('disabled', false);
        }
        // jika 0 maka non aktif
        else {
            $('#div_ring1').hide();
            $("#sakit_ring1").prop('disabled', true);
            $("#tanggal_ring1").prop('disabled', true);
        }
    }
    // 
    function div_ring2(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            // alert(cek);
            $('#div_ring2').show();
            $('select[id="siapa_ring2[]"]').prop('disabled', false);
            $('input[id="sakit_ring2[]"]').prop('disabled', false);
            $('input[id="tanggal_ring2[]"]').prop('disabled', false);
        }
        // // jika 0 maka non aktif
        else {
            $('#div_ring2').hide();
            $('select[id="siapa_ring2[]"]').prop('disabled', true);
            $('input[id="sakit_ring2[]"]').prop('disabled', true);
            $('input[id="tanggal_ring2[]"]').prop('disabled', true);
        }
    }
    // 
    function div_ring3(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            // alert(cek);
            $('#div_ring3').show();
            $('input[id="siapa_ring3[]"]').prop('disabled', false);
            $('input[id="sakit_ring3[]"]').prop('disabled', false);
            $('input[id="tanggal_ring3[]"]').prop('disabled', false);
        }
        // // jika 0 maka non aktif
        else {
            $('#div_ring3').hide();
            $('input[id="siapa_ring3[]"]').prop('disabled', true);
            $('input[id="sakit_ring3[]"]').prop('disabled', true);
            $('input[id="tanggal_ring3[]"]').prop('disabled', true);
        }
    }
    // 
    let row_number_ring2 = 1;
    $("#add_row_ring2").click(function(e) {
        e.preventDefault();
        let new_row_number_ring2 = row_number_ring2 - 1;
        $('#ringdua' + row_number_ring2).html($('#ringdua' + new_row_number_ring2).html()).find(
            'td:first-child');
        $('#trx_ringdua').append('<tr id="ringdua' + (row_number_ring2 + 1) + '"></tr>');
        row_number_ring2++;
    });
    $("#delete_row_ring2").click(function(e) {
        e.preventDefault();
        if (row_number_ring2 > 1) {
            $("#ringdua" + (row_number_ring2 - 1)).html('');
            row_number_ring2--;
        }
    });
    // 
    let row_number_ring3 = 1;
    $("#add_row_ring3").click(function(e) {
        e.preventDefault();
        let new_row_number_ring3 = row_number_ring3 - 1;
        $('#ringtiga' + row_number_ring3).html($('#ringtiga' + new_row_number_ring3).html()).find(
            'td:first-child');
        $('#trx_ringtiga').append('<tr id="ringtiga' + (row_number_ring3 + 1) + '"></tr>');
        row_number_ring3++;
    });
    $("#delete_row_ring3").click(function(e) {
        e.preventDefault();
        if (row_number_ring3 > 1) {
            $("#ringtiga" + (row_number_ring3 - 1)).html('');
            row_number_ring3--;
        }
    });
    // 
});
</script>
@endsection