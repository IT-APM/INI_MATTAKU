@extends('layouts.simple.master')
@section('title', 'Data Master Plafon Kesehatan')

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
<h3>Data Master Plafon Kesehatan</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item active">Plafon Kesehatan</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
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
                    @if(Session::has('msg'))
                    <!-- <div class="alert alert-success">{{Session::get('msg')}}</div> -->
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <a href="{{ route('plafon.create') }}" class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add new</a> | 
                    <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                    <form class="mt-2" action="{{ route('plafon.upload_data') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to upload this data?');">
                    @csrf
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <input name="template" id="template" class="form-control" type="file" required="">
                            <label class="form-label">Maksimal 2 MB dengan format xls/xlsx</label>
                            <br/><a href="{{ asset('assets/template/Template Data Master Plafon Kesehatan.xlsx') }}" target="_blank"><i class="icon-file"></i>&nbsp;Download template</a>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload</button>
                        </div>
                    </div>
                    </form>
                    <div class="collapse" id="collapse_filter">
                        <form class="mt-2" action="{{ route('plafon.index') }}" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-5">
                                    <label class="h6">Tahun</label>&nbsp;<input type="checkbox" onClick="toggle_tahun(this)" /> Check All
                                    <label class="d-block" for="tahun">
                                        @foreach ($tahun as $data)
                                        <input class="checkbox_animated" id="tahun[]" name="tahun[]" type="checkbox" value="{{ $data }}" @if(in_array($data, $arr_tahun)) checked @endif> {{ $data }}<br/>
                                        @endforeach
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- <br/><br/> --}}
                    <div class="table-responsive mt-2">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>Tahun</th>
                                    <th>Kesehatan</th>
                                    <th>Sisa Plafon Kesehatan</th>
                                    <th>Lensa</th>
                                    <th>Sisa Plafon Lensa</th>
                                    <th>Frame</th>
                                    <th>Sisa Plafon Frame</th>
                                    <th>Kehamilan</th>
                                    <th>Sisa Plafon Kehamilan</th>
                                    <th>Persalinan Normal</th>
                                    <th>Sisa Plafon Persalinan Normal</th>
                                    <th>Persalinan Caesar</th>
                                    <th>Sisa Plafon Persalinan Caesar</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @inject('sisaPlafon', 'App\Http\Controllers\ReimbursePlafonController')
                                @inject('sisaPlafonNonKes', 'App\Http\Controllers\TrxReimburseController')
                                @foreach ($plafon as $data)
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $data->_nik->nama }}</td>
                                    <td>{{ $data->nik_karyawan }}</td>
                                    <td>{{ $data->periode }}</td>
                                    <td>{{ number_format($data->kesehatan) }}</td>
                                    <td>{{ number_format($data->kesehatan - $sisaPlafon::getSisaPlafon($data->nik_karyawan, $data->periode)) }}</td>
                                    <td>{{ number_format($data->lensa) }}</td>
                                    <!-- 3= Kacamata-Lensa -->
                                    @if($sisaPlafonNonKes::getIsKlaimNonKesehatan($data->periode, $data->nik_karyawan, 3)['cek'])
                                    <td>{{ number_format(0) }}</td>
                                    @else
                                    <td>{{ number_format($data->lensa) }}</td>
                                    @endif
                                    <td>{{ number_format($data->frame) }}</td>
                                    <!-- 2= Kacamata-Frame -->
                                    @if($sisaPlafonNonKes::getIsKlaimNonKesehatan($data->periode, $data->nik_karyawan, 2)['cek'])
                                    <td>{{ number_format(0) }}</td>
                                    @else
                                    <td>{{ number_format($data->frame) }}</td>
                                    @endif
                                    <td>{{ number_format($data->kehamilan) }}</td>
                                    <!-- 4= Kehamilan -->
                                    @if($sisaPlafonNonKes::getIsKlaimNonKesehatan($data->periode, $data->nik_karyawan, 4)['cek'])
                                    <td>{{ number_format(0) }}</td>
                                    @else
                                    <td>{{ number_format($data->kehamilan) }}</td>
                                    @endif
                                    <td>{{ number_format($data->persalinan_normal) }}</td>
                                    <!-- 5= Persalinan-Normal -->
                                    @if($sisaPlafonNonKes::getIsKlaimNonKesehatan($data->periode, $data->nik_karyawan, 5)['cek'])
                                    <td>{{ number_format(0) }}</td>
                                    @else
                                    <td>{{ number_format($data->persalinan_normal) }}</td>
                                    @endif
                                    <td>{{ number_format($data->persalinan_caesar) }}</td>
                                    <!-- 6= Persalinan-Caesar -->
                                    @if($sisaPlafonNonKes::getIsKlaimNonKesehatan($data->periode, $data->nik_karyawan, 6)['cek'])
                                    <td>{{ number_format(0) }}</td>
                                    @else
                                    <td>{{ number_format($data->persalinan_caesar) }}</td>
                                    @endif
                                    <td>
                                        <form action="{{ route('plafon.destroy', $data->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <!-- <a href="{{ route('plafon.edit', $data->id) }}"><i class="icon-pencil-alt"></i></a>&nbsp; -->
                                            <!-- <li class="delete"><a href="#" onclick="return confirm('Yakin mau dihapus?')"><i class="icon-trash"></i></a></li> -->
                                            <!-- <a onClick="return confirm('Are you sure want to delete this data?')"><i class="icon-trash"></i></a> -->
                                            <!-- <a href="#"><i class="icon-trash show_confirm"></i></a> -->
                                            <!-- <button type="submit" class="btn btn-xs btn-danger btn-flat show_confirm" data-toggle="tooltip" title='Delete'>Delete</button> -->
                                            <ul class="action">
                                                <li class="edit"><a href="{{ route('plafon.edit', $data->id) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                {{-- <li class="delete"><a href="#" data-toggle="tooltip" title='Delete'><i class="icon-trash show_confirm"></i></a></li> --}}
                                                <li class="delete"><a href="#" id="btn-delete" name="btn-delete" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></a></li>
                                            </ul>
                                        </form>
                                    </td>
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
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
<script type="text/javascript">
function toggle_tahun(source) {
    checkboxes = document.getElementsByName('tahun[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
$('#_datatable').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
});
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
$('.show_confirm').click(function(event) {
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
</script>
@endsection