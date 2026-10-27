@extends('layouts.simple.master')
@section('title', 'title')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/datatable/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/datatable/css/buttons.dataTables.min.css') }}"> -->
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
<h3>Perubahan Data Karyawan</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Perubahan</li>
<li class="breadcrumb-item active">Data Karyawan</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Zero Configuration  Starts-->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('error'))
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('error') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if(Session::has('msg'))
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="{{ route('karyawan.bulanan', 0) }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
                    <div class="collapse mt-2 mb-2" id="collapse_filter">
                        <form action="{{ route('karyawan.bulanan', 1) }}" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="h6">Perusahaan</label>&nbsp;<input type="checkbox" onClick="toggle_perusahaan(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="perusahaan">
                                        @foreach ($perusahaan as $key => $data)
                                        <input class="checkbox_animated" id="perusahaan[]" name="perusahaan[]" type="checkbox" value="{{ $key }}" @if(in_array($data, $arr_perusahaan)) checked @endif> {{ $data }}<br>
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Direktorat</label>&nbsp;<input type="checkbox" onClick="toggle_divisi(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="divisi">
                                        @foreach ($divisi as $key => $data)
                                        <input class="checkbox_animated" id="divisi[]" name="divisi[]" type="checkbox" value="{{ $key }}" @if(in_array($data, $arr_divisi)) checked @endif> {{ $data }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Divisi</label>&nbsp;<input type="checkbox" onClick="toggle_dep(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="dep">
                                        @foreach ($dep as $key => $data)
                                        <input class="checkbox_animated" id="dep[]" name="dep[]" type="checkbox" value="{{ $key }}" @if(in_array($data, $arr_dep)) checked @endif> {{ $data }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Departemen</label>&nbsp;<input type="checkbox" onClick="toggle_sub_dep(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="sub_dep">
                                        @foreach ($sub_dep as $key => $data)
                                        <input class="checkbox_animated" id="sub_dep[]" name="sub_dep[]" type="checkbox" value="{{ $key }}" @if(in_array($data, $arr_sub_dep)) checked @endif> {{ $data }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="{{ route('karyawan.bulanan', 1) }}" class="btn btn-info"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="display nowrap" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Periode</th>
                                    <th>NIP Karyawan</th>
                                    <th>Nama Karyawan</th>
                                    <th>Perusahaan</th>
                                    <th>Direktorat</th>
                                    <th>Divisi</th>
                                    <th>Departemen</th>
                                    <th>Level</th>
                                    <th>Jabatan</th>
                                    <th>Grup Kerja</th>
                                    <th>Status Kerja</th>
                                    <th>NIP Layer1</th>
                                    <th>Nama Layer1</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 0; @endphp
                                @foreach ($trx as $data)
                                @php
                                $nik_property = json_decode($data->nik_property, true);
                                $layer1_property = json_decode($data->layer1_property, true);
                                @endphp
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ Carbon\Carbon::parse($data->tanggal)->locale('id')->isoFormat('MMMM YYYY') }}</td>
                                    <td>{{ $nik_property['nik'] }}</td>
                                    <td>
                                        {{ $nik_property['nama'] }}
                                        @if(!empty($data->perubahan))
                                        <br/><span style="background-color:#ffc107;">{{ nl2br($data->perubahan) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $nik_property['perusahaan'] }}</td>
                                    <td>{{ $nik_property['divisi'] }}</td>
                                    <td>{{ $nik_property['dep'] }}</td>
                                    <td>{{ $nik_property['sub_dep'] }}</td>
                                    <td>{{ $nik_property['level'] }}</td>
                                    <td>{{ $nik_property['jabatan'] }}</td>
                                    <td>{{ $nik_property['wg_name'] }}</td>
                                    <td>{{ $nik_property['status_karyawan'] }}</td>
                                    <td>{{ $layer1_property['nik'] }}</td>
                                    <td>{{ $layer1_property['nama'] }}</td>
                                </tr>
                                {{-- @if(!empty($data->perubahan))
                                <tr style="background-color:#ffc107;">
                                    <td colspan="2">Perubahan</td>
                                    <td colspan="12">{{ nl2br($data->perubahan) }}</td>
                                </tr>
                                @endif --}}
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
{{--  --}}
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
});
// 
function toggle_perusahaan(source) {
    checkboxes = document.getElementsByName('perusahaan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function toggle_divisi(source) {
    checkboxes = document.getElementsByName('divisi[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function toggle_dep(source) {
    checkboxes = document.getElementsByName('dep[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function toggle_sub_dep(source) {
    checkboxes = document.getElementsByName('sub_dep[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
</script>
@endsection