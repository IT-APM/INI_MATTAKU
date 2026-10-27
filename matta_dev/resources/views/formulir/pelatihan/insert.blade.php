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
<li class="breadcrumb-item">Formulir Pengajuan Pelatihan</li>
<li class="breadcrumb-item active">@if ($status == 'edit') Edit @else Add @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                @if ($status == 'edit')
                <form action="{{ route('pelatihan.save_update', [$trx->id, 'back_url' => $back_url]) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                @else
                <form action="{{ route('pelatihan.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                @endif
                @csrf
                    <div class="row">
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
                        <!-- start -->
                        <div class="col-md-6">
                            <div class="card-body">
                                @if($status=='create')
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="peserta[]">Peserta*</label>
                                    <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="peserta[]" name="peserta[]" required="">
                                        @foreach($karyawan as $data)
                                        <option value="{{ $data->nik }}">{{ $data->nama }} - {{ $data->nik }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback txt-danger">Peserta is required.</div>
                                </div>
                                @endif
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="tahun">Tahun*</label>
                                    <input min=0 type="number" name="tahun" id="tahun" class="form-control numbers" required="" @if($status=='edit') value="{{ $trx->tahun }}" @endif>
                                    <div class="invalid-feedback txt-danger">Tahun is required.</div>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="jenis[]">Jenis Pelatihan*</label>
                                    <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="jenis[]" name="jenis[]" required="">
                                        @foreach($jenis as $data)
                                        <option value="{{ $data->id }}" @if($status=='edit') @if(in_array($data->id, explode(';', $trx->jenis))) selected @endif @endif>{{ $data->jenis }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback txt-danger">Jenis Pelatihan is required.</div>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="int_ext">Mode Pelatihan*</label>
                                    <select class="form-select col-sm-12" id="int_ext" name="int_ext" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        @foreach($int_ext as $key => $value)
                                        <option value="{{ $key }}" @if($status=='edit') @if($key == $trx->int_ext) selected @endif @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback txt-danger">Mode Pelatihan is required.</div>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="judul">Judul Pelatihan/Sertifikasi*</label>
                                    <input name="judul" id="judul" class="form-control" type="text" required="" @if($status=='edit') value="{{ $trx->judul }}" @endif>
                                    <div class="invalid-feedback txt-danger">Judul Pelatihan/Sertifikasi is required.</div>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="penyelenggara">Penyelenggara*</label>
                                    <input name="penyelenggara" id="penyelenggara" class="form-control" type="text" required="" @if($status=='edit') value="{{ $trx->penyelenggara }}" @endif>
                                    <div class="invalid-feedback txt-danger">Penyelenggara is required.</div>
                                </div>
                                {{-- <div class="col-md-12">
                                    <label class="form-label">Tanggal Pelaksanaan*</label>
                                </div> --}}
                                <div class="row" id="tanggal-container">
                                    <label class="form-label">Tanggal Pelaksanaan*</label>
                                    {{-- jika insert --}}
                                    @if($status=='create')
                                        {{-- <div class="col-md-12 tanggal-group">
                                            <input type="text" name="tanggal_range[]" id="tanggal_range[]" class="form-control tanggal-range" required readonly>
                                            <div class="invalid-feedback txt-danger">Tanggal Pelaksanaan is required.</div>
                                        </div> --}}
                                        <div class="row col-md-12 tanggal-group align-items-center">
                                            <div class="col-md-10">
                                                <input type="text" name="tanggal_range[]" id="tanggal_range[]" class="form-control tanggal-range" required readonly>
                                                <div class="invalid-feedback txt-danger">Tanggal Pelaksanaan is required.</div>
                                            </div>
                                            <div class="col-md-2">&nbsp;</div>
                                        </div>
                                    {{-- jika selain insert yaitu edit --}}
                                    @else
                                        @php
                                        $tanggal_range = json_decode($trx->tanggal_range, true);
                                        @endphp
                                        @foreach($tanggal_range as $_tanggal_range)
                                        <div class="row col-md-12 @if(!$loop->last) pb-2 @endif tanggal-group align-items-center">
                                            <div class="col-md-10">
                                                <input type="text" name="tanggal_range[]" id="tanggal_range[]" class="form-control tanggal-range" required readonly value="{{ $_tanggal_range }}">
                                                <div class="invalid-feedback txt-danger">Tanggal Pelaksanaan is required.</div>
                                            </div>
                                            @if(!$loop->first)
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger remove-tanggal"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                            </div>
                                            @endif
                                        </div>
                                        @endforeach
                                    @endif
                                    <div class="col-md-12 pb-2 tanggal-group">
                                        <button type="button" class="btn btn-primary mt-2" id="add-tanggal"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                    </div>
                                    {{-- <div class="col-md-2">
                                        <button type="button" class="btn btn-danger remove-tanggal">Delete</button>
                                    </div> --}}
                                    {{-- <div class="mt-2 tanggal-group">
                                        <input type="text" name="tanggal_range[]" id="tanggal_range[]" class="form-control tanggal-range" required>
                                    </div> --}}
                                    {{-- <div class="col-md-6 pb-2">
                                        <label class="form-label" for="tanggal_mulai">Tanggal Mulai*</label>
                                        <input class="form-control" name="tanggal_mulai" id="tanggal_mulai" type="date" required="">
                                    </div>
                                    <div class="col-md-6 pb-2">
                                        <label class="form-label" for="tanggal_akhir">Tanggal Akhir*</label>
                                        <input class="form-control" name="tanggal_akhir" id="tanggal_akhir" type="date" required="">
                                    </div> --}}
                                </div>
                                {{--  --}}
                                {{-- <div class="row" id="tanggal-sertifikasi-container" style="display:none;">
                                    <label class="form-label">Tanggal Sertifikasi*</label>
                                    @if($status=='create')
                                    <div class="row col-md-12 tanggal-sertifikasi-group align-items-center">
                                        <div class="col-md-10">
                                            <input type="text" name="tanggal_sertifikasi_range[]" id="tanggal_sertifikasi_range[]" class="form-control tanggal-sertifikasi-range" required readonly>
                                            <div class="invalid-feedback txt-danger">Tanggal Sertifikasi is required.</div>
                                        </div>
                                        <div class="col-md-2">&nbsp;</div>
                                    </div>
                                    @else
                                    @php
                                    $tanggal_range = json_decode($trx->tanggal_range, true);
                                    @endphp
                                        @foreach($tanggal_range as $_tanggal_range)
                                        <div class="row col-md-12 pb-2 tanggal-group align-items-center">
                                            <div class="col-md-10">
                                                <input type="text" name="tanggal_range[]" id="tanggal_range[]" class="form-control tanggal-range" required readonly value="{{ $_tanggal_range }}">
                                                <div class="invalid-feedback txt-danger">Tanggal Pelaksanaan is required.</div>
                                            </div>
                                            @if(!$loop->first)
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger remove-tanggal-sertifikasi"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                            </div>
                                            @endif
                                        </div>
                                        @endforeach
                                    @endif
                                    <div class="col-md-12 pb-2 tanggal-sertifikasi-group">
                                        <button type="button" class="btn btn-primary mt-2" id="add-tanggal-sertifikasi"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                    </div>
                                </div> --}}
                                {{--  --}}
                                @if($status=='create')
                                <div class="row">
                                    <div class="col-md-12 pb-2">
                                        <label class="form-label" for="reviewer1">Reviewer 1</label>
                                        <select class="form-select col-sm-12 js-example-basic-single" id="reviewer1" name="reviewer1">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            @foreach($reviewer as $data)
                                            <option value="{{ $data->nik }}">{{ $data->nama }} - {{ $data->nik }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 pb-2">
                                        <label class="form-label" for="reviewer2">Reviewer 2</label>
                                        <select class="form-select col-sm-12 js-example-basic-single" id="reviewer2" name="reviewer2">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            @foreach($reviewer as $data)
                                            <option value="{{ $data->nik }}">{{ $data->nama }} - {{ $data->nik }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <!-- finish -->
                        <!-- start -->
                        <div class="col-md-6">
                            <div class="card-body">
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="anggaran">Anggaran*</label>
                                    <select class="form-select col-sm-12" id="anggaran" name="anggaran" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        @foreach($anggaran as $data)
                                        <option value="{{ $data->id }}" @if($status=='edit') @if($data->id == $trx->anggaran) selected @endif @endif>{{ $data->anggaran }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback txt-danger">Anggaran is required.</div>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="biaya">Biaya (Termasuk PPN, jika ada)*</label>
                                    <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="biaya" id="biaya" class="form-control numbers" required="" @if($status=='edit') value="{{ $trx->biaya }}" @endif>
                                    <div class="invalid-feedback txt-danger">Biaya is required.</div>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="benefit[]">Benefit*</label>
                                    <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="benefit[]" name="benefit[]" required="">
                                        @foreach($benefit as $data)
                                        <option value="{{ $data->id }}" @if($status=='edit') @if(in_array($data->id, explode(';', $trx->benefit))) selected @endif @endif>{{ $data->benefit }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback txt-danger">Benefit is required.</div>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="tempat">Tempat*</label>
                                    <textarea class="form-control" id="tempat" name="tempat" rows="4" required="">@if($status=='edit') {{ $trx->tempat }} @endif</textarea>
                                    <div class="invalid-feedback txt-danger">Tempat is required.</div>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="keterangan">Keterangan</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="4" >@if($status=='edit') {{ $trx->keterangan }} @endif</textarea>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="lampiran">Lampiran<br/>*Maksimal 2MB dengan format pdf atau gambar(jpg, jpeg dan png)</label>
                                    <input name="lampiran" id="lampiran" class="form-control" type="file">
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="catatan">Catatan</label>
                                    <textarea class="form-control" id="catatan" name="catatan" rows="4" >@if($status=='edit') {{ $trx->catatan }} @endif</textarea>
                                </div>
                            </div>
                        </div>
                        <!-- finish -->
                        <div class="col-md-12">
                            <div class="card-body">
                                <button class="btn btn-success" type="submit">Submit</button> | <a @if($status == 'edit') href="{{ $back_url }}" @else href="{{ route('pelatihan.index') }}" @endif class="btn btn-warning" type="button">Back</a>
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