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
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#ID</th>
                                    <th>Formulir</th>
                                    <th>Nama</th>
                                    @if(in_array($trx[0]->filename, ['emails.penilaian0','emails.penilaian0_outsourcing']))
                                    <th>NIP</th>
                                    @endif
                                    <th>Jabatan</th>
                                    <th>Unit Kerja</th>
                                    @if(in_array($trx[0]->filename, ['emails.penilaian0','emails.penilaian0_outsourcing']))
                                    <th>Nilai Total Penilai 1</th>
                                    @endif
                                    <th>Created at</th>
                                    <th>Link</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no=0 @endphp
                                @foreach ($trx as $data)
                                @php
                                    $penilaian = App\Models\TrxPenilaian::find($data->id_trx);
                                    // Nilai Total Penilai 1
                                    $_where = ['id_trx' => $data->id_trx];
                                    $skor_penilai_1 = App\Models\TrxPenilaianSkor::where($_where)->sum('skor_penilai_1');
                                @endphp
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $data->id_trx }}</td>
                                    <td>{{ $data->title }}</td>
                                    <td>{{ $data->nama }}</td>
                                    @if(in_array($data->filename, ['emails.penilaian0','emails.penilaian0_outsourcing']))
                                    <td>{{ $penilaian->nik_karyawan }}</td>
                                    @endif
                                    <td>{{ $data->jabatan }}</td>
                                    <td>{{ $data->unit_kerja }}</td>
                                    @if(in_array($data->filename, ['emails.penilaian0','emails.penilaian0_outsourcing']))
                                    <td>{{ $skor_penilai_1 }}</td>
                                    @endif
                                    <td>{{ $data->created_at }}</td>
                                    <td><a href="{{ $data->link }}" data-toggle="tooltip" title="Link" target="_blank"><i class="icon-eye"></i></a></td>
                                    <td>{{ $data->link }}</td>
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