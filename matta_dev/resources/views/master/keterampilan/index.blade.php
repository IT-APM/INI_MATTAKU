@extends('layouts.simple.master')
@section('title', 'Basic DataTables')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Data Master Keterampilan</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item active">Keterampilan</li>
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
                    <a href="{{ route('_keterampilan_master.create') }}" class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add new</a><br /><br />
                    <div class="table-responsive">
                        <table class="display" id="basic-1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis</th>
                                    <th>Keterampilan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($keterampilan_sub as $data)
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $data->_keterampilan->keterampilan }}</td>
                                    <td>{{ $data->sub_keterampilan }}</td>
                                    <td>
                                        <form action="{{ route('_keterampilan_master.destroy', $data->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <ul class="action">
                                                <li class="edit"><a href="{{ route('_keterampilan_master.edit', $data->id) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                <li class="delete"><a href="#" data-toggle="tooltip" title='Delete'><i class="icon-trash show_confirm"></i></a></li>
                                            </ul>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- <a href="{{ route('kebutuhan.create') }}" class="btn btn-warning" type="button">Back</a> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
<script type="text/javascript">
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