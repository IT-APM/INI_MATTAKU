@extends('layouts.simple.master')
@section('title', 'Data Master Pekerjaan')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Detail</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item active">Pekerjaan</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <label class="h6">Bidang Pekerjaan</label>
                            @foreach ($bidang as $data)
                            <label class="d-block" for="bidang">
                                <input class="checkbox_animated" id="bidang" name="bidang" type="checkbox" value="{{ $data->id }}"> {{ $data->bidang }}
                            </label>
                            @endforeach
                            <a href="{{ route('pekerjaan_bidang.index') }}" class="btn btn-primary mb-2" type="button"><i class="fa fa-plus" aria-hidden="true"></i> Add new</a>
                            <br/><button class="btn btn-warning" id="filter" name="filter" type="button"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <button class="btn btn-info" id="reset" name="reset" type="button"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
                            <!-- <label class="form-label" for="bidang[]">Bidang</label>
                            <div class="col-sm-12 pb-2">
                                <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple"
                                    id="bidang[]" name="bidang[]">
                                    @foreach ($bidang as $data)
                                    <option value="{{ $data->id }}">{{ $data->bidang }}</option>
                                    @endforeach
                                </select>
                            </div> -->
                        </div>
                        <div class="col-sm-9">
                            @if(Session::has('msg'))
                            <!-- <div class="alert alert-success">{{Session::get('msg')}}</div> -->
                            <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                                <h6>{{ session('msg') }}</h6>
                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif
                            <a href="{{ route('pekerjaan_master.create') }}" class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add new</a>
                            <div class="table-responsive mt-2">
                                <!-- id="basic-1" -->
                                <table class="display" id="_table_">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Bidang</th>
                                            <th>Pekerjaan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--  -->
                                    </tbody>
                                    {{-- <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Bidang</th>
                                            <th>Pekerjaan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead> --}}
                                </table>
                            </div>
                            {{-- <a href="{{ route('kebutuhan.create') }}" class="btn btn-warning" type="button">Back</a> --}}
                        </div>
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
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script type="text/javascript">
var dataTable;
$(document).ready(function() {
    // 
    loadData([0]);
    $("#filter").click(function() {
        var bidang = [0];
        $.each($("input[name='bidang']:checked"), function() {
            bidang.push($(this).val());
        });
        loadData(bidang);
    });
    // 
    $("#reset").click(function() {
        $("input[name='bidang']").prop("checked", false);
        loadData([0]);
    });
    // 
    function loadData(bidang) {
        // alert(bidang);
        $('#_table_').DataTable().clear().destroy();
        var i = 1;
        dataTable = $('#_table_').DataTable({
            // destroy: true,
            // retrieve: true,
            processing: true,
            serverSide: false,
            ajax: {
                // url: 'http://localhost/datatable/',
                url: "{{ route('pekerjaan_master_pekerjaan') }}" + "/" + bidang.join(","),
                type: "GET",
                dataSrc: ""
            },
            columns: [{
                    "render": function() {
                        return i++;
                    }
                },
                {
                    "data": "id_bidang"
                },
                {
                    "data": "pekerjaan"
                },
                {
                    "data": "id",
                    "render": function(data) {
                        var urlView = '{{ route("pekerjaan_master.show", ":id") }}';
                        urlView = urlView.replace(':id', data);
                        var urlDel = '{{ route("pekerjaan_master.edit", ":id") }}';
                        urlDel = urlDel.replace(':id', data);
                        return `<a href="`+urlView+`" data-toggle="tooltip" title="View"><i class="icon-eye"></i></a>
                        &nbsp;<a href="`+urlDel+`" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a>`;
                    }
                }
            ],
        });
    }
    // 
});
</script>
@endsection