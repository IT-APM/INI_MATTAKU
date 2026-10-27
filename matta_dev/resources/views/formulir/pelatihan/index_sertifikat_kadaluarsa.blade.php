@extends('layouts.simple.master')

@section('title', 'Daftar Sertifikat')

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
<h3>Daftar Sertifikat</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Pelatihan</li>
<li class="breadcrumb-item active">Daftar Sertifikat</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    {{-- @if(Session::has('msg'))
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if (count($errors) > 0)
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h6>Upload File is ERROR</h6>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif --}}
                    {{-- <a href="{{ route('pelatihan_sertifikat.create') }}" class="btn btn-primary" type="button"><i class="fa fa-plus" aria-hidden="true"></i> Add new</a> |  --}}
                    <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                    <div class="collapse" id="collapse_filter">
                        <form class="mt-2" action="{{ route('pelatihan_sertifikat.kadaluarsa', 0) }}" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="h6">Status</label>&nbsp;<input type="checkbox" onClick="toggle_aktif_non(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="aktif_non">
                                        @foreach($aktif_non as $key => $value)
                                        <input class="checkbox_animated" id="aktif_non[]" name="aktif_non[]" type="checkbox" value="{{ $key }}" @if(in_array($key, $arr_aktif_non)) checked @endif> {{ $value }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Jenis Pelatihan</label>&nbsp;<input type="checkbox" onClick="toggle_jenis(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="jenis">
                                        @foreach($jenis as $data)
                                        <input class="checkbox_animated" id="jenis[]" name="jenis[]" type="checkbox" value="{{ $data->jenis }}" @if(in_array($data->jenis, $arr_jenis)) checked @endif> {{ $data->jenis }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Mode Pelatihan</label>&nbsp;<input type="checkbox" onClick="toggle_int_ext(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="int_ext">
                                        @foreach($int_ext as $key => $value)
                                        <input class="checkbox_animated" id="int_ext[]" name="int_ext[]" type="checkbox" value="{{ $key }}" @if(in_array($key, $arr_int_ext)) checked @endif> {{ $value }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">KTA</label>&nbsp;<input type="checkbox" onClick="toggle_kta(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="kta">
                                        @foreach($kta as $key => $value)
                                        <input class="checkbox_animated" id="kta[]" name="kta[]" type="checkbox" value="{{ $key }}" @if(in_array($key, $arr_kta)) checked @endif> {{ $value }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="{{ route('pelatihan_sertifikat.kadaluarsa', 0) }}" class="btn btn-info" type="button"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- <div class="col-sm-6 mt-2">
                        <h6>Upload Sertifikat</h6>
                        <form action="{{ route('pelatihan_sertifikat.upload_file', 0) }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to upload this data?');">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-8">
                                <input name="template" id="template" class="form-control" type="file" required="">
                                <label class="form-label">Maksimal 2 MB dengan format xls/xlsx</label>
                                <br/><a href="{{ asset('assets/template/template_import_sertifikat_v1.1.xlsx') }}" target="_blank"><i class="icon-file"></i>&nbsp;Download Template</a>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload</button>
                            </div>
                        </div>
                        </form>
                    </div> --}}
                    <div class="table-responsive">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Action</th>
                                    <th>Status</th>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>Jenis Pelatihan</th>
                                    <th>Mode Pelatihan</th>
                                    <th>Penerbit</th>
                                    <th>Kartu Tanda Anggota</th>
                                    <th>Judul Pelatihan</th>
                                    <th>Lembaga</th>
                                    <th>Biaya</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Akhir</th>
                                    <th>No Sertifikat</th>
                                    <th>No Kartu Lisensi</th>
                                    <th>No Kartu Anggota</th>
                                    <th>Tanggal Sertifikat</th>
                                    <th>Tanggal Kedaluwarsa</th>
                                    <th>Sertifikat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trx as $data)
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            {{-- jika ada id pelatihan --}}
                                            @if(!empty($data->id_trx))
                                            <a href="{{ route('pelatihan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id_trx), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')]) }}" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                            @else
                                                {{-- <a href="{{ route('pelatihan_sertifikat.edit', (new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a> --}}
                                                {{-- jika tidak ada id pelatihan maka gunakan link dari gdrive --}}
                                                @if(!empty($data->formulir_link))
                                                <a href="{{ $data->formulir_link }}" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                                @endif
                                            @endif
                                            {{-- <form action="{{ route('pelatihan_sertifikat.destroy', $data->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <ul class="action">
                                                <li class="delete"><a href="#" id="btn-delete" name="btn-delete" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></a></li>
                                            </ul>
                                            </form> --}}
                                        </div>
                                    </td>
                                    @php $is_status = App\Models\Karyawan::where(['nik'=>$data->nik, 'is_aktif'=>1])->exists(); @endphp
                                    <td>@if ($is_status) <span class="badge badge-success">Aktif</span> @else <span class="badge badge-danger">Non Aktif</span> @endif</td>
                                    <td>{{ $data->nama }}</td>
                                    <td>{{ $data->nik }}</td>
                                    <td>{{ $data->jenis }}</td>
                                    <td>{{ $data->int_ext }}</td>
                                    <td>{{ $data->penerbit }}</td>
                                    <td>{{ $data->kta }}</td>
                                    <td>{{ $data->judul }}@if(!empty($data->int_ext))<br/> ({{ $data->int_ext }}) @endif</td>
                                    <td>{{ $data->lembaga }}</td>
                                    <td>Rp.{{ number_format($data->biaya, 0) }}</td>
                                    <td>
                                        @if(!empty($data->tanggal_mulai) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data->tanggal_mulai))
                                        {{ Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggal_mulai)->format('d-m-Y') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($data->tanggal_akhir) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data->tanggal_akhir))
                                        {{ Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggal_akhir)->format('d-m-Y') }}
                                        @endif
                                    </td>
                                    <td>{{ $data->nomor_sertifikat }}</td>
                                    <td>{{ $data->no_kartu_lisensi }}</td>
                                    <td>{{ $data->no_kartu_anggota }}</td>
                                    <td>
                                        @if(!empty($data->tanggal_sertifikat) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data->tanggal_sertifikat))
                                        {{ Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggal_sertifikat)->format('d-m-Y') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($data->tanggal_kadaluarsa) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data->tanggal_kadaluarsa))
                                        {{ Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggal_kadaluarsa)->format('d-m-Y') }}
                                        @endif
                                    </td>
                                    <td>@if(!empty($data->sertifikat_link))<a href="{{ $data->sertifikat_link }}" data-toggle="tooltip" title="Sertifikat" target="_blank"><i class="fa fa-file-text-o"></i></a>@endif</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('pelatihan.index') }}" class="btn btn-warning mt-2" type="button">Back</a>
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
function toggle_aktif_non(source) {
    checkboxes = document.getElementsByName('aktif_non[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_jenis(source) {
    checkboxes = document.getElementsByName('jenis[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_int_ext(source) {
    checkboxes = document.getElementsByName('int_ext[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_kta(source) {
    checkboxes = document.getElementsByName('kta[]');
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
$(document).on('click', '#btn-delete', function(event) {
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