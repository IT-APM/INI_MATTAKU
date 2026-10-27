@extends('layouts.simple.master')
@section('title', 'Health Reporting')

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
<h3>Health Reporting</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Health</li>
<li class="breadcrumb-item active">Reporting</li>
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
                            <form class="m-4" action="{{ route('health.index') }}" method="get" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label class="h6">Date</label>
                                        <br/>From <input class="form-control" name="date_from" id="date_from" type="date" required="" value="{{ $date_from }}">
                                        To <input class="form-control" name="date_to" id="date_to" type="date" required="" value="{{ $date_to }}">
                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <br/><br/>
                        <div class="table-responsive">
                            <table class="display" id="_datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama/NIP</th>
                                        <th>Perusahaan</th>
                                        <th>Direktorat</th>
                                        <th>Divisi</th>
                                        <th>Departemen</th>
                                        <th>Ring 1</th>
                                        <th>Ring 2</th>
                                        <th>Ring 3</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($trx as $data)
                                @php
                                $karyawan_property = json_decode($data['trx_m']->karyawan_property, true);
                                @endphp
                                    <tr>
                                       <td>{{ ++$no }}</td>
                                       <td>{{ \Carbon\Carbon::parse($data['trx_m']->tanggal_input) ->format('d-m-Y') }}</td>
                                       <td>@if(!empty($karyawan_property)){{ $karyawan_property['nama'] }} / {{ $karyawan_property['nik'] }}@endif</td>
                                       <td>@if(!empty($karyawan_property)){{ $karyawan_property['perusahaan'] }}@endif</td>
                                       <td>@if(!empty($karyawan_property)){{ $karyawan_property['divisi'] }}@endif</td>
                                       <td>@if(!empty($karyawan_property)){{ $karyawan_property['dep'] }}@endif</td>
                                       <td>@if(!empty($karyawan_property)){{ $karyawan_property['sub_dep'] }}@endif</td>
                                       <td>
                                            @foreach ($data['trx_d'] as $_data)
                                            @if($_data->ring_ke == 'ring1')
                                            Apakah anda dalam keadaan sehat hari ini? {{ $_data->ring_isian }}
                                                @if(!empty($_data->siapa))
                                                <br/>Siapa: {{ $_data->siapa }}<br/>Sakit apa: {{ $_data->sakit }}
                                                @endif
                                            @endif
                                            @endforeach
                                       </td>
                                       <td>
                                            @foreach ($data['trx_d'] as $_data)
                                            @if($_data->ring_ke == 'ring2')
                                            Orang serumah, Apakah ada yang sakit? {{ $_data->ring_isian }}
                                                @if(!empty($_data->siapa))
                                                <br/>Siapa: {{ $_data->siapa }}<br/>Sakit apa: {{ $_data->sakit }}
                                                @endif
                                            @endif
                                            @endforeach
                                       </td>
                                       <td>
                                            @foreach ($data['trx_d'] as $_data)
                                            @if($_data->ring_ke == 'ring3')
                                            Lingkungan tempat tinggal, Apakah ada yang sakit? {{ $_data->ring_isian }}
                                                @if(!empty($_data->siapa))
                                                <br/>Siapa: {{ $_data->siapa }}<br/>Sakit apa: {{ $_data->sakit }}
                                                @endif
                                            @endif
                                            @endforeach
                                       </td>
                                    </tr>
                                @endforeach
                                </tbody>
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
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/jszip.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.print.min.js') }}"></script>
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
</script>
@endsection
