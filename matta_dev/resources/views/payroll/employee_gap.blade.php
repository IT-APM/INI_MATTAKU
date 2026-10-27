@extends('layouts.simple.master')

@section('title', 'Payroll')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}"> --}}
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
<h3>Employee Gap</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Payroll</li>
<li class="breadcrumb-item active">Employee Gap</li>
@endsection
@php
$bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
@endphp
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                        </div>
                    </div>
                    <div class="collapse mt-2" id="collapse_filter">
                        <form action="{{ route('payroll.employee_gap', 0) }}" method="get" enctype="multipart/form-data" class="needs-validation" novalidate="">
                            <div class="row">
                                <div class="col-sm-2">
                                    <label class="h6" for="nama">Bulan-Tahun</label>
                                    <div>
                                        <input name="bulan_tahun_dari" id="bulan_tahun_dari" class="form-control digits" type="month" required="" value="{{ $bulan_tahun_dari }}">
                                        <div class="invalid-feedback txt-danger">Field is required.</div>
                                    </div>
                                    sampai
                                    <div>
                                        <input name="bulan_tahun_sampai" id="bulan_tahun_sampai" class="form-control digits" type="month" required="" value="{{ $bulan_tahun_sampai }}">
                                        <div class="invalid-feedback txt-danger">Field is required.</div>
                                    </div>
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
                        <table class="display nowrap" id="_datatable1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tahun</th>
                                    <th>Bulan</th>
                                    <th>Masuk</th>
                                    <th>Keluar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no=0; @endphp
                                @foreach ($result as $data)
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $data['tahun'] }}</td>
                                    <td>{{ $bulan[$data['bulan'] - 1] }}</td>
                                    <td>
                                        @if($data['tahun'] == 2022 && $data['bulan'] == 1)
                                            <span class="badge badge-primary" style="font-size:12px;">#0</span>
                                        @else
                                            @php $arr_nik = array(); @endphp
                                            @forelse ($data['after'] as $_after)
                                                @php
                                                    $nik_property_after = json_decode($_after->nik_property, true);
                                                    array_push($arr_nik, $nik_property_after['nama'].'/'.$nik_property_after['nik']);
                                                @endphp
                                                {{-- <span class="badge badge-success mb-1" style="font-size:12px;">{{ $nik_property_after['nama'] }}/{{ $nik_property_after['nik'] }}</span><br/> --}}
                                            @empty
                                                <span class="badge badge-primary" style="font-size:12px;">#0</span>
                                            @endforelse
                                            @if(count($arr_nik)>0)
                                                @php sort($arr_nik); @endphp
                                                @foreach($arr_nik as $_data)
                                                    <span class="badge badge-success mb-1" style="font-size:12px;">{{ $_data }}</span><br/>
                                                @endforeach
                                                <span class="badge badge-success mb-1" style="font-size:12px;">#{{ count($arr_nik) }}</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @php $arr_nik = array(); @endphp
                                        @forelse ($data['before'] as $_before)
                                            @php
                                                $nik_property_before = json_decode($_before->nik_property, true);
                                                array_push($arr_nik, $nik_property_before['nama'].'/'.$nik_property_before['nik']);
                                            @endphp
                                            {{-- <span class="badge badge-danger mb-1" style="font-size:12px;">{{ $nik_property_before['nama'] }}/{{ $nik_property_before['nik'] }}</span><br/> --}}
                                        @empty
                                            <span class="badge badge-primary" style="font-size:12px;">#0</span>
                                        @endforelse
                                        @if(count($arr_nik)>0)
                                            @php sort($arr_nik); @endphp
                                            @foreach($arr_nik as $_data)
                                                <span class="badge badge-danger mb-1" style="font-size:12px;">{{ $_data }}</span><br/>
                                            @endforeach
                                            <span class="badge badge-danger mb-1" style="font-size:12px;">#{{ count($arr_nik) }}</span>
                                        @endif
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
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
{{-- <script src="{{ asset('assets/js/sweet-alert/sweetalert.minjs') }}"></script>
<script src="{{ asset('assets/js/sweet-alert/appjs') }}"></script> --}}
<script type="text/javascript">
// semua
$('#_datatable1').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]]
} );
</script>
@endsection