@extends('layouts.simple.master')
@section('title', 'PDP DataTables')

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
<h3>Salary Structure</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item active">Salary Structure</li>
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
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <a href="{{ route('salary_str.create') }}" class="btn btn-primary mb-2" type="button"><i class="fa fa-plus"></i> Add new</a>
                    <div class="col-sm-6">
                    <form action="{{ route('salary_str.upload_template') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to upload this data?');">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-8">
                            <input name="template" id="template" class="form-control" type="file" required="">
                            <label class="form-label">Maksimal 2 MB dengan format xls/xlsx</label>
                            <br/><a href="{{ asset('assets/template/template_struktur_gaji_v1.xlsx') }}" target="_blank"><i class="icon-file"></i>&nbsp;Download Salary Structure</a>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload</button>
                        </div>
                    </div>
                    </form>
                </div>
                    <div class="table-responsive">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Level</th>
                                    <th>Grade</th>
                                    <th>Tingkat</th>
                                    <th>Tahun</th>
                                    <th>Gaji Pokok</th>
                                    <th>Tunjangan Makan</th>
                                    <th>Tunjangan Transport</th>
                                    <th>Tunjangan Jabatan</th>
                                    <th>Tunjangan Telekomunikasi</th>
                                    <th>Tunjangan Lain-Lain</th>
                                    <th>Total Tunjangan</th>
                                    <th>Upah</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no=0; @endphp
                                @foreach ($trx as $data)
                                @php
                                 $total_tunjangan = $data->t_makan + $data->t_transport + $data->t_jabatan + $data->t_telekomunikasi + $data->t_lain;
                                @endphp
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $data->_grade->level }}</td>
                                    <td>{{ $data->_grade->grade }}</td>
                                    <td>{{ $data->tingkat }}</td>
                                    <td>{{ $data->tahun }}</td>
                                    <td class="text-end">{{ number_format($data->gapok, 0) }}</td>
                                    <td class="text-end">{{ number_format($data->t_makan, 0) }}</td>
                                    <td class="text-end">{{ number_format($data->t_transport, 0) }}</td>
                                    <td class="text-end">{{ number_format($data->t_jabatan, 0) }}</td>
                                    <td class="text-end">{{ number_format($data->t_telekomunikasi, 0) }}</td>
                                    <td class="text-end">{{ number_format($data->t_lain, 0) }}</td>
                                    <td class="text-end">{{ number_format($total_tunjangan, 0) }}</td>
                                    <td class="text-end">{{ number_format($data->gapok + $total_tunjangan, 0) }}</td>
                                    <td>
                                        <form action="{{ route('salary_str.destroy', $data->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <ul class="action">
                                                <li class="edit"><a href="{{ route('salary_str.edit', $data->id) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
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
</script>
@endsection