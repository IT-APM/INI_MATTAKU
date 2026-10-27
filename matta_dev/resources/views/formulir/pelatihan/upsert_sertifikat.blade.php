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
<h3>@if ($status == 'edit') Edit @else Add @endif</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Sertifikat</li>
<li class="breadcrumb-item active">@if ($status == 'edit') Edit @else Add @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if (count($errors) > 0)
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h6>Upload image is ERROR</h6>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if ($status == 'edit')
                    <form action="{{ route('pelatihan_sertifikat.update', $trx->id) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @method('PATCH')
                    @else
                    <form action="{{ route('pelatihan_sertifikat.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="nama">Nama</label>
                            <input name="nama" id="nama" class="form-control" type="text" required="" @if($status=='edit') value="{{ $trx->nama }}" @endif>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="nik">NIP</label>
                            <input name="nik" id="nik" class="form-control" type="text" @if($status=='edit') value="{{ $trx->nik }}" readonly @endif>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="jenis">Jenis Pelatihan</label>
                            <select class="form-select col-sm-12" id="jenis" name="jenis" required="">
                                <option selected="" disabled="" value="">Pilih...</option>
                                @foreach ($jenis as $data)
                                <option value="{{ $data->jenis }}" @if ($status=='edit' ) @if($data->jenis == $trx->jenis) selected @endif @endif >{{ $data->jenis }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="int_ext">Mode Pelatihan</label>
                            <select class="form-select col-sm-12" id="int_ext" name="int_ext" required="">
                                <option selected="" disabled="" value="">Pilih...</option>
                                @foreach($int_ext as $key => $value)
                                <option value="{{ $value }}" @if($status=='edit') @if($value == $trx->int_ext) selected @endif @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="penerbit">Penerbit</label>
                            <select class="form-select col-sm-12" id="penerbit" name="penerbit" required="">
                                <option selected="" disabled="" value="">Pilih...</option>
                                @foreach ($penerbit as $data)
                                <option value="{{ $data->penerbit }}" @if ($status=='edit' ) @if($data->penerbit == $trx->penerbit) selected @endif @endif >{{ $data->penerbit }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                            {{-- <input name="penerbit" id="penerbit" class="form-control" type="text" required="" @if($status=='edit') value="{{ $trx->penerbit }}" @endif>
                            <div class="invalid-feedback txt-danger">Field is required.</div> --}}
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="kta">Kartu Tanda Anggota</label>
                            <select class="form-select col-sm-12" id="kta" name="kta" required="">
                                <option selected="" disabled="" value="">Pilih...</option>
                                @foreach($kta as $key => $value)
                                <option value="{{ $value }}" @if($status=='edit') @if($value == $trx->kta) selected @endif @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="judul">Judul Pelatihan</label>
                            <input name="judul" id="judul" class="form-control" type="text" required="" @if($status=='edit') value="{{ $trx->judul }}" @endif>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="lembaga">Lembaga</label>
                            <input name="lembaga" id="lembaga" class="form-control" type="text" required="" @if($status=='edit') value="{{ $trx->lembaga }}" @endif>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="nomor_sertifikat">No Sertifikat</label>
                            <input name="nomor_sertifikat" id="nomor_sertifikat" class="form-control" type="text" @if($status=='edit') value="{{ $trx->nomor_sertifikat }}" @endif>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="no_kartu_lisensi">No Kartu Lisensi</label>
                            <input name="no_kartu_lisensi" id="no_kartu_lisensi" class="form-control" type="text" @if($status=='edit') value="{{ $trx->no_kartu_lisensi }}" @endif>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="no_kartu_anggota">No Kartu Anggota</label>
                            <input name="no_kartu_anggota" id="no_kartu_anggota" class="form-control" type="text" @if($status=='edit') value="{{ $trx->no_kartu_anggota }}" @endif>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="biaya">Biaya</label>
                            <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="biaya" id="biaya" class="form-control numbers" required="" @if($status=='edit') value="{{ $trx->biaya }}" @endif>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="tanggal_mulai">Tanggal Mulai</label>
                            <input class="form-control" type="date" name="tanggal_mulai" id="tanggal_mulai" placeholder="Tanggal Mulai" @if($status=='edit') value="{{ $trx->tanggal_mulai }}" @endif>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="tanggal_akhir">Tanggal Akhir</label>
                            <input class="form-control" type="date" name="tanggal_akhir" id="tanggal_akhir" placeholder="Tanggal Akhir" @if($status=='edit') value="{{ $trx->tanggal_akhir }}" @endif>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="tanggal_sertifikat">Tanggal Sertifikat</label>
                            <input class="form-control" type="date" name="tanggal_sertifikat" id="tanggal_sertifikat" placeholder="Tanggal Sertifikat" @if($status=='edit') value="{{ $trx->tanggal_sertifikat }}" @endif>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="tanggal_kadaluarsa">Tanggal Kedaluwarsa</label>
                            <input class="form-control" type="date" name="tanggal_kadaluarsa" id="tanggal_kadaluarsa" placeholder="Tanggal Kedaluwarsa" @if($status=='edit') value="{{ $trx->tanggal_kadaluarsa }}" @endif>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label" for="formulir_link">Link Formulir</label>
                            <input name="formulir_link" id="formulir_link" class="form-control" type="text" @if($status=='edit') value="{{ $trx->formulir_link }}" @endif>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label" for="sertifikat_link">Link Sertifikat</label>
                            <input name="sertifikat_link" id="sertifikat_link" class="form-control" type="text" required="" @if($status=='edit') value="{{ $trx->sertifikat_link }}" @endif>
                            <div class="invalid-feedback txt-danger">Field is required.</div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ route('pelatihan_sertifikat.index') }}" class="btn btn-warning" type="button">Back</a>
                        </div>
                    </div>
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
<!-- Moment.js (required for daterangepicker) -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<!-- Daterangepicker JS -->
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
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
// 
$(document).ready(function() {
    // $('#tanggal-sertifikasi-container').hide().find('input, button').prop('disabled', true);
    // $(this).on('change', 'select[id="jenis[]"]', function () {
    //     const pilihan = $(this).val();
    //     if (pilihan.includes('5')) {
    //         $('#tanggal-sertifikasi-container').show().find('input, button').prop('disabled', false);
    //     } else {
    //         $('#tanggal-sertifikasi-container').hide().find('input, button').prop('disabled', true);
    //     }
    // });
    // 
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
    // Inisialisasi pertama tanggal pelaksanaan
    initDateRangePicker($('.tanggal-range'));
    // Tambah input baru
    $('#add-tanggal').click(function() {
        const newInput = $(`
            <div class="row col-md-12 pb-2 tanggal-group align-items-center">
                <div class="col-md-10">
                    <input type="text" name="tanggal_range[]" id="tanggal_range[]" class="form-control tanggal-range" required readonly>
                    <div class="invalid-feedback txt-danger">Tanggal Pelaksanaan is required.</div>
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
    $('form').on('submit', function(e) {
        $(this).find('.tanggal-range').each(function() {
            if ($(this).val().trim() === '') {
                $(this).addClass('is-invalid').removeClass('is-valid');
                e.preventDefault();
            }else{
                $(this).removeClass('is-invalid').addClass('is-valid');
            }
        });
    });
});
</script>
@endsection