@extends('layouts.simple.master')

@section('title', 'Payroll')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}"> --}}
@endsection

@section('style')
<style>
    button.dt-button {
    float:none;
    text-align:center;
    color: black;
    background: white;
    }
</style>
@endsection

@section('breadcrumb-title')
<h3>Payroll Slip</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Payroll</li>
<li class="breadcrumb-item active">Payroll Slip</li>
@endsection
@php
$bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
@endphp
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(count($payroll)>0 || count($payroll_h)>0)<h6 class="text-danger">Catatan: Masukkan tanggal lahir anda dengan format ddmmyyyy sebagai password untuk membuka file pdf slip gaji.<br>Contoh: Jika tanggal lahir anda adalah 2 Maret 2004 maka passwordnya adalah 02032004.</h6>@endif
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                        </div>
                    </div>
                    <div class="collapse mt-2" id="collapse_filter">
                        <form action="{{ route('payroll.slip', (new App\Http\Controllers\OpenDataController)->encryptWithKey(auth()->user()->nik)) }}" method="get" enctype="multipart/form-data" class="needs-validation" novalidate="">
                            <div class="row">
                                <div class="col-sm-2">
                                    <label class="h6" for="nama">Bulan-Tahun</label>
                                    <div>
                                        <input name="bulan_tahun_dari" id="bulan_tahun_dari" class="form-control digits" type="month" required="" value="{{ $bulan_tahun_dari }}">
                                        <div class="invalid-feedback txt-danger">Field is required.</div>
                                    </div>
                                    sampai
                                    <div>
                                        <input name="bulan_tahun_sampai" id="bulan_tahun_sampai" class="form-control digits" type="month" required="" value="{{ $bulan_tahun_sampai }}">
                                        <div class="invalid-feedback txt-danger">Field is required.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive mt-2">
                        <table class="display nowrap" id="_datatable1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tahun</th>
                                    <th>Bulan</th>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>Slip Gaji</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no=0;
                                    $hco = explode(',', App\Models\MtrUtility::find(32)->informasi);
                                @endphp
                                @foreach ($payroll as $data)
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $data->tahun }}</td>
                                    <td>{{ $bulan[$data->bulan - 1] }}</td>
                                    <td>{{ $data->_nik->nama }}</td>
                                    <td>{{ $data->nik }}</td>
                                    @if(in_array(auth()->user()->nik, $hco))
                                    <td><a href="{{ route('payroll.slip.detail', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => 'pdf']) }}" data-toggle="tooltip" title="Slip Gaji" target="_blank"><i class="fa fa-file-pdf-o"></i></a></td>
                                    @else
                                    <td>@if((!empty($data->paid_at)) && (Carbon\Carbon::parse($data->paid_at)->isPast() || Carbon\Carbon::parse($data->paid_at)->isToday()))<a href="{{ route('payroll.slip.detail', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => 'pdf']) }}" data-toggle="tooltip" title="Slip Gaji" target="_blank"><i class="fa fa-file-pdf-o"></i></a>@endif</td>
                                    @endif
                                </tr>
                                @endforeach
                                @foreach ($payroll_h as $data)
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $data->tahun }}</td>
                                    <td>{{ $bulan[$data->bulan - 1] }}</td>
                                    <td>{{ $data->_nik->nama }}</td>
                                    <td>{{ $data->nik }}</td>
                                    @if(in_array(auth()->user()->nik, $hco))
                                    <td><a href="{{ route('payroll.slip.detail', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => 'pdf_h']) }}" data-toggle="tooltip" title="Slip Gaji" target="_blank"><i class="fa fa-file-pdf-o"></i></a></td>
                                    @else
                                    <td>@if((!empty($data->paid_at)) && (Carbon\Carbon::parse($data->paid_at)->isPast() || Carbon\Carbon::parse($data->paid_at)->isToday()))<a href="{{ route('payroll.slip.detail', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => 'pdf_h']) }}" data-toggle="tooltip" title="Slip Gaji" target="_blank"><i class="fa fa-file-pdf-o"></i></a>@endif</td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/jszip.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.print.min.js') }}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
{{-- <script src="{{ asset('assets/js/sweet-alert/sweetalert.minjs') }}"></script>
<script src="{{ asset('assets/js/sweet-alert/appjs') }}"></script> --}}
<script type="text/javascript">
// semua
$('#_datatable1').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]]
} );
</script>
@endsection