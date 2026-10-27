@extends('layouts.simple.master')

@section('title', 'Formulir Pengajuan Pelatihan')

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
<h3>Formulir Pengajuan Pelatihan</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Pengajuan Pelatihan</li>
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
                    <a href="{{ route('pelatihan.create') }}" class="btn btn-primary" type="button"><i class="fa fa-plus" aria-hidden="true"></i> Add new</a> | <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> @can('hc') | <a href="{{ route('pelatihan_sertifikat.index') }}" class="btn btn-primary" type="button"><i class="fa fa-file-text-o" aria-hidden="true"></i> Certificates</a> | <a href="{{ route('pelatihan_sertifikat.kadaluarsa', 0) }}" class="btn btn-danger" type="button"><i class="fa fa-file-text-o" aria-hidden="true"></i> Expired Certificates</a> @endcan
                    <div class="collapse" id="collapse_filter">
                        <form class="mt-2" action="{{ route('pelatihan.index') }}" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="h6">Tanggal</label>
                                    <input class="form-control" name="tanggal_mulai" id="tanggal_mulai" type="date" required="" value="{{ $tanggal_mulai }}">
                                    sampai
                                    <input class="form-control" name="tanggal_akhir" id="tanggal_akhir" type="date" required="" value="{{ $tanggal_akhir }}">
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Status Pengajuan</label>&nbsp;<input type="checkbox" onClick="toggle_status_pengajuan(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="status_pengajuan">
                                        @foreach($status_pengajuan as $data)
                                        <input class="checkbox_animated" id="status_pengajuan[]" name="status_pengajuan[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_status_pengajuan)) checked @endif> {{ $data->status_pengajuan }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br/><br/>
                    <div class="table-responsive">
                        <table class="display nowrap" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#ID</th>
                                    <th>Action</th>
                                    <th>Pengaju</th>
                                    <th>NIP Pengaju</th>
                                    <th>Layer 1 Pengaju</th>
                                    <th>NIP Layer 1 Pengaju</th>
                                    <th>Peserta</th>
                                    <th>Layer 1 Peserta</th>
                                    <th>Approval</th>
                                    <th>Pelatihan</th>
                                    <th>Biaya</th>
                                    <th>Status Pengajuan</th>
                                    <th>Created at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trx as $data)
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $data->id }}</td>
                                    <td>
                                        <a href="{{ route('pelatihan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('detail')]) }}" data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        &nbsp;<a href="{{ route('pelatihan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')]) }}" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        @can('hc')
                                        <br/><a href="{{ route('pelatihan.edit', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)]) }}" data-toggle="tooltip" title='Update'><i class="icon-pencil-alt"></i></a>
                                        &nbsp;<a href="{{ route('pelatihan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('peserta'), 'back_url' => url()->full()]) }}" data-toggle="tooltip" title="Sertifikat"><i class="fa fa-file-text-o"></i></a>
                                        @endcan
                                    </td>
                                    <td>{{ $data->_pengaju->nama }}</td>
                                    <td>{{ $data->pengaju }}</td>
                                    <td>{{ $data->_atasan->nama }}</td>
                                    <td>{{ $data->atasan_pengaju }}</td>
                                    <td>
                                        @foreach(explode(';', $data->peserta_property) as $value)
                                            {{ json_decode($value, true)['nama'] }} @if(!$loop->last) <br/> @endif
                                        @endforeach
                                    </td>
                                    @php
                                        $names = collect(explode(';', $data->atasan_peserta_property))->map(fn($v) => json_decode($v, true))->map(fn($v) => ['nama' => $v['nama'], 'nik' => $v['nik']])->unique('nik')->sortBy('nama')->values();
                                    @endphp
                                    <td>
                                        @foreach($names as $nama)
                                            {{ $nama['nama'] }} @if(!$loop->last) <br/> @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        <table style="border-collapse: collapse; width: 100%;">
                                            <tr>
                                                <th style="padding: 2px 4px;">Otoritas</th>
                                                <th style="padding: 2px 4px;">Nama</th>
                                                <th style="padding: 2px 4px;">Waktu</th>
                                            </tr>
                                            @php
                                                // L1 Requestor (2,3)
                                                $log = App\Models\TrxPelatihanLog::where(['id_trx'=>$data->id, 'nik_pic'=>$data->atasan_pengaju])->whereIn('id_status', [2,3])->orderBy('id', 'desc');
                                            @endphp
                                            <tr @if($log->exists()) class="bg-success" @else class="bg-danger" @endif>
                                                <td style="padding: 2px 4px;">Layer 1 Pengaju</td>
                                                <td style="padding: 2px 4px;">{{ $data->_atasan->nama }}</td>
                                                <td style="padding: 2px 4px;">
                                                    @if($log->exists())
                                                        {{ $log->get()[0]->created_at }}
                                                    @endif
                                                </td>
                                            </tr>
                                            @foreach($names as $nama)
                                            @php
                                                // L1 Participants (5,6)
                                                $log = App\Models\TrxPelatihanLog::where(['id_trx'=>$data->id, 'nik_pic'=>$nama['nik']])->whereIn('id_status', [5,6])->orderBy('id', 'desc');
                                            @endphp
                                            <tr @if($log->exists()) class="bg-success" @else class="bg-danger" @endif>
                                                <td style="padding: 2px 4px;">Layer 1 Peserta</td>
                                                <td style="padding: 2px 4px;">{{ $nama['nama'] }}</td>
                                                <td style="padding: 2px 4px;">
                                                    @if($log->exists())
                                                        {{ $log->get()[0]->created_at }}
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                            @if(!empty($data->reviewer1))
                                            @php
                                                // Reviewer 1 (10,8)
                                                $log = App\Models\TrxPelatihanLog::where(['id_trx'=>$data->id, 'nik_pic'=>$data->reviewer1])->whereIn('id_status', [10,8])->orderBy('id', 'desc');
                                            @endphp
                                            <tr @if($log->exists()) class="bg-success" @else class="bg-danger" @endif>
                                                <td style="padding: 2px 4px;">Reviewer 1</td>
                                                <td style="padding: 2px 4px;">{{ $data->_reviewer1->nama }}</td>
                                                <td style="padding: 2px 4px;">
                                                    @if($log->exists())
                                                        {{ $log->get()[0]->created_at }}
                                                    @endif
                                                </td>
                                            </tr>
                                            @endif
                                            @if(!empty($data->reviewer2))
                                            @php
                                                // Reviewer 2 (14,12)
                                                $log = App\Models\TrxPelatihanLog::where(['id_trx'=>$data->id, 'nik_pic'=>$data->reviewer2])->whereIn('id_status', [14,12])->orderBy('id', 'desc');
                                            @endphp
                                            <tr @if($log->exists()) class="bg-success" @else class="bg-danger" @endif>
                                                <td style="padding: 2px 4px;">Reviewer 2</td>
                                                <td style="padding: 2px 4px;">{{ $data->_reviewer2->nama }}</td>
                                                <td style="padding: 2px 4px;">
                                                    @if($log->exists())
                                                        {{ $log->get()[0]->created_at }}
                                                    @endif
                                                </td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                    <td>{{ $data->judul }}</td>
                                    <td>Rp.{{ number_format($data->biaya, 0) }}</td>
                                    <td>{{ $data->_status->status_pengajuan }}</td>
                                    <td>{{ $data->created_at }}</td>
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
function toggle_status_pengajuan(source) {
    checkboxes = document.getElementsByName('status_pengajuan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_pengaju(source) {
    checkboxes = document.getElementsByName('pengaju[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_atasan_pengaju(source) {
    checkboxes = document.getElementsByName('atasan_pengaju[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
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