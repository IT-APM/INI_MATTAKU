@extends('layouts.simple.master')
@section('title', 'Reminder Penilaian Karyawan')

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
<h3>Reminder Penilaian Karyawan</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Reminder</li>
<li class="breadcrumb-item active">Formulir Penilaian Karyawan</li>
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
                    <ul class="nav nav-tabs nav-info" id="pills-warningtab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="pills-non_outsource-tab" data-bs-toggle="pill" href="#pills-non_outsource" role="tab" aria-controls="pills-non_outsource" aria-selected="false">Reguler</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-outsource-tab" data-bs-toggle="pill" href="#pills-outsource" role="tab" aria-controls="pills-outsource" aria-selected="true">Outsourcing</a></li>
                    </ul>
                    <div class="tab-content" id="pills-warningtabContent">
                        <div class="tab-pane fade show active" id="pills-non_outsource" role="tabpanel" aria-labelledby="pills-non_outsource-tab">
                            <!--  -->
                            {{-- <br/> --}}
                            @if(count($trx_non_outsource)>0)
                            <form action="{{ route('penilaian_reminder.store') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to execute this data?');">
                            @csrf
                            <input type="hidden" id="is_outsource" name="is_outsource" value="0">
                            <button class="btn btn-info mt-2 mb-2" type="submit" name="submit" id="submit" value="execute"><i class="fa fa-repeat" aria-hidden="true"></i> Execute</button> | <button class="btn btn-danger mt-2 mb-2" type="submit" name="submit" id="submit" value="delete"><i class="icon-trash" aria-hidden="true"></i> Delete</button>
                            {{-- <br/> --}}
                            @endif
                            <div class="table-responsive">
                                <table class="display" id="_datatable_non_outsource">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" onClick="toggle_status_non_outsource(this)" /></th>
                                            <th>No</th>
                                            <th>Karyawan</th>
                                            <th>NIP</th>
                                            <th>Penilai 1</th>
                                            <th>Penilai 2</th>
                                            <th>Penilai 3</th>
                                            <th>Tipe</th>
                                            <th>Status</th>
                                            <th>Tanggal Berakhir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trx_non_outsource as $data)
                                        <tr>
                                            <td><input id="id_non_outsource[]" name="id_non_outsource[]" type="checkbox" value="{{ $data->id_trx }}"></td>
                                            <td>{{ ++$no_non_outsource }}</td>
                                            <td>{{ $data->_karyawan->nama }}</td>
                                            <td>{{ $data->nik_karyawan }}</td>
                                            <td>{{ $data->_penilai1->nama }}</td>
                                            <td>@if (!empty($data->nik_penilai_2)) {{ $data->_penilai2->nama }} @endif</td>
                                            <td>@if (!empty($data->nik_penilai_3)) {{ $data->_penilai3->nama }} @endif</td>
                                            <td>{{ $data->_tipe->tipe_penilaian }}</td>
                                            <td>{{ $data->_status->status_penilaian }}</td>
                                            <td>{{ Carbon\Carbon::createFromFormat('Y-m-d', $data->end_date)->format('d-m-Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            </form>
                            <!--  -->
                        </div>
                        <div class="tab-pane fade" id="pills-outsource" role="tabpanel" aria-labelledby="pills-outsource-tab">
                            <!--  -->
                            {{-- <br/> --}}
                            @if(count($trx_outsource)>0)
                            <form action="{{ route('penilaian_reminder.store') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to execute this data?');">
                            @csrf
                            <input type="hidden" id="is_outsource" name="is_outsource" value="1">
                            <button class="btn btn-info mt-2 mb-2" type="submit" name="submit" id="submit" value="execute"><i class="fa fa-repeat" aria-hidden="true"></i> Execute</button> | <button class="btn btn-danger mt-2 mb-2" type="submit" name="submit" id="submit" value="delete"><i class="icon-trash" aria-hidden="true"></i> Delete</button>
                            {{-- <br/> --}}
                            @endif
                            <div class="table-responsive">
                                <table class="display" id="_datatable_outsource">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" onClick="toggle_status_outsource(this)" /></th>
                                            <th>No</th>
                                            <th>Karyawan</th>
                                            <th>NIP</th>
                                            <th>Penilai 1</th>
                                            <th>Penilai 2</th>
                                            <th>Penilai 3</th>
                                            <th>Tipe</th>
                                            <th>Status</th>
                                            <th>Tanggal Berakhir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trx_outsource as $data)
                                        <tr>
                                            <td><input id="id_outsource[]" name="id_outsource[]" type="checkbox" value="{{ $data->id_trx }}"></td>
                                            <td>{{ ++$no_outsource}}</td>
                                            <td>{{ $data->_karyawan->nama }}</td>
                                            <td>{{ $data->nik_karyawan }}</td>
                                            <td>{{ $data->_penilai1->nama }}</td>
                                            <td>@if (!empty($data->nik_penilai_2)) {{ $data->_penilai2->nama }} @endif</td>
                                            <td>@if (!empty($data->nik_penilai_3)) {{ $data->_penilai3->nama }} @endif</td>
                                            <td>{{ $data->_tipe->tipe_penilaian }}</td>
                                            <td>{{ $data->_status->status_penilaian }}</td>
                                            <td>{{ Carbon\Carbon::createFromFormat('Y-m-d', $data->end_date)->format('d-m-Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            </form>
                            <!--  -->
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
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/jszip.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
<script type="text/javascript">
// 
function toggle_status_non_outsource(source) {
    checkboxes = document.getElementsByName('id_non_outsource[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function toggle_status_outsource(source) {
    checkboxes = document.getElementsByName('id_outsource[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
$('#_datatable_non_outsource').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
} );
// 
$('#_datatable_outsource').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
} );
// 
// $(document).on('click', '#btn-delete', function(event) {
//     var form = $(this).closest("form");
//     event.preventDefault();
//     swal({
//             title: "Are you sure to delete this?",
//             text: "If you delete this, it will be gone for this session only.",
//             icon: "warning",
//             buttons: true,
//             dangerMode: true,
//         })
//         .then((willDelete) => {
//             if (willDelete) {
//                 form.submit();
//             }
//         });
// });
// 
</script>
@endsection