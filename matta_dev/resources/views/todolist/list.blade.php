@extends('layouts.simple.master')
@section('title', 'To Do List')

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
<h3>To Do List</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">To Do List</li>
<li class="breadcrumb-item active">@if(!empty($trx[0])){{ $trx[0]->title }} @else NA @endif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('msg'))
                    <!-- <div class="alert alert-success">{{Session::get('msg')}}</div> -->
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <div class="table-responsive">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#ID</th>
                                    <th>Formulir</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    @if(request('filename') == 'emails.penilaian0')
                                        <th>Perusahaan</th>
                                    @endif
                                    <th>Unit Kerja</th>
                                    @if(request('filename') == 'emails.reimburse0')
                                        <th>Status</th>
                                    @endif
                                    <th>Created at</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trx as $data)
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $data->id_trx }}</td>
                                    <td>{{ $data->title }}</td>
                                    <td>{{ $data->nama }}</td>
                                    <td>{{ $data->jabatan }}</td>
                                    @if(request('filename') == 'emails.penilaian0')
                                        <td>{{ json_decode(App\Models\TrxPenilaian::find($data->id_trx)->karyawan_property, true)['perusahaan'] }}</td>
                                    @endif
                                    <td>{{ $data->unit_kerja }}</td>
                                    @if(request('filename') == 'emails.reimburse0')
                                        <td>{{ App\Models\ReimburseStatus::find($data->id_status)->status }}</td>
                                    @endif
                                    <td>{{ $data->created_at }}</td>
                                    <td>
                                        <a href="{{ $data->link }}" data-toggle="tooltip" title="Detail" target="_blank"><i class="icon-eye"></i></a>
                                        @if($data->filename == 'emails.bpd0')
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id_trx), 'view'=>'history']) }}" data-toggle="tooltip" title="History" target="_blank"><i class="icon-eye" style="color:red"></i></a>
                                        @elseif($data->filename == 'emails.penilaian0_outsourcing')
                                        <a href="{{ route('penilaian.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id_trx), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')]) }}" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        @elseif($data->filename == 'emails.izin0')
                                        <a href="{{ route('izin.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id_trx), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')]) }}" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        @elseif($data->filename == 'emails.pelatihan0')
                                        <a href="{{ route('pelatihan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id_trx), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')]) }}" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        @elseif($data->filename == 'emails.recruitment0' && $data->pic != 'hco')
                                        <a href="{{ route('recruitment.download_offering', Crypt::encrypt($data->id_trx)) }}" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <!-- <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>#ID</th>
                                    <th>Formulir</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Unit Kerja</th>
                                    <th>Created at</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot> -->
                        </table>
                    </div><a href="{{ URL::previous() }}" class="btn btn-warning" type="button">Back</a>
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