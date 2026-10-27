@extends('layouts.simple.master')
@section('title', 'Formulir Permohonan Cuti')

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
<li class="breadcrumb-item">Permohonan Cuti</li>
<li class="breadcrumb-item active">Add</li>
@endsection
<!-- https://makitweb.com/auto-populate-dropdown-with-jquery-ajax-in-laravel/ -->
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('error'))
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <!-- <img class="img-50" src="{{asset('assets/images/other-images/sad.png')}}" alt="error"> -->
                        <h4>ERROR:</h4>
                        @if (session('error')[0]['status']) <h5>
                            {{ session('error')[0]['message'] }} - OK</h5>
                        @else <h5>{{ session('error')[0]['message'] }} - NOK</h5> @endif
                        @if (session('error')[1]['status']) <h5>
                            {{ session('error')[1]['message'] }} - OK</h5>
                        @else <h5>{{ session('error')[1]['message'] }} - NOK</h5> @endif
                        @if (session('error')[2]['status']) <h5>
                            {{ session('error')[2]['message'] }} - OK</h5>
                        @else <h5>{{ session('error')[2]['message'] }} - NOK</h5> @endif
                        @if (session('error')[3]['status']) <h5>
                            {{ session('error')[3]['message'] }} - OK</h5>
                        @else <h5>{{ session('error')[3]['message'] }} - NOK</h5> @endif
                        @if (session('error')[4]['status']) <h5>
                            {{ session('error')[4]['message'] }} - OK</h5>
                        @else <h5>{{ session('error')[4]['message'] }} - NOK</h5> @endif
                        @if (session('error')[5]['status']) <h5>
                            {{ session('error')[5]['message'] }} - OK</h5>
                        @else <h5>{{ session('error')[5]['message'] }} - NOK</h5> @endif
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <div class="row g-3 mb-2">
                        <div class="col-md-4">
                            <label class="form-label" for="pengaju">Pengaju</label>
                            <input class="form-control" type="text" required="" readonly="" value="{{ $pengaju }}">
                        </div>
                    </div>
                    <form target="_self" id="form" name="form" action="{{ route('cuti.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @csrf
                        <input type="hidden" id="nik_karyawan" name="nik_karyawan" value="{{ $nik_karyawan }}">
                        {{-- auth()->user()->nik diganti dengan $nik_karyawan agar hco bisa input on behalf karyawan --}}
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label class="form-label">Sisa Jumlah Hari Cuti Tahunan</label>
                                {{-- <h4>{{ App\Http\Controllers\TrxCutiController::getSisaFormCuti() }} Hari</h4> --}}
                                {{-- jika di Bulan Januari maka tampilkan sisa cuti tahun lalu --}}
                                @if(\Carbon\Carbon::today()->between(\Carbon\Carbon::now()->year.'-01-01', \Carbon\Carbon::now()->year.'-01-31'))
                                    <h4>{{ \Carbon\Carbon::now()->year }}: {{ App\Http\Controllers\TrxCutiController::getSisaFormCuti($nik_karyawan, \Carbon\Carbon::now()->year) }} hari
                                    <br/>{{ \Carbon\Carbon::now()->subYear()->year }}: {{ App\Http\Controllers\TrxCutiController::getSisaFormCuti($nik_karyawan, \Carbon\Carbon::now()->subYear()->year) }} hari</h4>
                                {{-- jika selain Bulan Januari maka tampilkan seperti biasa --}}
                                @else
                                    <h4>{{ \Carbon\Carbon::now()->year }}: {{ App\Http\Controllers\TrxCutiController::getSisaFormCuti($nik_karyawan, \Carbon\Carbon::now()->year) }} Hari</h4>
                                @endif
                                {{-- ambil daftar cutinya --}}
                                @php
                                    $where = ['nik_karyawan' => $nik_karyawan, 'periode_cuti' => \Carbon\Carbon::now()->year, 'tipe_cuti' => 0];
                                    $TrxCuti = App\Models\TrxCuti::where($where)->whereNotIn('status_cuti', [5,7,8])->orderBy('id', 'desc')->get();
                                    // 
                                    // cek apakah karyawan reguler atau tidak, jika iya maka dihitung cuti bersama
                                    $where = [ 'nik' => $nik_karyawan, 'wg_id' => 1 ];
                                    $check = App\Models\Karyawan::where($where)->exists();
                                    $BersamaCuti = [];
                                    if($check){
                                        $BersamaCuti = App\Models\Holiday::where(['is_cuti' => 1])->whereYear('tanggal', \Carbon\Carbon::now()->year)->orderBy('tanggal', 'desc')->get();
                                    }
                                @endphp
                                @if(!empty($TrxCuti))
                                    <button id="autoClickBtn" type="button" class="btn text-white" style="background-color: #797d7f;" data-bs-toggle="modal" data-bs-target="#show_cuti">Detail</button>
                                @endif
                            </div>
                            @php
                            // 2=Pria
                            // 3=Wanita
                            $kode_kelamin = App\Models\Karyawan::where(['nik'=>$nik_karyawan])->first()->kode_kelamin;
                            if($kode_kelamin == 2){
                                // 8=Melahirkan-Karyawati (90 hari)
                                // 16=Cuti Haid (2 hari)
                                // 17=Cuti Haid (1 hari)
                                $tipe = $tipe->whereNotIn('id', [8, 16, 17]);
                            }elseif($kode_kelamin == 3){
                                // 9=Melahirkan-Istri (3 hari)
                                $tipe = $tipe->whereNotIn('id', [9]);
                            }
                            @endphp
                            <div class="col-md-4">
                                <label class="form-label" for="tipe_cuti">Tipe Cuti</label>
                                <select class="form-select" id="tipe_cuti" name="tipe_cuti" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($tipe as $data)
                                    <option value="{{ $data->id }}">{{ $data->tipe_cuti }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4" id="div_jumlah_hari">
                                <label class="form-label" for="jumlah_hari">Jumlah Hari</label>
                                <input id="jumlah_hari" name="jumlah_hari" min="1" oninput="validity.valid||(value='');" required="" class="form-control numbers" type="number" placeholder="Jumlah Hari">
                            </div>
                        </div>
                        <div class="row mt-2" id="div_umroh">
                            <div class="col-md-4">&nbsp;</div>
                            <div class="col-md-8">
                                <div class="form-label">
                                    <span class="text-danger">Catatan:<br/>1. Diberikan cuti 2 hari kerja sebelum keberangkatan.<br/>2. Diberikan cuti 2 hari kerja setelah kepulangan.<br/>3. Tanggal mulai yang dimasukkan adalah tanggal keberangkatan.</span>
                                </div>
                            </div>
                        </div>
                        {{-- <br /> --}}
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label class="form-label" for="tanggal_mulai">Tanggal Mulai</label>
                                <input class="form-control" name="tanggal_mulai" id="tanggal_mulai" type="date" required="">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="pic_pengganti">PIC Pengganti</label>
                                <select class="js-example-basic-multiple col-sm-12" id="pic_pengganti[]" name="pic_pengganti[]" required="" multiple="multiple">
                                    <!-- <option selected="" disabled="" value="">Pilih...</option> -->
                                    @foreach ($karyawan as $data)
                                    <option value="{{ $data->nik }}">{{ $data->nama }} - {{ $data->nik }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback text-danger">Required</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="telepon">Telepon Pengaju</label>
                                <input class="form-control m-input digits" type="tel" name="telepon" id="telepon" required="">
                            </div>
                        </div>
                        {{-- <br /> --}}
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label class="form-label" for="alasan">Alasan</label>
                                <textarea class="form-control" id="alasan" name="alasan" rows="4" required=""></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="alamat">Alamat Tujuan Cuti</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="4" required=""></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="keterangan">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="4"></textarea>
                            </div>
                        </div>
                        {{-- <br /> --}}
                        <button id="submit" name="submit" value="submit" class="btn btn-success mt-2" type="submit">Submit</button> | <button class="btn btn-primary mt-2" type="submit" id="preview" name="preview" value="preview">Preview</button> | <a href="{{ URL::previous() }}" class="btn btn-warning mt-2" type="button">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="show_cuti" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title" id="myLargeModalLabel">Daftar Cuti Tahunan dan Cuti Bersama (jika ada)</h4>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- <img src="{{asset('assets/images/matta/uu_pdp.png')}}" class="img-fluid d-flex" alt="Pengumuman"> --}}
                <div class="table-responsive">
                    <table class="display table" id="_datatable_cuti">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Cuti</th>
                                <th>Tanggal</th>
                                <th>Jumlah Hari</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 0; @endphp
                            @foreach ($TrxCuti as $data)
                            <tr>
                                <td>{{ ++$no }}</td>
                                <td>{{ $data->alasan }}</td>
                                <td>{{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d-m-Y') }} sd {{ \Carbon\Carbon::parse($data->tanggal_akhir)->format('d-m-Y') }}</td>
                                <td>{{ $data->jumlah_hari }}</td>
                            </tr>
                            @endforeach
                            @foreach ($BersamaCuti as $data)
                            <tr>
                                <td>{{ ++$no }}</td>
                                <td>{{ $data->libur }}</td>
                                <td>{{ \Carbon\Carbon::parse($data->tanggal)->format('d-m-Y') }}</td>
                                <td>1</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Tutup</button>
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
    $("#jumlah_hari").prop('disabled', true);
    $('#div_jumlah_hari').hide();
    $('#div_umroh').hide();
    $("#tipe_cuti").change(function() {
        var selectedValue = $(this).val();
        if (selectedValue == 0) {
            $("#jumlah_hari").prop('disabled', false);
            $('#div_jumlah_hari').show();
            $('#div_umroh').hide();
        } else if (selectedValue == 13 || selectedValue == 14 || selectedValue == 15) {
            $("#jumlah_hari").prop('disabled', true);
            $('#div_jumlah_hari').hide();
            $('#div_umroh').show();
        } else{
            $("#jumlah_hari").prop('disabled', true);
            $('#div_jumlah_hari').hide();
            $('#div_umroh').hide();
        }
    });
    $('form#form #submit').click(function() {
        $('form#form').attr('target', '_self');
    });
    $('form#form #preview').click(function() {
        $('form#form').attr('target', '_blank');
    });
});
// 
$('#_datatable_cuti').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
} );
</script>
@endsection