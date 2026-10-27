@extends('layouts.simple.master')

@section('title', 'Payroll')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
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
<h3>Payroll Master</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Payroll</li>
<li class="breadcrumb-item active">Payroll Master</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('msg'))
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if (count($errors) > 0)
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h6>Upload File is ERROR</h6>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
                        <li class="nav-item"><a class="nav-link @if($id == 0) active @endif" id="pills-karyawan-tab" data-bs-toggle="pill" href="#pills-karyawan" role="tab" aria-controls="pills-karyawan" aria-selected="false">Karyawan</a></li>
                        <li class="nav-item"><a class="nav-link @if($id == 1) active @endif" id="pills-tambah-tab" data-bs-toggle="pill" href="#pills-tambah" role="tab" aria-controls="pills-tambah" aria-selected="true">Penambahan</a></li>
                        <li class="nav-item"><a class="nav-link @if($id == 2) active @endif" id="pills-kurang-tab" data-bs-toggle="pill" href="#pills-kurang" role="tab" aria-controls="pills-kurang" aria-selected="true">Pengurangan</a></li>
                        <li class="nav-item"><a class="nav-link @if($id == 3) active @endif" id="pills-bpjstk-tab" data-bs-toggle="pill" href="#pills-bpjstk" role="tab" aria-controls="pills-bpjstk" aria-selected="true">BPJS TK</a></li>
                        <li class="nav-item"><a class="nav-link @if($id == 4) active @endif" id="pills-bpjskes-tab" data-bs-toggle="pill" href="#pills-bpjskes" role="tab" aria-controls="pills-bpjskes" aria-selected="true">BPJS Kesehatan</a></li>
                        <li class="nav-item"><a class="nav-link @if($id == 5) active @endif" id="pills-bpjsmax-tab" data-bs-toggle="pill" href="#pills-bpjsmax" role="tab" aria-controls="pills-bpjsmax" aria-selected="true">Parameter</a></li>
                    </ul>
                    <div class="tab-content" id="pills-warningtabContent">
                        {{-- Karyawan --}}
                        <div class="tab-pane fade @if($id == 0) show active @endif" id="pills-karyawan" role="tabpanel" aria-labelledby="pills-karyawan-tab">
                            <p class="mb-0 mt-2">
                                <div class="row g-3">
                                    <div class="col-md-2">
                                        <a href="{{ route('payroll.master',[0, 'tipe'=>'karyawan']) }}" class="btn btn-primary mb-2" type="button"><i class="fa fa-plus"></i> Add new</a>
                                    </div>
                                    <div class="col-md-10">
                                        <form action="{{ route('payroll.upload_payroll') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to upload this data?');">
                                        @csrf
                                        <input type="hidden" id="jenis" name="jenis" value="mtr">
                                        <div class="row mb-2">
                                            <div class="col-md-4">
                                                <input name="template" id="template" class="form-control" type="file" required="">
                                                <label class="form-label">Maksimal 2 MB dengan format xls/xlsx</label>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload</button>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable1">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                {{-- <th>Bulan</th>
                                                <th>Tahun</th>
                                                <th>Gaji Pokok</th> --}}
                                                <th>Pasangan</th>
                                                <th>Anak</th>
                                                <th>Tambahan</th>
                                                <th>No KTP</th>
                                                <th>No KPJ</th>
                                                <th>No JKN</th>
                                                <th>Rekening Atas Nama</th>
                                                <th>No. Rekening</th>
                                                <th>Nama Bank</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no=0; @endphp
                                            @foreach ($payroll as $data)
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $data->_nik->nama }}</td>
                                                <td>{{ $data->nik }}</td>
                                                {{-- <td>{{ $data->bulan }}</td>
                                                <td>{{ $data->tahun }}</td>
                                                <td class="text-end">{{ number_format($data->gapok, 0) }}</td> --}}
                                                <td>{{ $data->pasangan }}</td>
                                                <td>{{ $data->anak }}</td>
                                                <td>{{ $data->tambahan }}</td>
                                                <td>{{ $data->no_ktp }}</td>
                                                <td>{{ $data->no_kpj }}</td>
                                                <td>{{ $data->no_jkn }}</td>
                                                <td>{{ $data->nama_rek }}</td>
                                                <td>{{ $data->no_rek }}</td>
                                                <td>{{ $data->nama_bank }}</td>
                                                <td>
                                                    <form action="{{ route('payroll.destroy',[0, 'tipe'=>'del_karyawan', 'id'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)]) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <ul class="action">
                                                            <li class="edit"><a href="{{ route('payroll.master',[0, 'tipe'=>'edit_karyawan', 'id_data'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)]) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                            <li class="delete"><a href="#" id="btn-delete" name="btn-delete" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></a></li>
                                                        </ul>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                        {{-- Penambahan --}}
                        <div class="tab-pane fade @if($id == 1) show active @endif" id="pills-tambah" role="tabpanel" aria-labelledby="pills-tambah-tab">
                            <p class="mb-0 m-t-10">
                                <a href="{{ route('payroll.master',[1, 'tipe'=>'add']) }}" class="btn btn-primary mb-2" type="button"><i class="fa fa-plus"></i> Add new</a>
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable2">
                                        <thead>
                                            <tr>
                                                <th>#ID</th>
                                                <th>Komponen</th>
                                                <th>Bulanan</th>
                                                <th>Sumber</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no=0; @endphp
                                            @foreach ($add as $data)
                                            <tr>
                                                <td>{{ $data->id }}</td>
                                                <td>{{ $data->nama }}</td>
                                                <td>@if($data->bulanan == 1) Iya @else Tidak @endif</td>
                                                <td>{{ $data->status }}</td>
                                                <td>
                                                    <form action="{{ route('payroll.destroy',[1, 'tipe'=>'del', 'id'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)]) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <ul class="action">
                                                            <li class="edit"><a href="{{ route('payroll.master',[1, 'tipe'=>'edit', 'id_data'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)]) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                            <li class="delete"><a href="#" id="btn-delete" name="btn-delete" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></a></li>
                                                        </ul>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                        {{-- Pengurangan --}}
                        <div class="tab-pane fade @if($id == 2) show active @endif" id="pills-kurang" role="tabpanel" aria-labelledby="pills-kurang-tab">
                            <p class="mb-0 m-t-10">
                                <a href="{{ route('payroll.master',[2, 'tipe'=>'sub']) }}" class="btn btn-primary mb-2" type="button"><i class="fa fa-plus"></i> Add new</a>
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable3">
                                        <thead>
                                            <tr>
                                                <th>#ID</th>
                                                <th>Komponen</th>
                                                <th>Bulanan</th>
                                                <th>Sumber</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no=0; @endphp
                                            @foreach ($sub as $data)
                                            <tr>
                                                <td>{{ $data->id }}</td>
                                                <td>{{ $data->nama }}</td>
                                                <td>@if($data->bulanan == 1) Iya @else Tidak @endif</td>
                                                <td>{{ $data->status }}</td>
                                                <td>
                                                    <form action="{{ route('payroll.destroy',[2, 'tipe'=>'del', 'id'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)]) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <ul class="action">
                                                            <li class="edit"><a href="{{ route('payroll.master',[2, 'tipe'=>'edit', 'id_data'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)]) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                            <li class="delete"><a href="#" id="btn-delete" name="btn-delete" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></a></li>
                                                        </ul>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                        {{-- BPJSTK --}}
                        <div class="tab-pane fade @if($id == 3) show active @endif" id="pills-bpjstk" role="tabpanel" aria-labelledby="pills-bpjstk-tab">
                            <p class="mb-0 m-t-10">
                                <a href="{{ route('payroll.master',[3, 'tipe'=>'bpjstk']) }}" class="btn btn-primary mb-2" type="button"><i class="fa fa-plus"></i> Add new</a>
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable4">
                                        <thead>
                                            <tr>
                                                <th>#ID</th>
                                                <th>Komponen</th>
                                                <th>Persen</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no=0; @endphp
                                            @foreach ($bpjstk as $data)
                                            <tr>
                                                <td>{{ $data->id }}</td>
                                                <td>{{ $data->nama }}</td>
                                                <td>{{ number_format($data->persen, 2) }} %</td>
                                                <td>
                                                    <form action="{{ route('payroll.destroy',[3, 'tipe'=>'del_bpjstk', 'id'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)]) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <ul class="action">
                                                            <li class="edit"><a href="{{ route('payroll.master',[3, 'tipe'=>'edit_bpjstk', 'id_data'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)]) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                            <li class="delete"><a href="#" id="btn-delete" name="btn-delete" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></a></li>
                                                        </ul>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                        {{-- BPJSKes --}}
                        <div class="tab-pane fade @if($id == 4) show active @endif" id="pills-bpjskes" role="tabpanel" aria-labelledby="pills-bpjskes-tab">
                            <p class="mb-0 m-t-10">
                                <a href="{{ route('payroll.master',[4, 'tipe'=>'bpjsks']) }}" class="btn btn-primary mb-2" type="button"><i class="fa fa-plus"></i> Add new</a>
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable5">
                                        <thead>
                                            <tr>
                                                <th>#ID</th>
                                                <th>Komponen</th>
                                                <th>Persen</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no=0; @endphp
                                            @foreach ($bpjskes as $data)
                                            <tr>
                                                <td>{{ $data->id }}</td>
                                                <td>{{ $data->nama }}</td>
                                                <td>{{ number_format($data->persen, 2) }} %</td>
                                                <td>
                                                    <form action="{{ route('payroll.destroy',[4, 'tipe'=>'del_bpjsks', 'id'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)]) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <ul class="action">
                                                            <li class="edit"><a href="{{ route('payroll.master',[4, 'tipe'=>'edit_bpjsks', 'id_data'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)]) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                            <li class="delete"><a href="#" id="btn-delete" name="btn-delete" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></a></li>
                                                        </ul>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                        {{-- Parameter --}}
                        <div class="tab-pane fade @if($id == 5) show active @endif" id="pills-bpjsmax" role="tabpanel" aria-labelledby="pills-bpjsmax-tab">
                            <p class="mb-0 m-t-10">
                                <a href="{{ route('payroll.master',[5, 'tipe'=>'bpjsmx']) }}" class="btn btn-primary  mb-2" type="button"><i class="fa fa-plus"></i> Add new</a>
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable6">
                                        <thead>
                                            <tr>
                                                <th>#ID</th>
                                                <th>Komponen</th>
                                                <th>Angka</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no=0; @endphp
                                            @foreach ($bpjsmax as $data)
                                            <tr>
                                                <td>{{ $data->id }}</td>
                                                <td>{{ $data->nama }}</td>
                                                <td class="text-end">{{ number_format($data->angka, 0) }}</td>
                                                <td>
                                                    <form action="{{ route('payroll.destroy',[5, 'tipe'=>'del_bpjsmx', 'id'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)]) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <ul class="action">
                                                            <li class="edit"><a href="{{ route('payroll.master',[5, 'tipe'=>'edit_bpjsmx', 'id_data'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)]) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                            <li class="delete"><a href="#" id="btn-delete" name="btn-delete" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></a></li>
                                                        </ul>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                        {{--  --}}
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
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
<script type="text/javascript">
// 
$(document).on('click', '#btn-delete', function(event) {
    var form = $(this).closest("form");
    event.preventDefault();
    swal({
            title: "Are you sure to delete this?",
            text: "If you delete this, it will be gone forever.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                form.submit();
            }
        });
});
// 
// karyawan
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
// penambahan
$('#_datatable2').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]]
} );
// pengurangan
$('#_datatable3').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]]
} );
// bpjstk
$('#_datatable4').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]]
} );
// bpjskes
$('#_datatable5').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]]
} );
// bpjsmax
$('#_datatable6').DataTable( {
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