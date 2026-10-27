@extends('layouts.simple.master')
@section('title', 'Jadwal')

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
<h3>Jadwal Grup Random</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Jadwal</li>
<li class="breadcrumb-item active">Grup Random</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- State saving Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        @if(Session::has('msg'))
                        <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                            <h6>{{ session('msg') }}</h6>
                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        <a href="{{ route('jadwalrandom.create') }}" class="btn btn-success" type="button"><i class="fa fa-plus"></i> Insert</a> | <a href="{{ route('jadwalrandom.index') }}" class="btn btn-warning" type="button"><i class="fa fa-arrow-left"></i> Back</a>
                        <br /><br />
                        <form action="{{ route('generate_random') }}" method="get" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to Generate this schedule?');">
                        @csrf
                            <button class="btn btn-danger" type="submit"><i class="fa fa-gear"></i> Generate</button>
                        </form>
                        <br />
                        <div class="table-responsive">
                            <table class="display" id="_datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama/NIP</th>
                                        <th>Shift</th>
                                        <th>Tanggal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($trx as $data)
                                    <tr>
                                       <td>{{ ++$no }}</td>
                                       <td>{{ $data->_nik->nama }} / {{ $data->nik }}</td>
                                       <td>{{ $data->_shift->shift }}<br/>{{ $data->_shift->clock_in }} sd {{ $data->_shift->clock_out }}</td>
                                       <td>{{ \Carbon\Carbon::parse($data->tanggal_awal)->format('d-m-Y') }}</td>
                                        <td>
                                            <form action="{{ route('jadwalrandom.destroy', $data->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <ul class="action">
                                                    <li class="edit"><a href="{{ route('jadwalrandom.edit', $data->id) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                    <li class="delete"><a href="#" data-toggle="tooltip" title='Delete'><i class="icon-trash show_confirm"></i></a></li>
                                                </ul>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <!-- <tfoot>
                                    <tr>
                                        <th>No  </th>
                                        <th>Timestamp</th>
                                        <th>Nama</th>
                                        <th>Action</th>
                                        <th>Longitude</th>
                                        <th>Latitude</th>
                                        <th>Status</th>
                                        <th>Type</th>
                                    </tr>
                                </tfoot> -->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- State saving Ends-->
        </div>
    </div>
@endsection

@section('script')
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
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
$('#_datatable').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
} );
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
