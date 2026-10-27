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
<h3>Admin</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Data Mapping</li>
<li class="breadcrumb-item active">Admin</li>
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
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label class="form-label">Project ID</label>
                            <input class="form-control" value="{{ $project->project_id }}">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Project Name</label>
                            <input class="form-control" value="{{ $project->name }}">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Division</label>
                            <input class="form-control" value="{{ $project->divisi }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label class="form-label">Customer Name</label>
                            <input class="form-control" value="{{ $project->customer }}">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Group</label>
                            <input class="form-control" value="{{ $project->group }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="form-label">COA Prefix</label><br/>
                            <input class="form-control" value="{{ $project->coa_prefix }}">
                        </div>
                        <div class="col-sm-2">
                            <label class="form-label">Status</label><br/>
                            @if($project->is_active==1) <span class="badge bg-success" style="font-size:18px">Aktif</span> @else <span class="badge bg-danger" style="font-size:18px">Non Aktif</span> @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('mtrdataprojectadminpic.create', ['project_id'=>Crypt::encrypt($project->id)]) }}" class="btn btn-primary" type="button">Add new</a> | 
                            <a href="{{ route('mtrdataprojectadmin.index') }}" class="btn btn-warning" type="button">Back</a><br /><br />
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Admin</th>
                                    <th>NIP</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trx as $data)
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $data->_admin->nama }}</td>
                                    <td>{{ $data->admin }}</td>
                                    <td>
                                        <form action="{{ route('mtrdataprojectadminpic.destroy', [$data->id, 'project_id'=>Crypt::encrypt($project->id)]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <ul class="action">
                                                <li class="edit"><a href="{{ route('mtrdataprojectadminpic.edit', [$data->id, 'project_id'=>Crypt::encrypt($project->id)]) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
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