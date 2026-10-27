@extends('layouts.simple.master')
@section('title', 'Formulir Pengajuan Pelatihan')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
<!-- Daterangepicker CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
/* Ganti warna untuk tanggal yang dipilih */
.daterangepicker td.in-range {
    background-color: #d3d3d3 !important; /* abu-abu terang */
    color: #000 !important;
}

.daterangepicker td.active,
.daterangepicker td.active:hover {
    background-color: #a9a9a9 !important; /* abu-abu tua */
    color: #fff !important;
}

/* Hover di kalender */
.daterangepicker td.available:hover {
    background-color: #c0c0c0 !important; /* abu-abu medium */
}
</style>
@endsection

@section('breadcrumb-title')
<h3>@if($status == 'edit') Edit @else Add @endif</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item">Pengajuan Pelatihan</li>
<li class="breadcrumb-item">Sertifikat</li>
<li class="breadcrumb-item active">@if($status == 'edit') Edit @else Add @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                @if($status == 'edit')
                <form action="{{ route('pelatihan.update', (new App\Http\Controllers\OpenDataController)->encryptWithKey($_trx->id)) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                @else
                <form action="{{ route('pelatihan.update', (new App\Http\Controllers\OpenDataController)->encryptWithKey($trx->id)) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                @endif
                @method('PATCH')
                @csrf
                    <input type="hidden" id="back_url" name="back_url" value="{{ $back_url }}">
                    <input type="hidden" id="type" name="type" @if($status == 'edit') value="doc_update" @else value="doc_insert" @endif>
                    @if($status == 'create')
                    <input type="hidden" id="nik" name="nik" value="{{ $trx->nik }}">
                    <input type="hidden" id="nik_property" name="nik_property" value="{{ $trx->nik_property }}">
                    @endif
                    <div class="row">
                        <!-- start -->
                        <div class="col-md-6">
                            @php $nik_property = json_decode($trx->nik_property, true); @endphp
                            <div class="card-body">
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="nik">Peserta</label>
                                    <input name="nik" id="nik" class="form-control" type="text" value="{{ $nik_property['nama'] }} / {{ $nik_property['nik'] }}" disabled>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="jenis">Jenis Pelatihan</label>
                                    <input name="jenis" id="jenis" class="form-control" type="text" value="@foreach($arrJenis as $value){{ $value->jenis }} @if(!$loop->last) <br/> @endif @endforeach" disabled>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="judul">Judul Pelatihan/Sertifikasi</label>
                                    <input name="judul" id="judul" class="form-control" type="text" value="{{ $trx->_trx->judul }}" disabled>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="nama_sertifikat">Nama Sertifikat</label>
                                    <input name="nama_sertifikat" id="nama_sertifikat" class="form-control" type="text" @if($status == 'edit') value="{{ $_trx->nama_sertifikat }}" @endif required>
                                    <div class="invalid-feedback txt-danger">Nama Sertifikat is required.</div>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="no_sertifikat">No Sertifikat</label>
                                    <input name="no_sertifikat" id="no_sertifikat" class="form-control" type="text" @if($status == 'edit') value="{{ $_trx->no_sertifikat }}" @endif required>
                                    <div class="invalid-feedback txt-danger">No Sertifikat is required.</div>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="penerbit">Penerbit Sertifikat</label>
                                    <input name="penerbit" id="penerbit" class="form-control" type="text" @if($status == 'edit') value="{{ $_trx->penerbit }}" @endif required>
                                    <div class="invalid-feedback txt-danger">No Sertifikat is required.</div>
                                </div>
                                <div class="col-md-12" id="tanggal-container">
                                    <label class="form-label" for="tanggal_ujian">Tanggal Ujian</label>
                                    {{-- jika insert --}}
                                    @if($status == 'create')
                                        <div class="row col-md-12 tanggal-group align-items-center">
                                            <div class="col-md-10">
                                                <input type="text" name="tanggal_ujian[]" id="tanggal_ujian[]" class="form-control tanggal-range" readonly>
                                                <div class="invalid-feedback txt-danger">Tanggal Ujian is required.</div>
                                            </div>
                                            <div class="col-md-2">&nbsp;</div>
                                        </div>
                                    {{-- jika selain insert yaitu edit --}}
                                    @else
                                        @if(!empty($_trx->tanggal_ujian))
                                        @php
                                        $tanggal_ujian = json_decode($_trx->tanggal_ujian, true);
                                        @endphp
                                        @foreach($tanggal_ujian as $_tanggal_ujian)
                                        <div class="row col-md-12 pb-2 tanggal-group align-items-center">
                                            <div class="col-md-10">
                                                <input type="text" name="tanggal_ujian[]" id="tanggal_ujian[]" class="form-control tanggal-range" readonly value="{{ $_tanggal_ujian }}">
                                                <div class="invalid-feedback txt-danger">Tanggal Ujian is required.</div>
                                            </div>
                                            @if(!$loop->first)
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger remove-tanggal"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                            </div>
                                            @endif
                                        </div>
                                        @endforeach
                                        @else
                                        <div class="row col-md-12 tanggal-group align-items-center">
                                            <div class="col-md-10">
                                                <input type="text" name="tanggal_ujian[]" id="tanggal_ujian[]" class="form-control tanggal-range" readonly>
                                                <div class="invalid-feedback txt-danger">Tanggal Ujian is required.</div>
                                            </div>
                                            <div class="col-md-2">&nbsp;</div>
                                        </div>
                                        @endif
                                    @endif
                                    <div class="col-md-2 pb-2 tanggal-group">
                                        <button type="button" class="btn btn-primary mt-2" id="add-tanggal"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="is_lulus">Lulus Sertifikasi</label>
                                    <select class="form-select col-sm-12" id="is_lulus" name="is_lulus" required>
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        <option value="1" @if($status == 'edit') @if("1" == $_trx->is_lulus) selected @endif @endif>Lulus</option>
                                        <option value="0" @if($status == 'edit') @if("0" == $_trx->is_lulus) selected @endif @endif>Tidak Lulus</option>
                                    </select>
                                    <div class="invalid-feedback txt-danger">Lulus Sertifikasi is required.</div>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="tanggal_berlaku">Tanggal Berlaku</label>
                                    <input type="text" name="tanggal_berlaku[]" id="tanggal_berlaku[]" class="form-control tanggal-berlaku" readonly @if($status == 'edit') @if(!empty($_trx->tanggal_berlaku)) value="{{ json_decode($_trx->tanggal_berlaku, true)[0] }}" @endif @endif>
                                    <div class="invalid-feedback txt-danger">Tanggal Berlaku is required.</div>
                                </div>
                            </div>
                        </div>
                        <!-- finish -->
                        <!-- start -->
                        <div class="col-md-6">
                            <div class="card-body">
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="file_anggota">Upload Kartu Anggota</label>
                                    <input name="file_anggota" id="file_anggota" class="form-control" type="file" >
                                    @if(!empty($_trx->file_anggota)) <a href="{{ asset('assets/pelatihan/'.$_trx->file_anggota) }}" target="_blank">Download Kartu Anggota</a> @endif
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="file_lisensi">Upload Kartu Lisensi</label>
                                    <input name="file_lisensi" id="file_lisensi" class="form-control" type="file" >
                                    @if(!empty($_trx->file_lisensi)) <a href="{{ asset('assets/pelatihan/'.$_trx->file_lisensi) }}" target="_blank">Download Kartu Anggota</a> @endif
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="file_modul">Upload Modul</label>
                                    <input name="file_modul" id="file_modul" class="form-control" type="file" >
                                    @if(!empty($_trx->file_modul)) <a href="{{ asset('assets/pelatihan/'.$_trx->file_modul) }}" target="_blank">Download Kartu Anggota</a> @endif
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="file_pelatihan">Upload Sertifikat Pelatihan</label>
                                    <input name="file_pelatihan" id="file_pelatihan" class="form-control" type="file" >
                                    @if(!empty($_trx->file_pelatihan)) <a href="{{ asset('assets/pelatihan/'.$_trx->file_pelatihan) }}" target="_blank">Download Kartu Anggota</a> @endif
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="file_keahlian">Upload Sertifikat Keahlian</label>
                                    <input name="file_keahlian" id="file_keahlian" class="form-control" type="file" >
                                    @if(!empty($_trx->file_keahlian)) <a href="{{ asset('assets/pelatihan/'.$_trx->file_keahlian) }}" target="_blank">Download Kartu Anggota</a> @endif
                                </div>
                            </div>
                        </div>
                        <!-- finish -->
                    </div>
                    <div class="col-md-12 ms-4 mb-2">
                        {{-- <div class="card-body"> --}}
                            <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ $back_url }}" class="btn btn-warning" type="button">Back</a>
                        {{-- </div> --}}
                    </div>
                    {{-- <hr/> --}}
                </form>
                @if($status == 'edit')
                <form action="{{ route('pelatihan.destroy', (new App\Http\Controllers\OpenDataController)->encryptWithKey($_trx->id)) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to delete this data?');">
                @method('DELETE')
                @csrf
                    <input type="hidden" id="back_url" name="back_url" value="{{ $back_url }}">
                    <input type="hidden" id="type" name="type" value="doc_delete">
                    <div class="col-md-12 ms-4 mb-2"><button class="btn btn-danger mt-2" type="submit">Delete</button></div>
                </form>
                @endif
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
<!-- Moment.js (required for daterangepicker) -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<!-- Daterangepicker JS -->
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    function initDateRangePicker(element) {
        element.daterangepicker({
            locale: {
                format: 'YYYY-MM-DD',
                separator: ' s/d '
            },
            autoUpdateInput: false
        });
        element.on('apply.daterangepicker', function(ev, picker) {
            const start = picker.startDate.format('YYYY-MM-DD');
            const end = picker.endDate.format('YYYY-MM-DD');

            // if (start === end) {
            //     $(this).val(start);
            // } else {
                $(this).val(start + ' s/d ' + end);
            // }
        });
        element.on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    }
    // Inisialisasi pertama Tanggal Ujian
    initDateRangePicker($('.tanggal-range'));
    initDateRangePicker($('.tanggal-berlaku'));
    // Tambah input baru
    $('#add-tanggal').click(function() {
        const newInput = $(`
            <div class="row col-md-12 pb-2 tanggal-group align-items-center">
                <div class="col-md-10">
                    <input type="text" name="tanggal_ujian[]" id="tanggal_ujian[]" class="form-control tanggal-range" required readonly>
                    <div class="invalid-feedback txt-danger">Tanggal Ujian is required.</div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-tanggal"><i class="fa fa-minus" aria-hidden="true"></i></button>
                </div>
            </div>
        `);
        $('#tanggal-container').append(newInput);
        initDateRangePicker(newInput.find('.tanggal-range'));
    });
    // Tombol Hapus
    $('#tanggal-container').on('click', '.remove-tanggal', function () {
        $(this).closest('.tanggal-group').remove();
    });
    // $('form').on('Delete', function(e) {
    //     $(this).find('.tanggal-range').each(function() {
    //         if ($(this).val().trim() === '') {
    //             $(this).addClass('is-invalid').removeClass('is-valid');
    //             e.preventDefault();
    //         }else{
    //             $(this).removeClass('is-invalid').addClass('is-valid');
    //         }
    //     });
    //     $(this).find('.tanggal-berlaku').each(function() {
    //         if ($(this).val().trim() === '') {
    //             $(this).addClass('is-invalid').removeClass('is-valid');
    //             e.preventDefault();
    //         }else{
    //             $(this).removeClass('is-invalid').addClass('is-valid');
    //         }
    //     });
    // });
});
</script>
@endsection