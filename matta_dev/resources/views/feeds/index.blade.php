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
<h3>Data Master Feeds</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item active">Feeds</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('msg'))
                    <div class="alert alert-success">{{Session::get('msg')}}</div>
                    @endif
                    <a href="{{ route('feeds.create') }}" class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add new</a> | <a href="{{ route('feeds.show', 0) }}" class="btn btn-warning" type="button"><i class="fa fa-image"></i> Images</a><br /><br />
                    <div class="table-responsive">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Is Active</th>
                                    <th>Is Notif</th>
                                    <th>Title</th>
                                    <th>Created by</th>
                                    <th>Created at</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($feed as $data)
                                @inject('kary', 'App\Http\Controllers\FeedController')
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>@if ($data->is_active == 1) <span class="badge badge-success">Aktif</span> @else
                                        <span class="badge badge-danger">Non Aktif</span> @endif</td>
                                    <td>@if ($data->is_notif == 1) <span class="badge badge-success">Aktif</span> @else
                                        <span class="badge badge-danger">Non Aktif</span> @endif</td>
                                    <td>{{ $data->title }}</td>
                                    <td>{{ $kary::getKaryawan($data->created_by) }}</td>
                                    <td>{{ $data->created_at }}</td>
                                    <td>
                                        <form action="{{ route('feeds.destroy', $data->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <ul class="action">
                                                <li class="edit"><a href="{{ route('feeds.edit', $data->id) }}"
                                                        data-toggle="tooltip" title='Edit'><i
                                                            class="icon-pencil-alt"></i></a></li>
                                                <li class="delete"><a href="#" data-toggle="tooltip" title='Delete'><i
                                                            class="icon-trash show_confirm"></i></a></li>
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