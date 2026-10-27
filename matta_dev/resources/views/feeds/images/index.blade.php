@extends('layouts.simple.master')
@section('title', 'Basic DataTables')

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
<h3>Daftar Gambar</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item active">Gambar</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <!-- @if(Session::has('msg'))
                    <div class="alert alert-success">{{Session::get('msg')}}</div>
                    @endif -->
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                            <h6>{{ $message }}</h6>
                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
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
                    <form action="{{ route('feeds.image.store' ) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <input type="file" name="image" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-success">Upload</button> | <a href="{{ route('feeds.index') }}" class="btn btn-warning" type="button"><i class="fa fa-arrow-left"></i> Back</a>
                            </div>
                        </div>
                    </form>
                    <br /><br />
                    <div class="table-responsive">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>url</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($images as $data)
                                <tr>
                                    <!-- <td>{{ ++$no }}</td> -->
                                    <td>{{ asset('feeds/images/'.$data->getFilename()) }}</td>
                                    <td><a href="{{ asset('feeds/images/'.$data->getFilename()) }}" target="_blank"><img class="img-thumbnail" src="{{ asset('feeds/images/'.$data->getFilename()) }}" alt="user" width="200"></a></td>
                                    <td>
                                    <form id="myform" name="myform" method="get" enctype="multipart/form-data" action="{{ route('feeds.image.remove') }}" target="_self" onsubmit="return confirm('Are you sure want to delete this image?');">
                                        @method('GET')
                                        @csrf
                                        <input type="hidden" id="image" name="image" value="{{ $data->getFilename() }}">
                                        <button type="submit" class="btn btn-danger">Delete</button>
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