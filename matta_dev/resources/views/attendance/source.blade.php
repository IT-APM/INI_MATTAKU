@extends('layouts.simple.master')
@section('title', 'Attendance Recap')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
    <style>
        button.dt-button {
            float: none;
            text-align: center;
            color: black;
            background: white;
        }
    </style>
@endsection

@section('breadcrumb-title')
    <h3>Attendance Source</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Attendance</li>
    <li class="breadcrumb-item active">Source</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- State saving Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                        <div class="collapse" id="collapse_filter">
                            <form class="mt-2" action="{{ route('attendance.source', 1) }}" method="get"
                                enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label class="h6">Date</label>
                                        <input class="form-control" name="date_from" id="date_from" type="date" required="" value="{{ $date_from }}">
                                        Sampai <input class="form-control" name="date_to" id="date_to" type="date" required="" value="{{ $date_to }}">
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Karyawan</label>
                                        <select class="form-select js-example-basic-single col-sm-12" id="str_att_nik_nama" name="str_att_nik_nama">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            <option value="">Semua</option>
                                            @foreach ($att_nik_nama as $data)
                                                <option value="{{ $data->nik }}" @if($data->nik == $str_att_nik_nama) selected @endif>{{ $data->nama }} - {{ $data->nik }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-12">
                                        <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive mt-2">
                            <label class="h5">Finger Print</label>
                            <table class="display" id="_datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>NIP</th>
                                        <th>ID_FP</th>
                                        <th>Waktu</th>
                                        <th>Mesin</th>
                                        {{-- <th>Clock In</th>
                                        <th>Clock Out</th> --}}
                                    </tr>
                                </thead>
                                @php $no = 0; @endphp
                                <tbody>
                                    @foreach ($trx_fp as $data)
                                        <tr>
                                            <td>{{ ++$no }}</td>
                                            <td>{{ $data->nama }}</td>
                                            <td>{{ $data->nik }}</td>
                                            <td>{{ $data->id_nip }}</td>
                                            <td>{{ $data->waktu ? Carbon\Carbon::parse($data->waktu)->format('d-m-Y H:i:s') : '' }}</td>
                                            {{-- <td>@if(!empty($data->id_mesin)) FINGER-{{ $data->id_mesin }} @endif</td> --}}
                                            <td>@if($data->id_mesin == 1) <span class="badge badge-primary">FINGER-{{ $data->id_mesin }}</span> @else <span class="badge badge-info">FINGER-{{ $data->id_mesin }}</span> @endif</td>
                                            {{-- <td>{{ $data->clock_in ? Carbon\Carbon::parse($data->clock_in)->format('d-m-Y H:i:s') : '' }}</td>
                                            <td>{{ $data->clock_out ? Carbon\Carbon::parse($data->clock_out)->format('d-m-Y H:i:s') : '' }}</td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{--  --}}
                        <div class="table-responsive">
                            <label class="h5">Matta</label>
                            <table class="display" id="_datatable_">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>NIP</th>
                                        <th>Clock In</th>
                                        <th>Clock Out</th>
                                    </tr>
                                </thead>
                                @php $no = 0; @endphp
                                <tbody>
                                    @foreach ($trx_matta as $data)
                                        <tr>
                                            <td>{{ ++$no }}</td>
                                            <td>{{ $data->nama }}</td>
                                            <td>{{ $data->nik }}</td>
                                            <td>{{ $data->clock_in ? Carbon\Carbon::parse($data->clock_in)->format('d-m-Y H:i:s') : '' }}</td>
                                            <td>{{ $data->clock_out ? Carbon\Carbon::parse($data->clock_out)->format('d-m-Y H:i:s') : '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{--  --}}
                    </div>
                </div>
            </div>
            <!-- State saving Ends-->
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
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script type="text/javascript">
// 
$('#_datatable').DataTable({
    // dom: 'lBfrtip',
    dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" + "<'row'<'col-sm-12'rt>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    "lengthMenu": [
        [50, 100, 200, 500, -1],
        [50, 100, 200, 500, "All"]
    ]
});
$('#_datatable_').DataTable({
    // dom: 'lBfrtip',
    dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" + "<'row'<'col-sm-12'rt>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    "lengthMenu": [
        [50, 100, 200, 500, -1],
        [50, 100, 200, 500, "All"]
    ]
});
</script>
@endsection
